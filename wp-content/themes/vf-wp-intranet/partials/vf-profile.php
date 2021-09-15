<?php
$user_id = get_the_author_meta('ID');
$cpid = get_field('cpid');
$orcid = get_field('orcid');
$photo = get_field('photo');
$email = get_field('email');
$position = get_field('positions_name_1');
$outstation = get_field('outstation');
$room = get_field('room');
$biography = get_field('biography');
$team_1 = get_field('team_name_1');
$team_2 = get_field('team_name_2');
$team_3 = get_field('team_name_3');
$team_4 = get_field('team_name_4');
$telephone = get_field('telephone');
$title = get_post_meta( $post->ID, 'full_name', true);
?>

<article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>
  <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
  <h3 class="vf-profile__title | people-search">
    <a href="<?php echo get_the_permalink(); ?>" class="vf-profile__link"><?php echo get_the_title(); ?></a>
  </h3>
  <p class="vf-profile__job-title | people-search"><?php echo esc_html($position); ?></p>
  <p class="vf-profile__email">
    <a href="mailto:<?php echo $email; ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($email); ?></a>
  </p>
  <p class="vf-profile__phone">
    <a href="<?php echo esc_attr($telephone); ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($telephone); ?></a>
  </p>
  <?php if (!empty($room)) { ?>
  <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0">
    <span>Room:</span> <?php echo esc_html($room); ?>
  </p>
  <?php } ?>
  <p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200"><?php echo esc_html($outstation); ?>
  </p>
</article>