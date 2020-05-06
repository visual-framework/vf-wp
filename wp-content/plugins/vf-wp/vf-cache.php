<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Cache') ) :

/**
 * Cache post type for contentHub API results
 */
class VF_Cache {

  // Default max page for cached content
  const MAX_AGE = 60 * 5;

  // How often to schedule a cache update check
  const REFRESH_RATE = 60 * 1;

  private $post_type = 'vf_cache';
  private $post_type_plural = 'vf_caches';
  private $roles = array('administrator', 'editor', 'author');

  /**
   * Global store to save cached posts from the database
   */
  private $store = array();

  public function __construct() {
    // Nothing
  }

  /**
   * Generate hash / checksum for value
   */
  static public function hash($value) {
    return hash('crc32', $value);
  }

  /**
   * Return the EMBL Content Hub API URL from ACF options page
   * without trailing slash
   */
  static public function get_api_url() {
    $url = get_field('vf_api_url', 'option');
    return rtrim(trim($url), '/\\');
  }

  /**
   * Return true if the cache is disabled
   */
  static public function is_disabled() {
    $is_disabled = get_field('vf_cache_disabled', 'option');
    return in_array($is_disabled, array(true, 1));
  }

  /**
   * Return the post modified age in seconds
   */
  static public function get_post_age($post) {
    if ($post instanceof WP_Post) {
      return time() - mysql2date('U', $post->post_modified, false);
    }
    return 0;
  }

  /**
   * Return fallback HTML (used by client-side JavaScript)
   */
  static public function fetch_fallback($url) {
    $html = '<link rel="import" href="';
    $html .= $url;
    $html .= '" data-target="self" data-embl-js-content-hub-loader>';
    return $html;
  }

