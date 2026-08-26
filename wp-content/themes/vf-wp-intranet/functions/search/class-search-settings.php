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
			'acf_field_names' => array(),
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

		if (!is_array($settings['post_types'])) {
			$settings['post_types'] = array();
		}

		$settings['acf_field_names'] = is_array($settings['acf_field_names'])
			? self::parse_acf_field_names(implode(',', $settings['acf_field_names']))
			: self::parse_acf_field_names((string) $settings['acf_field_names']);

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
	 * Return configured ACF field names.
	 *
	 * @return array
	 */
	public static function get_acf_field_names() {
		return self::get_settings()['acf_field_names'];
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
			'manage_options',
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
		if (!current_user_can('manage_options')) {
			return self::get_settings();
		}

		$old_settings = self::get_settings();
		$input = is_array($input) ? wp_unslash($input) : array();
		$sanitized = self::defaults();

		foreach ($sanitized['field_weights'] as $field => $default_weight) {
			$value = isset($input['field_weights'][$field]) ? $input['field_weights'][$field] : $default_weight;
			$sanitized['field_weights'][$field] = $this->sanitize_weight($value);
		}

		$raw_acf_field_names = isset($input['acf_field_names']) ? (string) $input['acf_field_names'] : '';
		$sanitized['acf_field_names'] = self::parse_acf_field_names($raw_acf_field_names);

		foreach (self::get_searchable_post_types() as $post_type => $post_type_object) {
			$post_type_input = isset($input['post_types'][$post_type]) && is_array($input['post_types'][$post_type])
				? $input['post_types'][$post_type]
				: array();

			$sanitized['post_types'][$post_type] = array(
				'include' => empty($post_type_input['include']) ? 0 : 1,
				'weight'  => $this->sanitize_weight(isset($post_type_input['weight']) ? $post_type_input['weight'] : 1),
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
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have permission to manage search settings.', 'vfwp'));
		}

		$rebuild_status = get_option(self::REBUILD_REQUIRED_OPTION, array());
		?>
		<div class="wrap">
			<h1><?php echo esc_html__('Search', 'vfwp'); ?></h1>

			<?php $this->render_index_action_notice(); ?>

			<?php if (is_array($rebuild_status) && !empty($rebuild_status['required'])) : ?>
				<div class="notice notice-warning">
					<p>
						<strong><?php echo esc_html__('Search index rebuild required.', 'vfwp'); ?></strong>
						<?php echo esc_html(isset($rebuild_status['reason']) ? $rebuild_status['reason'] : ''); ?>
					</p>
				</div>
			<?php endif; ?>

			<?php $this->render_index_management(); ?>
			<?php $this->render_pdf_extraction_issues(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields('vfwp_intranet_search_settings');
				do_settings_sections('vfwp-intranet-search');
				submit_button();
				?>
			</form>
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
					<th scope="row"><?php echo esc_html__('Indexed PDF documents', 'vfwp'); ?></th>
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
		<p class="description"><?php echo esc_html__('Index jobs run in safe batches through WP-Cron. Refresh this page to observe progress.', 'vfwp'); ?></p>
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
		$labels = array(
			'title'        => __('Title', 'vfwp'),
			'acf_keywords' => __('ACF keyword fields', 'vfwp'),
			'excerpt'      => __('Excerpt', 'vfwp'),
			'content'      => __('Main content', 'vfwp'),
		);
		?>
		<table class="widefat striped" style="max-width: 640px;">
			<thead>
				<tr>
					<th scope="col"><?php echo esc_html__('Field', 'vfwp'); ?></th>
					<th scope="col"><?php echo esc_html__('Weight', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($labels as $field => $label) : ?>
					<tr>
						<th scope="row"><?php echo esc_html($label); ?></th>
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
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p class="description"><?php echo esc_html__('Changing these values takes effect immediately when the custom search query layer uses the index.', 'vfwp'); ?></p>
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
	 * @param string $raw_value Raw comma-separated input.
	 * @return array
	 */
	public static function parse_acf_field_names($raw_value) {
		$field_names = array();
		$parts = explode(',', (string) $raw_value);

		foreach ($parts as $part) {
			$field_name = sanitize_key(trim($part));

			if ($field_name !== '') {
				$field_names[$field_name] = $field_name;
			}
		}

		return array_values($field_names);
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
