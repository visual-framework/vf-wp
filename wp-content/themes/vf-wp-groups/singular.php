<?php

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_EBI_Global_Header')) {
  VF_Plugin::render(VF_EBI_Global_Header::get_plugin('vf_ebi_global_header'));
}
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
}
get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$show = get_post_meta( get_the_ID(), 'hide_featured_image', true );
$slug = get_page_by_path( 'blog' ); 
$custom_template = get_field('vf_groups_custom_blog_template', $slug->ID);

if ($custom_template) {
  if (class_exists('VF_WP_Hero_Blog')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_hero_blog'));
  }
  if (class_exists('VF_Navigation')) {
    VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
  } }
else {
  if (class_exists('VF_WP_Groups_Header')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_groups_header'));
  }
  
}
?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0">
  <div>
  <div class="vf-article-meta-information | vf-u-margin__bottom--400">
      <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
          <a class="vf-link"
            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </p>
        <a class="vf-author--avatar__link | vf-link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
          <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </a>
      </div>
      <div class="vf-meta__details">
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <p class="vf-meta__topics"><span class="vf-text--body vf-text-body--3">Category: </span><?php echo get_the_category_list(' '); ?></p>
        <?php
    $tags = get_the_tags($post->ID);
    if (is_array($tags)) {
    ?>
    <p class="vf-u-margin__top--0">
    <span class="vf-text--body vf-text-body--3">Tags: </span>
      <?php
      $tagslist = array();
      foreach($tags as $tag) {
        $tagslist[] = '<a  href="'
          . get_tag_link($tag->term_id)
          . '" class="vf-link" style="color: #707372;"'
          . '>'
          . esc_html($tag->name)
          . '</a>';
      }
      echo implode(', ', $tagslist);
    ?>
    </p>
    <?php } ?>
      </div>
  </div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <h1 class="vf-text vf-text-heading--1 vf-u-margin__bottom--400"><?php the_title(); ?></h1>

    <?php
    if ($show == '0') {
      if (has_post_thumbnail()) {
        $caption = get_the_post_thumbnail_caption();
      ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image | vf-u-margin__bottom--400')); ?>
      <?php if ( ! vf_html_empty($caption)) { ?>
      <figcaption class="vf-figure__caption">
        <?php echo esc_html($caption); ?>
      </figcaption>
      <?php } ?>
    </figure>
    <?php } } ?>

    <?php

      // the_content();
      $vf_theme->the_content();

      ?>


  </div>
  <div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <?php vf_sidebar('sidebar-blog'); ?>
    <?php } ?>
  </div>
</section>

<?php
// Global Footer
if (class_exists('VF_Global_Footer')) {
    VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
  }
if (class_exists('VF_EBI_Global_Footer')) {
    VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
  }
  
  get_footer();
  ?>