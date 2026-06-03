<?php
$start_date = get_field('labs_start_date');
$end_date = get_field('labs_end_date');
$start = DateTime::createFromFormat('j M Y', $start_date);
$end = DateTime::createFromFormat('j M Y', $end_date);
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$type = get_field('labs_type');


?>
<article class="vf-card vf-card--brand vf-card--bordered">
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
  <div class="vf-card__content | vf-stack vf-stack--200">
    <time class="vf-summary__date" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>">
    
      <?php 
            if ( ! empty($start_date)) {
              if ($end_date) { 
                if ($start->format('M') == $end->format('M')) {
                  echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
                else {
                  echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
               } 
              else {
                echo $start->format('j M Y'); 
              } }
              ?>
    </time>
    <h3 class="vf-card__heading | vf-u-margin__top--100">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?>
      <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
    </svg>
  </a>
</h3>
<p class="vf-card__text" style="margin-bottom: 10px !important;">
  <?php echo get_the_excerpt(); ?></p>
  <?php if ($type) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--100">
    <?php if ($type) { ?>
    <span class="vf-u-text-color--grey"><?php echo ($type->name); ?></span>&nbsp;&nbsp;
    <?php } }
    if ($type) { ?>
  </p>
  <?php }?>
  </div>
</article>
