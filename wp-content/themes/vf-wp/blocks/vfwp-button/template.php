<?php
// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$button_link = get_field('button_link');
$button_style = get_field('button_style');
$button_size = get_field('button_size');

// Determine size class
$size = '';
if (in_array($button_size, ['sm','lg'], true)) {
    $size = " vf-button--{$button_size}";
}

// Function to output a banner message in Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
    if (!$is_preview) return;
    ?>
    <div class="vf-banner vf-banner--alert vf-banner--<?php echo esc_attr($modifier); ?>">
        <div class="vf-banner__content">
            <p class="vf-banner__text"><?php echo esc_html($message); ?></p>
        </div>
    </div>
    <!--/vf-banner-->
    <?php
};

// Safe extraction of button link fields
$button_url = '';
$button_title = '';
$button_target = '_blank'; // default target

if (!empty($button_link) && is_array($button_link)) {
    $button_url = $button_link['url'] ?? '';
    $button_title = $button_link['title'] ?? '';
    $button_target = $button_link['target'] ?? '_blank';
} elseif (!empty($button_link) && is_string($button_link)) {
    $button_url = $button_link;
    $button_title = $button_link;
    $button_target = '_blank';
}

if (empty($button_url) || empty($button_title)) {
    $admin_banner(__('Please enter content for this block.', 'vfwp'));
    return;
}
?>

<a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>">
    <button class="vf-button vf-button--<?php echo esc_attr($button_style) . esc_attr($size); ?>">
        <?php echo esc_html($button_title); ?>
    </button>
</a>
<!--/vf-button-->
