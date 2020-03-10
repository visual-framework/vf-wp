<?php
/*
Template Name: Archives
*/
get_template_part('partials/header');

the_post();
?>

<section class="vf-inlay">
	<div class="vf-inlay__content vf-u-background-color-ui--white">
		<main class="vf-inlay__content--full-width | vf-u-margin__bottom--0 | category-container">
			<div>
				<h2 class="vf-text vf-text-heading--1" style="font-weight: 400;">Archive</h2>
			</div>
			<div class="vf-grid vf-grid__col-3 | category-latest">
				<?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page' => 9,
    'paged' => $page,); 
query_posts($args);?>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php include(locate_template('partials/vf-summary--article.php', false, false)); ?>
				<?php endwhile; endif; ?>
            </div>
            <div class="vf-grid" style="margin: 4%">			<?php vf_pagination();
      ?>
</div>

<section class="vf-inlay">

	
	<?php include(locate_template('partials/archive-container.php', false, false)); ?>

    <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

    <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_template_part('partials/footer');?>