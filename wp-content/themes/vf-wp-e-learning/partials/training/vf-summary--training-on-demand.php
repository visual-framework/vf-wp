<?php
$now           = new DateTime();
$post_id       = get_the_ID();
$current_date  = $now->format('Ymd');
$organiser     = get_the_terms($post->ID, 'training-organiser');

$odType        = get_field('vf-wp-training-on_demand_type', $post_id);
$odSubType     = get_field('vf-wp-training-course_type', $post_id);
$category      = get_field('vf-wp-training-topic', $post_id);
$duration      = get_field('vf-wp-training-duration', $post_id);
$additionalInfo= get_field('vf-wp-training-info', $post_id, false, false);
$audience      = get_field('vf-wp-training-audience', $post_id);
$type          = get_field('vf-wp-training-training_type', $post_id);
$keywords      = get_field('vf-wp-training-keywords', $post_id);
$tags          = get_field('vf-wp-training-tags', $post_id);
$featured      = get_field('vf-wp-training-featured', $post_id);
$updated       = get_field('vf-wp-training-updated', $post_id);
$certificate   = get_field('vf-wp-training-certificate', $post_id);

$categorySlug  = !empty($category) ? strtolower(str_replace(' ', '_', $category)) : '';
$durationSlug  = !empty($duration) ? strtolower(str_replace(' ', '_', $duration)) : '';

$publish_date  = get_the_date('Y-m-d H:i:s');
$modified_date = get_the_modified_date('Y-m-d');

$publish_timestamp = strtotime($publish_date);
$two_days_ago      = strtotime('-2 days');

// Use modified date if different, otherwise use publish date
$update_date   = ($publish_date !== $modified_date) ? $modified_date : $publish_date;
$update_value = ($updated == 1) ? $modified_date : '';

// Prepare organisation lists safely
$org_list       = [];
$org_list_upper = [];

if (!empty($organiser) && !is_wp_error($organiser)) {
    foreach ($organiser as $org) {
        $org_list[]       = strtolower(str_replace([' ', "’"], ['-', ''], $org->name));
        $org_list_upper[] = strtoupper($org->name);
    }
}

$org_list_attr = esc_attr(implode(', ', $org_list));
$org_list_text = esc_html(implode(', ', $org_list_upper));
?>

<article class="vf-summary vf-summary--event | trainingOnDemand | vf-u-margin__bottom--0 articleBottomBorder" data-jplist-item>
  <div class="flexContainer">
    <div>
      <?php if (!empty($odType)) : ?>
        <p class="customFormat"><?php echo esc_html($odType); ?></p>
      <?php endif; ?>
    </div>

    <div>
  <?php if ($publish_timestamp >= $two_days_ago) : ?>
    <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeDarkBlue">
      New
    </span>
  <?php elseif ($updated == 1 && $featured == 0) : ?>
    <span class="customFormat vf-u-text-color--grey">
      Updated on <?php echo esc_html(date('M d, Y', strtotime($modified_date))); ?>
    </span>
  <?php elseif ($featured == 1) : ?>
    <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreenDark">
      Featured course
    </span>
  <?php endif; ?>
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
          echo '<p>' . esc_html($limitedString) . ' ...</p>';
      } else {
          echo '<p>' . esc_html($additionalInfo) . '</p>';
      }
      ?>
    </div>

    <?php if (!empty($tags)) : ?>
      <?php
      $tag_array = array_map('trim', explode(',', $tags));
      ?>
      <p class="vf-u-margin__top--0 vf-u-margin__bottom--400">
        <?php foreach ($tag_array as $tag) : ?>
          <?php if (!empty($tag)) : ?>
            <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGrey | search-data-od">
              <?php echo esc_html($tag); ?>
            </span>
          <?php endif; ?>
        <?php endforeach; ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($duration)) : ?>
      <p class="vf-summary__meta | vf-u-margin__top--200">
        <span>Duration:</span>&nbsp;
        <span class="vf-u-text-color--grey"><?php echo esc_html($duration); ?></span>
      </p>
    <?php endif; ?>
  </div>

  <!-- Hidden values used by jPList -->
  <div class="vf-u-display-none">
    <span class="type-<?php echo esc_attr(strtolower(str_replace(' ', '_', $odType))); ?>">
      <?php echo esc_html($odType); ?>
    </span>
    <span class="subtype-<?php echo esc_attr(strtolower(str_replace(' ', '_', $odSubType))); ?>">
      <?php echo esc_html($odSubType); ?>
    </span>

    <span class="duration-<?php echo esc_attr($durationSlug); ?>">
      <?php echo esc_html($duration); ?>
    </span>

    <!-- publish date -->
    <span class="added"><?php echo esc_html($publish_date); ?></span>
<?php if ($audience && is_array($audience)) {
    foreach ($audience as $aud) {
        // Convert to lowercase and replace spaces with hyphens
        $class = 'audience-' . sanitize_title($aud); 
        echo '<span class="' . esc_attr($class) . '">' . esc_html($aud) . '</span> ';
    }
} ?>
    <!-- featured -->
    <span class="featured"><?php echo $featured ? '1' : '0'; ?></span>
<span class="category-<?php echo $categorySlug; ?>"><?php echo $category; ?></span>
    <!-- certificate -->
    <span class="<?php echo $certificate ? 'certificate-1' : 'certificate-0'; ?>">
      <?php echo $certificate ? 'certificate-1' : 'certificate-0'; ?>
    </span>

    <span class="updated"><?php echo esc_html($update_value); ?></span>

    <!-- provider (class + visible text) -->
    <span class="provider-od-<?php echo $org_list_attr; ?>">
      <?php echo $org_list_text; ?>
    </span>

    <?php if (!empty($keywords)) : ?>
      <?php
      $keyword_array = array_map('trim', explode(',', $keywords));
      ?>
      <p>
        <?php foreach ($keyword_array as $keyword) : ?>
          <?php if (!empty($keyword)) : ?>
            <span class="search-data-od"><?php echo esc_html($keyword); ?></span>
          <?php endif; ?>
        <?php endforeach; ?>
      </p>
    <?php endif; ?>
  </div>
</article>
