<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Events_Thank_You') ) :

class VF_Events_Thank_You {

  const META_IS_THANK_YOU_PAGE = '_vf_event_thank_you_page';
  const FIELD_ENABLE_SETTING = 'vf_events_enable_thank_you_pages';
  const FIELD_DEFAULT_CONTENT_SETTING = 'vf_events_default_thank_you_content';
  const FIELD_BUILDER_EVENTS = 'vf_events_thank_you_selected_events';
  const FIELD_INCLUDE_EVENT = 'vf_event_include_thank_you_page';
  const FIELD_CONTENT_EVENT = 'vf_event_thank_you_content';
  const CLEANUP_HOOK = 'vf_events_cleanup_expired_thank_you_pages_daily';
  const ROUTE_QUERY_VAR = 'vf_event_thank_you_event';
  const REWRITE_VERSION = '20260610_1';

  private $file;

  function __construct($file) {
    $this->file = $file;

    add_action('acf/init', array($this, 'acf_init'));
    add_filter(
      'acf/prepare_field/name=vf_events_thank_you_builder',
      array($this, 'prepare_builder_field')
    );
    add_filter(
      'acf/prepare_field/key=field_vf_event_thank_you_tab',
      array($this, 'prepare_event_field')
    );
    add_filter(
      'acf/prepare_field/key=field_vf_event_include_thank_you_page',
      array($this, 'prepare_event_field')
    );
    add_filter(
      'acf/prepare_field/key=field_vf_event_thank_you_content',
      array($this, 'prepare_event_field')
    );
    add_action(
      'admin_post_vf_events_build_thank_you_pages',
      array($this, 'admin_post_build_thank_you_pages')
    );
    add_action(
      'wp_ajax_vf_events_ajax_build_thank_you_pages',
      array($this, 'ajax_build_thank_you_pages')
    );
    add_action(
      'wp_ajax_vf_events_ajax_save_default_thank_you_content',
      array($this, 'ajax_save_default_thank_you_content')
    );
    add_action(
      'wp_ajax_vf_events_ajax_delete_all_thank_you_pages',
      array($this, 'ajax_delete_all_thank_you_pages')
    );
    add_action(
      'wp_ajax_vf_events_ajax_delete_thank_you_page',
      array($this, 'ajax_delete_thank_you_page')
    );
    add_action(
      'wp_ajax_vf_events_ajax_set_thank_you_pages_enabled',
      array($this, 'ajax_set_thank_you_pages_enabled')
    );
    add_action(
      'admin_post_vf_events_delete_thank_you_page',
      array($this, 'admin_post_delete_thank_you_page')
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
    );
    add_action('admin_notices', array($this, 'admin_notices'));
    add_action('acf/save_post', array($this, 'acf_save_post'), 20);
    add_action(
      'save_post_' . VF_Events::type(),
      array($this, 'maybe_build_on_event_save'),
      30, 3
    );
    add_action('pre_get_posts', array($this, 'pre_get_posts'), 20);
    add_filter(
      'manage_' . VF_Events::type() . '_posts_columns',
      array($this, 'posts_columns')
    );
    add_action(
      'manage_' . VF_Events::type() . '_posts_custom_column',
      array($this, 'posts_custom_column'),
      10, 2
    );
    add_filter(
      'acf/fields/post_object/query/name=' . self::FIELD_BUILDER_EVENTS,
      array($this, 'acf_query_builder_events'),
      10, 3
    );
    add_filter(
      'acf/fields/post_object/result/name=' . self::FIELD_BUILDER_EVENTS,
      array($this, 'acf_result_builder_events'),
      10, 4
    );
    add_filter('wp_robots', array($this, 'wp_robots'));
    add_action('wp_head', array($this, 'wp_head'), 1);
    add_action('init', array($this, 'add_rewrite_rules'), 20);
    add_filter('query_vars', array($this, 'query_vars'));
    add_filter('request', array($this, 'request'));
    add_action('init', array($this, 'maybe_schedule_cleanup'));
    add_action(self::CLEANUP_HOOK, array($this, 'cleanup_expired_thank_you_pages'));
  }

