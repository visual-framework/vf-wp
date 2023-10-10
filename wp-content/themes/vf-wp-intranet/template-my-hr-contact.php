<?php

/**
* Template Name: My HR contact
*/


get_header();

global $vf_theme;
$today_date = date('Ymd');
?>

<div class="title-container">
            <h1 class="vf-text vf-text-heading--1">
         My HR contact       </h1>
     </div>
<?php the_content(); ?>


<div class="vf-grid | vf-grid__col-3">
  <div class="vf-grid__col--span-2">
    <div id="hrFilters" class="vf-u-margin__bottom--800">
  <div class="vf-form__item vf-stack">

<label class="vf-form__label" for="vf-form__select">Topic</label>

<select class="vf-form__select | selectCustomWidth" id="vf-form__select">
  <option value="cat">Cat</option>
  <option value="hamster">Hamster</option>
  <option value="parrot">Parrot</option>
  <option value="dog" selected>Dog</option>
  <option value="spider">Spider</option>
  <option value="goldfish">Goldfish</option>
</select>

</div>
<div class="vf-form__item vf-stack">

  <label class="vf-form__label" for="vf-form__select">EMBL site</label>

  <select class="vf-form__select | selectCustomWidth" id="vf-form__select">
    <option value="cat">Cat</option>
    <option value="hamster">Hamster</option>
    <option value="parrot">Parrot</option>
    <option value="dog" selected>Dog</option>
    <option value="spider">Spider</option>
    <option value="goldfish">Goldfish</option>
  </select>
  </div>
<div class="vf-form__item vf-stack">

  <label class="vf-form__label" for="vf-form__select">Department, team or group</label>

  <select class="vf-form__select | selectCustomWidth" id="vf-form__select">
    <option value="cat">Cat</option>
    <option value="hamster">Hamster</option>
    <option value="parrot">Parrot</option>
    <option value="dog" selected>Dog</option>
    <option value="spider">Spider</option>
    <option value="goldfish">Goldfish</option>
  </select>
  </div>
</div>
  <main>
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
          <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
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
  gap: 3rem;
}
.selectCustomWidth {
  width: 12rem !important;
}
</style>


<script type="text/javascript">
  jplist.init({});
</script>


<?php

get_footer();

?>