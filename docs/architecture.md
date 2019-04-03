# Architecture

The Visual Framework WordPress theme uses a plugin-based architecture.

### Contents:

* [Theme and plugins](#theme-and-plugins)
* [Container plugins](#container-plugins)
* [Block plugins](#block-plugins)
* [VF core plugin](#vf-wp-core-plugin)
* [Plugin ACF configuration](#plugin-acf-configuration)
* [Actions and filters](#actions-and-filters)

# Theme and plugins

In the VF-WP theme pages are built up from *Container* and *Block* plugins. Both are represented as [custom post types](https://codex.wordpress.org/Post_Types) that are registered by the `vf-wp` core plugin.

Each plugin directory contains:

* `index.php`
* `template.php`
* `group_vf_[NAME].json` (optional)

And any additional includes or assets needed.

Below I'll start with a high-level overview of containers and blocks before getting technical.

## Container plugins

Containers represent a horizontal slice of the page. Only a small number of them exist. The **Settings > VF Settings** option page in the Admin area defines the order.

For example:

1. Global Header
2. Breadcrumbs
3. Page Template †
4. EMBL News

† The *"Page Template"* container is registered by the `vf-wp` core plugin. It is a placeholder for the current page template found in the theme directory (as defined by the [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)).

The theme exposes two actions: `vf_header` and `vf_footer`. They are triggered by their respective template partials (i.e. `partials/header.php`). All containers set above the *"Page Template"* are outputted in the header. All containers below are outputted in the footer.

Containers can be configured under **VF Containers** in the Admin area. See the individual plugin README files for a detailed spec.

Containers have the custom post type: `vf_container`.

## Block plugins

Blocks are smaller, reusable content patterns such as a list of jobs or publications.

Blocks may appear within the Gutenberg editor, sidebar widgets, or rendered elsewhere.

Default block settings are configured under **VF Blocks** in the Admin area. See the individual plugin README files for a detailed spec.

Blocks have the custom post type: `vf_block`.

## VF-WP core plugin

This plugin provides common functionality for all other container and block plugins. There are four PHP classes defined here.

The `VF_Type` class is extended by `VF_Blocks` and `VF_Containers`. It provides common methods to:

* Register the custom post type
* Setup administrator capabilities for the post type
* Add ACF rules for location matching based on the `post_name`
* Add ACF filters for listing the post type
* Provide a static function to render a plugin template

The `VF_Containers` class extends to:

* Insert the placeholder *"Page Template"* container post
* Register action hooks for `vf_header` and `vf_footer` to render container templates

The `VF_Blocks` class extends to:

* Add new Gutenberg block categories
* Register Gutenberg blocks

The fourth class `VF_Plugin` is extended by all container and block plugins. It provides common methods to:

* Register the plugin in the global option `vf__plugins`
* Insert the custom post for the plugin
* Load custom ACF config from the plugin directory
* Return the template path from the plugin directory

### The `vf__plugins` option

This option is set in the `wp_options` database table to keep track of all activated VF plugins and to help resolve template paths. It is automatically managed by `VF_Plugin` as they are activated or deactivated.

The value is a serialized PHP array that follows this format:

```php
array (
  'vf_page_template' =>
  array (
    'post_type' => 'vf_container',
  ),
  'vf_breadcrumbs' =>
  array (
    'post_type' => 'vf_container',
    'plugin__dirname' => 'vf-breadcrumbs-container',
  ),
  'vf_jobs' =>
  array (
    'post_type' => 'vf_block',
    'plugin__dirname' => 'vf-jobs-block',
  )
)
```

The array keys, such as `vf_breadcrumbs`, are the `post_name` of the container or block. **Because of this all `vf_container` and `vf_block` plugins should have a unique `post_name` despite being two different post types**.

## Plugin ACF configuration

ACF config for plugins is loaded from external JSON files. A strict naming convention is required.

A plugin with the `post_name` of `vf_breadcrumbs` must use a group key prefixed with `group_` followed by its `post_name`.

For example:

```json
{
  "key": "group_vf_breadcrumbs",
  "title": "VF Breadcrumbs (container)"
}
```

The file name will be: `group_vf_breadcrumbs.json`.

All fields within the group should be prefixed like so:

```json
"fields": [
  {
    "key": "field_vf_breadcrumbs_items",
    "name": "vf_breadcrumbs_items",
  }
]
```

The default config for both containers and blocks can be edited under **VF Containers** and **VF Blocks** in the admin area.

Some plugins will dynamically add fields via PHP rather than JSON. This allows for additional configuration based on other plugins, such as the *EMBL Taxonomy* plugin.

To edit Custom Fields during development:

1. Go to **Custom Fields > Field Groups** and sync the relevant field group
2. Edits the field group with the Admin UI (this will save to the JSON file)
3. Delete the field group in the Admin UI
4. Edit the JSON file to ensure all new field keys are named †

† ACF uses randomly generated hashes as field keys by default.

## Hooks and Actions

The VF theme and plugins have several [actions](https://developer.wordpress.org/plugins/hooks/actions/) that can be hooked into.

### Template Actions

Two template actions exist:

* `vf_header` is called in `partials/header.php` after the opening `<body>` tag
* `vf_footer` is called in `partials/footer.php` before the closing `</body>` tag

The VF core plugin hooks into these actions to render the containers before and after the main page template.

### Actions and filters

*Before* a block is rendered these actions are called sequentially:

1. `vf/plugin/before_render`
2. `vf/plugin/before_render?post_name=vf_jobs`
3. `vf/block/before_render`
4. `vf/block/before_render?post_name=vf_jobs`

and *After* a block is rendered:

1. `vf/plugin/after_render`
2. `vf/plugin/after_render?post_name=vf_jobs`
3. `vf/block/after_render`
4. `vf/block/after_render?post_name=vf_jobs`

Containers have the same actions – replace `block` with `container`.

Use the standard WordPress [`add_actions`](https://developer.wordpress.org/reference/functions/add_action/) function to provide a callback, for example:

```php
add_action(
  'vf/container/after_render/post_name=vf_breadcrumbs',
  'after_breadcrumbs_callback'
);
```

The `VF_Plugin` extended instance is passed as the first argument to the callback.

### Widget Actions

The theme generates an action before rendering sidebar widgets.

```php
add_filter('vf/render_widget_search', 'render_widget_search');

function render_widget_search($html) {
  // Do something...
  return $html;
}
```

The action is based on the standard widget class attribute. Plugins that register a new widget should use markup like:

```html
<div class="widget widget_vf_factoid">
```

The callback should return the edited HTML.
