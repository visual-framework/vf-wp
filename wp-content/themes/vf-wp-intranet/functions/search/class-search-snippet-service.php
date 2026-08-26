<?php
/**
 * Snippet selection and safe term highlighting for search results.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Snippet_Service {
	const SNIPPET_LENGTH = 220;
	const MAX_HIGHLIGHTS = 20;

	/**
	 * @var VFWP_Intranet_Search_Query_Parser
	 */
	private $query_parser;

	/**
	 * @param VFWP_Intranet_Search_Query_Parser|null $query_parser Query parser.
	 */
	public function __construct($query_parser = null) {
		$this->query_parser = $query_parser ? $query_parser : new VFWP_Intranet_Search_Query_Parser();
	}

	/**
	 * Build raw and display-safe fields for one result.
	 *
	 * @param array $result Result data.
	 * @param array $parsed_query Parsed query.
	 * @return array
	 */
	public function add_display_fields(array $result, array $parsed_query) {
		$source = isset($result['snippet_source']) && is_array($result['snippet_source']) ? $result['snippet_source'] : array();
		$snippet = $this->select_snippet($source, $parsed_query, isset($result['object_type']) ? $result['object_type'] : 'post');
		$title = isset($result['title']) ? (string) $result['title'] : '';

		$result['title_highlighted'] = $this->highlight_text($title, $parsed_query);
		$result['snippet'] = $snippet['text'];
		$result['snippet_highlighted'] = $this->highlight_text($snippet['text'], $parsed_query);
		$result['snippet_field'] = $snippet['field'];
		$result['display'] = array(
			'title'   => $result['title_highlighted'],
			'snippet' => $result['snippet_highlighted'],
		);

		return $result;
	}

	/**
	 * Select the most useful snippet passage.
	 *
	 * @param array  $source Source fields.
	 * @param array  $parsed_query Parsed query.
	 * @param string $object_type Object type.
	 * @return array
	 */
	public function select_snippet(array $source, array $parsed_query, $object_type = 'post') {
		$field_order = $object_type === 'pdf'
			? array('content', 'excerpt', 'acf_keywords')
			: array('excerpt', 'acf_keywords', 'content');
		$best = null;

		foreach ($field_order as $index => $field) {
			$text = isset($source[$field]) ? $this->clean_text($source[$field]) : '';

			if ($text === '') {
				continue;
			}

			$matches = $this->find_matches($text, $this->get_needles($parsed_query), self::MAX_HIGHLIGHTS);
			$score = $this->score_passage($matches, $field, $index, $parsed_query);
			$position = empty($matches) ? 0 : (int) $matches[0]['start'];

			if ($best === null || $score > $best['score']) {
				$best = array(
					'field'    => $field,
					'text'     => $text,
					'position' => $position,
					'score'    => $score,
				);
			}
		}

		if ($best === null) {
			$fallback = isset($source['title']) ? $this->clean_text($source['title']) : '';

			return array(
				'field' => 'title',
				'text'  => $this->build_snippet($fallback, 0),
			);
		}

		return array(
			'field' => $best['field'],
			'text'  => $this->build_snippet($best['text'], $best['position']),
		);
	}

	/**
	 * Return display-safe text with marked terms.
	 *
	 * @param string $text Raw text.
	 * @param array  $parsed_query Parsed query.
	 * @return string
	 */
	public function highlight_text($text, array $parsed_query) {
		$text = $this->clean_text($text);

		if ($text === '') {
			return '';
		}

		$matches = $this->find_matches($text, $this->get_needles($parsed_query), self::MAX_HIGHLIGHTS);

		if (empty($matches)) {
			return esc_html($text);
		}

		usort($matches, array($this, 'sort_matches_by_position'));
		$output = '';
		$cursor = 0;

		foreach ($matches as $match) {
			if ($match['start'] < $cursor) {
				continue;
			}

			$output .= esc_html($this->substring($text, $cursor, $match['start'] - $cursor));
			$output .= '<mark>' . esc_html($this->substring($text, $match['start'], $match['end'] - $match['start'])) . '</mark>';
			$cursor = $match['end'];
		}

		$output .= esc_html($this->substring($text, $cursor));

		return $output;
	}

	/**
	 * Score a candidate passage.
	 *
	 * @param array  $matches Matches.
	 * @param string $field Field name.
	 * @param int    $field_index Field order.
	 * @param array  $parsed_query Parsed query.
	 * @return int
	 */
	private function score_passage(array $matches, $field, $field_index, array $parsed_query) {
		if (empty($matches)) {
			return 10 - $field_index;
		}

		$unique_terms = array();
		$phrase_hits = 0;

		foreach ($matches as $match) {
			if (!empty($match['is_phrase'])) {
				$phrase_hits++;
			}

			$unique_terms[$match['needle']] = true;
		}

		$field_bonus = array(
			'excerpt'      => 35,
			'acf_keywords' => 30,
			'content'      => 20,
			'title'        => 10,
		);

		$term_count = max(1, count($parsed_query['fulltext_terms']));
		$coverage = min(1, count($unique_terms) / $term_count);

		return (int) (
			(isset($field_bonus[$field]) ? $field_bonus[$field] : 0)
			+ ($phrase_hits * 100)
			+ (count($unique_terms) * 30)
			+ ($coverage * 80)
			- $field_index
		);
	}

	/**
	 * Build a readable snippet around a match position.
	 *
	 * @param string $text Source text.
	 * @param int    $position Match position in characters.
	 * @return string
	 */
	private function build_snippet($text, $position) {
		$text = $this->clean_text($text);
		$length = $this->length($text);

		if ($length <= self::SNIPPET_LENGTH) {
			return $text;
		}

		$start = max(0, (int) $position - (int) floor(self::SNIPPET_LENGTH / 3));
		$end = min($length, $start + self::SNIPPET_LENGTH);

		if ($end - $start < self::SNIPPET_LENGTH) {
			$start = max(0, $end - self::SNIPPET_LENGTH);
		}

		$start = $this->adjust_start_boundary($text, $start);
		$end = $this->adjust_end_boundary($text, $end);
		$snippet = trim($this->substring($text, $start, $end - $start));

		if ($start > 0) {
			$snippet = '...' . $snippet;
		}

		if ($end < $length) {
			$snippet .= '...';
		}

		return $snippet;
	}

	/**
	 * Return needles for phrase and term highlighting.
	 *
	 * @param array $parsed_query Parsed query.
	 * @return array
	 */
	private function get_needles(array $parsed_query) {
		$needles = array();

		foreach ((array) $parsed_query['phrases'] as $phrase) {
			if ($phrase !== '') {
				$needles[$phrase] = array(
					'value'     => $phrase,
					'is_phrase' => true,
				);
			}
		}

		foreach ((array) $parsed_query['terms'] as $term) {
			if ($term !== '') {
				$needles[$term] = array(
					'value'     => $term,
					'is_phrase' => false,
				);
			}
		}

		uasort($needles, array($this, 'sort_needles_by_length'));

		return array_values($needles);
	}

	/**
	 * Find non-overlapping accent-insensitive matches.
	 *
	 * @param string $text Text.
	 * @param array  $needles Needles.
	 * @param int    $limit Match limit.
	 * @return array
	 */
	private function find_matches($text, array $needles, $limit) {
		if ($text === '' || empty($needles)) {
			return array();
		}

		$folded = $this->fold_with_map($text);
		$matches = array();

		foreach ($needles as $needle) {
			$folded_needle = $this->fold_plain($needle['value']);

			if ($folded_needle === '') {
				continue;
			}

			$offset = 0;
			$needle_length = $this->length($folded_needle);

			while (($position = mb_strpos($folded['text'], $folded_needle, $offset, 'UTF-8')) !== false) {
				if (!isset($folded['map'][$position]) || !isset($folded['map'][$position + $needle_length - 1])) {
					break;
				}

				$start = (int) $folded['map'][$position];
				$end = (int) $folded['map'][$position + $needle_length - 1] + 1;

				if (!$this->overlaps_existing_match($matches, $start, $end)) {
					$matches[] = array(
						'start'     => $start,
						'end'       => $end,
						'needle'    => $needle['value'],
						'is_phrase' => !empty($needle['is_phrase']),
					);
				}

				if (count($matches) >= $limit) {
					return $matches;
				}

				$offset = $position + max(1, $needle_length);
			}
		}

		usort($matches, array($this, 'sort_matches_by_position'));

		return $matches;
	}

	/**
	 * Build folded text and a map back to original character offsets.
	 *
	 * @param string $text Source text.
	 * @return array
	 */
	private function fold_with_map($text) {
		$chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
		$folded_text = '';
		$map = array();

		if (!is_array($chars)) {
			return array(
				'text' => $this->fold_plain($text),
				'map'  => array(),
			);
		}

		foreach ($chars as $index => $char) {
			$folded_char = $this->fold_plain($char);
			$folded_chars = preg_split('//u', $folded_char, -1, PREG_SPLIT_NO_EMPTY);

			if (!is_array($folded_chars)) {
				continue;
			}

			foreach ($folded_chars as $folded_unit) {
				$folded_text .= $folded_unit;
				$map[] = $index;
			}
		}

		return array(
			'text' => $folded_text,
			'map'  => $map,
		);
	}

	/**
	 * Fold text for case/accent-insensitive matching.
	 *
	 * @param string $text Text.
	 * @return string
	 */
	private function fold_plain($text) {
		$text = (string) $text;

		if (function_exists('remove_accents')) {
			$text = remove_accents($text);
		}

		if (function_exists('mb_strtolower')) {
			return mb_strtolower($text, 'UTF-8');
		}

		return strtolower($text);
	}

	/**
	 * Clean raw source text for snippets.
	 *
	 * @param mixed $text Source.
	 * @return string
	 */
	private function clean_text($text) {
		if (is_array($text) || is_object($text)) {
			return '';
		}

		$text = html_entity_decode(wp_specialchars_decode((string) $text, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, get_bloginfo('charset'));
		$text = wp_strip_all_tags($text, true);
		$text = preg_replace('/\s+/u', ' ', $text);

		return is_string($text) ? trim($text) : '';
	}

	/**
	 * Avoid starting in the middle of a word where practical.
	 *
	 * @param string $text Text.
	 * @param int    $start Start.
	 * @return int
	 */
	private function adjust_start_boundary($text, $start) {
		if ($start <= 0) {
			return 0;
		}

		$length = $this->length($text);

		for ($i = $start; $i < min($length, $start + 30); $i++) {
			if (preg_match('/\s/u', $this->substring($text, $i, 1))) {
				return min($length, $i + 1);
			}
		}

		return $start;
	}

	/**
	 * Avoid ending in the middle of a word where practical.
	 *
	 * @param string $text Text.
	 * @param int    $end End.
	 * @return int
	 */
	private function adjust_end_boundary($text, $end) {
		$length = $this->length($text);

		if ($end >= $length) {
			return $length;
		}

		for ($i = $end; $i > max(0, $end - 30); $i--) {
			if (preg_match('/\s/u', $this->substring($text, $i, 1))) {
				return $i;
			}
		}

		return $end;
	}

	/**
	 * Determine if a match overlaps an existing match.
	 *
	 * @param array $matches Matches.
	 * @param int   $start Start.
	 * @param int   $end End.
	 * @return bool
	 */
	private function overlaps_existing_match(array $matches, $start, $end) {
		foreach ($matches as $match) {
			if ($start < $match['end'] && $end > $match['start']) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Sort matches by position.
	 *
	 * @param array $a Match A.
	 * @param array $b Match B.
	 * @return int
	 */
	private function sort_matches_by_position($a, $b) {
		if ($a['start'] === $b['start']) {
			return $b['end'] - $a['end'];
		}

		return $a['start'] - $b['start'];
	}

	/**
	 * Sort needles by length so phrases win over inner term matches.
	 *
	 * @param array $a Needle A.
	 * @param array $b Needle B.
	 * @return int
	 */
	private function sort_needles_by_length($a, $b) {
		return $this->length($b['value']) - $this->length($a['value']);
	}

	/**
	 * Unicode-aware substring.
	 *
	 * @param string   $text Text.
	 * @param int      $start Start.
	 * @param int|null $length Length.
	 * @return string
	 */
	private function substring($text, $start, $length = null) {
		if (function_exists('mb_substr')) {
			return $length === null ? mb_substr($text, $start, null, 'UTF-8') : mb_substr($text, $start, $length, 'UTF-8');
		}

		return $length === null ? substr($text, $start) : substr($text, $start, $length);
	}

	/**
	 * Unicode-aware length.
	 *
	 * @param string $text Text.
	 * @return int
	 */
	private function length($text) {
		if (function_exists('mb_strlen')) {
			return (int) mb_strlen($text, 'UTF-8');
		}

		return strlen($text);
	}
}
