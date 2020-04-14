<?php

get_header();

$dtypes = get_the_terms( $post->ID , 'document_types' );
$categories = get_the_terms( $post->ID , 'document_topics' );


get_template_part('partials/vf-intro');

?>
<div class="embl-grid">

  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>

  <div class="vf-content">
    <div class="vf-grid">
      <div>
        <h2 style="padding-top: 0;"><?php the_title(); ?>
        </h2>
        <p><?php the_content(); ?></p>
        <?php if( have_rows('file_upload') ): ?>
        <?php $start = 1; $end = 1; ?>
        <?php while( have_rows('file_upload') ): the_row();
			 if( $start <= $end ):
      $file = get_sub_field('file');?>
        <a href="<?php echo $file['url']; ?>" class="vf-button vf-button--primary vf-button--sm">Download</a>
        <?php endif; ?>
        <?php $start++; ?>
        <?php endwhile; ?>
        <?php endif; ?>

      </div>
        <div class="vf-box vf-box--normal vf-box-theme--quinary" style="background-color: #f3f3f3;">
          <p class="vf-box__text">Last update: <?php the_field('last_update'); ?></p>
          <p class="vf-box__text">File type: <?php the_field('file_type'); ?></p>
          <p class="vf-box__text">Document category: <?php
		 foreach ( $categories as $category ) :
		?> <a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>"
              title="<?php echo esc_attr( $category->name ); ?>"><?php echo esc_html( $category->name); ?></a>
            <?php endforeach; ?> </p>
          <p class="vf-box__text">Document type: <?php
		 foreach ( $dtypes as $dtype ) :
		?> <a href="<?php echo esc_url( get_term_link( $dtype->term_id ) ); ?>"
              title="<?php echo esc_attr( $dtype->name ); ?>"><?php echo esc_html( $dtype->name); ?></a>
            <?php endforeach; ?> </p>
          <p class="vf-box__text">Available in languages: <?php if( have_rows('file_upload') ): ?>

            <?php while( have_rows('file_upload') ): the_row();

      $language = get_sub_field('language');
      $file = get_sub_field('file');?>

            <a href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
            <?php endwhile; ?>
            <?php endif; ?> </p>
        </div>
    </div>
  </div>
</div>
<?php

get_footer();

?>
