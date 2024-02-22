<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');


?>

<article class="vf-card vf-card--brand vf-card--bordered | embletcCard">
<?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image', 'loading' => 'lazy' ) ); ?>
<div class="vf-card__content | vf-stack vf-stack--400">
  <h3 class="vf-card__heading"><a class="vf-card__link" href="<?php the_permalink(); ?>"><?php echo $title; ?> 
    </a></h3>
</div>
</article>