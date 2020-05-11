See [VF-WP Blocks](/docs/blocks.md) general documentation.

# EMBL News (block)

A list of latest news articles from the Content Hub. Using the `vf-summary--news` Visual Framework pattern.

## Configuration

ACF / Block data:

| field key | field name |
| --------- | ---------- |
| field_5eb014ed283d9 | limit |
| field_5eb01560283da | type † |
| field_5eb01627283db | embl_terms ‡ |
| field_5eb01691283dc | keyword ‡ |
| field_5eb016ca283dd | ids ‡ |
| field_5eb01805283df | tags ‡ |

Block `name`: `acf/vf-embl-news`

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
