<section class="vf-summary-container | embl-grid">
  <div class="vf-section-header">
    <a href="/internal-information/seminars"
      class="vf-section-header__heading vf-section-header__heading--is-link">Seminars <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
  </div>
  <div class="vf-grid vf-grid__col-3">
    <?php
        $request = wp_remote_get( 'https://www.embl.org/api/v1/events?_format=json&source=contenthub&field_event_type=Seminar&start_date=today
        ' );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = wp_remote_retrieve_body( $request );
        $content = json_decode( $body );

        // excludes Other - EBI seminars
        foreach ($content as $key => $item) {
            if($item->field_event_location == 'Other') {
                unset($content[$key]);
            }
        }
        
        function sortByStartDate($param1, $param2) {
            return strcmp($param1->field_event_start_date_time, $param2->field_event_start_date_time);
        }
        usort($content, "sortByStartDate");
        $data = array_slice($content, 0, 3);

        if( ! empty( $data ) ) {
            foreach( $data as $event ) {
            $newDate = date("l, j F Y, g:i a", strtotime($event->field_event_start_date_time));
            $info = $event->field_event_additional_info;  
            
                echo '<article class="vf-summary vf-summary--event">';

                //Date
                echo '<p class="vf-summary__date">' . $newDate . '&nbsp;&nbsp;&nbsp;&nbsp;';

                //Title
                echo '<h3 class="vf-summary__title">';

                //Link
                if (!empty($event->field_event_more_information)) {
                    echo '<a href="'. $event->field_event_more_information . '" class="vf-summary__link">' . $event->title . '</a></h3>' ;
                }
                else {
                     echo ($event->title . '</h3>'); }

                // additional info field break down
                echo '<p class="vf-summary__text" style="font-size: 16px;">'; 
                 // show only speaker    
                $speaker = substr($info, strpos($info, 'Speaker'));
                $venue = $event->field_event_venue;
                $venue = str_replace('<br />', '', $venue );
                $venue = str_replace('\n', ', ', $venue );
                echo '<span>' . strstr($speaker, '<br />', true) . ', ' . $venue . '</span><br>';
                 // show only host
                if (strpos($info, 'Host') !== false) {     
                $host = substr($info, strpos($info, 'Host'));
                echo '<span>' . strstr($host, 'Location', true);'</span><br>'; }
                 // show location
                $newinfo = str_replace('Location', 'Place', $info); 
                echo '<span>' . substr($newinfo, strpos($newinfo, 'Place'));'</span></p>';

                // Seminar type
                echo '<p class="vf-summary__text | vf-text-heading--5 | type">' . strstr($info, '<', true) . '</p>';

                // Location
                echo '<p class="vf-summary__location | location">' . $event->field_event_location . '</p>';

                // Abstract
                if (!empty($event->field_event_summary)) {
                echo '<details class="vf-details | vf-u-padding__left--0" close>
                <summary class="vf-details--summary" style="font-size: 16px;">Show abstract</summary>
                ' . $event->field_event_summary . '</details>';
                }
                echo '</article>';
            }
        }
        ?>

  </div>
</section>
