<?php
$start_date = get_field('labs_start_date');
$end_date = get_field('labs_end_date');
$start = DateTime::createFromFormat('j M Y', $start_date);
$end = DateTime::createFromFormat('j M Y', $end_date);
$type = get_field('labs_type');

?>
<article class="vf-summary vf-summary--news">
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
  <?php if ($type) { ?>
  <p class="vf-summary__meta">
    <?php if ($type) { ?>
    <span class="vf-u-text-color--grey"><?php echo ($type->name); ?></span>&nbsp;&nbsp;
    <?php } }
      if ($type) { ?>
  </p>
  <?php }?>

  </article>
