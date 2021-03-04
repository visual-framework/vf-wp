<?php
$title = esc_html(get_the_title());
?>

<article class="vf-card vf-card--brand vf-card--bordered">
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
  <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__heading">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?>
        <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
        </svg>
      </a>
    </h3>
    <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </div>
</article>