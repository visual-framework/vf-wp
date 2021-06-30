# VF 2.0 WordPress - Themes and Plugins

VF-WP is a collection of WordPress themes and plugins that integrate with the [Visual Framework](https://stable.visual-framework.dev/). They are currently heavily tied to the brand-specific "VF 2.0 for the EMBL Design Language". Additional WordPress plugin dependencies are kept in an external repository (see below).

## Documentation

* [Work in Progress, Issues, Bugs →](https://github.com/visual-framework/vf-wp/issues)
* [Theme and Plugin Architecture →](/docs/architecture.md)
* [Theming Documentation →](/wp-content/themes/vf-wp/README.md)
* [WordPress Setup →](/docs/wordpress.md)

## Plugin Documentation

* [External Plugins Repository](https://github.com/visual-framework/vfwp-external-plugins) - Please create tag whenever it is locally tested & ready to deploy on all sites and update the tagname in `VF_EXTERNAL_PLUGINS_REPO_TAG` variable in [`.env`](.env) file.
* [EMBL Taxonomy →](/wp-content/plugins/embl-taxonomy/README.md)
* [VF Gutenberg →](/wp-content/plugins/vf-gutenberg/README.md)
* [Events →](/wp-content/plugins/vf-events/README.md)

## Architecture

The Visual Framework WordPress theme uses a plugin-based architecture.

[Architecture documentation →](/docs/architecture.md)

## Blocks

Blocks are small, reusable content patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Blocks documentation →](/docs/blocks.md)

## Containers

Containers are large, single use template patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Containers documentation →](/docs/containers.md)

## Templates

Templates are configurable container stacks that can be used to define preset dynamic theme templates.

[Templates documentation →](/docs/templates.md)

* * *

## Development

### Theme and Plugin Development

Contributing to this repository requires command line tools:

* Git
* Node
* Gulp (optional)

To start:

```bash
# Install dev dependencies
yarn install
```

This project makes use of [Visual Framework components](https://visual-framework.github.io/vf-welcome) to build its CSS and JavaScript.

These scripts and tasks are available:

```sh
yarn run update-components
```

To interactively update the Visual Framework components (and other npm packages).

```sh
gulp build
```

* to build `vf-components/vf-componenet-rollup/index.scss`
  - to make `wp-content/themes/vf-wp/assets/css/styles.css`
* to build `vf-components/vf-componenet-rollup/scripts.scss`
  - to make `wp-content/themes/vf-wp/assets/scripts/scripts.js`

Note: [the CI](https://github.com/visual-framework/vf-wp/blob/master/.github/workflows/build.js.yml) will run `gulp build` on commit to `master`.

```sh
gulp default
```

To launch local development of the above with a `watch` task.

```sh
gulp vf-gutenberg
```

To compile the Gutenberg React blocks.

### EMBL + EMBL-EBI site local development for WordPress


1. Clone repo by running - `git clone --recursive https://github.com/visual-framework/vf-wp.git`
1. Use any of the below variation of command to build the site

    ##### Run command

    - `bin/dev quick_group` - to build WordPress website with basic Visual Framework default configuration - Plugin/themes enabled
    - `bin/dev quick_group_bootstrap` - to build WordPress website setup with Visual Framework dummy microsite bootstrap version
    - `bin/dev launch` - to launch browser
    - `bin/dev login`  - to login in wordpress admin

    ##### Diagnostics

    - `bin/dev logs`    - tail logs from containers
    - `bin/dev pma`     - launch phpMyAdmin to view database
    - `bin/dev up`   - to spin up docker containers
    - `bin/dev down`   - to spin down docker containers
    - `bin/dev mailhog`   - to launch mailhog to view e-mail sent my containers

n.b. To develop locally you'll need to make sure your project's git submodules are up to date: `git submodule update --init --recursive`

1. Default variables including CSS/JS version, site title, admin password are configured in `.env` file.
