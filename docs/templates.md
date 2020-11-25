# Templates

Templates are configurable [container](/docs/containers.md) stacks that can be used to define preset dynamic theme templates. The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_template`. They can be configured under **WP Admin > Templates** on a per-site basis.

The post content for templates is restricted to container blocks. For example, the default structure for a new template is:

```html
<!-- wp:acf/vf-container-global-header /-->

<!-- wp:acf/vf-container-page-template /-->

<!-- wp:acf/vf-container-global-footer /-->
```

Themes must have at least one template with the `post_name` as `default`.

The `acf/vf-container-page-template` block is a unique placeholder that is replaced by the actual [WordPress template](https://developer.wordpress.org/themes/basics/template-hierarchy/).

The theme exposes two actions: `vf_header` and `vf_footer`. They are triggered by their respective template partials (i.e. `partials/header.php`). All containers set above the placeholder are outputted in the header. All containers below are outputted in the footer.

## Selecting the Page Templates

The `default` template is used unless another is selected. Dynamic templates will appear above theme templates in the "Page Attributes" panel of the Gutenberg editor.

<img src="/.github/docs/template-panel.png" alt="Page attribute template panel" width="278">

Templates with the "(theme)" suffix mark those with a PHP template using the standard WordPress `Template Name: Full-width` comment metadata.

<!--

The **Settings > VF Settings** option page in the Admin area defines the order.

For example:

1. Global Header
2. Breadcrumbs
3. Page Template †
4. EMBL News

† The *"Page Template"* container is registered by the `vf-wp` core plugin. It is a placeholder for the current page template found in the theme directory (as defined by the [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)).

The theme exposes two actions: `vf_header` and `vf_footer`. They are triggered by their respective template partials (i.e. `partials/header.php`). All containers set above the *"Page Template"* are outputted in the header. All containers below are outputted in the footer.

Containers can be configured under **VF Containers** in the Admin area. See the individual plugin README files for a detailed spec.

Containers have the custom post type: `vf_container`.

-->
