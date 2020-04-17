# WordPress setup

To setup a new blog:

* Run default WP install
* Permanently delete all default comments, posts, and pages (make sure trash is empty)
* Delete default plugins and themes from `wp-content`
* Add standard WordPress `.htaccess` file to root
* Publish a new page "Home" as `post_title`
* Publish a new page "Blog" as `post_title`

### Default options

In `wp_options` table set:

| option_name | option_value | autoload |
| ----------- | ------------ | -------- |
| show_on_front | `page` |  |
| page_on_front | [new Home page ID] |  |
| page_for_posts | [new Blog page ID] |  |
| blogname | [Site title, e.g. "Häring Group"] |  |
| blogdescription |  |  |
| date_format | `j F Y` |  |
| default_ping_status | `closed` |  |
| default_pingback_flag | 0 |  |
| permalink_structure | `/%year%/%monthnum%/%postname%/` |  |
| options_vf_cdn_stylesheet | [CDN URL] | no |
| options_vf_cdn_javascript | [CDN URL] | no |
| options_vf_api_url | [API URL] | no |

### CDN and API URLs (examples):

Development bleeding edge of CSS, JS:

```
https://dev.assets.emblstatic.net/vf/develop/css/styles.min.css
https://dev.assets.emblstatic.net/vf/develop/scripts/scripts.js
```

Versioned URLs of CSS, JS:

```
https://dev.assets.emblstatic.net/vf/v2.0.0-beta.3/css/styles.min.css
https://dev.assets.emblstatic.net/vf/v2.0.0-beta.3/scripts/scripts.js
```

EMBL ContentHub API:

```
https://www.embl.org/api/v1/
```

* Add and activate **ACF Pro** (Advanced Custom Fields) plugin
* Flush rewrite rules?

### Install theme

To setup the Visual Framework theme:

* Add and activate theme `vf-wp` to `wp-content/themes` directory
* Add and activate plugins to `wp-content/plugins` directory:

1. `vf-gutenberg`
2. `vf-wp`
3. other block/container plugins that depend on `vf-wp`

* Add and activate container plugins:

  1. `vf-global-header-container`
  2. `vf-breadcrumbs-container`
  3. `vf-embl-news-container`
  4. `vf-global-footer-container`

**Note: the `vf-wp` plugin is required for all other VF plugins.**

### Configure the default template

The theme allows for dynamic page templates. These are configured as posts with the type `vf_template`. There should be at least one `default` post of this type which is usually inserted when the `vf-wp` plugin is activated.

After the theme and plugins are activated ensure this post exists:

| post_type | post_name | post_content |
| --------- | --------- | ------------ |
| vf_template | default | see examples below... |

The `post_content` for basic EMBL sites:

```html
<!-- wp:vf/container-global-header /-->

<!-- wp:vf/container-page-template /-->

<!-- wp:vf/container-global-footer /-->
```

or for Group sites:

```html
<!-- wp:vf/container-ebi-global-header /-->

<!-- wp:vf/container-breadcrumbs /-->

<!-- wp:vf/container-wp-groups-header /-->

<!-- wp:vf/container-page-template /-->

<!-- wp:vf/container-embl-news /-->

<!-- wp:vf/container-ebi-global-footer /-->
```

### Content setup

* Add a new menu named "Primary" assigned to `primary` location.
* Add a new menu named "Secondary" assigned to `secondary` location.

*Page Templates* need to be assigned for these pages:

* Members: `template-members.php`
* Publications: `template-publications.php`

– by setting the `_wp_page_template` meta property.

### Locking posts

Posts and pages can be locked so that non-admin users cannot edit them. (By default the 'editor' role can edit all posts.)

In the `wp_postmeta` table assign two properties against the Post ID:

| meta_key | meta_value |
| ----------- | ------------ |
| vf_locked | 1 |
| \_vf_locked | field_vf_locked |

### Plugin setup

After a VF Block or Container plugin is activated it will create (or update) a post in the `wp_posts` table. For example the "Group Header" block and the "EMBL News" container will have these fields:

| post_name | post_type |
| ----------- | ------------ |
| vf_group_header | vf_block |
| vf_embl_news | vf_container |

The post `ID` can be found using this WP CLI command:

```sh
wp post list --name=vf_group_header --post_type=vf_block --field=ID
```

Default content for a plugin is assigned as post meta – either as content itself, or values for Content Hub API configuration.

The "Group Header" block is hard-coded into the standard page template. The default heading text can be set like so:

| meta_key | meta_value |
| ----------- | ------------ |
| vf_group_header_heading | `<h1>The Häring group aims to understand the molecular machinery that organises eukaryotic genomes.</h1>` |
| \_vf_group_header_heading | field_vf_group_header_heading |

The second field is a reference used by ACF. It should follow a predictable pattern of prefixing the key with an `_` (underscore) and the value as `field_[KEY]`.

Available fields can be seen in the plugin ACF file:

```
plugins/vf-group-header-block/group_vf_group_header.json
```

This will be documented into a README in future.

The "EMBL News" container:

```sh
wp post list --name=vf_embl_news --post_type=vf_container --field=ID
```

| post_name | post_type | post_title |
| ----------- | ------------ | ------------ |
| vf_embl_news | vf_container | EMBL and Cancer |

– makes use of the `post_title` in its template. So this can be changed too.

| meta_key | meta_value |
| ----------- | ------------ |
| vf_embl_news_limit | 3 |
| \_vf_embl_news_limit | field_vf_embl_news_limit |

It also has the meta property above that is used to request 3 news articles from the Content Hub. Other meta properties still need to be implemented – they will reflect the Content Hub API parameters.

The process above is enough to set up Container plugins because they only have one instance.

### VF Block plugins and Gutenberg

VF Block plugins can be added as blocks within the Gutenberg editor.

Gutenberg stores all content in the `post_content` field (for backwards compability).

Gutenberg makes used of HTML comments to define blocks. A standard heading block has this markup:

```html
<!-- wp:heading {"level":2} -->
<h2>Heaving level two</h2>
<!-- /wp:heading -->
```

VF Blocks as Gutenberg blocks are a bit more complicated. They're defined as a single HTML comment with JSON data.

```html
<!-- wp:vf/group-header {"ver":1} /-->
```

The plugin name (`vf_group_header`) is prefixed with `vf/` and underscores are converted to hyphens. Attributes are encoded as JSON. The version attribute (`ver`) is not necessary but will be added by Gutenberg if post content is edited.

Field attributes can be changed beyond the plugin default ACF configuration by editing the JSON content:

```html
<!-- wp:vf/group-header {"heading":"Test","ver":1} /-->
```

In the example above the `vf_group_header_heading` field value is changed. The field key prefix (`vf_group_header_`) is not required within the Gutenberg block code.

HTML content should be encoded for JSON. Gutenberg converts a lot to unicode but basic encoding like this should work:

```json
{ "heading": "<h1>The group introduction<\/h1>" }
```

Please note that most block templates will escape HTML for safety.

The home page for a Groups microsite has four blocks. The `post_content` looks something like this:

```html
<!-- wp:vf/group-header {"ver":1} /-->

<!-- wp:vf/latest-posts {"ver":1} /-->

<!-- wp:vf/data-resources {"ver":1} /-->

<!-- wp:vf/jobs {"limit":1,"filter":"all","ver":1} /-->
```
