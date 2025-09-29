<?php
$now = new DateTime();
$post_id = get_the_ID();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$odType = get_field('vf-wp-training-on_demand_type',$post_id);
$category = get_field('vf-wp-training-category',$post_id);
$categorySlug = ! empty($category) ? strtolower(str_replace(' ', '_', $category)) : '';
$duration = get_field('vf-wp-training-duration',$post_id);
$durationSlug = ! empty($duration) ? strtolower(str_replace(' ', '_', $duration)) : '';
$additionalInfo = get_field('vf-wp-training-info',$post_id, false, false); 
$audience = get_field('vf-wp-training-audience',$post_id); 
$type = get_field('vf-wp-training-training_type',$post_id); 
$keywords = get_field('vf-wp-training-keywords',$post_id); 
$tags = get_field('vf-wp-training-tags',$post_id); 
$featured = get_field('vf-wp-training-featured',$post_id); 
$updated = get_field('vf-wp-training-updated',$post_id); 
$certificate = get_field('vf-wp-training-certificate',$post_id); 
$publish_date  = get_the_date('Y-m-d H:i:s');
$modified_date = get_the_modified_date('Y-m-d');
// Use modified date if different, otherwise use publish date
$update_date = ($publish_date !== $modified_date) ? $modified_date : $publish_date;
$update_value = ($updated == 1) ? $modified_date : $publish_date; // fallback to title for A–Z sorting

// prepare organisation lists safely
$org_list = array();
$org_list_upper = array();
if ( ! empty($organiser) && ! is_wp_error($organiser) ) {
    foreach ( $organiser as $org ) {
        $org_list[] = strtolower( str_replace( [' ', "’"], ['-', ''], $org->name ) );
        $org_list_upper[] = strtoupper( $org->name );
    }
}
$org_list_attr = esc_attr( implode(', ', $org_list) );
$org_list_text = esc_html( implode(', ', $org_list_upper) );

?>
<article class="vf-summary vf-summary--event | trainingOnDemand | vf-u-margin__bottom--0 articleBottomBorder" data-jplist-item>
  <div class="flexContainer">
    <div>
      <?php if (!empty($odType)) { ?>
      <p class="customFormat"><?php echo $odType; ?></p>
      <?php } ?>
      </div>
<div>
    <?php 
    if ($updated == 1 && $featured == 0) { 
        echo '<span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreen">Updated on '.date('M d, Y', strtotime($modified_date)).'</span>'; 
    } elseif ($featured == 1) { 
        echo '<span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreenDark">Featured course</span>'; 
    } 
    ?>
</div>
  </div>

  <h2 class="vf-summary__title | vf-u-margin__bottom--200 vf-u-margin__top--200 | search-data-od">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link | post-title"><?php the_title(); ?></a>
  </h2>
  <div>
    <div class="vf-content | wysiwyg-training-info | search-data-od">
      <?php
      $limitStr = 175;
      if (strlen($additionalInfo) > $limitStr) {
        $limitedString = substr($additionalInfo, 0, $limitStr);
        $lastSpace = strrpos($limitedString, ' ');
        if ($lastSpace !== false) {
            $limitedString = substr($limitedString, 0, $lastSpace);
        }
        echo '<p>' . $limitedString . ' ...</p>';
      } else {
        echo '<p>' . $additionalInfo . '</p>';
      } 
      ?>
    </div>
      <?php 

if ( !empty($tags) ) {
    // Split into array and trim spaces
    $tag_array = array_map('trim', explode(',', $tags));

    echo '<p class="vf-u-margin__top--0 vf-u-margin__bottom--400">';
    foreach ( $tag_array as $tag ) {
        if ( !empty($tag) ) {
            echo '<span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGrey | search-data-od">'
                 . esc_html($tag) .
                 '</span>';
        }
    }
    echo '</p>';
}
?>
<?php if (!empty($duration)) : ?>
    <p class="vf-summary__meta | vf-u-margin__top--200">
        <span>Duration:</span>&nbsp;
        <span class="vf-u-text-color--grey"><?php echo $duration; ?></span>
    </p>
<?php endif; ?>
  </div>


<!-- Hidden values used by jPList -->
<div class="vf-u-display-none">
  <span class="type-<?php echo esc_attr(strtolower(str_replace(' ', '_', $odType))); ?>">
    <?php echo esc_html($odType); ?>
  </span>

  <span class="duration-<?php echo esc_attr($durationSlug); ?>">
    <?php echo esc_html($duration); ?>
  </span>

<!-- publish date -->
<span class="added"><?php echo esc_html($publish_date); ?></span>

<!-- featured -->
<span class="featured"><?php echo $featured ? '1' : '0'; ?></span>
<!-- certificate -->
<span class="<?php echo $certificate ? 'certificate-1' : 'certificate-0'; ?>"><?php echo $certificate ? 'certificate-1' : 'certificate-0'; ?></span>

<span class="updated"><?php echo esc_html($update_value); ?></span>


  <!-- provider (class + visible text) -->
  <span class="provider-od-<?php echo $org_list_attr; ?>">
    <?php echo $org_list_text; ?>
  </span>

      <?php 

if ( !empty($keywords) ) {
    // Split into array and trim spaces
    $keyword_array = array_map('trim', explode(',', $keywords));

    echo '<p>';
    foreach ( $keyword_array as $keyword ) {
        if ( !empty($keyword) ) {
            echo '<span class="search-data-od">'
                 . esc_html($keyword) .
                 '</span>';
        }
    }
    echo '</p>';
}
?>
</div>

</article>
