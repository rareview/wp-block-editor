<?php
/**
 * Example Block render callback function.
 *
 * @package Rareview
 * @subpackage BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

/**
 * Example Block Render
 */
function rareview_example_block_render() {
	return esc_html__( 'Hello example block render!', 'rareview' );
}
