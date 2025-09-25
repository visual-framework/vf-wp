<?php
$now = new DateTime();
$post_id = get_the_ID();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$odType = get_field('vf-wp-training-on_demand_type',$post_id);
$category = get_field('vf-wp-training-category',$post_id);
$categorySlug = ! empty($category) ? strtolower(str_replace(' ', '_', $category)) : '';
$additionalInfo = get_field('vf-wp-training-info',$post_id, false, false); 
$audience = get_field('vf-wp-training-audience',$post_id); 
$type = get_field('vf-wp-training-training_type',$post_id); 
$keywords = get_field('vf-wp-training-keywords',$post_id); 
$publish_date  = get_the_date('Y-m-d H:i:s');
$modified_date = get_the_modified_date('Y-m-d H:i:s');
// Use modified date if different, otherwise use publish date
$update_date = ($publish_date !== $modified_date) ? $modified_date : $publish_date;
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
<article class="vf-summary vf-summary--event | trainingOnDemand | vf-u-margin__bottom--0" data-jplist-item>
      <?php if (!empty($odType)) { ?>
      <p class="customFormat"><?php echo $odType; ?></p>
      <?php } ?>
  <h2 class="vf-summary__title | vf-u-margin__bottom--200 vf-u-margin__top--200 | search-data-od">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
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

if ( !empty($keywords) ) {
    // Split into array and trim spaces
    $keyword_array = array_map('trim', explode(',', $keywords));

    echo '<p class="vf-u-margin__top--0 vf-u-margin__bottom--800">';
    foreach ( $keyword_array as $keyword ) {
        if ( !empty($keyword) ) {
            echo '<span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGrey | search-data-od">'
                 . esc_html($keyword) .
                 '</span>';
        }
    }
    echo '</p>';
}
?>
  </div>
  <div>
    <?php /* if (!empty($category)) { ?>
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--800"><span
        class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue"><?php echo $category; ?></span>
      <?php } ?>
      <?php if (($organiser)) { ?>
        <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGrey">
        <?php
        foreach( $organiser as $org ) { 
          $prov_list = [];
          $prov_list[] = strtoupper($org->name);  }
          echo implode(', ', $prov_list); ?></span>
      <?php } */?>

      <?php // else { echo '</p>'; }?>
  </div>
<!-- Hidden values used by jPList -->
<div class="vf-u-display-none">
  <span class="type-<?php echo esc_attr(strtolower(str_replace(' ', '_', $odType))); ?>">
    <?php echo esc_html($odType); ?>
  </span>

  <span class="category-od-<?php echo esc_attr($categorySlug); ?>">
    <?php echo esc_html($category); ?>
  </span>

<!-- publish date -->
<span class="added"><?php echo esc_html($publish_date); ?></span>

<!-- modified date (or fallback to publish date) -->
<span class="update"><?php echo esc_html($update_date); ?></span>


  <!-- title used for alphabetical sort (plain text) -->
  <span class="post-title"><?php echo esc_html( get_the_title() ); ?></span>

  <!-- provider (class + visible text) -->
  <span class="provider-od-<?php echo $org_list_attr; ?>">
    <?php echo $org_list_text; ?>
  </span>
</div>
  <?php if ($onDemandLoop->current_post +1 < $onDemandLoop->post_count) {
    echo '<hr class="vf-divider">';
} ?>
</article>
