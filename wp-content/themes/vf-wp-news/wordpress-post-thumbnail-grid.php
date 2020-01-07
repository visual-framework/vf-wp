<?php
/*
Template Name: Thumbnails
*/
?>
<?php get_header(); ?>

<div class="gridcontainer">

<h1 class="entry-title"><?php the_title(); ?></h1>

<?php

// Grid Parameters
$counter = 1; // Start the counter
$grids = 5; // Grids per row
$titlelength = 20; // Length of the post titles shown below the thumbnails

// The Query
$args=array (
	'post_type' => 'post',
	'posts_per_page' => -1
	);
$the_query = new WP_Query($args);

// The Loop
while ( $the_query->have_posts() ) :
	$the_query->the_post();

// Show all columns except the right hand side column
if($counter != $grids) :
?>
<div class="griditemleft">
	<div class="postimage">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
	</div><!-- .postimage -->
	<h2 class="postimage-title">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php if (mb_strlen($post->post_title) > $titlelength)
			{ echo mb_substr(the_title($before = '', $after = '', FALSE), 0, $titlelength) . ' ...'; }
		else { the_title(); } ?>
		</a>
	</h2>
</div><!-- .griditemleft -->
<?php

// Show the right hand side column
elseif($counter == $grids) :
?>
<div class="griditemright">
	<div class="postimage">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
	</div><!-- .postimage -->
	<h2 class="postimage-title">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php if (mb_strlen($post->post_title) > $titlelength)
			{ echo mb_substr(the_title($before = '', $after = '', FALSE), 0, $titlelength) . ' ...'; }
		else { the_title(); } ?>
		</a>
	</h2>
</div><!-- .griditemright -->

<div class="clear"></div>
<?php
$counter = 0;
endif;
$counter++;
endwhile;
wp_reset_postdata();
?>

</div><!-- .gridcontainer -->

<?php get_footer(); ?>