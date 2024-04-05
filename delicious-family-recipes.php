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
	exit( 'This plugin requires WordPress' );
}

require_once( plugin_dir_path(__FILE__) . '/vendor/autoload.php');

Init::get_instance();
