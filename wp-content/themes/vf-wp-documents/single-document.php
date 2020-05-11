<?php

get_header();

$types = get_the_terms( $post->ID , 'document_type' );
$topics = get_the_terms( $post->ID , 'document_topic' );


get_template_part('partials/vf-intro');

?>
<div class="vf-grid">
  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>
  <div class="vf-content">
    <div class="vf-grid | vf-grid__col-3">
      <div class="vf-grid__col--span-2">
        <article>
          <time class="vf-summary__date vf-u-margin__bottom--0" style="margin-left: 0;" title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
          <h2 style="padding-top: 0;"><?php the_title(); ?>
          </h2>
          <p><?php the_content(); ?></p>
        </article>
      </div>
      <div class="vf-box vf-box--normal vf-box-theme--quinary">
        <p class="vf-box__heading">Document details:</p>
        <p class="vf-box__text">Topic: <?php
    if (is_array($topics)) {
		  foreach ( $topics as $topic ) {
		?> <a href="<?php echo esc_url( get_term_link( $topic->term_id ) ); ?>"
            title="<?php echo esc_attr( $topic->name ); ?>"><?php echo esc_html( $topic->name); ?></a>
          <?php } } ?> </p>

        <p class="vf-box__text">Type: <?php
    if (is_array($types)) {
		  foreach ( $types as $type ) {
		?> <a href="<?php echo esc_url( get_term_link( $type->term_id ) ); ?>"
            title="<?php echo esc_attr( $type->name ); ?>"><?php echo esc_html( $type->name); ?></a>
          <?php } } ?> </p>

        <p class="vf-box__text">Available in languages: <?php if( have_rows('vf-document_file_upload') ): ?>
          <?php while( have_rows('vf-document_file_upload') ): the_row();
          $language = get_sub_field('language');
          $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
          <?php endwhile; ?> </p>

          <?php endif; ?>
          <?php if( have_rows('vf-document_annexes') ): ?>
        <p class="vf-box__text">Annexes:
          <?php while( have_rows('vf-document_annexes') ): the_row();
          $language = get_sub_field('language');
          $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
          <?php endwhile; ?></p>

          <?php endif; ?>
        <p class="vf-box__text">Last update: <?php the_field('vf-document_last_update'); ?></p>
        
        <p class="vf-box__text">File type: 
          <?php 
            $file_type = the_field('vf-document_file_type');
            echo esc_html($file_type['label']); ?></p>

          <?php if( have_rows('vf-document_file_upload') ): ?>
          <?php $start = 1; $end = 1; ?>
          <?php while( have_rows('vf-document_file_upload') ): the_row();
        if( $start <= $end ):
        $file = get_sub_field('file');?>
          <a href="<?php echo $file['url']; ?>" class="vf-button vf-button--primary vf-button--sm">Download</a>
          <?php endif; ?>
          <?php $start++; ?>
          <?php endwhile; ?>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php

get_footer();

?>
