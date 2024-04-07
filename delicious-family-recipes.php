<?php
/**
 * Plugin Name: Delicious Family Recipes
 * Description: Provides additional functionality to Delicious Family Recipes that will not be overridden by updating the theme.
 * Author: Douglas Little
 * Version: 0.0.1
 * @package Delicious_Family_Recipes
 * @version 0.0.1
*/

use DFR\Init;

// Prevent direct access.
if (
	! defined( 'ABSPATH' )
	|| !function_exists( 'add_action' )
) {
	exit( 'This plugin requires WordPress.' );
}

// Ensure autoload map exists.
if ( ! file_exists( plugin_dir_path(__FILE__) . 'lib/autoload.php' ) ) {
	exit( 'Installation is messssed up.' );
}

if ( ! defined('DFR_DIR') ) {
	define( 'DFR_DIR', __DIR__ );
}

if ( ! defined( 'DFR_PLUGIN_URL' ) ) {
	define( 'DFR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once( plugin_dir_path( __FILE__ ) . 'lib/autoload.php' );

Init::get_instance();
