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
		$content = is_scalar($content) ? (string) $content : '';
		$pieces = array();
		$visible_content = $this->normalize_text($content);

		if ($visible_content !== '') {
			$pieces[] = $visible_content;
		}

		if (strpos($content, '<!-- wp:acf/') !== false && function_exists('parse_blocks')) {
			$blocks = parse_blocks($content);

			if (is_array($blocks)) {
				$this->collect_acf_block_text($blocks, $pieces);
			}
		}

		return $this->normalize_text(implode("\n", array_values(array_unique($pieces))));
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
	 * Collect bounded text stored in dynamic ACF block attributes.
	 *
	 * Dynamic ACF blocks are commonly self-closing, so their visible values live
	 * in the block comment JSON and are removed by normal HTML normalization.
	 *
	 * @param array $blocks Parsed Gutenberg blocks.
	 * @param array $pieces Collected text pieces.
	 * @param int   $depth Current block nesting depth.
	 * @return void
	 */
	private function collect_acf_block_text(array $blocks, array &$pieces, $depth = 0) {
		if ($depth > 8 || count($pieces) >= 100) {
			return;
		}

		foreach ($blocks as $block) {
			if (!is_array($block)) {
				continue;
			}

			$block_name = isset($block['blockName']) ? (string) $block['blockName'] : '';
			$attributes = isset($block['attrs']) && is_array($block['attrs']) ? $block['attrs'] : array();

			if (strpos($block_name, 'acf/') === 0 && isset($attributes['data']) && is_array($attributes['data'])) {
				$this->collect_acf_block_data($attributes['data'], $pieces);
			}

			if (!empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
				$this->collect_acf_block_text($block['innerBlocks'], $pieces, $depth + 1);
			}

			if (count($pieces) >= 100) {
				return;
			}
		}
	}

	/**
	 * Extract user-facing text fields without indexing ACF block configuration.
	 *
	 * @param array $data ACF block data.
	 * @param array $pieces Collected text pieces.
	 * @return void
	 */
	private function collect_acf_block_data(array $data, array &$pieces) {
		foreach ($data as $key => $value) {
			$key = strtolower((string) $key);

			if (
				$key === ''
				|| strpos($key, '_') === 0
				|| preg_match('/(^|_)(title|heading|subheading|lede|text|content|description|summary|caption|quote|label|name|overview|intro)(_|$)/', $key) !== 1
			) {
				continue;
			}

			$this->collect_text_values($value, $pieces);

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
