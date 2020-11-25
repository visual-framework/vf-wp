# Blocks

Blocks are small, reusable content patterns based on the [Visual Framework](https://stable.visual-framework.dev/). Blocks may appear within the Gutenberg editor, sidebar widgets, or rendered elsewhere in a template. Blocks added in the Gutenberg editor can be configured individually. See the individual README files for a detailed spec.

There are three types of blocks:

1. [Plugin Blocks](#plugin-blocks)
2. [ACF Blocks](#acf-blocks)
3. [React Blocks](#react-blocks)

Additional topics on this page:

* [Blocks in the Gutenberg Editor](#blocks-in-the-gutenberg-editor)
* [Blocks within Page Templates](#blocks-within-page-templates)

## Plugin Blocks

The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_block` and a set of PHP classes. Individual blocks inherit the [`VF_Plugin`](/wp-content/plugins/vf-wp/README.md#vf_plugin) class. Blocks registered this way have default metadata assigned to their respective posts. Block defaults can be configured under **WP Admin > Content Hub > Blocks** on a per-site basis.

<img src="/.github/docs/plugin-block.png" alt="Plugin block example" width="801">

Plugin blocks are best suited for retrieving and caching HTML from the **Content Hub**. They allow for customizable API settings with per-site defaults. They can be enabled or disabled individually via their own WordPress plugin.

Available plugin blocks:

* [Data Resources](/wp-content/plugins/vf-data-resources-block/README.md)
* [EMBL News](/wp-content/plugins/vf-embl-news-block/README.md)
* [Example](/wp-content/plugins/vf-example-block/README.md) <sup>†1</sup>
* [Factoid](/wp-content/plugins/vf-factoid-block/README.md)
* [Group Header](/wp-content/plugins/vf-group-header-block/README.md)
* [Jobs](/wp-content/plugins/vf-jobs-block/README.md)
* Latest Posts <sup>†2</sup>
* [Members](/wp-content/plugins/vf-members-block/README.md)
* [Publications](/wp-content/plugins/vf-publications-block/README.md)
* [Publications Group EBI](/wp-content/plugins/vf-publications-group-ebi-block/README.md)

<sup>†1 The Example plugin block is used for development testing and should not be activated on live websites.</sup>
<sup>†2 The Latest Posts plugin block was deprecated and replaced with an ACF block better suited for local WordPress content.</sup>

## ACF Blocks

ACF blocks are registered for use in the Gutenberg editor using the [Advanced Custom Fields plugin](https://www.advancedcustomfields.com/resources/blocks/). Unlike plugin blocks, their default configuration cannot be defined on a per-site basis. They are defined using the `VFWP_Block` class.

<img src="/.github/docs/acf-block.png" alt="ACF block example" width="800">

ACF blocks are best suited for single instances of local content and easy development. They are located in the parent or child themes.

Available ACF blocks:

| Block |   |   |   |
|-------|---|---|---|
| Activity List | Badge | Banner | [Box](/wp-content/themes/vf-wp/blocks/vfwp-box/README.md) |
| Button | [Card](/wp-content/themes/vf-wp/blocks/vfwp-card/README.md) | Details | Divider |
| Embed | Figure | Hero | Intro |
| [Latest Posts](/wp-content/themes/vf-wp/blocks/vfwp-latest-posts/README.md) | Lede| [Links List](/wp-content/themes/vf-wp/blocks/vfwp-links-list/README.md) | Masthead |
| Page Header | Profile| Search | Section Header |
| Social Icons | [Summary](/wp-content/themes/vf-wp/blocks/vfwp-summary/README.md) | Tabs <sup>†3</sup> |   |

ACF blocks from plugins:

* [Events List](/wp-content/plugins/vf-events/README.md#gutenberg-block)

<sup>†3 The Tabs ACF block was deprecated and replaced with a React block listed below.</sup>

## React Blocks

React blocks are registered for use in the [Gutenberg editor](https://developer.wordpress.org/block-editor/developers/) using JavaScript & React. They are provided by the [VF Gutenberg plugin](/wp-content/plugins/vf-gutenberg/README.md).

<img src="/.github/docs/react-block.png" alt="React block example" width="888">

React blocks are best suited for advanced editor requirements that cannot be achieved using ACF blocks alone; block transforms, and managing multiple `<InnerBlocks />`, for example.

Available React blocks:

* VF Cluster
* VF Embed
* VF EMBL Grid
* VF Grid Column <sup>†4</sup>
* VF Grid
* VF Tabs Section <sup>†4</sup>
* VF Tabs

### Legacy React Blocks

Before ACF blocks were possible, the ACF blocks listed above were implemented as React blocks. Those older versions have since been deprecated but remain in the code to avoid breaking existing usage. They are hidden from the block inserter. They should be removed entirely in future following an audit. They used Nunjucks templates from the Visual Framework making them difficult to update.

<sup>†4 Restricted to their respective parent innner blocks.</sup>

* * *

## Blocks in the Gutenberg Editor

ACF and plugin blocks in the Gutenberg editor use the `acf/vfwp-` and `acf/vf-` prefixes:

```html
<!-- wp:acf/vf-members {"id":"block_5eb936165175f","name":"acf/vf-members"} /-->
```

The block `id` attribute must be unique per instance in the post content. When inserting blocks programmatically, generate a random ID using [`uniqid('block_')`](https://www.php.net/manual/en/function.uniqid.php) in PHP, or `acf.uniqid('block_')` in JavaScript.

Plugin blocks can be configured individually by adding the `data` and `mode` attributes with `field_defaults` set to `0`.

For example:

```html
<!-- wp:acf/vf-members {"id":"block_5eb937ea938af","name":"acf/vf-members","data":{"field_defaults":"0","field_vf_members_limit":"2","field_vf_members_order":"DESC","field_5ea988878eacf":"default","field_5ea983003e756":"0","field_vf_members_variation":"s"},"mode":"preview"} /-->
```

The JSON data format is used by ACF and plugin blocks to allow sever-side PHP templates. Those templates can be re-used outside of the Gutenberg editor. React blocks managed their own HTML and are only usable in the editor.

React blocks can be identified with the `vf/` prefix:

```html
<!-- wp:vf/members {"ver":"1.0.0"} /-->
```

These blocks are best configured using the Gutenberg front-end editor.

* * *

## Blocks within Page Templates

Plugin blocks can be hard-coded into theme templates:

```php
$vf_members = VF_Plugin::get_plugin('vf_members');
if ($vf_members) {
  VF_Plugin::render($vf_members);
}
```

The block defaults from the related post metadata are be used.

It is possible to use ACF blocks in templates:

```php
acf_render_block(array(
  'id'   => uniqid('block_'),
  'name' => 'acf/vfwp-box',
  'data' => array(
    /* ... */
  )
));
```

Although this method is not officially documented.

It is also possible to do:

```php
get_template_part('blocks/vfwp-box/template');
```

However, the corresponding template would require additional logic to determine whether it is an ACF or template include.

For example:

```php
$is_block = isset($block['id']);
```

It may be simpler to use two different templates.
