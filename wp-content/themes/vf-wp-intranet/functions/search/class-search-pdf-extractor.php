<?php
/**
 * Best-effort, dependency-free PDF text extraction.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_PDF_Extractor {
	const DEFAULT_MAX_FILE_SIZE = 52428800;
	const DEFAULT_MAX_TEXT_BYTES = 1048576;
	const EXTRACTOR_VERSION = 'pure_php_v5';

	/**
	 * Determine whether PDF text extraction is available.
	 *
	 * @return bool
	 */
	public function is_available() {
		return true;
	}

	/**
	 * Extract machine-readable text from a PDF without external binaries.
	 *
	 * @param string $file_path Absolute file path.
	 * @return array
	 */
	public function extract($file_path) {
		$file_path = (string) $file_path;

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

		$pdf = file_get_contents($file_path);

		if (!is_string($pdf) || $pdf === '') {
			return $this->result('failed', '', __('Could not read PDF file.', 'vfwp'));
		}

		if (strpos($pdf, '%PDF-') === false) {
			return $this->result('failed', '', __('File does not look like a PDF.', 'vfwp'));
		}

		if (preg_match('/\/Encrypt\b/', $pdf)) {
			return $this->result('password_protected', '', __('Encrypted or password-protected PDFs cannot be extracted by the pure PHP extractor.', 'vfwp'));
		}

		$text = $this->extract_text_from_pdf($pdf);
		$text = $this->normalize_output_text($text);

		if ($text === '') {
			return $this->result('no_text', '', __('No machine-readable text was found in this PDF. Scanned-image PDFs need OCR and are not extracted by this system.', 'vfwp'));
		}

		$status = $this->was_text_truncated($text) ? 'success_truncated' : 'success';
		$text = $this->limit_text($text);

		return $this->result($status, $text, '');
	}

	/**
	 * Extract text from decoded PDF streams and fallback raw content.
	 *
	 * @param string $pdf PDF bytes.
	 * @return string
	 */
	private function extract_text_from_pdf($pdf) {
		$text_parts = array();
		$streams = $this->extract_streams($pdf);

		foreach ($streams as $stream) {
			$stream_text = $this->extract_text_from_content_stream($stream);

			if ($stream_text !== '') {
				$text_parts[] = $stream_text;
			}
		}

		if (empty($text_parts)) {
			$raw_text = $this->extract_text_from_content_stream($pdf);

			if ($raw_text !== '') {
				$text_parts[] = $raw_text;
			}
		}

		return implode("\n", $text_parts);
	}

	/**
	 * Extract and decode PDF stream bodies.
	 *
	 * @param string $pdf PDF bytes.
	 * @return array
	 */
	private function extract_streams($pdf) {
		$streams = array();

		if (!preg_match_all('/(<<.*?>>)\s*stream\r?\n?(.*?)\r?\n?endstream/s', $pdf, $matches, PREG_SET_ORDER)) {
			return $streams;
		}

		foreach ($matches as $match) {
			$dictionary = isset($match[1]) ? (string) $match[1] : '';
			$stream = isset($match[2]) ? (string) $match[2] : '';
			$decoded = $this->decode_stream($stream, $dictionary);

			if ($decoded !== '') {
				$streams[] = $decoded;
			}
		}

		return $streams;
	}

	/**
	 * Decode a stream using supported PDF filters.
	 *
	 * @param string $stream Stream bytes.
	 * @param string $dictionary Stream dictionary.
	 * @return string
	 */
	private function decode_stream($stream, $dictionary) {
		$filters = $this->get_stream_filters($dictionary);
		$decoded = (string) $stream;

		foreach ($filters as $filter) {
			if ('FlateDecode' === $filter || 'Fl' === $filter) {
				$decoded = $this->decode_flate($decoded);
			} elseif ('ASCIIHexDecode' === $filter || 'AHx' === $filter) {
				$decoded = $this->decode_ascii_hex($decoded);
			} elseif ('ASCII85Decode' === $filter || 'A85' === $filter) {
				$decoded = $this->decode_ascii85($decoded);
			} elseif ('LZWDecode' === $filter || 'RunLengthDecode' === $filter || 'DCTDecode' === $filter || 'JPXDecode' === $filter) {
				return '';
			}

			if ($decoded === '') {
				return '';
			}
		}

		return $decoded;
	}

	/**
	 * Return stream filters from a dictionary.
	 *
	 * @param string $dictionary Stream dictionary.
	 * @return array
	 */
	private function get_stream_filters($dictionary) {
		$filters = array();

		if (preg_match('/\/Filter\s*\[(.*?)\]/s', $dictionary, $match)) {
			if (preg_match_all('/\/([A-Za-z0-9]+)/', $match[1], $filter_matches)) {
				$filters = $filter_matches[1];
			}
		} elseif (preg_match('/\/Filter\s*\/([A-Za-z0-9]+)/', $dictionary, $match)) {
			$filters[] = $match[1];
		}

		return $filters;
	}

	/**
	 * Decode Flate-compressed bytes.
	 *
	 * @param string $data Data.
	 * @return string
	 */
	private function decode_flate($data) {
		if (!function_exists('gzuncompress') || !function_exists('gzinflate')) {
			return '';
		}

		$decoded = @gzuncompress($data);

		if (is_string($decoded)) {
			return $decoded;
		}

		$decoded = @gzinflate($data);

		if (is_string($decoded)) {
			return $decoded;
		}

		$decoded = @gzinflate(substr($data, 2));

		return is_string($decoded) ? $decoded : '';
	}

	/**
	 * Decode ASCIIHex stream data.
	 *
	 * @param string $data Data.
	 * @return string
	 */
	private function decode_ascii_hex($data) {
		$data = preg_replace('/\s+|>/u', '', (string) $data);

		if (!is_string($data) || $data === '') {
			return '';
		}

		if (strlen($data) % 2 !== 0) {
			$data .= '0';
		}

		$decoded = @hex2bin($data);

		return is_string($decoded) ? $decoded : '';
	}

	/**
	 * Decode ASCII85 stream data.
	 *
	 * @param string $data Data.
	 * @return string
	 */
	private function decode_ascii85($data) {
		$data = preg_replace('/\s+|<~|~>/u', '', (string) $data);

		if (!is_string($data) || $data === '') {
			return '';
		}

		$output = '';
		$group = array();
		$length = strlen($data);

		for ($i = 0; $i < $length; $i++) {
			$char = $data[$i];

			if ('z' === $char && empty($group)) {
				$output .= "\0\0\0\0";
				continue;
			}

			$ord = ord($char);

			if ($ord < 33 || $ord > 117) {
				continue;
			}

			$group[] = $ord - 33;

			if (count($group) === 5) {
				$output .= $this->pack_ascii85_group($group, 4);
				$group = array();
			}
		}

		if (!empty($group)) {
			$bytes_to_write = count($group) - 1;

			while (count($group) < 5) {
				$group[] = 84;
			}

			$output .= $this->pack_ascii85_group($group, $bytes_to_write);
		}

		return $output;
	}

	/**
	 * Pack one ASCII85 group.
	 *
	 * @param array $group Group.
	 * @param int   $bytes_to_write Bytes to write.
	 * @return string
	 */
	private function pack_ascii85_group(array $group, $bytes_to_write) {
		$value = 0;

		foreach ($group as $part) {
			$value = ($value * 85) + (int) $part;
		}

		$packed = pack('N', $value);

		return substr($packed, 0, max(0, min(4, (int) $bytes_to_write)));
	}

	/**
	 * Extract text shown by PDF text operators.
	 *
	 * @param string $content Content stream.
	 * @return string
	 */
	private function extract_text_from_content_stream($content) {
		$tokens = $this->tokenize_content_stream($content);
		$text = array();
		$operands = array();

		foreach ($tokens as $token) {
			if ('operator' !== $token['type']) {
				$operands[] = $token;

				if (count($operands) > 24) {
					array_shift($operands);
				}

				continue;
			}

			$operator = $token['value'];

			if ('Tj' === $operator || "'" === $operator || '"' === $operator) {
				$string = $this->last_string_operand($operands);

				if ($string !== '') {
					$text[] = $string;
				}
			} elseif ('TJ' === $operator) {
				$array_text = $this->last_array_text_operand($operands);

				if ($array_text !== '') {
					$text[] = $array_text;
				}
			} elseif ('Td' === $operator || 'TD' === $operator || 'T*' === $operator) {
				$text[] = "\n";
			}

			$operands = array();
		}

		return implode(' ', $text);
	}

	/**
	 * Tokenize enough PDF content syntax to find text-showing operators.
	 *
	 * @param string $content Content stream.
	 * @return array
	 */
	private function tokenize_content_stream($content) {
		$tokens = array();
		$length = strlen($content);
		$i = 0;
		$array_stack = array();

		while ($i < $length) {
			$char = $content[$i];

			if (ctype_space($char)) {
				$i++;
				continue;
			}

			if ('%' === $char) {
				while ($i < $length && "\n" !== $content[$i] && "\r" !== $content[$i]) {
					$i++;
				}
				continue;
			}

			if ('(' === $char) {
				$token = array(
					'type'  => 'string',
					'value' => $this->parse_literal_string($content, $i),
				);
				$this->append_token($tokens, $array_stack, $token);
				continue;
			}

			if ('<' === $char && ($i + 1 >= $length || '<' !== $content[$i + 1])) {
				$token = array(
					'type'  => 'string',
					'value' => $this->parse_hex_string($content, $i),
				);
				$this->append_token($tokens, $array_stack, $token);
				continue;
			}

			if ('[' === $char) {
				$array_stack[] = array();
				$i++;
				continue;
			}

			if (']' === $char) {
				$array_token = array(
					'type'  => 'array',
					'value' => array_pop($array_stack),
				);
				$this->append_token($tokens, $array_stack, $array_token);
				$i++;
				continue;
			}

			$value = '';

			while ($i < $length && !ctype_space($content[$i]) && !in_array($content[$i], array('[', ']', '(', ')', '<', '>'), true)) {
				$value .= $content[$i];
				$i++;
			}

			if ($value === '') {
				$i++;
				continue;
			}

			$type = preg_match('/^[A-Za-z\*\'"]+$/', $value) ? 'operator' : 'other';
			$token = array(
				'type'  => $type,
				'value' => $value,
			);
			$this->append_token($tokens, $array_stack, $token);
		}

		return $tokens;
	}

	/**
	 * Append a token to the current array or top-level token stream.
	 *
	 * @param array $tokens Tokens.
	 * @param array $array_stack Array stack.
	 * @param array $token Token.
	 * @return void
	 */
	private function append_token(array &$tokens, array &$array_stack, array $token) {
		if (!empty($array_stack)) {
			$last_index = count($array_stack) - 1;
			$array_stack[$last_index][] = $token;
			return;
		}

		$tokens[] = $token;
	}

	/**
	 * Parse a PDF literal string.
	 *
	 * @param string $content Content.
	 * @param int    $offset Offset, passed by reference.
	 * @return string
	 */
	private function parse_literal_string($content, &$offset) {
		$length = strlen($content);
		$offset++;
		$depth = 1;
		$output = '';

		while ($offset < $length && $depth > 0) {
			$char = $content[$offset];

			if ('\\' === $char) {
				$offset++;

				if ($offset >= $length) {
					break;
				}

				$escaped = $content[$offset];

				if ('n' === $escaped) {
					$output .= "\n";
				} elseif ('r' === $escaped) {
					$output .= "\r";
				} elseif ('t' === $escaped) {
					$output .= "\t";
				} elseif ('b' === $escaped) {
					$output .= "\b";
				} elseif ('f' === $escaped) {
					$output .= "\f";
				} elseif ("\r" === $escaped || "\n" === $escaped) {
					if ("\r" === $escaped && $offset + 1 < $length && "\n" === $content[$offset + 1]) {
						$offset++;
					}
				} elseif (preg_match('/[0-7]/', $escaped)) {
					$octal = $escaped;
					for ($j = 0; $j < 2 && $offset + 1 < $length && preg_match('/[0-7]/', $content[$offset + 1]); $j++) {
						$offset++;
						$octal .= $content[$offset];
					}
					$output .= chr(octdec($octal));
				} else {
					$output .= $escaped;
				}
			} elseif ('(' === $char) {
				$depth++;
				$output .= $char;
			} elseif (')' === $char) {
				$depth--;

				if ($depth > 0) {
					$output .= $char;
				}
			} else {
				$output .= $char;
			}

			$offset++;
		}

		return $this->decode_pdf_string($output);
	}

	/**
	 * Parse a PDF hex string.
	 *
	 * @param string $content Content.
	 * @param int    $offset Offset, passed by reference.
	 * @return string
	 */
	private function parse_hex_string($content, &$offset) {
		$length = strlen($content);
		$offset++;
		$hex = '';

		while ($offset < $length && '>' !== $content[$offset]) {
			if (!ctype_space($content[$offset])) {
				$hex .= $content[$offset];
			}

			$offset++;
		}

		if ($offset < $length && '>' === $content[$offset]) {
			$offset++;
		}

		if ($hex === '') {
			return '';
		}

		if (strlen($hex) % 2 !== 0) {
			$hex .= '0';
		}

		$bytes = @hex2bin($hex);

		return is_string($bytes) ? $this->decode_pdf_string($bytes) : '';
	}

	/**
	 * Decode common PDF string encodings.
	 *
	 * @param string $bytes Bytes.
	 * @return string
	 */
	private function decode_pdf_string($bytes) {
		if ($bytes === '') {
			return '';
		}

		if (substr($bytes, 0, 2) === "\xFE\xFF" && function_exists('mb_convert_encoding')) {
			return (string) mb_convert_encoding(substr($bytes, 2), 'UTF-8', 'UTF-16BE');
		}

		if (function_exists('mb_convert_encoding')) {
			$utf8 = @mb_convert_encoding($bytes, 'UTF-8', 'Windows-1252');

			return is_string($utf8) ? $utf8 : $bytes;
		}

		return $bytes;
	}

	/**
	 * Return the nearest previous string operand.
	 *
	 * @param array $operands Operands.
	 * @return string
	 */
	private function last_string_operand(array $operands) {
		for ($i = count($operands) - 1; $i >= 0; $i--) {
			if (isset($operands[$i]['type']) && 'string' === $operands[$i]['type']) {
				return (string) $operands[$i]['value'];
			}
		}

		return '';
	}

	/**
	 * Return text from the nearest previous TJ array operand.
	 *
	 * @param array $operands Operands.
	 * @return string
	 */
	private function last_array_text_operand(array $operands) {
		for ($i = count($operands) - 1; $i >= 0; $i--) {
			if (!isset($operands[$i]['type']) || 'array' !== $operands[$i]['type'] || !is_array($operands[$i]['value'])) {
				continue;
			}

			$text = '';

			foreach ($operands[$i]['value'] as $item) {
				if (isset($item['type']) && 'string' === $item['type']) {
					$text .= (string) $item['value'];
				}
			}

			return $text;
		}

		return '';
	}

	/**
	 * Normalize extracted text for storage.
	 *
	 * @param string $text Extracted text.
	 * @return string
	 */
	private function normalize_output_text($text) {
		$text = str_replace("\0", '', (string) $text);
		$text = preg_replace('/[^\P{C}\n\r\t]+/u', ' ', $text);
		$text = preg_replace('/[ \t]+/u', ' ', is_string($text) ? $text : '');
		$text = preg_replace('/\s*\n\s*/u', "\n", is_string($text) ? $text : '');
		$text = $this->remove_pdf_glyph_noise(is_string($text) ? $text : '');
		$text = preg_replace('/\n{3,}/u', "\n\n", is_string($text) ? $text : '');

		return is_string($text) ? trim($text) : '';
	}

	/**
	 * Remove common glyph-decoding noise from lines that are mostly broken PDF tokens.
	 *
	 * @param string $text Extracted text.
	 * @return string
	 */
	private function remove_pdf_glyph_noise($text) {
		$lines = $this->split_pdf_text_lines((string) $text);

		$cleaned_lines = array();

		foreach ($lines as $line) {
			$line = trim((string) $line);

			if ($line === '') {
				$cleaned_lines[] = '';
				continue;
			}

			$tokens = $this->split_pdf_line_tokens($line);
			$tokens = $this->merge_broken_initial_word_tokens($tokens);
			$non_empty_tokens = $this->remove_empty_tokens($tokens);

			if ($this->is_pdf_glyph_noise_only_line($non_empty_tokens)) {
				continue;
			}

			if (!$this->is_pdf_glyph_noise_line($tokens)) {
				$cleaned_lines[] = $line;
				continue;
			}

			$kept = array();

			foreach ($tokens as $token) {
				if (!$this->is_pdf_glyph_noise_token($token)) {
					$kept[] = $token;
				}
			}

			$cleaned_line = trim(implode(' ', $kept));
			$cleaned_line = $this->trim_pdf_fragment_edges($cleaned_line);

			if ($cleaned_line !== '') {
				$cleaned_lines[] = $cleaned_line;
			}
		}

		return trim(implode("\n", $cleaned_lines));
	}

	/**
	 * Split text into lines, falling back when Unicode PCRE rejects PDF bytes.
	 *
	 * @param string $text Extracted text.
	 * @return array
	 */
	private function split_pdf_text_lines($text) {
		$lines = preg_split('/\R/u', (string) $text);

		if (is_array($lines)) {
			return $lines;
		}

		$lines = preg_split('/\r\n|\r|\n/', (string) $text);

		return is_array($lines) ? $lines : array((string) $text);
	}

	/**
	 * Split a line into tokens, falling back when Unicode PCRE rejects PDF bytes.
	 *
	 * @param string $line Extracted line.
	 * @return array
	 */
	private function split_pdf_line_tokens($line) {
		$tokens = preg_split('/\s+/u', (string) $line);

		if (is_array($tokens)) {
			return $tokens;
		}

		$tokens = preg_split('/\s+/', (string) $line);

		return is_array($tokens) ? $tokens : array((string) $line);
	}

	/**
	 * Remove empty tokens while preserving token values.
	 *
	 * @param array $tokens Line tokens.
	 * @return array
	 */
	private function remove_empty_tokens(array $tokens) {
		$clean = array();

		foreach ($tokens as $token) {
			$token = trim((string) $token);

			if ($token !== '') {
				$clean[] = $token;
			}
		}

		return $clean;
	}

	/**
	 * Determine if a short line is entirely PDF glyph noise.
	 *
	 * @param array $tokens Line tokens.
	 * @return bool
	 */
	private function is_pdf_glyph_noise_only_line(array $tokens) {
		if (empty($tokens)) {
			return true;
		}

		if (count($tokens) > 7) {
			return false;
		}

		foreach ($tokens as $token) {
			if (!$this->is_pdf_glyph_noise_token($token)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Rejoin a PDF split such as "U pon" into "Upon" before single-letter cleanup.
	 *
	 * @param array $tokens Line tokens.
	 * @return array
	 */
	private function merge_broken_initial_word_tokens(array $tokens) {
		$merged = array();
		$count = count($tokens);

		for ($i = 0; $i < $count; $i++) {
			$current = (string) $tokens[$i];
			$next = $i + 1 < $count ? (string) $tokens[$i + 1] : '';

			if (preg_match('/^[A-Z]$/', $current) && preg_match('/^[a-z]{2,}$/u', $next)) {
				$merged[] = $current . $next;
				$i++;
				continue;
			}

			$merged[] = $current;
		}

		return $merged;
	}

	/**
	 * Determine if a line has enough one-character/symbol noise to clean aggressively.
	 *
	 * @param array $tokens Line tokens.
	 * @return bool
	 */
	private function is_pdf_glyph_noise_line(array $tokens) {
		$total = 0;
		$noise = 0;

		foreach ($tokens as $token) {
			$token = trim((string) $token);

			if ($token === '') {
				continue;
			}

			$total++;

			if ($this->is_pdf_glyph_noise_token($token)) {
				$noise++;
			}
		}

		if ($total < 8) {
			return false;
		}

		if (($noise / $total) >= 0.25) {
			return true;
		}

		return $this->has_pdf_glyph_noise_run($tokens);
	}

	/**
	 * Detect a long run of PDF glyph noise tokens inside a line.
	 *
	 * @param array $tokens Line tokens.
	 * @return bool
	 */
	private function has_pdf_glyph_noise_run(array $tokens) {
		$run = 0;

		foreach ($tokens as $token) {
			if ($this->is_pdf_glyph_noise_token($token)) {
				$run++;

				if ($run >= 5) {
					return true;
				}

				continue;
			}

			$run = 0;
		}

		return false;
	}

	/**
	 * Determine if a token is likely broken font-encoding noise.
	 *
	 * @param string $token Token.
	 * @return bool
	 */
	private function is_pdf_glyph_noise_token($token) {
		$token = trim((string) $token);

		if ($token === '') {
			return true;
		}

		if (strpos($token, '^') !== false) {
			return true;
		}

		if (preg_match('/^[\p{P}\p{S}]+$/u', $token)) {
			return true;
		}

		$length = function_exists('mb_strlen') ? mb_strlen($token, 'UTF-8') : strlen($token);

		if (
			$length <= 3
			&& preg_match('/[\x{0152}\x{0153}\x{0160}\x{0161}\x{00C7}\x{00E7}\x{2030}\x{00C1}\x{00E1}\x{00B5}\x{2039}\x{203A}\x{2014}\[\]\{\}\(\)\/\\\\|]/u', $token)
		) {
			return true;
		}

		if (
			false === preg_match('//u', $token)
			&& preg_match('/^[^A-Za-z0-9@._-]+$/', $token)
		) {
			return true;
		}

		if ($length === 1) {
			$lower = function_exists('mb_strtolower') ? mb_strtolower($token, 'UTF-8') : strtolower($token);

			if (in_array($lower, array('a', 'i'), true) || preg_match('/^\d$/', $token)) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Remove leftover dangling fragments after a noisy line has been cleaned.
	 *
	 * @param string $line Cleaned line.
	 * @return string
	 */
	private function trim_pdf_fragment_edges($line) {
		$tokens = $this->split_pdf_line_tokens((string) $line);
		$tokens = $this->remove_empty_tokens($tokens);

		while (!empty($tokens) && $this->is_dangling_pdf_fragment(reset($tokens))) {
			array_shift($tokens);
		}

		while (!empty($tokens) && $this->is_dangling_pdf_fragment(end($tokens))) {
			array_pop($tokens);
		}

		return trim(implode(' ', $tokens));
	}

	/**
	 * Detect a leftover one-letter fragment after glyph-noise cleanup.
	 *
	 * @param string $token Token.
	 * @return bool
	 */
	private function is_dangling_pdf_fragment($token) {
		$token = trim((string) $token);
		$length = function_exists('mb_strlen') ? mb_strlen($token, 'UTF-8') : strlen($token);

		if ($length !== 1) {
			return false;
		}

		$lower = function_exists('mb_strtolower') ? mb_strtolower($token, 'UTF-8') : strtolower($token);

		return !in_array($lower, array('a', 'i'), true) && !preg_match('/^\d$/', $token);
	}

	/**
	 * Determine if extracted text will be truncated before storage/indexing.
	 *
	 * @param string $text Text.
	 * @return bool
	 */
	private function was_text_truncated($text) {
		$max_text_bytes = (int) apply_filters('vfwp_intranet_search_pdf_max_text_bytes', self::DEFAULT_MAX_TEXT_BYTES);

		return strlen((string) $text) > $max_text_bytes;
	}

	/**
	 * Limit extracted text stored in ACF and the index.
	 *
	 * @param string $text Text.
	 * @return string
	 */
	private function limit_text($text) {
		$max_text_bytes = (int) apply_filters('vfwp_intranet_search_pdf_max_text_bytes', self::DEFAULT_MAX_TEXT_BYTES);

		return substr((string) $text, 0, $max_text_bytes);
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
			'method' => self::EXTRACTOR_VERSION,
		);
	}
}
