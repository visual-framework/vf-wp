<?php

if( ! defined( 'ABSPATH' ) ) exit;

require_once('functions/walker-comment.php');
require_once('functions/admin.php');
require_once('functions/theme.php');
require_once('functions/widgets.php');
require_once('functions/pagination.php');
require_once('functions/comment-form.php');

global $vf_admin;
if ( ! isset($vf_admin)) {
  $vf_admin = new VF_Admin();
}

global $vf_theme;
if ( ! isset($vf_theme)) {
  $vf_theme = new VF_Theme();
}

global $vf_widgets;
if ( ! isset($vf_widgets)) {
  $vf_widgets = new VF_Widgets();
}

?>
