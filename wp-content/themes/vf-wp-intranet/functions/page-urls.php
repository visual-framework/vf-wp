<?php

add_action('admin_menu', 'vfwp_register_page_urls_settings_page');

function vfwp_register_page_urls_settings_page() {
  add_options_page(
    'URL Explorer',
    'URL Explorer',
    'manage_options',
    'vfwp-page-urls',
    'vfwp_render_page_urls_settings_page'
  );
}

function vfwp_render_page_urls_settings_page() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  $search_path = '';
  if (isset($_GET['page_path'])) {
    $search_path = sanitize_text_field(wp_unslash($_GET['page_path']));
  }

  $sort_by = 'title';
  if (isset($_GET['sort_by'])) {
    $requested_sort = sanitize_key(wp_unslash($_GET['sort_by']));
    if (in_array($requested_sort, array('title', 'url'), true)) {
      $sort_by = $requested_sort;
    }
  }

  $page_posts = get_posts(array(
    'post_type' => 'page',
    'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
    'posts_per_page' => -1,
    'orderby' => 'ID',
    'order' => 'ASC',
  ));

  $pages = array();

  foreach ($page_posts as $page_post) {
    $url = get_permalink($page_post->ID);

    if (!$url) {
      continue;
    }

    $path = wp_parse_url($url, PHP_URL_PATH);
    if (!$path) {
      $path = '/';
    }

    if ($search_path !== '' && stripos($path, $search_path) === false) {
      continue;
    }

    $last_editor_id = (int) get_post_meta($page_post->ID, '_edit_last', true);
    if (!$last_editor_id) {
      $last_editor_id = (int) $page_post->post_author;
    }

    $last_editor = $last_editor_id ? get_userdata($last_editor_id) : false;

    $pages[] = array(
      'title' => get_the_title($page_post->ID) ?: '(no title)',
      'url' => $url,
      'path' => $path,
      'created_at' => get_the_date(
        sprintf('%s %s', get_option('date_format'), get_option('time_format')),
        $page_post->ID
      ),
      'updated_at' => get_the_modified_date(
        sprintf('%s %s', get_option('date_format'), get_option('time_format')),
        $page_post->ID
      ),
      'last_editor' => $last_editor ? $last_editor->display_name : 'Unknown',
    );
  }

  usort($pages, function ($left, $right) use ($sort_by) {
    $left_value = $sort_by === 'url' ? $left['url'] : $left['title'];
    $right_value = $sort_by === 'url' ? $right['url'] : $right['title'];

    return strcasecmp($left_value, $right_value);
  });
  ?>
  <div class="wrap">
    <h1>URL Explorer</h1>
    <p>Browse all pages and filter them by URL path.</p>

    <form method="get">
      <input type="hidden" name="page" value="vfwp-page-urls" />
      <p class="search-box">
        <label class="screen-reader-text" for="vfwp-page-path-search">Filter by page path</label>
        <input
          id="vfwp-page-path-search"
          type="search"
          name="page_path"
          value="<?php echo esc_attr($search_path); ?>"
          placeholder="/page-path"
        />
        <label class="screen-reader-text" for="vfwp-page-sort-by">Sort pages</label>
        <select id="vfwp-page-sort-by" name="sort_by">
          <option value="title" <?php selected($sort_by, 'title'); ?>>Sort by title</option>
          <option value="url" <?php selected($sort_by, 'url'); ?>>Sort by URL (A-Z)</option>
        </select>
        <?php submit_button('Filter', '', '', false); ?>
        <?php if ($search_path !== '' || $sort_by !== 'title') : ?>
          <a class="button button-secondary" href="<?php echo esc_url(admin_url('options-general.php?page=vfwp-page-urls')); ?>">Reset</a>
        <?php endif; ?>
      </p>
    </form>

    <p>
      <?php
      printf(
        esc_html(_n('%s page found', '%s pages found', count($pages), 'vf-wp-intranet')),
        esc_html(number_format_i18n(count($pages)))
      );
      ?>
    </p>

    <table class="widefat striped">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">URL</th>
          <th scope="col">Creation Date</th>
          <th scope="col">Last Updated</th>
          <th scope="col">Last Edited By</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($pages)) : ?>
          <tr>
            <td colspan="5">No pages matched that path filter.</td>
          </tr>
        <?php else : ?>
          <?php foreach ($pages as $page) : ?>
            <tr data-page-path="<?php echo esc_attr(strtolower($page['path'])); ?>">
              <td><?php echo esc_html($page['title']); ?></td>
              <td><a href="<?php echo esc_url($page['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($page['url']); ?></a></td>
              <td><?php echo esc_html($page['created_at']); ?></td>
              <td><?php echo esc_html($page['updated_at']); ?></td>
              <td><?php echo esc_html($page['last_editor']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var searchInput = document.getElementById('vfwp-page-path-search');
      var rows = document.querySelectorAll('tr[data-page-path]');

      if (!searchInput || !rows.length) {
        return;
      }

      searchInput.addEventListener('input', function () {
        var query = searchInput.value.toLowerCase();

        rows.forEach(function (row) {
          row.style.display = row.dataset.pagePath.indexOf(query) !== -1 ? '' : 'none';
        });
      });
    });
  </script>
  <?php
}
