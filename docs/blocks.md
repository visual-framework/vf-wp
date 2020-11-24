# VF-WP Blocks

Blocks are small, reusable content patterns based on the [Visual Framework](https://stable.visual-framework.dev/). Blocks may appear within the Gutenberg editor, sidebar widgets, or rendered elsewhere in a template. Blocks added in the Gutenberg editor can be configured individually. See the individual README files for a detailed spec.

There are three types of blocks:

1. [Plugin Blocks](#plugin-blocks)
2. [ACF Blocks](#acf-blocks)
3. [React Blocks](#react-blocks)

## Plugin Blocks

The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_block` and a set of PHP classes. Individual blocks inherit the [`VF_Plugin`](/wp-content/plugins/vf-wp/README.md#vf_plugin) class. Blocks registered this way have default metadata values assigned to their respective posts. Block defaults can be configured under **WP Admin > Content Hub > Blocks** on a per-site basis.

<img src="/.github/docs/plugin-block.png" alt="Plugin block example" width="801">

Plugin blocks are best suited for retrieving and caching HTML from the Content Hub. They allow for customizable API settings with per-site defaults. They can be enabled or disabled individually via their own WordPress plugin.

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

* Activity List
* Badge
* Banner
* [Box](/wp-content/themes/vf-wp/blocks/vfwp-box/README.md)
* Button
* [Card](/wp-content/themes/vf-wp/blocks/vfwp-card/README.md)
* Details
* Divider
* Embed
* Figure
* Hero
* Intro
* [Latest Posts](/wp-content/themes/vf-wp/blocks/vfwp-latest-posts/README.md)
* Lede
* [Links List](/wp-content/themes/vf-wp/blocks/vfwp-links-list/README.md)
* Masthead
* Page Header
* Profile
* Search
* Section Header
* Social Icons
* [Summary](/wp-content/themes/vf-wp/blocks/vfwp-summary/README.md)
* Tabs <sup>†3</sup>

<sup>†3 The Tabs ACF block was deprecated and replaced with a React block listed below.</sup>

ACF blocks from plugins:

* [Events List](/wp-content/plugins/vf-events/README.md#gutenberg-block)

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

<sup>†4 Restricted to their respective parent innner blocks.</sup>

### Legacy React Blocks

Before ACF blocks were possible, the ACF blocks listed above were implemented as React blocks. Those older versions have since been deprecated but remain in the code to avoid breaking existing usage. They are hidden from the block inserter. They should be removed entirely in future following an audit. They used Nunjucks templates from the Visual Framework making them difficult to update.

## Blocks in the Gutenberg editor

Blocks in the Gutenberg editor take the form:

```html
<!-- wp:acf/vf-members {"id":"block_5eb936165175f","name":"acf/vf-members"} /-->
```

Block `id` must be unique per instance in the post content. Use a random ID, e.g. [`uniqid('block_')`](https://www.php.net/manual/en/function.uniqid.php).

If configured they require a `data` and `mode` property with `field_defaults` set to `0`. For example:

```html
<!-- wp:acf/vf-members {"id":"block_5eb937ea938af","name":"acf/vf-members","data":{"field_defaults":"0","field_vf_members_limit":"2","field_vf_members_order":"DESC","field_5ea988878eacf":"default","field_5ea983003e756":"0","field_vf_members_variation":"s"},"mode":"preview"} /-->
```

Old plugin versions used the format:

```html
<!-- wp:vf/members {"ver":"1.0.0"} /-->
```

This format is now deprecated. Existing block used in this way are rendered with the new ACF templates to support backwards compatibility.

## Page Templates

Blocks registered via a plugin can be hard-coded into theme templates:

```php
$vf_members = VF_Plugin::get_plugin('vf_members');
if ($vf_members) {
  VF_Plugin::render($vf_members);
}
```

The block defaults assigned to the related post will be used.
