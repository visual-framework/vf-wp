# Members (block)

A list of group members using the `vf-summary--profile` Visual Framework pattern.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_members | vf_block |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_members_limit | [INT] |
| \_vf_members_limit | field_vf_members_limit |
| vf_members_order | [STRING] |
| \_vf_members_order | field_vf_members_order |

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
