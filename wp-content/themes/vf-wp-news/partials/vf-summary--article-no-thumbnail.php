<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>
<article class="vf-summary vf-summary--article">
	<div class="article-summary-sidebar-no-th">
	<span>
<?php 
    $sep = '';
    foreach ((get_the_category()) as $cat) {
        echo $sep . '<a href="' . get_category_link($cat->term_id) . '"  class="' . $cat->slug . '" title="View all posts in '. esc_attr($cat->name) . '">' . $cat->cat_name . '</a>';
        $sep = ', ';
    }
?>
</span>
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <span class="vf-summary__meta">
    <a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"><?php the_author(); ?></a>
    <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </span>	
			</div>
</article>
<!--/vf-summary-->

