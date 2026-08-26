<?php
/**
 * Text normalization for indexed search content.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Normalizer {
	/**
	 * Normalize a text fragment for indexing.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public function normalize_text($value) {
		if (is_array($value) || is_object($value)) {
			return '';
		}

		$text = (string) $value;

		if ($text === '') {
			return '';
		}

		$text = strip_shortcodes($text);
		$text = wp_strip_all_tags($text, true);
		$text = wp_specialchars_decode($text, ENT_QUOTES);
		$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, get_bloginfo('charset'));
		$text = preg_replace('/\s+/u', ' ', $text);

		if (!is_string($text)) {
			return '';
		}

		return trim($text);
	}

	/**
	 * Normalize post content without rendering shortcodes or scanning postmeta.
	 *
	 * @param string $content Raw post content.
	 * @return string
	 */
	public function normalize_content($content) {
		return $this->normalize_text($content);
	}

	/**
	 * Normalize text-compatible values from an ACF field.
	 *
	 * @param mixed $value Raw ACF value.
	 * @return string
	 */
	public function normalize_acf_value($value) {
		$pieces = array();
		$this->collect_text_values($value, $pieces);

		return $this->normalize_text(implode(' ', $pieces));
	}

	/**
	 * Create a stable hash for the searchable fields.
	 *
	 * @param array $parts Hash input.
	 * @return string
	 */
	public function hash(array $parts) {
		return hash('sha256', wp_json_encode($parts));
	}

	/**
	 * Recursively collect bounded textual values from nested ACF structures.
	 *
	 * @param mixed $value Raw value.
	 * @param array $pieces Collected text pieces.
	 * @param int   $depth Current recursion depth.
	 * @return void
	 */
	private function collect_text_values($value, array &$pieces, $depth = 0) {
		if ($depth > 4 || count($pieces) >= 100) {
			return;
		}

		if (is_string($value)) {
			if (!$this->looks_binary($value)) {
				$pieces[] = substr($value, 0, 2000);
			}
			return;
		}

		if (is_int($value) || is_float($value)) {
			$pieces[] = (string) $value;
			return;
		}

		if (!is_array($value)) {
			return;
		}

		foreach ($value as $key => $child_value) {
			if ($this->should_skip_acf_key($key)) {
				continue;
			}

			$this->collect_text_values($child_value, $pieces, $depth + 1);

			if (count($pieces) >= 100) {
				return;
			}
		}
	}

	/**
	 * Avoid common non-text ACF array metadata.
	 *
	 * @param mixed $key Array key.
	 * @return bool
	 */
	private function should_skip_acf_key($key) {
		if (is_int($key)) {
			return false;
		}

		$key = strtolower((string) $key);

		if ($key === '' || strpos($key, '_') === 0) {
			return true;
		}

		return in_array(
			$key,
			array('id', 'url', 'uri', 'filename', 'filesize', 'mime_type', 'mime', 'type', 'subtype', 'icon', 'width', 'height', 'sizes'),
			true
		);
	}

	/**
	 * Avoid storing binary-looking content.
	 *
	 * @param string $value Raw string.
	 * @return bool
	 */
	private function looks_binary($value) {
		return preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $value) === 1;
	}
}
