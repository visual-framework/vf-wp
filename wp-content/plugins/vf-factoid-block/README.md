# Factoid (block)

Boxed content using the `vf-factoid` Visual Framework pattern.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_factoid | vf_block |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_factoid_limit | [INT] |
| \_vf_factoid_limit | field_vf_factoid_limit |
| vf_factoid_id | [INT] |
| \_vf_factoid_id | field_vf_factoid_id |
| vf_factoid_topic | [INT] |
| \_vf_factoid_topic | field_vf_factoid_topic |

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
