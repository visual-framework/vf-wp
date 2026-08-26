<?php
/**
 * Machine-readable PDF text extraction using server-local tools.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_PDF_Extractor {
	const DEFAULT_MAX_FILE_SIZE = 52428800;
	const DEFAULT_MAX_TEXT_BYTES = 1048576;
	const DEFAULT_TIMEOUT = 30;

	/**
	 * @var string
	 */
	private $ghostscript_binary;

	/**
	 * @param string|null $ghostscript_binary Ghostscript binary path.
	 */
	public function __construct($ghostscript_binary = null) {
		$this->ghostscript_binary = $ghostscript_binary ? $ghostscript_binary : $this->detect_ghostscript_binary();
	}

	/**
	 * Determine whether PDF text extraction is available.
	 *
	 * @return bool
	 */
	public function is_available() {
		return $this->ghostscript_binary !== '' && function_exists('proc_open');
	}

	/**
	 * Extract machine-readable text from a PDF.
	 *
	 * @param string $file_path Absolute file path.
	 * @return array
	 */
	public function extract($file_path) {
		$file_path = (string) $file_path;

		if (!$this->is_available()) {
			return $this->result('failed', '', __('Ghostscript text extraction is not available.', 'vfwp'));
		}

		if ($file_path === '' || !file_exists($file_path)) {
			return $this->result('missing_file', '', __('PDF file is missing.', 'vfwp'));
		}

		if (!is_readable($file_path)) {
			return $this->result('not_readable', '', __('PDF file is not readable.', 'vfwp'));
		}

		$file_size = filesize($file_path);
		$max_file_size = (int) apply_filters('vfwp_intranet_search_pdf_max_file_size', self::DEFAULT_MAX_FILE_SIZE);

		if ($file_size !== false && $file_size > $max_file_size) {
			return $this->result('too_large', '', sprintf(__('PDF exceeds the configured extraction size limit of %d bytes.', 'vfwp'), $max_file_size));
		}

		$output_file = tempnam(get_temp_dir(), 'vfwp-pdf-text-');

		if (!$output_file) {
			return $this->result('failed', '', __('Could not create a temporary extraction file.', 'vfwp'));
		}

		$command = array(
			$this->ghostscript_binary,
			'-q',
			'-dSAFER',
			'-dNOPAUSE',
			'-dBATCH',
			'-sDEVICE=txtwrite',
			'-sOutputFile=' . $output_file,
			$file_path,
		);

		$process_result = $this->run_command($command, (int) apply_filters('vfwp_intranet_search_pdf_extraction_timeout', self::DEFAULT_TIMEOUT));
		$text = '';
		$status = 'failed';
		$error = '';

		if ($process_result['timed_out']) {
			$status = 'timeout';
			$error = __('PDF text extraction timed out.', 'vfwp');
		} elseif ($process_result['exit_code'] !== 0) {
			$status = $this->detect_error_status($process_result['stderr']);
			$error = $process_result['stderr'] !== '' ? $process_result['stderr'] : __('PDF text extraction failed.', 'vfwp');
		} else {
			$text = $this->read_extracted_text($output_file);

			if ($text === '') {
				$status = 'no_text';
				$error = __('No machine-readable text was found in this PDF.', 'vfwp');
			} else {
				$status = $this->was_text_truncated($output_file) ? 'success_truncated' : 'success';
			}
		}

		if (file_exists($output_file)) {
			wp_delete_file($output_file);
		}

		return $this->result($status, $text, $this->sanitize_error($error, $file_path));
	}

	/**
	 * Read bounded extracted text.
	 *
	 * @param string $output_file Temporary output file.
	 * @return string
	 */
	private function read_extracted_text($output_file) {
		if (!file_exists($output_file) || !is_readable($output_file)) {
			return '';
		}

		$max_text_bytes = (int) apply_filters('vfwp_intranet_search_pdf_max_text_bytes', self::DEFAULT_MAX_TEXT_BYTES);
		$text = file_get_contents($output_file, false, null, 0, $max_text_bytes);

		return is_string($text) ? $text : '';
	}

	/**
	 * Detect if text output exceeded the configured indexed text limit.
	 *
	 * @param string $output_file Temporary output file.
	 * @return bool
	 */
	private function was_text_truncated($output_file) {
		$file_size = file_exists($output_file) ? filesize($output_file) : false;
		$max_text_bytes = (int) apply_filters('vfwp_intranet_search_pdf_max_text_bytes', self::DEFAULT_MAX_TEXT_BYTES);

		return $file_size !== false && $file_size > $max_text_bytes;
	}

	/**
	 * Run a process with a timeout.
	 *
	 * @param array $command Command argv.
	 * @param int   $timeout Timeout seconds.
	 * @return array
	 */
	private function run_command(array $command, $timeout) {
		$descriptors = array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$process = proc_open($command, $descriptors, $pipes);

		if (!is_resource($process)) {
			return array(
				'exit_code' => 1,
				'timed_out' => false,
				'stdout'    => '',
				'stderr'    => __('Could not start Ghostscript.', 'vfwp'),
			);
		}

		foreach ($pipes as $pipe) {
			stream_set_blocking($pipe, false);
		}

		$stdout = '';
		$stderr = '';
		$started_at = time();
		$timed_out = false;

		while (true) {
			$status = proc_get_status($process);
			$stdout .= stream_get_contents($pipes[1]);
			$stderr .= stream_get_contents($pipes[2]);

			if (!$status['running']) {
				break;
			}

			if ((time() - $started_at) >= $timeout) {
				$timed_out = true;
				proc_terminate($process);
				break;
			}

			usleep(100000);
		}

		$stdout .= stream_get_contents($pipes[1]);
		$stderr .= stream_get_contents($pipes[2]);

		foreach ($pipes as $pipe) {
			fclose($pipe);
		}

		$exit_code = proc_close($process);

		return array(
			'exit_code' => $exit_code,
			'timed_out' => $timed_out,
			'stdout'    => $stdout,
			'stderr'    => $stderr,
		);
	}

	/**
	 * Detect common PDF extraction failures.
	 *
	 * @param string $stderr Error output.
	 * @return string
	 */
	private function detect_error_status($stderr) {
		$stderr = strtolower((string) $stderr);

		if (strpos($stderr, 'password') !== false || strpos($stderr, 'encrypted') !== false) {
			return 'password_protected';
		}

		return 'failed';
	}

	/**
	 * Build an extraction result.
	 *
	 * @param string $status Status.
	 * @param string $text Extracted text.
	 * @param string $error Error.
	 * @return array
	 */
	private function result($status, $text, $error) {
		return array(
			'status' => $status,
			'text'   => (string) $text,
			'error'  => (string) $error,
			'method' => 'ghostscript_txtwrite',
		);
	}

	/**
	 * Remove sensitive filesystem paths from stored errors.
	 *
	 * @param string $error Raw error.
	 * @param string $file_path Source file path.
	 * @return string
	 */
	private function sanitize_error($error, $file_path) {
		$error = trim(wp_strip_all_tags((string) $error));

		if ($error === '') {
			return '';
		}

		$error = str_replace($file_path, basename($file_path), $error);
		$error = preg_replace('/\s+/u', ' ', $error);

		return mb_substr($error, 0, 1000);
	}

	/**
	 * Detect Ghostscript binary from the current server environment.
	 *
	 * @return string
	 */
	private function detect_ghostscript_binary() {
		$candidates = array('/usr/bin/gs', '/usr/local/bin/gs');

		foreach ($candidates as $candidate) {
			if (is_executable($candidate)) {
				return $candidate;
			}
		}

		return '';
	}
}
