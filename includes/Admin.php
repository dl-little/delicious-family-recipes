<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Admin is a class that creates the admin view.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Admin extends Init {

		use DFR_Shared_Properties;

		/**
		 * Class instance.
		 *
		 * @var ?self
		 */
		public static $instance = null;

		/**
		 * Admin page slug.
		 *
		 * @var string
		 */
		public $page_slug = 'dfr_settings';

		/**
		 * Path to admin page template.
		 *
		 * @var string
		 */
		private $template_path = DFR_DIR . '/admin/templates';

		/**
		 * Admin settings.
		 *
		 * @var ?array
		 */
		public static $settings = null;

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
			add_action( 'admin_menu', [ $this, 'add_admin_page_to_appearance_menu' ] );
			add_action( 'admin_init', [ $this, 'register_admin_settings' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts'] );

			self::$settings = [
				'display' => [
					'use_display_settings' => [
						'default' => true,
						'create_var' => false
					],
					'body_font_family' => [
						'default' => "-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif",
						'create_var' => true
					],
					'body_font_size' => [
						'default' => '18',
						'unit' => 'px',
						'create_var' => true
					],
					'header_font_family' => [
						'default' => "-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif",
						'create_var' => true
					],
					'primary_color' => [
						'default' => '#113946',
						'create_var' => true
					],
					'secondary_color' => [
						'default' => '#BCA37F',
						'create_var' => true
					],
					'accent_color' => [
						'default' => '#EAD7BB',
						'create_var' => true
					],
					'tertiary_color' => [
						'default' => '#FFF2D8',
						'create_var' => true
					]
				]
			];
		}

		/**
		 * Creates a link to the admin page in the Appearance submenu.
		 */
		public function add_admin_page_to_appearance_menu(): void {
			add_theme_page(
				'Delicious Family Recipes Settings',
				'DFR Settings',
				'edit_theme_options',
				$this->page_slug,
				function() {
					$this->render_template( 'page' );
				}
			);
		}

		/**
		 * Registers the settings for the admin page.
		 */
		public function register_admin_settings(): void {
			$sections = self::get_settings();

			foreach( $sections as $section => $fields ) {

				add_settings_section(
					self::$slug . '-' . $section,
					ucwords( $section ),
					function() use ( $section ) {
						$this->render_template( $section );
					},
					$this->page_slug
				);

				foreach( $fields as $field => $args ) {

					register_setting( $this->page_slug, self::$prefix . $field );

					add_settings_field(
						self::$slug . '-' . $field,
						str_replace( '_', ' ', ucwords( $field, '_' ) ),
						function() use ( $field ) {
							$this->render_template( $field );
						},
						$this->page_slug,
						self::$slug . '-' . $section
					);
				}
			}
		}

		/**
		 * Renders a php template given its slug.
		 *
		 * @param string $template_slug
		 */
		private function render_template( $template_slug ): void {
			$path = $this->template_path . '/' . $template_slug;
			$path .= $template_slug === 'display' ? '.html' : '.php';

			if ( ! is_readable( $path ) ) {
				error_log($path);
				return;
			}

			include $path;
		}

		/**
		 * Enqueues the stylesheet and javascript for the dfr admin.
		 *
		 * @param $hook_suffix The admin page suffix.
		 */
		public function admin_enqueue_scripts( $hook_suffix ): void {
			// Early return if we're not on our admin page.
			if (
				get_current_screen()->base !== 'appearance_page_'. $this->page_slug
				&& $hook_suffix !== 'term.php'
			) {
				return;
			}

			wp_register_style( self::$prefix . 'admin_styles', DFR_PLUGIN_URL . 'admin/css/adminStyles.css' );
			wp_enqueue_style( self::$prefix . 'admin_styles' );
			
			wp_register_script( self::$prefix . 'admin_script', DFR_PLUGIN_URL . 'admin/js/adminScript.js', [], false, true );
			wp_enqueue_script( self::$prefix . 'admin_script' );

			wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( self::$prefix . 'color_picker', DFR_PLUGIN_URL . 'admin/js/colorPicker.js', array( 'wp-color-picker' ), false, true );
		}

		/**
		 * Returns an array of settings in Admin page.
		 *
		 * @param ?string $type The type of setting to grab from array. Default is all.
		 * 
		 * @return array
		 */
		public static function get_settings( $type = '' ): array {
			if ( ! empty( $type ) && array_key_exists( $type, self::$settings ) ) {
				return self::$settings[$type];
			}

			return self::$settings;
		}

		/**
		 * Prepends the site prefix to get_option calls.
		 *
		 * @param string $option_slug
		 */
		public static function get_option( $option_slug, $default = false ): mixed {
			return get_option( self::$prefix . $option_slug, $default );
		}

		/**
		 * Prepends the site prefix to update_option calls.
		 *
		 * @param string $option_slug
		 */
		public static function update_option( $option_slug, $value ): mixed {
			return update_option( self::$prefix . $option_slug, $value );
		}
	}
}
