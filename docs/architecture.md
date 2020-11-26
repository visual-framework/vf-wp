# Architecture

The Visual Framework WordPress theme uses a plugin-based architecture.

Pages in the VF-WP parent and child themes are built up using *block* and *container* plugins. Both types of plugin are represented as [custom post types](https://codex.wordpress.org/Post_Types). These are registered by the core [VF-WP plugin](/wp-content/plugins/vf-wp/README.md).

Each *block* or *container* plugin directory contains:

* `index.php`
* `template.php`
* `group_vf_[NAME].json` (optional)

And any additional includes or assets needed.

## VF-WP Plugin

[VF-WP plugin documentation →](/wp-content/plugins/vf-wp/README.md)


## Blocks

Blocks are small, reusable content patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Blocks documentation →](/docs/blocks.md)

## Containers

Containers are large, single use template patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Containers documentation →](/docs/containers.md)

## Templates

Templates are configurable container stacks that can be used to define preset dynamic theme templates.

[Templates documentation →](/docs/templates.md)
