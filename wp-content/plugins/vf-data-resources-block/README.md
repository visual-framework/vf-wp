# Data resources (block)

A list of group data resources using the `vf-summary--profile` Visual Framework pattern.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_data_resources | vf_block |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_data_resources_limit | [INT] |
| \_vf_data_resources_limit | field_vf_data_resources_limit |
| vf_data_resources_order | [STRING] |
| \_vf_data_resources_order | field_vf_data_resources_order |

### Limit

**Key**: `vf_data_resources_limit`
**Value**: integer (range: 1–50, default: 10)

Maximum number of group data resources (Content Hub API).

### Order

**Key**: `vf_data_resources_order`
**Value**: string (default: "DESC")

Order of data resources (Content Hub API).

## Global Configuration

| option_name | option_value |
| ----------- | ------------ |
| options_embl_taxonomy_term_what | [TERM_ID] |

Option provided by the **EMBL Taxonomy** plugin.

Term name (e.g. "Sharpe Group") used to filter API results.

## Page Template

The theme includes an optional page template entitled "Data resources" (`template.php`). This will include a minimal "Header" block above the data resources list to show the group leader.

For the `post_id` of the "Data resources" page:

| meta_key | meta_value |
| -------- | ---------- |
| \_wp_page_template | template.php |
