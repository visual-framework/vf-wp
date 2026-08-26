<?php
$now = new DateTime();
$post_id = get_the_ID();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$location = get_the_terms( $post->ID , 'event-location' );
$start_date = get_field('vf-wp-training-start_date',$post_id);
$start_time = get_field('vf-wp-training-start_time',$post_id);
$start = DateTime::createFromFormat('Ymd', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf-wp-training-end_date',$post_id);
$end_time = get_field('vf-wp-training-end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('Ymd', $end_date);
$registrationStatus = get_field('vf-wp-training-registration-status',$post_id); 
$registrationDeadline = get_field('vf-wp-training-registration-deadline',$post_id); 
$deadlineDate = new DateTime($registrationDeadline);
$registrationDeadlineFormatted = $deadlineDate->format('Ymd');
$venue = get_field('vf-wp-training-venue',$post_id);
$fee = get_field('vf-wp-training-fee',$post_id);
$feeSlug = strtolower(str_replace(' ', '_', $fee));
$odType = get_field('vf-wp-training-on_demand_type',$post_id);
$category = get_field('vf-wp-training-category',$post_id);
$categorySlug = strtolower(str_replace(' ', '_', $category));
$additionalInfo = get_field('vf-wp-training-info',$post_id, false, false); 
$audience = get_field('vf-wp-training-audience',$post_id); 
$type = get_field('vf-wp-training-training_type',$post_id); 
$keywords = get_field('keyword',$post_id); 



?>
<article class="vf-summary vf-summary--event | trainingOnDemand | vf-u-margin__bottom--0" data-jplist-item>

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
  </div>
  <div>
    <?php if (!empty($category)) { ?>
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--800"><span
        class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue"><?php echo $category; ?></span>
      <?php } ?>
      <?php if (($organiser)) { ?>
        <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGrey">
        <?php
        foreach( $organiser as $org ) { 
          $prov_list = [];
          if ($org->name === 'EMBL Centres') {
            $prov_list[] = $emblCentres; }
            else {
          $prov_list[] = strtoupper($org->name); } }
          echo implode(', ', $prov_list); ?></span>
      <?php } ?>
      <?php if (!empty($odType)) { ?>
      <span class="customFormat"><?php echo $odType; ?></span></p>
      <?php } 
      else { echo '</p>'; }?>
  </div>
  <!-- for filtering -->
  <div class="vf-u-display-none">
    <span class="type-<?php echo strtolower(str_replace(' ', '_', $odType)); ?>"><?php echo $odType; ?></span>
    <span class="category-od-<?php echo $categorySlug; ?>"><?php echo $category; ?></span>
    <span class="keywords | search-data-od"><?php echo $keywords; ?></span>
    <span class="provider-od-<?php 
        $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = strtolower(str_replace([' ', "’"], ['-', ''], $org->name)); }
          echo implode(', ', $org_list); ?>">
        <?php 
        foreach( $organiser as $org ) { 
          $org_list_upper = [];
          $org_list_upper[] = strtoupper($org->name); }
          echo implode(', ', $org_list_upper); ?>"      
      </span>
  </div>
  <?php if ($onDemandLoop->current_post +1 < $onDemandLoop->post_count) {
    echo '<hr class="vf-divider">';
} ?>
</article>
