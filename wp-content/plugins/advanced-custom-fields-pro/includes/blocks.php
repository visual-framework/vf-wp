<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('ACF_Blocks') ) :

class ACF_Blocks {
	
	/** @var array Storage for registered blocks */
	var $blocks = array();
	
	/** @var array Storage for the current block */
	var $block = false;
		
	/**
	*  __construct
	*
	*  Sets up the class functionality.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	void
	*  @return	void
	*/
	function __construct() {
		
		// Register actions.
		add_action('acf/enqueue_scripts', array($this, 'enqueue_scripts'));
		
		// Register ajax actions.
		acf_register_ajax('render_block_edit', array($this, 'ajax_render_block_edit'));
		acf_register_ajax('render_block_preview', array($this, 'ajax_render_block_preview'));
	}
	
	/**
	*  get_block_type_default_attributes
	*
	*  Returns an array of default attribute settings for a block type.
	*
	*  @date	19/11/18
	*  @since	5.8.0
	*
	*  @param	void
	*  @return	array
	*/
	function get_block_type_default_attributes() {
		return array(
			'className'	=> array(
				'type'		=> 'string',
				'default'	=> '',
			),
			'align'		=> array(
				'type'		=> 'string',
				'default'	=> '',
			),
			'data'		=> array(
				'type'		=> 'object',
				'default'	=> '',
			),
			'id'		=> array(
				'type'		=> 'string',
				'default'	=> '',
			),
			'name'		=> array(
				'type'		=> 'string',
				'default'	=> '',
			),
			'mode'		=> array(
				'type'		=> 'mode',
				'default'	=> '',
			),
		);
	}
	
	/**
	*  validate_block_type
	*
	*  Validates a block type ensuring all settings exist.
	*  Called when registering a block.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	array $block
	*  @return	array
	*/
	function validate_block_type( $block ) {
		
		// Add default settings.
		$block = wp_parse_args($block, array(
			'name'				=> '',
			'title'				=> '',
			'description'		=> '',
			'category'			=> 'common',
			'icon'				=> '',
			'mode'				=> 'preview',
			'align'				=> '',
			'keywords'			=> array(),
			'supports'			=> array(),
			'post_types'		=> array(),
			'render_template'	=> '',
			'render_callback'	=> false
		));
		
		// Restrict keywords to 3 max to avoid JS error.
		$block['keywords'] = array_slice($block['keywords'], 0, 3);
		
		// Generate name with prefix.
		$block['name'] = acf_slugify( 'acf/' . $block['name'] );
		
		// Add default 'supports' settings.
		$block['supports'] = wp_parse_args($block['supports'], array(
			'align'		=> true,
			'html'		=> false,
			'mode'		=> true,
		));
		
		// Return block.
		return $block;
	}
	
	/**
	*  prepare_block
	*
	*  Prepares a block for use in render_callback by merging in all settings and attributes.
	*  Default attributes are different from the block type's default settings.
	*
	*  @date	19/11/18
	*  @since	5.8.0
	*
	*  @param	array $block
	*  @return	array
	*/
	function prepare_block( $block ) {
		
		// Bail early if no name.
		if( !isset($block['name']) ) {
			return false;
		}
		
		// Get block type and return false if doesn't exist.
		$block_type = acf_get_block_type( $block['name'] );
		if( !$block_type ) {
			return false;
		}
		
		// Generate default attributes.
		$attributes = array();
		foreach( $this->get_block_type_default_attributes() as $k => $v ) {
			$attributes[ $k ] = $v['default'];
		}
		
		// Merge together arrays in order of least to most specific.
		$block = array_merge($block_type, $attributes, $block);
		
		// Return block.
		return $block;
	}
	
