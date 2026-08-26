<?php
/**
 * WP-CLI commands for the theme search index.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_CLI_Command {
	/**
	 * Show search index status.
	 *
	 * ## EXAMPLES
	 *
	 *     wp theme-search index status
	 *
	 * @return void
	 */
	public function status() {
		$manager = new VFWP_Intranet_Search_Index_Manager();
		$data = $manager->get_dashboard_data();
		$status = $data['status'];
		$counts = $data['counts'];
		$failed_items = max((int) $status['failed'], (int) $data['pdf_issue_count']);

		WP_CLI::line('Status: ' . $status['status']);
		WP_CLI::line('Mode: ' . ($status['mode'] !== '' ? $status['mode'] : 'none'));
		WP_CLI::line('Phase: ' . ($status['phase'] !== '' ? $status['phase'] : 'none'));
		WP_CLI::line('Schema: ' . $data['installed_version'] . ' / ' . $data['schema_version']);
		WP_CLI::line('Total indexed: ' . $counts['total']);
		WP_CLI::line('Indexed web pages: ' . $counts['web']);
		WP_CLI::line('Indexed PDFs: ' . $counts['pdf']);
		WP_CLI::line('Processed: ' . $status['processed'] . ' / ' . $status['total_planned']);
		WP_CLI::line('Pending: ' . max(0, (int) $status['total_planned'] - (int) $status['processed']));
		WP_CLI::line('Failed: ' . $failed_items);
		WP_CLI::line('Last full rebuild: ' . ($data['last_full_rebuild'] !== '' ? $data['last_full_rebuild'] : 'never'));
		WP_CLI::line('Rebuild required: ' . (!empty($data['rebuild_required']['required']) ? 'yes' : 'no'));
	}

	/**
	 * Start a full search index rebuild.
	 *
	 * ## OPTIONS
	 *
	 * [--wait]
	 * : Process batches synchronously until complete.
	 *
	 * [--batch-size=<number>]
	 * : Batch size. Defaults to 25.
	 *
	 * ## EXAMPLES
	 *
	 *     wp theme-search index rebuild --wait
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Assoc args.
	 * @return void
	 */
	public function rebuild($args, $assoc_args) {
		$this->start_and_optionally_wait('full', $assoc_args);
	}

	/**
	 * Reindex changed content. Unchanged objects are skipped by hashes.
	 *
	 * ## OPTIONS
	 *
	 * [--wait]
	 * : Process batches synchronously until complete.
	 *
	 * [--batch-size=<number>]
	 * : Batch size. Defaults to 25.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Assoc args.
	 * @return void
	 */
	public function changed($args, $assoc_args) {
		$this->start_and_optionally_wait('changed', $assoc_args);
	}

	/**
	 * Clear the index table and rebuild it in batches.
	 *
	 * ## OPTIONS
	 *
	 * [--wait]
	 * : Process batches synchronously until complete.
	 *
	 * [--batch-size=<number>]
	 * : Batch size. Defaults to 25.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Assoc args.
	 * @return void
	 */
	public function clear($args, $assoc_args) {
		$this->start_and_optionally_wait('clear_rebuild', $assoc_args);
	}

	/**
	 * Process one pending batch.
	 *
	 * ## EXAMPLES
	 *
	 *     wp theme-search index run-batch
	 *
	 * @return void
	 */
	public function run_batch() {
		$manager = new VFWP_Intranet_Search_Index_Manager();
		$status = $manager->process_batch();

		WP_CLI::line('Status: ' . $status['status'] . '; phase: ' . $status['phase'] . '; processed: ' . $status['processed'] . '/' . $status['total_planned']);
	}

	/**
	 * Run snippet/highlight self-tests.
	 *
	 * ## EXAMPLES
	 *
	 *     wp theme-search index test_snippets
	 *
	 * @return void
	 */
	public function test_snippets() {
		$parser = new VFWP_Intranet_Search_Query_Parser();
		$snippet_service = new VFWP_Intranet_Search_Snippet_Service($parser);
		$passed = 0;

		$this->assert_contains(
			'<mark>Alpha</mark>',
			$this->build_snippet_test_result($parser, $snippet_service, 'alpha', 'Alpha title', '', 'Some alpha passage.', 'post')['display']['title'],
			'one search term highlights title'
		);
		$passed++;

		$multi = $this->build_snippet_test_result($parser, $snippet_service, 'alpha gamma', 'Multiple terms', '', 'Alpha appears near the beginning and gamma appears nearby.', 'post');
		$this->assert_contains('<mark>Alpha</mark>', $multi['display']['snippet'], 'multiple terms highlight first term');
		$this->assert_contains('<mark>gamma</mark>', $multi['display']['snippet'], 'multiple terms highlight second term');
		$passed++;

		$phrase = $this->build_snippet_test_result($parser, $snippet_service, '"alpha beta"', 'Phrase test', '', 'The alpha beta phrase appears here.', 'post');
		$this->assert_contains('<mark>alpha beta</mark>', $phrase['display']['snippet'], 'phrase search highlights phrase');
		$passed++;

		$parentheses = $this->build_snippet_test_result($parser, $snippet_service, 'alpha (beta)', 'Parentheses test', '', 'Alpha and beta are both present.', 'post');
		$this->assert_contains('<mark>Alpha</mark>', $parentheses['display']['snippet'], 'parentheses query highlights safely');
		$this->assert_contains('<mark>beta</mark>', $parentheses['display']['snippet'], 'parentheses query highlights second term safely');
		$passed++;

		$regex_chars = $this->build_snippet_test_result($parser, $snippet_service, '+ . ? * alpha net', 'Regex chars', '', 'Alpha and net appear without regex failures.', 'post');
		$this->assert_contains('<mark>Alpha</mark>', $regex_chars['display']['snippet'], 'regex characters do not break highlighting');
		$this->assert_contains('<mark>net</mark>', $regex_chars['display']['snippet'], 'regex-like query terms highlight safely');
		$passed++;

		$html = $this->build_snippet_test_result($parser, $snippet_service, 'alpha', '<script>alert(1)</script> Alpha', '', '<strong>Alpha</strong> & beta', 'post');
		$this->assert_not_contains('<script', $html['display']['title'], 'HTML title is not rendered');
		$this->assert_not_contains('<strong', $html['display']['snippet'], 'HTML snippet is not rendered');
		$this->assert_contains('<mark>Alpha</mark>', $html['display']['snippet'], 'HTML content is highlighted after stripping tags');
		$passed++;

		$unicode = $this->build_snippet_test_result($parser, $snippet_service, 'cafe', 'Unicode test', '', 'Café society has accented text.', 'post');
		$this->assert_contains('<mark>Café</mark>', $unicode['display']['snippet'], 'accented Unicode text highlights');
		$passed++;

		$pdf = $this->build_snippet_test_result($parser, $snippet_service, 'pdf alpha', 'PDF result', '', 'Extracted PDF content mentions alpha in a useful passage.', 'pdf');
		$this->assert_contains('<mark>PDF</mark>', $pdf['display']['snippet'], 'PDF content highlights from extracted text');
		$this->assert_equals('content', $pdf['snippet_field'], 'PDF snippet uses extracted content field');
		$passed++;

		$missing_excerpt = $this->build_snippet_test_result($parser, $snippet_service, 'fallback', 'Missing excerpt', '', 'Content fallback contains the matching fallback term.', 'post');
		$this->assert_contains('<mark>fallback</mark>', $missing_excerpt['display']['snippet'], 'missing excerpt falls back to content');
		$passed++;

		$no_match = $this->build_snippet_test_result($parser, $snippet_service, 'alpha', 'No matching title', 'Plain fallback excerpt.', 'No matching passage here.', 'post');
		$this->assert_contains('Plain fallback excerpt', $no_match['snippet'], 'no matching passage falls back to excerpt');
		$this->assert_not_contains('<mark>', $no_match['display']['snippet'], 'no matching passage does not invent highlights');
		$passed++;

		WP_CLI::success('Snippet tests passed: ' . $passed);
	}

	/**
	 * Start a job and optionally wait for completion.
	 *
	 * @param string $mode Mode.
	 * @param array  $assoc_args Assoc args.
	 * @return void
	 */
	private function start_and_optionally_wait($mode, array $assoc_args) {
		$manager = new VFWP_Intranet_Search_Index_Manager();
		$batch_size = VFWP_Intranet_Search_Index_Manager::DEFAULT_BATCH_SIZE;

		if (isset($assoc_args['batch-size'])) {
			$batch_size = (int) $assoc_args['batch-size'];
		} elseif (isset($assoc_args['batch_size'])) {
			$batch_size = (int) $assoc_args['batch_size'];
		}

		if ($mode === 'changed') {
			$result = $manager->start_changed_reindex($batch_size);
		} elseif ($mode === 'clear_rebuild') {
			$result = $manager->start_full_rebuild(true, $batch_size);
		} else {
			$result = $manager->start_full_rebuild(false, $batch_size);
		}

		if (empty($result['started'])) {
			WP_CLI::warning($result['message']);
			return;
		}

		WP_CLI::success($result['message']);

		if (empty($assoc_args['wait'])) {
			return;
		}

		do {
			$status = $manager->process_batch();
			WP_CLI::line('Batch: ' . $status['status'] . '; phase: ' . $status['phase'] . '; processed: ' . $status['processed'] . '/' . $status['total_planned']);
		} while (!empty($status['active']));

		if ($status['status'] === 'completed') {
			WP_CLI::success('Search index job completed.');
		} else {
			WP_CLI::error('Search index job ended with status: ' . $status['status']);
		}
	}

	/**
	 * Build one direct snippet test result.
	 *
	 * @param VFWP_Intranet_Search_Query_Parser    $parser Parser.
	 * @param VFWP_Intranet_Search_Snippet_Service $snippet_service Snippet service.
	 * @param string                               $query Query.
	 * @param string                               $title Title.
	 * @param string                               $excerpt Excerpt.
	 * @param string                               $content Content.
	 * @param string                               $object_type Object type.
	 * @return array
	 */
	private function build_snippet_test_result($parser, $snippet_service, $query, $title, $excerpt, $content, $object_type) {
		$parsed = $parser->parse($query);
		$result = array(
			'object_id'      => 1,
			'object_type'    => $object_type,
			'post_type'      => $object_type === 'pdf' ? 'attachment' : 'post',
			'title'          => $title,
			'url'            => '',
			'relevance'      => 1,
			'snippet_source' => array(
				'title'        => $title,
				'excerpt'      => $excerpt,
				'content'      => $content,
				'acf_keywords' => '',
			),
		);

		return $snippet_service->add_display_fields($result, $parsed);
	}

	/**
	 * Assert a string contains a substring.
	 *
	 * @param string $needle Needle.
	 * @param string $haystack Haystack.
	 * @param string $message Message.
	 * @return void
	 */
	private function assert_contains($needle, $haystack, $message) {
		if (strpos($haystack, $needle) === false) {
			WP_CLI::error($message . ' failed. Expected to find: ' . $needle . ' in: ' . $haystack);
		}
	}

	/**
	 * Assert a string does not contain a substring.
	 *
	 * @param string $needle Needle.
	 * @param string $haystack Haystack.
	 * @param string $message Message.
	 * @return void
	 */
	private function assert_not_contains($needle, $haystack, $message) {
		if (strpos($haystack, $needle) !== false) {
			WP_CLI::error($message . ' failed. Unexpected substring: ' . $needle . ' in: ' . $haystack);
		}
	}

	/**
	 * Assert two values are equal.
	 *
	 * @param mixed  $expected Expected.
	 * @param mixed  $actual Actual.
	 * @param string $message Message.
	 * @return void
	 */
	private function assert_equals($expected, $actual, $message) {
		if ($expected !== $actual) {
			WP_CLI::error($message . ' failed. Expected ' . $expected . ', got ' . $actual . '.');
		}
	}
}
