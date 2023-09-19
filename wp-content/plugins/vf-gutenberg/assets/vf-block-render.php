<!DOCTYPE html>
<html <?php language_attributes(); ?> class="vf-no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php

// Output theme styles
global $wp_styles;
wp_enqueue_scripts();
if ($wp_styles instanceof WP_Styles) {
  foreach ($wp_styles->queue as $style) {
    $style_obj = $wp_styles->registered[$style];
    $href = esc_url($style_obj->src);
    $media = esc_attr($style_obj->args);
    echo "<link rel='stylesheet' id='$style' href='$href' type='text/css' media='$media' />\n";
  }
}

$path = get_template_directory_uri();
$path = "{$path}/assets/assets/vfwp-gutenberg-iframe/vfwp-gutenberg-iframe.css";
echo '<link rel="stylesheet" href="' . esc_url($path) . '">';

// Add iframe styles
echo '<style id="vf-block-render-css">';
echo file_get_contents(
  plugin_dir_path(__FILE__) . '/vf-block-render.css',
  true
);
echo '</style>';

// Add iframe script
echo '<script id="vf-block-render-js">';
echo file_get_contents(
  plugin_dir_path(__FILE__) . '/vf-block-render.js',
  true
);
echo '</script>';

?>
</head>
<body>
<!--[BLOCKHTML]--->
</body>
</html>
