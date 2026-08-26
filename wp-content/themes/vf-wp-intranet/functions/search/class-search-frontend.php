<?php
/**
 * Frontend helpers for rendering indexed search results in the existing theme UI.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Frontend {
	const FILTER_PARAM = 'search_type';
	const CONTENT_TYPE_PARAM = 'content_type';
	const DEFAULT_PER_PAGE = 10;

	/**
	 * Register frontend integration hooks.
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_filter('posts_pre_query', array(__CLASS__, 'short_circuit_main_search_query'), 10, 2);
		add_filter('pre_handle_404', array(__CLASS__, 'allow_indexed_search_pagination'), 10, 2);
	}

	/**
	 * Search the current frontend request.
	 *
	 * @return array
	 */
	public static function search_current_request() {
		return vfwp_intranet_search(
			self::get_query(),
			self::get_filters_for_request(),
			self::get_current_page(),
			self::get_per_page()
		);
	}

	/**
	 * Return the raw search query from the current request.
	 *
	 * @return string
	 */
	public static function get_query() {
		return get_search_query(false);
	}

	/**
	 * Return the current search page number.
	 *
	 * @return int
	 */
	public static function get_current_page() {
		$page = get_query_var('paged');

		if (!$page && isset($_GET['paged'])) {
			$page = wp_unslash($_GET['paged']);
		}

		return max(1, absint($page));
	}

	/**
	 * Return frontend result page size.
	 *
	 * @return int
	 */
	public static function get_per_page() {
		$per_page = (int) apply_filters('vfwp_intranet_search_frontend_per_page', self::DEFAULT_PER_PAGE);

		return min(VFWP_Intranet_Search_Service::MAX_PER_PAGE, max(1, $per_page));
	}

	/**
	 * Return filters for SearchService from the current request.
	 *
	 * @return array
	 */
	public static function get_filters_for_request() {
		return self::build_service_filters(self::get_selected_content_type(), self::get_selected_filter_slugs());
	}

	/**
	 * Return indexed result counts for all visible frontend filters.
	 *
	 * @return array
	 */
	public static function get_filter_counts() {
		static $filter_counts = null;

		if (is_array($filter_counts)) {
			return $filter_counts;
		}

		$query = self::get_query();
		$selected_filters = self::get_selected_filter_slugs();
		$selected_content_type = self::get_selected_content_type();
		$service = new VFWP_Intranet_Search_Service();
		$content_type_groups = $service->count_groups($query, self::build_service_filters('all', $selected_filters));
		$search_type_groups = $service->count_groups($query, self::build_service_filters($selected_content_type, array()));
		$filter_counts = array(
			'content_type' => array(),
			'search_type'  => array(),
		);

		foreach (self::get_content_type_definitions() as $content_type_slug => $definition) {
			$filter_counts['content_type'][$content_type_slug] = self::sum_grouped_counts(
				$content_type_groups,
				$definition['object_types']
			);
		}

		foreach (self::get_filter_definitions() as $filter_slug => $definition) {
			$object_types = array_values(array_intersect($definition['object_types'], self::get_object_types_for_content_type($selected_content_type)));

			$filter_counts['search_type'][$filter_slug] = self::sum_grouped_counts(
				$search_type_groups,
				$object_types,
				$definition['post_types']
			);
		}

		return $filter_counts;
	}

	/**
	 * Render a compact, accessible count for one filter option.
	 *
	 * @param int $count Result count.
	 * @return string
	 */
	public static function render_filter_count($count) {
		$count = max(0, (int) $count);
		$formatted_count = number_format_i18n($count);
		$label = sprintf(
			/* translators: %s: formatted result count. */
			_n('%s result', '%s results', $count, 'vfwp'),
			$formatted_count
		);

		return '<span class="vf-search-filter__count" aria-label="' . esc_attr($label) . '">(' . esc_html($formatted_count) . ')</span>';
	}

	/**
	 * Convert frontend filter state into SearchService filters.
	 *
	 * @param string $content_type Content-type filter slug.
	 * @param array  $selected_filters Selected search-type filter slugs.
	 * @return array
	 */
	private static function build_service_filters($content_type, array $selected_filters) {
		$content_type = sanitize_key($content_type);
		$selected_filters = array_values(array_unique(array_filter(array_map('sanitize_key', $selected_filters))));
		$allowed_object_types = self::get_object_types_for_content_type($content_type);

		if (empty($selected_filters)) {
			if ('all' === $content_type) {
				return array();
			}

			return array(
				'object_types' => $allowed_object_types,
			);
		}

		$definitions = self::get_filter_definitions();
		$enabled_post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();
		$post_types = array();
		$object_types = array();

		foreach ($selected_filters as $filter_slug) {
			if (empty($definitions[$filter_slug])) {
				continue;
			}

			$definition = $definitions[$filter_slug];
			$object_types = array_merge($object_types, $definition['object_types']);

			foreach ($definition['post_types'] as $post_type) {
				if (post_type_exists($post_type) && in_array($post_type, $enabled_post_types, true)) {
					$post_types[] = $post_type;
				}
			}
		}

		$object_types = array_values(array_unique(array_filter(array_map('sanitize_key', $object_types))));
		$post_types = array_values(array_unique(array_filter(array_map('sanitize_key', $post_types))));
		$object_types = array_values(array_intersect($object_types, $allowed_object_types));

		if (empty($post_types)) {
			$object_types = array_values(array_diff($object_types, array('post')));
		}

		if (empty($object_types)) {
			return array(
				'object_types' => array('post'),
				'post_types'   => array('__none__'),
			);
		}

		$filters = array(
			'object_types' => $object_types,
		);

		if (!empty($post_types)) {
			$filters['post_types'] = $post_types;
		}

		return $filters;
	}

	/**
	 * Sum grouped SearchService counts for a filter definition.
	 *
	 * @param array $groups Grouped counts.
	 * @param array $object_types Object types to include.
	 * @param array $post_types Post types to include when object_type includes post.
	 * @return int
	 */
	private static function sum_grouped_counts(array $groups, array $object_types, array $post_types = array()) {
		$total = 0;
		$object_types = array_values(array_unique(array_filter(array_map('sanitize_key', $object_types))));
		$post_types = array_values(array_unique(array_filter(array_map('sanitize_key', $post_types))));

		foreach ($object_types as $object_type) {
			if ('post' !== $object_type) {
				$total += isset($groups['object_types'][$object_type]) ? (int) $groups['object_types'][$object_type] : 0;
				continue;
			}

			if (empty($post_types)) {
				$total += isset($groups['object_types']['post']) ? (int) $groups['object_types']['post'] : 0;
				continue;
			}

			foreach ($post_types as $post_type) {
				$total += isset($groups['post_types'][$post_type]) ? (int) $groups['post_types'][$post_type] : 0;
			}
		}

		return $total;
	}

	/**
	 * Return selected frontend filter slugs.
	 *
	 * @return array
	 */
	public static function get_selected_filter_slugs() {
		$definitions = self::get_filter_definitions();
		$selected = array();

		if (isset($_GET[self::FILTER_PARAM])) {
			$raw_values = (array) wp_unslash($_GET[self::FILTER_PARAM]);

			foreach ($raw_values as $raw_value) {
				$slug = self::normalize_filter_slug($raw_value);

				if (isset($definitions[$slug])) {
					$selected[] = $slug;
				}
			}
		}

		if (empty($selected)) {
			$legacy_post_type = self::get_legacy_post_type();

			if ($legacy_post_type !== '') {
				$legacy_map = self::get_legacy_post_type_filter_map();

				if (isset($legacy_map[$legacy_post_type])) {
					$selected[] = $legacy_map[$legacy_post_type];
				}
			}
		}

		return array_values(array_unique($selected));
	}

	/**
	 * Determine if a UI filter is selected.
	 *
	 * @param string $filter_slug Filter slug.
	 * @return bool
	 */
	public static function is_filter_selected($filter_slug) {
		return in_array($filter_slug, self::get_selected_filter_slugs(), true);
	}

	/**
	 * Return content-type filter definitions.
	 *
	 * @return array
	 */
	public static function get_content_type_definitions() {
		return array(
			'all' => array(
				'label'        => __('All', 'vfwp'),
				'description'  => __('Web pages and PDF documents', 'vfwp'),
				'object_types' => array('post', 'pdf'),
			),
			'web' => array(
				'label'        => __('Web pages', 'vfwp'),
				'description'  => __('Pages, posts and enabled custom post types', 'vfwp'),
				'object_types' => array('post'),
			),
			'pdf' => array(
				'label'        => __('PDF documents', 'vfwp'),
				'description'  => __('Indexed PDF document text', 'vfwp'),
				'object_types' => array('pdf'),
			),
		);
	}

	/**
	 * Return selected content-type filter.
	 *
	 * @return string all|web|pdf
	 */
	public static function get_selected_content_type() {
		if (empty($_GET[self::CONTENT_TYPE_PARAM])) {
			return 'all';
		}

		$content_type = sanitize_key(wp_unslash($_GET[self::CONTENT_TYPE_PARAM]));
		$definitions = self::get_content_type_definitions();

		return isset($definitions[$content_type]) ? $content_type : 'all';
	}

	/**
	 * Determine if a content type option is selected.
	 *
	 * @param string $content_type Content type option.
	 * @return bool
	 */
	public static function is_content_type_selected($content_type) {
		return self::get_selected_content_type() === $content_type;
	}

	/**
	 * Prevent the unused frontend main search query from scanning wp_posts.
	 *
	 * The search template renders results from SearchService, so the normal
	 * main query only needs to preserve WordPress' search routing context.
	 *
	 * @param array|null $posts Posts from a previous short-circuit.
	 * @param WP_Query   $query Query object.
	 * @return array|null
	 */
	public static function short_circuit_main_search_query($posts, $query) {
		if (!self::is_frontend_main_search_query($query)) {
			return $posts;
		}

		$query->found_posts = 0;
		$query->max_num_pages = 0;

		return array();
	}

	/**
	 * Let the indexed search template handle paginated search requests.
	 *
	 * @param bool     $preempt Whether to preempt WordPress' 404 handling.
	 * @param WP_Query $query   Query object.
	 * @return bool
	 */
	public static function allow_indexed_search_pagination($preempt, $query) {
		if (!self::is_frontend_main_search_query($query)) {
			return $preempt;
		}

		return true;
	}

	/**
	 * Return existing frontend search filter definitions.
	 *
	 * @return array
	 */
	public static function get_filter_definitions() {
		return array(
			'page'          => array(
				'label'        => __('Page', 'vfwp'),
				'query_value'  => 'Page',
				'post_types'   => array('page', 'teams'),
				'object_types' => array('post'),
			),
			'people'        => array(
				'label'        => __('People', 'vfwp'),
				'query_value'  => 'People',
				'post_types'   => array('people'),
				'object_types' => array('post'),
			),
			'documents'     => array(
				'label'        => __('Documents', 'vfwp'),
				'query_value'  => 'Documents',
				'post_types'   => array('documents'),
				'object_types' => array('post', 'pdf'),
			),
			'announcements' => array(
				'label'        => __('Announcements', 'vfwp'),
				'query_value'  => 'Announcements',
				'post_types'   => array('community-blog'),
				'object_types' => array('post'),
			),
			'news'          => array(
				'label'        => __('News', 'vfwp'),
				'query_value'  => 'News',
				'post_types'   => array('insites'),
				'object_types' => array('post'),
			),
			'events'        => array(
				'label'        => __('Events', 'vfwp'),
				'query_value'  => 'Events',
				'post_types'   => array('events', 'vf_event'),
				'object_types' => array('post'),
			),
			'training'      => array(
				'label'        => __('Training', 'vfwp'),
				'query_value'  => 'Training',
				'post_types'   => array('training'),
				'object_types' => array('post'),
			),
		);
	}

	/**
	 * Return selected legacy post_type filter, if present.
	 *
	 * @return string
	 */
	public static function get_legacy_post_type() {
		if (empty($_GET['post_type'])) {
			return '';
		}

		$post_type = wp_unslash($_GET['post_type']);

		if (is_array($post_type)) {
			$post_type = reset($post_type);
		}

		$post_type = sanitize_key($post_type);

		return 'any' === $post_type ? '' : $post_type;
	}

	/**
	 * Return a short result count summary.
	 *
	 * @param array $pagination SearchService pagination data.
	 * @return string
	 */
	public static function get_result_count_text(array $pagination, $query = null) {
		$total = isset($pagination['total']) ? (int) $pagination['total'] : 0;
		$query = null === $query ? self::get_query() : (string) $query;
		$query = trim($query);

		if ($total < 1) {
			if ($query !== '') {
				return sprintf(
					/* translators: %s: search query. */
					__('No results for "%s"', 'vfwp'),
					$query
				);
			}

			return __('No results found', 'vfwp');
		}

		if ($query !== '') {
			return sprintf(
				/* translators: 1: result count, 2: search query. */
				_n('%1$d result for "%2$s"', '%1$d results for "%2$s"', $total, 'vfwp'),
				$total,
				$query
			);
		}

		return sprintf(
			/* translators: %d: result count. */
			_n('%d result', '%d results', $total, 'vfwp'),
			$total
		);
	}

	/**
	 * Determine whether the current search has active filters.
	 *
	 * @return bool
	 */
	public static function has_active_filters() {
		return self::get_selected_content_type() !== 'all' || !empty(self::get_selected_filter_slugs());
	}

	/**
	 * Return active filter chips with labels and remove URLs.
	 *
	 * @return array
	 */
	public static function get_active_filters() {
		$active_filters = array();
		$content_type = self::get_selected_content_type();
		$content_type_definitions = self::get_content_type_definitions();

		if ($content_type !== 'all' && isset($content_type_definitions[$content_type])) {
			$active_filters[] = array(
				'type'       => 'content_type',
				'slug'       => $content_type,
				'label'      => $content_type_definitions[$content_type]['label'],
				'remove_url' => self::get_remove_filter_url('content_type', $content_type),
			);
		}

		$filter_definitions = self::get_filter_definitions();

		foreach (self::get_selected_filter_slugs() as $filter_slug) {
			if (!isset($filter_definitions[$filter_slug])) {
				continue;
			}

			$active_filters[] = array(
				'type'       => 'search_type',
				'slug'       => $filter_slug,
				'label'      => $filter_definitions[$filter_slug]['label'],
				'remove_url' => self::get_remove_filter_url('search_type', $filter_slug),
			);
		}

		return $active_filters;
	}

	/**
	 * Render active filters with remove links.
	 *
	 * @return string
	 */
	public static function render_active_filters() {
		$active_filters = self::get_active_filters();

		if (empty($active_filters)) {
			return '';
		}

		$html = '<div class="vf-cluster | vf-u-margin__bottom--400" aria-label="' . esc_attr__('Active filters', 'vfwp') . '">';
		$html .= '<span class="vf-text-body vf-text-body--5">' . esc_html__('Active filters:', 'vfwp') . '</span>';

		foreach ($active_filters as $active_filter) {
			$html .= '<a class="vf-badge vf-badge--tertiary | vf-u-margin__right--100" href="' . esc_url($active_filter['remove_url']) . '">';
			$html .= esc_html($active_filter['label']) . ' <span aria-hidden="true">x</span>';
			$html .= '<span class="vf-u-sr-only"> ' . esc_html__('Remove filter', 'vfwp') . '</span>';
			$html .= '</a>';
		}

		$html .= '<a class="vf-link" href="' . esc_url(self::get_clear_filters_url()) . '">' . esc_html__('Clear filters', 'vfwp') . '</a>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Return URL that clears filters but keeps the search query.
	 *
	 * @return string
	 */
	public static function get_clear_filters_url() {
		return self::build_search_url(
			array(
				self::CONTENT_TYPE_PARAM => 'all',
				self::FILTER_PARAM       => array(),
			)
		);
	}

	/**
	 * Return URL for removing one active filter.
	 *
	 * @param string $filter_type content_type|search_type.
	 * @param string $filter_slug Filter slug.
	 * @return string
	 */
	public static function get_remove_filter_url($filter_type, $filter_slug) {
		$content_type = self::get_selected_content_type();
		$selected_filters = self::get_selected_filter_slugs();

		if ('content_type' === $filter_type) {
			$content_type = 'all';
		}

		if ('search_type' === $filter_type) {
			$selected_filters = array_values(array_diff($selected_filters, array($filter_slug)));
		}

		return self::build_search_url(
			array(
				self::CONTENT_TYPE_PARAM => $content_type,
				self::FILTER_PARAM       => $selected_filters,
			)
		);
	}

	/**
	 * Render server-side pagination using existing VF classes.
	 *
	 * @param array $pagination SearchService pagination data.
	 * @return string
	 */
	public static function render_pagination(array $pagination) {
		$total_pages = isset($pagination['total_pages']) ? (int) $pagination['total_pages'] : 0;
		$current = isset($pagination['page']) ? max(1, (int) $pagination['page']) : 1;

		if ($total_pages < 2) {
			return '';
		}

		$items = array();

		$items[] = self::render_pagination_previous($current);

		foreach (self::get_pagination_numbers($current, $total_pages) as $number) {
			if ('ellipsis' === $number) {
				$items[] = '<li class="vf-pagination__item"><span class="vf-pagination__label">&hellip;</span></li>';
				continue;
			}

			$items[] = self::render_pagination_number((int) $number, $current);
		}

		$items[] = self::render_pagination_next($current, $total_pages);

		return sprintf(
			'<nav class="vf-pagination" aria-label="%1$s"><ul class="vf-pagination__list">%2$s</ul></nav>',
			esc_attr__('Pagination', 'vfwp'),
			implode('', $items)
		);
	}

	/**
	 * Return URL for a frontend search page.
	 *
	 * @param int $page Page number.
	 * @return string
	 */
	public static function get_pagination_url($page) {
		return self::build_search_url(
			array(
				self::CONTENT_TYPE_PARAM => self::get_selected_content_type(),
				self::FILTER_PARAM       => self::get_selected_filter_slugs(),
				'paged'                  => (int) $page,
			)
		);
	}

	/**
	 * Normalize a filter value from GET.
	 *
	 * @param mixed $raw_value Raw value.
	 * @return string
	 */
	private static function normalize_filter_slug($raw_value) {
		return sanitize_title((string) $raw_value);
	}

	/**
	 * Return object types allowed by the selected content-type filter.
	 *
	 * @param string $content_type Content type option.
	 * @return array
	 */
	private static function get_object_types_for_content_type($content_type) {
		$definitions = self::get_content_type_definitions();

		if (!isset($definitions[$content_type])) {
			$content_type = 'all';
		}

		return $definitions[$content_type]['object_types'];
	}

	/**
	 * Build a frontend search URL from filter slugs.
	 *
	 * @param array $overrides URL state overrides.
	 * @return string
	 */
	private static function build_search_url(array $overrides) {
		$args = array(
			's' => self::get_query(),
		);

		$content_type = isset($overrides[self::CONTENT_TYPE_PARAM])
			? sanitize_key($overrides[self::CONTENT_TYPE_PARAM])
			: self::get_selected_content_type();

		if ($content_type !== 'all') {
			$args[self::CONTENT_TYPE_PARAM] = $content_type;
		}

		$selected_filters = array_key_exists(self::FILTER_PARAM, $overrides)
			? (array) $overrides[self::FILTER_PARAM]
			: self::get_selected_filter_slugs();
		$selected_filters = array_values(array_unique(array_filter(array_map('sanitize_key', $selected_filters))));
		$definitions = self::get_filter_definitions();

		if (!empty($selected_filters)) {
			$args[self::FILTER_PARAM] = array();

			foreach ($selected_filters as $filter_slug) {
				if (isset($definitions[$filter_slug])) {
					$args[self::FILTER_PARAM][] = $definitions[$filter_slug]['query_value'];
				}
			}
		}

		$paged = isset($overrides['paged']) ? (int) $overrides['paged'] : 1;

		if ($paged > 1) {
			$args['paged'] = $paged;
		}

		return add_query_arg($args, home_url('/'));
	}

	/**
	 * Determine whether a query is the public main search query.
	 *
	 * @param mixed $query Query object.
	 * @return bool
	 */
	private static function is_frontend_main_search_query($query) {
		return !is_admin()
			&& $query instanceof WP_Query
			&& $query->is_main_query()
			&& $query->is_search();
	}

	/**
	 * Map the old hidden post_type select values to visible filters.
	 *
	 * @return array
	 */
	private static function get_legacy_post_type_filter_map() {
		return array(
			'page'      => 'page',
			'teams'     => 'page',
			'people'    => 'people',
			'documents' => 'documents',
			'insites'   => 'news',
			'events'    => 'events',
			'vf_event'  => 'events',
			'training'  => 'training',
		);
	}

	/**
	 * Return compact pagination numbers with ellipses.
	 *
	 * @param int $current Current page.
	 * @param int $total_pages Total pages.
	 * @return array
	 */
	private static function get_pagination_numbers($current, $total_pages) {
		$pages = array(1, $total_pages);

		for ($page = $current - 2; $page <= $current + 2; $page++) {
			if ($page > 1 && $page < $total_pages) {
				$pages[] = $page;
			}
		}

		$pages = array_values(array_unique(array_filter($pages)));
		sort($pages);

		$numbers = array();
		$previous = 0;

		foreach ($pages as $page) {
			if ($previous > 0 && $page > $previous + 1) {
				$numbers[] = 'ellipsis';
			}

			$numbers[] = $page;
			$previous = $page;
		}

		return $numbers;
	}

	/**
	 * Render a previous-page item.
	 *
	 * @param int $current Current page.
	 * @return string
	 */
	private static function render_pagination_previous($current) {
		if ($current <= 1) {
			return '<li class="vf-pagination__item vf-pagination__item--previous-page"><span class="vf-pagination__label">Previous <span class="vf-u-sr-only"> page</span></span></li>';
		}

		return sprintf(
			'<li class="vf-pagination__item vf-pagination__item--previous-page"><a class="vf-pagination__link" href="%s">Previous <span class="vf-u-sr-only"> page</span></a></li>',
			esc_url(self::get_pagination_url($current - 1))
		);
	}

	/**
	 * Render a next-page item.
	 *
	 * @param int $current Current page.
	 * @param int $total_pages Total pages.
	 * @return string
	 */
	private static function render_pagination_next($current, $total_pages) {
		if ($current >= $total_pages) {
			return '<li class="vf-pagination__item vf-pagination__item--next-page"><span class="vf-pagination__label">Next <span class="vf-u-sr-only"> page</span></span></li>';
		}

		return sprintf(
			'<li class="vf-pagination__item vf-pagination__item--next-page"><a class="vf-pagination__link" href="%s">Next <span class="vf-u-sr-only"> page</span></a></li>',
			esc_url(self::get_pagination_url($current + 1))
		);
	}

	/**
	 * Render one pagination number.
	 *
	 * @param int $number Page number.
	 * @param int $current Current page.
	 * @return string
	 */
	private static function render_pagination_number($number, $current) {
		if ($number === $current) {
			return sprintf(
				'<li class="vf-pagination__item vf-pagination__item--is-active"><span class="vf-pagination__label" aria-current="page"><span class="vf-u-sr-only">Page </span>%d</span></li>',
				$number
			);
		}

		return sprintf(
			'<li class="vf-pagination__item"><a href="%s" class="vf-pagination__link"><span class="vf-u-sr-only">Page </span>%d</a></li>',
			esc_url(self::get_pagination_url($number)),
			$number
		);
	}
}
