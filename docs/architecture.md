# Architecture

The Visual Framework WordPress theme uses a plugin-based architecture.

### Contents:

* [Theme and plugins](#theme-and-plugins)
* [Container plugins](#container-plugins)
* [Block plugins](#block-plugins)
* [VF-WP plugin](#vf-wp-plugin)
* [Plugin ACF configuration](#plugin-acf-configuration)
* [Actions and filters](#actions-and-filters)

# Theme and plugins

With the VF-WP theme, pages are built up from *Block* and *Container* plugins. Both are represented as [custom post types](https://codex.wordpress.org/Post_Types) that are registered by the `vf-wp` core plugin.

Each Block or Container plugin directory contains:

* `index.php`
* `template.php`
* `group_vf_[NAME].json` (optional)

And any additional includes or assets needed.

Below I'll start with a high-level overview of containers and blocks before getting technical.

## Container plugins

[See container plugin documentation.](docs/containers.md)

## Block plugins

[See block plugin documentation.](docs/blocks.md)

## VF-WP plugin

[See vf-wp plugin documentation.](wp-content/plugins/vf-wp/README.md)
