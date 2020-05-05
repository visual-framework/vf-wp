# PUBLISHING

A reference guide on how to do releases of `vf-wp`, and its themes+plugins. The two are done independently.

## `vf-wp` project

The `vf-wp` is a parent project. It is the bundling of the themes, plugins and some configuration.

1. Go to the add a new release on GitHub
  - https://github.com/visual-framework/vf-wp/releases
1. Add a new version number
  - these are semantic versions
1. Add release notes, emphasizing any breaking changes or changes in deployment
1. Notify EMBL-EBI Web Development of the new version

## Themes and plugins inside `vf-wp`

Components are contained in a [monorepo](https://gomonorepo.org) inside of the `vf-wp` project. They are NOT individually published. When changes are made to the plugins and themes inside `vf-wp`, their version numbers should be updated accordingly.

For example:

- [wp-content/plugins/embl-taxonomy/embl-taxonomy.php](https://github.com/visual-framework/vf-wp/blob/master/wp-content/plugins/embl-taxonomy/embl-taxonomy.php#L6)
- [wp-content/themes/vf-wp-news/style.css](https://github.com/visual-framework/vf-wp/blob/master/wp-content/themes/vf-wp-news/style.css#L8)

Updating the versions on these `vf-wp` child assets will allow the current version of the plugins and theme to be seen from the WordPress admin interface.

The versions of the child assets do not need to match `vf-wp` and can be independently and semantically versioned.
