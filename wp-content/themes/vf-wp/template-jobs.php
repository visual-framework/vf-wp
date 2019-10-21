<?php
/**
* Template Name: Jobs
*/

// The plugin is missing so use the default page template
if ( ! class_exists('VF_Jobs')) {
  get_template_part('page');
  return;
}

$vf_jobs = VF_Plugin::get_plugin('vf_jobs');

get_template_part('partials/header');

the_post();

$vf_group_header = VF_Plugin::get_plugin('vf_group_header');

if (class_exists('VF_Group_Header')) {
  VF_Plugin::render($vf_group_header);
}

$keyword = $vf_jobs->get_query_keyword();

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>

      <?php the_content(); ?>

    </main>
    <aside class="vf-inlay__content--additional">

      <form role="search" class="vf-form | vf-search vf-search--inline" method="get" action="<?php the_permalink(); ?>">
        <div class="vf-form__item | vf-search__item">
          <label class="vf-form__label vf-sr-only | vf-search__label" for="filter_keyword"><?php _e('Search jobs by keyword:', 'vfwp'); ?></label>
          <input class="vf-form__input | vf-search__input" value="<?php echo esc_attr($keyword); ?>" placeholder="<?php esc_attr_e('Search job description...', 'vfwp'); ?>" name="filter_keyword">
        </div>
        <input type="submit" class="vf-search__button | vf-button vf-button--primary" value="<?php esc_attr_e('Search', 'vfwp'); ?>">
      </form>

    </aside>
  </div>
</section>
<?php

/**
 * Action: `wp_footer`
 * JavaScript to activate the autocomplete search field
 * using the EMBL Taxonomy as suggestions
 */

$autocomplete = get_field('embl_taxonomy_autocomplete', 'option');

if ($autocomplete === true) {
  add_action('wp_footer', 'vf__jobs_filter_script', 20);
}

function vf__jobs_filter_script() {
  if ( ! function_exists('embl_taxonomy')) {
    return;
  }
  $wp_terms = embl_taxonomy()->register->get_wp_taxonomy();

  $vf_jobs = VF_Plugin::get_plugin('vf_jobs');
  $keyword = $vf_jobs->get_query_keyword();

  $options = array();
  $exclude = array('Who', 'What', 'Where');

  foreach ($wp_terms as $i => $wp_term) {
    if ( ! property_exists($wp_term, 'meta')) {
      continue;
    }
    if ( ! array_key_exists(EMBL_Taxonomy::META_NAME, $wp_term->meta)) {
      continue;
    }
    $name = vf_search_keyword(
      $wp_term->meta[EMBL_Taxonomy::META_NAME]
    );
    if (in_array($name, $options)) {
      continue;
    }
    $options[] = $name;
  }

  // Exclude and reindex
  $options = array_values(
    array_diff($options, $exclude)
  );

  if ( ! count($options)) {
    return;
  }
?>
<script>
(function() {

  if (/complete|interactive|loaded/.test(document.readyState)) {
    handleReady();
  } else {
    document.addEventListener('DOMContentLoaded', handleReady);
  }

  function handleReady() {

    // Check library exists
    if (typeof window.accessibleAutocomplete !== 'function') {
      return;
    }
    // Ensure input element exists
    var $field = document.querySelector('input[name="filter_keyword"]');
    if ( ! $field) {
      return;
    }

    // Replace input with new container
    var $container = document.createElement('div');
    $field.parentNode.insertBefore($container, $field);
    $field.parentNode.removeChild($field);
    $field.remove();

    // https://github.com/alphagov/accessible-autocomplete#api-documentation
    var config = {
      element: $container,
      source: <?php echo json_encode($options); ?>,
      id: $field.name,
      name: $field.name,
      defaultValue: $field.value,
      placeholder: $field.placeholder,
      cssNamespace: 'vf-autocomplete',
      showNoOptionsFound: false
    };

    accessibleAutocomplete(config);

  } // handleReady

})();
</script>
<?php
} // Action: `vf__jobs_filter_script`

get_template_part('partials/footer');

?>
