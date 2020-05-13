# Summary (block)

Display: 

Custom summary with an image in  `vf-summary--has-image` Visual Framework pattern 
Single blog post in  `vf-summary--news` Visual Framework pattern 
Single event in  `vf-summary--event` Visual Framework pattern 

## Configuration

ACF / Block data:

| meta_key | meta_name | meta_value |
| -------- | --------- | ---------- |
| field_5e99679631cbd | select_type | [STRING] |
| field_5eb950da30d23 | image | [STRING] |
| field_5eb9510330d24 | title | [STRING] |
| field_5eb9513430d25 | text | [STRING] |
| field_5eba8a28aa227 | post | [STRING] |
| field_5eba8a5d18b98 | event | [STRING] |



Block `name`: `acf/vfwp-summary`

Block `id` must be unique per instance in the post content. Use a random ID, e.g. [`uniqid('block_')`](https://www.php.net/manual/en/function.uniqid.php).

### Block (minimal/defaults)

```html
<!-- wp:acf/vfwp-summary {"id":"block_5eba9873a64e8","name":"acf/vfwp-summary"} /-->
```

### Block (configured)

```html
<!-- wp:acf/vfwp-summary {"id":"block_5eba9873a64e8","name":"acf/vfwp-summary","data":{"select_type":"Custom","_select_type":"field_5eba88575b33d","image":22538,"_image":"field_5eb950da30d23","title":"This is a title","_title":"field_5eb9510330d24","text":"Some text here","_text":"field_5eb9513430d25"},"mode":"preview"} /-->
```

Full block `data` and `mode` properties should be added if configured.

