<?php
$start_date = get_field('labs_start_date');
$end_date = get_field('labs_end_date');
$start = DateTime::createFromFormat('j M Y', $start_date);
$end = DateTime::createFromFormat('j M Y', $end_date);
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

?>
<article class="vf-card vf-card--primary vf-card--bordered">

  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>

  <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__title">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?></a>
    </h3>
    <p class="vf-card__text">
      <?php echo get_the_excerpt(); ?></p>
    <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>">

      <?php 
            if ( ! empty($start_date)) {
              if ($end_date) { 
                if ($start->format('M') == $end->format('M')) {
                  echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
                else {
                  echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
                    ?>
      <?php } 
              else {
                echo $start->format('j M Y'); 
              } }
              ?>
    </time>
  </div>
</article>
