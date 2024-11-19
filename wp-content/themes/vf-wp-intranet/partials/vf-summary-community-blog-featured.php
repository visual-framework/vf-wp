<?php
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');
$customDateSorting = get_the_time('Ymd');
$emergency = get_field('cb_emergency_notification');
$start_date = get_the_date('Ymd'); // Retrieve the post's publishing date in Ymd format
$start = DateTime::createFromFormat('Ymd', $start_date);
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
<article class="vf-summary vf-summary--news | vf-u-margin__bottom--800 vf-u-padding--200 newsItem articleBottomBorder" data-jplist-item style="<?php echo $backgroundColor ? "background-color: $backgroundColor; color: $textColor;" : ''; ?>">
  <span class="vf-summary__date" <?php if ($emergency !== 'red') { echo 'data-eventtime="' . $start->format('Ymd') . '"'; } ?>><time
      class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0; color: <?php echo $textColor; ?>;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php 
  if ( has_post_thumbnail() ) {
  the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); 
  }
  else { ?>
  <img class="vf-summary__image"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
  <?php } ?>

  <h3 class="vf-summary__title" <?php if (is_front_page()) { echo 'style="font-size: 18px;"'; } ?>>
    <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="color: <?php echo $linkColor; ?>;"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <div>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    <?php if (($topic_terms)) { ?>
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue ' . esc_attr( $term->slug ) . '"style="color: #373a36; text-decoration: none;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode('', $topics_list); }?>
    </span>
  </p>
<?php } ?>
  <p class="vf-summary__meta vf-u-margin__bottom--600">EMBL site: 
  <?php 
if (($locations)) { ?>
  <span class="vf-u-text-color--grey | vf-u-margin__right--600
  location vf-u-margin__top--0 
  <?php foreach($locations as $location) { echo 'location-' . $location->slug . ' '; } ?>">
  <?php $location_list = [];
    foreach( $locations as $location ) { 
      $location_list[] = $location->name; }
      echo implode(', ', $location_list); ?>
</span>&nbsp;&nbsp;
<?php }?>
    </p>
    </div>
</article>