  public function acf_init() {
    if ( ! function_exists('acf_add_local_field_group')) {
      return;
    }

    acf_add_local_field_group(array(
      'key' => 'group_vf_events_thank_you_settings',
      'title' => __('Thank you Page Settings', 'vfwp'),
      'fields' => array(
        array(
          'key' => 'field_vf_events_enable_thank_you_pages',
          'label' => __('Enable thank you pages', 'vfwp'),
          'name' => self::FIELD_ENABLE_SETTING,
          'type' => 'true_false',
          'instructions' => __('*Only for events added by Course and Conference Office', 'vfwp'),
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => 'vf-events-thank-you-settings-field',
            'id' => '',
          ),
          'message' => '',
          'default_value' => 0,
          'ui' => 1,
          'ui_on_text' => __('Enabled', 'vfwp'),
          'ui_off_text' => __('Disabled', 'vfwp'),
        ),
        array(
          'key' => 'field_vf_events_default_thank_you_content',
          'label' => __('Default thank you content', 'vfwp'),
          'name' => self::FIELD_DEFAULT_CONTENT_SETTING,
          'type' => 'wysiwyg',
          'instructions' => __('Used on generated thank you pages when an event does not have its own thank you content.', 'vfwp'),
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_vf_events_enable_thank_you_pages',
                'operator' => '==',
                'value' => '1',
              ),
            ),
          ),
          'wrapper' => array(
            'width' => '',
            'class' => 'vf-events-thank-you-settings-field',
            'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'full',
          'media_upload' => 0,
          'delay' => 0,
        ),
        array(
          'key' => 'field_vf_events_thank_you_selected_events',
          'label' => __('Select events', 'vfwp'),
          'name' => self::FIELD_BUILDER_EVENTS,
          'type' => 'post_object',
          'instructions' => __('Choose published events to build thank you pages for.', 'vfwp'),
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_vf_events_enable_thank_you_pages',
                'operator' => '==',
                'value' => '1',
              ),
            ),
          ),
          'wrapper' => array(
            'width' => '',
            'class' => 'vf-events-thank-you-settings-field',
            'id' => '',
          ),
          'post_type' => array(VF_Events::type()),
          'taxonomy' => '',
          'allow_null' => 0,
          'multiple' => 1,
          'return_format' => 'id',
          'ui' => 1,
        ),
        array(
          'key' => 'field_vf_events_thank_you_builder',
          'label' => __('Build thank you pages', 'vfwp'),
          'name' => 'vf_events_thank_you_builder',
          'type' => 'message',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_vf_events_enable_thank_you_pages',
                'operator' => '==',
                'value' => '1',
              ),
            ),
          ),
          'wrapper' => array(
            'width' => '',
            'class' => 'vf-events-thank-you-settings-field',
            'id' => '',
          ),
          'message' => '',
          'new_lines' => '',
          'esc_html' => 0,
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'vf-events-settings',
          ),
        ),
      ),
      'menu_order' => 11,
      'position' => 'normal',
      'style' => 'seamless',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => __('Thank you page settings for event pages.', 'vfwp'),
    ));
  }

  public function prepare_builder_field($field) {
    $field['message'] = $this->get_builder_field_markup();
    return $field;
  }

  public function prepare_event_field($field) {
    if ( ! self::is_enabled()) {
      return false;
    }

    return $field;
  }

  private function get_builder_field_markup() {
    ob_start();
    ?>
    <div class="vf-events-thank-you-builder" data-vf-events-thank-you-builder>
    <?php wp_nonce_field('vf_events_build_thank_you_pages', 'vf_events_thank_you_nonce'); ?>
    <?php wp_nonce_field('vf_events_delete_thank_you_page', 'vf_events_delete_thank_you_nonce'); ?>
    <input type="hidden" name="vf_events_thank_you_action" value="">
    <input type="hidden" name="vf_events_thank_you_page_id" value="">
    <input type="hidden" name="vf_events_thank_you_was_enabled" value="<?php echo esc_attr(self::is_enabled() ? '1' : '0'); ?>">
    <input type="hidden" name="vf_events_disable_thank_you_confirmed" value="">
    <p class="vf-events-thank-you-builder__actions">
      <button
        type="button"
        class="button"
        data-vf-events-thank-you-select-all
      ><?php esc_html_e('Select all available events', 'vfwp'); ?></button>
      <button
        type="submit"
        name="vf_events_build_thank_you_pages"
        value="1"
        class="button button-primary"
      ><?php esc_html_e('Build', 'vfwp'); ?></button>
    </p>
    <div data-vf-events-thank-you-notices></div>
    <div data-vf-events-thank-you-table>
      <?php echo $this->get_existing_pages_table_markup(); ?>
    </div>
    </div>
    <?php
    return ob_get_clean();
  }

  private function get_existing_pages_table_markup() {
    $thank_you_pages = self::get_existing_thank_you_pages();

    if (empty($thank_you_pages)) {
      return '';
    }

    ob_start();
    ?>
    <h3><?php esc_html_e('Thank you pages', 'vfwp'); ?></h3>
    <table class="widefat striped">
      <thead>
        <tr>
          <th scope="col"><?php esc_html_e('Event', 'vfwp'); ?></th>
          <th scope="col"><?php esc_html_e('Thank you page', 'vfwp'); ?></th>
          <th scope="col"><?php esc_html_e('Start date', 'vfwp'); ?></th>
          <th scope="col"><?php esc_html_e('Actions', 'vfwp'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($thank_you_pages as $thank_you_page) {
          $event_id = self::get_parent_event_id($thank_you_page->ID);
          $event_title = $event_id ? get_the_title($event_id) : __('Missing parent event', 'vfwp');
          $event_start_date = $event_id ? get_field('vf_event_start_date', $event_id) : '';
          $thank_you_url = self::get_thank_you_page_url($thank_you_page->ID);
          ?>
          <tr>
            <td>
              <strong><?php echo esc_html($event_title); ?></strong>
            </td>
            <td>
              <a href="<?php echo esc_url($thank_you_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('View', 'vfwp'); ?></a>
            </td>
            <td>
              <?php echo esc_html($event_start_date); ?>
            </td>
            <td>
              <button
                type="submit"
                name="vf_events_delete_thank_you_page"
                value="<?php echo esc_attr($thank_you_page->ID); ?>"
                class="button vf-events-button--danger"
              ><?php esc_html_e('Delete', 'vfwp'); ?></button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php
    return ob_get_clean();
  }

  public function admin_enqueue_scripts() {
    if (
      empty($_GET['page']) ||
      $_GET['page'] !== 'vf-events-settings'
    ) {
      return;
    }

    $script_path = plugin_dir_path($this->file) . 'assets/thank-you-admin.js';

    wp_enqueue_script(
      'vf-events-thank-you-admin',
      plugin_dir_url($this->file) . 'assets/thank-you-admin.js',
      array(),
      file_exists($script_path) ? filemtime($script_path) : null,
      true
    );

    wp_localize_script(
      'vf-events-thank-you-admin',
      'vfEventsThankYouAdmin',
      array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'buildNonce' => wp_create_nonce('vf_events_build_thank_you_pages'),
        'deleteNonce' => wp_create_nonce('vf_events_delete_thank_you_page'),
        'enabled' => self::is_enabled() ? '1' : '0',
        'warning' => __('Disabling thank you pages will remove all existing thank you pages.', 'vfwp'),
        'confirm' => __('Disabling thank you pages will remove all existing thank you pages. Do you want to continue?', 'vfwp'),
        'deleteAllFailed' => __('The thank you pages could not be removed.', 'vfwp'),
        'alreadyBuiltLabel' => __('Already has a thank you page', 'vfwp'),
        'saveDefaultContentLabel' => __('Save', 'vfwp'),
        'availableEvents' => self::get_available_builder_event_choices(),
        'unavailableEventIds' => self::get_unavailable_builder_event_ids(),
      )
    );
  }

  public function add_rewrite_rules() {
    $post_type_object = get_post_type_object(VF_Events::type());

    if ( ! $post_type_object || empty($post_type_object->rewrite['slug'])) {
      return;
    }

    $slug = trim($post_type_object->rewrite['slug'], '/');

    add_rewrite_rule(
      $slug . '/([^/]+)/thank-you/?$',
      'index.php?' . self::ROUTE_QUERY_VAR . '=$matches[1]',
      'top'
    );

    if (get_option('vf_events_thank_you_rewrite_version') !== self::REWRITE_VERSION) {
      flush_rewrite_rules(false);
      update_option('vf_events_thank_you_rewrite_version', self::REWRITE_VERSION, false);
    }
  }

  public function query_vars($vars) {
    $vars[] = self::ROUTE_QUERY_VAR;
    $vars[] = 'vf_events_include_thank_you_pages';

    return $vars;
  }

  public function request($query_vars) {
    if (empty($query_vars[self::ROUTE_QUERY_VAR])) {
      return $query_vars;
    }

    $event_id = self::get_event_id_by_slug($query_vars[self::ROUTE_QUERY_VAR]);
    $thank_you_id = $event_id ? self::get_thank_you_page_id($event_id) : 0;

    if ( ! $thank_you_id) {
      return array('error' => '404');
    }

    $query_vars['post_type'] = VF_Events::type();
    $query_vars['p'] = $thank_you_id;
    $query_vars['vf_events_include_thank_you_pages'] = true;

    return $query_vars;
  }

  static private function get_event_id_by_slug($slug) {
    $events = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => 'publish',
      'name' => sanitize_title($slug),
      'posts_per_page' => 1,
      'fields' => 'ids',
      'vf_events_include_thank_you_pages' => true,
      'meta_query' => array(
        array(
          'key' => self::META_IS_THANK_YOU_PAGE,
          'compare' => 'NOT EXISTS',
        ),
      ),
    ));

    return empty($events) ? 0 : (int) $events[0];
  }

  public function admin_post_build_thank_you_pages() {
    if ( ! current_user_can('manage_options')) {
      wp_die(esc_html__('You are not allowed to build thank you pages.', 'vfwp'));
    }

    check_admin_referer('vf_events_build_thank_you_pages', 'vf_events_thank_you_nonce');

    if ( ! self::is_enabled()) {
      $this->redirect_to_settings('disabled');
    }

    $built = $this->build_selected_events();
    $this->clear_builder_selection();

    $this->redirect_to_settings('built', $built);
  }

  public function ajax_build_thank_you_pages() {
    if ( ! current_user_can('manage_options')) {
      wp_send_json_error(array(
        'message' => __('You are not allowed to build thank you pages.', 'vfwp'),
      ), 403);
    }

    if (
      empty($_POST['vf_events_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_thank_you_nonce'])),
        'vf_events_build_thank_you_pages'
      )
    ) {
      wp_send_json_error(array(
        'message' => __('The build request could not be verified.', 'vfwp'),
      ), 400);
    }

    if ( ! self::is_enabled()) {
      wp_send_json_error(array(
        'message' => __('Thank you pages are disabled in Events Settings.', 'vfwp'),
      ), 400);
    }

    $this->maybe_update_default_thank_you_content_from_post();
    $built = $this->build_selected_events();
    $this->clear_builder_selection();

    wp_send_json_success(array(
      'built' => $built,
      'message' => sprintf(
        _n(
          'Built %d thank you page.',
          'Built %d thank you pages.',
          $built,
          'vfwp'
        ),
        $built
      ),
      'tableHtml' => $this->get_existing_pages_table_markup(),
      'availableEvents' => self::get_available_builder_event_choices(),
      'unavailableEventIds' => self::get_unavailable_builder_event_ids(),
    ));
  }

  public function ajax_save_default_thank_you_content() {
    if ( ! current_user_can('manage_options')) {
      wp_send_json_error(array(
        'message' => __('You are not allowed to update thank you page settings.', 'vfwp'),
      ), 403);
    }

    if (
      empty($_POST['vf_events_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_thank_you_nonce'])),
        'vf_events_build_thank_you_pages'
      )
    ) {
      wp_send_json_error(array(
        'message' => __('The save request could not be verified.', 'vfwp'),
      ), 400);
    }

    $this->maybe_update_default_thank_you_content_from_post();

    wp_send_json_success(array(
      'message' => __('Saved default thank you content.', 'vfwp'),
    ));
  }

  public function ajax_delete_all_thank_you_pages() {
    if ( ! current_user_can('manage_options')) {
      wp_send_json_error(array(
        'message' => __('You are not allowed to delete thank you pages.', 'vfwp'),
      ), 403);
    }

    if (
      empty($_POST['vf_events_delete_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_delete_thank_you_nonce'])),
        'vf_events_delete_thank_you_page'
      )
    ) {
      wp_send_json_error(array(
        'message' => __('The delete request could not be verified.', 'vfwp'),
      ), 400);
    }

    $deleted = self::delete_all_thank_you_pages();
    self::set_enabled(false);
    $this->clear_builder_selection();

    wp_send_json_success(array(
      'deleted' => $deleted,
      'message' => sprintf(
        _n(
          'Deleted %d existing thank you page.',
          'Deleted %d existing thank you pages.',
          $deleted,
          'vfwp'
        ),
        $deleted
      ),
      'tableHtml' => $this->get_existing_pages_table_markup(),
      'availableEvents' => self::get_available_builder_event_choices(),
      'unavailableEventIds' => self::get_unavailable_builder_event_ids(),
    ));
  }

  public function ajax_delete_thank_you_page() {
    if ( ! current_user_can('manage_options')) {
      wp_send_json_error(array(
        'message' => __('You are not allowed to delete thank you pages.', 'vfwp'),
      ), 403);
    }

    $deleted = $this->delete_thank_you_page_from_post();

    if ( ! $deleted) {
      wp_send_json_error(array(
        'message' => __('The thank you page could not be deleted.', 'vfwp'),
      ), 400);
    }

    wp_send_json_success(array(
      'message' => __('Deleted the thank you page and updated the event toggle.', 'vfwp'),
      'tableHtml' => $this->get_existing_pages_table_markup(),
      'availableEvents' => self::get_available_builder_event_choices(),
      'unavailableEventIds' => self::get_unavailable_builder_event_ids(),
    ));
  }

  public function ajax_set_thank_you_pages_enabled() {
    if ( ! current_user_can('manage_options')) {
      wp_send_json_error(array(
        'message' => __('You are not allowed to update thank you page settings.', 'vfwp'),
      ), 403);
    }

    if (
      empty($_POST['vf_events_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_thank_you_nonce'])),
        'vf_events_build_thank_you_pages'
      )
    ) {
      wp_send_json_error(array(
        'message' => __('The settings request could not be verified.', 'vfwp'),
      ), 400);
    }

    $enabled = ! empty($_POST['enabled']);
    self::set_enabled($enabled);

    wp_send_json_success(array(
        'enabled' => $enabled ? '1' : '0',
        'message' => $enabled
        ? ''
        : __('Thank you pages are disabled.', 'vfwp'),
    ));
  }

  public function admin_post_delete_thank_you_page() {
    if ( ! current_user_can('manage_options')) {
      wp_die(esc_html__('You are not allowed to delete thank you pages.', 'vfwp'));
    }

    check_admin_referer('vf_events_delete_thank_you_page', 'vf_events_delete_thank_you_nonce');

    $thank_you_id = $this->get_delete_thank_you_page_id_from_post();

    if ( ! $thank_you_id || get_post_type($thank_you_id) !== VF_Events::type()) {
      $this->redirect_to_settings('delete_failed');
    }

    $event_id = self::get_parent_event_id($thank_you_id);

    if ( ! self::is_thank_you_url_page($thank_you_id)) {
      $this->redirect_to_settings('delete_failed');
    }

    $deleted = wp_delete_post($thank_you_id, true);

    if ($deleted && $event_id) {
      self::set_event_include_thank_you_page($event_id, false);
      $this->redirect_to_settings('deleted');
    }

    $this->redirect_to_settings('delete_failed');
  }

  public function admin_notices() {
    if (
      empty($_GET['page']) ||
      $_GET['page'] !== 'vf-events-settings'
    ) {
      return;
    }

    if (empty($_GET['vf_events_thank_you_status'])) {
      $last_build_count = get_option('vf_events_thank_you_last_build_count', null);
      $last_delete_status = get_option('vf_events_thank_you_last_delete_status', null);
      $last_delete_all_count = get_option('vf_events_thank_you_last_delete_all_count', null);

      if ($last_build_count !== null) {
        delete_option('vf_events_thank_you_last_build_count');
        printf(
          '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
          esc_html(sprintf(
            _n(
              'Built %d thank you page.',
              'Built %d thank you pages.',
              (int) $last_build_count,
              'vfwp'
            ),
            (int) $last_build_count
          ))
        );
      }

      if ($last_delete_status !== null) {
        delete_option('vf_events_thank_you_last_delete_status');
        printf(
          '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
          esc_html(
            $last_delete_status === 'deleted'
              ? __('Deleted the thank you page and updated the event toggle.', 'vfwp')
              : __('The thank you page could not be deleted.', 'vfwp')
          )
        );
      }

      if ($last_delete_all_count !== null) {
        delete_option('vf_events_thank_you_last_delete_all_count');
        printf(
          '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
          esc_html(sprintf(
            _n(
              'Deleted %d existing thank you page.',
              'Deleted %d existing thank you pages.',
              (int) $last_delete_all_count,
              'vfwp'
            ),
            (int) $last_delete_all_count
          ))
        );
      }

      return;
    }

    $status = sanitize_key($_GET['vf_events_thank_you_status']);

    if ($status === 'disabled') {
      printf(
        '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
        esc_html__('Thank you pages are disabled in Events Settings.', 'vfwp')
      );
      return;
    }

    if ($status === 'built') {
      $built = isset($_GET['vf_events_thank_you_built'])
        ? intval($_GET['vf_events_thank_you_built'])
        : 0;

      printf(
        '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
        esc_html(sprintf(
          _n(
            'Built %d thank you page.',
            'Built %d thank you pages.',
            $built,
            'vfwp'
          ),
          $built
        ))
      );
    }

    if ($status === 'deleted') {
      printf(
        '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
        esc_html__('Deleted the thank you page and updated the event toggle.', 'vfwp')
      );
      return;
    }

    if ($status === 'delete_failed') {
      printf(
        '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
        esc_html__('The thank you page could not be deleted.', 'vfwp')
      );
    }
  }

  private function redirect_to_settings($status, $built = 0) {
    wp_safe_redirect(add_query_arg(array(
      'page' => 'vf-events-settings',
      'vf_events_thank_you_status' => $status,
      'vf_events_thank_you_built' => $built,
    ), admin_url('edit.php?post_type=' . VF_Events::type())));
    exit;
  }

  private function get_builder_event_ids_from_post() {
    $event_ids = array();

    if (isset($_POST['vf_events_thank_you_event_ids'])) {
      $event_ids = (array) $_POST['vf_events_thank_you_event_ids'];
    } elseif (isset($_POST['acf']['field_vf_events_thank_you_selected_events'])) {
      $event_ids = (array) $_POST['acf']['field_vf_events_thank_you_selected_events'];
    }

    if (empty($event_ids) && function_exists('get_field')) {
      $event_ids = (array) get_field(self::FIELD_BUILDER_EVENTS, 'option', false);
    }

    return array_values(array_unique(array_filter(array_map('intval', $event_ids))));
  }

  private function get_delete_thank_you_page_id_from_post() {
    if (isset($_POST['vf_events_thank_you_page_id'])) {
      return intval($_POST['vf_events_thank_you_page_id']);
    }

    if (isset($_POST['vf_events_delete_thank_you_page'])) {
      return intval($_POST['vf_events_delete_thank_you_page']);
    }

    return 0;
  }

  private function get_posted_action() {
    if (empty($_POST['vf_events_thank_you_action'])) {
      return '';
    }

    return sanitize_key(wp_unslash($_POST['vf_events_thank_you_action']));
  }

  private function delete_thank_you_page_from_post() {
    if ( ! current_user_can('manage_options')) {
      return false;
    }
    if (
      empty($_POST['vf_events_delete_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_delete_thank_you_nonce'])),
        'vf_events_delete_thank_you_page'
      )
    ) {
      return false;
    }

    $thank_you_id = $this->get_delete_thank_you_page_id_from_post();

    if ( ! $thank_you_id || get_post_type($thank_you_id) !== VF_Events::type()) {
      return false;
    }

    $event_id = self::get_parent_event_id($thank_you_id);

    if ( ! self::is_thank_you_url_page($thank_you_id)) {
      return false;
    }

    $deleted = wp_delete_post($thank_you_id, true);

    if ( ! $deleted) {
      return false;
    }

    if ($event_id) {
      self::set_event_include_thank_you_page($event_id, false);
    }

    return true;
  }

  private function delete_all_thank_you_pages_from_settings() {
    if ( ! current_user_can('manage_options')) {
      return 0;
    }
    if (
      empty($_POST['vf_events_delete_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_delete_thank_you_nonce'])),
        'vf_events_delete_thank_you_page'
      )
    ) {
      return 0;
    }
    if (empty($_POST['vf_events_disable_thank_you_confirmed'])) {
      return 0;
    }

    return self::delete_all_thank_you_pages();
  }

  private function maybe_update_default_thank_you_content_from_post() {
    if ( ! isset($_POST['vf_events_default_thank_you_content'])) {
      return;
    }

    $content = wp_kses_post(wp_unslash($_POST['vf_events_default_thank_you_content']));

    if (function_exists('update_field')) {
      update_field('field_vf_events_default_thank_you_content', $content, 'option');
    } else {
      update_option('options_' . self::FIELD_DEFAULT_CONTENT_SETTING, $content, false);
      update_option('_options_' . self::FIELD_DEFAULT_CONTENT_SETTING, 'field_vf_events_default_thank_you_content', false);
    }
  }

  static public function delete_all_thank_you_pages() {
    $thank_you_pages = self::get_existing_thank_you_page_ids();
    $deleted = 0;

    foreach ($thank_you_pages as $thank_you_id) {
      if ( ! self::is_thank_you_url_page($thank_you_id)) {
        continue;
      }

      $event_id = self::get_parent_event_id($thank_you_id);

      if (wp_delete_post($thank_you_id, true)) {
        $deleted++;

        if ($event_id) {
          self::set_event_include_thank_you_page($event_id, false);
        }
      }
    }

    return $deleted;
  }

  private function build_selected_events() {
    $event_ids = $this->get_builder_event_ids_from_post();
    $built = 0;

    foreach ($event_ids as $event_id) {
      if (get_post_type($event_id) !== VF_Events::type()) {
        continue;
      }
      if (get_post_status($event_id) !== 'publish') {
        continue;
      }
      if (self::is_thank_you_page($event_id)) {
        continue;
      }
      if (self::event_has_thank_you_page($event_id)) {
        continue;
      }

      self::set_event_include_thank_you_page($event_id, true);

      if (self::build_for_event($event_id)) {
        $built++;
      }
    }

    return $built;
  }

  private function clear_builder_selection() {
    if (function_exists('update_field')) {
      update_field('field_vf_events_thank_you_selected_events', false, 'option');
    } else {
      update_option('options_' . self::FIELD_BUILDER_EVENTS, false, false);
      update_option('_options_' . self::FIELD_BUILDER_EVENTS, 'field_vf_events_thank_you_selected_events', false);
    }

    if (isset($_POST['acf']['field_vf_events_thank_you_selected_events'])) {
      $_POST['acf']['field_vf_events_thank_you_selected_events'] = false;
    }
  }

  public function acf_save_post($post_id) {
    if ($this->is_settings_save($post_id)) {
      if ($this->get_posted_action() === 'disable') {
        $deleted = $this->delete_all_thank_you_pages_from_settings();
        update_option('vf_events_thank_you_last_delete_all_count', $deleted, false);
        $this->clear_builder_selection();
        return;
      }

      if (
        $this->get_posted_action() === 'delete' ||
        isset($_POST['vf_events_delete_thank_you_page']) ||
        ! empty($_POST['vf_events_thank_you_page_id'])
      ) {
        $deleted = $this->delete_thank_you_page_from_post();
        update_option(
          'vf_events_thank_you_last_delete_status',
          $deleted ? 'deleted' : 'delete_failed',
          false
        );
        return;
      }

      $this->maybe_build_on_settings_save();
      return;
    }

    if (get_post_type($post_id) !== VF_Events::type()) {
      return;
    }

    $post = get_post($post_id);

    if ( ! $post) {
      return;
    }

    $this->maybe_build_on_event_save((int) $post_id, $post, true);
  }

  private function is_settings_save($post_id) {
    if ($post_id === 'options' || $post_id === 'vf-events-settings') {
      return true;
    }

    return (
      is_admin() &&
      isset($_GET['page']) &&
      $_GET['page'] === 'vf-events-settings'
    );
  }

  private function maybe_build_on_settings_save() {
    if (
      $this->get_posted_action() !== 'build' &&
      empty($_POST['vf_events_build_thank_you_pages'])
    ) {
      return;
    }

    if ( ! current_user_can('manage_options') || ! self::is_enabled()) {
      return;
    }
    if (
      empty($_POST['vf_events_thank_you_nonce']) ||
      ! wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['vf_events_thank_you_nonce'])),
        'vf_events_build_thank_you_pages'
      )
    ) {
      return;
    }

    $built = $this->build_selected_events();

    update_option('vf_events_thank_you_last_build_count', $built, false);
    $this->clear_builder_selection();
  }

  public function acf_query_builder_events($args, $field, $post_id) {
    $event_ids = self::get_upcoming_event_ids_for_picker();

    $args['post_status'] = 'publish';
    $args['post__in'] = empty($event_ids) ? array(0) : $event_ids;
    $args['orderby'] = 'post__in';
    $args['vf_events_include_thank_you_pages'] = true;
    unset($args['nopaging']);
    unset($args['meta_key']);
    unset($args['meta_query']);

    return $args;
  }

  public function acf_result_builder_events($title, $post, $field, $post_id) {
    if ( ! $post || empty($post->ID)) {
      return $title;
    }

    if ( ! self::event_has_thank_you_page($post->ID)) {
      return $title;
    }

    return sprintf(
      '%1$s (%2$s)',
      $title,
      __('Already has a thank you page', 'vfwp')
    );
  }

  public function maybe_build_on_event_save($post_id, $post, $update) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
      return;
    }
    if ($post->post_status !== 'publish') {
      return;
    }
    if (self::is_thank_you_page($post_id)) {
      return;
    }
    if ( ! self::is_enabled()) {
      return;
    }
    if ( ! self::event_includes_thank_you_page($post_id)) {
      self::delete_for_event($post_id);
      return;
    }
    if (self::is_event_expired_for_cleanup($post_id)) {
      self::delete_for_event($post_id);
      return;
    }

    self::build_for_event($post_id);
  }

  static public function build_for_event($event_id) {
    $event = get_post($event_id);

    if ( ! $event || $event->post_type !== VF_Events::type()) {
      return false;
    }
    if (self::is_event_expired_for_cleanup($event_id)) {
      return false;
    }

    $existing_id = self::get_thank_you_page_id($event_id);
    $postarr = array(
      'post_type' => VF_Events::type(),
      'post_status' => 'publish',
      'post_parent' => $event_id,
      'post_name' => 'thank-you',
      'post_title' => sprintf(
        _x('%s Thank You', 'generated event thank you page title', 'vfwp'),
        get_the_title($event_id)
      ),
      'post_content' => '',
      'comment_status' => 'closed',
      'ping_status' => 'closed',
      'menu_order' => 999,
    );

    if ($existing_id) {
      $postarr['ID'] = $existing_id;
      $thank_you_id = wp_update_post($postarr, true);
    } else {
      $thank_you_id = wp_insert_post($postarr, true);
    }

    if (is_wp_error($thank_you_id) || ! $thank_you_id) {
      return false;
    }

    update_post_meta($thank_you_id, self::META_IS_THANK_YOU_PAGE, $event_id);

    return (int) $thank_you_id;
  }

  static public function delete_for_event($event_id) {
    $thank_you_id = self::get_thank_you_page_id($event_id);

    if ( ! $thank_you_id) {
      return false;
    }

    return (bool) wp_delete_post($thank_you_id, true);
  }

  static public function get_existing_thank_you_pages() {
    $pages_by_id = array();
    $posts = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
      'vf_events_include_thank_you_pages' => true,
    ));

    foreach ($posts as $post) {
      if (self::is_thank_you_url_page($post->ID)) {
        $pages_by_id[$post->ID] = $post;
      }
    }

    $included_event_ids = self::get_event_ids_with_thank_you_enabled();

    foreach ($included_event_ids as $event_id) {
      $thank_you_id = self::get_thank_you_page_id($event_id);

      if ( ! $thank_you_id && get_post_status($event_id) === 'publish') {
        $thank_you_id = self::build_for_event($event_id);
      }

      if ($thank_you_id) {
        $pages_by_id[$thank_you_id] = get_post($thank_you_id);
      }
    }

    $pages = array_values(array_filter($pages_by_id));

    usort($pages, function($a, $b) {
      $a_event_id = self::get_parent_event_id($a->ID);
      $b_event_id = self::get_parent_event_id($b->ID);
      $a_timestamp = $a_event_id ? self::get_event_start_timestamp($a_event_id) : 0;
      $b_timestamp = $b_event_id ? self::get_event_start_timestamp($b_event_id) : 0;

      if ($a_timestamp === $b_timestamp) {
        return strcasecmp(get_the_title($a_event_id), get_the_title($b_event_id));
      }

      if ( ! $a_timestamp) {
        return 1;
      }

      if ( ! $b_timestamp) {
        return -1;
      }

      return $a_timestamp < $b_timestamp ? -1 : 1;
    });

    return $pages;
  }

  static public function get_existing_thank_you_page_ids() {
    return array_map(function($post) {
      return (int) $post->ID;
    }, self::get_existing_thank_you_pages());
  }

  static private function get_upcoming_event_ids_for_picker() {
    $event_ids = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'fields' => 'ids',
      'orderby' => 'title',
      'order' => 'ASC',
      'vf_events_include_thank_you_pages' => true,
    ));

    $event_ids = array_values(array_filter(array_map('intval', $event_ids), function($event_id) {
      return (
        ! self::is_thank_you_page($event_id) &&
        self::is_event_upcoming_for_picker($event_id)
      );
    }));

    usort($event_ids, function($a, $b) {
      $a_timestamp = self::get_event_start_timestamp($a);
      $b_timestamp = self::get_event_start_timestamp($b);

      if ($a_timestamp === $b_timestamp) {
        return strcasecmp(get_the_title($a), get_the_title($b));
      }

      if ( ! $a_timestamp) {
        return 1;
      }

      if ( ! $b_timestamp) {
        return -1;
      }

      return $a_timestamp < $b_timestamp ? -1 : 1;
    });

    return $event_ids;
  }

  static private function get_available_builder_event_choices() {
    $choices = array();

    foreach (self::get_upcoming_event_ids_for_picker() as $event_id) {
      if (self::event_has_thank_you_page($event_id)) {
        continue;
      }

      $choices[] = array(
        'id' => (string) $event_id,
        'text' => html_entity_decode(get_the_title($event_id), ENT_QUOTES, get_bloginfo('charset')),
      );
    }

    return $choices;
  }

  static private function get_unavailable_builder_event_ids() {
    return array_values(array_map('strval', array_filter(
      self::get_upcoming_event_ids_for_picker(),
      function($event_id) {
        return self::event_has_thank_you_page($event_id);
      }
    )));
  }

  static private function event_has_thank_you_page($event_id) {
    return (bool) self::get_thank_you_page_id($event_id);
  }

  static private function get_event_ids_with_thank_you_enabled() {
    $event_ids = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
      'posts_per_page' => -1,
      'fields' => 'ids',
      'orderby' => 'title',
      'order' => 'ASC',
      'vf_events_include_thank_you_pages' => true,
    ));

    return array_values(array_filter(array_map('intval', $event_ids), function($event_id) {
      return (
        ! self::is_thank_you_page($event_id) &&
        self::event_includes_thank_you_page($event_id)
      );
    }));
  }

  static public function get_thank_you_page_id($event_id) {
    $posts = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => array('publish', 'draft', 'pending', 'private'),
      'post_parent' => $event_id,
      'name' => 'thank-you',
      'posts_per_page' => 1,
      'fields' => 'ids',
      'orderby' => 'ID',
      'order' => 'ASC',
      'vf_events_include_thank_you_pages' => true,
    ));

    return empty($posts) ? 0 : (int) $posts[0];
  }

  static public function get_parent_event_id($post_id = null) {
    $post = get_post($post_id ?: get_the_ID());

    if ( ! $post || $post->post_type !== VF_Events::type()) {
      return 0;
    }

    $event_id = (int) get_post_meta($post->ID, self::META_IS_THANK_YOU_PAGE, true);

    if ($event_id) {
      return $event_id;
    }

    return (int) $post->post_parent;
  }

  static public function get_event_thank_you_url($event_id) {
    return trailingslashit(get_permalink($event_id)) . 'thank-you/';
  }

  static public function get_thank_you_page_url($thank_you_id) {
    $event_id = self::get_parent_event_id($thank_you_id);

    if ($event_id) {
      return self::get_event_thank_you_url($event_id);
    }

    return get_permalink($thank_you_id);
  }

  static public function is_enabled() {
    $enabled = null;

    if (function_exists('get_field')) {
      $enabled = get_field(self::FIELD_ENABLE_SETTING, 'option');
    }

    if ($enabled === null) {
      $enabled = get_option('options_' . self::FIELD_ENABLE_SETTING);
    }

    return (bool) $enabled;
  }

  static public function set_enabled($enabled) {
    $value = $enabled ? 1 : 0;

    if (function_exists('update_field')) {
      update_field('field_vf_events_enable_thank_you_pages', $value, 'option');
    } else {
      update_option('options_' . self::FIELD_ENABLE_SETTING, $value, false);
      update_option('_options_' . self::FIELD_ENABLE_SETTING, 'field_vf_events_enable_thank_you_pages', false);
    }
  }

  static public function get_event_thank_you_content($event_id) {
    $content = function_exists('get_field')
      ? get_field(self::FIELD_CONTENT_EVENT, $event_id)
      : get_post_meta($event_id, self::FIELD_CONTENT_EVENT, true);

    if (self::has_rich_text_content($content)) {
      return $content;
    }

    return self::get_default_thank_you_content();
  }

  static public function get_default_thank_you_content() {
    $content = null;

    if (function_exists('get_field')) {
      $content = get_field(self::FIELD_DEFAULT_CONTENT_SETTING, 'option');
    }

    if ($content === null) {
      $content = get_option('options_' . self::FIELD_DEFAULT_CONTENT_SETTING);
    }

    return $content;
  }

  static private function has_rich_text_content($content) {
    if ( ! is_string($content) || trim($content) === '') {
      return false;
    }

    if (trim(wp_strip_all_tags($content)) !== '') {
      return true;
    }

    return (bool) preg_match('/<(img|iframe|video|audio|table|ul|ol|blockquote)\b/i', $content);
  }

  static public function event_includes_thank_you_page($event_id) {
    $include = null;

    if (function_exists('get_field')) {
      $include = get_field(self::FIELD_INCLUDE_EVENT, $event_id);
    }

    if ($include === null) {
      $include = get_post_meta($event_id, self::FIELD_INCLUDE_EVENT, true);
    }

    return (bool) $include;
  }

  static public function set_event_include_thank_you_page($event_id, $include) {
    $value = $include ? 1 : 0;

    if (function_exists('update_field')) {
      update_field('field_vf_event_include_thank_you_page', $value, $event_id);
    } else {
      update_post_meta($event_id, self::FIELD_INCLUDE_EVENT, $value);
      update_post_meta($event_id, '_' . self::FIELD_INCLUDE_EVENT, 'field_vf_event_include_thank_you_page');
    }
  }

  static public function is_thank_you_page($post_id = null) {
    $post_id = $post_id ?: get_the_ID();

    if ( ! $post_id || get_post_type($post_id) !== VF_Events::type()) {
      return false;
    }

    return (bool) get_post_meta($post_id, self::META_IS_THANK_YOU_PAGE, true);
  }

  static public function is_thank_you_url_page($post_id) {
    $post = get_post($post_id);

    if ( ! $post || $post->post_type !== VF_Events::type()) {
      return false;
    }

    if (self::is_thank_you_page($post->ID)) {
      return true;
    }

    if ((int) $post->post_parent > 0) {
      if (strpos($post->post_name, 'thank-you') === 0) {
        return true;
      }

      $permalink = get_permalink($post);

      return (
        is_string($permalink) &&
        preg_match('#/thank-you/?$#', $permalink)
      );
    }

    return false;
  }

  public function maybe_schedule_cleanup() {
    self::maybe_schedule_cleanup_event();
  }

  static public function maybe_schedule_cleanup_event() {
    if (wp_next_scheduled(self::CLEANUP_HOOK)) {
      return;
    }

    wp_schedule_event(
      time() + HOUR_IN_SECONDS,
      'daily',
      self::CLEANUP_HOOK
    );
  }

  static public function clear_cleanup_schedule() {
    $timestamp = wp_next_scheduled(self::CLEANUP_HOOK);

    if ($timestamp) {
      wp_unschedule_event($timestamp, self::CLEANUP_HOOK);
    }
  }

  public function cleanup_expired_thank_you_pages() {
    $thank_you_pages = get_posts(array(
      'post_type' => VF_Events::type(),
      'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
      'posts_per_page' => -1,
      'fields' => 'ids',
      'vf_events_include_thank_you_pages' => true,
      'meta_query' => array(
        array(
          'key' => self::META_IS_THANK_YOU_PAGE,
          'compare' => 'EXISTS',
        ),
      ),
    ));

    foreach ($thank_you_pages as $thank_you_id) {
      $event_id = self::get_parent_event_id($thank_you_id);

      if ( ! $event_id || get_post_type($event_id) !== VF_Events::type()) {
        wp_delete_post($thank_you_id, true);
        continue;
      }

      if (self::is_event_expired_for_cleanup($event_id)) {
        wp_delete_post($thank_you_id, true);
      }
    }
  }

  static public function is_event_expired_for_cleanup($event_id) {
    $event_timestamp = self::get_event_end_timestamp($event_id);

    if ( ! $event_timestamp) {
      return false;
    }

    return (current_time('timestamp') > $event_timestamp + (2 * DAY_IN_SECONDS));
  }

  static private function is_event_upcoming_for_picker($event_id) {
    $today = strtotime('today', current_time('timestamp'));
    $start_timestamp = self::get_event_start_timestamp($event_id);
    $end_timestamp = self::get_event_end_timestamp($event_id);

    if ($end_timestamp) {
      return $end_timestamp >= $today;
    }

    return $start_timestamp ? $start_timestamp >= $today : false;
  }

  static private function get_event_start_timestamp($event_id) {
    $date = get_post_meta($event_id, 'vf_event_start_date', true);

    if (empty($date) && function_exists('get_field')) {
      $date = get_field('vf_event_start_date', $event_id);
    }

    return self::parse_event_date_timestamp($date);
  }

  static private function get_event_end_timestamp($event_id) {
    $date = get_post_meta($event_id, 'vf_event_end_date', true);

    if (empty($date)) {
      $date = get_post_meta($event_id, 'vf_event_start_date', true);
    }

    if (empty($date)) {
      return 0;
    }

    $timestamp = self::parse_event_date_timestamp($date);

    if ( ! $timestamp && function_exists('get_field')) {
      $field_date = get_field('vf_event_end_date', $event_id);

      if (empty($field_date)) {
        $field_date = get_field('vf_event_start_date', $event_id);
      }

      $timestamp = self::parse_event_date_timestamp($field_date);
    }

    if ( ! $timestamp) {
      return 0;
    }

    return strtotime('23:59:59', $timestamp);
  }

  static private function parse_event_date_timestamp($date) {
    if (empty($date)) {
      return 0;
    }

    $date = trim((string) $date);
    $timezone = function_exists('wp_timezone') ? wp_timezone() : new DateTimeZone('UTC');
    $formats = array('Ymd', 'Y-m-d', 'j M Y', 'j F Y');

    foreach ($formats as $format) {
      $datetime = DateTime::createFromFormat($format, $date, $timezone);

      if ($datetime instanceof DateTime) {
        return $datetime->getTimestamp();
      }
    }

    $timestamp = strtotime($date);

    return $timestamp ? $timestamp : 0;
  }

  public function pre_get_posts($query) {
    if ( ! $query instanceof WP_Query) {
      return;
    }
    if ($query->get('vf_events_include_thank_you_pages')) {
      return;
    }

    $post_type = $query->get('post_type');
    $is_event_query = (
      $post_type === VF_Events::type() ||
      (is_array($post_type) && in_array(VF_Events::type(), $post_type, true)) ||
      VF_Events::is_query_events($query)
    );

    if ( ! $is_event_query && ! $query->is_search()) {
      return;
    }
    if ($query->is_singular()) {
      return;
    }

    if (is_admin()) {
      if ( ! $query->is_main_query() || $post_type !== VF_Events::type()) {
        return;
      }
    }

    $meta_query = (array) $query->get('meta_query');
    $meta_query[] = array(
      'key' => self::META_IS_THANK_YOU_PAGE,
      'compare' => 'NOT EXISTS',
    );
    $query->set('meta_query', $meta_query);
  }

  public function posts_columns($columns) {
    $offset = array_search('date', array_keys($columns), true);

    if ($offset === false) {
      $columns['vf_event_thank_you_page'] = __('Thank you page', 'vfwp');
      return $columns;
    }

    return array_merge(
      array_slice($columns, 0, $offset),
      array('vf_event_thank_you_page' => __('Thank you page', 'vfwp')),
      array_slice($columns, $offset)
    );
  }

  public function posts_custom_column($column, $post_id) {
    if ($column !== 'vf_event_thank_you_page') {
      return;
    }

    $thank_you_id = self::get_thank_you_page_id($post_id);

    if (
      ! $thank_you_id &&
      get_post_status($post_id) === 'publish' &&
      self::event_includes_thank_you_page($post_id)
    ) {
      $thank_you_id = self::build_for_event($post_id);
    }

    if ( ! $thank_you_id) {
      echo '&mdash;';
      return;
    }

    printf(
      '<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
      esc_url(self::get_event_thank_you_url($post_id)),
      esc_html__('View', 'vfwp')
    );
  }

  public function wp_robots($robots) {
    if (self::is_thank_you_page()) {
      $robots['noindex'] = true;
      $robots['nofollow'] = true;
    }

    return $robots;
  }

  public function wp_head() {
    if ( ! self::is_thank_you_page()) {
      return;
    }

    echo '<meta name="robots" content="noindex,nofollow">' . "\n";
  }
}

endif;

?>
