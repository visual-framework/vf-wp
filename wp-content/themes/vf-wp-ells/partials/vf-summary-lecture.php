<?php
$start_date = get_field('il_start_date');
$topic_area = get_field('il_topic_area');

?>
<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php echo ($start_date); ?></time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
      'loading'  => 'lazy',
      'itemprop' => 'image' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo get_the_title(); ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <?php if ($topic_area) { ?>
  <p class="vf-summary__meta">
    <span class="vf-u-text-color--grey">          
      <?php
      $topic_list = [];
      foreach( $topic_area as $topic ) { 
        $topic_list[] = $topic->name; }
        echo implode(', ', $topic_list); ?></span>&nbsp;&nbsp;
    <?php  } ?>
  </p>
</article>
