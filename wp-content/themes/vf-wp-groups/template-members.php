<?php
/**
* Template Name: Members
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
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--full-width">
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

        echo '<hr class="vf-divider">';
      }

      // the_content();
      include('wp-content/plugins/vf-members-block/template.php')

      ?>
    </main>
  </div>
</section>
<?php

get_footer();

?>
