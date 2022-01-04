<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$show = get_post_meta( get_the_ID(), 'hide_featured_image', true );
?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1>
      <?php the_title(); ?>
    </h1>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">

    <?php
    if ($show == '0') {
      if (has_post_thumbnail()) {
        $caption = get_the_post_thumbnail_caption();
      ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image | vf-u-margin__bottom--200')); ?>
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
    <?php
    $tags = get_the_tags($post->ID);
    if (is_array($tags)) {
    ?>
    <p class="vf-text--body vf-text-body--3">
      <?php esc_html_e('Tags:', 'vfwp'); ?>
      <?php
      $tagslist = array();
      foreach($tags as $tag) {
        $tagslist[] = '<a  href="'
          . get_tag_link($tag->term_id)
          . '" class="vf-link vf-link--secondary | vf-text--body vf-text-body--3'
          . esc_attr($tag->term_id)
          . '">'
          . esc_html($tag->name)
          . '</a>';
      }
      echo implode(', ', $tagslist);
    ?>
    </p>
    <?php } ?>

  </div>
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
        <p class="vf-meta__topics"><?php echo get_the_category_list(' '); ?></p>
      </div>
      <?php if( have_rows('in_this_article') ): ?>
      <div class="vf-links vf-links--tight vf-links__list--s">
        <p class="vf-links__heading">In this article</p>
        <ul class="vf-links__list vf-links__list--secondary | vf-list">

          <?php while( have_rows('in_this_article') ): the_row();
        $anchor = get_sub_field('anchor');
        $heading = get_sub_field('heading_description');?>

          <li class="vf-list__item">
            <a href="<?php echo esc_url( $anchor ); ?>" class="vf-list__link"><?php echo esc_html($heading) ?></a>
          </li>
          <?php endwhile; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <div>
      <?php vf_sidebar('sidebar-blog'); ?>
    </div>
    <?php } ?>
  </div>
</div>
<?php

get_footer();

?>
