<?php
get_header();
$start_date = get_field('il_start_date');
$registration_link = get_field('il_registration_link');
$application_deadline = get_field('il_application_deadline');

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image-size: auto 28.5rem">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">

    <h2 class="vf-hero__heading">
    EMBL Insight Lectures    </h2>

    <p class="vf-hero__text">The EMBL Insight Lecture series takes a look at current trends in life science research and shows how research is influencing our everyday lives. Each year, a senior scientist from the European Molecular Biology Laboratory gives a lecture specially designed for a secondary school student audience.</p>

  </div>

</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<section class="vf-intro">

<div><!-- empty --></div>

<div class="vf-stack">

  <h2 class="vf-intro__subheading">The lectures are live streamed to school across the globe and made available online after the event.</h2>




<p class="vf-intro__text">There are currently ten lectures available, with topics ranging from genomics and neuroscience to ocean diversity, advances in light microscopy and the study of macromolecules.</p>
</div>
</section>

<div class="vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--100 vf-u-padding__top--600">
<section class="embl-grid embl-grid--has-centered-content | vf-content">

<div class="vf-section-header">
  <h2 class="vf-section-header__heading"> Upcoming lecture </h2>
</div>
    <?php
			$upcomingLecture = new WP_Query (array('posts_per_page' => 1, 'post_type' => 'insight-lecture'  ));
$ids = array();
while ($upcomingLecture->have_posts()) : $upcomingLecture->the_post();
$ids[] = get_the_ID(); ?>
      <?php include(locate_template('partials/vf-card--article-lecture.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>

        <div>
        <p class="vf-text-body vf-text-body--3">The EMBL Insight Lecture 2020 will be presented by Professor Matthias W. Hentze, EMBL Senior Scientist and Director</p>
        <hr class="vf-divider">
        <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Registration deadline</span> <span class="vf-u-text-color--grey"><br><?php echo esc_html($application_deadline); ?></span></p>
        <a href="<?php echo esc_url($registration_link); ?>" class="vf-button vf-button--primary vf-button--sm">Register</a>
        </div>
</section>
</div>
<section class="embl-grid | vf-u-margin__top--600">

<div class="vf-section-header">
  <h2 class="vf-section-header__heading"> Past lectures </h2>
</div>
<div>

  <?php
		$pastLectures = new WP_Query(array('post__not_in' => $ids, 'post_type' => 'insight-lecture'));
		while ($pastLectures->have_posts()) : $pastLectures->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary-lecture.php', false, false));  
                if ( ! $vf_theme->is_last_post()) {
                  echo '<hr class="vf-divider">';
                }
       ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  </div>

</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

