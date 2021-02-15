<?php

get_header();

?>
<section class="vf-hero vf-hero--primary vf-hero--block vf-hero--800 | vf-u-fullbleed | vf-u-margin__bottom--0"
  style="--vf-hero--bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/Ells_Masthead_1000x600.png');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      ELLS
    </h2>
    <p class="vf-hero__subheading">European Learning Laboratory for the Life Sciences</p>
  </div>
</section>
<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<section class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h2>
      Latest news</h2>
    <div>
      <?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page' => 6,
    'paged' => $page,);
query_posts($args);?>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
      <?php endwhile; endif; ?>
    </div>
    <div class="vf-grid" style="margin: 4%"> <?php vf_pagination();
      ?>
    </div>
  </div>
  <div>
  </div>
</section>


<?php get_footer(); ?>
