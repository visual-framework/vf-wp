<?php

if (
  is_tax('document_topic') ||
  is_tax('document_type')
) {
  get_template_part('archive-document');
  return;
}

get_header();

get_template_part('partials/vf-intro');

$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="embl-grid">

  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>

  <div class="vf-content">

<h4 class="vf-text vf-text-heading--4 vf-u-margin__top--0">Recently added:</h4>

    <div class="vf-grid vf-grid__col-2">

<?php

 $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
 $args = array(
  'post_type' => 'document',
  'posts_per_page' => 16,
  'paged' => $page,);
query_posts($args);?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_template_part('partials/vf-summary--document'); ?>
<?php endwhile; endif; 

wp_reset_postdata();

// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=document');

?>

    </div>
    <!--/vf-grid-->
    <div class="vf-grid"> <?php vf_pagination();
      ?>
      </div>

  </div>
  <!--/vf-content-->
</div>
<!--/embl-grid-->


<?php

get_footer();

?>
