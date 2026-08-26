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
	private $stopwords = array(
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
		'it',
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
		$all_terms = $this->extract_terms($normalized_query, false);
		$terms = $this->extract_terms($normalized_query, true);
		$fulltext_terms = array();
		$ignored_terms = array();

		foreach ($terms as $term) {
			if ($this->string_length($term) >= self::MIN_FULLTEXT_TERM_LENGTH) {
				$fulltext_terms[] = $term;
			} else {
				$ignored_terms[] = $term;
			}
		}

		$phrases = $this->build_phrases($normalized_query, $quoted_phrases, $fulltext_terms);
		$boolean_query = $this->build_boolean_query($fulltext_terms);

		return array(
			'raw'              => $raw_query,
			'normalized'       => $normalized_query,
			'terms'            => $terms,
			'all_terms'        => $all_terms,
			'fulltext_terms'   => $fulltext_terms,
			'ignored_terms'    => $ignored_terms,
			'phrases'          => $phrases,
			'exact_phrase'     => isset($phrases[0]) ? $phrases[0] : '',
			'boolean_query'    => $boolean_query,
			'is_empty'         => $normalized_query === '',
			'is_searchable'    => $boolean_query !== '',
			'min_term_length'  => self::MIN_FULLTEXT_TERM_LENGTH,
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

		foreach ($matches[0] as $term) {
			if ($remove_stopwords && in_array($term, $this->stopwords, true)) {
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
	 * @param array  $fulltext_terms Searchable fulltext terms.
	 * @return array
	 */
	private function build_phrases($normalized_query, array $quoted_phrases, array $fulltext_terms) {
		$phrases = array();

		if (count($fulltext_terms) > 1) {
			$whole_query_phrase = implode(' ', $fulltext_terms);
			$phrases[$whole_query_phrase] = $whole_query_phrase;
		}

		foreach ($quoted_phrases as $phrase) {
			$phrase_terms = $this->extract_terms($phrase, true);
			$phrase_terms = array_filter($phrase_terms, array($this, 'is_fulltext_length'));

			if (count($phrase_terms) > 1) {
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
}
