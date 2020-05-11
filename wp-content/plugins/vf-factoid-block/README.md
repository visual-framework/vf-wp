# Factoid (block)

Boxed content using the `vf-factoid` Visual Framework pattern.

## Configuration

ACF / Block data:

| field key | field name | type |
| --------- | ---------- | ---- |
| field_vf_factoid_limit | vf_factoid_limit | [INT] |
| field_vf_factoid_id | vf_factoid_id | [STRING] |

Block `name`: `acf/vf-factoid`

See plugin JSON file for source of truth.

## Related post

| post_name | post_type |
| --------- | --------- |
| vf_factoid | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_factoid_limit | 3 |
| \_vf_factoid_limit | field_vf_factoid_limit |

### Limit

**Key**: `vf_factoid_limit`
**Value**: integer (range: 1–5, default: 1)

How many factoids to display (Content Hub API).

### ID

**Key**: `vf_factoid_id`
**Value**: integer

The exact Factoid ID from the Content Hub. Will probably override other fields and only return a single factoid.

## Widget

This plugin integrates itself as a sidebar widget!
