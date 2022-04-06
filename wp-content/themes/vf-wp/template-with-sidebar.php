<?php
/**
* Template Name: Sidebar
*/
get_header();

$title = get_the_title();
?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-3 vf-u-grid-gap--800">
    <div class="vf-grid__col--span-2">
      <h1 class="vf-text vf-text-heading--1">
        <?php echo esc_html($title); ?>
      </h1>
      <?php

      // the_content();
      $vf_theme->the_content();

      ?>
    </div>
    <?php if (is_active_sidebar('sidebar-page')) { ?>
    <div>
      <?php vf_sidebar('sidebar-page'); ?>
    </div>
    <?php } ?>
</div>

<?php

get_footer();
?>

