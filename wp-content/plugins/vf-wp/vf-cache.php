<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Cache') ) :

/**
 * Cache post type for contentHub API results
 */
class VF_Cache {

  private $post_type = 'vf_cache';
  private $post_type_plural = 'vf_caches';
  private $roles = array('administrator', 'editor', 'author');

  // Global store
  private $store = array();

  // How often to schedule a cache update check
  const REFRESH_RATE = 60;

  const MAX_AGE = 300; // 60 * 11;

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
   * A more reliable way to fetch remote HTML
   * Derrived from https://www.experts-exchange.com/questions/26187506/Function-file-get-contents-connection-time-out.html
   */
  static public function vf_curl($url, $timeout=2, $error_report=FALSE)  {
    $curl = curl_init();

    // I don't think we need these, but leeving commented in case...
    $header[] = "";
    // $header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    // $header[] = "Cache-Control: max-age=0";
    // $header[] = "Connection: keep-alive";
    // $header[] = "Keep-Alive: 300";
    // $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    // $header[] = "Accept-Language: en-us,en;q=0.5";
    // $header[] = "Pragma: "; // browsers keep this blank.

    // Disable SSL as PHP does not come with a CA certificate bundle any more
    // and you have to download this manually and set a reference to it in php.ini:
    // https://wehavefaces.net/fixing-ssl-certificate-problem-when-performing-https-requests-in-php-e3b2bb5c58f6
    // and that's not a great option for hosting as a service
    // So we disable the ssl verification: https://www.drupal.org/project/uc_linkpoint_api/issues/1081534:
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    // Curl options
    // http://php.net/manual/en/function.curl-setopt.php
    curl_setopt($curl, CURLOPT_URL,            $url);
    curl_setopt($curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (compatible; EMBL VF WP; http://content.embl.org/user-agent)');
    // curl_setopt($curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6');
    curl_setopt($curl, CURLOPT_HTTPHEADER,     $header);
    curl_setopt($curl, CURLOPT_REFERER,        'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    curl_setopt($curl, CURLOPT_ENCODING,       'gzip,deflate');
    curl_setopt($curl, CURLOPT_AUTOREFERER,    TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_TIMEOUT,        $timeout);

    // Bootstrap
    $htm = curl_exec($curl);
    $err = curl_errno($curl);
    $inf = curl_getinfo($curl);
    curl_close($curl);

    // ON FAILURE
    // if (!$htm) {
    //     // PROCESS ERRORS HERE
    //     if ($error_report) {
    //         echo "CURL FAIL: $url TIMEOUT=$timeout, CURL_ERRNO=$err";
    //         var_dump($inf);
    //     }
    //     return FALSE;
    // }

    return $htm;
  }

  /**
   * Retrieve HTML contents from cache by URL hash
   */
  static public function fetch(string $url) {
    global $vf_cache;
    if ( ! $vf_cache instanceof VF_Cache) {
      return;
    }

    if (empty($url)) {
      return;
    }

    $url = add_query_arg(array(
      'source' => 'contenthub'
    ), $url);

    // Look for cached content from hashed URL
    $key = VF_Cache::hash($url);

    // Return from global store if already retrieved from database
    if ($vf_cache->has($key)) {
      $html = $vf_cache->get($key);
      if (vf_debug()) {
        $html .= "\n<!--/vf:cache:store-->\n";
      }
      return $html;
    }

    // The hash is used as `post_name` for `vf_cache` post type
    $cache_post = get_page_by_path($key, OBJECT, 'vf_cache');

    $hit = $cache_post instanceof WP_Post;

    // Cached content age in seconds (zero means not cached)
    $age = PHP_INT_MAX;
    if ($hit) {
      $age = time() - mysql2date('U', $cache_post->post_modified, false);
    }

    // Save to global store
    $vf_cache->set(
      $key,
      $cache_post ? $cache_post->post_content : '',
      array(
        'url' => $url,
        'age' => $age,
        'hit' => $hit
      )
    );

    return $vf_cache->get($key);
  }

  /**
   * Return true if data exists for key
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
      $this->update($key);
    }
  }

  /**
   * Return value for key
   */
  public function get($key) {
    if ($this->has($key)) {
      $data = $this->store[$key];
      return $data['value'];
    }
  }

  /**
   * Request cached URL (single `$key` or all cached posts)
   */
  private function update($key = false) {
    $update_store = array();
    if ($key) {
      if ($this->has($key)) {
        $update_store[$key] = $this->store[$key];
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
          $update_store[$post->post_name] = array(
            'url' => $post->post_title
          );
        }
      }
    }
    if (empty($update_store)) {
      return;
    }
    foreach ($update_store as $key => $data) {
      $html = VF_Cache::vf_curl($data['url']);
      if (vf_html_empty($html)) {
        $html = '<link rel="import" href="';
        $html .= $data['url'];
        $html .= '" data-target="self" data-embl-js-content-hub-loader>';
      }
      // update local store
      $this->store[$key]['value'] = $html;

      if (vf_debug()) {
        $this->store[$key]['value'] .= "\n<!--/vf:cache:miss-->\n";
      }

      // save to database
      $cache_post = get_page_by_path($key, OBJECT, 'vf_cache');
      if ($cache_post instanceof WP_Post) {
        wp_update_post(array(
          'ID'           => $cache_post->ID,
          'post_content' => $html
        ));
      } else {
        $cache_post = get_post(
            wp_insert_post(array(
            'post_author'  => 1,
            'post_name'    => $key,
            'post_title'   => $data['url'],
            'post_type'    => 'vf_cache',
            'post_status'  => 'publish',
            'post_content' => $html,
          ), true)
        );
      }
    }
  }

  /**
   * Check if a cache update needs to be scheduled and if so execute
   * with a client-side AJAX request
   */
  private function schedule_update() {
    // avoid recursion
    if (wp_doing_ajax()) {
      return;
    }
    // check if a cache update is due
    $now = time();
    $last = get_option('vf_cache_update');
    $last = intval($last);
    if ($now - $last < VF_Cache::REFRESH_RATE) {
      return;
    }
    // generate nonce so ajax response only handles one update
    $nonce = VF_Cache::hash(rand() . $now);
    update_option('vf_cache_nonce', $nonce);
    update_option('vf_cache_update', $now);

    /**
     * Send AJAX request via front-end JavaScript
     * we could trigger a cache udate server-side with: `wp_remote_post`
     * but this seems to throw CURL errors
     */
    $url = admin_url('admin-ajax.php');
    $url = add_query_arg(array(
      'action'   => 'vf_cache_update',
      '_wpnonce' => $nonce
    ), $url);

    // Action callback
    $script = function() use ($url) {
?>
<script>
(function() {
var xhr = new XMLHttpRequest();
xhr.open('POST', <?php echo json_encode($url); ?>);
<?php if (vf_debug()) { ?>
xhr.addEventListener('load', function() {
  console.log(xhr);
});
<?php } ?>
xhr.send();
})();
</script>
<?php
    };
    // write JavaScript to footer
    add_action('admin_footer', $script, 100);
    add_action('wp_footer', $script, 100);
  }

  /**
   * Handle the scheduled AJAX update
   */
  public function handle_update() {
    ignore_user_abort(true);
    session_write_close();
    // Verify nonce
    $nonce = isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : false;
    $check = get_option('vf_cache_nonce');
    if ( ! $nonce || ! $check || $nonce !== $check) {
      wp_die(vf_debug() ? '0' : '');
    }
    // Reset for schedule next update
    update_option('vf_cache_nonce', 0);
    update_option('vf_cache_update', time());
    $this->update();
    wp_die(vf_debug() ? '1' : '');
  }

  /**
   * Initial setup once per load
   */
  public function initialize() {

    add_action('init', array($this, 'init'));
    add_action('acf/init', array($this, 'acf_init'));

    add_action(
      'wp_ajax_vf_cache_update',
      array($this, 'handle_update')
    );

    add_action(
      'wp_ajax_nopriv_vf_cache_update',
      array($this, 'handle_update')
    );

    $this->schedule_update();

    if (is_admin()) {
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
    register_post_type($this->post_type, array(
      'labels' => array(
        'name'          => 'VF Cache',
        'singular_name' => 'Cache',
        'edit_item'     => 'Edit Cache'
      ),
      'description'     => 'VF Cache',
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
    acf_add_local_field(
      array(
        'parent' => 'group_embl_setting',
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
      )
    );
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
      echo get_the_modified_date('Y-m-d H:i:s', get_post($post_id));
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
    $actions['view'] = sprintf(
      '<a href="%1$s" target="_blank">%2$s</a>',
      esc_attr($post->post_title),
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

} // VF_Cache

endif;

?>
