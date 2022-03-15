<?php
get_header();
$start_date = get_field('il_start_date');
$registration_link = get_field('il_registration_link');
$application_deadline = get_field('il_application_deadline');
$summary = get_field('il_summary');

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">Insight Lectures
    </h1>
    <p>Since its start in 2003, the EMBL Insight Lecture series has been inspiring thousands of young learners from
      across the world. Each year, a senior EMBL scientist presents their research on a cutting-edge topic in the life
      sciences in a lecture specifically designed for a secondary school student audience. </p>
    <p>We invite young learners aged 16 to 19 to attend the virtual event with their school classes or as individuals.
    </p>
    <p>Recordings of the lectures are available on our website. The lectures cover a wide range of life sciences topics,
      from genomics and neuroscience, to ocean diversity, advances in light microscopy and the study of macromolecules.
    </p>
  </div>
</div>
<?php $upcomingLecture = new WP_Query (array('posts_per_page' => 1, 'post_type' => 'insight-lecture'  ));
        $ids = array();
        while ($upcomingLecture->have_posts()) : $upcomingLecture->the_post();
        $ids[] = get_the_ID(); ?>
<section
  class="vf-content | vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--600 vf-u-padding__top--600 vf-u-margin__bottom--600">
  <div class="vf-grid vf-grid__col-4">
    <div class="vf-grid__col--span-2 | vf-content">
      <h3> Upcoming lecture </h3>
      <p><?php echo ($summary); ?></p>
      <hr class="vf-divider">
      <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration
          deadline</span> <span class="vf-u-text-color--grey"><br><?php echo esc_html($application_deadline); ?></span>
      </p>
      <a href="<?php echo esc_url($registration_link); ?>"
        class="vf-button vf-button--primary vf-button--sm">Register</a>
    </div>

    <div class="vf-grid__col--span-2">
      <?php include(locate_template('partials/vf-card-lecture.php', false, false)); ?>
    </div>
  </div>
</section>
<?php endwhile;?>
<?php wp_reset_postdata(); ?>

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
