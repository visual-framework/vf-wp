<?php

if (
  is_tax('document_topics') ||
  is_tax('document_types')
) {
  get_template_part('archive-document');
  return;
}

get_header();

get_template_part('partials/vf-intro');

?>
<div class="embl-grid">

  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>

  <div class="vf-content">

    <p><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_them_posts()
      ); ?></p>

    <div class="vf-grid vf-grid__col-2">

<?php

$query = new WP_Query(
array(
    'posts_per_page' => 6,
    'post_type'      => 'document',
    'post_status'    => 'publish'
  )
);

if ( $query->have_posts() ) {
  while ( $query->have_posts() ) {
    $query->the_post();
    get_template_part('partials/vf-summary--article-no-thumbnail');
  }
} else {
  echo '<p>', __('No documents found', 'vfwp'), '</p>';
}

wp_reset_postdata();

?>

    </div>
    <!--/vf-grid-->

    <a href="<?php echo get_post_type_archive_link('document'); ?>" class="vf-button vf-button--primary vf-button--sm">
      <?php esc_html_e('View all'); ?>
    </a>

  </div>
  <!--/vf-content-->
</div>
<!--/embl-grid-->


<?php

get_footer();

?>
