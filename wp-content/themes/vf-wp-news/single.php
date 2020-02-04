<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');


get_template_part('partials/header');

the_post();

?>

<main class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--xxl | vf-u-margin__bottom--0">
	<div class="article-left-col">
		<div class="vf-article-meta-information | vf-u-margin__left--sm | author-box" style="display: block;">
			<div class="vf-author | vf-article-meta-info__author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 48); ?>
				<p class="vf-author__name | vf-text-body--5">
					<a class="vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
				</p>
			</div>
			<div class="vf-meta__details">
				<time class="vf-meta__date | vf-text-body--5" title="<?php the_time('c'); ?>"
					datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
				<p class="vf-meta__date | vf-text-body--5"><?php echo reading_time(); ?> read</p>
			</div>
			<div class="vf-meta__details">
				<p class="vf-meta__topics | vf-text-body--5">Topics:&nbsp;
				<a class="vf-meta__topics | vf-text-body--6"><?php the_category(); ?></a>
				</p>
			</div>
		</div>
	</div>

	<div class="vf-content | vf-u-padding__bottom--xxl">
		<h1><?php the_title(); ?></h1>
		<h2>
			<?php echo get_post_meta($post->ID, 'article_intro', true); ?>
		</h2>
		<figure class="vf-figure">
			<?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>

			<figcaption class="vf-figure__caption">
				<?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
			</figcaption>
		</figure>
			<?php the_content(); ?>
		<p class="vf-text--body vf-text-body--3"><?php the_tags(); ?></p>

		<div class="vf-links vf-links--tight vf-links__list--s | vf-u-margin__top--xxl">
			<?php if( have_rows('article_sources') ): ?>
			<?php while( have_rows('article_sources') ): the_row(); 
			
        $source = get_sub_field('source_link');
		$description = get_sub_field('description');?>
					<p class="vf-text--body vf-text-body--3">Source article:</p>
			<ul class="vf-links__list vf-links__list--secondary | vf-list">
				<li class="vf-list__item">
					<a class="vf-list__link" href="<?php echo esc_url( $source ); ?>"><?php echo esc_html($description) ?></a>
				</li>
			</ul>
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="social-share-box">
		<?php echo do_shortcode('[Sassy_Social_Share]') ?>
	</div>

</main>

<section class="vf-inlay">
	<div class="vf-inlay__content | related-posts-container vf-u-background-color-ui--off-white">
		<main class="vf-inlay__content--full-width">
			<h3 class="vf-links__heading">More from this category</h3>&nbsp;&nbsp;<i
				class="fas fa-arrow-circle-right"></i>
			<div class="vf-grid vf-grid__col-3">
				<?php
$args = array(
	'posts_per_page' => 3, 
	'post__not_in'   => array( get_the_ID() ), 
	'no_found_rows'  => true, 
);

$cats = wp_get_post_terms( get_the_ID(), 'category' ); 
$cats_ids = array();  
foreach( $cats as $related_cat ) {
	$cats_ids[] = $related_cat->term_id; 
}
if ( ! empty( $cats_ids ) ) {
	$args['category__in'] = $cats_ids;
}

$query = new wp_query( $args );

foreach( $query->posts as $post ) : setup_postdata( $post ); ?>

				<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
				<?php endforeach;
wp_reset_postdata(); ?>
			</div>
		</main>
	</div>

	<?php include(locate_template('partials/pow-container.php', false, false)); ?>

	<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

	<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>
<?php get_template_part('partials/footer');?>