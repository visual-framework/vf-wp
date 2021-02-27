<?php 

$delimiter = '&raquo;';
$name = 'Home'; //text for the 'Home' link
$currentBefore = '<li class="vf-breadcrumbs__item" aria-current="location">';
$currentAfter = '</li>';
$home = get_bloginfo('url');
$post = get_queried_object();

echo '<nav class="vf-breadcrumbs" aria-label="Breadcrumb">';
echo '<ul class="vf-breadcrumbs__list | vf-list vf-list--inline">';

// blog home page
if ( is_home() ) {
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
  $insites_url = get_post_type_archive_link('post');
  $insites_name = 'Insites';
  echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
   }

// community blog
if ( is_post_type_archive('community-blog') ) {
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
  $community_url = get_post_type_archive_link('community-blog');
  $community_name = 'Community blog';
  echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . $community_url . '" class="vf-breadcrumbs__link">' . $community_name . '</a></li>';
  echo '</ul>';
  echo '</nav>';
    }

// events
if ( is_post_type_archive('vf_event') ) {
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
  $event_url = get_post_type_archive_link('vf_event');
  $event_name = 'Internal events';
  echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
  echo '</ul>';
  echo '</nav>';

    }

//insites    
if ( is_post_type_archive('insites') ) {
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
  $event_url = get_post_type_archive_link('insites');
  $event_name = 'INsites';
  echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
  echo '</ul>';
  echo '</nav>';

    }

//documents   
if ( is_post_type_archive('documents') ) {
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
  $event_url = get_post_type_archive_link('documents');
  $event_name = 'Documents';
  echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
  echo '</ul>';
  echo '</nav>';

    }

if (is_tag()){
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
  echo $currentBefore . 'Tag: ';
  single_tag_title();
  echo $currentAfter;
  echo '</ul>';
  echo '</nav>';

}
/*
// topic taxonomy
if (is_tax('topic')){
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
  echo $currentBefore . 'Topic: ';
  single_tag_title();
  echo $currentAfter;
  echo '</ul>';
  echo '</nav>';

}

// embl location taxonomy
if (is_tax('embl-location')){
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
  echo $currentBefore . 'EMBL Location: &#39;';
  single_tag_title();
  echo '&#39;' . $currentAfter;
  echo '</ul>';
  echo '</nav>';

}
*/
if ( is_category() ) {
  global $wp_query;
  $cat_obj = $wp_query->get_queried_object();
  $thisCat = $cat_obj->term_id;
  $thisCat = get_category($thisCat);
  $parentCat = get_category($thisCat->parent);
  if ($thisCat->parent != 0)
  echo (get_category_parents($parentCat, TRUE, ''));
  echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
  $blog_url = get_post_type_archive_link('blog');
  $blog_name = 'Blog';
  echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . $blog_url . '" class="vf-breadcrumbs__link">' . $blog_name . '</a></li>';
  echo $currentBefore . 'Category: ';
  single_cat_title();
  echo $currentAfter;
  echo '</ul>';
  echo '</nav>';

}

