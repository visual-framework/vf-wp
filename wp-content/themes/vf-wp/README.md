# VF-WP Theme

The Visual Framework WordPress theme provides a set of basic templates and theme options. The theme was developed alongside the [VF-WP plugins](/docs/architecture.md) to create a framework or sorts.

Child themes like `vf-wp-groups` are an example of extending the parent theme and utilising this framework.

## Theme Hooks

[Hooks](https://developer.wordpress.org/plugins/hooks/) are a way to customise things in WordPress. The VF-WP theme provides:

### Action Hooks

Documentation coming soon...

### Filter Hooks

#### Editor Color Palette Filter

```php
apply_filters( 'vf/theme/editor_color_palette', array $color_palette )
```

Filter available text colors in the Gutenberg editor.

##### Parameters

###### `$color_palette`

(array) See: [block color palettes](https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-color-palettes)

#### Editor Font Sizes Filter

```php
apply_filters( 'vf/theme/editor_font_sizes', array $font_sizes )
```

Filter available font sizes in the Gutenberg editor.

##### Parameters

###### `$font_sizes`

(array) See: [block font sizes](https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-font-sizes)

#### Theme Supports Filter

```php
apply_filters( 'vf/theme/supports', $supports )
```

Filter all options passed to [`add_theme_support`](https://developer.wordpress.org/reference/functions/add_theme_support/). The font sizes and color palette filters are appended to `$supports` before this filter is applied.

##### Parameters

###### `$supports`

(array) A list of either strings (e.g. `'title-tag'`), or nested arrays (e.g. `array('editor-font-sizes', array(...))`).
