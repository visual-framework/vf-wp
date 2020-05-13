See [VF-WP Blocks](/docs/blocks.md) general documentation.

# Latest Posts (block)

Display the latest WordPress blog post in a `vf-summary--article` Visual Framework pattern followed by three more aside in a `vf-box--inlay` pattern.

## Configuration

ACF / Block data:

| field key | field name |
| --------- | ---------- |
| field_5e99679631cbd | heading_singular |
| field_5e9967a331cbe | heading_plural |

Block `name`: `acf/vfwp-latest-posts`

### Heading (singular)

Template default: "Latest blog post"

An optional heading above the single post using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Heading (plural)

Template default: "Latest posts"

An optional heading inside the box aside above the three additional posts. If empty no heading is displayed.
