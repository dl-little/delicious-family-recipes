<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Enqueue is a class that handles enqueueing of main stylesheet and scripts.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Enqueue extends Init {

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
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10 );
		}

		/**
		 * Enqueues the main style and script.
		 */
		public static function enqueue_scripts(): void {
			wp_register_style( self::$prefix . 'main_styles', DFR_PLUGIN_URL . '/assets/dist/main.css' );
			wp_enqueue_style( self::$prefix . 'main_styles' );
		}
	}
}
