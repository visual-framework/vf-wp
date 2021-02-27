<?php

$title = esc_html(get_the_title());
$tags = get_the_tags($post->ID);
$file = get_field('upload_file');
$related_documents = get_field('related_documents');
$file_type = get_field('file_type');


get_header();

?>

<style>
  .vf-search--inline .vf-search__input {
    min-width: 300px;
  }

  .vf-search--inline .vf-form__select {
    padding: 8px 12px;
  }

  .vf-search--inline .vf-search__item:not(:first-child) {
    padding-left: 10px;
  }

  .vf-search--inline .vf-search__button {
    top: -3px;
  }

</style>
<div
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-background-color-ui--off-white vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <form role="search" method="get" class="vf-form  | vf-search vf-search--inline"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-form__item | vf-search__item">
      <input type="search" class="vf-form__input | vf-search__input" placeholder="Search for a document"
        value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    </div>
    <div class="vf-form__item | vf-search__item" style="display: none;">
      <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
        <option value="documents" name="post_type[]">Document</option>
      </select>
    </div>
    <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
      value="<?php esc_attr_e('Search', 'vfwp'); ?>">
  </form>
</div>

<div class="embl-grid embl-grid--has-centered-content | vf-content">
  <div></div>
  <div>
    <?php
    $tags = get_the_tags($post->ID);
    if ($tags) {
    $tagslist = array();
    foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-badge | vf-u-margin__bottom--200">' . $tag->name . '</a>';
    }
    echo implode('  ', $tagslist);
    } 
    ?>
    <p class="vf-summary__date | vf-u-margin__bottom--0">
      <time title="<?php the_time('c'); ?>"
        datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    </p>
    <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <p class="vf-text-body vf-text-body--5 | vf-u-text-color--grey
">File type: <?php echo ($file_type); ?></p>
    <?php
if( $file ): ?>
    <a href="<?php echo $file['url']; ?>" class="vf-button vf-button--primary vf-button--outline vf-button--sm">Download</a>
    <?php endif; ?>
    <hr class="vf-divider vf-u-margin__top--600">

    <?php
if( $related_documents ): ?>
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
  <div></div>
</div>


<?php 

get_footer(); 

?>
