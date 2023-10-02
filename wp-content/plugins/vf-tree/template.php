<?php

global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
?>

<div class="vf-tree vf-tree__item--expanded" data-vf-js-tree="" aria-expanded="true"
  data-vf-js-button-hidden-open-text="Open Tree" data-vf-js-button-hidden-close-text="Close Tree"
  data-vf-js-tree--collapsed="false">
  <div class="vf-tree__inner" role="main">
    <?php
    // Check for rows (parent repeater)
    if (have_rows('vf_tree_top_level')) :
      ?>
    <ul class="vf-tree__list vf-tree__list--1 | vf-list" aria-role="tree">
      <?php
        // Loop through rows (parent repeater)
        while (have_rows('vf_tree_top_level')) : the_row();
          $topLink = get_sub_field('vf_tree_top_menu_item');
          $selectClass = '';

          if (!empty($topLink)) {
          $linkUrl = rtrim($topLink['url'], '/');
          if($current_url == $linkUrl) {
            $selectClass = ' vf-tree__item--selected';
          }
          ?>
      <li class="vf-tree__item vf-tree--collapsed <?php echo esc_attr($selectClass); ?>"
        data-vf-js-tree--collapsed="true" data-vf-js-tree="" aria-role="treeitem" aria-expanded="">
        <a href="<?php echo esc_url($linkUrl ); ?>" class="vf-tree__link"
          <?php if ($topLink['url'] == '#') { echo 'style="color: #000; cursor: text; text-decoration: none;" onclick="return false;"'; } ?>><?php echo esc_html($topLink['title']); ?>
          <?php
            // Check for rows (sub repeater)
            if (have_rows('vf_tree_sub_menu')) :?>
          <button class="vf-button vf-tree__button" data-vf-js-tree--button="">
            <p data-vf-js-tree-button-hidden-text="" class="vf-u-sr-only">Open Tree</p>
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M19.5,12a2.3,2.3,0,0,1-.78,1.729L7.568,23.54a1.847,1.847,0,0,1-2.439-2.773l9.752-8.579a.25.25,0,0,0,0-.376L5.129,3.233A1.847,1.847,0,0,1,7.568.46l11.148,9.808A2.31,2.31,0,0,1,19.5,12Z">
              </path>
            </svg>
          </button></a>
        <ul class="vf-tree__list vf-tree__list--additional vf-tree__list--2 | vf-list" aria-role="group">
          <?php
                // Loop through rows (sub repeater)
                while (have_rows('vf_tree_sub_menu')) : the_row();
                  $subLink = get_sub_field('vf_tree_sub_menu_item');
                  $selectClass = '';
                  $linkUrl2 = rtrim($subLink['url'], '/');
                  if($current_url == $linkUrl2) {
                    $selectClass = ' vf-tree__item--selected';
                  }
                  ?>
          <li class="vf-tree__item vf-tree--collapsed <?php echo esc_attr($selectClass); ?>"
            data-vf-js-tree--collapsed="true" data-vf-js-tree="" aria-role="treeitem" aria-expanded="false"><a
              href="<?php echo esc_url($linkUrl2); ?>" class="vf-tree__link"><?php echo esc_html($subLink['title']); ?>
          </li></a>
          <?php  endwhile; ?>
        </ul>
        <?php else : ?>
        </a>
        <?php endif; // if( have_rows('vf_tree_sub_menu') ): ?>
      </li>
      <?php  
      } 
      endwhile; // while( have_rows('vf_tree_top_level') ): ?>
    </ul>
    <?php else : ?>
    <?php
    // Block preview in Gutenberg editor
    $is_preview = isset($is_preview) && $is_preview;
    
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
    <?php }; 
      $admin_banner(__('This is a placeholder for the vf-tree component', 'vfwp'));
      return;
    ?>

    <?php endif; // if( have_rows('vf_tree_top_level') ): ?>
  </div><!-- #content -->
</div><!-- #primary -->

<style>
  .vf-tree__link {
    color: #3b6fb6;
  }

</style>
