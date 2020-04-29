# VF-WP Events

This plugin registers a custom post type for events.

## Post Meta (ACF)

The `vf_event` post type has custom meta properties:

| meta_name | meta_value |
| -------- | ---------- |
| vf_event_start_date | [DATE] † ‡ |
| vf_event_end_date | [DATE] † |
| vf_event_location | [TEXT] † |

† Dates are saved as `Ymd` (YYYYMMDD) in the database.

‡ The `vf_event_start_date` value is used to order archives instead of the standard `post_date` (published date). The start date is required for events to appear in the archives.

Event meta is included in the WordPress REST API:

```json
{
  "vf_event_start_date": {
    "value": "20200917",
    "formatted": "17 September 2020"
  }
}
```

For all meta properties see the [JSON config files](/wp-content/plugins/vf-events/acf-json).

## Archives

The default events archive displays upcoming events ordered nearest to farthest in the future. The archive URL is:

```
/events/
```

Past events can be found at:

```
/events/past/
```

Past events are ordered most recent first.

## Settings

WordPress Admin settings are accessible via **Events > Settings**:

```
/wp-admin/edit.php?post_type=vf_event&page=vf-events-settings
```

The global options are:

| meta_name | meta_value |
| ----------- | ------------ |
| vf_event_date_format | [CHOICES] † |
| vf_event_date_format_custom | [TEXT] |
| vf_event_upcoming_title | [TEXT] |
| vf_event_past_title | [TEXT] |
| vf_events_per_page | [NUMBER] |

† If the date format is `custom` the custom text string is used.

See the ACF JSON file for default values.

## Templates

Default templates exist for event **archive** and **singular** pages. Themes can override or customize them using standard template naming. If required copy the files from `vf-events/templates` into your theme directory:

```
archive-vf_event.php
single-vf_event.php
```

## Template Functions

```php
VF_Events::is_upcoming_archive();
```

Returns `true` on the upcoming events archive template.

```php
VF_Events::is_past_archive();
```

Returns `true` on the past events archive template.

```php
VF_Events::get_archive_title();
```

Returns the archive title ("Upcoming Events", or "Past Events" by default).

```php
VF_Events::get_archive_pages();
```

Returns an array of `next` and `previous` archive page URLs. These values will be `false` if there is no page.

## Gutenberg block

This plugin registers an "Events List" block using ACF.
