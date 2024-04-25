<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * CSS_Vars is a class that outputs css vars from options.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class CSS_Vars extends Init {

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
			add_action( 'admin_enqueue_scripts', [ $this, 'append_css_vars_to_admin_stylesheet' ], 20 );
			add_action( 'wp_enqueue_scripts', [ $this, 'append_css_vars_to_main_stylesheet' ], 20 );
			add_action( 'enqueue_block_assets', [ $this, 'append_css_vars_to_editor_stylesheet' ], 20 );
		}

		/**
		 * Checks if display settings are enabled.
		 */
		public static function display_settings_enabled(): bool {
			return (bool) Admin::get_option( 'use_display_settings', true );
		}

		/**
		 * Appends vars to admin stylesheet.
		 */
		public static function append_css_vars_to_admin_stylesheet(): void {
			self::append_css_vars_to_stylesheet( self::$prefix . 'admin_styles' );
		}

		/**
		 * Appends vars to main stylesheet.
		 */
		public static function append_css_vars_to_main_stylesheet(): void {
			self::append_css_vars_to_stylesheet( self::$prefix . 'main_styles' );
		}

		/**
		 * Appends vars to main stylesheet.
		 */
		public static function append_css_vars_to_editor_stylesheet(): void {
			self::append_css_vars_to_stylesheet( self::$prefix . 'editor_styles' );
		}

		/**
		 * Adds display CSS vars to given stylesheet.
		 *
		 * @param string $stylesheet_handle registered handle to append vars to.
		 */
		public static function append_css_vars_to_stylesheet( $stylesheet_handle ): void {
			if ( ! self::display_settings_enabled() ) {
				return;
			}

			$display_settings = Admin::get_settings( 'display' );
			$custom_css       = ':root {';

			foreach ( $display_settings as $setting => $options ) {

				if ( ! (bool) $options['create_var'] ) {
					continue;
				}

				$custom_css .= self::create_css_variable( $setting, $options['default'] );

				if ( ! empty( $options['unit'] ) ) {
					$custom_css .= $options['unit'];
				}

				$custom_css .= ';';
			}

			$custom_css .= '}';

			wp_add_inline_style( $stylesheet_handle, $custom_css );
		}

		/**
		 * Creates a CSS Variable string given an option slug.
		 *
		 * @param string $option_slug
		 *
		 * @return string The option turned into a css variable, where '--option-slug: value;'
		 */
		public static function create_css_variable( $option_slug, $default ): string {
			return '--' . str_replace( '_', '-', self::$prefix . $option_slug ) . ':' . ' ' . Admin::get_option( $option_slug, $default );
		}
	}
}
