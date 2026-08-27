<?php
/**
 * Query normalization and parsing for the theme search index.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Query_Parser {
	const MAX_QUERY_LENGTH = 240;
	const MAX_TERMS = 12;
	const MAX_PHRASES = 3;
	const MIN_FULLTEXT_TERM_LENGTH = 3;

	/**
	 * Common English stopwords that do not help FULLTEXT candidate selection.
	 *
	 * @var array
	 */
	private $default_stopwords = array(
		'a',
		'an',
		'and',
		'are',
		'as',
		'at',
		'be',
		'by',
		'for',
		'from',
		'has',
		'have',
		'in',
		'is',
		'its',
		'of',
		'on',
		'or',
		'our',
		'that',
		'the',
		'this',
		'to',
		'was',
		'were',
		'with',
	);

	/**
	 * Parse a raw visitor query into safe normalized search parts.
	 *
	 * @param mixed $query Raw query.
	 * @return array
	 */
	public function parse($query) {
		$raw_query = is_scalar($query) ? (string) $query : '';
		$raw_query = $this->limit_string($raw_query, self::MAX_QUERY_LENGTH);
		$decoded_query = $this->decode($raw_query);
		$quoted_phrases = $this->extract_quoted_phrases($decoded_query);
		$normalized_query = $this->normalize_search_text($decoded_query);
		$min_word_length = $this->get_min_word_length();
		$all_terms = $this->extract_terms($normalized_query, false);
		$protected_phrase_parts = $this->extract_protected_phrase_parts($normalized_query);
		$protected_phrases = $protected_phrase_parts['phrases'];
		$has_protected_phrases = !empty($protected_phrases);
		$terms = $this->extract_terms($protected_phrase_parts['remaining_query'], true);
		$protected_phrase_terms = array();

		foreach ($protected_phrases as $protected_phrase) {
			$protected_phrase_terms = array_merge($protected_phrase_terms, $this->extract_terms($protected_phrase, false));
		}

		$term_source = array_merge($terms, $protected_phrase_terms);
		$fulltext_terms = array();
		$ignored_terms = array();

		foreach ($term_source as $term) {
			if ($this->string_length($term) >= self::MIN_FULLTEXT_TERM_LENGTH) {
				$fulltext_terms[$term] = $term;
			} else {
				$ignored_terms[$term] = $term;
			}
		}

		$phrases = array_merge(
			$protected_phrases,
			$this->build_phrases($protected_phrase_parts['remaining_query'], $quoted_phrases, $terms)
		);
		$boolean_query = $this->build_boolean_query($fulltext_terms);

		return array(
			'raw'              => $raw_query,
			'normalized'       => $normalized_query,
			'term_query'       => $protected_phrase_parts['remaining_query'],
			'terms'            => $terms,
			'all_terms'        => $all_terms,
			'protected_phrase_terms' => array_values(array_unique($protected_phrase_terms)),
			'fulltext_terms'   => array_values($fulltext_terms),
			'ignored_terms'    => array_values($ignored_terms),
			'phrases'          => $phrases,
			'exact_phrase'     => isset($phrases[0]) ? $phrases[0] : '',
			'protected_phrase' => isset($protected_phrases[0]) ? $protected_phrases[0] : '',
			'protected_phrases' => $protected_phrases,
			'is_exact_phrase_only' => $has_protected_phrases && empty($terms),
			'has_protected_phrases' => $has_protected_phrases,
			'boolean_query'    => $boolean_query,
			'is_empty'         => $normalized_query === '',
			'is_searchable'    => $boolean_query !== '' || $has_protected_phrases,
			'min_term_length'  => $min_word_length,
			'fulltext_min_term_length' => self::MIN_FULLTEXT_TERM_LENGTH,
			'max_query_length' => self::MAX_QUERY_LENGTH,
		);
	}

	/**
	 * Normalize punctuation, spaces, apostrophes, hyphens, accents and case.
	 *
	 * @param string $text Raw text.
	 * @return string
	 */
	public function normalize_search_text($text) {
		$text = $this->decode($text);
		$text = str_replace(
			array('’', '‘', '‚', '`', '´'),
			"'",
			$text
		);
		$text = str_replace(
			array('‐', '‑', '‒', '–', '—', '―'),
			'-',
			$text
		);

		if (function_exists('remove_accents')) {
			$text = remove_accents($text);
		}

		$text = str_replace(array("'", '-'), ' ', $text);
		$text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
		$text = preg_replace('/\s+/u', ' ', $text);
		$text = is_string($text) ? trim($text) : '';

		if (function_exists('mb_strtolower')) {
			return mb_strtolower($text, 'UTF-8');
		}

		return strtolower($text);
	}

	/**
	 * Extract deduplicated terms.
	 *
	 * @param string $text Normalized text.
	 * @param bool   $remove_stopwords Whether to remove stopwords.
	 * @return array
	 */
	private function extract_terms($text, $remove_stopwords) {
		if ($text === '') {
			return array();
		}

		preg_match_all('/[\p{L}\p{N}]+/u', $text, $matches);
		$terms = array();
		$stopwords = $this->get_stopwords();
		$min_word_length = $this->get_min_word_length();

		foreach ($matches[0] as $term) {
			if ($remove_stopwords && $this->string_length($term) < $min_word_length) {
				continue;
			}

			if ($remove_stopwords && in_array($term, $stopwords, true)) {
				continue;
			}

			if (!isset($terms[$term])) {
				$terms[$term] = $term;
			}

			if (count($terms) >= self::MAX_TERMS) {
				break;
			}
		}

		return array_values($terms);
	}

	/**
	 * Extract quoted phrases before punctuation normalization.
	 *
	 * @param string $text Raw text.
	 * @return array
	 */
	private function extract_quoted_phrases($text) {
		preg_match_all('/"([^"]+)"|“([^”]+)”|‘([^’]+)’/u', $text, $matches);
		$phrases = array();

		foreach ($matches as $match_group) {
			foreach ($match_group as $match) {
				$phrase = $this->normalize_search_text($match);

				if ($phrase !== '' && strpos($phrase, ' ') !== false) {
					$phrases[$phrase] = $phrase;
				}

				if (count($phrases) >= self::MAX_PHRASES) {
					return array_values($phrases);
				}
			}
		}

		return array_values($phrases);
	}

	/**
	 * Build phrase candidates used for exact phrase ranking.
	 *
	 * @param string $normalized_query Normalized full query.
	 * @param array  $quoted_phrases Quoted phrases.
	 * @param array  $terms Query terms.
	 * @return array
	 */
	private function build_phrases($normalized_query, array $quoted_phrases, array $terms) {
		$phrases = array();

		if (count($terms) > 1 && $this->contains_fulltext_term($terms)) {
			$whole_query_phrase = implode(' ', $terms);
			$phrases[$whole_query_phrase] = $whole_query_phrase;
		}

		foreach ($quoted_phrases as $phrase) {
			$phrase_terms = $this->extract_terms($phrase, true);

			if (count($phrase_terms) > 1 && $this->contains_fulltext_term($phrase_terms)) {
				$normalized_phrase = implode(' ', $phrase_terms);
				$phrases[$normalized_phrase] = $normalized_phrase;
			}

			if (count($phrases) >= self::MAX_PHRASES) {
				break;
			}
		}

		return array_values($phrases);
	}

	/**
	 * Build a safe MySQL boolean FULLTEXT query.
	 *
	 * @param array $terms Fulltext terms.
	 * @return string
	 */
	private function build_boolean_query(array $terms) {
		$parts = array();

		foreach ($terms as $term) {
			$parts[] = $term . '*';
		}

		return trim(implode(' ', array_unique($parts)));
	}

	/**
	 * Decode entities and strip markup from a query string.
	 *
	 * @param string $text Raw text.
	 * @return string
	 */
	private function decode($text) {
		$text = wp_strip_all_tags((string) $text, true);
		$text = wp_specialchars_decode($text, ENT_QUOTES);

		return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, get_bloginfo('charset'));
	}

	/**
	 * Bound query length without breaking Unicode when mbstring is available.
	 *
	 * @param string $text Raw text.
	 * @param int    $limit Length limit.
	 * @return string
	 */
	private function limit_string($text, $limit) {
		if (function_exists('mb_substr')) {
			return mb_substr($text, 0, $limit, 'UTF-8');
		}

		return substr($text, 0, $limit);
	}

	/**
	 * Return Unicode-aware string length.
	 *
	 * @param string $text Text.
	 * @return int
	 */
	private function string_length($text) {
		if (function_exists('mb_strlen')) {
			return (int) mb_strlen($text, 'UTF-8');
		}

		return strlen($text);
	}

	/**
	 * Callback for fulltext token length filtering.
	 *
	 * @param string $term Term.
	 * @return bool
	 */
	private function is_fulltext_length($term) {
		return $this->string_length($term) >= self::MIN_FULLTEXT_TERM_LENGTH;
	}

	/**
	 * Return configured minimum query word length.
	 *
	 * @return int
	 */
	private function get_min_word_length() {
		if (class_exists('VFWP_Intranet_Search_Settings')) {
			return VFWP_Intranet_Search_Settings::get_query_min_word_length();
		}

		return 2;
	}

	/**
	 * Return configured stopwords.
	 *
	 * @return array
	 */
	private function get_stopwords() {
		if (class_exists('VFWP_Intranet_Search_Settings')) {
			return VFWP_Intranet_Search_Settings::get_stopwords();
		}

		return $this->default_stopwords;
	}

	/**
	 * Extract configured exact phrases from a normalized query.
	 *
	 * @param string $normalized_query Normalized full query.
	 * @return array
	 */
	private function extract_protected_phrase_parts($normalized_query) {
		$normalized_query = trim((string) $normalized_query);

		if ($normalized_query === '' || !class_exists('VFWP_Intranet_Search_Settings')) {
			return array(
				'phrases'         => array(),
				'remaining_query' => $normalized_query,
			);
		}

		$configured_phrases = VFWP_Intranet_Search_Settings::get_exact_phrases();

		usort($configured_phrases, array($this, 'sort_strings_by_length_desc'));

		$remaining_query = $normalized_query;
		$protected_phrases = array();

		foreach ($configured_phrases as $phrase) {
			$phrase = trim((string) $phrase);

			if ($phrase === '') {
				continue;
			}

			$pattern = '/(^|\s)' . preg_quote($phrase, '/') . '(?=\s|$)/u';

			if (preg_match($pattern, $remaining_query) !== 1) {
				continue;
			}

			$protected_phrases[$phrase] = $phrase;
			$remaining_query = preg_replace($pattern, ' ', $remaining_query);
			$remaining_query = is_string($remaining_query)
				? trim(preg_replace('/\s+/u', ' ', $remaining_query))
				: '';
		}

		return array(
			'phrases'         => array_values($protected_phrases),
			'remaining_query' => $remaining_query,
		);
	}

	/**
	 * Sort strings from longest to shortest.
	 *
	 * @param string $a First value.
	 * @param string $b Second value.
	 * @return int
	 */
	private function sort_strings_by_length_desc($a, $b) {
		return $this->string_length($b) - $this->string_length($a);
	}

	/**
	 * Determine whether a term list includes at least one FULLTEXT-sized term.
	 *
	 * @param array $terms Terms.
	 * @return bool
	 */
	private function contains_fulltext_term(array $terms) {
		foreach ($terms as $term) {
			if ($this->is_fulltext_length($term)) {
				return true;
			}
		}

		return false;
	}
}
