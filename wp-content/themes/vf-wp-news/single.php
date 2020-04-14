<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);


get_template_part('partials/header');

the_post();

?>

<main
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--xxl | vf-u-margin__bottom--0">
  <div class="article-left-col">

    <aside class="vf-article-meta-information">
    <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
            <a class="vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </p>
        <a class="vf-author--avatar__link | vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, $default, $alt, array('class' => 'vf-author--avatar')); ?>
        </a>
    </div>
    <div class="vf-meta__details">
      <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
        datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <p class="vf-meta__topics"><?php echo get_the_category_list(','); ?></p>
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
    </div>
    <?php endif; ?>
</aside>
  </div>

  <div class="vf-content | vf-u-padding__bottom--xxl">
    <h1><?php the_title(); ?></h1>
    <h2>
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </h2>
    <?php

if( get_field( 'youtube_url' ) ) {
    $videoid = get_field( 'youtube_url' );
    $caption = get_field('video_caption');
    echo '<div class="vf-u-margin__bottom--xs embed-container embed-padding-main"><iframe src="' . $videoid . '" frameborder="0" allowfullscreen class="vf-card__image"></iframe></div><figcaption class="vf-figure__caption">' . $caption . '</figcaption>';
} 

else if ( get_field( 'mp4_url' ) ) { 
  $mp4url = get_field( 'mp4_url' );
  $caption = get_field('video_caption');
  echo '<div><video muted="muted" class="vf-card__image" controls><source src="' . $mp4url . '" type="video/mp4"></video></div><figcaption class="vf-figure__caption">' . $caption . '</figcaption>';
}

else { 
      $show = get_post_meta( get_the_ID(), 'show_featured_image', true );
       if ( $show == '1' ): //not displaying
      else:?>
      <figure class="vf-figure">
          <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
          <figcaption class="vf-figure__caption">
            <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
          </figcaption>
        </figure>
        <?php
      endif;
}
?>

    <?php the_content(); ?>
  </div>
  <div class="social-share-box">
    <?php echo do_shortcode('[Sassy_Social_Share]') ?>
  </div>

</main>

<div class="embl-grid embl-grid--has-centered-content | vf-content">
  <div></div>

  <div>
    <?php if( have_rows('article_sources') ): ?>
    <div class="embl-grid">
      <div>
        <h4 class="vf-text vf-text-heading--5">Source articles:</h4>
      </div>
      <div>
        <?php while( have_rows('article_sources') ): the_row(); 
			
      $source = get_sub_field('source_link_url');
      $description = get_sub_field('source_description', false, false);?>
        <p class="vf-text--body vf-text-body--3"><a class="vf-link vf-link--secondary"
            href="<?php echo esc_url( $source ); ?>"><?php echo ($description) ?></a></p>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if( have_rows('related_links') ): ?>
    <div class="embl-grid">
      <div>
        <h4 class="vf-text vf-text-heading--5">Related links:</h4>
      </div>
      <div>
        <?php while( have_rows('related_links') ): the_row(); 
			
      $source = get_sub_field('link_url');
      $description = get_sub_field('link_description');?>
        <p class="vf-text--body vf-text-body--3"><a class="vf-link vf-link--secondary"
            href="<?php echo esc_url( $source ); ?>"><?php echo esc_html($description) ?></a></p>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

    <p class="vf-text--body vf-text-body--3 | tags-inline">Tags:</p>
    <?php
    $tags = get_the_tags($post->ID);
if ($tags) {
   $tagslist = array();
   foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary | vf-text--body vf-text-body--3' . $tag->term_id . '">' . $tag->name . '</a>';
   }
   echo implode(', ', $tagslist);
} ?>
  </div>
  <div></div>
</div>

<section class="vf-inlay">
  <div
    class="vf-inlay__content | vf-u-background-color-ui--off-white | vf-u-margin__bottom--xs | vf-u-padding__top--md | vf-u-fullbleed | category-more">
    <main class="vf-inlay__content--full-width">
      <h3 class="vf-section-header__heading | vf-u-margin__bottom--md">More from this category</h3>
      <div class="vf-grid vf-grid__col-3">
        <?php
          $args = array(
            'posts_per_page' => 3, 
            'post__not_in'   => array( get_the_ID() ), 
            'no_found_rows'  => true, 
          );

          $cats = wp_get_post_terms( get_the_ID(), 'category' ); 
          $cats_ids = array();  
          foreach( $cats as $related_cat ) {
            $cats_ids[] = $related_cat->term_id; 
          }
          if ( ! empty( $cats_ids ) ) {
            $args['category__in'] = $cats_ids;
          }

          $query = new wp_query( $args );

          foreach( $query->posts as $post ) : setup_postdata( $post ); ?>

        <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>

    </main>
  </div>

  <?php include(locate_template('partials/pow-container.php', false, false)); ?>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_template_part('partials/footer');?>
