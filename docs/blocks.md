# VF-WP Blocks

Blocks are small, reusable content patterns based on the Visual Framework. Blocks may appear within the Gutenberg editor, sidebar widgets, or rendered elsewhere in a template. Blocks added in the Gutenberg editor can be individually configured. See the individual README files for a detailed spec.

There are three types of blocks:

1. [Plugin Blocks](#plugin-blocks)
2. [Advanced Custom Fields Blocks](#advanced-custom-fields-blocks)
3. [Gutenberg Blocks](#gutenberg-blocks)

## Plugin Blocks

The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_block` and a set of PHP classes. Individual block plugins inherit the [`VF_Plugin`](/wp-content/plugins/vf-wp/README.md#vf_plugin) class.

Blocks registered this way have default metadata values assigned to their respective posts. Block defaults can be configured under **WP Admin > Content Hub > Blocks** on a per-site basis. This type of block is best suited for retrieving and caching HTML from the Content Hub. They allow for default and customizable API settings.

Available plugin blocks:

* [Data Resources](/wp-content/plugins/vf-data-resources-block/README.md)
* [Factoid](/wp-content/plugins/vf-factoid-block/README.md)
* [Group Header](/wp-content/plugins/vf-group-header-block/README.md)
* [Jobs](/wp-content/plugins/vf-jobs-block/README.md)
* [Members](/wp-content/plugins/vf-members-block/README.md)
* [Publications](/wp-content/plugins/vf-publications-block/README.md)
* [Publications Group EBI](/wp-content/plugins/vf-publications-group-ebi-block/README.md)

## Advanced Custom Fields

ACF blocks are registered for use in the Gutenberg editor. Unlike plugin blocks, default configuration cannot be defined on a per-site basis. They are best suited for single instances of local content.

Available ACF blocks:

* [Events List](/wp-content/plugins/vf-events/README.md#gutenberg-block)
* [EMBL News](/wp-content/plugins/vf-embl-news-block/README.md)
* [Latest Posts](/wp-content/themes/vf-wp/blocks/vfwp-latest-posts/README.md)
* [Summary](/wp-content/themes/vf-wp/blocks/vfwp-summary/README.md)
* [Card](/wp-content/themes/vf-wp/blocks/vfwp-card/README.md)
* [Links List](/wp-content/themes/vf-wp/blocks/vfwp-links-list/README.md)
* [Box](/wp-content/themes/vf-wp/blocks/vfwp-box/README.md)

## Gutenberg Blocks

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
