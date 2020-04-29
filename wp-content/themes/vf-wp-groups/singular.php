<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white | vf-content">
    <main class="vf-inlay__content--main">
      <h1>
        <?php the_title(); ?>
      </h1>
    </main>

    <aside class="vf-inlay__content--additional">
      </aside>

    <main class="vf-inlay__content--main">
      <figure class="vf-figure">
          <?php the_post_thumbnail('full', array('class' => 'vf-figure__image | vf-u-margin__bottom--sm')); ?>
          <figcaption class="vf-figure__caption">
            <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
          </figcaption>
        </figure>
      <?php

      // the_content();
      $vf_theme->the_content();

      ?>
      <?php
      if (comments_open() || get_comments_number()) {
        comments_template();
      }
      ?>
    </main>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>

    <aside class="vf-inlay__content--additional ">
      <div class="vf-article-meta-information">
    <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
            <a class="vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </p>
        <a class="vf-author--avatar__link | vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </a>
    </div>
    <div class="vf-meta__details">
      <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
        datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <p class="vf-meta__topics"><?php echo get_the_category_list(','); ?></p>
    </div>
    <p class="vf-text--body vf-text-body--3 | vf-u-margin__left--sm">Tags:
    <?php
      $tags = get_the_tags($post->ID);
      if ($tags) {
        $tagslist = array();
        foreach($tags as $tag) {
        $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary | vf-text--body vf-text-body--3' . $tag->term_id . '">' . $tag->name . '</a>';
        }
       echo implode(', ', $tagslist);
      } ?></p>
    </div>
    <div class="vf-u-margin__top--md">
    <?php vf_sidebar('sidebar-blog'); ?>
    </div>
    </aside>
    
    <?php } ?>
  </div>
</section>
<?php

get_footer();

?>
