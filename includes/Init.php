<?php 

namespace DFR;

/**
 * Init initializes the plugin.
 *
 * @package Delicious_Family_Recipes
 */
class Init {

	use DFR_Shared_Properties;

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
	 * Fonts instance.
	 *
	 * @var ?Fonts
	 */
	public $fonts;

	/**
	 * Admin instance.
	 *
	 * @var ?Admin
	 */
	public $admin;

	/**
	 * Enqueue instance.
	 *
	 * @var ?Enqueue
	 */
	public $enqueue;

	/**
	 * Categories instance.
	 *
	 * @var ?Categories
	 */
	public $categories;

	/**
	 * Instantiate class and load subclasses.
	 *
	 * @return object class.
	 */
	public static function get_instance(): object {
		if ( ! self::$instance ) {
			self::$instance = new self();

			// Load classes.
			self::$instance->css_vars    = CSS_Vars::get_instance();
			self::$instance->admin       = Admin::get_instance();
			self::$instance->fonts       = Fonts::get_instance();
			self::$instance->enqueue     = Enqueue::get_instance();
			self::$instance->categories  = Categories::get_instance();
		}

		return self::$instance;
	}
}
