# Templates

Templates are configurable [container](/docs/containers.md) stacks that can be used to define preset dynamic theme templates. The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_template`. They can be configured under **WP Admin > Templates** on a per-site basis.

The post content for templates is restricted to container blocks. For example, the default structure for a new template is:

```html
<!-- wp:acf/vf-container-global-header /-->

<!-- wp:acf/vf-container-page-template /-->

<!-- wp:acf/vf-container-global-footer /-->
```

Themes must have at least one template with a `post_name` of `default`.

The `acf/vf-container-page-template` block is a unique placeholder that is replaced by the actual [WordPress template](https://developer.wordpress.org/themes/basics/template-hierarchy/). The theme exposes two actions: `vf_header` and `vf_footer`. They are triggered by their respective template partials (i.e. `partials/header.php`). All containers set above the placeholder are outputted in the header. All containers below are outputted in the footer.

## Selecting the Page Template

The `default` template is used unless another is selected. Dynamic templates will appear above theme templates in the "Page Attributes" panel of the Gutenberg editor.

<img src="/.github/docs/template-panel.png" alt="Page attribute template panel" width="278">

Templates with the "(theme)" suffix mark those with a PHP template using the standard WordPress `/* Template Name: Full-width */` comment. Those are based on the `default` dynamic template unless they specifically opt-out of the `vf_header` and `vf_footer` actions.

## Assigning the Page Template

Dynamic page templates can be assigned programmatically the same way that  standard theme templates are set. Update the `_wp_page_template` post metadata using the format:

```
vf_template_[post_name].php
```

Replacing `[post_name]` with the `vf_template` name.

The page will then follow the standard [WordPress template hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/) whilst including the dynamic header and footer containers.
