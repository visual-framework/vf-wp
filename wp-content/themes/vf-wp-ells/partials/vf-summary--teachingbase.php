<?php
$title = esc_html(get_the_title());
$topic_area = get_field('tb_topic_area');
$age_group = get_field('tb_age_group');
$post_id = get_the_ID();
?>

<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
      'loading'  => 'lazy',
      'itemprop' => 'image' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <div>
    <?php if (($topic_area) || ($age_group)) { ?>
    <p class="vf-summary__meta | vf-u-margin__bottom--200">
      <?php if ($topic_area) { ?>
      <span>Topic area:</span>&nbsp;
       <span class="vf-u-text-color--grey"> <?php $topic_list = [];
        foreach( $topic_area as $topic ) { 
          $topic_list[] = $topic->name; }
          echo implode(', ', $topic_list); ?></span>&nbsp;&nbsp;
      <?php } ?>
      <?php if ($age_group) { ?>
      <span>Age group:</span>&nbsp;
       <span class="vf-u-text-color--grey"> <?php $age_list = [];
        foreach( $age_group as $age ) { 
         $age_list[] = $age->name; }
         echo implode(', ', $age_list); ?>
      </span>
      <?php } }
      if (($topic_area) || ($age_group)) { ?>
    </p>
    <?php }?>
    <?php wpml_post_languages_in_loop(); ?>
  </div>
</article>
