<?php
get_header();
$start_date = get_field('il_start_date');
$registration_link = get_field('il_registration_link');
$application_deadline = get_field('il_application_deadline');
$summary = get_field('il_summary');

?>

<section class="vf-hero vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image-size: auto 28.5rem">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <h2 class="vf-hero__heading">
    ELLS</h2>
    <p class="vf-hero__subheading">European Learning Laboratory for the Life Sciences</p>
    <p class="vf-hero__text">Our inspiring educational experiences share the scientific discoveries of EMBL with young learners aged 10-19 years and teachers in Europe and beyond.</p>
  </div>
</section>

<?php
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}
?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">Insight Lectures
    </h1>
    <p>The lectures are live streamed to school across the globe and made available online after the event.</p>
    <p>There are currently ten lectures available, with topics ranging from genomics and neuroscience to ocean diversity, advances in light microscopy and the study of macromolecules.
    </p>
  </div>
</div>
<section
  class="vf-content | vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--600 vf-u-padding__top--600 vf-u-margin__bottom--600">
  <div class="vf-grid vf-grid__col-4">
    <div class="vf-grid__col--span-2 | vf-content">
      <h3> Upcoming lecture </h3>
      <p><?php echo ($summary); ?></p>
      <hr class="vf-divider">
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Registration
          deadline</span> <span class="vf-u-text-color--grey"><br><?php echo esc_html($application_deadline); ?></span>
      </p>
      <a href="<?php echo esc_url($registration_link); ?>"
        class="vf-button vf-button--primary vf-button--sm">Register</a>
    </div>
    <div class="vf-grid__col--span-2">
      <?php $upcomingLecture = new WP_Query (array('posts_per_page' => 1, 'post_type' => 'insight-lecture'  ));
        $ids = array();
        while ($upcomingLecture->have_posts()) : $upcomingLecture->the_post();
        $ids[] = get_the_ID(); ?>
        <?php include(locate_template('partials/vf-card-lecture.php', false, false)); ?>
        <?php endwhile;?>
        <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<section class="vf-content">
  <h3>Browse or filter all Insight Lectures</h3>
  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>
      <?php include(locate_template('partials/lecture-filter.php', false, false)); ?>
    </div>
    <div class="vf-grid__col--span-3">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-lecture.php', false, false)); 
            if (($wp_query->current_post + 1) < ($wp_query->post_count)) {
              echo '<hr class="vf-divider">';
           }
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
      <div class="vf-grid"> <?php vf_pagination();?></div>
    </div>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

