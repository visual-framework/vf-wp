<?php

/**
* Template Name: Seminars
*/

get_header();

?>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800 | vf-content">
  <div></div>
  <div class="vf-stack vf-stack--400">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>

    <div class="vf-form__item | vf-u-margin__top--800">
      <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
        data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
        placeholder="Filter by seminar title" data-clear-btn-id="name-clear-btn">
    </div>
  </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/seminars-filter.php', false, false)); ?>
  </div>
  <div>
    <div class="vf-content">
      <div class="embl-content-hub-loader | vf-grid vf-grid__col-1" data-jplist-group="data-group-1">
        <?php
        $request = wp_remote_get( 'https://www.embl.org/api/v1/events?_format=json&source=contenthub&field_event_type=Seminar&start_date=today&items_per_page=400
        ' );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = wp_remote_retrieve_body( $request );
        $data = json_decode( $body );
        // excludes Other - EBI seminars
        foreach ($data as $key => $item) {
            if($item->field_event_location == 'Other') {
                unset($data[$key]);
            }
        }      
        function sortByStartDate($param1, $param2) {
            return strcmp($param1->field_event_start_date_time, $param2->field_event_start_date_time);
        }
        usort($data, "sortByStartDate");

        if( ! empty( $data ) ) {
            foreach( $data as $event ) {
              $newDate = date("j M Y, H:i", strtotime($event->field_event_start_date_time));
              $info = html_entity_decode(strip_tags($event->field_event_additional_info));  
              $type = $event->field_embl_seminars_type;  
              $location = $event->field_event_location;
              $type_filter_class = strtolower(str_replace(' ', '_', $type)); 
              $location_filter_class = strtolower(str_replace(' ', '_', $location)); 
              $address = html_entity_decode(strip_tags($event->field_event_address));
              $speaker = strstr($info, 'Host', true);
              $canceled = $event->field_event_canceled;
              if ($canceled == 'No') {
                $canceled = ''; }
              elseif ($canceled == 'Yes') {
                $canceled = ' - Cancelled'; }
              elseif ($canceled == 'Postponed') {
                $canceled = ' - Postponed'; }  
              $calendarStartDate = date("Ymd", strtotime($event->field_event_start_date_time));
              $calendarStartTime = date("Hi", strtotime($event->field_event_start_date_time));
              $calendarEndTime = date("Hi", strtotime($event->field_event_start_date_time) + 60*60);
  
              if (strpos($info, 'Host') == false) {
                $address = '';
              }
              echo '<article class="vf-summary vf-summary--event" data-jplist-item>';

                //Date
                echo '<p class="vf-summary__date">' . $newDate . '&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;">
                <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=' . $event->title . '&location=' . $event->field_event_venue . '&dates=' . $calendarStartDate . 'T' . $calendarStartTime . '00/' . $calendarStartDate . 'T' . $calendarEndTime . '00'.'&sprop=name:" target="_blank" rel="nofollow">Add to calendar</a>
               </span></p>';

                //Title
                echo '<h3 class="vf-summary__title">';

                //Link
                if (!empty($event->field_event_more_information)) {
                    echo '<a href="'. $event->field_event_more_information . '" class="vf-summary__link">' . $event->title . '</a>' . $canceled . '</h3>';
                }
                else {
                     echo ($event->title . $canceled . '</h3>'); }

                // additional info field break down
                if ($speaker) {
                echo '<p class="vf-summary__text" style="font-size: 16px;">'; 
                // $speaker = substr($info, strpos($info, 'Speaker:'));
                // $venue = $event->field_event_venue;
                // $venue = str_replace('<br />', '', $venue );
                // $venue = str_replace('\n', ', ', $venue );
                // echo '<span>' . strstr($speaker, '<br />', true) . ', ' . $venue . '</span><br>';
                // show only host
                // if (strpos($info, 'Host') !== false) {     
                  // $host = substr($info, strpos($info, 'Host'));
                  // echo '<span>' . strstr($host, 'Location', true);'</span><br>'; }
                  
                // show only speaker
                echo '<span  style="font-weight: 500;">' . $speaker  . '</span><span style="font-size: 16px;">'. ' - ' . $address . '</span></p>' ;
                } 
                // show host
                echo '<p class="vf-summary__text host" style="font-size: 16px; margin-top: 0px;"><span>' . substr($info, strpos($info, 'Host')) . '</span></p>';

                // show venue
                echo '<p class="vf-summary__text place" style="font-size: 16px; margin-top: 0px;"><span>Place: ' . $event->field_event_venue . '</span></p>';
                // Seminar type
                echo '<p class="vf-summary__text | vf-text-heading--5 | type | ' . $type_filter_class .'">' . $type . '</p>';

                // Location
                echo '<p class="vf-summary__location | location | ' . $location_filter_class . '">' . $event->field_event_location . '</p>';

                // Abstract
                if (!empty($event->field_event_summary)) {
                echo '<details class="vf-details | vf-u-padding__left--0" close>
                <summary class="vf-details--summary" style="font-size: 16px;">Show abstract</summary>
                ' . $event->body . '</details>';
                }
                echo '</article>';
            }
        }
        ?>
      </div>

      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching seminars found
        </p>
      </article>
      <?php the_content(); ?>
    </div>

    <style>
      .jplist-selected {
        background-color: #707372;
      }

      .jplist-selected a {
        color: #fff;
      }

    </style>

    <nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
      data-items-per-page="25" data-current-page="0" data-name="pagination1">
      <ul class="vf-pagination__list">
        <li class="vf-pagination__item vf-pagination__item--previous-page" data-type="prev">
          <a class="vf-pagination__link">
            Previous<span class="vf-u-sr-only"> page</span>
          </a>
        </li>
        <div data-type="pages" style="display: flex;">
          <li class="vf-pagination__item" data-type="page">
            <a href="#" class="vf-pagination__link">
              {pageNumber}<span class="vf-u-sr-only">page</span>
            </a>
          </li>
        </div>
        <li class="vf-pagination__item vf-pagination__item--next-page" data-type="next">
          <a href="#" class="vf-pagination__link">
            Next<span class="vf-u-sr-only"> page</span>
          </a>
        </li>
      </ul>
    </nav>

  </div>
  <div class="vf-stack vf-stack--400">
    <fieldset id="seminars-subscription"
      class="vf-form__fieldset | vf-stack vf-stack--400 vf-u-margin__bottom--800 | vf-u-background-color-ui--off-white | vf-u-padding--400">
      <p class="vf-text-body vf-text-body--3">
        Add selected seminars to your calendar for:</p>
      </p>
      <div class="vf-cluster vf-cluster--400">
        <div class="vf-cluster__inner" style="padding: 3px;">

          <div class="vf-form__item vf-form__item--radio">
            <input type="radio" name="timeframe" value="week" id="week" class="vf-form__radio | subscrition-time-filter"
              timeframe="2">
            <label for="week" class="vf-form__label">One week</label>
          </div>

          <div class="vf-form__item vf-form__item--radio">
            <input type="radio" name="timeframe" value="month" id="month"
              class="vf-form__radio | subscrition-time-filter" timeframe="3">
            <label for="month" class="vf-form__label">One month</label>
          </div>

          <div class="vf-form__item vf-form__item--radio">
            <input type="radio" name="timeframe" value="all" id="all" class="vf-form__radio | subscrition-time-filter"
              timeframe="0" checked>
            <label for="all" class="vf-form__label">All upcoming</label>
          </div>

        </div>
      </div>
      <div class="calendar-feed">
        <button class="vf-button vf-button--primary vf-button--sm | seminar-subscribe | url-copied"
          url="https://seminarlist.embl.de/rest/calendar?dutystationID=0&seminarTypeID=0&timeFrame=0">Add to calendar</button>
      </div>
      <hr class="vf-divider  vf-u-margin__bottom--200">
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0 vf-u-margin__top--0"><a
          href="https://www.embl.org/internal-information/help/seminars-subscription">Read instructions</a>

    </fieldset>
    <article class="vf-card vf-card--brand vf-card--bordered">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.embl.org/events">Courses and
            conferences<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
      </div>
    </article>
    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="https://www.ebi.ac.uk/about/events/seminars">EMBL-EBI Seminars<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
      </div>
    </article>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).on("change", ".vf-form__item", function () {
    var arrOS = []
    var arrTYP = []
    var arrTIME = []

    $(".jplist-selected").each(function () {
      arrOS.push($(this).attr("subscribe"));
    })
    var valsOS = arrOS.join('')
    var valsOS = valsOS.slice(0, -1)

    $(".jplist-selected").each(function () {
      arrTYP.push($(this).attr("subscribet"));
    })
    var valsTYP = arrTYP.join('')
    var valsTYP = valsTYP.slice(0, -1)

    $(".subscrition-time-filter:checked").each(function () {
      arrTIME.push($(this).attr("timeframe"));
    })
    var valsTIME = arrTIME.join('')
    var str = "https://seminarlist.embl.de/rest/calendar?dutystationID=" + valsOS + "&seminarTypeID=" + valsTYP +
      "&timeFrame=" + valsTIME;
    var subscribe =
      '  <button class="vf-button vf-button--primary vf-button--sm | seminar-subscribe | url-copied" url="' + str +
      '">Add to calendar</button>'


    $('.calendar-feed').html(subscribe);
    var $temp = $("<input>");
    var $url = $('.seminar-subscribe').attr('url');

    $('.seminar-subscribe').on('click', function () {
      $("body").append($temp);
      $temp.val($url).select();
      document.execCommand("copy");
      $temp.remove();
      $(".url-copied").text("URL copied!");
    })
  });

</script>

<?php

get_footer();

?>
