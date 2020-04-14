# Beta (container) – DEPRECATED

**This plugin is deprecated** – notificated are added via the content hub.

Display the Beta notification from the Content Hub.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_beta | vf_container |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_beta_node_id | [INT] |
| \_vf_beta_node_id | field_vf_beta_node_id |

### Node ID

**Key**: `vf_beta_node_id`
**Value**: integer (optional, default: 580)

The Content Hub node ID of the beta notification to request.

## Output

Unlike other containers the Beta notification is not part of the general page structure.

Instead it is rendered using an action hook:

```
vf/plugin/after_render/container/vf_global_header
vf/plugin/after_render/container/vf_ebi_global_header
```

The callback uses the WordPress template function `is_front_page()` to only show the Beta container on the home page. This behaviour can be changed in the plugin `index.php` class.
