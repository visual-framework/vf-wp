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
$primary_1 = get_field('is_primary_1');
$primary_2 = get_field('is_primary_2');
$primary_3 = get_field('is_primary_3');
$primary_4 = get_field('is_primary_4');
$telephone = get_field('telephone');
$title = get_post_meta( $post->ID, 'full_name', true);
?>

<article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>
  <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
  <h3 class="vf-profile__title | people-search">
    <a href="<?php echo get_the_permalink(); ?>" class="vf-profile__link"><?php echo get_the_title(); ?></a>
  </h3>
  <p class="vf-profile__job-title | people-search"><?php echo esc_html($position); ?></p>
  <p class="vf-profile__text | people-search">
    <?php
            if (!empty($team_1) && ($primary_1 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_1); ?>" class="vf-link"
              href="//www.embl.org/search?searchQuery=<?php echo esc_html($team_1); ?> "><?php echo esc_html($team_1); ?>
    <?php }?>
    <?php
            if (!empty($team_2) && ($primary_2 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_2); ?>" class="vf-link"
              href="//www.embl.org/search?searchQuery=<?php echo esc_html($team_2); ?> "><?php echo esc_html($team_2); ?>
    <?php }?>
    <?php
            if (!empty($team_3) && ($primary_3 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_3); ?>" class="vf-link"
              href="//www.embl.org/search?searchQuery=<?php echo esc_html($team_3); ?> "><?php echo esc_html($team_3); ?>
    <?php }?>
    <?php
            if (!empty($team_4) && ($primary_4 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_4); ?>" class="vf-link"
              href="//www.embl.org/search?searchQuery=<?php echo esc_html($team_4); ?> "><?php echo esc_html($team_4); ?>
    <?php }?>
  </p>
  <p class="vf-profile__email">
    <a href="mailto:<?php echo $email; ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($email); ?></a>
  </p>
  <p class="vf-profile__phone">
    <a href="<?php echo esc_attr($telephone); ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($telephone); ?></a>
  </p>
  <?php if (!empty($room)) { ?>
  <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0 vf-u-margin__top--100">
    <span>Location:</span> <?php echo esc_html($room); ?>
  </p>
  <?php } ?> <p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200">
    <?php echo esc_html($outstation); ?>
  </p>
</article>
