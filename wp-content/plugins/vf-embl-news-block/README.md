# EMBL News (block)

A list of latest news articles from the Content Hub. Using the `vf-summary--news` Visual Framework pattern.

## Configuration

ACF / Block data:

| meta_key | meta_name | meta_value |
| -------- | --------- | ---------- |
| field_5eb014ed283d9 | limit | [INT] |
| field_5eb01560283da | type | [STRING] † |
| field_5eb01627283db | embl_terms | [TERM_ID] ‡ |
| field_5eb01691283dc | keyword | [STRING] ‡ |
| field_5eb016ca283dd | ids | [STRING] ‡ |
| field_5eb01805283df | tags | [STRING] ‡ |

Block `name`: `acf/vf-embl-news`

Block `id` must be unique per instance in the post content. Use a random ID, e.g. [`uniqid('block_')`](https://www.php.net/manual/en/function.uniqid.php).

### † Type

Supported type values:

  * `latest`
  * `taxonomy`
  * `keyword`
  * `ids`
  * `tags`

### ‡ Filters

Filter fields for **EMBL Taxonomy** (`taxonomy`), **Keyword** (`keyword`), **IDs** (`ids`), and **Tags** (`tags`), are optional and conditional based on the respective `type` field value.

### EMBL Taxonomy

The [EMBL Taxonomy plugin](/wp-content/plugins/embl-taxonomy/) must be activated for term filters to work.

The `embl_terms` field supports a single term ID. In future this may allow an array of multiple IDs. ACF should automatically handle this if the field changes from `select` to `mutli_select`.

### Free `tags`

The `tags` field is a placeholder for future Content Hub API filters. It currently has no effect on the search.

### Block (minimal/defaults)

```html
<!-- wp:acf/vf-embl-news {"id":"block_5eb014c0e6a33","name":"acf/vf-embl-news"} /-->
```
