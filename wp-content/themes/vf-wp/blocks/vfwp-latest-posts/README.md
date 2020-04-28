# Latest Posts (block)

Display the latest WordPress blog post in a `vf-summary--article` Visual Framework pattern followed by three more aside in a `vf-box--inlay` pattern.

## Configuration

ACF / Block data:

| meta_key | meta_name | meta_value |
| -------- | --------- | ---------- |
| field_5e99679631cbd | heading_singular | [STRING] |
| field_5e9967a331cbe | heading_plural | [STRING] |

Block `name`: `acf/vfwp-latest-posts`

Block `id` must be unique per instance in the post content. Use a random ID, e.g. [`uniqid('block_')`](https://www.php.net/manual/en/function.uniqid.php).

### Block (minimal/defaults)

```html
<!-- wp:acf/vfwp-latest-posts {"id":"block_5ea826258cfbb","name":"acf/vfwp-latest-posts"} /-->
```

### Block (configured)

```html
<!-- wp:acf/vfwp-latest-posts {"id":"block_5ea826da451bc","name":"acf/vfwp-latest-posts","data":{"field_5e99679631cbd":"Latest","field_5e9967a331cbe":"More"},"mode":"preview"} /-->
```

Full block `data` and `mode` properties should be added if configured.

### Heading (singular)

Template default: "Latest blog post"

An optional heading above the single post using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Heading (plural)

Template default: "Latest posts"

An optional heading inside the box aside above the three additional posts. If empty no heading is displayed.
