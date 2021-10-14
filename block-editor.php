<?php
/**
 * Plugin Name: Block Editor
 * Description: Scaffold for building with the WordPress Block Editor.
 * Version: 0.1.0
 * Author: Rareview
 * Author URI: https://rareview.com
 * License: MIT
 * Text Domain: rv-block-editor
 *
 * @package BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

namespace Rareview\BlockEditor;

const AUTOLOADER = __DIR__ . '/vendor/autoload.php';

/**
 * Configuration Class
 *
 * Used to store common references, such as a file directory, for easy lookups.
 */
abstract class Config {

	/**
	 * Lookup a location relative to the plugin directory.
	 *
	 * @param string $loc The location, directory or file, to lookup.
	 * @param bool   $url True if requesting the URL, otherwise it is a path.
	 */
	public static function dir( $loc = '', $url = false ) {
		return ( true === $url ? plugin_dir_url( __FILE__ ) : plugin_dir_path( __FILE__ ) ) . $loc;
	}

	/**
	 * Alias for looking up a URL more easily.
	 *
	 * @param string $loc The location, directory or file, to lookup.
	 */
	public static function url( $loc = '' ) {
		return self::dir( $loc, true );
	}
}

// Fire things off, if we have an autoload file.
if ( file_exists( AUTOLOADER ) ) {
	require_once AUTOLOADER;

	add_action(
		'plugins_loaded',
		function() {}
	);
}