  /**
   * Return HTML content for URL
   * Plugins should use `VF_Cache::fetch()`
   */
  static public function fetch_remote($url)  {
    // A more reliable way to fetch remote HTML derrived from:
    // https://www.experts-exchange.com/questions/26187506/Function-file-get-contents-connection-time-out.html
    $curl = curl_init();

    // Some requests turning "Bad request" error without headers
    $headers = array(
      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Cache-Control: no-cache',
      'Connection: keep-alive',
      'Pragma: no-cache'
    );

    // http://php.net/manual/en/function.curl-setopt.php
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_URL,            $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);
    curl_setopt($curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (compatible; EMBL VF WP; http://content.embl.org/user-agent)');
    curl_setopt($curl, CURLOPT_REFERER,        'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    curl_setopt($curl, CURLOPT_ENCODING,       'gzip,deflate');
    curl_setopt($curl, CURLOPT_AUTOREFERER,    TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_TIMEOUT,        2);
    $html = curl_exec($curl);
    $http_code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_errno($curl);
    curl_close($curl);

    // Return CURL error number or HTML
    if ($err) {
      return $err;
    }

    // Return HTTP code as error number
    if ( ! in_array($http_code, array(200, 302))) {
      return $http_code;
    }

    return $html;
  }

  /**
   * Return HTML content for URL via this cache
   */
  static public function fetch(string $url, $max_age = VF_Cache::MAX_AGE) {
    global $vf_cache;
    if ( ! $vf_cache instanceof VF_Cache) {
      trigger_error('Global variable $vf_cache is not instance of VF_Cache');
      return;
    }
    $url = esc_url_raw($url);
    if (empty($url)) {
      return;
    }

    // Bypass the cache if disabled
    if (VF_Cache::is_disabled()) {
      // Fetch new content
      $html = VF_Cache::fetch_remote($url);
      // Check error status
      $error = is_numeric($html) ? $html : 0;
      if ($html === $error || vf_html_empty($html)) {
        return '';
      }
      return $html;
    }

    /**
     * Look for cached content from hashed URL
     * The hash is used as `post_name` for `vf_cache` post type
     */
    $key = VF_Cache::hash($url);

    // Return from global store if already retrieved
    if ($vf_cache->has($key)) {
      $html = $vf_cache->get($key);
      return $html;
    }

    // Retrieve cached content from database
    $value = '';
    $hit = false;
    $age = PHP_INT_MAX;
    $cache_post = get_page_by_path($key, OBJECT, 'vf_cache');
    if ($cache_post instanceof WP_Post) {
      $value = $cache_post->post_content;
      $age = VF_Cache::get_post_age($cache_post);
      $hit = true;
    }

    // Save to global store
    $vf_cache->set(
      $key,
      $value,
      array(
        'url'     => $url,
        'hit'     => $hit,
        'age'     => $age,
        'max_age' => $max_age
      )
    );

    return $vf_cache->get($key);
  }

  /**
   * Return true if store has key
   */
  public function has($key) {
    return array_key_exists($key, $this->store);
  }

  /**
   * Set value for key
   */
  public function set($key, $value, $meta) {
    $data = array(
      'key'   => $key,
      'value' => $value
    );
    if (is_array($meta)) {
      $data = array_merge($meta, $data);
    }
    $this->store[$key] = $data;
    // force an update for initial miss
    if (isset($data['hit']) && $data['hit'] === false) {
      $this->update_cache_posts($key);
    }
  }

  /**
   * Return value for key
   */
  public function get($key) {
    if ($this->has($key)) {
      return $this->store[$key]['value'];
    }
  }

  /**
   * Check if a cache update needs to be scheduled and if so
   * execute it via a client-side AJAX request
   */
  private function maybe_schedule_ajax() {
    // avoid recursion
    if (wp_doing_ajax()) {
      return;
    }
    // check if a cache update is due
    $now = time();
    $last = intval(
      get_option('vf_cache_update')
    );
    if ($now - $last < VF_Cache::REFRESH_RATE) {
      return;
    }
    // generate nonce for ajax response
    $nonce = wp_create_nonce("vf_nonce_{$now}");
    update_option('vf_cache_update', $now);

    /**
     * Send AJAX request via front-end JavaScript
     * we could trigger a cache udate server-side with: `wp_remote_post`
     * but this seems to throw CURL errors or block loading (?)
     */
    $url = admin_url('admin-ajax.php');
    $data = array(
      'action' => 'vf/cache/update',
      'nonce' => $nonce
    );

    // Footer action callback
    $script = function() use ($url, $data) {
?>
<script>
(function() {
var xhr = new XMLHttpRequest();
xhr.open('POST', '<?php echo $url; ?>');
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
<?php if (vf_debug()) { ?>
xhr.addEventListener('load', function() {
  console.log(xhr);
});
<?php } ?>
xhr.send('<?php echo build_query($data); ?>');
})();
</script>
<?php
    };
    // Output JavaScript to footer
    add_action('admin_footer', $script, 100);
    add_action('wp_footer', $script, 100);
  }

  /**
   * Handle the scheduled AJAX update; hook called by the `wp_ajax_`
   * action from 'admin-ajax.php' endpoint
   */
  public function handle_schedule_ajax() {
    ignore_user_abort(true);
    session_write_close();

    // Verify nonce to avoid unscheduled updates
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    $update = get_option('vf_cache_update');

    if ( ! wp_verify_nonce($nonce, "vf_nonce_{$update}")) {
      wp_send_json_error(__('Invalid nonce', 'vfwp'));
    }

    // Update cache
    update_option('vf_cache_update', time());

    // Skip update if cache is disabled
    if (VF_Cache::is_disabled()) {
      wp_send_json_error(__('Cache disabled', 'vfwp'));
    }

    $this->update_cache_posts();

    wp_send_json_success();
  }

  /**
   * Update cached posts by requesting new HTML
   * Specify `$key` to update single post
   */
  private function update_cache_posts($key = false) {
    $store = array();
    if ($key) {
      if ($this->has($key)) {
        $store[$key] = $this->store[$key];
      }
    } else {
      $query = new WP_Query(
        array(
          'post_type'      => 'vf_cache',
          'posts_per_page' => -1
        )
      );
      if ($query->have_posts()) {
        foreach ($query->posts as $post) {
          $store[$post->post_name] = array(
            'url'     => get_post_meta($post->ID, 'vf_cache_url', true),
            'age'     => VF_Cache::get_post_age($post),
            'max_age' => get_post_meta($post->ID, 'vf_cache_max_age', true),
            'post'    => $post
          );
        }
      }
    }
    if (empty($store)) {
      return;
    }
    foreach ($store as $key => $data) {

      if ($data['age'] < $data['max_age']) {
        continue;
      }

      // Retrieve the cache post
      if (isset($data['post'])) {
        $cache_post = $data['post'];
      } else {
        $cache_post = get_page_by_path($key, OBJECT, 'vf_cache');
      }

      // Add new cache post before fetching to avoid duplicates if
      // parallel updates are somehow triggered
      if ( ! $cache_post instanceof WP_Post) {
        $cache_post = get_post(
            wp_insert_post(array(
            'post_author'  => 1,
            'post_name'    => $key,
            'post_title'   => basename(urldecode($data['url'])),
            'post_type'    => 'vf_cache',
            'post_status'  => 'publish',
            'post_content' => '',
          ), true)
        );
      }

      // Something has gone very wrong...
      if ( ! $cache_post instanceof WP_Post) {
        continue;
      }

      // Ensure meta data is set
      update_post_meta(
        $cache_post->ID,
        'vf_cache_max_age',
        $data['max_age']
      );
      update_post_meta(
        $cache_post->ID,
        'vf_cache_url',
        $data['url']
      );

      // Fetch new content
      $html = VF_Cache::fetch_remote($data['url']);

      // Update error status
      $error = is_numeric($html) ? $html : 0;
      update_post_meta($cache_post->ID, 'vf_cache_error', $error);

      // Keep old content for now
      if ($error && ! vf_html_empty($cache_post->post_content)) {
        continue;
      }

      // Fallback to Content Hub loader
      if ($html === $error || vf_html_empty($html)) {
        $html = VF_Cache::fetch_fallback($data['url']);
      }

      // update local store and database
      $this->store[$key]['value'] = $html;
      wp_update_post(array(
        'ID'           => $cache_post->ID,
        'post_content' => $html
      ));
    }
  }

  /**
   * Initial setup once per load
   */
  public function initialize() {

    add_action('init', array($this, 'init'));
    add_action('acf/init', array($this, 'acf_init'));

    add_action(
      'wp_ajax_vf/cache/update',
      array($this, 'handle_schedule_ajax')
    );
    add_action(
      'wp_ajax_nopriv_vf/cache/update',
      array($this, 'handle_schedule_ajax')
    );

    if (is_admin()) {
      add_action(
        'admin_notices',
        array($this, 'admin_notices')
      );
      add_filter(
        'manage_' . $this->post_type . '_posts_columns',
        array($this, 'manage_vf_cache_posts_columns'),
        10, 1
      );
      add_action(
        'manage_' . $this->post_type . '_posts_custom_column',
        array($this, 'manage_vf_cache_posts_custom_column'),
        10, 2
      );
      add_filter(
        'manage_edit-' . $this->post_type . '_sortable_columns',
        array($this, 'manage_edit_vf_cache_sortable_columns'),
        10, 1
      );
      add_filter(
        'post_row_actions',
        array($this, 'post_row_actions'),
        10, 2
      );
      add_filter('request', array($this, 'request'));
      add_filter('pre_get_posts', array($this, 'pre_get_posts'));
      add_filter('user_can_richedit', array($this, 'user_can_richedit'));
    }
  }

  /**
   * Action: plugin activation
   */
  public function activate() {
    // Setup post type capabilities for caontainers
    foreach ($this->roles as $role) {
      $role = get_role($role);
      if ($role) {
        $role->add_cap('edit_' . $this->post_type_plural);
        $role->add_cap('edit_' . $this->post_type);
        $role->add_cap('delete_' . $this->post_type_plural);
        $role->add_cap('delete_' . $this->post_type);
      }
    }
  }

  /**
   * Action: plugin deactivation
   */
  public function deactivate() {
    // Tidy up database by removing all capabilities
    foreach ($this->roles as $role) {
      $role = get_role($role);
      if ($role) {
        $role->remove_cap('edit_' . $this->post_type_plural);
        $role->remove_cap('edit_' . $this->post_type);
        $role->remove_cap('delete_' . $this->post_type_plural);
        $role->remove_cap('delete_' . $this->post_type);
      }
    }
  }

  /**
   * Action: `init`
   * Register custom post type
   */
  public function init() {
    // check if a cache update can be scheduled
    $this->maybe_schedule_ajax();

    register_post_type($this->post_type, array(
      'labels' => array(
        'name'          => 'Cache',
        'singular_name' => 'Cache',
        'edit_item'     => 'Edit Cache'
      ),
      'description'     => 'Cache',
      'capability_type' => $this->post_type,
      'capabilities'    => array(
        'edit_post'   => 'edit_' . $this->post_type,
        'read_post'   => 'read_' . $this->post_type,
        'delete_post' => 'delete_' . $this->post_type,

        'edit_posts'         => 'edit_' . $this->post_type_plural,
        'edit_others_posts'  => 'edit_others_' . $this->post_type_plural,
        'publish_posts'      => 'publish_' . $this->post_type_plural,
        'read_private_posts' => 'read_private_' . $this->post_type_plural,

        'delete_posts'           => 'delete_' . $this->post_type_plural,
        'delete_private_posts'   => 'delete_private_' . $this->post_type_plural,
        'delete_published_posts' => 'delete_published_' . $this->post_type_plural,
        'delete_others_posts'    => 'delete_others_' . $this->post_type_plural,
        'edit_private_posts'     => 'edit_private_' . $this->post_type_plural,
        'edit_published_posts'   => 'edit_published_' . $this->post_type_plural,
        'create_posts'           => 'create_' . $this->post_type_plural
      ),
      'map_meta_cap'      => false,
      'public'            => false,
      'show_ui'           => true,
      'show_in_admin_bar' => false,
      'show_in_menu'      => 'vf-settings',
      'supports'          => array('title', 'editor'),
      'rewrite'           => false,
      'query_var'         => false,
      'can_export'        => false
    ));
  }

  /**
   * Action `acf/init`
   * Add field for Content Hub API URL
   */
  public function acf_init() {
    acf_add_local_field_group(array(
      'key' => 'group_5eb28cf840399',
      'title' => __('Cache Settings', 'vfwp'),
      'fields' => array(
        array(
          'key' => 'field_vf_api_url',
          'label' => __('EMBL Content Hub', 'vfwp'),
          'name' => 'vf_api_url',
          'type' => 'url',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
        ),
        array(
          'key' => 'field_vf_cache_disabled',
          'label' => __('Disable Cache', 'vfwp'),
          'name' => 'vf_cache_disabled',
          'type' => 'true_false',
          'instructions' => __('Disable the Content Hub cache. This is not advised for live websites.', 'vfwp'),
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'message' => '',
          'default_value' => 0,
          'ui' => 1,
          'ui_on_text' => '',
          'ui_off_text' => '',
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'vf-settings',
          ),
        ),
      ),
      'menu_order' => 100,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
    ));
  }

  /**
   * Filter: `manage_vf_cache_posts_columns`
   * Add "Hash" and "Modified" columns before "Title" in Admin table
   */
  public function manage_vf_cache_posts_columns($columns) {
    $offset = array_search('title', array_keys($columns));
    $columns = array_merge(
      array_slice($columns, 0, $offset),
      array(
        'vf_hash'     => __('Hash', 'vfwp'),
        'vf_modified' => __('Modified', 'vfwp')
      ),
      array_slice($columns, $offset)
    );
    return $columns;
  }

  /**
   * Action: `manage_vf_cache_posts_custom_column`
   * Output "Hash" and "Modified" column values in Admin table
   */
  public function manage_vf_cache_posts_custom_column($column, $post_id) {
    if ($column === 'vf_hash') {
      echo '<code>', esc_html(get_post_field('post_name', $post_id)) , '</code>';
    } else if ($column === 'vf_modified') {

      // show error icon
      $error = (int) get_post_meta($post_id, 'vf_cache_error', true);
      if ($error === 0) {
        echo '<span class="dashicons dashicons-yes"></span>';
      } else {
        echo '<span class="dashicons dashicons-no-alt" style="color:#a00;"></span>';
      }

      // based on `class-wp-posts-list-table.php`
      $now = current_time('timestamp');
      $t_time = get_the_modified_date(__('Y/m/d g:i:sa'), $post_id);
      if ($error) {
        $t_time .= ' (' . esc_attr($error) . ')';
      }
      $h_time = sprintf(
        __('%s ago'),
        human_time_diff(
          get_the_modified_date('U', $post_id),
          $now
        )
      );
      echo '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';

      $max_age = get_post_meta($post_id, 'vf_cache_max_age', true);
      if (is_numeric($max_age)) {
        echo '<br><small>' . sprintf(
          __('Rate: %s'),
          human_time_diff($now + $max_age, $now)
        ) . '</small>';
      }
    }
  }

  /**
   * Filter: `manage_edit-vf_cache_sortable_columns`
   */
  public function manage_edit_vf_cache_sortable_columns($columns) {
    $columns['vf_modified'] = 'vf_modified';
    return $columns;
  }

  /**
   * Filter: 'post_row_actions'
   */
  public function post_row_actions($actions, $post) {
    if ($post->post_type !== $this->post_type) {
      return $actions;
    }
    // Hide unnecessary actions
    unset($actions['inline hide-if-no-js']);
    unset($actions['view']);
    // Add view link to open content hub response
    $url = get_post_meta($post->ID, 'vf_cache_url', true);
    $actions['view'] = sprintf(
      '<a href="%1$s" target="_blank">%2$s</a>',
      esc_attr($url),
      __('View', 'vfwp')
    );
    return $actions;
  }

  /**
   * Filter: `request`
   * Order the "Modified" column in the Admin table
   */
  public function request($vars) {
    $screen = get_current_screen();
    if ($screen->id === 'edit-vf_cache') {
      if (array_key_exists('orderby', $vars) && $vars['orderby'] === 'vf_modified') {
        $vars = array_merge($vars, array('orderby' => 'post_modified'));
      }
    }
    return $vars;
  }

  /**
   * Filter: `pre_get_posts`
   * Set the default post order in the Admin table
   */
  public function pre_get_posts($query) {
    if ( ! $query->is_main_query() || $query->get('post_type') !== $this->post_type) {
      return;
    }
    if ( ! $query->get('orderby')) {
      $query->set('orderby', 'post_modified');
      $query->set('order', 'desc');
      $_GET['orderby'] = 'vf_modified';
      $_GET['order'] = 'desc';
    }
  }

  /**
   * Filter: `user_can_richedit`
   * Hide the WYSIWYG editor for cache content
   */
  function user_can_richedit($default) {
    if (get_post_type() === 'vf_cache') return false;
    return $default;
  }

  /**
   * Add admin notice when cache is disabled
   */
  public function admin_notices() {
    if ( ! function_exists('get_current_screen')) {
      return;
    }
    $screen = get_current_screen();
    if ($screen->id !== 'edit-vf_cache') {
      return;
    }

    if (VF_Cache::is_disabled()) {
      printf('<div class="%1$s"><p>%2$s %3$s</p></div>',
        esc_attr('notice notice-error'),
        esc_html__('The Content Hub cache is currently disabled', 'vfwp'),
        ' <a href="'
          . admin_url('admin.php?page=vf-settings')
          . '" class="button button-small">'
          . esc_html__('View Settings', 'vfwp')
          . '</a>'
      );
    }
  }

} // VF_Cache

endif;

?>
