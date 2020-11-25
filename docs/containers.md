# Containers

Containers are large, single use template patterns based on the [Visual Framework](https://stable.visual-framework.dev/). They usually represent a horizontal slice of a page template like the header. Therefore, a page template can be defined as a stack of containers. They are registered similarly to [plugin blocks](docs/blocks.md#plugin-blocks) but are not available in Gutenberg editor for normal pages.

Topics on this page:

* [Plugin Containers](#plugin-containers)
* [Containers in Templates](#containers-in-templates)
* [Containers in Theme Templates](#containers-in-theme-templates)

## Plugin Containers

The [VF-WP plugin](/wp-content/plugins/vf-wp/README.md) defines a custom post type `vf_container` and a set of PHP classes. Individual containers inherit the [`VF_Plugin`](/wp-content/plugins/vf-wp/README.md#vf_plugin) class. Containers have metadata assigned to their respective posts. They can be configured under **WP Admin > Content Hub > Containers** on a per-site basis.

* [Banner](/wp-content/plugins/vf-banner-container/README.md)
* [Beta](/wp-content/plugins/vf-beta-container/README.md) <sup>†1</sup>
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

* * *

## Containers in Templates

Templates are configuration container stacks that can be used to define preset  theme templates.

[Templates documentation →](/docs/templates.md)

* * *

## Containers in Theme Templates

It is possible to hard-code containers into theme templates:

```php
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Plugin::get_plugin('vf_breadcrumbs'));
}
```

However it is preferable to use the template architecture described above.
