<?php

get_header();

// Only show group long description on front page
if (is_front_page()) {

  $vf_group_header = VF_Plugin::get_plugin('vf_group_header');

  if (class_exists('VF_Group_Header')) {
    VF_Plugin::render($vf_group_header);
  }
}

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>

      <?php the_content(); ?>

    </main>
    <?php if (is_active_sidebar('sidebar-page')) { ?>
    <aside class="vf-inlay__content--additional">
      <?php vf_sidebar('sidebar-page'); ?>
    </aside>
    <?php } ?>
  </div>
</section>
<?php

get_footer();

?>
