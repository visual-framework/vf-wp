<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>
<div class="vf-card vf-card--very-easy | vf-u-margin__bottom--md">
<a style="display: flex;" href="<?php the_permalink(); ?>">
<?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
		</a>
  <div class="vf-card__content">
    <h3 class="vf-card__title">
	<a href="<?php the_permalink(); ?>" class="vf-link vf-link--secondary"><?php echo $title; ?></a>
	</h3>
	<time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
                datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        <span class="vf-summary__meta | vf-u-margin__bottom--xs ">
			<p class="vf-summary__meta vf-u-margin__bottom--xs vf-u-margin__top--xs">By&nbsp;<a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"
				><?php the_author(); ?></a></p>
                <p class="vf-summary__meta vf-u-margin__bottom--xs"><a class="vf-link"><?php echo get_the_category_list(','); ?>&nbsp;&nbsp;&nbsp;</a><?php echo reading_time(); ?> read</p>
		</span>
  </div>
</div>