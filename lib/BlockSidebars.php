<?php
/**
 * BlockSidebars
 *
 * @package Rareview
 * @subpackage BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

namespace Rareview\BlockEditor;

/**
 * Block Sidebars Class
 */
class BlockSidebars extends Config {

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! self::is_registered( __CLASS__ ) ) {
			self::register( __CLASS__ );

			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_sidebars' ] );
		}
	}

	/**
	 * Enqueues the registered block sidebars asset.
	 *
	 * @return void
	 */
	public function enqueue_block_sidebars() {
		wp_enqueue_script( self::BLOCK_EDITOR_NAMESPACE . '-block-editor-sidebars' );
	}
}
