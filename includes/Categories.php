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
		 * Is category block registered.
		 *
		 * @var ?bool
		 */
		public $is_block_registered = false;

		/**
		 * Meta key for image.
		 *
		 * @var ?string
		 */
		public static $term_meta_key = 'dfr_image';

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
			add_action( 'init', [ $this, 'category_block_init' ] );
		}

		/**
		 * Register the category block
		 */
		public function category_block_init() {
			$this->is_block_registered = ! empty( register_block_type( untrailingslashit( DFR_DIR ) . '/build/category-block' ) ) ? true : false;
			register_term_meta(
				'category',
				self::$term_meta_key,
				[
					'show_in_rest' => true
				],
			);
		}

		/**
		 * Adds the category image field to category edit screen.
		 */
		public static function add_category_image_field( $tag ): void {
			$id              = $tag->term_id;
			$term_meta       = get_term_meta( $id, self::$term_meta_key, true );

			?>
				<tr 
					class="form-field" 
					id="<?php echo self::$prefix . 'image_field' ?>" 
					data-slug="<?php echo $tag->slug; ?>"
				>
					<th scope="row" valign="top">
						<label for="term_meta[<?php echo self::$term_meta_key; ?>]"><?php _e('Category Image'); ?></label>
					</th>
					<td class="wp-media-buttons">
						<div 
							id="<?php echo self::$prefix . 'image_container'; ?>" 
							class="<?php echo self::$prefix , ( ! empty( $term_meta ) ) ? 'image_chosen' : 'image_not_chosen' ?>" 
						>
							<?php
								if ( ! empty( $term_meta ) ) {
									echo wp_get_attachment_image( $term_meta );
								}
							?>
						</div>
						<button 
							type="button" 
							id="<?php echo self::$prefix . 'insert_media_button'; ?>" 
							class="button add_media <?php echo ( ! empty( $term_meta ) ) ? 'dfr_hidden' : null; ?>"
						>
							<span class="wp-media-buttons-icon"></span> 
							Select Category Image
						</button>
						<button 
							type="button" 
							id="<?php echo self::$prefix . 'delete_media_button'; ?>" 
							class="button add_media <?php echo ( empty( $term_meta ) ) ? 'dfr_hidden' : null; ?>"
						>
							Remove Category Image
						</button>
						<input 
							type="hidden" 
							class="dfr_hidden" 
							name="term_meta[<?php echo self::$term_meta_key; ?>]" 
							id="term_meta[<?php echo self::$term_meta_key; ?>]"
							size="25"
							style="width:60%;"
							value="<?php echo ( ! empty( $term_meta ) ) ? $term_meta : '' ?>"
						>
						<br />
						<span class="description dfr_hidden"><?php _e('The selected category image.'); ?></span>
					</td>
				</tr>
			<?php
		}

		/**
		 * Saves the term meta.
		 *
		 * @param int $term_id Term ID.
		 */
		public function save_category_fields( $term_id ): void {
			if ( ! isset( $_POST[ 'term_meta' ] ) ) {
				return;
			}

			update_term_meta(
				$term_id,
				self::$term_meta_key,
				$_POST[ 'term_meta' ][ self::$term_meta_key ]
			);
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
		public function enqueue_block_editor_assets(): void {

			if ( $this->is_block_registered ) {
				return;
			}

			$asset_file  = include untrailingslashit( DFR_DIR ) . '/build/category-block/index.asset.php';

			wp_enqueue_script(
				'category-block-scripts',
				DFR_PLUGIN_URL . '/build/category-block/index.js',
				$asset_file['dependencies'],
				$asset_file['version']
			);

			wp_enqueue_style(
				'category-block-styles',
				DFR_PLUGIN_URL . '/build/category-block/index.js'
			);
		}
	}
}
