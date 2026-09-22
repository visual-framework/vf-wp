<?php
/**
 * Dependency-free DOCX text extraction using PHP ZIP and XML extensions.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_DOCX_Extractor {
	const DEFAULT_MAX_FILE_SIZE = 52428800;
	const DEFAULT_MAX_XML_BYTES = 16777216;
	const DEFAULT_MAX_TEXT_BYTES = 1048576;
	const EXTRACTOR_VERSION = 'native_docx_v1';

	/**
	 * Determine whether DOCX text extraction is available.
	 *
	 * @return bool
	 */
	public function is_available() {
		return class_exists('ZipArchive') && class_exists('DOMDocument') && class_exists('DOMXPath');
	}

	/**
	 * Extract text from a DOCX file without external services or binaries.
	 *
	 * @param string $file_path Absolute file path.
	 * @return array
	 */
	public function extract($file_path) {
		$file_path = (string) $file_path;

		if (!$this->is_available()) {
			return $this->result('unavailable', '', __('DOCX extraction requires the PHP ZIP and DOM extensions.', 'vfwp'));
		}

		if ($file_path === '' || !file_exists($file_path)) {
			return $this->result('missing_file', '', __('DOCX file is missing.', 'vfwp'));
		}

		if (!is_readable($file_path)) {
			return $this->result('not_readable', '', __('DOCX file is not readable.', 'vfwp'));
		}

		$file_size = filesize($file_path);
		$max_file_size = (int) apply_filters('vfwp_intranet_search_docx_max_file_size', self::DEFAULT_MAX_FILE_SIZE);

		if ($file_size !== false && $max_file_size > 0 && $file_size > $max_file_size) {
			return $this->result('too_large', '', sprintf(__('DOCX exceeds the configured extraction size limit of %d bytes.', 'vfwp'), $max_file_size));
		}

		$zip = new ZipArchive();
		$opened = $zip->open($file_path);

		if ($opened !== true) {
			return $this->result('failed', '', __('Could not open the DOCX package.', 'vfwp'));
		}

		if ($zip->locateName('[Content_Types].xml') === false || $zip->locateName('word/document.xml') === false) {
			$zip->close();

			return $this->result('failed', '', __('File does not contain a valid DOCX document package.', 'vfwp'));
		}

		$part_names = $this->get_text_part_names($zip);
		$max_xml_bytes = (int) apply_filters('vfwp_intranet_search_docx_max_xml_bytes', self::DEFAULT_MAX_XML_BYTES);
		$total_xml_bytes = 0;
		$text_parts = array();

		foreach ($part_names as $part_name) {
			$stat = $zip->statName($part_name);
			$part_size = is_array($stat) && isset($stat['size']) ? (int) $stat['size'] : 0;

			if ($part_size < 0 || ($max_xml_bytes > 0 && $total_xml_bytes + $part_size > $max_xml_bytes)) {
				$zip->close();

				return $this->result('too_large', '', __('DOCX document XML exceeds the configured extraction limit.', 'vfwp'));
			}

			$xml = $zip->getFromName($part_name);

			if (!is_string($xml)) {
				continue;
			}

			$total_xml_bytes += strlen($xml);

			if ($max_xml_bytes > 0 && $total_xml_bytes > $max_xml_bytes) {
				$zip->close();

				return $this->result('too_large', '', __('DOCX document XML exceeds the configured extraction limit.', 'vfwp'));
			}

			$part_text = $this->extract_xml_part_text($xml);

			if ($part_text !== '') {
				$text_parts[] = $part_text;
			}
		}

		$zip->close();
		$text = trim(implode("\n", $text_parts));

		if ($text === '') {
			return $this->result('no_text', '', __('No readable text was found in this DOCX file.', 'vfwp'));
		}

		$max_text_bytes = (int) apply_filters('vfwp_intranet_search_docx_max_text_bytes', self::DEFAULT_MAX_TEXT_BYTES);
		$truncated = $max_text_bytes > 0 && strlen($text) > $max_text_bytes;

		if ($truncated) {
			$text = substr($text, 0, $max_text_bytes);
		}

		return $this->result($truncated ? 'success_truncated' : 'success', $text, '');
	}

	/**
	 * Return Word XML parts that contain visible document text.
	 *
	 * @param ZipArchive $zip Open DOCX package.
	 * @return array
	 */
	private function get_text_part_names(ZipArchive $zip) {
		$names = array('word/document.xml');
		$optional = array('word/footnotes.xml', 'word/endnotes.xml');

		foreach ($optional as $part_name) {
			if ($zip->locateName($part_name) !== false) {
				$names[] = $part_name;
			}
		}

		$headers_and_footers = array();
		$entry_limit = min((int) $zip->numFiles, 10000);

		for ($index = 0; $index < $entry_limit; $index++) {
			$name = $zip->getNameIndex($index);

			if (is_string($name) && preg_match('#^word/(?:header|footer)[0-9]+\.xml$#', $name)) {
				$headers_and_footers[] = $name;
			}
		}

		sort($headers_and_footers, SORT_NATURAL);

		return array_values(array_unique(array_merge($names, $headers_and_footers)));
	}

	/**
	 * Extract paragraphs, tables, tabs, and line breaks from one Word XML part.
	 *
	 * @param string $xml WordprocessingML.
	 * @return string
	 */
	private function extract_xml_part_text($xml) {
		$previous_errors = libxml_use_internal_errors(true);
		$document = new DOMDocument();
		$loaded = $document->loadXML((string) $xml, LIBXML_NONET | LIBXML_COMPACT | LIBXML_NOERROR | LIBXML_NOWARNING);
		libxml_clear_errors();
		libxml_use_internal_errors($previous_errors);

		if (!$loaded) {
			return '';
		}

		$xpath = new DOMXPath($document);
		$xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
		$paragraphs = $xpath->query('//w:p');

		if (!$paragraphs instanceof DOMNodeList) {
			return '';
		}

		$lines = array();

		foreach ($paragraphs as $paragraph) {
			$nodes = $xpath->query('.//w:t | .//w:tab | .//w:br | .//w:cr', $paragraph);
			$line = '';

			if ($nodes instanceof DOMNodeList) {
				foreach ($nodes as $node) {
					if ($node->localName === 't') {
						$line .= $node->textContent;
					} elseif ($node->localName === 'tab') {
						$line .= "\t";
					} else {
						$line .= "\n";
					}
				}
			}

			$line = trim($line);

			if ($line !== '') {
				$lines[] = $line;
			}
		}

		return implode("\n", $lines);
	}

	/**
	 * Build a normalized extraction result.
	 *
	 * @param string $status Status.
	 * @param string $text Extracted text.
	 * @param string $error Error message.
	 * @return array
	 */
	private function result($status, $text, $error) {
		return array(
			'status' => (string) $status,
			'text'   => (string) $text,
			'error'  => (string) $error,
			'method' => self::EXTRACTOR_VERSION,
		);
	}
}
