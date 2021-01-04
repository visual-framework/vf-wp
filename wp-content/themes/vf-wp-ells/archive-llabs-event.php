<?php
get_header();

$year = get_the_time('Y');

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">

    <h2 class="vf-hero__heading">
    ELLS LearningLABs    </h2>

    <p class="vf-hero__text">ELLS LearningLABs are Continuing Professional Development courses for European secondary science teachers organised by the European Learning Laboratory for the Life Sciences (ELLS).</p>

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

  <h2 class="vf-intro__subheading">Any upcoming ELLS LearningLABs will be advertised via this website and listed below.</h2>




<p class="vf-intro__text">The 1-3 day courses provide a mix of scientific seminars, hands-on activities, lab experiments and new teaching resources, covering cutting-edge research and technologies and illustrate how these topics can be transferred to the classroom. 
      As of 2017, ELLS also offers virtual teacher training opportunities.</p>
</div>
</section>

<div class="vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--100 vf-u-padding__top--600">
<section class="embl-grid embl-grid--has-centered-content | vf-content">

<div class="vf-section-header">
  <h2 class="vf-section-header__heading"> LearningLabs <?php echo esc_html($year); ?> </h2>
</div>
    <div>
    <?php
 $current_year = get_the_time('Y') . '0101';
 $args = array(
  'post_type' => 'llabs-event',
  'order' => 'ASC',
  'meta_query' => array( array(
    'key' => 'labs_start_date',
    'value' => $current_year,
    'compare' => '>=',
    'type' => 'date'
) )
);
// The Query
$the_query = new WP_Query( $args );
 
// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        include(locate_template('partials/vf-summary-llab.php', false, false));
    }

} else {
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>

</div>
</div>
</section>


<section class="embl-grid embl-grid--has-centered-content vf-u-margin__top--600 | vf-content">

  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" > Past LearningLabs<svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg></a>
    </div>
    <div>
    <?php
 $current_year = get_the_time('Y') . '0101';
 $args = array(
  'post_type' => 'llabs-event',
  'meta_query' => array( array(
    'key' => 'labs_start_date',
    'value' => $current_year,
    'compare' => '<',
    'type' => 'date'
) )
);
// The Query
$past_query = new WP_Query( $args );
 
// past Loop
if ( $past_query->have_posts() ) {
    while ( $past_query->have_posts() ) {
        $past_query->the_post();
        include(locate_template('partials/vf-summary-llab.php', false, false));
    }

} else {
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>

</div>
</div>
</section>
<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

