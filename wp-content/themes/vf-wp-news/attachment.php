<?php

get_template_part('partials/header');
$attachment_id = wp_get_attachment_url();


?>
<main
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--xxl | vf-u-margin__bottom--0">
  <div>

  </div>

  <div class="vf-content | vf-u-margin__bottom--xxl">
    <h2><?php the_title(); ?></h2>

    <?php echo wp_get_attachment_image( get_the_ID(), 'medium_large' ); ?>
    <figcaption class="vf-figure__caption">
    <?php the_excerpt(); ?>      
    </figcaption>

    <p>Download:</p>
		<?php
			$images = array();
			$image_sizes = get_intermediate_image_sizes();
			array_unshift( $image_sizes, 'full' );
			foreach( $image_sizes as $image_size ) {
				$image = wp_get_attachment_image_src( get_the_ID(), $image_size );
				$name = $image_size . ' (' . $image[1] . 'x' . $image[2] . ')';
				$images[] = '<a href="' . $image[0] . '">' . $name . '</a>';
			}

			echo implode( ' | ', $images );
		?> 
</div>

</main>
<section class="vf-inlay">

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>
</section>
<?php

get_template_part('partials/footer');

?>
