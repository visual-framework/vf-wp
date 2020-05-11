# VF-WP Blocks

Blocks are small, reusable content patterns. Blocks may appear within the Gutenberg editor, sidebar widgets, or rendered elsewhere in a template.

* [Data Resources](/wp-content/plugins/vf-data-resources-block/README.md)
* [Factoid](/wp-content/plugins/vf-factoid-block/README.md)
* [Group Header](/wp-content/plugins/vf-group-header-block/README.md)
* [Jobs](/wp-content/plugins/vf-jobs-block/README.md)
* [Members](/wp-content/plugins/vf-members-block/README.md)
* [Publications](/wp-content/plugins/vf-publications-block/README.md)
* [Publications Group EBI](/wp-content/plugins/vf-publications-group-ebi-block/README.md)

Some blocks registered via a plugin have a related "post" with assigned defaults. Default block settings are configured under **Content Hub > Blocks** in the Admin area. Block defaults have the custom post type: `vf_block`.

Blocks added in the Gutenberg editor can be individually configured. See the individual plugin README files for a detailed spec.

## Advanced Custom Fields

Other blocks are registered with ACF Gutenberg only:

* [EMBL News](/wp-content/plugins/vf-embl-news-block/README.md)
* [Latest Posts](/wp-content/themes/vf-wp/blocks/vfwp-latest-posts/README.md)
* [Events List](/wp-content/plugins/vf-events/README.md#gutenberg-block)

They have no defaults block post.

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

## Page Templates

Blocks registered via a plugin can be hard-coded into theme templates:

```php
$vf_members = VF_Plugin::get_plugin('vf_members');
if ($vf_members) {
  VF_Plugin::render($vf_members);
}
```

The block defaults assigned to the related post will be used.
