<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Filters is a class that adds filters.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Filters extends Init {

		use DFR_Shared_Properties;

		/**
		 * Class instance.
		 *
		 * @var ?self
		 */
		public static $instance = null;

		/**
		 * Instantiate class.
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
			add_filter( 'body_class', [ $this, 'add_dfr_body_class' ] );
			add_filter( 'body_class', [ $this, 'add_hidden_class_title_to_homepage' ] );
		}

		/**
		 * Adds the dfr class to the body element.
		 *
		 * @param array $classes classlist of body classes.
		 */
		public static function add_dfr_body_class( $classes ): array {
			$classes[] = self::$slug;
			return $classes;
		}

		/**
		 * Hides the homepage title.
		 *
		 * @param array $classes classlist of body classes.
		 */
		public static function add_hidden_class_title_to_homepage( $classes ): array {
			if ( ! is_front_page() ) {
				return $classes;
			}

			$classes[] = self::$prefix . 'hidden_title';
			return $classes; 
		}
	}
}
