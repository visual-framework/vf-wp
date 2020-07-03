<!DOCTYPE html>
<html <?php language_attributes(); ?> class="vf-no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" sizes="180x180" href="https://www.embl.org/guidelines/design/assets/embl-favicon/assets/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://www.embl.org/guidelines/design/assets/embl-favicon/assets//favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://www.embl.org/guidelines/design/assets/embl-favicon/assets/favicon-16x16.png">
  <link rel="manifest" href="https://www.embl.org/guidelines/design/assets/embl-favicon/assets/site.webmanifest">
  <link rel="mask-icon" href="https://www.embl.org/guidelines/design/assets/embl-favicon/assets/safari-pinned-tab.svg" color="#ffffff">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  <!-- Search indexing optimisations -->
  <meta class="swiftype" name="what" data-type="string" content="News" />
  <?php
    // https://swiftype.com/documentation/site-search/crawler-configuration/meta-tags#thumbnails
    if (get_the_post_thumbnail_url()) {
      echo '<meta class="swiftype" name="image" data-type="enum" content="' . get_the_post_thumbnail_url() . '" />';
    }
  ?>

<?php wp_head(); ?>
</head>
<body class="vf-body "<?php body_class(); ?>>
