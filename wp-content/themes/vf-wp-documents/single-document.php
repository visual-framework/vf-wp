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

get_template_part('partials/vf-intro');

?>

<div class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>
  <div class="vf-content">
    <article class="vf-summary vf-summary--news">
      <time class="vf-summary__date vf-u-margin__bottom--0 vf-u-margin__left--0" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?>
      </time>
            
      <?php if ($image) { echo $image; } ?>
      <h2 class="vf-text vf-text-heading--2 | vf-u-margin__bottom--md vf-u-padding__top--0"><?php the_title(); ?>
      </h2>
      <?php the_content(); ?>
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
    </article>
  </div>
  
  <div class="vf-links vf-links--tight vf-links__list--s vf-links__list--very-easy">
    <h3 class="vf-links__heading">Document details</h3>
    <ul class="vf-links__list vf-links__list--secondary | vf-list">
      <li class="vf-list__item">
        <p class="vf-links__meta">Type:</p>
          <?php
            if (is_array($types)) {
		        foreach ( $types as $type ) {
          ?> 
          <a href="<?php echo esc_url( get_term_link( $type->term_id ) ); ?>" class="vf-list__link"
            title="<?php echo esc_attr( $type->name ); ?>"><?php echo esc_html( $type->name); ?></a>
          <?php } } ?>
    </li>

    <li class="vf-list__item">
      <p class="vf-links__meta">Topic:</p>
      <?php
        if (is_array($topics)) {
          foreach ( $topics as $topic ) {
      ?> 
    <a href="<?php echo esc_url( get_term_link( $topic->term_id ) ); ?>" class="vf-list__link"
            title="<?php echo esc_attr( $topic->name ); ?>"><?php echo esc_html( $topic->name); ?></a>
      <?php } } ?>
    </li>

    <?php if ($update) { ?>
    <li class="vf-list__item">
      <p class="vf-links__meta">Updated: <?php echo $update; ?></p>
    </li>
    <?php } ?>

    <li class="vf-list__item">
      <p class="vf-links__meta">File type:
      <?php echo ($file_type); ?>
      </p>
    </li>

    <li class="vf-list__item">
      <p class="vf-links__meta">Languages:</p>
      <?php 
        if( have_rows('vf-document_file_upload') ): 
          while( have_rows('vf-document_file_upload') ): the_row();
            $language = get_sub_field('language');
            $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>" class="vf-list__link"><?php echo esc_html($language) ?></a>
          <?php endwhile; ?> </p>
        <?php endif; ?>
    </li>

    <?php if( have_rows('vf-document_annexes') ): ?>
      <p class="vf-links__meta">Annexes:
        <?php while( have_rows('vf-document_annexes') ): the_row();
          $language = get_sub_field('language');
          $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>" class="vf-list__link"><?php echo esc_html($language) ?></a>&nbsp;
        <?php endwhile; ?></p>
      <?php endif; ?>
    </ul>
  </div>
</div>

<?php

get_footer();

?>


