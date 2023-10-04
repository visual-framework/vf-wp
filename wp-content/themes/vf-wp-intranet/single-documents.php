<?php

$tags = get_the_tags($post->ID);
$related_documents = get_field('related_documents');
$title = esc_html(get_the_title());
$file_type = get_field('file_type');
$file = get_field('upload_file');
$locations = get_field('embl_location');
$update = get_field('latest_update');
$annexes = get_field('annexes');

get_header();

?>

<div class="vf-grid | vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="title-container">
      <?php /*
    $tags = get_the_tags($post->ID);
    if ($tags) {
    $tagslist = array();
    foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-badge | vf-u-margin__bottom--200">' . $tag->name . '</a>';
    }
    echo implode('  ', $tagslist);
    } */
    ?>

      <p class="vf-summary__date | vf-u-margin__bottom--0">
        <time title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      </p>
      <h1 class="vf-text vf-text-heading--2"><?php the_title(); ?></h1>

      <?php the_content(); ?>

      <?php if( have_rows('annexes') ): ?>
      <p class="vf-summary__meta">
        Available in language(s):&nbsp;
        <?php while( have_rows('annexes') ): the_row();
        $language = get_sub_field('language');
        $language_file = get_sub_field('file');?>
        <a class="vf-link" href="<?php echo $language_file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
        <?php endwhile; ?>
      </p>
      <?php endif; ?>

      <?php if (($locations)) { ?>
      <p class="vf-summary__meta">
        <span>EMBL site:</span>&nbsp;
        <span class="vf-u-text-color--grey | location">
          <?php $location_list = [];
        foreach( $locations as $location ) { 
          switch ($location->name) {
            case "Heidelberg":
              $location->name = 'HD';
              break;
            case "Hamburg":
              $location->name = 'HH';
              break;
            case "Rome":
              $location->name = 'RM';
              break;
            case "Grenoble":
              $location->name = 'GR';
              break;
            case "Barcelona":
              $location->name = 'BCN';
              break;
            case "EMBL-EBI":
              $location->name = 'EBI';
                break;
          }
          $location_list[] = $location->name; }
          echo implode(' | ', $location_list); ?>

        </span>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php } ?>
      </p>

      <?php if ($file_type) { ?>
      <p class="vf-summary__meta">File type:
        <span class="vf-u-text-color--grey"><?php echo esc_html($file_type); ?></span></p>
      <?php }  ?>

      <?php if ($update) { ?>
      <p class="vf-summary__meta"><strong>Updated: </strong>
        <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></p>
      <?php }  

    if( $file ): ?>
      <a href="<?php echo $file['url']; ?>"
        class="vf-button vf-button--primary vf-button--outline vf-button--sm">Download</a>
      <?php endif; ?>

      <?php
    if( $related_documents ): ?>
      <hr class="vf-divider vf-u-margin__top--600">
      <div class="vf-links">
        <h3 class="vf-links__heading">Related documents:</h3>
        <ul class="vf-links__list | vf-list">
          <?php foreach( $related_documents as $related_document ): 
        $permalink = get_permalink( $related_document->ID );
        $title = get_the_title( $related_document->ID );
        ?>
          <li class="vf-list__item">
            <a class="vf-list__link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div></div>
</div>

<?php 

get_footer(); 

?>
