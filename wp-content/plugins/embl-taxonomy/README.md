# WordPress EMBL Taxonomy Plugin

## What does this do?

The plugin registers a custom WordPress taxonomy called "EMBL Taxonomy". Taxonomy terms are synced with the real EMBL Taxonomy API. Posts can be tagged with one or more terms (similar to default WordPress Tags). The WordPress REST API exposes the tagged terms.

### Todo:

 - [ ] Auto sync via WordPress Admin page load, or an external trigger?
 - [ ] Notify user when editing a post tagged with a deprecated term
 - [ ] Some kind of testing

### Extra / Unknown:

 - [ ] Add taxonomy version to term meta (useful?)
 - [ ] Poll an external API for version info so changes can be synced?
 - [ ] Show default suggestion when focused in empty terms field when editing a post (possible?)
 - [ ] Provide option for enabling per `post_type` (i.e. 'post', 'page', etc)
 - [ ] Provide option to set taxonomy to public with rewrite rules (e.g. `/embl-taxonomy/term-slug/`)

## Template Functions

```php
embl_taxonomy();
```

Return the `EMBL_Taxonomy` class instance used by the plugin.

```php
embl_taxonomy_sync_terms();
```

Manually sync the taxonomy terms with the API.

```php
embl_taxonomy_get_url();
```

Return the EMBL Taxonomy API URL as configured by the ACF settings page.

```php
// int
embl_taxonomy_get_term(4);
// array
embl_taxonomy_get_term(
  array(
    '98831673-5bc8-4348-8f42-17b09c1d5462',
    'f4d27b51-cddd-4eba-919e-1ea57cc255c8'
  )
);
// string
embl_taxonomy_get_term('98831673-5bc8-4348-8f42-17b09c1d5462')
```

Return the associated `WP_Term` from the registered `embl_taxonomy` terms. If an integer is passed it will search by `WP_Term->term_id`. An array will search for that hierarchy of UUIDs (e.g. "What > Research"). A string will search for a single UUID – **please note** - not all EMBL Taxonomy terms exist on their own and instead may have multiple parents.

## How it works

The EMBL Taxonomy is imported as JSON similar to the example below:

```json
{
  "terms": [
    { "name": "Activities", "uuid": 200, "parents": [] },
    { "name": "Research", "uuid": 201, "parents": [200] },
    { "name": "Cell Division", "uuid": 202, "parents": [201] },
    { "name": "Ellenberg Group", "uuid": 203, "parents": [202, 301] },
    { "name": "People", "uuid": 300, "parents": [] },
    { "name": "Groups", "uuid": 301, "parents": [300] }
  ]
}
```

A flat WordPress taxonomy (registered as `embl_taxonomy`) is populated with terms generated for each parent hierarchy:

1. Activities
2. Activities > Research
3. Activities > Research > Cell Division
4. Activities > Research > Cell Division > Ellenberg Group
5. People
6. People > Groups
7. People > Groups > Ellenberg Group

The syncing process attempts to match terms based on the `id` chain. Name changes are updated where matches are found.

Metadata is saved for each term to remember the original `id` and `parents` from the EMBL Taxonomy.

The WordPress REST API exposes the tagged terms for a post like so:

```json
{
  "embl_taxonomy_terms": [
    { "uuid": 203, "parents": [200, 201, 202], "name": "Ellenberg Group" },
    { "uuid": 203, "parents": [300, 301], "name": "Ellenberg Group" },
    { "uuid": 500, "parents": [], "name": "Deprecated Term Example", "deprecated": true }
  ]
}
```

This example post was tagged three times:

* *People > Groups > Ellenberg Group*
* *Activities > Research > Cell Division > Ellenberg Group*
* *Deprecated Term Example*

Terms are flagged as `deprecated` if they were previously tagged to a post but no longer exist.

As a [WordPress Taxonomy](https://codex.wordpress.org/Taxonomies) the plugin is compatible with all standard taxonomy and term template functions. Editing the taxonomy should be avoided as this is handled automatically. Edit and delete capabilities are unassigned for all user roles.

## WordPress Admin

A list of taxonomy terms can be viewed by visiting **Posts > EMBL Taxonomy**:

```
/wp-admin/edit-tags.php?taxonomy=embl_taxonomy
```

If the cached taxonomy is older than `MAX_AGE` an admin notice will appear linking to:

```
/wp-admin/edit-tags.php?taxonomy=embl_taxonomy&sync=true
```

This URL will force a resync with the EMBL Taxonomy.

## ACF Configuration

If the Advanced Custom Fields (ACF) plugin is also activated a settings page will be available:

```
/wp-admin/options-general.php?page=embl-settings
```

These settings are saved in the `wp_options` table like so:

| option_name | option_value |
| ----------- | ------------ |
| options_embl_taxonomy | [URL] |
| \_options_embl_taxonomy | field_embl_taxonomy |
| options_embl_taxonomy_autocomplete | 1 |
| \_options_embl_taxonomy_autocomplete | field_embl_taxonomy_autocomplete |
| options_embl_taxonomy_term_who | [TERM_ID] |
| options_embl_taxonomy_term_what | [TERM_ID] |
| options_embl_taxonomy_term_where | [TERM_ID] |
| \_options_embl_taxonomy_term_who | field_embl_taxonomy_term_who |
| \_options_embl_taxonomy_term_what | field_embl_taxonomy_term_what |
| \_options_embl_taxonomy_term_where | field_embl_taxonomy_term_where |

The `TERM_ID` refers to the `wp_terms` table primary key.

`options_embl_taxonomy_autocomplete` (1 or 0) allows keyword search suggestions on templates like "Jobs" and "Publications".

`options_embl_taxonomy` is the URL for the EMBL Taxonomy JSON feed. For example:

```
https://dev.beta.embl.org/api/v1/pattern.json?pattern=embl-ontology&source=contenthub
```

If the three term options are set meta tags will be outputted in the `<head>`.

```html
<meta name="embl:who" content="Sharpe Group" uuid="7ee108ed-9d0a-4476-bc75-acad5f02c5a0">
<meta name="embl:where" content="EMBL Barcelona" uuid="90909ff5-f73f-4799-9c25-4427ce84eea0">
<meta name="embl:what" content="Multicellular systems biology" uuid="7869db5e-2b20-4308-b250-7b05ac81f740">
<meta name="embl:active" content="what">
```
