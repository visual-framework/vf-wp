<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};

if (
  ! have_rows('list_items')
) {
  $admin_banner(__('Please add an item to the list.', 'vfwp'));
  return;
}
?>

<div class="vf-activity">
  <?php if ( ! empty($heading)) { ?>
    <p class="vf-activity__date"><?php echo $heading; ?></p>
  <?php } ?>
  <?php if (have_rows('list_items')) { ?>
    <ul class="vf-activity__list | vf-list">

    <?php
    while (have_rows('list_items')) {
      the_row();
      $text = get_sub_field('text', false, false);
      $additional_text = get_sub_field('additional_text');
      
    ?>

    <li class="vf-activity__item">
      <?php echo ($text); ?>
      <?php if( ! empty ($additional_text)) { ?>
        <blockquote class="vf-activity__blockquote | vf-blockquote"><?php echo esc_html($additional_text); ?></blockquote>      
      <?php } }?>

    </li>
    <!--/vf-list-item-->
      <?php } ?>
  </ul>
</div>
<!--/vf-activity-list-->
