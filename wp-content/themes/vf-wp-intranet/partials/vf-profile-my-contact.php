<?php
$peoplePosts = get_field('vf_wp_my_contact_profile');
$hr_topic = get_field('vf_wp_my_contact_hr_topic');
$hr_site = get_field('vf_wp_my_contact_hr_embl_site');
$hr_group = get_field('vf_wp_my_contact_hr_department_team_group');

if( $peoplePosts ): ?>
<?php foreach( $peoplePosts as $peoplePost ): 
$photo = $peoplePost->photo;
$email = get_field('email', $peoplePost->ID);
$position = get_field('positions_name_1', $peoplePost->ID);
$outstation = get_field('outstation', $peoplePost->ID);
$full_name = get_field('full_name', $peoplePost->ID);
$room = get_field('room', $peoplePost->ID);
$team_1 = get_field('team_name_1', $peoplePost->ID);
$team_2 = get_field('team_name_2', $peoplePost->ID);
$team_3 = get_field('team_name_3', $peoplePost->ID);
$team_4 = get_field('team_name_4', $peoplePost->ID);
$primary_1 = get_field('is_primary_1', $peoplePost->ID);
$primary_2 = get_field('is_primary_2', $peoplePost->ID);
$primary_3 = get_field('is_primary_3', $peoplePost->ID);
$primary_4 = get_field('is_primary_4', $peoplePost->ID);
$telephone = get_field('telephone', $peoplePost->ID);
?>

<article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>
  <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
  <h3 class="vf-profile__title | people-search">
    <a href="<?php echo get_the_permalink(); ?>" class="vf-profile__link"><?php the_title(); ?></a>
  </h3>
  <p class="vf-profile__job-title | people-search"><?php echo esc_html($position); ?></p>
  <?php /*
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
  */ ?>
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
  <p class="people vf-u-display-none | used-for-filtering">PeopleMeta
    <?php 
      if( $hr_group ): ?>
        <span class="hr-group | <?php echo strtolower(implode( ', ', $hr_group )); ?>">
        <?php 
        $hr_group = str_replace(' ', '_', $hr_group);
        echo strtolower(implode( ', ', $hr_group )); ?>
      </span>
      <?php endif; ?>
      <?php 
      if( $hr_site ): ?>
        <span class="hr-site | <?php echo strtolower(implode( ', ', $hr_site )); ?>">
        <?php echo strtolower(implode( ', ', $hr_site )); ?>
      </span>
      <?php endif; ?>
      <?php 
      if( $hr_topic ): ?>
        <span class="hr-topic | <?php echo strtolower(implode( ', ', $hr_topic )); ?>">
        <?php echo strtolower(implode( ', ', $hr_topic )); ?>
      </span>
      <?php endif; ?>
  </p>
</article>
<?php endforeach; ?>

<?php endif; ?>
<?php
