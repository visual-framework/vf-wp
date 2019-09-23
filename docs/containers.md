# VF-WP Container plugins

Containers represent a horizontal slice of the page template.

* [Beta](wp-content/plugins/vf-beta-container/README.md)
* [Breadcrumbs](wp-content/plugins/vf-breadcrumbs-container/README.md)
* [EMBL News](wp-content/plugins/vf-embl-news-container/README.md)
* [Global Footer](wp-content/plugins/vf-global-footer-container/README.md)
* [Global Header](wp-content/plugins/vf-global-header-container/README.md)

## Architecture

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
