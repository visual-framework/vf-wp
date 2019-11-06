<?php
/**
 * Theme Pagination
 * Sub-class initiated by `VF_Theme`
 * Provides the global `vf_pagination` template function
 * for pagination with Visual Framework markup.
 */

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Theme_Pagination') ) :

class VF_Theme_Pagination {

  /**
   * Template function to render posts pagination using Visual Framework markup
   * https://visual-framework.github.io/vf-core/components/detail/vf-pagination.html
   * https://codex.wordpress.org/Function_Reference/paginate_links
   */
  static function render() {

    $prev_text = __('Previous', 'vfwp');
    $next_text = __('Next', 'vfwp');

    // Generate and capture default markup
    ob_start();
    echo paginate_links(
      array(
        'type'      => 'list',
        'prev_text' => $prev_text,
        'next_text' => $next_text,
        'before_page_number' => '<span class="vf-sr-only">Page </span>'
      )
    );
    $html = ob_get_contents();
    ob_end_clean();

    // No pagination?
    if (empty(trim(strip_tags($html)))) {
      return;
    }

    $classes = array(
      'list'  => 'vf-pagination__list',
      'item'  => 'vf-pagination__item',
      'link'  => 'vf-pagination__link',
      'label' => 'vf-pagination__label'
    );

    // Add list class
    $html = preg_replace(
      '#<ul[^>]*?>#',
      '<ul class="'.$classes['list'].'">',
      $html
    );

    // Add list item class
    $html = preg_replace(
      '#<li[^>]*?>#',
      '<li class="'.$classes['item'].'">',
      $html
    );

    // Add link class
    // WordPress is using both single and double quotes
    // for html attributes (U+0027, U+0022)
    $html = preg_replace(
      '#<a[^>]*?href=[\'"](.+?)[\'"][^>]*?>#',
      '<a href="$1" class="'.$classes['link'].'">',
      $html
    );

    // Add label class
    $html = preg_replace(
      '#<span[^>]*?>(&hellip;</span>)#',
      '<span class="'.$classes['label'].'">$1',
      $html
    );

    // Replace markup for current item
    $html = preg_replace(
      '#<li[^>]*?><span[^>]*?aria-current=[\'"]page[\'"][^>]*?>(.*?</span></li>)#',
      '<li class="'.$classes['item'].' '.$classes['item'].'--is-active">
        <span class="'.$classes['label'].'" aria-current="page">$1',
      $html
    );

    // Replace markup for "Previous" item
    $html = preg_replace(
      '#<li[^>]*?>(<a[^>]*?>' . preg_quote($prev_text) . '</a></li>)#',
      '<li class="'.$classes['item'].' '.$classes['item'].'--jump-back">$1',
      $html
    );

    // Replace markup for "Next" item
    $html = preg_replace(
      '#<li[^>]*?>(<a[^>]*?>' . preg_quote($next_text) . '</a></li>)#',
      '<li class="'.$classes['item'].' '.$classes['item'].'--jump-forward">$1',
      $html
    );

  ?>
  <nav class="vf-pagination" aria-label="Pagination">
  <?php echo $html; ?>
  </nav>
  <?php

  }

} // VF_Theme_Pagination

endif;

/**
 * Template function to render pagination
 */
if ( ! function_exists('vf_pagination')) {
  function vf_pagination() {
    VF_Theme_Pagination::render();
  }
}

?>
