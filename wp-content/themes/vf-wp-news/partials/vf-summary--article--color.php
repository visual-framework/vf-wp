<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>
<article class="vf-summary vf-summary--article">
	<div class="post-image">
		<a style="display: grid;" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		  <div class="overlay">
			  <p class="vf-u-margin--0">
				  <?php the_field('image_caption'); ?>
			  </p>
		</div>
	</div>
	<div class="article-summary" style="background-color: <?php the_field('color'); ?>">
		<h2 class="vf-summary__title | vf-u-margin__top--sm">
			<a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
		</h2>
		<p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
        <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
                datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        <span class="vf-summary__meta | vf-u-margin__bottom--xs vf-u-margin__top--xs">
			<p class="vf-summary__meta">By&nbsp;</p>
			<a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"
				style="color: <?php the_field('link_color'); ?>"><?php the_author(); ?></a>
		</span>
		<div class="vf-summary__meta topics-info">
			<p class="vf-summary__meta">Topics:&nbsp;</p><a class="vf-link"
				style="color: <?php the_field('link_color'); ?>"><?php the_category(); ?>&nbsp;&nbsp;&nbsp;</a>
			<p class="vf-summary__meta"><?php echo reading_time(); ?> read</p>

		</div>
	</div>
</article>