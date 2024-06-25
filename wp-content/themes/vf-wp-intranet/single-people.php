<?php

$user_id = get_the_author_meta('ID');
$cpid = get_field('cpid');
$orcid = get_field('orcid');
$photo = get_field('photo');
$email = get_field('email');
$position = get_field('positions_name_1');
$outstation = get_field('outstation');
$full_name = get_field('full_name');
$room = get_field('room');
$biography = get_field('biography');
$team_1 = get_field('team_name_1');
$team_2 = get_field('team_name_2');
$team_3 = get_field('team_name_3');
$team_4 = get_field('team_name_4');
$is_primary_1 = get_field('is_primary_1');
$is_primary_2 = get_field('is_primary_2');
$is_primary_3 = get_field('is_primary_3');
$is_primary_4 = get_field('is_primary_4');
$telephone = get_field('telephone');
$title = get_post_meta( $post->ID, 'full_name', true);
$teamArray = array(
  array(
"team" => $team_1,
"isPrimary" => $is_primary_1,
),
  array(
"team" => $team_2,
"isPrimary" => $is_primary_2,
),
  array(
"team" => $team_3,
"isPrimary" => $is_primary_3,
  ),
  array(
"team" => $team_4,
"isPrimary" => $is_primary_4
),
);
$key_values = array_column($teamArray, 'isPrimary'); 
array_multisort($key_values, SORT_DESC, $teamArray);
$teamArray = array_map('array_filter', $teamArray);
$teamArray = array_filter($teamArray);

get_header();

?>
<?php 
/*
$meta_values = get_post_meta( get_the_ID() );
var_dump( $meta_values ); 
*/
?>
<!-- PROFILE -->
<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-content">
    <article class="vf-profile vf-profile--medium vf-profile--inline">
      <img class="vf-profile__image" src="<?php echo esc_url($photo); ?>" alt="" loading="lazy">
      <h3 class="vf-text vf-text-heading--3 vf-u-margin__bottom--0">
      <?php echo esc_html($full_name); ?>
      </h3>
      <p class="vf-profile__job-title"><?php echo esc_html($position); ?></p>
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
      <?php } ?>
      <p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200">
        <?php echo esc_html($outstation); ?></p>
      <?php if (!empty($orcid)) { ?>
      <p class="vf-profile__uuid">
        <span>ORCID:</span>
        <a class="vf-profile__link vf-profile__link--secondary"
          href="https://europepmc.org/authors/<?php echo $orcid; ?>"><?php echo esc_html($orcid); ?></a>
      </p>

      <?php } ?>
    </article>
  </div>
  <div>
    <?php if ($outstation != 'EMBL-EBI') { ?>
    <p class="vf-text-body vf-text-body--5"><a href="https://privacysettings.embl.de/" target="_blank">[Privacy
        settings]</a></p>
    <?php } ?>
  </div>
</section>


<!-- TEAMS -->
<section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--400">
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading">Teams</h2>
  </div>
  <div class="vf-content">
    <div class="vf-grid vf-grid__col-2">
      <?php
            foreach ($teamArray as $key => $teamName)  { ?>
      <article class="vf-card vf-card--brand vf-card--bordered">
        <div class="vf-card__content | vf-stack vf-stack--400">
          <h3 class="vf-card__heading">
            <a data-embl-js-group-link="<?php echo esc_attr($teamName['team']); ?>" class="vf-card__link"
              href="https://www.embl.org/internal-information/?s=<?php echo esc_html($teamName['team']); ?>&post_type=any"><?php echo esc_html($teamName['team']); ?>
              <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                height="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                  fill="currentColor" fill-rule="nonzero"></path>
              </svg>
            </a>
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
    <p><?php echo ($bio); ?></p>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>
<?php } ?>


<!-- PUBLICATIONS -->
<section class="embl-grid embl-grid--has-centered-content | publications-container | vf-u-margin__bottom--400">
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading">Publications</h2>
  </div>
  <div class="embl-content-hub-loader">
    <?php if (!empty($orcid)) { ?>
    <link rel="import"
      href="https://www.embl.org/api/v1/pattern.html?source=contenthub&pattern=embl-person-publications&limit=100&sort-field-value[changed]=DESC&orcid=<?php echo $orcid; ?>&source=contenthub"
      data-target="self" data-embl-js-content-hub-loader-no-content="No publications were found."
      data-embl-js-content-hub-loader-no-content-hide=".publications-container" data-embl-js-content-hub-loader>
    <?php }
  else  { ?>
    <link rel="import"
      href="https://www.embl.org/api/v1/pattern.html?source=contenthub&pattern=embl-person-publications&limit=100&sort-field-value[changed]=DESC&cpid=<?php echo $cpid; ?>&source=contenthub"
      data-target="self" data-embl-js-content-hub-loader-no-content="No publications were found."
      data-embl-js-content-hub-loader-no-content-hide=".publications-container" data-embl-js-content-hub-loader>
    <?php } ?>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>
<script>
    // With the emblTaxonomy loaded, we can assign links to containers
  // <a data-embl-js-group-link="team name" href="#placeholder">
  function emblPeoplePagesGroupLinkAssign() {
    var emblPeoplePagesGroupLinkTarget = document.querySelectorAll("[data-embl-js-group-link]");
  
    if (emblPeoplePagesGroupLinkTarget.length === 0) {
      console.warn('There is no `[data-embl-js-group-link]` in which to insert the breadcrumbs; exiting');
      return false;
    }
    // console.log(emblTaxonomy)
    // console.log(emblPeoplePagesGroupLinkTarget)
  
    // process each link target found
    for (const key in emblPeoplePagesGroupLinkTarget) {
      if (Object.hasOwnProperty.call(emblPeoplePagesGroupLinkTarget, key)) {
        const element = emblPeoplePagesGroupLinkTarget[key];
        let team = emblOntologyFindTeamByName(element.dataset.emblJsGroupLink)
  
        if (team != false) {
          element.href = team.url;
        } else {
          console.warn('emblPeoplePagesGroupLinkAssign', 'No team match found, leaving default search in place')
        }
      }
    }
  }
  emblPeoplePagesGroupLinkAssign(); 
</script>

<?php 

get_footer(); 

?>
