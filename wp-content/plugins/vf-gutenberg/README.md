# Visual Framework Gutenberg

This plugin integrates the Visual Framework with the WordPress Gutenberg editor to:

* Adapt core Gutenberg blocks to render with VF markup
* Add new Gutenberg blocks under the "Visual Framework" category
* Register an admin options page entitled "VF Settings"
* Provide global options and template functions for VF assets

These features require the **Advanced Custom Fields** (ACF) plugin to be active.

## Core Blocks

Some core Gutenberg blocks are adapted to render with VF markup on the front-end. Within the Gutenberg editor they will appear as default.

See the `includes/core-*.php` files for a all compatible blocks.

Additional blocks can be adapted by creating a new PHP class with one static `render` method:

```php
class VF_Gutenberg_Core_Block {
  static function render($html, $block) {
    return $html;
  }
}

vf_gutenberg()->add_compatible(
  'core/block',
  array('VF_Gutenberg_Core_Block', 'render')
);
```

The `core/block` reference should be replaced with the actual block name.

New class files need to be included within the `initialize` method in `vf-gutenberg.php`.

The `render` method is called before a block is outputted in a post template. It should return the HTML using string and regular expression replacements wherever necessary.

## VF Blocks

Visual Framework blocks are added under their own category. They are rendered using an `<iframe>` within the Gutenberg editor. This allows the VF stylesheet to be included (providing the option is set; see below).

See the `includes/vf-block.php` file for the PHP interface new classes should implement.

The `key()` method returns a unique reference (e.g. 'vf-gutenberg-box').

The `title()` method returns a display name (e.g. "VF Box").

The `fields()` method returns an array of ACF fields to register for this block.

The `render()` method returns the final HTML to output in the template. The ACF `get_field()` function is used to retrieve configuration.

After a new class file is created make sure it ends with:

```php
vf_gutenberg()->_deprecated_add_block(
  new VF_Gutenberg_Box()
);
```

And the file is included within the `initialize` method in `vf-gutenberg.php`.

There are several examples to follow in the includes directory of this plugin.

## Global Options

The global options `vf_cdn_stylesheet` and `vf_cdn_javascript` are added to the `vf-settings` options page. Template functions exist for convenience. They will return `null` if the option is empty.

```php
$stylesheet = vf_get_stylesheet();
$javascript = vf_get_javascript();
```

## Gutenberg Block CSS

If you do need to provide additional CSS to the Gutenberg editor (outside of the `<iframe>`) use the `admin_head` action.

```php
add_action('admin_head', array($this, 'admin_head'), 15);
```

This is particularly useful if a pattern can be wider than the default WP block max-width:

```php
function admin_head() {
?>
<style>
.wp-block[data-type="acf/vf-block"] {
  max-width: 800px;
}
</style>
<?php
}
```

The attribute selector with `data-type` (using the block key) is a useful prefix to scope your CSS.
