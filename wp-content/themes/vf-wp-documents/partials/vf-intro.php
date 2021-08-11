<section class="vf-intro">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <span>Documents</span>
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
