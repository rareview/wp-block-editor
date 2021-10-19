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
 * @package Rareview
 * @subpackage BlockEditor
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

	const BLOCK_EDITOR_NAMESPACE  = 'rareview';
	const BLOCK_EDITOR_MANIFEST   = 'dist/manifest.json';
	const BLOCK_LIBRARY_LOCATION  = 'block-library';
	const BLOCK_PATTERNS_LOCATION = 'block-patterns';

	/**
	 * Class Registered
	 *
	 * @var boolean $registered
	 */
	protected static $registered = [];

	/**
	 * Lookup a location relative to the plugin directory.
	 *
	 * @param string  $loc The location, directory or file, to lookup.
	 * @param boolean $url True if requesting the URL, otherwise it is a path.
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

	/**
	 * Checks if a class has already been registered.
	 *
	 * @param object $class Passed __CLASS__ to be registered.
	 *
	 * @return boolean
	 */
	protected function is_registered( $class ) {
		return in_array( $class, self::$registered, true );
	}

	/**
	 * Handles one-time class registration.
	 *
	 * @param object $class Passed __CLASS__ to be registered.
	 */
	protected function register( $class ) {
		if ( ! self::is_registered( $class ) ) {
			array_push( self::$registered, $class );
		}
	}
}

// Fire things off, if we have an autoload file.
if ( file_exists( AUTOLOADER ) ) {
	require_once AUTOLOADER;

	add_action(
		'plugins_loaded',
		function() {
			new AllowedBlocks();
			new BlockLibrary();
		}
	);
}
