# Groups Header (container)

VF-WP Groups theme global container. Includes masthead, navigation, and hero components from the Visual Framework.

This plugin can exist within the `vf-wp-groups` child theme directory and self-activate. The reason for that is because the plugin is required and bespoke to the Groups theme, whilst adhering to the container functionality of other plugins.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_wp_groups_header | vf_container |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_hero_enable | [INT] |
| \_vf_hero_enable | field_vf_hero_enable |
| vf_hero_theme | [STRING] |
| \_vf_hero_theme | field_vf_hero_theme |
| vf_hero_level | [INT] |
| \_vf_hero_level | field_vf_hero_level |
| vf_hero_heading | [STRING] |
| \_vf_hero_heading | field_vf_hero_heading |
| vf_hero_text | [STRING] |
| \_vf_hero_text | field_vf_hero_text |
| vf_hero_link | [ARRAY] |
| \_vf_hero_link | field_vf_hero_link |
| vf_hero_image | [INT] |
| \_vf_hero_image | field_vf_hero_image |


### Hero Enable

**Key**: `vf_hero_enable`
**Value**: integer/boolean (0 or 1, default: 0)

Set to `1` to show the `vf-hero` component. If disabled, only the blog name and navigation are visible.

### Hero Theme

**Key**: `vf_hero_theme`
**Value**: string (optional)

Optional design theming; choices are `default`, `primary`, `secondary`, and `tertiary`.

### Hero Level

**Key**: `vf_hero_level`
**Value**: integer (range: 1–5, default: 1)

More optional design variants.

### Hero Heading

**Key**: `vf_hero_heading`
**Value**: string (optional)

Optional alternate heading. Template defaults to `About the %1$s` with the `blogname` option as first argument.

### Hero Text

**Key**: `vf_hero_text`
**Value**: string (optional)

Optional alternate secondary heading or description. Template will default to the Group description from the Content Hub API (based on EMBL Taxonomy options).

### Hero Link

**Key**: `vf_hero_link`
**Value**: serialized array (optional)

Optional link appended to the description. Template will default to "Read more" if an `/about/` page slug exists.

The link value is a serialized array with `title` and `url` key/values.

### Hero Image

**Key**: `vf_hero_image`
**Value**: integer (optional)

The `post_id` key for an image post uploaded to the media library (with the `post_type` of `attachment`).

Template will default to a single background colour or inline SVG pattern.

## Template

The template combines the Visual Framework components for `vf-masthead`, `vf-navigation`, and `vf-hero`. The hero is only included when the `vf_hero_enable` option is set.

## Hooks

The plugin uses standard [WordPress hooks](https://codex.wordpress.org/Plugin_API/Hooks) to filter some of the values above. These filters may be useful for child themes.

```php
apply_filters(
  'vf_wp_groups_header/hero_heading',
  string $heading
);
```

Filter the `vf_hero_heading` value (custom or default) before it's passed to the template.

```php
apply_filters(
  'vf_wp_groups_header/hero_text',
  string $text
);
```

Filter the `vf_hero_text` value (custom or default) before it's passed to the template.

```php
apply_filters(
  'vf_wp_groups_header/hero_link',
  array $link
);
```

Filter the `vf_hero_link` value (custom or default) before it's passed to the template. The `$link` argument may be null or undefined.

### Default filters

The plugin applies several default filters with a `9` or lower priority.

They can be disabled:

```php
add_filter('vf_wp_groups_header/hero_text_cleanup', '__return_false');
```

Unless disabled, this default filter sanitizes `vf_hero_text` value using `wp_kses()`.

```php
add_filter('vf_wp_groups_header/hero_text_link', '__return_false');
```

This filter appends the hero link to the text content. If disabled `vf_hero_link` is ignored unless reapplied manually via other filters.
