<?php

if( ! defined( 'ABSPATH' ) ) exit;

require_once('functions/groups-theme.php');

global $vf_groups_theme;
if ( ! isset($vf_groups_theme)) {
  $vf_groups_theme = new VF_Groups_Theme();
}

?>
