<?php
/**
 * Admin settings for the theme search system.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Settings {
	const OPTION_NAME = 'vfwp_intranet_search_settings';
	const REBUILD_REQUIRED_OPTION = 'vfwp_intranet_search_rebuild_required';
	const ADMIN_CAPABILITY = 'manage_options';

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action('admin_menu', array($this, 'add_settings_page'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	/**
	 * Default search settings.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'field_weights'   => array(
				'title'        => 10,
				'acf_keywords' => 7,
				'excerpt'      => 4,
				'content'      => 1,
			),
			'post_types'      => array(),
			'ranking_boosts'  => self::default_ranking_boosts(),
			'acf_field_names' => array(),
			'query_min_word_length' => 2,
			'stopwords'       => self::default_stopwords(),
			'exact_phrases'   => array(),
			'synonyms'        => array(),
			'analytics'       => array(
				'enabled'        => 1,
				'exclude_admins' => 0,
				'track_user_email' => 0,
				'retention_days' => 180,
			),
		);
	}

	/**
	 * Return merged settings with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$settings = get_option(self::OPTION_NAME, array());

		if (!is_array($settings)) {
			$settings = array();
		}

		$settings = wp_parse_args($settings, self::defaults());

		if (!is_array($settings['field_weights'])) {
			$settings['field_weights'] = array();
		}

		$settings['field_weights'] = wp_parse_args($settings['field_weights'], self::defaults()['field_weights']);

		if (!isset($settings['ranking_boosts']) || !is_array($settings['ranking_boosts'])) {
			$settings['ranking_boosts'] = array();
		}

		$settings['ranking_boosts'] = wp_parse_args($settings['ranking_boosts'], self::default_ranking_boosts());
		$settings['ranking_boosts'] = self::sanitize_ranking_boosts($settings['ranking_boosts']);

		if (!is_array($settings['post_types'])) {
			$settings['post_types'] = array();
		}

		$settings['acf_field_names'] = self::parse_acf_field_names($settings['acf_field_names']);
		$settings['query_min_word_length'] = self::sanitize_min_word_length_value($settings['query_min_word_length']);
		$settings['stopwords'] = self::parse_stopwords($settings['stopwords']);
		$settings['exact_phrases'] = self::parse_exact_phrases($settings['exact_phrases']);
		$settings['synonyms'] = self::parse_synonyms(isset($settings['synonyms']) ? $settings['synonyms'] : array());
		$settings['analytics'] = self::sanitize_analytics_settings(isset($settings['analytics']) ? $settings['analytics'] : array());

		return $settings;
	}

	/**
	 * Return configured field weights.
	 *
	 * @return array
	 */
	public static function get_field_weights() {
		return self::get_settings()['field_weights'];
	}

	/**
	 * Return configured ranking boost multipliers.
	 *
	 * @return array
	 */
	public static function get_ranking_boosts() {
		return self::get_settings()['ranking_boosts'];
	}

	/**
	 * Return configured ACF field names.
	 *
	 * @return array
	 */
	public static function get_acf_field_names() {
		return self::get_settings()['acf_field_names'];
	}

	/**
	 * Return minimum query word length for term matching.
	 *
	 * @return int
	 */
	public static function get_query_min_word_length() {
		return (int) self::get_settings()['query_min_word_length'];
	}

	/**
	 * Return configured query stopwords.
	 *
	 * @return array
	 */
	public static function get_stopwords() {
		return self::get_settings()['stopwords'];
	}

	/**
	 * Return configured exact phrase searches.
	 *
	 * @return array
	 */
	public static function get_exact_phrases() {
		return self::get_settings()['exact_phrases'];
	}

	/**
	 * Return configured directional query synonyms.
	 *
	 * @return array
	 */
	public static function get_synonyms() {
		return self::get_settings()['synonyms'];
	}

	/**
	 * Return analytics settings.
	 *
	 * @return array
	 */
	public static function get_analytics_settings() {
		return self::get_settings()['analytics'];
	}

	/**
	 * Return public post types that are appropriate for frontend search.
	 *
	 * @return array
	 */
	public static function get_searchable_post_types() {
		$post_types = get_post_types(array('public' => true), 'objects');
		$searchable = array();

		foreach ($post_types as $post_type => $post_type_object) {
			if ('attachment' === $post_type) {
				continue;
			}

			if (!empty($post_type_object->exclude_from_search)) {
				continue;
			}

			$searchable[$post_type] = $post_type_object;
		}

		return $searchable;
	}

	/**
	 * Return enabled post type names.
	 *
	 * @return array
	 */
	public static function get_enabled_post_types() {
		$settings = self::get_settings();
		$enabled = array();

		foreach (self::get_searchable_post_types() as $post_type => $post_type_object) {
			$post_type_settings = self::get_post_type_setting($post_type, $settings);

			if (!empty($post_type_settings['include'])) {
				$enabled[] = $post_type;
			}
		}

		return $enabled;
	}

	/**
	 * Determine whether one post type is included in the search index.
	 *
	 * @param string $post_type Post type name.
	 * @return bool
	 */
	public static function is_post_type_enabled($post_type) {
		$searchable_post_types = self::get_searchable_post_types();

		if (!isset($searchable_post_types[$post_type])) {
			return false;
		}

		$post_type_settings = self::get_post_type_setting($post_type);

		return !empty($post_type_settings['include']);
	}

	/**
	 * Return one post type's ranking weight.
	 *
	 * @param string $post_type Post type name.
	 * @return float
	 */
	public static function get_post_type_weight($post_type) {
		$post_type_settings = self::get_post_type_setting($post_type);

		return (float) $post_type_settings['weight'];
	}

	/**
	 * Return one post type's include/weight settings.
	 *
	 * @param string     $post_type Post type name.
	 * @param array|null $settings Optional settings array.
	 * @return array
	 */
	public static function get_post_type_setting($post_type, $settings = null) {
		$settings = is_array($settings) ? $settings : self::get_settings();
		$stored = isset($settings['post_types'][$post_type]) && is_array($settings['post_types'][$post_type])
			? $settings['post_types'][$post_type]
			: array();

		return wp_parse_args(
			$stored,
			array(
				'include' => 1,
				'weight'  => 1,
			)
		);
	}

	/**
	 * Mark the content index as requiring a rebuild.
	 *
	 * @param string $reason Reason text.
	 * @return void
	 */
	public static function mark_rebuild_required($reason) {
		update_option(
			self::REBUILD_REQUIRED_OPTION,
			array(
				'required'  => 1,
				'reason'    => sanitize_text_field($reason),
				'marked_at' => current_time('mysql', true),
			),
			false
		);
	}

	/**
	 * Register Settings API entries.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'vfwp_intranet_search_settings',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array($this, 'sanitize_settings'),
				'default'           => self::defaults(),
			)
		);

		add_settings_section(
			'vfwp_intranet_search_field_weights',
			__('Field weights', 'vfwp'),
			array($this, 'render_field_weights_description'),
			'vfwp-intranet-search'
		);

		add_settings_field(
			'vfwp_intranet_search_field_weights_table',
			__('Field weights', 'vfwp'),
			array($this, 'render_field_weights'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_field_weights'
		);

		add_settings_section(
			'vfwp_intranet_search_post_types',
			__('Post type weights', 'vfwp'),
			array($this, 'render_post_type_description'),
			'vfwp-intranet-search'
		);

		add_settings_field(
			'vfwp_intranet_search_post_types_table',
			__('Post types', 'vfwp'),
			array($this, 'render_post_types'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_post_types'
		);

		add_settings_section(
			'vfwp_intranet_search_acf',
			__('ACF search fields', 'vfwp'),
			array($this, 'render_acf_description'),
			'vfwp-intranet-search'
		);

		add_settings_field(
			'vfwp_intranet_search_acf_field_names',
			__('ACF field names', 'vfwp'),
			array($this, 'render_acf_field_names'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_acf'
		);

		add_settings_section(
			'vfwp_intranet_search_query_parsing',
			__('Query parsing', 'vfwp'),
			array($this, 'render_query_parsing_description'),
			'vfwp-intranet-search'
		);

		add_settings_field(
			'vfwp_intranet_search_min_word_length',
			__('Minimum word length', 'vfwp'),
			array($this, 'render_query_min_word_length'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_query_parsing'
		);

		add_settings_field(
			'vfwp_intranet_search_stopwords',
			__('Stopwords', 'vfwp'),
			array($this, 'render_stopwords'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_query_parsing'
		);

		add_settings_field(
			'vfwp_intranet_search_exact_phrases',
			__('Exact phrase searches', 'vfwp'),
			array($this, 'render_exact_phrases'),
			'vfwp-intranet-search',
			'vfwp_intranet_search_query_parsing'
		);
	}

	/**
	 * Add Settings > Search.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		add_options_page(
			__('Search', 'vfwp'),
			__('Search', 'vfwp'),
			self::ADMIN_CAPABILITY,
			'vfwp-intranet-search',
			array($this, 'render_page')
		);
	}

	/**
	 * Sanitize settings on save.
	 *
	 * @param array $input Raw settings input.
	 * @return array
	 */
	public function sanitize_settings($input) {
		if (!current_user_can(self::ADMIN_CAPABILITY)) {
			return self::get_settings();
		}

		$old_settings = self::get_settings();
		$input = is_array($input) ? wp_unslash($input) : array();
		$sanitized = $old_settings;

		foreach ($sanitized['field_weights'] as $field => $default_weight) {
			$value = isset($input['field_weights'][$field]) ? $input['field_weights'][$field] : $old_settings['field_weights'][$field];
			$sanitized['field_weights'][$field] = $this->sanitize_weight($value);
		}

		foreach (self::default_ranking_boosts() as $boost => $default_value) {
			$value = isset($input['ranking_boosts'][$boost]) ? $input['ranking_boosts'][$boost] : $old_settings['ranking_boosts'][$boost];
			$sanitized['ranking_boosts'][$boost] = $this->sanitize_weight($value);
		}

		$raw_acf_field_names = array_key_exists('acf_field_names', $input) ? $input['acf_field_names'] : $old_settings['acf_field_names'];
		$sanitized['acf_field_names'] = self::parse_acf_field_names($raw_acf_field_names);
		$sanitized['query_min_word_length'] = self::sanitize_min_word_length_value(
			array_key_exists('query_min_word_length', $input) ? $input['query_min_word_length'] : $old_settings['query_min_word_length']
		);
		$sanitized['stopwords'] = self::parse_stopwords(array_key_exists('stopwords', $input) ? $input['stopwords'] : $old_settings['stopwords']);
		$sanitized['exact_phrases'] = self::parse_exact_phrases(array_key_exists('exact_phrases', $input) ? $input['exact_phrases'] : $old_settings['exact_phrases']);
		$sanitized['synonyms'] = self::parse_synonyms(array_key_exists('synonyms', $input) ? $input['synonyms'] : $old_settings['synonyms']);
		$sanitized['analytics'] = self::sanitize_analytics_settings(array_key_exists('analytics', $input) ? $input['analytics'] : $old_settings['analytics']);

		foreach (self::get_searchable_post_types() as $post_type => $post_type_object) {
			$old_post_type = self::get_post_type_setting($post_type, $old_settings);
			$post_type_input = isset($input['post_types'][$post_type]) && is_array($input['post_types'][$post_type])
				? $input['post_types'][$post_type]
				: null;

			$sanitized['post_types'][$post_type] = array(
				'include' => is_array($post_type_input) ? (empty($post_type_input['include']) ? 0 : 1) : (int) $old_post_type['include'],
				'weight'  => $this->sanitize_weight(is_array($post_type_input) && isset($post_type_input['weight']) ? $post_type_input['weight'] : $old_post_type['weight']),
			);
		}

		if ($this->settings_change_requires_rebuild($old_settings, $sanitized)) {
			self::mark_rebuild_required(__('Searchable content settings changed.', 'vfwp'));
		}

		return $sanitized;
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render_page() {
		if (!current_user_can(self::ADMIN_CAPABILITY)) {
			wp_die(esc_html__('You do not have permission to manage search settings.', 'vfwp'));
		}

		$rebuild_status = get_option(self::REBUILD_REQUIRED_OPTION, array());
		$current_tab = $this->get_current_tab();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__('Search', 'vfwp'); ?></h1>

			<?php $this->render_index_action_notice(); ?>
			<?php settings_errors('vfwp_intranet_search_settings'); ?>

			<?php if (is_array($rebuild_status) && !empty($rebuild_status['required'])) : ?>
				<div class="notice notice-warning">
					<p>
						<strong><?php echo esc_html__('Search index rebuild required.', 'vfwp'); ?></strong>
						<?php echo esc_html(isset($rebuild_status['reason']) ? $rebuild_status['reason'] : ''); ?>
					</p>
				</div>
			<?php endif; ?>

			<?php $this->render_tabs($current_tab); ?>

			<?php if ('index' === $current_tab) : ?>
				<?php $this->render_index_management(); ?>
				<?php $this->render_pdf_extraction_issues(); ?>
			<?php elseif ('analytics' === $current_tab) : ?>
				<?php $this->render_analytics_tab(); ?>
			<?php else : ?>
				<form method="post" action="options.php">
					<?php settings_fields('vfwp_intranet_search_settings'); ?>
					<?php $this->render_tab_content($current_tab); ?>
					<?php submit_button(); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Return settings tabs.
	 *
	 * @return array
	 */
	private function get_tabs() {
		return array(
			'index'   => __('Index', 'vfwp'),
			'ranking' => __('Ranking', 'vfwp'),
			'content' => __('Content', 'vfwp'),
			'query'   => __('Query parsing', 'vfwp'),
			'analytics' => __('Analytics', 'vfwp'),
		);
	}

	/**
	 * Return the active settings tab.
	 *
	 * @return string
	 */
	private function get_current_tab() {
		$tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'index';
		$tabs = $this->get_tabs();

		return isset($tabs[$tab]) ? $tab : 'index';
	}

	/**
	 * Render settings navigation tabs.
	 *
	 * @param string $current_tab Current tab.
	 * @return void
	 */
	private function render_tabs($current_tab) {
		$tabs = $this->get_tabs();
		?>
		<nav class="nav-tab-wrapper" aria-label="<?php echo esc_attr__('Search settings sections', 'vfwp'); ?>">
			<?php foreach ($tabs as $tab => $label) : ?>
				<a
					href="<?php echo esc_url(add_query_arg(array('page' => 'vfwp-intranet-search', 'tab' => $tab), admin_url('options-general.php'))); ?>"
					class="nav-tab <?php echo $tab === $current_tab ? 'nav-tab-active' : ''; ?>"
				>
					<?php echo esc_html($label); ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Render one settings tab.
	 *
	 * @param string $tab Current tab.
	 * @return void
	 */
	private function render_tab_content($tab) {
		if ('ranking' === $tab) {
			$this->render_ranking_tab();
			return;
		}

		if ('content' === $tab) {
			$this->render_content_tab();
			return;
		}

		$this->render_query_tab();
	}

	/**
	 * Render ranking settings.
	 *
	 * @return void
	 */
	private function render_ranking_tab() {
		?>
		<h2><?php echo esc_html__('Ranking', 'vfwp'); ?></h2>
		<?php $this->render_ranking_admin_styles(); ?>
		<?php $this->render_ranking_overview(); ?>
		<?php $this->render_calculated_ranking_summary(); ?>
		<h3><?php echo esc_html__('Primary ranking controls', 'vfwp'); ?></h3>
		<?php $this->render_field_weights_description(); ?>
		<?php $this->render_field_weights(); ?>
		<?php $this->render_post_type_description(); ?>
		<?php $this->render_post_types(); ?>
		<details class="vfwp-search-settings-panel">
			<summary><?php echo esc_html__('Advanced boost tuning', 'vfwp'); ?></summary>
			<?php $this->render_ranking_boosts(); ?>
		</details>
		<?php
	}

	/**
	 * Render content/indexing settings.
	 *
	 * @return void
	 */
	private function render_content_tab() {
		?>
		<h2><?php echo esc_html__('Searchable Content', 'vfwp'); ?></h2>
		<?php $this->render_acf_description(); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php echo esc_html__('ACF field names', 'vfwp'); ?></th>
				<td><?php $this->render_acf_field_names(); ?></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render query parsing settings.
	 *
	 * @return void
	 */
	private function render_query_tab() {
		?>
		<h2><?php echo esc_html__('Query Parsing', 'vfwp'); ?></h2>
		<?php $this->render_query_parsing_description(); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php echo esc_html__('Minimum word length', 'vfwp'); ?></th>
				<td><?php $this->render_query_min_word_length(); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html__('Stopwords', 'vfwp'); ?></th>
				<td><?php $this->render_stopwords(); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html__('Exact phrase searches', 'vfwp'); ?></th>
				<td><?php $this->render_exact_phrases(); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html__('Synonyms', 'vfwp'); ?></th>
				<td><?php $this->render_synonyms(); ?></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render analytics settings and reports.
	 *
	 * @return void
	 */
	private function render_analytics_tab() {
		?>
		<h2><?php echo esc_html__('Search Analytics', 'vfwp'); ?></h2>
		<p><?php echo esc_html__('Analytics records frontend search queries and result counts so editors can find popular searches and searches that return nothing. It does not store IP addresses or user agents.', 'vfwp'); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields('vfwp_intranet_search_settings'); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php echo esc_html__('Analytics settings', 'vfwp'); ?></th>
					<td><?php $this->render_analytics_settings(); ?></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php

		if (class_exists('VFWP_Intranet_Search_Analytics')) {
			$analytics = new VFWP_Intranet_Search_Analytics();
			$this->render_analytics_reports($analytics->get_dashboard_data());
		}
	}

	/**
	 * Render lightweight admin styling for the ranking tab.
	 *
	 * @return void
	 */
	private function render_ranking_admin_styles() {
		?>
		<style>
			.vfwp-search-settings-grid {
				display: grid;
				gap: 16px;
				grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
				max-width: 920px;
			}
			.vfwp-search-settings-card {
				background: #fff;
				border: 1px solid #c3c4c7;
				padding: 14px 16px;
			}
			.vfwp-search-settings-card h3,
			.vfwp-search-settings-card h4 {
				margin-top: 0;
			}
			.vfwp-search-settings-panel {
				background: #fff;
				border: 1px solid #c3c4c7;
				margin-top: 18px;
				max-width: 960px;
				padding: 0;
			}
			.vfwp-search-settings-panel > summary {
				cursor: pointer;
				font-size: 1.1em;
				font-weight: 600;
				padding: 14px 16px;
			}
			.vfwp-search-settings-panel > *:not(summary) {
				margin-left: 16px;
				margin-right: 16px;
			}
			.vfwp-search-settings-panel table {
				margin-bottom: 18px;
			}
			.vfwp-search-settings-kicker {
				color: #646970;
				display: block;
				font-size: 12px;
				font-weight: 600;
				letter-spacing: .02em;
				text-transform: uppercase;
			}
			.vfwp-search-settings-value {
				display: block;
				font-size: 22px;
				font-weight: 700;
				margin-top: 4px;
			}
		</style>
		<?php
	}

	/**
	 * Render a readable overview of the ranking model.
	 *
	 * @return void
	 */
	private function render_ranking_overview() {
		$top_rows = $this->get_calculated_ranking_rows();
		usort($top_rows, array($this, 'sort_calculated_rows_desc'));
		$top_rows = array_slice($top_rows, 0, 3);
		?>
		<div class="vfwp-search-settings-grid">
			<div class="vfwp-search-settings-card">
				<h3><?php echo esc_html__('How ranking is decided', 'vfwp'); ?></h3>
				<p><?php echo esc_html__('Search first finds matching indexed rows. Each result then earns points from matching signals such as exact title, phrase in title, exact ACF keyword, excerpt match, content/PDF match, and database FULLTEXT relevance.', 'vfwp'); ?></p>
				<p><?php echo esc_html__('Those points are added together, multiplied by the post-type weight for web results, and then a small recent-content bonus may be added.', 'vfwp'); ?></p>
			</div>
			<div class="vfwp-search-settings-card">
				<h3><?php echo esc_html__('Best place to tune first', 'vfwp'); ?></h3>
				<p><?php echo esc_html__('Start with Field weights. They are the clearest controls: raise Title if titles should dominate, raise ACF keywords for curated keyword hits, raise Content if body/PDF text should matter more.', 'vfwp'); ?></p>
				<p><?php echo esc_html__('Use Advanced boost tuning only when you need to change a specific signal, such as exact title matches or FULLTEXT content scoring.', 'vfwp'); ?></p>
			</div>
			<div class="vfwp-search-settings-card">
				<h3><?php echo esc_html__('Top signals right now', 'vfwp'); ?></h3>
				<?php foreach ($top_rows as $row) : ?>
					<p>
						<span class="vfwp-search-settings-kicker"><?php echo esc_html($row['signal']); ?></span>
						<span class="vfwp-search-settings-value"><?php echo esc_html($this->format_decimal($row['value'])); ?></span>
					</p>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render redirect notice after an index action.
	 *
	 * @return void
	 */
	private function render_index_action_notice() {
		if (empty($_GET['vfwp_search_index_notice'])) {
			return;
		}

		$notice_type = sanitize_key(wp_unslash($_GET['vfwp_search_index_notice']));
		$message = isset($_GET['vfwp_search_index_message'])
			? sanitize_text_field(rawurldecode(wp_unslash($_GET['vfwp_search_index_message'])))
			: '';

		if ($message === '') {
			return;
		}

		$class = $notice_type === 'started' ? 'notice notice-success' : 'notice notice-error';
		?>
		<div class="<?php echo esc_attr($class); ?>">
			<p><?php echo esc_html($message); ?></p>
		</div>
		<?php
	}

	/**
	 * Render index status and management controls.
	 *
	 * @return void
	 */
	private function render_index_management() {
		if (!class_exists('VFWP_Intranet_Search_Index_Manager')) {
			return;
		}

		$manager = new VFWP_Intranet_Search_Index_Manager();
		$data = $manager->get_dashboard_data();
		$status = $data['status'];
		$counts = $data['counts'];
		$pending = max(0, (int) $status['total_planned'] - (int) $status['processed']);
		$failed_items = max((int) $status['failed'], (int) $data['pdf_issue_count']);
		$rebuild_required = !empty($data['rebuild_required']['required']);
		$next_batch = !empty($data['next_scheduled_batch']) ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), (int) $data['next_scheduled_batch']) : __('Not scheduled', 'vfwp');
		?>
		<h2><?php echo esc_html__('Index management', 'vfwp'); ?></h2>
		<table class="widefat striped" style="max-width: 920px;">
			<tbody>
				<tr>
					<th scope="row"><?php echo esc_html__('Total indexed items', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n((int) $counts['total'])); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Indexed web pages', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n((int) $counts['web'])); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Indexed standalone PDF documents', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n((int) $counts['pdf'])); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Index/schema version', 'vfwp'); ?></th>
					<td><?php echo esc_html((int) $data['installed_version'] . ' / ' . (int) $data['schema_version']); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Last completed full rebuild', 'vfwp'); ?></th>
					<td><?php echo esc_html($data['last_full_rebuild'] !== '' ? $data['last_full_rebuild'] : __('Never', 'vfwp')); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Current indexing status', 'vfwp'); ?></th>
					<td>
						<code><?php echo esc_html($status['status']); ?></code>
						<?php if ($status['phase'] !== '') : ?>
							<?php echo esc_html(sprintf(__('Phase: %s', 'vfwp'), $status['phase'])); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Progress', 'vfwp'); ?></th>
					<td>
						<?php
						echo esc_html(
							sprintf(
								__('%1$s processed of about %2$s planned.', 'vfwp'),
								number_format_i18n((int) $status['processed']),
								number_format_i18n((int) $status['total_planned'])
							)
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Pending items', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n($pending)); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Failed items', 'vfwp'); ?></th>
					<td>
						<?php echo esc_html(number_format_i18n($failed_items)); ?>
						<?php if ((int) $data['pdf_issue_count'] > 0) : ?>
							<?php echo esc_html(sprintf(__('(%s PDF extraction issues)', 'vfwp'), number_format_i18n((int) $data['pdf_issue_count']))); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Rebuild required', 'vfwp'); ?></th>
					<td><?php echo esc_html($rebuild_required ? __('Yes', 'vfwp') : __('No', 'vfwp')); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Next scheduled batch', 'vfwp'); ?></th>
					<td><?php echo esc_html($next_batch); ?></td>
				</tr>
			</tbody>
		</table>

		<div style="display: flex; gap: 12px; flex-wrap: wrap; margin: 16px 0 28px;">
			<?php $this->render_index_action_button('rebuild_full', __('Rebuild full search index', 'vfwp'), 'primary'); ?>
			<?php $this->render_index_action_button('reindex_changed', __('Reindex changed content', 'vfwp'), 'secondary'); ?>
			<?php $this->render_index_action_button('clear_recreate', __('Clear/recreate index', 'vfwp'), 'secondary'); ?>
		</div>
		<p class="description"><?php echo esc_html__('Index jobs run in safe batches. Keep this page open after starting a rebuild to process batches immediately.', 'vfwp'); ?></p>
		<div
			id="vfwp-search-index-batch-runner"
			class="description"
			data-active="<?php echo esc_attr(!empty($status['active']) ? '1' : '0'); ?>"
			data-nonce="<?php echo esc_attr(wp_create_nonce('vfwp_intranet_search_process_batch')); ?>"
		></div>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var runner = document.getElementById('vfwp-search-index-batch-runner');

				if (!runner || runner.getAttribute('data-active') !== '1' || typeof ajaxurl === 'undefined') {
					return;
				}

				var nonce = runner.getAttribute('data-nonce');
				var isRunning = false;

				function setRunnerText(message) {
					runner.textContent = message;
				}

				function processBatch() {
					if (isRunning) {
						return;
					}

					isRunning = true;
					setRunnerText('<?php echo esc_js(__('Processing search index batch...', 'vfwp')); ?>');

					var body = new URLSearchParams();
					body.set('action', 'vfwp_intranet_search_process_index_batch');
					body.set('nonce', nonce);

					fetch(ajaxurl, {
						method: 'POST',
						credentials: 'same-origin',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
						},
						body: body.toString()
					})
						.then(function (response) {
							return response.json();
						})
						.then(function (response) {
							var status = response && response.data && response.data.status ? response.data.status : null;

							if (!response || !response.success || !status) {
								setRunnerText('<?php echo esc_js(__('Search index batch processing failed. Refresh the page to inspect status.', 'vfwp')); ?>');
								return;
							}

							setRunnerText(
								(status.processed || 0) + ' / ' + (status.total_planned || 0) + ' <?php echo esc_js(__('items processed.', 'vfwp')); ?>'
							);

							if (status.active) {
								isRunning = false;
								window.setTimeout(processBatch, 300);
								return;
							}

							window.setTimeout(function () {
								window.location.reload();
							}, 600);
						})
						.catch(function () {
							setRunnerText('<?php echo esc_js(__('Search index batch processing failed. Refresh the page to inspect status.', 'vfwp')); ?>');
						})
						.finally(function () {
							isRunning = false;
						});
				}

				processBatch();
			});
		</script>
		<?php
	}

	/**
	 * Render one index action button.
	 *
	 * @param string $action Action key.
	 * @param string $label Button label.
	 * @param string $button_type Button type.
	 * @return void
	 */
	private function render_index_action_button($action, $label, $button_type) {
		?>
		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
			<input type="hidden" name="action" value="vfwp_intranet_search_index_action">
			<input type="hidden" name="search_index_action" value="<?php echo esc_attr($action); ?>">
			<?php wp_nonce_field('vfwp_intranet_search_index_action'); ?>
			<?php submit_button($label, $button_type, 'submit', false); ?>
		</form>
		<?php
	}

	/**
	 * Render field weights description.
	 *
	 * @return void
	 */
	public function render_field_weights_description() {
		echo '<p>' . esc_html__('Weights control ranking strength at query time. Higher numbers make matches in that field score higher.', 'vfwp') . '</p>';
	}

	/**
	 * Render field weights inputs.
	 *
	 * @return void
	 */
	public function render_field_weights() {
		$weights = self::get_field_weights();
		$definitions = array(
			'title'        => array(
				'label'    => __('Title', 'vfwp'),
				'role'     => __('Best for high-confidence matches. Usually this should stay highest.', 'vfwp'),
				'guidance' => __('Increase when exact or partial page titles should win.', 'vfwp'),
			),
			'acf_keywords' => array(
				'label'    => __('ACF keyword fields', 'vfwp'),
				'role'     => __('Curated synonyms and admin-entered keywords. Strong when keyword governance is good.', 'vfwp'),
				'guidance' => __('Increase when curated keyword matches should jump to the top.', 'vfwp'),
			),
			'excerpt'      => array(
				'label'    => __('Excerpt', 'vfwp'),
				'role'     => __('Short summaries. Useful when excerpts are hand-written and descriptive.', 'vfwp'),
				'guidance' => __('Increase when summaries should matter more than body text.', 'vfwp'),
			),
			'content'      => array(
				'label'    => __('Main content and PDF text', 'vfwp'),
				'role'     => __('Body text and extracted PDF text. Broad, but can be noisy.', 'vfwp'),
				'guidance' => __('Keep lower if long body/PDF matches are overpowering precise title or keyword matches.', 'vfwp'),
			),
		);
		?>
		<table class="widefat striped" style="max-width: 920px;">
			<thead>
				<tr>
					<th scope="col"><?php echo esc_html__('Field', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Weight', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Role', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('When to change', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($definitions as $field => $definition) : ?>
					<tr>
						<th scope="row"><?php echo esc_html($definition['label']); ?></th>
						<td>
							<input
								type="number"
								min="0"
								step="0.1"
								name="<?php echo esc_attr(self::OPTION_NAME); ?>[field_weights][<?php echo esc_attr($field); ?>]"
								value="<?php echo esc_attr($weights[$field]); ?>"
								class="small-text"
							>
						</td>
						<td><?php echo esc_html($definition['role']); ?></td>
						<td><?php echo esc_html($definition['guidance']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p class="description"><?php echo esc_html__('Changing these values takes effect immediately when the custom search query layer uses the index.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render ranking boost inputs.
	 *
	 * @return void
	 */
	public function render_ranking_boosts() {
		$boosts = self::get_ranking_boosts();
		$definitions = self::ranking_boost_definitions();
		$groups = $this->get_ranking_boost_groups();
		?>
		<p><?php echo esc_html__('Boosts are advanced multipliers used by the scoring formula. Most tuning should happen with Field weights and Post-type weights first.', 'vfwp'); ?></p>
		<?php foreach ($groups as $group) : ?>
			<h3><?php echo esc_html($group['label']); ?></h3>
			<p><?php echo esc_html($group['description']); ?></p>
			<table class="widefat striped" style="max-width: 920px;">
				<thead>
					<tr>
						<th scope="col"><?php echo esc_html__('Signal', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Boost', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Meaning', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($group['boosts'] as $boost) : ?>
						<?php if (!isset($definitions[$boost])) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<tr>
							<th scope="row"><?php echo esc_html($definitions[$boost]['label']); ?></th>
							<td>
								<input
									type="number"
									min="0"
									step="0.1"
									name="<?php echo esc_attr(self::OPTION_NAME); ?>[ranking_boosts][<?php echo esc_attr($boost); ?>]"
									value="<?php echo esc_attr($boosts[$boost]); ?>"
									class="small-text"
								>
							</td>
							<td><?php echo esc_html($definitions[$boost]['description']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>
		<p class="description"><?php echo esc_html__('Changing these values takes effect immediately. No reindex is required.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Return grouped advanced boost controls.
	 *
	 * @return array
	 */
	private function get_ranking_boost_groups() {
		return array(
			array(
				'label'       => __('Strong exact and phrase matches', 'vfwp'),
				'description' => __('Controls the signals that should usually create the strongest relevance jumps.', 'vfwp'),
				'boosts'      => array('exact_title', 'title_phrase', 'acf_phrase', 'excerpt_phrase', 'content_phrase'),
			),
			array(
				'label'       => __('Term coverage', 'vfwp'),
				'description' => __('Controls how much individual query words matter once candidate rows have been found.', 'vfwp'),
				'boosts'      => array('title_all_terms', 'title_term', 'acf_term', 'excerpt_term', 'content_term', 'all_terms', 'term_coverage'),
			),
			array(
				'label'       => __('Database FULLTEXT relevance', 'vfwp'),
				'description' => __('Controls the MySQL/MariaDB FULLTEXT score. These values usually fine-tune ranking after clearer phrase and term signals.', 'vfwp'),
				'boosts'      => array('fulltext_title', 'fulltext_acf', 'fulltext_excerpt', 'fulltext_content'),
			),
			array(
				'label'       => __('Recency', 'vfwp'),
				'description' => __('Controls the small final bonus for content published in the last 30 days.', 'vfwp'),
				'boosts'      => array('recency'),
			),
		);
	}

	/**
	 * Render calculated ranking contributions.
	 *
	 * @return void
	 */
	private function render_calculated_ranking_summary() {
		$rows = $this->get_calculated_ranking_rows();
		usort($rows, array($this, 'sort_calculated_rows_desc'));
		?>
		<h3><?php echo esc_html__('Current effective priorities', 'vfwp'); ?></h3>
		<p><?php echo esc_html__('This table translates your settings into the actual point values used by the scorer. It is sorted from strongest to weakest by default.', 'vfwp'); ?></p>
		<div class="notice notice-info inline" style="max-width: 920px;">
			<p>
				<strong><?php echo esc_html__('Simple version', 'vfwp'); ?></strong>
				<?php echo esc_html__('A result can earn points from several signals at once. Strong exact or curated matches should usually sit near the top. Broad body/PDF matches should usually sit lower unless you intentionally want content text to dominate.', 'vfwp'); ?>
			</p>
			<p>
				<?php echo esc_html__('For example, FULLTEXT content/PDF score means the database gives a relevance number for how strongly the query matches body or PDF text. That database number is multiplied by the Content field weight and the FULLTEXT content boost.', 'vfwp'); ?>
			</p>
			<p>
				<?php echo esc_html__('After these signal points are added, web results are multiplied by their post-type multiplier. Finally, the recent-content bonus is added for content published in the last 30 days.', 'vfwp'); ?>
			</p>
		</div>
		<p>
			<button type="button" class="button" data-vfwp-ranking-sort="desc"><?php echo esc_html__('Sort by value: high to low', 'vfwp'); ?></button>
			<button type="button" class="button" data-vfwp-ranking-sort="asc"><?php echo esc_html__('Sort by value: low to high', 'vfwp'); ?></button>
		</p>
		<table id="vfwp-calculated-ranking-priorities" class="widefat striped" style="max-width: 920px;">
			<thead>
				<tr>
					<th scope="col"><?php echo esc_html__('Signal', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Current value', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Plain meaning', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Formula', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rows as $row) : ?>
					<tr data-sort-value="<?php echo esc_attr((string) (float) $row['value']); ?>">
						<th scope="row"><?php echo esc_html($row['signal']); ?></th>
						<td><strong><?php echo esc_html($this->format_decimal($row['value'])); ?></strong></td>
						<td><?php echo esc_html($row['meaning']); ?></td>
						<td><?php echo esc_html($row['formula']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var table = document.getElementById('vfwp-calculated-ranking-priorities');

				if (!table) {
					return;
				}

				var tbody = table.querySelector('tbody');

				if (!tbody) {
					return;
				}

				document.querySelectorAll('[data-vfwp-ranking-sort]').forEach(function (button) {
					button.addEventListener('click', function () {
						var direction = button.getAttribute('data-vfwp-ranking-sort') === 'asc' ? 'asc' : 'desc';
						var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));

						rows.sort(function (a, b) {
							var aValue = parseFloat(a.getAttribute('data-sort-value') || '0');
							var bValue = parseFloat(b.getAttribute('data-sort-value') || '0');

							return direction === 'asc' ? aValue - bValue : bValue - aValue;
						});

						rows.forEach(function (row) {
							tbody.appendChild(row);
						});
					});
				});
			});
		</script>
		<?php $this->render_post_type_weight_summary(); ?>
		<?php
	}

	/**
	 * Render current post type multipliers.
	 *
	 * @return void
	 */
	private function render_post_type_weight_summary() {
		$post_types = self::get_searchable_post_types();

		if (empty($post_types)) {
			return;
		}
		?>
		<h3><?php echo esc_html__('Post-type multipliers', 'vfwp'); ?></h3>
		<table class="widefat striped" style="max-width: 640px;">
			<thead>
				<tr>
					<th scope="col"><?php echo esc_html__('Post type', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Included', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Multiplier', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($post_types as $post_type => $post_type_object) : ?>
					<?php $setting = self::get_post_type_setting($post_type); ?>
					<tr>
						<th scope="row"><?php echo esc_html($post_type_object->labels->singular_name); ?> <code><?php echo esc_html($post_type); ?></code></th>
						<td><?php echo esc_html(!empty($setting['include']) ? __('Yes', 'vfwp') : __('No', 'vfwp')); ?></td>
						<td><strong><?php echo esc_html($this->format_decimal($setting['weight'])); ?></strong></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render post type section description.
	 *
	 * @return void
	 */
	public function render_post_type_description() {
		echo '<p>' . esc_html__('Only public post types that are not excluded from search are shown here.', 'vfwp') . '</p>';
	}

	/**
	 * Render post type include and weight settings.
	 *
	 * @return void
	 */
	public function render_post_types() {
		$settings = self::get_settings();
		$post_types = self::get_searchable_post_types();

		if (empty($post_types)) {
			echo '<p>' . esc_html__('No searchable post types are registered.', 'vfwp') . '</p>';
			return;
		}
		?>
		<table class="widefat striped" style="max-width: 760px;">
			<thead>
				<tr>
					<th scope="col"><?php echo esc_html__('Post type', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Include', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Weight', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($post_types as $post_type => $post_type_object) : ?>
					<?php $post_type_settings = self::get_post_type_setting($post_type, $settings); ?>
					<tr>
						<th scope="row">
							<?php echo esc_html($post_type_object->labels->singular_name); ?>
							<code><?php echo esc_html($post_type); ?></code>
						</th>
						<td>
							<input type="hidden" name="<?php echo esc_attr(self::OPTION_NAME); ?>[post_types][<?php echo esc_attr($post_type); ?>][include]" value="0">
							<label>
								<input
									type="checkbox"
									name="<?php echo esc_attr(self::OPTION_NAME); ?>[post_types][<?php echo esc_attr($post_type); ?>][include]"
									value="1"
									<?php checked(1, (int) $post_type_settings['include']); ?>
								>
								<?php echo esc_html__('Include', 'vfwp'); ?>
							</label>
						</td>
						<td>
							<input
								type="number"
								min="0"
								step="0.1"
								name="<?php echo esc_attr(self::OPTION_NAME); ?>[post_types][<?php echo esc_attr($post_type); ?>][weight]"
								value="<?php echo esc_attr($post_type_settings['weight']); ?>"
								class="small-text"
							>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p class="description"><?php echo esc_html__('Include/exclude changes require a rebuild. Weight changes take effect at query time.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Return calculated ranking rows for admin display.
	 *
	 * @return array
	 */
	private function get_calculated_ranking_rows() {
		$weights = self::get_field_weights();
		$boosts = self::get_ranking_boosts();

		return array(
			array(
				'signal'  => __('Exact title match', 'vfwp'),
				'formula' => __('Exact title boost', 'vfwp'),
				'meaning' => __('The query exactly equals the title. This is usually the strongest title signal.', 'vfwp'),
				'value'   => $boosts['exact_title'],
			),
			array(
				'signal'  => __('Title phrase match', 'vfwp'),
				'formula' => __('Title weight × title phrase boost', 'vfwp'),
				'meaning' => __('The complete query phrase or protected phrase appears in the title.', 'vfwp'),
				'value'   => $weights['title'] * $boosts['title_phrase'],
			),
			array(
				'signal'  => __('All terms in title', 'vfwp'),
				'formula' => __('Title weight × all-title-terms boost', 'vfwp'),
				'meaning' => __('Every searchable query word appears in the title.', 'vfwp'),
				'value'   => $weights['title'] * $boosts['title_all_terms'],
			),
			array(
				'signal'  => __('Each title term', 'vfwp'),
				'formula' => __('Title weight × title term boost', 'vfwp'),
				'meaning' => __('Each individual query word found in the title adds points.', 'vfwp'),
				'value'   => $weights['title'] * $boosts['title_term'],
			),
			array(
				'signal'  => __('Exact ACF keyword entry', 'vfwp'),
				'formula' => __('ACF keyword weight × ACF phrase boost', 'vfwp'),
				'meaning' => __('The full query exactly matches one configured ACF keyword entry.', 'vfwp'),
				'value'   => $weights['acf_keywords'] * $boosts['acf_phrase'],
			),
			array(
				'signal'  => __('ACF keyword term match', 'vfwp'),
				'formula' => __('ACF keyword weight × ACF term boost', 'vfwp'),
				'meaning' => __('Extra points added per searchable query word when the full ACF keyword entry matches.', 'vfwp'),
				'value'   => $weights['acf_keywords'] * $boosts['acf_term'],
			),
			array(
				'signal'  => __('Excerpt phrase match', 'vfwp'),
				'formula' => __('Excerpt weight × excerpt phrase boost', 'vfwp'),
				'meaning' => __('The complete query phrase or protected phrase appears in the excerpt.', 'vfwp'),
				'value'   => $weights['excerpt'] * $boosts['excerpt_phrase'],
			),
			array(
				'signal'  => __('Each excerpt term', 'vfwp'),
				'formula' => __('Excerpt weight × excerpt term boost', 'vfwp'),
				'meaning' => __('Each individual query word found in the excerpt adds points.', 'vfwp'),
				'value'   => $weights['excerpt'] * $boosts['excerpt_term'],
			),
			array(
				'signal'  => __('Content/PDF phrase match', 'vfwp'),
				'formula' => __('Content weight × content phrase boost', 'vfwp'),
				'meaning' => __('The complete query phrase or protected phrase appears in body text or extracted PDF text.', 'vfwp'),
				'value'   => $weights['content'] * $boosts['content_phrase'],
			),
			array(
				'signal'  => __('Each content/PDF term', 'vfwp'),
				'formula' => __('Content weight × content term boost', 'vfwp'),
				'meaning' => __('Each individual query word found in body text or extracted PDF text adds points.', 'vfwp'),
				'value'   => $weights['content'] * $boosts['content_term'],
			),
			array(
				'signal'  => __('All terms anywhere', 'vfwp'),
				'formula' => __('All-terms bonus', 'vfwp'),
				'meaning' => __('All searchable query words appear somewhere across title, excerpt, or content.', 'vfwp'),
				'value'   => $boosts['all_terms'],
			),
			array(
				'signal'  => __('Term coverage', 'vfwp'),
				'formula' => __('Matched terms ÷ query terms × coverage boost', 'vfwp'),
				'meaning' => __('Rewards results that match a higher percentage of the query words.', 'vfwp'),
				'value'   => $boosts['term_coverage'],
			),
			array(
				'signal'  => __('FULLTEXT title score', 'vfwp'),
				'formula' => __('Database score × title weight × FULLTEXT title boost', 'vfwp'),
				'meaning' => __('Database relevance from title matches, scaled by title importance.', 'vfwp'),
				'value'   => $weights['title'] * $boosts['fulltext_title'],
			),
			array(
				'signal'  => __('FULLTEXT ACF score', 'vfwp'),
				'formula' => __('Database score × ACF keyword weight × FULLTEXT ACF boost', 'vfwp'),
				'meaning' => __('Database relevance from ACF keywords after an exact keyword entry match.', 'vfwp'),
				'value'   => $weights['acf_keywords'] * $boosts['fulltext_acf'],
			),
			array(
				'signal'  => __('FULLTEXT excerpt score', 'vfwp'),
				'formula' => __('Database score × excerpt weight × FULLTEXT excerpt boost', 'vfwp'),
				'meaning' => __('Database relevance from excerpt matches, scaled by excerpt importance.', 'vfwp'),
				'value'   => $weights['excerpt'] * $boosts['fulltext_excerpt'],
			),
			array(
				'signal'  => __('FULLTEXT content/PDF score', 'vfwp'),
				'formula' => __('Database score × content weight × FULLTEXT content boost', 'vfwp'),
				'meaning' => __('Database relevance from body or PDF text matches, scaled by content importance.', 'vfwp'),
				'value'   => $weights['content'] * $boosts['fulltext_content'],
			),
			array(
				'signal'  => __('Recent content', 'vfwp'),
				'formula' => __('Recent-content boost', 'vfwp'),
				'meaning' => __('Small final bonus for content published in the last 30 days.', 'vfwp'),
				'value'   => $boosts['recency'],
			),
		);
	}

	/**
	 * Sort calculated ranking rows from high to low.
	 *
	 * @param array $a First row.
	 * @param array $b Second row.
	 * @return int
	 */
	private function sort_calculated_rows_desc(array $a, array $b) {
		if ((float) $a['value'] === (float) $b['value']) {
			return 0;
		}

		return (float) $a['value'] < (float) $b['value'] ? 1 : -1;
	}

	/**
	 * Render ACF section description.
	 *
	 * @return void
	 */
	public function render_acf_description() {
		echo '<p>' . esc_html__('Enter ACF field names whose textual values should be indexed as keyword content.', 'vfwp') . '</p>';
	}

	/**
	 * Render ACF field name input.
	 *
	 * @return void
	 */
	public function render_acf_field_names() {
		$field_names = implode(', ', self::get_acf_field_names());
		?>
		<input
			type="text"
			name="<?php echo esc_attr(self::OPTION_NAME); ?>[acf_field_names]"
			value="<?php echo esc_attr($field_names); ?>"
			class="regular-text"
			placeholder="<?php echo esc_attr__('keywords, alternative_terms, search_terms, product_keywords', 'vfwp'); ?>"
		>
		<p class="description"><?php echo esc_html__('Use ACF field names, not literal search terms. Changing this list requires a rebuild.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render query parsing section description.
	 *
	 * @return void
	 */
	public function render_query_parsing_description() {
		echo '<p>' . esc_html__('These settings control how visitor search queries are split into searchable terms. Changes take effect immediately.', 'vfwp') . '</p>';
	}

	/**
	 * Render minimum query word length input.
	 *
	 * @return void
	 */
	public function render_query_min_word_length() {
		$settings = self::get_settings();
		?>
		<input
			type="number"
			min="1"
			max="10"
			step="1"
			name="<?php echo esc_attr(self::OPTION_NAME); ?>[query_min_word_length]"
			value="<?php echo esc_attr((int) $settings['query_min_word_length']); ?>"
			class="small-text"
		>
		<p class="description"><?php echo esc_html__('Words shorter than this are ignored for term matching and highlighting. FULLTEXT candidate selection still depends on the database token length.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render stopwords textarea.
	 *
	 * @return void
	 */
	public function render_stopwords() {
		$stopwords = implode("\n", self::get_stopwords());
		?>
		<textarea
			name="<?php echo esc_attr(self::OPTION_NAME); ?>[stopwords]"
			rows="10"
			cols="50"
			class="large-text code"
		><?php echo esc_textarea($stopwords); ?></textarea>
		<p class="description"><?php echo esc_html__('Enter one stopword per line, or separate words with commas. Stopwords are ignored as standalone query terms.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render exact phrase searches textarea.
	 *
	 * @return void
	 */
	public function render_exact_phrases() {
		$phrases = implode("\n", self::get_exact_phrases());
		?>
		<textarea
			name="<?php echo esc_attr(self::OPTION_NAME); ?>[exact_phrases]"
			rows="6"
			cols="50"
			class="large-text code"
			placeholder="<?php echo esc_attr__('it services, core facilities, data protection', 'vfwp'); ?>"
		><?php echo esc_textarea($phrases); ?></textarea>
		<p class="description"><?php echo esc_html__('Enter one phrase per line, or separate phrases with commas. When a visitor query contains one of these phrases, the search treats the phrase as one protected search unit, requires the complete phrase, and does not split its words into loose standalone terms.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render synonym textarea.
	 *
	 * @return void
	 */
	public function render_synonyms() {
		$lines = array();

		foreach (self::get_synonyms() as $synonym) {
			if (empty($synonym['from']) || empty($synonym['to'])) {
				continue;
			}

			$lines[] = $synonym['from'] . ' = ' . $synonym['to'];
		}
		?>
		<textarea
			name="<?php echo esc_attr(self::OPTION_NAME); ?>[synonyms]"
			rows="6"
			cols="50"
			class="large-text code"
			placeholder="<?php echo esc_attr__('it members = it services members', 'vfwp'); ?>"
		><?php echo esc_textarea(implode("\n", $lines)); ?></textarea>
		<p class="description"><?php echo esc_html__('Enter one synonym per line using source = replacement. For example, searches for "it members" will be run as "it services members". Changes take effect immediately and do not require reindexing.', 'vfwp'); ?></p>
		<?php
	}

	/**
	 * Render analytics setting controls.
	 *
	 * @return void
	 */
	public function render_analytics_settings() {
		$settings = self::get_analytics_settings();
		?>
		<fieldset>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr(self::OPTION_NAME); ?>[analytics][enabled]"
					value="1"
					<?php checked(1, (int) $settings['enabled']); ?>
				>
				<?php echo esc_html__('Record frontend searches', 'vfwp'); ?>
			</label>
			<p class="description"><?php echo esc_html__('Only real search result pages are logged. Autocomplete requests and pagination pages are not recorded.', 'vfwp'); ?></p>
			<br>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr(self::OPTION_NAME); ?>[analytics][exclude_admins]"
					value="1"
					<?php checked(1, (int) $settings['exclude_admins']); ?>
				>
				<?php echo esc_html__('Do not record searches by administrators', 'vfwp'); ?>
			</label>
			<br>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr(self::OPTION_NAME); ?>[analytics][track_user_email]"
					value="1"
					<?php checked(1, (int) $settings['track_user_email']); ?>
				>
				<?php echo esc_html__('Store logged-in WordPress user email addresses', 'vfwp'); ?>
			</label>
			<p class="description"><?php echo esc_html__('Leave this off unless you have a clear operational need. IP addresses and browser user agents are never stored by this analytics layer.', 'vfwp'); ?></p>
			<br>
			<label>
				<?php echo esc_html__('Retention period', 'vfwp'); ?>
				<input
					type="number"
					min="7"
					max="730"
					step="1"
					name="<?php echo esc_attr(self::OPTION_NAME); ?>[analytics][retention_days]"
					value="<?php echo esc_attr((int) $settings['retention_days']); ?>"
					class="small-text"
				>
				<?php echo esc_html__('days', 'vfwp'); ?>
			</label>
			<p class="description"><?php echo esc_html__('Old analytics rows are cleaned up automatically during search logging.', 'vfwp'); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Render analytics report tables.
	 *
	 * @param array $data Analytics dashboard data.
	 * @return void
	 */
	private function render_analytics_reports(array $data) {
		$summary = isset($data['summary']) && is_array($data['summary']) ? $data['summary'] : array();
		$top_queries = isset($data['top_queries']) && is_array($data['top_queries']) ? $data['top_queries'] : array();
		$zero_results = isset($data['zero_results']) && is_array($data['zero_results']) ? $data['zero_results'] : array();
		$recent = isset($data['recent']) && is_array($data['recent']) ? $data['recent'] : array();
		?>
		<h3><?php echo esc_html__('Analytics summary', 'vfwp'); ?></h3>
		<table class="widefat striped" style="max-width: 920px;">
			<tbody>
				<tr>
					<th scope="row"><?php echo esc_html__('Total searches', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n(isset($summary['total_searches']) ? (int) $summary['total_searches'] : 0)); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Zero-result searches', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n(isset($summary['zero_result_searches']) ? (int) $summary['zero_result_searches'] : 0)); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Unique queries', 'vfwp'); ?></th>
					<td><?php echo esc_html(number_format_i18n(isset($summary['unique_queries']) ? (int) $summary['unique_queries'] : 0)); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__('Last search recorded', 'vfwp'); ?></th>
					<td><?php echo esc_html(!empty($summary['last_search_at']) ? $this->format_admin_datetime($summary['last_search_at']) : __('Never', 'vfwp')); ?></td>
				</tr>
			</tbody>
		</table>

		<?php $this->render_grouped_analytics_table(__('Most searched queries', 'vfwp'), $top_queries, false); ?>
		<?php $this->render_grouped_analytics_table(__('Queries with no results', 'vfwp'), $zero_results, true); ?>
		<?php $this->render_recent_analytics_table($recent); ?>

		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top: 18px;">
			<input type="hidden" name="action" value="<?php echo esc_attr(VFWP_Intranet_Search_Analytics::CLEAR_ACTION); ?>">
			<?php wp_nonce_field(VFWP_Intranet_Search_Analytics::CLEAR_ACTION); ?>
			<?php submit_button(__('Clear analytics data', 'vfwp'), 'delete', 'submit', false); ?>
		</form>
		<?php
	}

	/**
	 * Render grouped query analytics table.
	 *
	 * @param string $heading Heading.
	 * @param array  $rows Rows.
	 * @param bool   $zero_only Whether rows are zero-result only.
	 * @return void
	 */
	private function render_grouped_analytics_table($heading, array $rows, $zero_only) {
		?>
		<h3><?php echo esc_html($heading); ?></h3>
		<?php if (empty($rows)) : ?>
			<p><?php echo esc_html__('No analytics data recorded yet.', 'vfwp'); ?></p>
		<?php else : ?>
			<table class="widefat striped" style="max-width: 920px;">
				<thead>
					<tr>
						<th scope="col"><?php echo esc_html__('Query', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Searches', 'vfwp'); ?></th>
						<?php if (!$zero_only) : ?>
							<th scope="col"><?php echo esc_html__('No-result searches', 'vfwp'); ?></th>
							<th scope="col"><?php echo esc_html__('Average results', 'vfwp'); ?></th>
						<?php endif; ?>
						<th scope="col"><?php echo esc_html__('Last searched', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($rows as $row) : ?>
						<?php $query = isset($row['display_query']) && $row['display_query'] !== '' ? (string) $row['display_query'] : (string) $row['normalized_query']; ?>
						<tr>
							<th scope="row">
								<a href="<?php echo esc_url(add_query_arg(array('s' => $query), home_url('/'))); ?>">
									<?php echo esc_html($query); ?>
								</a>
							</th>
							<td><?php echo esc_html(number_format_i18n((int) $row['searches'])); ?></td>
							<?php if (!$zero_only) : ?>
								<td><?php echo esc_html(number_format_i18n((int) $row['zero_result_searches'])); ?></td>
								<td><?php echo esc_html(number_format_i18n((float) $row['average_results'], 1)); ?></td>
							<?php endif; ?>
							<td><?php echo esc_html($this->format_admin_datetime($row['last_searched_at'])); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render recent searches table.
	 *
	 * @param array $rows Rows.
	 * @return void
	 */
	private function render_recent_analytics_table(array $rows) {
		?>
		<h3><?php echo esc_html__('Recent searches', 'vfwp'); ?></h3>
		<?php if (empty($rows)) : ?>
			<p><?php echo esc_html__('No recent searches recorded yet.', 'vfwp'); ?></p>
		<?php else : ?>
			<table class="widefat striped" style="max-width: 920px;">
				<thead>
					<tr>
						<th scope="col"><?php echo esc_html__('Query', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Results', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('User email', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Searched', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($rows as $row) : ?>
						<?php $query = isset($row['query_text']) ? (string) $row['query_text'] : ''; ?>
						<tr>
							<th scope="row">
								<a href="<?php echo esc_url(add_query_arg(array('s' => $query), home_url('/'))); ?>">
									<?php echo esc_html($query); ?>
								</a>
							</th>
							<td><?php echo esc_html(number_format_i18n((int) $row['result_count'])); ?></td>
							<td><?php echo esc_html(!empty($row['user_email']) ? (string) $row['user_email'] : __('Not stored', 'vfwp')); ?></td>
							<td><?php echo esc_html($this->format_admin_datetime($row['searched_at'])); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render recent PDF extraction issues for administrators.
	 *
	 * @return void
	 */
	private function render_pdf_extraction_issues() {
		if (!class_exists('VFWP_Intranet_Search_Index_Repository')) {
			return;
		}

		$repository = new VFWP_Intranet_Search_Index_Repository();
		$issue_count = $repository->count_pdf_extraction_issues();

		if ($issue_count <= 0) {
			return;
		}

		$issues = $repository->get_pdf_extraction_issues(5);
		?>
		<div class="notice notice-warning">
			<p>
				<strong><?php echo esc_html__('PDF extraction issues detected.', 'vfwp'); ?></strong>
				<?php echo esc_html(sprintf(_n('%d PDF has an extraction issue.', '%d PDFs have extraction issues.', $issue_count, 'vfwp'), $issue_count)); ?>
			</p>
			<table class="widefat striped" style="max-width: 920px; margin: 0 0 12px;">
				<thead>
					<tr>
						<th scope="col"><?php echo esc_html__('Attachment', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Status', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Message', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Indexed', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($issues as $issue) : ?>
						<tr>
							<td>
								<a href="<?php echo esc_url(get_edit_post_link((int) $issue['object_id'])); ?>">
									<?php echo esc_html($issue['title'] !== '' ? $issue['title'] : $issue['file_name']); ?>
								</a>
							</td>
							<td><code><?php echo esc_html($issue['extraction_status']); ?></code></td>
							<td><?php echo esc_html($issue['extraction_error']); ?></td>
							<td><?php echo esc_html($issue['indexed_at']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Parse comma-separated ACF field names.
	 *
	 * @param mixed $raw_value Raw comma-separated input.
	 * @return array
	 */
	public static function parse_acf_field_names($raw_value) {
		$field_names = array();
		$raw_values = array();

		if (is_array($raw_value)) {
			$pending_values = array_values($raw_value);

			while (!empty($pending_values) && count($raw_values) < 100) {
				$value = array_shift($pending_values);

				if (is_array($value)) {
					$pending_values = array_merge($pending_values, array_values($value));
					continue;
				}

				if (is_scalar($value)) {
					$raw_values[] = (string) $value;
				}
			}
		} elseif (is_scalar($raw_value)) {
			$raw_values[] = (string) $raw_value;
		}

		$parts = explode(',', implode(',', $raw_values));

		foreach ($parts as $part) {
			$field_name = sanitize_key(trim($part));

			if ($field_name !== '' && $field_name !== 'array') {
				$field_names[$field_name] = $field_name;
			}
		}

		return array_values($field_names);
	}

	/**
	 * Default English query stopwords.
	 *
	 * @return array
	 */
	public static function default_stopwords() {
		return array(
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
	}

	/**
	 * Default ranking boost multipliers used by the search scoring formula.
	 *
	 * @return array
	 */
	public static function default_ranking_boosts() {
		return array(
			'exact_title'      => 1000,
			'title_phrase'     => 250,
			'title_all_terms'  => 100,
			'title_term'       => 20,
			'acf_phrase'       => 500,
			'acf_term'         => 20,
			'excerpt_phrase'   => 80,
			'excerpt_term'     => 8,
			'content_phrase'   => 30,
			'content_term'     => 4,
			'all_terms'        => 160,
			'term_coverage'    => 100,
			'fulltext_title'   => 25,
			'fulltext_acf'     => 18,
			'fulltext_excerpt' => 10,
			'fulltext_content' => 4,
			'recency'          => 2,
		);
	}

	/**
	 * Return ranking boost labels and descriptions.
	 *
	 * @return array
	 */
	public static function ranking_boost_definitions() {
		return array(
			'exact_title'      => array('label' => __('Exact title match', 'vfwp'), 'description' => __('Query exactly equals the indexed title.', 'vfwp')),
			'title_phrase'     => array('label' => __('Title phrase match', 'vfwp'), 'description' => __('Complete query phrase or protected phrase appears in the title.', 'vfwp')),
			'title_all_terms'  => array('label' => __('All terms in title', 'vfwp'), 'description' => __('Every searchable term appears in the title.', 'vfwp')),
			'title_term'       => array('label' => __('Title term match', 'vfwp'), 'description' => __('Each matched title term contributes this boost.', 'vfwp')),
			'acf_phrase'       => array('label' => __('Exact ACF keyword entry', 'vfwp'), 'description' => __('The full query exactly matches one configured ACF keyword entry.', 'vfwp')),
			'acf_term'         => array('label' => __('ACF keyword term boost', 'vfwp'), 'description' => __('Additional per-term boost when an exact ACF keyword entry matches.', 'vfwp')),
			'excerpt_phrase'   => array('label' => __('Excerpt phrase match', 'vfwp'), 'description' => __('Complete query phrase or protected phrase appears in the excerpt.', 'vfwp')),
			'excerpt_term'     => array('label' => __('Excerpt term match', 'vfwp'), 'description' => __('Each matched excerpt term contributes this boost.', 'vfwp')),
			'content_phrase'   => array('label' => __('Content phrase match', 'vfwp'), 'description' => __('Complete query phrase or protected phrase appears in main/PDF content.', 'vfwp')),
			'content_term'     => array('label' => __('Content term match', 'vfwp'), 'description' => __('Each matched content term contributes this boost.', 'vfwp')),
			'all_terms'        => array('label' => __('All terms anywhere', 'vfwp'), 'description' => __('Bonus when every searchable term appears across title, excerpt or content.', 'vfwp')),
			'term_coverage'    => array('label' => __('Term coverage', 'vfwp'), 'description' => __('Proportional bonus based on how many query terms matched.', 'vfwp')),
			'fulltext_title'   => array('label' => __('FULLTEXT title score', 'vfwp'), 'description' => __('Database FULLTEXT relevance contribution from title.', 'vfwp')),
			'fulltext_acf'     => array('label' => __('FULLTEXT ACF score', 'vfwp'), 'description' => __('Database FULLTEXT contribution from ACF keywords after an exact keyword entry match.', 'vfwp')),
			'fulltext_excerpt' => array('label' => __('FULLTEXT excerpt score', 'vfwp'), 'description' => __('Database FULLTEXT relevance contribution from excerpt.', 'vfwp')),
			'fulltext_content' => array('label' => __('FULLTEXT content score', 'vfwp'), 'description' => __('Database FULLTEXT relevance contribution from content/PDF text.', 'vfwp')),
			'recency'          => array('label' => __('Recent content bonus', 'vfwp'), 'description' => __('Small final bonus for content published in the last 30 days.', 'vfwp')),
		);
	}

	/**
	 * Sanitize ranking boost settings.
	 *
	 * @param array $boosts Raw boosts.
	 * @return array
	 */
	public static function sanitize_ranking_boosts(array $boosts) {
		$sanitized = array();

		foreach (self::default_ranking_boosts() as $boost => $default_value) {
			$value = isset($boosts[$boost]) ? $boosts[$boost] : $default_value;
			$value = (float) $value;
			$sanitized[$boost] = round(max(0, $value), 2);
		}

		return $sanitized;
	}

	/**
	 * Sanitize analytics settings.
	 *
	 * @param array $settings Raw analytics settings.
	 * @return array
	 */
	public static function sanitize_analytics_settings($settings) {
		$settings = is_array($settings) ? $settings : array();
		$defaults = self::defaults()['analytics'];

		return array(
			'enabled'        => empty($settings['enabled']) ? 0 : 1,
			'exclude_admins' => empty($settings['exclude_admins']) ? 0 : 1,
			'track_user_email' => empty($settings['track_user_email']) ? 0 : 1,
			'retention_days' => min(730, max(7, absint(isset($settings['retention_days']) ? $settings['retention_days'] : $defaults['retention_days']))),
		);
	}

	/**
	 * Parse editable stopwords from text or array input.
	 *
	 * @param mixed $raw_value Raw input.
	 * @return array
	 */
	public static function parse_stopwords($raw_value) {
		$stopwords = array();
		$raw_values = array();

		if (is_array($raw_value)) {
			$pending_values = array_values($raw_value);

			while (!empty($pending_values) && count($raw_values) < 500) {
				$value = array_shift($pending_values);

				if (is_array($value)) {
					$pending_values = array_merge($pending_values, array_values($value));
					continue;
				}

				if (is_scalar($value)) {
					$raw_values[] = (string) $value;
				}
			}
		} elseif (is_scalar($raw_value)) {
			$raw_values[] = (string) $raw_value;
		}

		$parts = preg_split('/[\s,]+/u', implode("\n", $raw_values));

		if (!is_array($parts)) {
			return array();
		}

		foreach ($parts as $part) {
			$stopword = sanitize_key(trim($part));

			if ($stopword !== '') {
				$stopwords[$stopword] = $stopword;
			}
		}

		return array_values($stopwords);
	}

	/**
	 * Parse exact phrase searches from text or array input.
	 *
	 * @param mixed $raw_value Raw input.
	 * @return array
	 */
	public static function parse_exact_phrases($raw_value) {
		$phrases = array();
		$raw_values = array();

		if (is_array($raw_value)) {
			$pending_values = array_values($raw_value);

			while (!empty($pending_values) && count($raw_values) < 100) {
				$value = array_shift($pending_values);

				if (is_array($value)) {
					$pending_values = array_merge($pending_values, array_values($value));
					continue;
				}

				if (is_scalar($value)) {
					$raw_values[] = (string) $value;
				}
			}
		} elseif (is_scalar($raw_value)) {
			$raw_values[] = (string) $raw_value;
		}

		$parts = preg_split('/[\r\n,]+/u', implode("\n", $raw_values));

		if (!is_array($parts)) {
			return array();
		}

		foreach ($parts as $part) {
			$phrase = self::normalize_exact_phrase($part);

			if ($phrase !== '') {
				$phrases[$phrase] = $phrase;
			}

			if (count($phrases) >= 100) {
				break;
			}
		}

		return array_values($phrases);
	}

	/**
	 * Parse directional query synonyms from source = replacement lines.
	 *
	 * @param mixed $raw_value Raw input.
	 * @return array
	 */
	public static function parse_synonyms($raw_value) {
		$synonyms = array();
		$raw_values = array();

		if (is_array($raw_value)) {
			$pending_values = array_values($raw_value);

			while (!empty($pending_values) && count($raw_values) < 100) {
				$value = array_shift($pending_values);

				if (is_array($value)) {
					if (isset($value['from']) || isset($value['to'])) {
						$from = isset($value['from']) ? $value['from'] : '';
						$to = isset($value['to']) ? $value['to'] : '';
						self::append_synonym($synonyms, $from, $to);
						continue;
					}

					$pending_values = array_merge($pending_values, array_values($value));
					continue;
				}

				if (is_scalar($value)) {
					$raw_values[] = (string) $value;
				}
			}
		} elseif (is_scalar($raw_value)) {
			$raw_values[] = (string) $raw_value;
		}

		$lines = preg_split('/[\r\n]+/u', implode("\n", $raw_values));

		if (!is_array($lines)) {
			return array();
		}

		foreach ($lines as $line) {
			if (strpos($line, '=') === false) {
				continue;
			}

			list($from, $to) = array_map('trim', explode('=', $line, 2));
			self::append_synonym($synonyms, $from, $to);

			if (count($synonyms) >= 100) {
				break;
			}
		}

		$values = array_values($synonyms);

		usort($values, array(__CLASS__, 'sort_synonyms_by_source_length_desc'));

		return $values;
	}

	/**
	 * Append one normalized synonym pair.
	 *
	 * @param array $synonyms Synonym store.
	 * @param mixed $from Source phrase.
	 * @param mixed $to Replacement phrase.
	 * @return void
	 */
	private static function append_synonym(array &$synonyms, $from, $to) {
		$from = self::normalize_exact_phrase($from);
		$to = self::normalize_exact_phrase($to);

		if ($from === '' || $to === '' || $from === $to) {
			return;
		}

		$synonyms[$from] = array(
			'from' => $from,
			'to'   => $to,
		);
	}

	/**
	 * Sort synonyms from longest source phrase to shortest.
	 *
	 * @param array $a First synonym.
	 * @param array $b Second synonym.
	 * @return int
	 */
	private static function sort_synonyms_by_source_length_desc($a, $b) {
		return strlen((string) $b['from']) - strlen((string) $a['from']);
	}

	/**
	 * Normalize an exact phrase using the same punctuation rules as visitor queries.
	 *
	 * @param mixed $value Raw phrase.
	 * @return string
	 */
	private static function normalize_exact_phrase($value) {
		if (!is_scalar($value)) {
			return '';
		}

		$text = wp_strip_all_tags((string) $value, true);
		$text = wp_specialchars_decode($text, ENT_QUOTES);
		$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, get_bloginfo('charset'));
		$text = str_replace(array('’', '‘', '‚', '`', '´'), "'", $text);
		$text = str_replace(array('‐', '‑', '‒', '–', '—', '―'), '-', $text);

		if (function_exists('remove_accents')) {
			$text = remove_accents($text);
		}

		$text = str_replace(array("'", '-'), ' ', $text);
		$text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
		$text = preg_replace('/\s+/u', ' ', is_string($text) ? $text : '');
		$text = is_string($text) ? trim($text) : '';

		if ($text === '') {
			return '';
		}

		if (function_exists('mb_strtolower')) {
			return mb_strtolower($text, 'UTF-8');
		}

		return strtolower($text);
	}

	/**
	 * Sanitize minimum query word length.
	 *
	 * @param mixed $value Raw value.
	 * @return int
	 */
	public static function sanitize_min_word_length_value($value) {
		return min(10, max(1, absint($value)));
	}

	/**
	 * Sanitize a numeric relevance weight.
	 *
	 * @param mixed $value Raw value.
	 * @return float
	 */
	private function sanitize_weight($value) {
		$weight = (float) $value;

		if ($weight < 0) {
			$weight = 0;
		}

		return round($weight, 2);
	}

	/**
	 * Format a ranking number for admin display.
	 *
	 * @param mixed $value Numeric value.
	 * @return string
	 */
	private function format_decimal($value) {
		return number_format_i18n((float) $value, 2);
	}

	/**
	 * Format a UTC datetime for admin display.
	 *
	 * @param string $datetime MySQL datetime.
	 * @return string
	 */
	private function format_admin_datetime($datetime) {
		$timestamp = strtotime((string) $datetime . ' UTC');

		if (!$timestamp) {
			return (string) $datetime;
		}

		return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
	}

	/**
	 * Determine whether a settings change requires content reindexing.
	 *
	 * @param array $old_settings Old settings.
	 * @param array $new_settings New settings.
	 * @return bool
	 */
	private function settings_change_requires_rebuild(array $old_settings, array $new_settings) {
		if ($old_settings['acf_field_names'] !== $new_settings['acf_field_names']) {
			return true;
		}

		foreach (self::get_searchable_post_types() as $post_type => $post_type_object) {
			$old_post_type = self::get_post_type_setting($post_type, $old_settings);
			$new_post_type = self::get_post_type_setting($post_type, $new_settings);

			if ((int) $old_post_type['include'] !== (int) $new_post_type['include']) {
				return true;
			}
		}

		return false;
	}
}
