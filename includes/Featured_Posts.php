<?php

namespace DFR;

if ( class_exists( 'DFR\Init' ) ) {
	/**
	 * Enqueue is a class that handles enqueuing assets related to query loop extension.
	 *
	 * @package Delicious_Family_Recipes
	 */
	class Featured_Posts extends Init {

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
			add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_assets' ] );
			add_filter( 'render_block_core/query', [ $this, 'filter_render_of_query_extension' ], 10, 2 );
		}

		/**
		 * Enqueues the script for the block editor.
		 */
		public static function enqueue_block_editor_assets(): void {

			$asset_file  = include untrailingslashit( DFR_DIR ) . '/build/extensions.asset.php';

			wp_enqueue_script(
				'extensions-scripts',
				DFR_PLUGIN_URL . 'build/extensions.js',
				$asset_file['dependencies'],
				$asset_file['version']
			);

			wp_enqueue_style(
				'extensions-styles',
				DFR_PLUGIN_URL . 'build/style-extensions.css'
			);
		}

		/**
		 * Filters the render of dfr/featured-posts extensions.
		 * Ensures that the post-term matches the taxonomy query if it exists.
		 *
		 * @param string $block_content The block content.
		 * @param array  $block Full block content, including name and attributes.
		 */
		public function filter_render_of_query_extension( string $block_content, array $block ): string {
			if (
				empty( $block['attrs'] )
				|| empty( $block['attrs']['namespace'] )
				|| $block['attrs']['namespace'] !== 'dfr/featured-posts'
			) {
				return $block_content;
			}

			if (
				empty( $block['attrs']['query'] )
				|| empty( $block['attrs']['query']['taxQuery'] )
				|| empty( $block['attrs']['query']['taxQuery']['category'] )
			) {
				return $block_content;
			}

			$cat_id   = $block['attrs']['query']['taxQuery']['category'][0];
			$cat_link = get_category_link( $cat_id );

			if ( empty( $cat_link ) ) {
				return $block_content;
			}

			$content = new \WP_HTML_Tag_Processor( $block_content );

			while( $content->next_tag('a') ) {
				if ( 'tag' === $content->get_attribute('rel') ) {
					$link = $content->get_attribute('href');

					if ( $link === $cat_link ) {
						$content->set_attribute( 'data-chosen', 1 );
					}
				}
			}

			return $content->get_updated_html();
		}
	}
}
