<?php

class wellthemesShortcodes {

	function __construct() {
		define( 'WELLTHEMES_SHORTCODES_URL',  get_template_directory_uri().'/framework/shortcodes' );
    	define('WELLTHEMES_TINYMCE_URI', WELLTHEMES_SHORTCODES_URL .'/tinymce');
	    add_action('init', array(&$this, 'action_admin_init'));        
        add_action('admin_enqueue_scripts', array(&$this, 'action_admin_scripts_init'));      
	}		
	
	/**
	 * Enqueue Scripts and Styles
	 *
	 * @return	void
	 */
	function action_admin_scripts_init() {
		wp_localize_script( 'jquery', 'wellthemesShortCodes', array('plugin_folder' => WELLTHEMES_SHORTCODES_URL) );
	}
	
	/**
	 * Registers TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function action_admin_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
	
		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
			add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Defines TinyMCE rich editor js plugin
	 *
	 * @return	void
	 */
	function add_rich_plugins( $plugin_array ) {	
		$plugin_array['wellthemesShortcodes'] = WELLTHEMES_TINYMCE_URI . '/plugin.js';		
		return $plugin_array;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function register_rich_buttons( $buttons ) {	
		array_push( $buttons, "|", 'wellthemes_button' );		
		return $buttons;
	}    
}

$wellthemesShortcodes = new wellthemesShortcodes();

?>