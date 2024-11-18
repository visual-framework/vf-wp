<?php
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');
$emergency = get_field('cb_emergency_notification');

// Determine the background color based on the emergency notification field
$backgroundColor = '';
$textColor = '';
$linkColor = '';

if ($emergency === 'red') {
    $backgroundColor = '#d41645';
    $textColor = 'white';
    $linkColor = 'white';
} elseif ($emergency === 'yellow') {
    $backgroundColor = '#fffadc';
    
}
?>

<article class="vf-summary vf-summary--has-image vf-u-margin__bottom--0 vf-u-padding--200" style="<?php echo $backgroundColor ? "background-color: $backgroundColor; color: $textColor;" : 'color: black;'; ?>">
  <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="color: <?php echo $linkColor; ?>;">
  <?php 
  if ( has_post_thumbnail() ) {
  the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-summary__image--thumbnail' ) ); 
  }
  else { ?>
  <img class="vf-summary__image vf-summary__image--thumbnail"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
  <?php } ?>
  </a>
  <p class="vf-summary__date vf-u-margin__bottom--100" style="color: <?php echo $textColor; ?>;">
      <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--0"><time class="" style="margin-left: 0; color: <?php echo $textColor; ?>;"
          title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
      <?php if ($topic_terms) { ?> | 
<span class="vf-text-body vf-text-body--5 | topic | vf-u-margin__bottom--0 | vf-u-margin__top--200">
        <?php 
        if ($topic_terms) {
          $topics_list = array(); 
          foreach ($topic_terms as $term) {
            $topics_list[] = '<a class="vf-link ' . esc_attr($term->slug) . '" style="color: ' . $linkColor . ';" href="' . esc_url(get_term_link($term)) . '">' . strtoupper(esc_html($term->name)) . '</a>'; }
            echo implode(', ', $topics_list); }?>
      </span>
      <?php } ?>
    </p>
  <h3 class="vf-summary__title" style="font-size: 20px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="color: <?php echo $linkColor; ?>;">
    <?php echo esc_html(get_the_title()); ?>
    </a>
  </h3>
</article>
