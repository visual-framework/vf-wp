# Latest Posts (block)

The latest WordPress blog post in a `vf-summary--article` Visual Framework pattern followed by three more aside in a `vf-box--inlay` pattern.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_latest_posts | vf_block |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_latest_posts_heading_singular | [STRING] |
| \_vf_latest_posts_heading_singular | field_vf_latest_posts_heading_singular |
| vf_latest_posts_heading_plural | [STRING] |
| \_vf_latest_posts_heading_plural | field_vf_latest_posts_heading_plural |

### Heading (singular)

**Key**: `vf_latest_posts_heading_singular`
**Value**: string (e.g. "Latest blog post")

An optional heading above the single post using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Heading (plural)

**Key**: `vf_latest_posts_heading_singular`
**Value**: string (e.g. "Latest posts")

An optional heading inside the box aside above the three additional posts. If empty no heading is displayed.
