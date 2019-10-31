<?php

get_header();

$vf_group_header = VF_Plugin::get_plugin('vf_group_header');

if (class_exists('VF_Group_Header')) {
  VF_Plugin::render($vf_group_header);
}

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
      <div class="vf-content">
        <?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>
      </div>
    </main>
  </div>
</section>
<?php

get_footer();

?>
