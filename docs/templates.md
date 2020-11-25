# Templates

```html
<!-- wp:acf/vf-container-global-header {"id":"block_5f5f3490ce209","name":"acf/vf-container-global-header","align":"","mode":"preview"} /-->

<!-- wp:acf/vf-container-page-template {"id":"block_5ebb9edff871d","name":"acf/vf-container-page-template","data":{},"mode":"preview"} /-->

<!-- wp:acf/vf-container-global-footer {"id":"block_5f5f349ace20a","name":"acf/vf-container-global-footer","data":{},"align":"","mode":"preview"} /-->
```

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
