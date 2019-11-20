# VF-WP plugin

[See Architecture documentation.](/docs/architecture.md)

This plugin provides core functionality for the VF-WP theme and plugins documented above.

There are several PHP classes defined here.

## VF_Type

The `VF_Type` class is extended by `VF_Blocks` and `VF_Containers`. It provides common methods to:

* Register a custom post type
* Setup administrator capabilities
* Add ACF rules for location matching on `post_name`
* Add ACF filters for listing the post type

## VF_Containers

The `VF_Containers` class extends to:

* Register the `vf_container` post type
* Insert the placeholder *"Page Template"* container
* Register action hooks for `vf_header` and `vf_footer` template functions

## VF_Blocks

The `VF_Blocks` class extends to:

* Register the `vf_block` post type
* Add `vf/wp` Gutenberg block category

## VF_Plugin

The `VF_Plugin` class is extended by all Block and Container plugins.

It provides common methods to:

* Register and initialise the plugin
* Insert the custom post for the plugin
* Load custom ACF config from the plugin directory
* Return the template path from the plugin directory
* Render a plugin template (static method)

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

The default settings for both plugin types can be edited under **VF Blocks** and **VF Containers** in the admin area.

Some plugins will dynamically add fields via PHP rather than JSON. This allows for additional configuration based on other plugins, such as the *EMBL Taxonomy* plugin.

### Using ACF in development

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
2. `vf/plugin/before_render/vf_jobs`
3. `vf/plugin/block/before_render`
3. `vf/plugin/block/before_render/vf_jobs`

and *After* a block is rendered:

1. `vf/plugin/after_render`
2. `vf/plugin/after_render/vf_jobs`
3. `vf/plugin/container/after_render`
4. `vf/plugin/container/after_render/vf_jobs`

Containers have the same actions – replace `block` with `container`.

Use the standard WordPress [`add_actions`](https://developer.wordpress.org/reference/functions/add_action/) function to provide a callback, for example:

```php
add_action(
  'vf/plugin/after_render/vf_breadcrumbs',
  'after_breadcrumbs_callback'
);
```

The `VF_Plugin` extended instance is passed as the first argument to the callback.
