<?php

$user_id = get_the_author_meta('ID');
$cpid = get_field('cpid');
$orcid = get_field('orcid');
$photo = get_field('photo');
$email = get_field('email');
$position = get_field('positions_name_1');
$team = get_field('team_name_1');
$telephone = get_field('telephone');
$title = get_post_meta( $post->ID, 'full_name', true);

get_header();

?>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="vf-content">
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
  <article class="vf-profile vf-profile--medium vf-profile--inline">
  <img class="vf-profile__image" src="<?php echo $photo; ?>" alt="" loading="lazy">

  <h3 class="vf-profile__title">
    <?php echo get_the_title(); ?>
  </h3>

  <p class="vf-profile__job-title"><?php echo $position; ?></p>

  <p class="vf-profile__text"><?php echo $team; ?></p>

  <p class="vf-profile__email">
    <a href="mailto:annika.grandison@embl.org" class="vf-profile__link vf-profile__link--secondary"><?php echo $email?></a>
  </p>

  <p class="vf-profile__phone">
    <a href="tel:+49 6221 387-8443" class="vf-profile__link vf-profile__link--secondary"><?php echo $telephone; ?></a>
  </p>


</article>
  </div>

</div>

<?php 

get_footer(); 

?>

