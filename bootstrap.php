<?php
/*
 *   Plugin Name: DPS Folio Authoring Plugin
 *   Plugin URI: http://www.adobe.com
 *   Description: A Wordpress plugin to author Adobe DPS folios
 *   Version: 1.0
 *   Author: Studio Mercury & Coffee + Code
 *   Author URI: TODO
 *   Author Email: TODO
 *   License: See license file
 *
 */

/*
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die( 'Access denied.' );
}

define( 'DPSFA_NAME',					'Digital Publishing Suite Folio Author' );
define( 'DPSFA_SLUG',					'dps_folio_author' );
define( 'DPSFA_REQUIRED_PHP_VERSION',	'5.3' );	// because of get_called_class()
define( 'DPSFA_REQUIRED_WP_VERSION',	'3.5' );	// because of wp_image_editor class

define( 'DPSFA_DIR_NAME',                basename(__DIR__) );
define( 'DPSFA_DIR',					 ABSPATH . 'wp-content/plugins/' . DPSFA_DIR_NAME );
define( 'DPSFA_URL',					 get_bloginfo('wpurl') . '/wp-content/plugins/' . DPSFA_DIR_NAME );

define( 'DPSFA_ASSETS_DIR',				 DPSFA_DIR . "/assets/" );
define( 'DPSFA_ASSETS_URL',	             esc_url( trailingslashit( plugins_url( '/assets/admin/', __FILE__ )) ) );

define( 'DPSFA_VERSION_META',	         DPSFA_SLUG . "_version" );

function dpsfa_requirementsMet(){
	global $wp_version;
	//require_once( ABSPATH .'/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early

	if( version_compare( PHP_VERSION, DPSFA_REQUIRED_PHP_VERSION, '<' ) )
		return false;

	if( version_compare( $wp_version, DPSFA_REQUIRED_WP_VERSION, '<' ) )
		return false;

	return true;
}

function dpsfa_requirementsError(){
	global $wp_version;
	$class = 'error';

	ob_start();
	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
	$message = ob_get_contents();
	ob_end_clean();
}

if( dpsfa_requirementsMet() ){
    require_once( dirname( __FILE__ ) . '/libs/simple_html_dom/simple_html_dom.php' );
    require_once( dirname( __FILE__ ) . '/classes/dpsfa-module.php' );
	require_once( dirname( __FILE__ ) . '/classes/dpsfa.php' );

	if( class_exists( 'DPSFolioAuthor' ) )
	{
		$GLOBALS['dpsfa'] = DPSFolioAuthor::getInstance();
		register_activation_hook( __FILE__,		array( $GLOBALS['dpsfa'], 'activate' ) );
		register_deactivation_hook( __FILE__,	array( $GLOBALS['dpsfa'], 'deactivate' ) );
		register_deactivation_hook( __FILE__,	array( $GLOBALS['dpsfa'], 'uninstall' ) );
	}
}
else
	add_action( 'admin_notices', 'dpsfa_requirementsError' );
