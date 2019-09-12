# Visual Framework Gutenberg

This plugin integrates the Visual Framework with WordPress by:

* Adding an admin options page "VF Settings"
* Providing global options and template functions for assets
* Registering new Gutenberg blocks under the "Visual Framework" categories
* Adapting core Gutenberg blocks to render with VF markup

These features require the **Advanced Custom Fields** (ACF) plugin to be active.


## VF Components in the Gutenberg editor

This plugin adds two block categories:


### Visual Framework (core)

VF core components include buttons, boxes, and grid layouts. These blocks exist within this plugin as React components in `blocks/vf-core` (for the back-end editor), and PHP templates in `includes/vf-core` for the (front-end render).

### Visual Framework (WordPress)

These blocks represent a Gutenberg version of a VF-WP plugin (such as "Latest News", "Members" etc). This plugin includes a base React component that automatically generates the editor UI from plugin's ACF configuration. Their `VF_Plugin` class handle the front-end render. The corresponding plugin must be activated for its block to appear.

* * *

Most of the VF blocks are rendered using an `<iframe>` within the Gutenberg editor. This allows the VF stylesheet to be included (providing the option is set; see below).

## Global Options

The global options `vf_cdn_stylesheet` and `vf_cdn_javascript` are added to the `vf-settings` options page. Template functions exist for convenience. They will return `null` if the option is empty.

```php
$stylesheet = vf_get_stylesheet();
$javascript = vf_get_javascript();
```

## WordPress Core Blocks

Some WordPress core Gutenberg blocks are adapted to render with VF markup on the front-end. Within the Gutenberg editor they will appear as default.

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


## Gutenberg Editor Styles

If you do need to provide additional CSS to the Gutenberg editor (outside of the `<iframe>`) use the `admin_head` action.

```php
add_action('admin_head', array($this, 'admin_head'), 15);
```

This is particularly useful if a pattern can be wider than the default WP block max-width:

```php
function admin_head() {
?>
<style>
.wp-block[data-type="vf/block"] {
  max-width: 800px;
}
</style>
<?php
}
```

The attribute selector with `data-type` (using the block key) is a useful prefix to scope your CSS.
