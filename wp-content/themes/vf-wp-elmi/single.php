<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');

get_header();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--xxl | vf-u-margin__bottom--0">
 <div>
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
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <p class="vf-meta__topics"><?php echo get_the_category_list(','); ?></p>
        </div>
    </div>
 </div>
 <div class="vf-content | vf-u-padding__bottom--xxl">
  <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
</section>

<?php get_footer(); ?>
