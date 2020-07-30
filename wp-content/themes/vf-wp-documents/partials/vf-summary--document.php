<?php
$types = get_the_terms( $post->ID , 'document_type' );
$topics = get_the_terms( $post->ID , 'document_topic' );

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
<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date vf-u-margin__bottom--0" style="margin-left: 0;" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>

 <?php if ($image) {
    echo $image; 
  }  ?>
  <h4 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
  </h4>
  <p class="vf-summary__meta | vf-u-margin__bottom--sm">Type: 
    <?php
      if (is_array($types)) {
        foreach ( $types as $type ) { ?> 
          <a href="<?php echo esc_url( get_term_link( $type->term_id ) ); ?>" title="<?php echo esc_attr( $type->name ); ?>"><?php echo esc_html( $type->name); ?>
          </a>
    <?php } } ?>
      &nbsp;&nbsp;&nbsp;&nbsp;Topic:
    <?php
      if (is_array($topics)) {
        foreach ( $topics as $topic ) { ?> 
          <a href="<?php echo esc_url( get_term_link( $topic->term_id ) ); ?>"
            title="<?php echo esc_attr( $topic->name ); ?>"><?php echo esc_html( $topic->name); ?>
          </a>
    <?php } } ?>
      &nbsp;&nbsp;&nbsp;&nbsp;Language(s):&nbsp;
    <?php if( have_rows('vf-document_file_upload') ): ?>
      <?php while( have_rows('vf-document_file_upload') ): the_row();
        $language = get_sub_field('language');
        $file = get_sub_field('file');?>
        <a href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
      <?php endwhile; ?>
    <?php endif; ?>
  </p>
</article>
<!--/vf-summary-->
