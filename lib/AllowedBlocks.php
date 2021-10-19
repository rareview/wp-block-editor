<?php
/**
 * AllowedBlocks
 *
 * @package Rareview
 * @subpackage BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

namespace Rareview\BlockEditor;

/**
 * Allowed Blocks Class
 */
class AllowedBlocks extends Config {
	/**
	 * Use allowed blocks to avoid usaged of untested blocks.
	 *
	 * DEV-NOTE: Change this to an array to restrict which blocks become available.
	 *
	 * @link https://github.com/WordPress/gutenberg/tree/trunk/packages/block-library/src.
	 *
	 * @var array $allowed_blocks
	 */
	public $allowed_blocks = true;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! self::is_registered( __CLASS__ ) ) {
			self::register( __CLASS__ );

			add_filter( 'allowed_block_types_all', [ $this, 'register_allowed_block_types' ] );
		}
	}

	/**
	 * Handles registration of the allowed block types.
	 *
	 * @param bool|array $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
	 *
	 * @return bool|array
	 */
	public function register_allowed_block_types( $allowed_block_types ) {
		return apply_filters( 'rareview_allowed_blocks', $this->allowed_blocks );
	}
}
