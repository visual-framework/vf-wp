<?php
$featured_posts = get_field('vf_wp_my_contact_profile');
if( $featured_posts ): ?>
  <ul>
  <?php foreach( $featured_posts as $featured_post ): 
  $cpid = get_field('cpid', $featured_post->ID);
$orcid = get_field('orcid', $featured_post->ID);
$photo = $featured_post->photo;
$email = get_field('email', $featured_post->ID);
$position = get_field('positions_name_1', $featured_post->ID);
$outstation = get_field('outstation', $featured_post->ID);
$full_name = get_field('full_name', $featured_post->ID);
$room = get_field('room', $featured_post->ID);
$biography = get_field('biography', $featured_post->ID);
$team_1 = get_field('team_name_1', $featured_post->ID);
$team_2 = get_field('team_name_2', $featured_post->ID);
$team_3 = get_field('team_name_3', $featured_post->ID);
$team_4 = get_field('team_name_4', $featured_post->ID);
$primary_1 = get_field('is_primary_1', $featured_post->ID);
$primary_2 = get_field('is_primary_2', $featured_post->ID);
$primary_3 = get_field('is_primary_3', $featured_post->ID);
$primary_4 = get_field('is_primary_4', $featured_post->ID);
$telephone = get_field('telephone', $featured_post->ID);
?>

<article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>
  <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
  <h3 class="vf-profile__title | people-search">
    <a href="<?php echo get_the_permalink(); ?>" class="vf-profile__link"><?php the_title(); ?></a>
  </h3>
  <p class="vf-profile__job-title | people-search"><?php echo esc_html($position); ?></p>
  <p class="vf-profile__text | team-search">
    <?php
            if (!empty($team_1) && ($primary_1 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_1); ?>" class="vf-link"
              href="https://www.embl.org/internal-information/?s=<?php echo esc_html($team_1); ?> "><?php echo esc_html($team_1); ?>
    <?php }?>
    <?php
            if (!empty($team_2) && ($primary_2 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_2); ?>" class="vf-link"
              href="https://www.embl.org/internal-information/?s=<?php echo esc_html($team_2); ?> "><?php echo esc_html($team_2); ?>
    <?php }?>
    <?php
            if (!empty($team_3) && ($primary_3 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_3); ?>" class="vf-link"
              href="https://www.embl.org/internal-information/?s=<?php echo esc_html($team_3); ?> "><?php echo esc_html($team_3); ?>
    <?php }?>
    <?php
            if (!empty($team_4) && ($primary_4 == 1)) { ?>
    <a data-embl-js-group-link="<?php echo esc_attr($team_4); ?>" class="vf-link"
              href="https://www.embl.org/internal-information/?s=<?php echo esc_html($team_4); ?> "><?php echo esc_html($team_4); ?>
    <?php }?>
  </p>
  <p class="vf-profile__email">
    <a href="mailto:<?php echo $email; ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($email); ?></a>
  </p>
  <p class="vf-profile__phone">
    <a href="tel:<?php echo esc_attr($telephone); ?>"
      class="vf-profile__link vf-profile__link--secondary"><?php echo esc_attr($telephone); ?></a>
  </p>
  <?php if (!empty($room)) { ?>
  <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0 vf-u-margin__top--100">
    <span>Location:</span> <?php echo esc_html($room); ?>
  </p>
  <?php } ?> <p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200">
    <?php echo esc_html($outstation); ?>
  </p>
  <p class="people vf-u-display-none | used-for-filtering">People</p>
</article>
<?php endforeach; ?>

<?php endif; ?>
<?php
