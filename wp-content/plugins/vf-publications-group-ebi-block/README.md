# Publications (block)

To display a list of team or group publications from the Content Hub.

This is mostly a clone of `vf-publications-block` and has been done so in case
different logic and features arise over time.

## Configuration

ACF / Block data:

| field key | field name |
| --------- | ---------- |
| field_vf_publications_group_ebi_heading | vf_publications_group_ebi_heading |
| field_vf_publications_group_ebi_limit | vf_publications_group_ebi_limit |
| field_vf_publications_group_ebi_order | vf_publications_group_ebi_order |

Block `name`: `acf/vf-publications-group-ebi`

See plugin JSON file for source of truth.

### Related post

| post_name | post_type |
| --------- | --------- |
| vf_publications_group_ebi | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_publications_group_ebi_heading | "Publications" |
| \_vf_publications_group_ebi_heading | field_vf_publications_group_ebi_heading |

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
