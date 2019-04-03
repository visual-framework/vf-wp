<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('ACF_Form_Gutenberg') ) :

class ACF_Form_Gutenberg {
	
	/**
	*  __construct
	*
	*  Setup for class functionality.
	*
	*  @date	13/2/18
	*  @since	5.6.9
	*
	*  @param	n/a
	*  @return	n/a
	*/
		
	function __construct() {
		
		// Modify metaboxes for Gutenberg.
		add_filter( 'filter_gutenberg_meta_boxes', 		array($this, 'filter_gutenberg_meta_boxes'), 99, 1 );
		add_filter( 'filter_block_editor_meta_boxes', 	array($this, 'filter_gutenberg_meta_boxes'), 99, 1 );
	}
	
	/**
	*  filter_gutenberg_meta_boxes
	*
	*  description
	*
	*  @date	6/11/18
	*  @since	5.8.0
	*
	*  @param	type $var Description. Default.
	*  @return	type Description.
	*/
	function filter_gutenberg_meta_boxes( $wp_meta_boxes) {
		add_action('admin_footer', array($this, 'admin_footer'));
		return $wp_meta_boxes;
	}
	
	/**
	*  admin_footer
	*
	*  Append missing HTML to Gutenberg editor.
	*
	*  @date	13/2/18
	*  @since	5.6.9
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function admin_footer() {
		
		// edit_form_after_title is not run due to missing action, call this manually
		?>
		<div id="acf-form-after-title">
			<?php acf_get_instance('ACF_Form_Post')->edit_form_after_title(); ?>
		</div>
		<?php
		
		
		// move #acf-form-after-title
		?>
		<script type="text/javascript">
			$('#normal-sortables').before( $('#acf-form-after-title') );
		</script>
		<?php
	}		
}

acf_new_instance('ACF_Form_Gutenberg');

endif;

?>