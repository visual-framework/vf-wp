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
get_header();

?>
<!-- PROFILE -->
<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-content">
    <article class="vf-profile vf-profile--medium vf-profile--inline">
      <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
      <h3 class="vf-profile__title">
        <?php echo get_the_title(); ?>
      </h3>
      <p class="vf-profile__job-title"><?php echo esc_html($position); ?></p>
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
        <span>Room:</span> <?php echo esc_html($room); ?>
        </p>
        <?php } ?>
      <p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200"><?php echo esc_html($outstation); ?></p>
      <?php if (!empty($orcid)) { ?>
      <p class="vf-profile__uuid">
      <span>ORCID:</span>
      <a class="vf-profile__link vf-profile__link--secondary" href="https://europepmc.org/authors/<?php echo $orcid; ?>"><?php echo esc_html($orcid); ?></a>
    </p>
    <?php } ?>
    </article>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>

<!-- TEAMS -->
<section class="embl-grid embl-grid--has-centered-content">
<div class="vf-section-header">
  <h2 class="vf-section-header__heading">Teams</h2>
</div>
  <div class="vf-content">
      <div class="vf-grid vf-grid__col-2">
        <?php
            if (!empty($team_1)) { ?>
            <article class="vf-card vf-card--brand vf-card--bordered">
                <div class="vf-card__content | vf-stack vf-stack--400">
                    <h3 class="vf-card__heading">
                    <?php echo esc_html($team_1); ?>  
                    </h3>
                </div>
            </article>
         <?php }?>
        <?php
            if (!empty($team_2)) { ?>
            <article class="vf-card vf-card--brand vf-card--bordered">
                <div class="vf-card__content | vf-stack vf-stack--400">
                    <h3 class="vf-card__heading">
                    <?php echo esc_html($team_2); ?>  
                    </h3>
                </div>
            </article>
         <?php }?>
        <?php
            if (!empty($team_3)) { ?>
            <article class="vf-card vf-card--brand vf-card--bordered">
                <div class="vf-card__content | vf-stack vf-stack--400">
                    <h3 class="vf-card__heading">
                    <?php echo esc_html($team_3); ?>  
                    </h3>
                </div>
            </article>
         <?php }?>
        <?php
            if (!empty($team_4)) { ?>
            <article class="vf-card vf-card--brand vf-card--bordered">
                <div class="vf-card__content | vf-stack vf-stack--400">
                    <h3 class="vf-card__heading">
                    <?php echo esc_html($team_4); ?>  
                    </h3>
                </div>
            </article>
         <?php }?>
     </div>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>

<!-- Biography -->
<?php if (!empty($biography)) {
  $bio =  html_entity_decode($biography);?>
<section class="embl-grid embl-grid--has-centered-content">
<div class="vf-section-header">
  <h2 class="vf-section-header__heading">Biography</h2>
</div>
  <div class="vf-content">
    <?php echo ($bio); ?>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>
<?php } ?>


<!-- PUBLICATIONS -->
<section class="embl-grid embl-grid--has-centered-content">
<div class="vf-section-header">
  <h2 class="vf-section-header__heading">Publications</h2>
</div>
  <div class="embl-content-hub-loader">
  <link rel="import" href="https://www.embl.org/api/v1/pattern.html?source=contenthub&pattern=embl-person-publications&limit=100&sort-field-value[changed]=DESC&orcid=<?php echo $orcid; ?>&source=contenthub" data-target="self" data-embl-js-content-hub-loader-no-content="No publications were found." data-embl-js-content-hub-loader>


  </div>
  <div>
    <!-- empty -->
  </div>
</section>


<?php 

get_footer(); 

?>
