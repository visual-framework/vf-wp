<?php

/**
* Template Name: Test
*/

get_header();

?>


<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
  </div>
</section>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".people-search" type="text" value=""
            placeholder="Filter by seminar title" data-clear-btn-id="name-clear-btn">
   </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
  </div>
  <div>
  <section class="vf-tabs__section" id="vf-tabs__section--pages">
      <!-- People -->
      <section class="vf-tabs__section" id="vf-tabs__section--people">
        <div class="vf-content">
          <div class="vf-grid vf-grid__col-1" data-jplist-group="data-group-1">
            <?php
            
        $people_json_feed_api_endpoint = 'https://content.embl.org/api/v1/people-all-info?items_per_page=100';
        $raw_content = file_get_contents($people_json_feed_api_endpoint);
        $raw_content_decoded = json_decode($raw_content, true);
        $people_data = $raw_content_decoded['rows'];  
            

        if (!empty($people_data) && is_array($people_data)) {
          foreach ($people_data as $person) {
          $title = $person['full_name']; 
          $photo = $person['photo']; ?>
            <article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>

  <img class="vf-profile__image" src="<?php echo $photo; ?> " alt="" loading="lazy">
<h3 class="vf-profile__title | people-search">
    <a href="" class="vf-profile__link"><?php  echo $title; ?></a>
  </h3>

</article>
<?php
            }
        }
        ?>
          </div>

          <!-- no results control -->

        </div>
      </section>

  </div>
</section>

<style>
  .vf-form__label {
    font-size: 16px;
  }

  .vf-form__legend {
    font-size: 19px;
  }

  .vf-form__checkbox+.vf-form__label::before {
    position: unset;
  }

</style>

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>

<?php

get_footer();

?>
