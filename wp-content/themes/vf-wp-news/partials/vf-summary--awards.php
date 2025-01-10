<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$site = get_the_terms( $post->ID , 'award-site' );
$unit = get_the_terms( $post->ID , 'award-unit' );
$type = get_the_terms( $post->ID , 'award-type' );


?>

<article class="vf-summary vf-summary--news allItems vf-u-margin__bottom--1200" data-jplist-item>
  <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" data-date="<?php the_time('Ymd'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>

  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title | vf-u-margin__bottom--200 vf-u-margin__top--200 | search-data"">
    <a href=" <?php the_permalink(); ?>" class="vf-summary__link">
    <?php echo $title; ?>
    </a>
  </h3>
  <div>
    <div class="vf-content | wysiwyg-award-info | search-data">
      <p><?php echo get_the_excerpt(); ?></p>
    </div>
    <p class="vf-summary__meta | vf-u-margin__bottom--200" id="awardsMeta">
        <?php if (($unit)) { ?>
        <span>Unit:</span>&nbsp;
        <span class="vf-u-text-color--grey | vf-u-margin__right--600">
          <?php $uni_list = [];
          foreach( $unit as $uni ) { 
            $uni_list[] = $uni->name; }
            echo implode(', ', $uni_list); ?></span>
        <?php } ?>

      <?php if (($site)) { ?>
      <span>Site:</span>&nbsp;
      <span class="vf-u-text-color--grey | vf-u-margin__right--600">
        <?php $loc_list = [];
        foreach( $site as $loc ) { 
          $loc_list[] = $loc->name; }
          echo implode(', ', $loc_list); ?></span>
      <?php } ?>
      
    </p>
    <div>
      <?php if (($type)) { ?>
      <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreen">
        <?php $typ_list = [];
        foreach( $type as $typ ) { 
          $typ_list[] = $typ->name; }
          echo implode(', ', $typ_list); ?></span>
      <?php } ?>
    </div>
  </div>

  <!-- for filtering -->
  <div class="vf-u-display-none">
    <span class="year year-<?php the_time('Y'); ?>"><?php the_time('Y'); ?></span>
    <span class="type type-<?php 
        $typ_list = [];
        foreach( $type as $typ ) { 
          $typ_list[] = strtolower(str_replace(' ', '-', $typ->slug)); }
          echo implode(', ', $typ_list); ?>">
      <?php 
        $typ_list_upper = [];
        foreach( $type as $typ ) { 
          $typ_list_upper[] = strtoupper($typ->name); }
          echo implode(', ', $typ_list_upper); ?>"
    </span>
    <span class="unit unit-<?php 
        $uni_list = [];
        foreach( $unit as $uni ) { 
          $uni_list[] = strtolower(str_replace(' ', '-', $uni->slug)); }
          echo implode(', ', $uni_list); ?>">
      <?php 
        $uni_list_upper = [];
        foreach( $unit as $uni ) { 
          $uni_list_upper[] = strtoupper($uni->name); }
          echo implode(', ', $uni_list_upper); ?>"
    </span>
    <span class="site site-<?php 
        $loc_list = [];
        foreach( $site as $loc ) { 
          $loc_list[] = strtolower(str_replace(' ', '-', $loc->slug)); }
          echo implode(', ', $loc_list); ?>">
      <?php 
        $loc_list_upper = [];
        foreach( $site as $loc ) { 
          $loc_list_upper[] = strtoupper($loc->name); }
          echo implode(', ', $loc_list_upper); ?>"
    </span>
  </div>

</article>
