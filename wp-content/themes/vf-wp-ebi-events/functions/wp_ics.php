<?php
/*  
	For a better understanding of ics requirements and time formats
    please check https://gist.github.com/jakebellacera/635416
*/
// UTILS
// Check if string is a timestamp
function isValidTimeStamp($timestamp) {
  //if($timestamp == '') return;
  return ((string) (int) $timestamp === $timestamp)
    && ($timestamp <= PHP_INT_MAX)
    && ($timestamp >= ~PHP_INT_MAX);
}

// Escapes a string of characters
function escapeString($string) {
  return preg_replace('/([\,;])/', '\\\$1', $string);
}

// Shorten a string to desidered characters lenght - eg. shorter_version($string, 100);
function shorter_version($string, $lenght) {
  if (strlen($string) >= $lenght) {
    return substr($string, 0, $lenght);
  }
  else {
    return $string;
  }
}

function cleanupDesc($htmlMsg) {
  $temp = str_replace(["\r\n"], "\n", $htmlMsg);
  $lines = explode("\n", $temp);
  $new_lines = [];
  foreach ($lines as $i => $line) {
    if (!empty($line)) {
      $new_lines[] = trim($line);
    }
  }
  $desc = implode("\r\n ", $new_lines);
  return $desc;
}

// Add a custom endpoint "calendar"
function add_calendar_feed() {
  add_feed('calendar', 'export_ics');
  // Only uncomment these 2 lines the first time you load this script, to update WP rewrite rules, or in case you see a 404
  //   global $wp_rewrite;
  //    $wp_rewrite->flush_rules( false );
}

