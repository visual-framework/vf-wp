<?php

$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$pow_link = get_category_link(6);

?>

<div class="vf-inlay__content vf-u-background-color-ui--black | vf-u-padding__top--0 vf-u-padding__bottom--0 | pow-container">
  <main class="vf-inlay__content--full-width | vf-u-margin--0">
    <?php $my_query = new WP_Query( 'category_name=multimedia&posts_per_page=1' );
while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
    <div class="vf-grid | vf-grid__col-2 | pow-article">

      <div class="vf-u-padding__top--xl">
        <a href="<?php echo esc_url( $pow_link ); ?>"
          class="vf-links__heading | vf-section-header__heading--is-link | vf-text vf-text-heading--2"
          style="font-weight: 200; color: #d0d0ce;"><em>Picture of the
            week</em><svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" style="color: #18974C;"
            width="24" height="24" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
              fill="" fill-rule="nonzero"></path>
          </svg></a>
        <div class="vf-u-margin__top--xl | vf-u-margin__bottom--md">
          <a href="<?php the_permalink(); ?>" class="vf-text--body vf-text-body--2 | vf-link vf-link--secondary"
            style="color: white;"><?php echo the_title(); ?></a>
        </div>
        <p class="vf-summary__text | vf-u-text-color--grey--lightest"><?php echo get_the_excerpt(); ?></p>
        <span class="vf-summary__meta | vf-u-margin__bottom--xs vf-u-margin__top--xs">
          <p class="vf-summary__meta vf-u-text-color--ui--white">By&nbsp;
            <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"
              style="color: #18974C;"><?php the_author(); ?></a></p>
        </span>
      </div>
      <a href="<?php the_permalink(); ?>" style="margin: auto;">
        <?php the_post_thumbnail(); ?>
      </a>
    </div>
    <?php endwhile; ?>
  </main>
</div>