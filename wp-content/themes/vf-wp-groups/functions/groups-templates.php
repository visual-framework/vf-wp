<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Groups_Templates') ) :

class VF_Groups_Templates {

  public function __construct() {
    add_filter(
      'vf/templates/post_content/default',
      array($this, 'post_content_default')
    );
  }

  public function post_content_default($post_content) {
    return '

<!-- wp:vf/container-global-header /-->

<!-- wp:vf/container-breadcrumbs /-->

<!-- wp:vf/container-wp-groups-header /-->

<!-- wp:vf/container-page-template /-->

<!-- wp:vf/container-embl-news /-->

<!-- wp:vf/container-global-footer /-->

';
  }

} // VF_Groups_Templates

endif;

?>
