<?php
/**
* Template Name: Members - automated
*/

// The plugin is missing so use the default page template
if ( ! class_exists('VF_Members')) {
  get_template_part('page');
  return;
}

$vf_members = VF_Plugin::get_plugin('vf_members');

get_header();

global $vf_theme;

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid">
  <div>
    <h1 class="vf-text vf-text-heading--1">
      <?php the_title(); ?>
    </h1>
    <?php

      // Render the group header plugin with the team leader only
      if (class_exists('VF_Group_Header')) {
        $vf_group_header = new VF_Group_Header(array(
          'minimal' => true
        ));

        VF_Plugin::render($vf_group_header);

        echo '<hr class="vf-divider | vf-u-margin__top--600">';
      }

      // the_content();
      echo '<div>';
      VF_Plugin::render($vf_members);
      echo '</div>';
      ?>
  </div>
</div>
<?php

get_footer();

?>
