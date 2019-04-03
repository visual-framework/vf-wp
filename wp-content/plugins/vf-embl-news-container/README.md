# EMBL News (container)

Display related news from the Cotent Hub. Includes an aside with a Factoid block.

## Configuration

Related post:

| post_name | post_type |
| --------- | --------- |
| vf_embl_news | vf_container |

Post meta:

| meta_key | meta_value |
| -------- | ---------- |
| vf_embl_news_limit | [INT] |
| \_vf_embl_news_limit | field_vf_embl_news_limit |
| vf_embl_news_keyword | [STRING] |
| \_vf_embl_news_keyword | field_vf_embl_news_keyword |
| vf_embl_news_term | [TERM_ID] |
| \_vf_embl_news_term | field_vf_embl_news_term |

### Limit

**Key**: `vf_embl_news_limit`
**Value**: integer (range: 1–10, default: 3)

Maximum number of news articles to display (Content Hub API).

### Keyword

**Key**: `vf_embl_news_keyword`
**Value**: string (default: '')

Filter the article teaser field by this keyword (Content Hub API).

### Term †

**Key**: `vf_embl_news_term`
**Value**: integer (`wp_terms` primary key)

Filter API results by this term. *EMBL Taxonomy* plugin must be active.

† **WIP:** The term name is used as a keyword filter until an `uuid` API is implemented.

## Factoid clone

The EMBL News container includes a *clone field* for the **Factoid block**.

Configuration for the Factoid should be assigned against this container post like so:

| meta_key | meta_value |
| -------- | ---------- |
| vf_embl_news_factoid |  |
| \_vf_embl_news_factoid | field_vf_embl_news_factoid |
| vf_factoid_limit | 1 |
| \_vf_factoid_limit | field_vf_factoid_limit |

See the Factoid plugin for all properties.
