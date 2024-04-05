<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * CSS_Vars is a class that outputs css vars from options.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class CSS_Vars extends Init {
		/**
		 * Class instance.
		 *
		 * @var ?self
		 */
		public static $instance = null;

		/**
		 * Instantiate class and load subclasses.
		 *
		 * @return object class.
		 */
		public static function get_instance(): object {
			if ( ! self::$instance ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Actions on class instantiation.
		 */
		public function init(): void {
			error_log('asd');
		}
	}
}
