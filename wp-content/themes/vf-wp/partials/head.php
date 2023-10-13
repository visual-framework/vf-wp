<!DOCTYPE html>
<html <?php language_attributes(); ?> class="vf-no-js">
<head>
  <link rel="shortcut icon" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/favicon-16x16.png">
  <link rel="manifest" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/site.webmanifest">
  <link rel="mask-icon" href="https://assets.emblstatic.net/vf/v2.5.0/assets/embl-favicon/assets/safari-pinned-tab.svg" color="#ffffff">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta class="swiftype" name="page-description" data-type="string" content="<?php echo swiftype_metadata_description(); ?>" />
  <?php
  // don't index trec outreach events
  global $wp;
  $url =  home_url( $wp->request ); 
  if(strpos($url, 'outreach/events') == true && is_singular('vf_event') ) {
   echo  '<meta name="robots" content="noindex">';
  } ?>

  <?php if(!is_singular('vf_event')) {
   echo  '<meta class="swiftype" name="what" data-type="string" content="Page" />';
  } else {
    echo '<meta class="swiftype" name="what" data-type="string" content="Events" />';
  } ?>


  <!-- Social meta tags for events -->
<?php
if(is_singular('vf_event')) {
  $card_url    = get_permalink();
  $card_title  = get_the_title();
  $card_desc   = get_the_excerpt();
  $card_name   = str_replace('@', '', get_the_author_meta('twitter')); 
  $card_thumb = get_field('vf_event_hero', $post->post_parent);
  if(!empty($card_thumb)){
  $card_thumb = wp_get_attachment_url($card_thumb['ID'], 'full', false);
  } ?>
  <!-- Twitter -->
  <meta name="twitter:card" value="summary" />
  <meta name="twitter:url" value="<?php echo $card_url; ?>" />
  <meta name="twitter:title" value="<?php echo $card_title; ?>" />
  <meta name="twitter:description" value="<?php echo $card_desc; ?>" />
  <meta name="twitter:image" value="<?php echo $card_thumb; ?>" />
  <meta name="twitter:site" value="@emblevents" />
<?php
if($card_name) { ?>
  <meta name="twitter:creator" value="@<?php echo $card_name; ?>" />
<?php } ?>
  <!-- Facebook -->
  <meta property="og:url" content="<?php echo $card_url; ?>" />
  <meta property="og:title" content="<?php echo $card_title; ?>" />
  <meta property="og:description" content="<?php echo $card_desc; ?>" />
  <meta property="og:image" content="<?php echo $card_thumb; ?>" />
<?php } ?>
  <!-- Search indexing optimisations -->
  <?php
    // https://swiftype.com/documentation/site-search/crawler-configuration/meta-tags#thumbnails
    if (get_the_post_thumbnail_url()) {
      echo '<meta class="swiftype" name="image" data-type="enum" content="' . get_the_post_thumbnail_url() . '" />';
    }

  ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
