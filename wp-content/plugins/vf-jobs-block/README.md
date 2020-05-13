See [VF-WP Blocks](/docs/blocks.md) general documentation.

# Jobs (block)

A list of one or more available jobs using the `vf-summary--job` Visual Framework pattern.

## Configuration

ACF / Block data:

| field key | field name |
| --------- | ---------- |
| field_vf_jobs_heading | vf_jobs_heading |
| field_vf_jobs_limit | vf_jobs_limit |
| field_vf_jobs_filter | vf_jobs_filter † |
| field_vf_jobs_term | field_vf_jobs_term † |

Block `name`: `acf/vf-jobs`

See plugin JSON file for source of truth.

 † Some fields are added dynamically if the EMBL Taxonomy plugin is active.

### Related post

| post_name | post_type |
| --------- | --------- |
| vf_jobs | vf_block |

Default values can be assigned to this post using post meta and the "field name" listed above.

For example in `wp_postmeta`:

| meta_key | meta_value |
| -------- | ---------- |
| vf_jobs_heading | "Jobs" |
| \_vf_jobs_heading | field_vf_jobs_heading |

### Heading

**Key**: `vf_jobs_heading`
**Value**: string (e.g. "Latest jobs")

An optional heading above the content using the `vf-section-header` VF pattern. If empty no heading is displayed.

### Limit

**Key**: `vf_jobs_limit`
**Value**: integer (range: 1–50, default: 10)

Maximum number of jobs to display (Content Hub API).

### Filter

**Key**: `vf_jobs_filter`
**Value**: string (default: "all")

How to filter the jobs API - values are:

* `all` - no filter (show all jobs)
* `who` - filter by `options_embl_taxonomy_term_who` †
* `what` - filter by `options_embl_taxonomy_term_what` †
* `where` - filter by `options_embl_taxonomy_term_where` †
* `term` - filter by `vf_jobs_term` †

† *EMBL Taxonomy* plugin must be active.

**WIP:** Term names are used as keyword filter on the jobs' description until an `uuid` API is implemented.

### Term

**Key**: `vf_jobs_term`
**Value**: integer (`wp_terms` primary key)

Filter API results by this term. Only used if `vf_jobs_filter` has the value `term`.

## Page Template

The theme includes an optional page template entitled "Jobs" (`template-jobs.php`).

For the `post_id` of the "Jobs" page:

| meta_key | meta_value |
| -------- | ---------- |
| \_wp_page_template | template-jobs.php |

## EMBL Taxonomy Configuration

More options are available when the *EMBL Taxonomy* plugin is active.

| option_name | option_value |
| ----------- | ------------ |
| options_embl_taxonomy_autocomplete | 1 |

Enable autocomplete to provide keyword search suggestions on the "Jobs" page template.

Set the "Who", "What", and "Where" options to allow default filtering by these terms.
