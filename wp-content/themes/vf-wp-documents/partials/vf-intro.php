<section class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div>
    <!-- empty -->
  </div>
  <div>
    <h1 class="vf-intro__heading vf-intro__heading--has-tag">
      <span>Documents</span>
      <span class="vf-badge vf-badge--primary vf-badge--phases">beta</span>
    </h1>
    <p class="vf-lede">The EMBL Documents repository holds the digital copies of official EMBL documents, reports, brochures and various other publications.</p>
    <p class="vf-intro__text"><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_them_posts()
      ); ?></p>
    <br>
    <?php get_search_form(); ?>
  </div>
</section>
<!--/vf-intro-->
