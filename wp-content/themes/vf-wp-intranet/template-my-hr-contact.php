<?php

/**
* Template Name: My HR contact
*/

get_header();

$hrTopics = get_field_object('field_6524fc78fb1ba');
$hrTopicsChoices = $hrTopics['choices'];

$hrSite = get_field_object('field_6524fdddfb1bb');
$hrSiteChoices = $hrSite['choices'];

$hrGroup = get_field_object('field_6524fef8fb1bc');
$hrGroupChoices = $hrGroup['choices'];

?>

<div class="title-container">
  <h1 class="vf-text vf-text-heading--1">
    My HR contact </h1>
</div>

<?php the_content(); ?>

<div class="vf-grid | vf-grid__col-3 | vf-u-margin__top--0">
  <div class="vf-grid__col--span-2">
    <div id="hrFilters" class="vf-u-margin__bottom--800 | vf-u-background-color-ui--off-white | vf-u-padding--400">

      <div class="vf-form__item vf-stack">
        <label class="vf-form__label" for="vf-form__select-topic">Topic</label>
        <select class="vf-form__select | selectCustomWidth" id="vf-form__select-topic"
          data-jplist-control="select-filter" data-group="data-group-1" data-name="HRtopic">
          <option value="0" data-path="default">Select</option>
          <?php
            foreach($hrTopicsChoices as $value => $label) {
              ?>
          <option data-path=".<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($value); ?>">
            <?php echo esc_html($label); ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="vf-form__item vf-stack">
        <label class="vf-form__label" for="vf-form__select-site">EMBL site</label>
        <select class="vf-form__select | selectCustomWidth" id="vf-form__select-site"
          data-jplist-control="select-filter" data-group="data-group-1" data-name="HRsite">
          <option value="0" data-path="default">Select</option>
          <?php
            foreach($hrSiteChoices as $value => $label) {
              ?>
          <option data-path=".<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($value); ?>">
            <?php echo esc_html($label); ?> </option>
          <?php } ?>
        </select>
      </div>

      <div class="vf-form__item vf-stack">
        <label class="vf-form__label" for="vf-form__select-group">Department, team or group</label>
        <select class="vf-form__select | selectCustomWidth" id="vf-form__select-group"
          data-jplist-control="select-filter" data-group="data-group-1" data-name="HRGroup">
          <option value="0" data-path="default">Select</option>
          <?php
            foreach($hrGroupChoices as $value => $label) {
              ?>
          <option data-path=".<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($value); ?>">
            <?php echo esc_html($label); ?> </option>
          <?php } ?>
        </select>
      </div>
    </div>

    <main class="vf-u-display-none" id="profile-container">
      <div data-jplist-group="data-group-1">
        <?php
         $forthcomingLoop = new WP_Query(array(
          'post_type' => 'my_contact',
          'posts_per_page' => -1,
          'post_status' => 'publish',	
      ));
        ?>
        <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
        <?php
         include(locate_template('partials/vf-profile-my-contact.php', false, false)); ?>
        <?php endwhile;?>
        <!-- no results control -->
        <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1" data-name="no-results">
          <p class="vf-summary__text">
            No results found
          </p>
        </article>
      </div>
    </main>

  </div>
  <div></div>
</div>

<style>
  #hrFilters {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 2rem;
  }

  .selectCustomWidth {
    width: 15rem !important;
  }

  .vf-form__label {
    font-weight: 500 !important;
  }

</style>

<script type="text/javascript">
  jplist.init({});

</script>

<script>
  // Get a reference to the select elements
  const topicSelect = document.getElementById('vf-form__select-topic');
  const siteSelect = document.getElementById('vf-form__select-site');
  const groupSelect = document.getElementById('vf-form__select-group');

  // Get a reference to the profile-container element
  const profileContainer = document.getElementById('profile-container');

  // Add event listeners to the select elements
  topicSelect.addEventListener('change', toggleClassBasedOnSelectValue);
  siteSelect.addEventListener('change', toggleClassBasedOnSelectValue);
  groupSelect.addEventListener('change', toggleClassBasedOnSelectValue);

  // Function to handle select change events
  function toggleClassBasedOnSelectValue() {
    // Check if the selected value is not equal to 0
    if (
      topicSelect.value !== '0' ||
      siteSelect.value !== '0' ||
      groupSelect.value !== '0'
    ) {
      // If any of the select elements have a non-zero value, remove the class
      profileContainer.classList.remove('vf-u-display-none');
    } else {
      // If all select elements have a value of 0, add the class
      profileContainer.classList.add('vf-u-display-none');
    }
  }

</script>

<?php

get_footer();

?>