	/**
	*  add_block_type
	*
	*  Adds a new block type to storage or returns false if already exists.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	array $block The block settings.
	*  @return	array|false
	*/
	function add_block_type( $block ) {
		
		// Validate block type settings.
		$block = $this->validate_block_type( $block );
		
		// Bail early if already exists.
		if( $this->has_block_type($block['name']) ) {
			return false;
		}
		
		// Add to storage.
		$this->blocks[ $block['name'] ] = $block;
		
		// Check function exists.
		if( function_exists('register_block_type') ) {
			
			// Register block type.
			register_block_type($block['name'], array(
				'attributes'		=> $this->get_block_type_default_attributes(),
				'render_callback'	=> array( $this, 'render_callback' ),
			));
		}
		
		// Return block.
		return $block;
	}
	
	/**
	*  has_block_type
	*
	*  Returns true if a block exists for the given name.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	string $name The block name.
	*  @return	boolean
	*/
	function has_block_type( $name ) {
		return isset( $this->blocks[ $name ] );
	}
	
	/**
	*  get_block_type
	*
	*  Returns a block type for the given name.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	string $name The block name.
	*  @return	array|false
	*/
	function get_block_type( $name ) {
		return isset( $this->blocks[ $name ] ) ? $this->blocks[ $name ] : false;
	}
	
	/**
	*  remove_block_type
	*
	*  Removes a block type for the given name.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	string $name The block name.
	*  @return	boolean
	*/
	function remove_block_type( $name ) {
		
		// Return false if desn't exist.
		if( !$this->has_block_type($name) ) {
			return false;
		
		// Unset and return true.
		} else {
			unset( $this->blocks[ $name ] );
			return true;
		}
	}
	
	/**
	*  get_block_types
	*
	*  Returns all block types for the given args.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	array $args
	*  @return	array
	*/
	function get_block_types( $args = array() ) {
		return $this->blocks;
	}
	
	/**
	*  render_callback
	*
	*  Renders the block HTML (frontend + preview)
	*  Called by WP core and the ajax preview request.
	*
	*  @date	11/4/18
	*  @since	5.8.0
	*
	*  @param	array $block The block props.
	*  @param	string $content The block content (emtpy string).
	*  @param	bool $is_preview True during AJAX preview.
	*  @return	string The block HTML.
	*/
	function render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		
		// Prepare block ensuring all settings and attributes exist.
		// Return false if anything went wrong.
		$block = $this->prepare_block( $block );
		if( !$block ) {
			return '';
		}
		
		// Find post_id if not defined.
		if( !$post_id ) {
			$post_id = get_the_ID();
		}
		
		// Setup postdata allowing get_field() to work.
		acf_setup_postdata( $block['data'], $block['id'], true );
		
		// Start output capture.
		ob_start();
		
		// Call render_callback.
		if( is_callable( $block['render_callback'] ) ) {
			call_user_func( $block['render_callback'], $block, $content, $is_preview, $post_id );
		
		// Or include template.
		} elseif( $block['render_template'] ) {
			
			// Locate template.
			if( file_exists($block['render_template']) ) {
				$path = $block['render_template'];
		    } else {
			    $path = locate_template( $block['render_template'] );
		    }
		    
		    // Include template.
		    if( file_exists($path) ) {
			    include( $path );
		    }
		}
		
		// Record output.
		$html = ob_get_contents();
		ob_end_clean();
		
		// Reset postdata.
		acf_reset_postdata( $block['id'] );
		