add_action('init', 'add_calendar_feed');
// Calendar function
function export_ics() {

  // Query the event
  $the_event = new WP_Query([
    'p' => $_REQUEST['id'],
    'post_type' => 'events',
  ]);
  if ($the_event->have_posts()) :
    while ($the_event->have_posts()) : $the_event->the_post();
      // If your version of WP < 5.3.0 use the code below
      /*  The correct date format, for ALL dates is date_i18n('Ymd\THis\Z',time(), true)
          So if your date is not in this format, use that function    */
      //          $start_date = get_field('vf_event_start_date');
      //
      //        $start_date = date_i18n("Ymd\THis\Z", get_post_meta( get_the_ID(), 'vf_event_start_date', true )); // EDIT THIS WITH YOUR OWN VALUE
      //        $end_date = date_i18n("Ymd\THis\Z", get_post_meta( get_the_ID(), 'vf_event_end_date', true )); // EDIT THIS WITH YOUR OWN VALUE
      //        $deadline = date_i18n("Ymd\THis\Z", get_post_meta( get_the_ID(), 'custom-field-of-deadline-date', true )); // EDIT THIS WITH YOUR OWN VALUE
      // Otherwise, if your version of WP >= 5.3.0 use this code
      $end_date = wp_date("Ymd\THis", $start_date + (1 * 60 * 60));

      $start_date = get_post_meta(get_the_ID(), 'vf_event_start_date', TRUE); // EDIT THIS WITH YOUR OWN VALUE
      $start_date_time = get_post_meta(get_the_ID(), 'vf_event_start_time', TRUE); // EDIT THIS WITH YOUR OWN VALUE
      $event_timezone = get_post_meta(get_the_ID(), 'vf_event_time_zone', TRUE);
      date_default_timezone_set($event_timezone);
      $start_timestamp = strtotime($start_date . date("H:i:s", strtotime($start_date_time)));
      $start_iso_time = date_format(date_create('@'. $start_timestamp), 'c') . "\n";
      $end_date = get_post_meta(get_the_ID(), 'vf_event_end_date', TRUE); // EDIT THIS WITH YOUR OWN VALUE
      $end_date_time = get_post_meta(get_the_ID(), 'vf_event_end_time', TRUE); // EDIT THIS WITH YOUR OWN VALUE
      $end_timestamp = strtotime($end_date . date("H:i:s", strtotime($end_date_time)));
      $end_iso_time = date_format(date_create('@'. $end_timestamp), 'c') . "\n";
      if ($start_timestamp != '' && !isValidTimeStamp($start_timestamp)) {
        $start_date = wp_date("Ymd\THis", $start_timestamp);
      }
      if ($end_timestamp != '' && !isValidTimeStamp($end_timestamp)) {
        $end_date = wp_date('Ymd\THis', $end_timestamp);
      }

      $deadline = '';// date_i18n("Ymd\THis\Z", get_post_meta( get_the_ID(), 'custom-field-of-deadline-date', true )); // EDIT THIS WITH YOUR OWN VALUE
      // The rest is the same for any version
      $timestamp = date_i18n('Ymd\THis\Z', time(), TRUE);
      $uid = get_the_ID();
      $created_date = get_post_time('Ymd\THis\Z', TRUE, $uid);
      $organiser = get_bloginfo('name'); // EDIT THIS WITH YOUR OWN VALUE
      $address = ''; // EDIT THIS WITH YOUR OWN VALUE
      $url = get_the_permalink();
      $event_summary = cleanupDesc(trim(get_post_meta(get_the_ID(), 'vf_event_summary', TRUE))) . "<br> Event details - " . $url;
      $summary = get_the_excerpt();
      $content = cleanupDesc(html_entity_decode(trim(preg_replace('/\s\s+/', ' ', get_the_content())))); // removes newlines and double spaces
      $title = html_entity_decode(get_the_title());
      //Give the iCal export a filename
      $filename = urlencode($title . '-ical-' . date('Y-m-d') . '.ics');
      $eol = "\r\n";
      //Collect output
      ob_start();
      // Set the correct headers for this file
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=" . $filename);
      header('Content-type: text/calendar; charset=utf-8');
      header("Pragma: 0");
      header("Expires: 0");
      // The below ics structure MUST NOT have spaces before each line
      // Credit for the .ics structure goes to https://gist.github.com/jakebellacera/635416
      ?>
BEGIN:VCALENDAR<?php echo $eol; ?>
VERSION:2.0<?php echo $eol; ?>
PRODID:-//EMBL-EBI Events//NONSGML Events//EN<?php echo $eol; ?>
CALSCALE:GREGORIAN<?php echo $eol; ?>
X-WR-CALNAME:<?php echo get_bloginfo('name') . $eol; ?>
BEGIN:VEVENT<?php echo $eol; ?>
CREATED:<?php echo $created_date . $eol; ?>
UID:<?php echo $uid . $eol; ?>
DTSTART:<?php echo $start_date . $eol; ?>
DTEND:<?php echo $end_date . $eol; ?>
DTSTAMP:<?php echo $timestamp . $eol; ?>
LOCATION:<?php echo escapeString($address) . $eol; ?>
DESCRIPTION:<?php echo $event_summary . $eol; ?>
X-ALT-DESC;FMTTYPE=text/html:<?php echo $event_summary . $eol; ?>
SUMMARY:<?php echo $title . $eol; ?>
ORGANIZER:<?php echo escapeString($organiser) . $eol; ?>
URL:<?php echo escapeString($url) . $eol; ?>
TRANSP:OPAQUE<?php echo $eol; ?>
BEGIN:VALARM<?php echo $eol; ?>
ACTION:DISPLAY<?php echo $eol; ?>
TRIGGER;VALUE=DATE-TIME:<?php echo $deadline . $eol; ?>
DESCRIPTION:Reminder for <?php echo $title; echo $eol; ?>
END:VALARM<?php echo $eol; ?>
END:VEVENT<?php echo $eol; ?>
<?php
endwhile;
?>
END:VCALENDAR
<?php
//Collect output and echo
$eventsical = ob_get_contents();
ob_end_clean();
echo $eventsical;
exit();
endif;

}

?>
