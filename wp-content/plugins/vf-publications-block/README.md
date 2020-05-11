# Publications (block)

To display a list of team or group publications from the Content Hub.

## Configuration

ACF / Block data:

| field key | field name |
| --------- | ---------- |
| field_vf_publications_heading | vf_publications_heading |
| field_vf_publications_limit | vf_publications_limit |
| field_vf_publications_order | vf_publications_order |
| field_vf_publications_type | vf_publications_type |
| field_vf_publications_query | vf_publications_query |

Block `name`: `acf/vf-publications`

See plugin JSON file for source of truth.

### Related post

| post_name | post_type |
| --------- | --------- |
| vf_publications | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_publications_heading | "Publications" |
| \_vf_publications_heading | field_vf_publications_heading |

### Heading

**Key**: `vf_publications_heading`
**Value**: string (e.g. "Latest publications")

An optional heading above the content using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Order

**Key**: `vf_publications_order`
**Value**: string (default: "DESC")

Order of publications (Content Hub API).

### Limit

**Key**: `vf_publications_limit`
**Value**: integer (range: 1–10, default: 1)

Maximum number of publications to display (Content Hub API).

## Page Template

The theme includes a page template entitled "Publications" (`template-publications.php`).

This template provides a sidebar form to manage query string values:

* `filter_keyword` (text field)
* `filter_year` (select field, e.g. "2019")

The `VF_Publications` class has two methods (`get_query_year()`, `get_query_keyword()`) that can be used for API calls.

For the `post_id` of the "Publications" page:

| meta_key | meta_value |
| -------- | ---------- |
| \_wp_page_template | template-publications.php |