		// Return html.
		return $html;
	}
	
	/**
	*  get_block_fields
	*
	*  Returns an array of all fields for the given block.
	*
	*  @date	24/10/18
	*  @since	5.8.0
	*
	*  @param	array $block The block props.
	*  @return	array
	*/
	function get_block_fields( $block ) {
		
		// Vars.
		$fields = array();
		
		// Get field groups for this block.
		$field_groups = acf_get_field_groups( array(
			'block'	=> $block['name']
		));
				
		// Loop over results and append fields.
		if( $field_groups ) {
		foreach( $field_groups as $field_group ) {
			$_fields = acf_get_fields( $field_group );
			if( $_fields ) {
				$fields += $_fields;
			}
		}}
		
		// Return fields.
		return $fields;
	}
	
	/**
	*  ajax_render_block_edit
	*
	*  AJAX callback to render block fields.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	void
	*  @return	void
	*/
	function ajax_render_block_edit() {
		
		// Validate ajax request.
		if( !acf_verify_ajax() ) {
			 die();
		}
		
   		// Get block from $_POST.
   		$block = acf_maybe_get_POST('block');
   		if( !$block ) {
	   		die();
   		}
   		
   		// Unslash $_POST data.
   		$block = wp_unslash($block);
   		
   		// Prepare block ensuring all settings and attributes exist.
		// Return false if anything went wrong.
		$block = $this->prepare_block( $block );
		if( !$block ) {
			die();
		}
		
		// Setup postdata allowing get_field() to work.
		acf_setup_postdata( $block['data'], $block['id'], true );
		
		// Get fields for block.
		$fields = $this->get_block_fields( $block );
		
		// Prefix field inputs to avoid multiple blocks using the same name/id attributes.
		acf_prefix_fields( $fields, "acf-{$block['id']}" );
				
		// Render fields.
		acf_render_fields( $fields, $block['id'], 'div', 'field' );
		
		// Reset postdata.
		acf_reset_postdata( $block['id'] );
		
		// End ajax.
		die;
	}
	
	/**
	*  ajax_render_block_preview
	*
	*  AJAX callback to render block preview.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	void
	*  @return	void
	*/
	function ajax_render_block_preview() {
		
		// Validate ajax request.
		if( !acf_verify_ajax() ) {
			 die();
		}
		
   		// Get block from $_POST.
   		$block = acf_maybe_get_POST('block');
   		if( !$block ) {
	   		die();
   		}
   		
   		// Unslash $_POST data.
   		$block = wp_unslash($block);
   		
   		// When first previewing block no data exists.
   		// Create data using default_value settings.
   		if( empty($block['data']) ) {
	   		$block['data'] = array();
	   		$fields = $this->get_block_fields( $block );
	   		if( $fields ) {
		   	foreach( $fields as $field ) {
		   		$block['data'][ $field['key'] ]	= isset($field['default_value']) ? $field['default_value'] : '';
	   		}}
   		}
		
   		// Render_callback vars.
   		$content = '';
   		$is_preview = true;
   		$post_id = acf_maybe_get_POST('post_id');
   		
   		// Render preview.
   		echo $this->render_callback( $block, $content, $is_preview, $post_id );
   		die;
	}
		
	/**
	*  enqueue_scripts
	*
	*  Enqueues scripts, styles and localizes data.
	*
	*  @date	10/4/18
	*  @since	5.8.0
	*
	*  @param	void
	*  @return	void
	*/
	function enqueue_scripts() {
		
		// Localize text.
		acf_localize_text(array(
			'Switch to Edit'		=> __('Switch to Edit', 'acf'),
			'Switch to Preview'		=> __('Switch to Preview', 'acf'),
		));
		
		// Localize data.
		acf_localize_data(array(
			'blockTypes'	=> array_values( $this->get_block_types() )
		));
	}
}

// instantiate
acf_new_instance('ACF_Blocks');

endif; // class_exists check

function acf_register_block( $block ) {
	return acf_register_block_type( $block );
}

function acf_register_block_type( $block ) {
	return acf_get_instance('ACF_Blocks')->add_block_type( $block );
}

function acf_has_block_type( $name ) {
	return acf_get_instance('ACF_Blocks')->has_block_type( $name );
}

function acf_get_block_types() {
	return acf_get_instance('ACF_Blocks')->get_block_types();
}

function acf_get_block_type( $name ) {
	return acf_get_instance('ACF_Blocks')->get_block_type( $name );
}

function acf_remove_block_type( $name ) {
	return acf_get_instance('ACF_Blocks')->remove_block_type( $name );
}

?>