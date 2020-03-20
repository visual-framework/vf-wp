<div class="vf-inlay__content | vf-u-background-color-ui--off-white | vf-u-padding__top--md | vf-u-padding__bottom--md | vf-u-margin__bottom--sm  | archive-container">
  <main class="vf-inlay__content--full-width | vf-u-margin--0">
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
			$cat = array(
	'title_li' => '',
  'show_count' => true,
  'before' => '<li class="vf-list__item">',
  'after'  => '</li>'
);
wp_list_categories( $cat );
?>
        </ul>
      </div>
    </div>
  </main>
</div>
