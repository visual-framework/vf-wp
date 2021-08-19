<?php
$types = get_the_terms( $post->ID , 'document_type' );
$topics = get_the_terms( $post->ID , 'document_topic' );
$update = get_field('vf-document_last_update');

$image = get_field('image');
if ( ! is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-summary__image',
    'style'    => 'width: 135px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}
?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <time class="vf-summary__date vf-u-margin__bottom--0 | date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>

  <?php 
      if (! empty($image)) { 
        echo $image;
         } 
      else { 
        // awaiting assets
      };
      
    ?>
  <h4 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
  </h4>
  <div>
    <?php if( have_rows('vf-document_file_upload') ): ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    Available in language(s):&nbsp;
      <?php while( have_rows('vf-document_file_upload') ): the_row();
        $language = get_sub_field('language');
        $file = get_sub_field('file');?>
      <a class="vf-link" href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
      <?php endwhile; ?>
      <?php endif; ?>
  </p>

  <p class="vf-summary__meta | vf-u-margin__bottom--200">
  <?php if (($types)) { ?>
    <span>Type:</span>&nbsp;
    <span class="vf-u-text-color--grey | type">
      <?php $type_list = [];
        foreach( $types as $type ) { 
          $type_list[] = $type->name; }
          echo implode(', ', $type_list); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
  <?php } ?>
  <?php if (($topics)) { ?>
  <span>Topic:</span>&nbsp;
  <span class="vf-u-text-color--grey | topic">
  <?php $topic_list = [];
        foreach( $topics as $topic ) { 
          $topic_list[] = $topic->name; }
          echo implode(', ', $topic_list); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
          </p>
          <?php if ($update) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200"><strong>Updated: </strong>
    <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></p>
  <?php }   ?>
  </div>
</article>
<!--/vf-summary-->
