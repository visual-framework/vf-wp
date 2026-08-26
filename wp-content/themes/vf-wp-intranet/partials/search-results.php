<?php
$title = esc_html(get_the_title());
$post_type = get_post_type(get_the_ID());

?>
<?php
if($post_type == 'page') {
   //  echo ' <span class="vf-badge vf-badge--secondary | vf-u-margin__bottom--400" style="font-size: 12px; color: #54585a;
   //  border-color: #54585a;">Page</span>';
   include(locate_template('partials/vf-summary--page.php', false, false)); }

elseif($post_type == 'documents') {
   include(locate_template('partials/vf-summary--document.php', false, false)); }

elseif($post_type == 'people') {
   include(locate_template('partials/vf-profile.php', false, false)); } 

elseif($post_type == 'insites') {
   include(locate_template('partials/vf-summary-insites-latest.php', false, false)); }

elseif($post_type == 'events') {
   include(locate_template('partials/vf-summary-events.php', false, false)); }

?>
