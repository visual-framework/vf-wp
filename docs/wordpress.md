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
https://dev.assets.emblstatic.net/vf/v2.0/css/styles.min.css
https://dev.assets.emblstatic.net/vf/v2.0/scripts/scripts.js
```

```
https://dev.assets.emblstatic.net/vf/v2.0.0-alpha.4/css/styles.min.css
https://dev.assets.emblstatic.net/vf/v2.0.0-alpha.4/scripts/scripts.js
```

EMBL ContentHub API:

```
https://dev.beta.embl.org/api/v1/
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

In `wp_options` table add (autoload = `no`):

| option_name | option_value |
| ----------- | ------------ |
| options_vf_containers | 5 |
| \_options_vf_containers | field_vf_containers |
| options_vf_containers_0_vf_container_name | vf_global_header |
| \_options_vf_containers_0_vf_container_name | field_vf_container_name |
| options_vf_containers_1_vf_container_name | vf_breadcrumbs |
| \_options_vf_containers_1_vf_container_name | field_vf_container_name |
| options_vf_containers_2_vf_container_name | vf_page_template |
| \_options_vf_containers_2_vf_container_name | field_vf_container_name |
| options_vf_containers_3_vf_container_name | vf_embl_news |
| \_options_vf_containers_3_vf_container_name | field_vf_container_name |
| options_vf_containers_4_vf_container_name | vf_global_footer |
| \_options_vf_containers_4_vf_container_name | field_vf_container_name |

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
<!-- wp:acf/vf-group-header {"id":"block_5c5982ee0b0c8","data":{"field_vf_block_custom":"0"},"name":"acf/vf-group-header","mode":"preview"} /-->
```

Here is the JSON formatted for readability:

```json
{
  "id": "block_5c5982ee0b0c8",
  "data": {"field_vf_block_custom": "0"},
  "name": "acf/vf-group-header",
  "mode": "preview"
}
```

The `id` is a randomly generated string. ACF prefixes with `block_`. I think the only thing that matters is that it is unique.

The block name is prefixed with `acf/` and underscores are converted to hyphens.

When `field_vf_block_custom` is set to `0` the default block content is used (as assigned in the post meta). If set to `1` custom content can be define for this instance in the JSON:

```json
{
  "id": "block_5c5982ee0b0c8",
  "data": {
    "field_vf_block_custom": "1",
    "field_acf/vf-group-header_clone": {
      "field_vf_group_header_heading": "\u003ch1\u003eThe Häring group aims to understand the molecular machinery that organises eukaryotic genomes. \u003ca href=\u0022http://vfthemeprototype.lndo.site/about/\u0022\u003eRead more about the Häring group\u003c/a\u003e\u003c/h1\u003e"
    }
  },
  "name": "acf/vf-group-header",
  "mode": "preview"
}
```

HTML content should be encoded for JSON. Gutenberg converts a lot to unicode but basic encoding like this should work:

```json
{ "field_vf_group_header_heading": "<h1>The group introduction<\/h1>" }
```

The home page for a Groups microsite has four blocks. The `post_content` looks something like this:

```html
<!-- wp:acf/vf-group-header {"id":"block_5c65674caae4e","data":{"field_vf_block_custom":"1","field_acf/vf-group-header_clone":{"field_vf_group_header_heading":"\u003ch1\u003eThe Häring group aims to understand the molecular machinery that organises eukaryotic genomes. \u003ca href=\u0022http://vfthemeprototype.lndo.site/about/\u0022\u003eRead more about the Häring group\u003c/a\u003e\u003c/h1\u003e"}},"name":"acf/vf-group-header","mode":"preview"} /-->

<!-- wp:acf/vf-latest-posts {"id":"block_5c65674caae4f","data":{"field_vf_block_custom":"0"},"name":"acf/vf-latest-posts","mode":"preview"} /-->

<!-- wp:acf/vf-publications {"id":"block_5c65674caae50","data":{"field_vf_block_custom":"1","field_acf/vf-publications_clone":{"field_vf_publications_heading":"Latest publications","field_vf_publications_count":"1"}},"name":"acf/vf-publications","mode":"preview"} /-->

<!-- wp:acf/vf-jobs {"id":"block_5c65674caae51","data":{"field_vf_block_custom":"1","field_acf/vf-jobs_clone":{"field_vf_jobs_heading":"Latest jobs","field_vf_jobs_limit":"1"}},"name":"acf/vf-jobs","mode":"preview"} /-->
```
