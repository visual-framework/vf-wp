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

<!-- wp:acf/vf-container-global-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-header"} /-->

<!-- wp:acf/vf-container-breadcrumbs {"id":"' . uniqid('block_') . '","name":"acf/vf-container-breadcrumbs"} /-->

<!-- wp:acf/vf-container-wp-groups-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-wp-groups-header"} /-->

<!-- wp:acf/vf-container-page-template {"id":"' . uniqid('block_') . '","name":"acf/vf-container-page-template"} /-->

<!-- wp:acf/vf-container-embl-news {"id":"' . uniqid('block_') . '","name":"acf/vf-container-embl-news"} /-->

<!-- wp:acf/vf-container-global-footer {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-footer"} /-->

';
  }

} // VF_Groups_Templates

endif;

?>
