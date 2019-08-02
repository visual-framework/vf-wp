# Publications (block)

To display a list of team or group publications from the Content Hub.

This is mostly a clone of `vf-publications-block` and has been done so in case
different logic and features arise over time.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_publications_group_ebi | vf_block |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_publications_group_ebi_heading | [STRING] |
| \_vf_publications_group_ebi_heading | field_vf_publications_group_ebi_heading |
| vf_publications_group_ebi_order | [STRING] |
| \_vf_publications_group_ebi_order | field_vf_publications_group_ebi_order |
| vf_publications_group_ebi_limit | [INT] |
| \_vf_publications_group_ebi_limit | field_vf_publications_group_ebi_limit |

### Heading

**Key**: `vf_publications_group_ebi_heading`
**Value**: string (e.g. "Latest publications")

An optional heading above the content using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Order

**Key**: `vf_publications_group_ebi_order`
**Value**: string (default: "DESC")

Order of publications (Content Hub API).

### Limit

**Key**: `vf_publications_group_ebi_limit`
**Value**: integer (range: 1–10, default: 1)

Maximum number of publications to display (Content Hub API).

## Page Template

The theme includes a page template entitled "Publications" (`template-publications.php`).

This template provides a sidebar form to manage query string values:

* `filter_keyword` (text field)
* `filter_year` (select field, e.g. "2019")

The `VF_Publications_group_ebi` class has two methods (`get_query_year()`, `get_query_keyword()`) that can be used for API calls.

For the `post_id` of the "Publications" page:

| meta_key | meta_value |
| -------- | ---------- |
| \_wp_page_template | template-publications.php |
