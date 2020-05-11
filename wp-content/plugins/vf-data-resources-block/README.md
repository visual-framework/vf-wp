# Data resources (block)

A list of group data resources using the `vf-summary--profile` Visual Framework pattern.

## Configuration

ACF / Block data:

| field key | field name | type |
| -------- | --------- | ---------- |
| field_vf_data_resources_heading | vf_data_resources_heading | [STRING] |
| field_vf_data_resources_limit | vf_data_resources_limit | [INT] |
| field_vf_data_resources_order | vf_data_resources_order | [INT] |

## Related post

| post_name | post_type |
| --------- | --------- |
| vf_data_resources | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_data_resources_limit | 100 |
| \_vf_data_resources_limit | field_vf_data_resources_limit |

### Limit

**Key**: `vf_data_resources_limit`
**Value**: integer (range: 1–100, default: 100)

Maximum number of group data resources (Content Hub API).

### Order

**Key**: `vf_data_resources_order`
**Value**: string (default: "DESC")

Order of data resources (Content Hub API).

### Heading
**Key**: `vf_data_resources_heading`
**Value**: string (default: "Data resources")


## Global Configuration

| option_name | option_value |
| ----------- | ------------ |
| options_embl_taxonomy_term_what | [TERM_ID] |

Option provided by the **EMBL Taxonomy** plugin.

Term name (e.g. "Sharpe Group") used to filter API results.
