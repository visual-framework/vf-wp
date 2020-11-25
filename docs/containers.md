# Containers

Containers are large, single use template patterns based on the [Visual Framework](https://stable.visual-framework.dev/). They usually represent a horizontal slice of a page template like the header. Therefore, a page template can be defined as a stack of containers. They are registered similarly to [plugin blocks](docs/blocks.md#plugin-blocks) but are not available in Gutenberg editor for normal pages.

The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_container` and a set of PHP classes. Individual containers inherit the [`VF_Plugin`](/wp-content/plugins/vf-wp/README.md#vf_plugin) class. Containers have metadata assigned to their respective posts. They can be configured under **WP Admin > Content Hub > Containers** on a per-site basis.

* [Banner](/wp-content/plugins/vf-banner-container/README.md)
* [Beta](/wp-content/plugins/vf-beta-container/README.md) †1
* [Breadcrumbs](/wp-content/plugins/vf-breadcrumbs-container/README.md)
* [EBI Global Footer](/wp-content/plugins/vf-ebi-global-footer-container/README.md)
* [EBI Global Header](/wp-content/plugins/vf-ebi-global-header-container/README.md)
* [EMBL News](/wp-content/plugins/vf-embl-news-container/README.md)
* [Global Footer](/wp-content/plugins/vf-global-footer-container/README.md)
* [Global Header](/wp-content/plugins/vf-global-header-container/README.md)
* [Hero](/wp-content/plugins/vf-hero-container/README.md)
* [Masthead](/wp-content/plugins/vf-masthead-container/README.md)
* [Navigation](/wp-content/plugins/vf-navigation-container/README.md)

Child theme containers:

* [Groups Header](/wp-content/themes/vf-wp-groups/vf-wp-groups-header/README.md)

<sup>†1 Container has been deprecated.</sup>

## Template Architecture

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
