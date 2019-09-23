# Visual Framework 2.0 WordPress theme

This theme allows integration with the VF 2.0. It is currently heavily tied to
the brand-specific "VF 2.0 for the EMBL Design Language".

## Documentation

* [Work in Progress, Issues, Bugs](https://github.com/visual-framework/vf-wp/issues)
* [Theme and plugin architecture](docs/architecture.md)
* [WordPress setup](docs/wordpress.md)

## Plugin documentation

* [EMBL Taxonomy](wp-content/plugins/embl-taxonomy/README.md)
* [VF Gutenberg](wp-content/plugins/vf-gutenberg/README.md)
* [Containers](docs/containers.md)
* [Blocks](docs/blocks.md)

## Development

### Theme and plugin development

* Git
* Node
* Gulp (optional)

```bash
# Install dev dependencies
npm install
# List build tasks
npx gulp --tasks
```

### EMBL + EMBL-EBI site development

n.b. To develop locally you'll need to make sure your project's git submodules are up to date: `git submodule update --init --recursive`

1. `git clone --recursive https://github.com/visual-framework/vf-wp.git`
1. Use any of the below variation of command to build the site

    ##### Run command

    - `bin/dev quick_group` - to build WordPress website with basic Visual Framework default configuration - Plugin/themes enabled
    - `bin/dev quick_group_bootstrap` - to build WordPress website setup with Visual Framework dummy microsite bootstrap version
    - `bin/dev launch` - to launch browser
    - `bin/dev login`  - to login in wordpress admin

    ##### Diagnostics

    - `bin/dev logs`    - tail logs from containers
    - `bin/dev pma`     - launch phpMyAdmin to view database
    - `bin/dev down`   - to spin down docker containers

1. Default variables including CSS/JS version, site title, admin password are configured in `.env`