if ( !is_home() && !is_archive('community-blog') && !is_archive('insites') && !is_archive('documents') && !is_archive('vf_event') && !is_front_page() || is_paged() ) {

  echo '
  <li class="vf-breadcrumbs__item">
  <a href="' . $home . '" class="vf-breadcrumbs__link" >' . $name . '</a></li>';
  // page no parent
  if ( is_page() && !$post->post_parent ) {
    echo $currentBefore;  
    single_post_title();
    echo $currentAfter;
    echo '</ul>';
    if( have_rows('vf-wp-intranet-related') ):
      echo '
      <span class="vf-breadcrumbs__heading">Related:</span>
      <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">';
      while( have_rows('vf-wp-intranet-related') ) : the_row();
      $related_link = get_sub_field('related-link'); ?>
        <li class="vf-breadcrumbs__item"><a href="<?php echo esc_url($related_link['url']); ?>" class="vf-breadcrumbs__link"><?php echo esc_html($related_link['title']); ?></a></li>
    <?php
      endwhile;
    echo '</ul>';
    else :
    endif;
    echo '</nav>';

  //page with a parent
  } elseif ( is_page() && $post->post_parent ) {
    $parent_id  = $post->post_parent;
    $breadcrumbs = array();
    while ($parent_id) {
      $page = get_page($parent_id);
      $breadcrumbs[] = '<li class="vf-breadcrumbs__item"><a href="' . get_permalink($page->ID) . '" class="vf-breadcrumbs__link">' . get_the_title($page->ID) . '</a></li>';
      $parent_id  = $page->post_parent;
    }
    $breadcrumbs = array_reverse($breadcrumbs);
    foreach ($breadcrumbs as $crumb) echo $crumb . '';
    echo $currentBefore;
    single_post_title();
    echo $currentAfter;
    echo '</ul>';
    if( have_rows('vf-wp-intranet-related') ):
      echo '
      <span class="vf-breadcrumbs__heading">Related:</span>
      <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">';
      while( have_rows('vf-wp-intranet-related') ) : the_row();
      $related_link = get_sub_field('related-link'); ?>
      <li class="vf-breadcrumbs__item"><a href="<?php echo esc_url($related_link['url']); ?>" class="vf-breadcrumbs__link"><?php echo esc_html($related_link['title']); ?></a></li>
    <?php
      endwhile;
    echo '</ul>';
    else :
    endif;
    echo '</nav>';
  }

  else {
    if ( is_day() ) {
    echo '<li class="vf-breadcrumbs__item"><a href="' . get_year_link(get_the_time('Y')) . '"class="vf-breadcrumbs__link">' . get_the_time('Y') . '</a></li>';
    echo '<li class="vf-breadcrumbs__item"><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '"class="vf-breadcrumbs__link">' . get_the_time('F') . '</a></li>';
    echo $currentBefore . get_the_time('d') . $currentAfter;

  } elseif ( is_month() ) {
    echo '<li class="vf-breadcrumbs__item"><a href="' . get_year_link(get_the_time('Y')) . '"class="vf-breadcrumbs__link">' . get_the_time('Y') . '</a></li>';
    echo $currentBefore . get_the_time('F') . $currentAfter;

  } elseif ( is_year() ) {
    echo $currentBefore . get_the_time('Y') . $currentAfter;

    // community blog single post
  } elseif ( is_singular('community-blog') ) {
    $community_url = get_post_type_archive_link('community-blog');
    $community_name = 'Community blog';
    echo '<li class="vf-breadcrumbs__item"><a href="' . $community_url . '" class="vf-breadcrumbs__link">' . $community_name . '</a></li>';
    echo '<li class="vf-breadcrumbs__item" aria-current="location">' . single_post_title() . '</li>';

    // event single post
  } elseif ( is_singular('vf_event') ) {
    $event_url = get_post_type_archive_link('vf_event');
    $event_name = 'Internal events';
    echo '<li class="vf-breadcrumbs__item"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
    echo '<li class="vf-breadcrumbs__item">' . single_post_title() . '</li>';
    
    // insites single post
  } elseif ( is_singular('insites') ) {
    $insites_url = get_post_type_archive_link('insites');
    $insites_name = 'INsites';
    echo '<li class="vf-breadcrumbs__item" false><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
    echo '<li class="vf-breadcrumbs__item" aria-current="location">' . single_post_title() . '</li>';

    // documents single post
  } elseif ( is_singular('documents') ) {
    $documents_url = get_post_type_archive_link('documents');
    $documents_name = 'Documents';
    echo '<li class="vf-breadcrumbs__item"><a href="' . $documents_url . '" class="vf-breadcrumbs__link">' . $documents_name . '</a></li>';
    echo '<li class="vf-breadcrumbs__item" aria-current="location">' . single_post_title() . '</li>';

    // blog single post
  } elseif ( is_single() && !is_attachment() ) {
    $blog_url = get_post_type_archive_link('post');
    $blog_name = 'Blog';
    echo '<li class="vf-breadcrumbs__item"><a href="' . $blog_url . '" class="vf-breadcrumbs__link">' . $blog_name . '</a></li>';
    echo '<li class="vf-breadcrumbs__item" aria-current="location">' . single_post_title() . '</li>';

  } elseif ( is_attachment() ) {
    $parent = get_post($post->post_parent);
    $cat = get_the_category($parent->ID); $cat = $cat[0];
    echo get_category_parents($cat, TRUE, '');
    echo '<li class="vf-breadcrumbs__item" aria-current="location"><a href="' . get_permalink($parent) . '"class="vf-breadcrumbs__link">' . $parent->post_title . '</a></li>';
    echo $currentBefore;
    the_title();
    echo $currentAfter;

  } elseif ( is_search() ) {
    echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;

  } elseif ( is_author() ) {
     global $author;
    $userdata = get_userdata($author);
    echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;

  } elseif ( is_404() ) {
    echo $currentBefore . 'Error 404' . $currentAfter;
  }

  if ( get_query_var('paged') ) {
    if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
    echo __('Page') . ' ' . get_query_var('paged');
    if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
  }

  echo '</ul>';
  echo '</nav>';
}
}


?>
