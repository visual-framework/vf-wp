

<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

?>

<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0; margin-top: 6px;" title="<?php the_time('c'); ?>"datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
</article>
