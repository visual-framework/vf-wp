<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1>
      <?php the_title(); ?>
    </h1>
    <div class="vf-meta__details">
      <p class="vf-author__name"><span class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>, by <a
          class="vf-link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> in
        <?php echo get_the_category_list(', '); ?>
      </p>
    </div>
  </div>

  <div></div>
</div>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">

    <?php
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
    <?php } ?>

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
    <?php
      if (comments_open() || get_comments_number()) {
        comments_template();
      }
      ?>
  </div>
  <?php if (is_active_sidebar('sidebar-blog')) { ?>

  <div>
    <?php vf_sidebar('sidebar-blog'); ?>
  </div>

  <?php } ?>
</div>
<?php

get_footer();

?>
