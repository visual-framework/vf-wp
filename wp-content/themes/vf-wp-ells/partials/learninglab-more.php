

<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
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
  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <?php if ($type) { ?>
  <p class="vf-summary__text">
    <?php echo ($type->name); ?>
  </p>
  <?php }?>

</article>
