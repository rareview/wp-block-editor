<?php
/**
 * BlockLibrary
 *
 * @package Rareview
 * @subpackage BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

namespace Rareview\BlockEditor;

/**
 * Block Library Class
 */
class BlockLibrary extends Config {

	/**
	 * Constructor
	 *
	 * @throws \Exception When Block Library manifest file is not found.
	 */
	public function __construct() {
		if ( ! self::is_registered( __CLASS__ ) ) {
			self::register( __CLASS__ );

			// Throw an error if the block asset manifest file is not found.
			if ( ! file_exists( self::dir( self::BLOCK_EDITOR_MANIFEST ) ) ) {
				throw new \Exception( __( 'Block Library manifest file not found.', 'rareview' ) );
			}

			add_filter( 'block_categories_all', [ $this, 'register_block_category' ] );

			add_action( 'init', [ $this, 'register_block_editor_assets' ] );
			add_action( 'init', [ $this, 'register_blocks_from_metadata' ] );
		}
	}

	/**
	 * Handles registration of the block category.
	 *
	 * @param array $categories Registered block categories.
	 *
	 * @return array
	 */
	public function register_block_category( $categories ) {
		return array_merge(
			[
				[
					'slug'  => self::BLOCK_EDITOR_NAMESPACE,
					'title' => __( 'Rareview', 'rareview' ),
				],
			],
			$categories,
		);
	}

	/**
	 * Handles Registration of Block Editor Assets
	 *
	 * Assets are registered using standard WordPress functions, but are done so
	 * using the block editor asset manifest.json file.
	 *
	 * @return void
	 */
	public function register_block_editor_assets() {
		$assets = json_decode( file_get_contents( self::dir( self::BLOCK_EDITOR_MANIFEST ) ), true ) ?? []; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		foreach ( $assets as $name => $file ) {
			$ext       = pathinfo( $file, PATHINFO_EXTENSION );
			$name      = str_replace( '.' . $ext, '', $name );
			$deps_file = str_replace( '.' . $ext, '.asset.php', $file );

			$asset = file_exists( self::dir( $deps_file ) ) ? require_once self::dir( $deps_file ) : [ 'dependencies' => [], 'version' => null ]; // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound

			// Skip maps, as they do not need to be registered.
			if ( 'map' === $ext ) {
				continue;
			}

			if ( 'css' === $ext ) {
				\wp_register_style(
					self::BLOCK_EDITOR_NAMESPACE . '-' . $name,
					self::url( $file, false ),
					$asset['dependencies'] ?? [],
					$asset['version'] ?? null,
				);
			} elseif ( 'js' === $ext ) {
				\wp_register_script(
					self::BLOCK_EDITOR_NAMESPACE . '-' . $name,
					self::url( $file, false ),
					$asset['dependencies'] ?? [],
					$asset['version'] ?? null,
					true
				);
			}
		}
	}

	/**
	 * Handles Registration of Blocks from Metadata
	 *
	 * Blocks are registered from each's metadata file (block.json). Without
	 * this file, an error is thrown.
	 *
	 * @throws \Exception When Block data file is not found.
	 *
	 * @return void
	 */
	public function register_blocks_from_metadata() {
		$blocks = array_diff( scandir( self::dir( self::BLOCK_LIBRARY_LOCATION ) ), [ '..', '.', 'README.md' ] );

		foreach ( $blocks as $block ) {
			$metadata_file = self::dir( \trailingslashit( self::BLOCK_LIBRARY_LOCATION ) . $block . '/block.json' );
			$render_file   = self::dir( \trailingslashit( self::BLOCK_LIBRARY_LOCATION ) . $block . '/render.php' );
			$metadata      = false;

			// Block JSON data file (block.json) is required for every block.
			if ( ! file_exists( $metadata_file ) ) {
				throw new \Exception( $block . ' data not found here: ' . $metadata_file );
			} else {
				$metadata = json_decode( file_get_contents( $metadata_file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			}

			$meta_args = [];

			if ( file_exists( $render_file ) ) {
				require_once $render_file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

				$function_name = preg_replace( '/\/|-/', '_', $metadata->name ) . '_render';

				if ( function_exists( $function_name ) ) {
					$meta_args['render_callback'] = $function_name;
				}
			}

			$block = \register_block_type_from_metadata( $metadata_file, $meta_args );

			// Block was registerd, so allow it.
			if ( ! empty( $block ) ) {
				\add_filter(
					'rareview_allowed_blocks',
					function( $allowed ) use ( $block ) {
						if ( is_array( $allowed ) ) {
							array_push( $allowed, $block->name );
						}

						return $allowed;
					}
				);
			}
		}
	}
}
