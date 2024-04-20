<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Enqueue is a class that handles custom functionality for categories.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Categories extends Init {

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
			add_action( 'category_edit_form_fields', [ $this, 'add_category_image_field' ], 10 );
			add_action( 'edited_category', [ $this, 'save_category_fields' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_category_script' ] );
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		}

		/**
		 * Adds the category image field to category edit screen.
		 */
		public static function add_category_image_field( $tag ): void {
			$id              = $tag->term_id;
			$category_images = Admin::get_option( 'category_images', [] );
			$image_slug      = $id . '_icon';
			?>
				<tr 
					class="form-field" 
					id="<?php echo self::$prefix . 'image_field' ?>" 
					data-slug="<?php echo $tag->slug; ?>"
				>
					<th scope="row" valign="top">
						<label for="term_meta[<?php echo $image_slug; ?>]"><?php _e('Category Image'); ?></label>
					</th>
					<td class="wp-media-buttons">
						<div 
							id="<?php echo self::$prefix . 'image_container'; ?>" 
							class="<?php echo self::$prefix , ( ! empty( $category_images[ $image_slug ] ) ) ? 'image_chosen' : 'image_not_chosen' ?>" 
						>
							<?php
								if ( ! empty( $category_images[ $image_slug ] ) ) {
									echo wp_get_attachment_image( $category_images[ $image_slug ] );
								}
							?>
						</div>
						<button 
							type="button" 
							id="<?php echo self::$prefix . 'insert_media_button'; ?>" 
							class="button add_media <?php echo ( ! empty( $category_images[ $image_slug ] ) ) ? 'dfr_hidden' : null; ?>"
						>
							<span class="wp-media-buttons-icon"></span> 
							Select Category Image
						</button>
						<button 
							type="button" 
							id="<?php echo self::$prefix . 'delete_media_button'; ?>" 
							class="button add_media <?php echo ( empty( $category_images[ $image_slug ] ) ) ? 'dfr_hidden' : null; ?>"
						>
							Remove Category Image
						</button>
						<input 
							type="hidden" 
							class="dfr_hidden" 
							name="term_meta[<?php echo $image_slug; ?>]" 
							id="term_meta[<?php echo $image_slug; ?>]"
							size="25"
							style="width:60%;"
							value="<?php echo ( ! empty( $category_images[ $image_slug ] ) ) ? $category_images[ $image_slug ] : '' ?>"
						>
						<br />
						<span class="description dfr_hidden"><?php _e('The selected category image.'); ?></span>
					</td>
				</tr>
			<?php
		}

		/**
		 * Saves the term meta.
		 */
		public static function save_category_fields(): void {
			if ( ! isset( $_POST[ 'term_meta' ] ) ) {
				return;
			}

			$term_meta = Admin::get_option( 'category_images', [] );
			$cat_keys  = array_keys( $_POST[ 'term_meta' ] );

			foreach ( $cat_keys as $key ) {
				if ( isset( $_POST[ 'term_meta' ][ $key ] ) ) {
					$term_meta[ $key ] = $_POST[ 'term_meta' ][ $key ];
				}
			}

			Admin::update_option( 'category_images', $term_meta );
		}

		/**
		 * Enqueues the script that creates the wp media frame for category images.
		 *
		 * @param $hook_suffix The admin page suffix.
		 */
		public static function enqueue_category_script( $hook_suffix ): void {
			if ( $hook_suffix !== 'term.php' ) {
				return;
			}
			
			wp_enqueue_media();
			wp_register_script(
				self::$prefix . 'category_images',
				DFR_PLUGIN_URL . 'admin/js/categoryImages.js',
				array( 'media-upload', 'jquery' ),
				false,
				true
			);
			wp_enqueue_script( self::$prefix . 'category_images' );
		}

		/**
		 * Enqueue everything for the category extension block.
		 */
		public function enqueue_block_editor_assets() {

			// $asset_file  = include untrailingslashit( DFR_DIR ) . '/build/index.asset.php';

			// wp_enqueue_script(
			// 	'enable-column-direction-editor-scripts',
			// 	DFR_PLUGIN_URL . '/build/index.js',
			// 	$asset_file['dependencies'],
			// 	$asset_file['version']
			// );

			// wp_enqueue_style(
			// 	'enable-column-direction-editor-styles',
			// 	$plugin_url . '/src/editor.css'
			// );
		}
	}
}
