<?php 
 $related = get_field('select_translations', $l->ID);
 if ($related['value'] == 'german') {
   $title = 'Deutsch';
 }
 if ($related['value'] == 'french') {
   $title = 'Français';
 }
 if ($related['value'] == 'english') {
   $title = 'English';
 }
 if ($related['value'] == 'italian') {
   $title = 'Italiano';
 }
 if ($related['value'] == 'spanish') {
   $title = 'Español';
 }
 if ($related['value'] == 'catalan') {
   $title = 'Catalan';
 }
$permalink = get_permalink( $l->ID );
?>
