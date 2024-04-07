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
		private $template_path = DFR_DIR . '/templates';

		/**
		 * Current Field.
		 *
		 * @var ?string
		 */
		public $field = null;

		/**
		 * Current Section.
		 *
		 * @var ?string
		 */
		public $section = null;

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

				foreach( $fields as $field ) {

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
		 * Renders a template given its slug.
		 *
		 * @var string $template_slug
		 */
		private function render_template( $template_slug ): void {
			$path = $this->template_path . '/' . $template_slug . '.php';

			if ( ! is_readable( $path ) ) {
				return;
			}

			include $path;
		}

		/**
		 * Enqueues the stylesheet and javascript for the dfr admin.
		 */
		public function admin_enqueue_scripts(): void {
			// Early return if we're not on our admin page.
			if ( get_current_screen()->base !== 'appearance_page_'. $this->page_slug ) {
				return;
			}

			wp_enqueue_style( self::$prefix . 'admin_styles', DFR_PLUGIN_URL . '/assets/dist/adminStyles.css' );
		}

		/**
		 * Returns an array of all settings in Admin page.
		 *
		 * @return array
		 */
		public static function get_settings(): array {
			return [
				'display' => [
					'body_font_size',
					'body_font_family',
					'header_font_family'
				]
			];
		}
	}
}
