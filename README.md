# VF 2.0 WordPress - Themes & Plugins

VF-WP is a collection of WordPress themes and plugins that integrate with the [Visual Framework](https://stable.visual-framework.dev/). They are currently heavily tied to the brand-specific "VF 2.0 for the EMBL Design Language". Additional WordPress plugin dependencies are kept in an external repository (see below).

## Documentation

* [Work in Progress, Issues, Bugs →](https://github.com/visual-framework/vf-wp/issues)
* [Theme and plugin architecture →](/docs/architecture.md)
* [Theming documentation →](/wp-content/themes/vf-wp/README.md)
* [WordPress setup →](/docs/wordpress.md)

## Plugin Documentation

* [External Plugins Repository](https://github.com/visual-framework/vfwp-external-plugins)
* [EMBL Taxonomy →](/wp-content/plugins/embl-taxonomy/README.md)
* [VF Gutenberg →](/wp-content/plugins/vf-gutenberg/README.md)
* [Events →](/wp-content/plugins/vf-events/README.md)

## [Blocks](/docs/blocks.md)

Blocks are small, reusable content patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Blocks documentation →](/docs/blocks.md)

## [Containers](/docs/containers.md)

Containers are large, single use template patterns based on the [Visual Framework](https://stable.visual-framework.dev/).

[Containers documentation →](/docs/blocks.md)

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

To launch local developement of the above with a `watch` task.

```sh
gulp vf-gutenberg
```

To compile the Gutenberg React blocks.

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
