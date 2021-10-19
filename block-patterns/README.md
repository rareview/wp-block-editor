# Block Patterns

Block patterns are a useful way to combine commonly used sets of elements into a registered pattern, for quickly an easily inserting into content.

See here for more information: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/

## Adding a Pattern

A block pattern consists of two files: `pattern.php` and `content.txt`. By design, each pattern will be a unique name, because each will reside in a directly located within this patterns directory.

### pattern.php

This file MUST return an array with the required `title` and any other pattern properties listed in the [WordPress documentation](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/#register_block_pattern).

```php
<?php
/**
 * Example Pattern
 *
 * @package Rareview
 * @subpackage BlockEditor
 *
 * @author Rareview <hello@rareview.com>
 */

return [
	'title'       => 'Example Pattern',
	'description' => 'Just an example... nothing more.',
	'categories'  => [ 'rareview' ],
	'keywords'    => [ 'example', 'pattern', 'keyword' ],
];
```

### content.txt

This file MUST consist of the HTML of a set of blocks, copied directly from the editor. As the pattern content must follow block markup, it is best to copy/paste the block pattern HTML directly from the Block editor. In addition, it is often best to add a class to the pattern (for styles) and combine blocks within a Group block to have the class applied to a wrapper.

Here is an example:

```html
<!-- wp:group {"className":"rareview\u002d\u002dexample-pattern"} -->
<div class="wp-block-group rareview--example-pattern"><div class="wp-block-group__inner-container"><!-- wp:heading -->
<h2 id="h-example-pattern">Example Pattern</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Some example pattern text... to be continued.</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:group -->
```
