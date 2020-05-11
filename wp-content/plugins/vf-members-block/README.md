# Members (block)

A list of group members using the `vf-summary--profile` Visual Framework pattern.

## Configuration

ACF / Block data:

| field key | field name | type |
| --------- | ---------- | ---- |
| field_vf_members_limit | vf_members_limit | [STRING] |
| field_vf_members_order | vf_members_order | [STRING] |
| field_vf_members_variation | vf_members_variation |  |
| field_5ea988878eacf | vf_members_team |  |
| field_5ea98b26aaf6c | vf_members_term |  |
| field_5ea98b56aaf6d | vf_members_keyword |  |
| field_5ea983003e756 | vf_members_leader |  |

Block `name`: `acf/vf-members`

See plugin JSON file for source of truth.

### Related post

| post_name | post_type |
| --------- | --------- |
| vf_members | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_members_limit | 10 |
| \_vf_members_limit | field_vf_members_limit |

### Limit

**Key**: `vf_members_limit`
**Value**: integer (range: 1–50, default: 10)

Maximum number of group members (Content Hub API).

### Order

**Key**: `vf_members_order`
**Value**: string (default: "DESC")

Order of members (Content Hub API).

## Global Configuration

| option_name | option_value |
| ----------- | ------------ |
| options_embl_taxonomy_term_what | [TERM_ID] |

Option provided by the **EMBL Taxonomy** plugin.

Term name (e.g. "Sharpe Group") used to filter API results.

## Page Template

The theme includes an optional page template entitled "Members" (`template-members.php`). This will include a minimal "Group Header" block above the members list to show the group leader.

For the `post_id` of the "Members" page:

| meta_key | meta_value |
| -------- | ---------- |
| \_wp_page_template | template-members.php |
