<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Fonts is a class that handles fonts.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Fonts extends Init {

		use DFR_Shared_Properties;

		/**
		 * Class instance.
		 *
		 * @var ?self
		 */
		public static $instance = null;

        /**
         * Font families.
         *
         * @var ?array
         */
        public static $font_families = null;

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
            $json                = file_get_contents( __DIR__ . '/../assets/fonts.json' );
            self::$font_families = json_decode( $json, true )['fontFamilies'];
		}

        /**
         * Returns font families.
         *
         * @return array
         */
        public static function get_font_families(): array {
            return self::$font_families;
        }
	}
}
