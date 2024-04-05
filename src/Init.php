<?php 

namespace DFR;

/**
 * Init initializes the plugin.
 *
 * @package Delicious_Family_Recipes
 */
class Init {
	/**
	 * Class instance.
	 *
	 * @var ?self
	 */
	public static $instance = null;

	/**
	 * CSS_Vars instance.
	 *
	 * @var ?CSS_Vars
	 */
	public $css_vars;

	/**
	 * Instantiate class and load subclasses.
	 *
	 * @return object class.
	 */
	public static function get_instance(): object {
		if ( ! self::$instance ) {
			self::$instance = new self();

			// Load classes.
			self::$instance->css_vars = CSS_Vars::get_instance();
		}

		return self::$instance;
	}
}
