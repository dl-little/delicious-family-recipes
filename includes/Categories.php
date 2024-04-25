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
		 * Is term meta registered.
		 *
		 * @var ?bool
		 */
		public $is_term_meta_registered = false;

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
			add_filter( 'render_block_dfr/category', [ $this, 'filter_render_of_category_block' ], 10, 2 );
			add_action( 'after_setup_theme', [ $this, 'register_category_block_image_size' ] );
		}

		/**
		 * Register the category block
		 */
		public function category_block_init() {
			$this->is_block_registered = ! empty( register_block_type( untrailingslashit( DFR_DIR ) . '/build/category-block' ) ) ? true : false;
			$this->is_term_meta_registered = register_term_meta(
				'category',
				self::$term_meta_key,
				[
					'show_in_rest' => true
				],
			);
		}

		/**
		 * Adds an image size for a 420x420px category image.
		 */
		public function register_category_block_image_size() {
			add_image_size( 'featured-category-thumb', 420, 420, true );
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
			if ( ! isset( $_POST[ 'term_meta' ] ) || ! $this->is_term_meta_registered ) {
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
				DFR_PLUGIN_URL . 'build/category-block/index.js',
				$asset_file['dependencies'],
				$asset_file['version']
			);

			wp_enqueue_style(
				'category-block-styles',
				DFR_PLUGIN_URL . 'build/category-block/index.js'
			);
		}

		/**
		 * Checks the 5 most recent posts in a category and returns the first featured image it finds.
		 *
		 * @param int $id
		 *
		 * @return string The attachment image if found, or an empty string otherwise.
		 */
		public function get_featured_image_from_latest_posts( $id ): string {
			$args = [
				'cat' => $id,
			];
			
			$latest_posts = get_posts($args);

			foreach( $latest_posts as $post ) {
				$id = $post->ID;

				if ( ! has_post_thumbnail( $id ) ) {
					continue;
				}

				return wp_get_attachment_image(
					get_post_thumbnail_id( $id ),
					'featured-category-thumb'
				);

			}

			return '';
		}

		/**
		 * Filters the render of dfr/category block.
		 *
		 * @param string $block_content The block content.
		 * @param array  $block Full block content, including name and attributes.
		 */
		public function filter_render_of_category_block( string $block_content, array $block ) {
			if ( ! $this->is_block_registered ) {
				// Return null if not registered.
				return;
			}

			if (
				empty( $block )
				|| empty( $block['attrs'] )
				|| ( empty( $block['attrs']['chosenCategories'] ) && empty( $block['attrs']['popularCategories'] ) )
			) {
				return $block_content;
			}

			$categories = ! empty( $block['attrs']['selectCategories'] ) ? $block['attrs']['chosenCategories'] : $block['attrs']['popularCategories'];

			// Instantiate the tag processor.
			$content = new \WP_HTML_Tag_Processor( $block_content );

			foreach( $categories as $category ) {
				$id = $category['value'];
				$link = get_category_link( $id );
				$attachment_id = get_term_meta( $id, self::$term_meta_key, true );
				$attachment = wp_get_attachment_image(
					$attachment_id,
					'featured-category-thumb'
				);

				if ( empty( $attachment ) ) {
					$attachment = $this->get_featured_image_from_latest_posts( $id );
				}

				$content->next_tag('li');

				if (
					(string)$id !== $content->get_attribute('data-id')
					|| empty( $attachment )
				) {
					continue;
				}

				$content->next_tag('a');
				$content->set_attribute('href', $link);

				$image = new \WP_HTML_Tag_Processor( $attachment );
				$image->next_tag();
				$attrs = [
					'width'  => $image->get_attribute('width'),
					'height' => $image->get_attribute('height'),
					'src'    => $image->get_attribute('src'),
					'class'  => $image->get_attribute('class') . ' cat-img',
					'alt'    => $image->get_attribute('alt'),
					'srcset' => $image->get_attribute('srcset'),
					'sizes'  => $image->get_attribute('sizes'),
				];

				$content->next_tag('img');

				foreach( $attrs as $k=>$v ) {
					$content->set_attribute( $k, $v);
				}
			}

			return $content->get_updated_html();
		}
	}
}
