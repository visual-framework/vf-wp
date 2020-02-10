<div class="vf-inlay__content vf-u-background-color-ui--black | vf-u-padding__top--0 vf-u-padding__bottom--0 | pow-container">
	<main class="vf-inlay__content--full-width | vf-u-margin--0">
		<?php $my_query = new WP_Query( 'category_name=picture-week&posts_per_page=1' );
while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
		<div class="vf-grid | vf-grid__col-2 | pow-article">

			<div class="pow-article-summary | vf-u-padding__top--xl">
				<a href="<?php echo esc_url( $pow_link ); ?>" class="vf-links__heading | pow-heading vf-section-header__heading--is-link">Picture of the
					week<svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" style="color: #18974C;" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>
				<div class="vf-u-margin__top--xl | vf-u-margin__bottom--md | pow-title">
					<a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo the_title(); ?></a>
				</div>
				<p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
			</div>
			<a href="<?php the_permalink(); ?>" style="margin-top: auto; margin-bottom: auto;">
				<div class="post-image" style="display: flex;"><?php the_post_thumbnail(); ?></div>
			</a>
		</div>
		<?php endwhile; ?>
	</main>
</div>