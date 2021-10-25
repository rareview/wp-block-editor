[![We're Hiring Banner](https://rareview.com/wp-content/uploads/2021/07/repo-banner.jpg)](https://rareview.com/careers/)

# WordPress Block Editor Scaffold

This project is a template repo for developing WordPress Blocks, Block Patterns, Block Styles and Block Editor Sidebars in an automated and organized fashion.

## Project Structure

As a WordPress Plugin, this project follows all requrements necessary for building a plugin; in addition, it uses different package management tools to enable developers to build faster and smarter, using modern web development features.

### Composer for managing PHP Dependencies

Aside from the [required plugin file](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/), all PHP code is located within the `lib` directory, which are autoloaded as classes and hooked on `plugins_loaded`.

Each newly added class MUST extend the Config class located in the main plugin file, which provides both useful utility methods and ensuring Class registration only occurs once.

### Node, NPM and Webpack for JavaScript Dependencies

Must like the WordPress Block Editor project (aka Gutenberg), this plugin uses Node, NPM and Webpack for managing JavaScript dependencies. In addition, automated build processes are included to ensure only those required dependencies are built, and all WordPress scripts are added as dependencies when enqueued.

## Installation

This plugin can be installed as a standard WordPress plugin, but it is highly recommended to use a a Must Use WordPress Plugin to ensure Block Editor functionalty does not change by an accidental plugin deactivation.

### Install as Must Use plugin

Installing as a Must Use plugin requires an additional step to load he required plugin file. First, add this plugin to the `mu-plugins` directory and add a file at the top-level of the `mu-plugins directory named `loader.php`. Within `loader.php`, simply require the plugin file like so:

```php
require_once __DIR__ . '/wp-block-editor/block-editor.php`;
```

Please note that this is just and example and there are many ways to require plugins within the Must Use directory. If you already have a file that loads plugins in this directory, then simply requiring within that file will suffice.

## More Information

Within other directories of this plugin there are README.md files describing in more detail the requirements for adding Blocks (`block-library`), Block Patterns (`block-patterns`), Block Styles (`block-styles`) and Block Editor Sidebars (`sidebars`). Please read each to better understand how these are added to the Block Editor.
