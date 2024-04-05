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
			add_action( 'admin_menu', [ $this, 'register_admin_settings' ], 20 );
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
				[ $this, 'render_menu_page' ]
			);
		}

		/**
		 * Creates the layout of the settings page.
		 */
		public function render_menu_page(): void {
			$this->render_template('page');
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
		 * Registers the settings for the admin page.
		 */
		public function register_admin_settings(): void {
			$sections = [
				'display' => [
					'font_size' => 'number',
				],
				'optimization' => [
					'minification' => 'number'
				]
			];

			// NOT WORKING - MAYBE USE DYNAMIC METHOD NAME INSTEAD OF CLOSURE.

			foreach( $sections as $section => $fields ) {
				$this->section = $section;

				add_settings_section(
					self::$slug . '-' . $this->section,
					strtoupper( $this->section ),
					[ $this, 'render_section' ],
					$this->page_slug
				);

				foreach( $fields as $field => $type ) {
					$this->field = $field;

					register_setting( $this->page_slug, self::$prefix . $this->field );
					add_settings_field(
						self::$slug . '-' . $this->field,
						$this->field,
						function() use ( $type ) {
							$this->render_template( $type );
						},
						$this->page_slug,
						self::$slug . '-' . $this->section
					);
				}
			}
		}

		/**
		 * Renders a settings section.
		 */
		public function render_section(): void {
			$this->render_template( 'section' );
		}
	}
}
