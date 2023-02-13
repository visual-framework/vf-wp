<?php
$is_preview = isset($is_preview) && $is_preview;
// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
    if ( ! $is_preview) {
      return;
    }
  ?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
  };
?>

<div class="vf-content">
  <div class="embl-content-hub-loader | vf-grid vf-grid__col-1" data-jplist-group="data-group-1">
    <?php
        $value = get_field('vf_team_profile_team_name');
        $source = "";
        $search_by = get_field('vf_team_profile_search_by');

        if ($search_by == 'name') {
            $source = 'team_name';
        }
        else if ($search_by == 'bdrid') {
            $source = 'team_id';
        }
        else {
            $source = "";
        }
        $raw_content = file_get_contents('https://dev.content.embl.org/api/v1/team-profiles?' . $source . '=' . $value);
        $raw_content_decoded = json_decode($raw_content, true);
        $people_data = $raw_content_decoded['rows'];



        if( !empty($people_data) && is_array($people_data))  {
            foreach ($people_data as $key => $person) {
              $name = $person['team_name'];  
              $url = $person['team_url'];  
              $photo = $person['team_leader_photo'];  
              $leader = $person['team_leader_name'];  
              $strapline = $person['team_strapline'];  
                echo '
                <article class="vf-profile vf-profile--very-easy vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600">
					<img class="vf-profile__image" src="'. $photo . '">
					<h3 class="vf-profile__title">
					<a href="'. $url . '" class="vf-profile__link">' . $name . '</a>
					</h3>
					<p class="vf-text vf-text-body--3">Led by ' . $leader . '</p>
				    <p class="vf-profile__text">' . $strapline . '</p>
			</article>';
            }
        }
        ?>
  </div>
</div>
