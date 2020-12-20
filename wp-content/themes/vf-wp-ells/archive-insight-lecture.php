<?php
get_header();
$start_date = get_field('il_start_date');

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
<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <p>The lectures are live streamed to school across the globe and made available online after the event.
</p>
    <p>There are currently ten lectures available, with topics ranging from genomics and neuroscience to ocean diversity, advances in light microscopy and the study of macromolecules.</p>
  </div>
  <div></div>
</div>
<section class="embl-grid embl-grid--has-centered-content | vf-content">

  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" > Upcoming lecture<svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg></a>
    </div>
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-card--article-lecture.php', false, false)); 
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
        <div>
        <p class="vf-text-body vf-text-body--3">The EMBL Insight Lecture 2020 will be presented by Professor Matthias W. Hentze, EMBL Senior Scientist and Director</p>
        <hr class="vf-divider">
        <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Registration deadline</span> <span class="vf-u-text-color--grey"><br><?php echo esc_html($application_deadline); ?></span></p>
        <a href="JavaScript:Void(0);" class="vf-button vf-button--primary vf-button--sm">Register</a>
        </div>
</section>

<section class="embl-grid">

  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" > Past lectures<svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg></a>
    </div>

    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-lecture.php', false, false)); 
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

