<?php
	/**
	 * @package Country Selector
	 */
	/*
	Plugin Name: Country Selector
	Plugin URI: -
	Description: Country selector plugin allows to redirect users based on their country selected in frontend popup.
	Version: 1.0
	Author: Chandreshgiri Goswami
	Author URI: 
	License: GPLv2 or later
	Text Domain: country-selector
	*/

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
	    die('Oops, not allowed');
	}

	// Define constants
	define('CNTSEL_VERSION', '1.0');
	define('CNTSEL_PLUGIN_FILE_URL', __FILE__ );
	define('CNTSEL_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));
	define('CNTSEL_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));

	// Include constants, admin files
	require_once( CNTSEL_PLUGIN_DIR_PATH . 'includes/install.php' );
	require_once( CNTSEL_PLUGIN_DIR_PATH . 'includes/functions.php' );
	require_once( CNTSEL_PLUGIN_DIR_PATH . 'admin/config.php' );

	// Enqueue frontend styles and scripts
	add_action( 'wp_enqueue_scripts', 'country_selector_enqueue_frontend_script' );
	function country_selector_enqueue_frontend_script() {
		// Enqueue styles
	    wp_enqueue_style( 'country-selector', CNTSEL_PLUGIN_DIR_URL . 'styles/cs-style.css', array(), CNTSEL_VERSION );
	    wp_enqueue_style( 'magnific-popup', CNTSEL_PLUGIN_DIR_URL . 'styles/magnific-popup.css', array(), CNTSEL_VERSION );
	    wp_enqueue_style( 'cs-chosen', CNTSEL_PLUGIN_DIR_URL . 'common/styles/chosen.min.css', array(), CNTSEL_VERSION );

	    // Enqueue scripts
	    wp_enqueue_script( 'magnific-popup', CNTSEL_PLUGIN_DIR_URL . 'scripts/jquery.magnific-popup.js', array('jquery'), CNTSEL_VERSION );
	    wp_enqueue_script( 'country-selector', CNTSEL_PLUGIN_DIR_URL . 'scripts/country-selector.js', array('jquery'), CNTSEL_VERSION );
	    wp_enqueue_script( 'cs-chosen', CNTSEL_PLUGIN_DIR_URL . 'common/scripts/chosen.jquery.min.js', array(), CNTSEL_VERSION );

	    $custom_params = array(
           'popup_enable' => get_option('cs_popup_enable', '')
       	);
       	wp_localize_script( 'country-selector', 'custom_params', $custom_params );
	}