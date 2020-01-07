<div class="vf-inlay__content vf-u-background-color-ui--black | vf-u-padding__top--0 | pow-container">
	<main class="vf-inlay__content--full-width | vf-u-margin--0">
		<?php $my_query = new WP_Query( 'category_name=picture-week&posts_per_page=1' );
while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
		<div class="vf-grid | vf-grid__col-2 | pow-article">
			<div class="pow-article-summary | vf-u-padding__top--xl">
				<a href="<?php echo esc_url( $pow_link ); ?>" class="vf-links__heading | pow-heading">Picture of the
					week</a>&nbsp;&nbsp;<i class="fas fa-arrow-circle-right | icon-green"></i>
				<div class="vf-u-margin__top--xl | vf-u-margin__bottom--md | pow-title">
					<a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo the_title(); ?></a>
				</div>
				<p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
			</div>
			<a href="<?php the_permalink(); ?>">
				<div class="post-image" style="display: flex;"><?php the_post_thumbnail(); ?></div>
			</a>
		</div>
		<?php endwhile; ?>
	</main>
</div>