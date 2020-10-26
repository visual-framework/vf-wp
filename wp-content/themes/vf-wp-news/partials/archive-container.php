<div class="vf-u-background-color-ui--off-white | vf-u-padding__top--400 | vf-u-padding__bottom--400 | vf-u-margin__bottom--200  | archive-container">
    <div class="vf-grid" style="--page-grid-gap: 0px;">
      <div class="vf-links vf-links--tight vf-links__list--s">
        <h3 class="vf-links__heading">Last 6 months:</h3>
      </div>
      <div class="vf-links vf-links--tight vf-links__list--s">
        <ul class="vf-links__list vf-links__list--secondary | vf-list">
<?php 
$month = array(
    'type'            => 'monthly',
	'limit'           => '6',
    'show_post_count' => true,
    'echo'            => 1,
    'order'           => 'DESC',
	'before' => '<li class="vf-list__item">',
    'after'  => '</li>'
);
wp_get_archives( $month );
?>
        </ul>
      </div>

      <div class="vf-links vf-links--tight vf-links__list--s">
        <h3 class="vf-links__heading">By year:</h3>
      </div>
      <div class="vf-links vf-links--tight vf-links__list--s | ">
        <ul class="vf-links__list vf-links__list--secondary | vf-list">
<?php 
$year = array(
	'format' => 'custom',
    'type'            => 'yearly',
    'show_post_count' => true,
    'echo'            => 1,
    'order'           => 'DESC',
	'before' => '<li class="vf-list__item">',
    'after'  => '</li>'
);
wp_get_archives( $year );
?>
        </ul>
      </div>

      <div class="vf-links vf-links--tight vf-links__list--s">
        <h3 class="vf-links__heading">By topics:</h3>
      </div>
      <div class="vf-links vf-links--tight vf-links__list--s">
        <ul class="vf-links__list vf-links__list--secondary | vf-list">

  <?php

    $cats= get_categories();
    if ($cats) {
    $output = array();
    
   foreach($cats as $cat) {
      $output[] = 
      '<li class="vf-list__item">
      <a  href="' . get_term_link($cat->term_id) . '" class="vf-link__list' . '">' . $cat->name . '</a>'. ' ' . '(' . $cat->count . ')' . '</li>';
   }
   echo implode('', $output); }
 ?>
        </ul>
      </div>
    </div>
</div>
