<?php

get_header();

$types = get_the_terms( $post->ID , 'document_type' );
$topics = get_the_terms( $post->ID , 'document_topic' );
$update = get_field('vf-document_last_update');
$file_type = get_field('vf-document_file_type');

$image = get_field('image');
if ( ! is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-summary__image',
    'style'    => 'width: 175px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}

?>

<div class="vf-grid vf-grid__col-3" style="margin-top: 2.5rem !important;">
  <div class="vf-grid__col--span-2 | vf-content">
    <article class="vf-summary vf-summary--news">
      <time class="vf-summary__date vf-u-margin__bottom--0 vf-u-margin__left--0" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?>
      </time>
            
      <?php if ($image) { echo $image; } ?>
      <h2 class="vf-text vf-text-heading--2 | vf-u-margin__bottom--400 vf-u-padding__top--0"><?php the_title(); ?>
      </h2>
      <?php the_content(); ?>
      <div>
      <?php if ($update) { ?>
  <p class="vf-summary__meta"><strong>Updated: </strong>
    <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></p>
  <?php }   ?>

      <?php if( have_rows('vf-document_file_upload') ):
        $start = 1; $end = 1; 
        while( have_rows('vf-document_file_upload') ): the_row();
        if( $start <= $end ):
        $file = get_sub_field('file');
      ?>
      <a href="<?php echo $file['url']; ?>" class="vf-button vf-button--primary vf-button--sm" style="width: 100px;">Download</a>
      <?php endif; 
       $start++; 
       endwhile; 
       endif; ?>
       </div>
    </article>
  </div>
  
  <div class="vf-links vf-links--tight vf-links__list--s vf-links__list--very-easy">
    <h3 class="vf-links__heading">Document details</h3>
    <ul class="vf-links__list vf-links__list--secondary | vf-list">
      <li class="vf-list__item">
        <p class="vf-links__meta">Type:</p>
        <?php $type_list = [];
        foreach( $types as $type ) { 
          $type_list[] = $type->name; }
          echo implode(', ', $type_list); ?>
    </li>

    <li class="vf-list__item">
      <p class="vf-links__meta">Topic:</p>
  <?php $topic_list = [];
  foreach( $topics as $topic ) { 
    $topic_list[] = $topic->name; }
    echo implode(', ', $topic_list); ?>
</li>

    <li class="vf-list__item">
      <p class="vf-links__meta">File type:
      <?php echo ($file_type); ?>
      </p>
    </li>

    <li class="vf-list__item">
      <p class="vf-links__meta">Language(s):</p>
      <?php 
        if( have_rows('vf-document_file_upload') ): 
          while( have_rows('vf-document_file_upload') ): the_row();
            $language = get_sub_field('language');
            $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>" class="vf-link"><?php echo esc_html($language) ?></a>
          <?php endwhile; ?> </p>
        <?php endif; ?>
    </li>

    <?php if( have_rows('vf-document_annexes') ): ?>
      <p class="vf-links__meta">Annexes:
        <?php while( have_rows('vf-document_annexes') ): the_row();
          $language = get_sub_field('language');
          $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>" class="vf-link"><?php echo esc_html($language) ?></a>&nbsp;
        <?php endwhile; ?></p>
      <?php endif; ?>
    </ul>
  </div>
</div>

<?php

get_footer();

?>


