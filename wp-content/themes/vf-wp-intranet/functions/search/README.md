# Theme Search System

This directory contains the custom indexed search system for the `vf-wp-intranet` theme. It replaces application-level Relevanssi usage with a theme-owned database index, query service, snippets, highlighting, PDF and DOCX text extraction for Document posts, autocomplete, analytics, admin settings, and batch index management.

The system lives in the active theme. It is not a plugin or MU plugin.

## High-Level Flow

1. `bootstrap.php` loads the search classes and registers hooks.
2. `VFWP_Intranet_Search_Schema` creates custom database tables when needed.
3. `VFWP_Intranet_Search_Indexer` keeps published searchable posts in the custom index.
4. `VFWP_Intranet_Search_Service` queries the custom index for frontend searches.
5. `VFWP_Intranet_Search_Snippet_Service` chooses snippets and highlights terms.
6. `VFWP_Intranet_Search_Frontend` adapts existing search templates, filters, counters, and pagination to the custom service.
7. `VFWP_Intranet_Search_Suggestions` powers autocomplete.
8. `VFWP_Intranet_Search_Index_Manager` manages rebuilds from Settings -> Search and WP-CLI.

Frontend search results should not depend on Relevanssi being installed or enabled.

## Main Files

- `bootstrap.php`
  Loads the subsystem, registers schema setup, frontend integration, suggestions, analytics, index manager, admin settings, admin document indicators, and WP-CLI commands.

- `class-search-schema.php`
  Creates the custom index, analytics, and spelling-dictionary tables. The index table uses normal indexes plus FULLTEXT indexes for title, excerpt, content, ACF keywords, and combined text.

- `class-search-index-repository.php`
  Handles low-level persistence for indexed rows, counts, rebuild token updates, issue clearing, and table truncation.

- `class-search-indexer.php`
  Indexes posts, pages, custom post types, ACF keyword fields, and text from Document PDF or DOCX attachments. Registers post lifecycle hooks so saves, status changes, trashing, restoring, deletion, upload-file changes, and attachment-file changes update or remove index rows.

- `class-search-pdf-extractor.php`
  Dependency-free, pure PHP PDF text extraction. It extracts machine-readable PDF text only. It does not OCR scanned PDFs.

- `class-search-docx-extractor.php`
  Local DOCX extraction using PHP's ZIP and DOM extensions. It reads paragraphs, tables, headers, footers, footnotes, and endnotes with bounded file, decompressed XML, and output sizes.

- `class-search-query-parser.php`
  Normalizes visitor queries, applies synonyms, handles exact phrase rules, stopwords, minimum word length, protected phrases, quoted phrases, and builds safe FULLTEXT boolean query terms.

- `class-search-spelling-repository.php`
  Maintains the precomputed title/ACF-keyword spelling dictionary, indexed deletion keys, source frequencies, and object mappings used by "Did you mean".

- `class-search-service.php`
  Runs searches against the custom index, applies filters, computes relevance, paginates efficiently, and returns structured result data.

- `class-search-snippet-service.php`
  Selects result snippets from indexed fields and returns display-safe highlighted title and snippet output using `<mark>`.

- `class-search-frontend.php`
  Connects the existing frontend search UI to `SearchService`. It preserves the existing query parameters, filter definitions, result counts, pagination, and filter counters.

- `class-search-suggestions.php`
  Provides AJAX autocomplete suggestions from the index plus configured exact phrases. It also powers "Did you mean" suggestions for no-result states.

- `class-search-settings.php`
  Renders Settings -> Search, including weights, query parsing, synonyms, analytics, diagnostics, index management, and document extraction issue reporting.

- `class-search-index-manager.php`
  Runs full and changed-content rebuilds in safe batches, prevents overlapping rebuilds, tracks progress, and handles admin/AJAX rebuild actions.

- `class-search-cli-command.php`
  Adds `wp theme-search index ...` commands for status, rebuilds, and snippet tests.

- `class-search-analytics.php`
  Logs frontend search queries, result counts, zero-result queries, optional user email, and retention cleanup.

- `class-search-document-index-status.php`
  Adds a lightweight admin label for Document posts showing whether the attached PDF or DOCX/search row is indexed, stale, not indexed, or has an extraction issue.

- `scripts/search-suggestions.js`
  Frontend autocomplete behavior: immediate "Search for ..." row, debounced AJAX lookup, request cancellation, stale-response protection, keyboard navigation, badges, external-link indicators, and browser-side cache.

## Database Tables

The schema uses the current WordPress database prefix.

### Search Index

Table: `{prefix}vf_search_index`

Important columns:

- `object_id`
- `object_type`
- `post_type`
- `post_status`
- `visibility`
- `title`
- `excerpt`
- `content`
- `acf_keywords`
- `url`
- `published_at`
- `updated_at`
- `indexed_at`
- `schema_version`
- `content_hash`
- `source_hash`
- `parent_object_id`
- `file_name`
- `extraction_status`
- `extraction_error`
- `rebuild_token`

Important indexes:

- Unique lookup on `object_type, object_id`
- Post/status/visibility indexes
- Rebuild and hash indexes
- FULLTEXT indexes on `title`, `excerpt`, `content`, `acf_keywords`, and combined fields

### Spelling dictionary

Tables:

- `{prefix}vf_search_spelling_terms`
- `{prefix}vf_search_spelling_deletions`
- `{prefix}vf_search_spelling_objects`

The dictionary stores bounded unique words from indexed titles and configured ACF keyword fields. It does not ingest body text, excerpts, or extracted PDF/DOCX text. Each term stores exact and one-character deletion keys, allowing misspellings to be retrieved with indexed equality lookups rather than wildcard content scans. Object mappings keep frequencies accurate when indexed content changes or is removed.

### Analytics

Table: `{prefix}vf_search_analytics`

Stores normalized query text, total results, filter hash/json, page number, result page size, timestamp, optional user email, and source.

## What Gets Indexed

The indexer only includes publicly searchable content:

- Published posts
- Published pages
- Enabled public custom post types from Settings -> Search
- Documents post type when enabled
- Document attachment text from the `upload_file` ACF field when the uploaded file is a PDF or DOCX

It excludes:

- Drafts
- Private posts
- Trash
- Revisions
- Autosaves
- Password-protected content
- Disabled post types
- Teams with the ACF "Exclude from search" field enabled

Enabled post types and post-type weights are configured in Settings -> Search.

## Document File Search

PDF and DOCX search are integrated into the `documents` post type and are not returned as separate attachment results.

For a Document post:

1. The indexer reads the `upload_file` ACF field.
2. If the attachment is a PDF or DOCX, the matching local extractor reads its text during indexing.
3. The extracted text is normalized and stored in the custom search index row's `content` field.
4. The full extracted file text is not stored in an ACF field or duplicated into large postmeta rows.
5. Lightweight metadata is stored on the Document post for diagnostics:
   - `_vfwp_search_pdf_attachment_id`
   - `_vfwp_search_file_type`
   - `_vfwp_search_pdf_file_name`
   - `_vfwp_search_pdf_file_size`
   - `_vfwp_search_pdf_file_mtime`
   - `_vfwp_search_pdf_extraction_status`
   - `_vfwp_search_pdf_extraction_error`
   - `_vfwp_search_pdf_extracted_chars`
   - `_vfwp_search_pdf_indexed_at`

Supported extraction statuses include `success`, `success_truncated`, `missing_file`, `not_readable`, `too_large`, `failed`, `password_protected`, `no_text`, and `timeout`.

Limitations:

- Scanned PDFs are not searchable unless they already contain machine-readable text.
- OCR is not performed.
- Encrypted/password-protected PDFs are not extracted.
- Very large PDFs may be skipped or truncated by configured safety limits.
- DOCX extraction requires the PHP ZIP and DOM extensions and does not support legacy `.doc` files.
- Very large or malformed DOCX packages may be skipped or truncated by configured safety limits.

## Indexing Lifecycle

`VFWP_Intranet_Search_Indexer` registers hooks for:

- `save_post`
- `acf/save_post`
- `transition_post_status`
- `trashed_post`
- `untrashed_post`
- `before_delete_post`
- `added_post_meta`, `updated_post_meta`, `deleted_post_meta` for Document `upload_file`
- Attachment updates for PDF or DOCX files referenced by Documents

The indexer builds a `source_hash` before file extraction. If searchable source data has not changed and the schema version is current, the row is skipped.

The repository also stores `content_hash` to avoid unnecessary updates.

## Rebuilds and Batch Processing

Settings -> Search includes controls for:

- Full rebuild
- Reindex changed content
- Clear/recreate index
- Clear document extraction issue notices

Full rebuilds are batched. They should not process the whole site in one browser request.

Batch safeguards include:

- Batch size defaults
- Maximum batch size
- Batch lock transient
- Time limit checks
- Memory usage checks
- Item-level exception handling
- Progress/status tracking
- Duplicate rebuild prevention

WP-CLI command:

```bash
wp theme-search index status
wp theme-search index rebuild
wp theme-search index rebuild --clear
wp theme-search index test_snippets
```

## Query Parsing

`VFWP_Intranet_Search_Query_Parser` handles:

- HTML/entity decoding
- Tag stripping
- Lowercasing
- Accent normalization where WordPress `remove_accents()` is available
- Apostrophes and hyphens
- Punctuation
- Duplicate terms
- Stopwords
- Minimum word length
- Quoted phrases
- Exact phrase search rules from settings
- Automatic exact matching for structured references containing letters, numbers, and repeated dot/slash separators
- Synonyms from settings
- Boolean FULLTEXT query construction

Settings that affect parsing:

- Minimum word length
- Stopwords
- Exact phrase searches
- Synonyms

Exact phrase entries are treated as protected phrases. For example, if `it services` is configured as an exact phrase, a query containing that phrase keeps it together instead of treating `it` and `services` as independent weak terms.

Reference-style queries such as `Fin.Com./2017/14 Rev.1` are automatically protected. Punctuation is normalized for comparison, but all identifier parts must occur together and in order; a page that merely contains `Fin` and the other parts separately is not a match.

Synonyms are directional replacements. Example:

```text
it members = it services members
```

## Relevance and Ranking

`VFWP_Intranet_Search_Service` calculates relevance in SQL from indexed fields. The ranking model combines:

- Exact query equals title
- Exact phrase in title
- All query terms in title
- Individual title term hits
- Exact ACF keyword entry match
- ACF keyword term hits
- Excerpt phrase hits
- Excerpt term hits
- Content/PDF phrase hits
- Content/PDF term hits
- All-field term coverage
- MySQL/MariaDB FULLTEXT scores
- Post-type weight
- A weak recency boost

Default field weights:

- Title: `10`
- ACF keyword fields: `7`
- Excerpt: `4`
- Main content/extracted document text: `1`

ACF keyword matching is delimiter-aware and exact-entry based. A keyword entry like `services prices` should not satisfy a search for `it services` unless `it services` is also a configured matching keyword or content/title match.

Post-type weights and ranking boosts are configurable in Settings -> Search. The admin UI also includes calculated ranking-priority information to explain how the final priority values are derived.

The Ranking test tab runs a real query against the current index without adding it to Search Analytics. It shows query normalization, FULLTEXT terms, protected phrases, the top ten results, every raw ranking signal, the field weight and boost applied to it, the post-type multiplier, recency bonus, and final database score.

## Snippets and Highlighting

`VFWP_Intranet_Search_Snippet_Service` adds display fields to each result.

It returns:

- Raw title/snippet fields
- Highlighted display title
- Highlighted display snippet
- Source field used for the snippet

Snippet behavior:

- For normal web content, excerpt is preferred before content.
- For Document file content, extracted PDF or DOCX content can be used when it contains the match.
- Snippets are selected around the strongest matching passage rather than simply using the first characters.
- Snippets are bounded to a concise length.
- Fallbacks use excerpt/content/title when no matching passage exists.

Highlighting behavior:

- Uses `<mark>...</mark>`
- Escapes output safely
- Does not modify stored WordPress content
- Handles multiple terms and phrases
- Handles regex characters safely
- Supports Unicode where available
- Limits the number of highlights

## Frontend Integration

The existing search templates call:

```php
vfwp_intranet_search_frontend_current_request()
```

That reaches:

```php
VFWP_Intranet_Search_Frontend::search_current_request()
VFWP_Intranet_Search_Service::search()
```

The normal WordPress main search query is short-circuited via `posts_pre_query` so frontend rendering does not force a separate `wp_posts` search scan.

The frontend preserves:

- Search query in the input
- Existing filter parameter `search_type[]`
- Legacy `post_type` mapping where possible
- Pagination
- Filter counters
- Active filters
- Clear filters behavior
- Existing result markup/styling conventions

Current filter categories:

- Pages
- Teams
- People
- Documents
- Announcements
- News
- Events
- Training

The current content type is fixed to web/document-post results. Extracted PDF and DOCX text is searched through the Documents post type.

## Autocomplete

Autocomplete uses:

- `class-search-suggestions.php`
- `scripts/search-suggestions.js`
- AJAX action `vfwp_intranet_search_suggestions`

Frontend behavior:

- Shows the first `Search for "..."` row immediately.
- Runs indexed lookup after a short debounce.
- Cancels or ignores stale requests.
- Uses a browser-side cache for repeated query/filter combinations.
- Supports keyboard navigation.
- Shows post-type badges for result suggestions.
- Shows external-link domain badges for Team suggestions.

Server behavior:

- Uses the custom search index.
- Uses object cache for short-lived suggestion results.
- Avoids indexed lookup for very short input.
- Suggests matching indexed titles and configured/indexed keyword phrases.

## Did You Mean

The no-results state can show "Did you mean" links from `VFWP_Intranet_Search_Suggestions::did_you_mean()`.

Term candidates come from the precomputed spelling dictionary; phrase candidates also use bounded indexed titles, ACF keywords, and configured exact phrases. Deletion-key lookup finds likely insertions, omissions, and transpositions before edit-distance scoring. Long words also use a bounded indexed-prefix lookup so plausible three-edit corrections can be considered without scanning the dictionary. Title terms rank above keyword-only terms, frequency breaks ties, and suggestions are only added if they lead to actual indexed results under the active filters.

Stopwords and other terms ignored by query parsing are preserved during term-level spelling correction rather than compared with dictionary words. This prevents valid connector words such as `and` from being changed to an indexed content word such as `end`. Did-you-mean labels are displayed in lowercase consistently.

People records also populate a dedicated fuzzy-name dictionary during indexing. It stores the display name, an accent-folded full name, first/last-name tokens, configured People keyword aliases, and precomputed character trigrams. On a no-results request, an indexed trigram lookup retrieves at most 50 candidates; bounded PHP scoring combines trigram Dice similarity, transposition-aware Damerau-Levenshtein similarity, token alignment, and prefix confidence. Up to three high-confidence names are then validated through SearchService under the active filters before being shown as lowercase Did-you-mean links. Successful normal searches do not run this lookup.

If strict AND matching returns no results and no reliable spelling correction exists, the no-results state can optionally offer one broader search. This behavior is disabled by default and can be enabled under Settings > Search > Query parsing. It removes a single query term, preserves active filters, and only displays the link after an indexed existence check confirms that the broader query has results. Normal search matching remains AND-based, and changing the toggle does not require reindexing.

Strict term, phrase, and exact-keyword verification expands normalized Latin query letters to bounded accent-aware regular-expression classes. This keeps `rudiger` and `rüdiger` equivalent against stored display text such as `Rüdiger`, while preserving word boundaries and prefix behavior. The index retains original accents and no rebuild is required for this matching rule.

Schema version 17 introduces the spelling tables. A full rebuild or changed-content reindex is required after deployment to populate the dictionary for all existing indexed content. Normal post saves then maintain it automatically.

## Search Analytics

Analytics are controlled in Settings -> Search.

Logged data can include:

- Query text
- Normalized query
- Result count
- Filter hash/json
- Page/per-page
- Search timestamp
- Optional user email
- Source
- Selected result ID, type, title, and URL for autocomplete selections
- Whether a no-result query led to a clicked spelling correction
- The corrected normalized query

Analytics logs page 1 frontend searches and deliberate indexed-result selections from autocomplete. Autocomplete lookup requests, incomplete keystrokes, and pagination requests are not logged. A selected autocomplete result records the visitor's typed query, the selected result metadata, source `autocomplete`, and a positive result count, so it contributes to total searches and successful-search metrics. The Recent searches table displays an Autocomplete indicator and the selected title. The selected object must still exist as a public, published index row or the event is rejected.

Reports include top queries, zero-result queries, recent searches, summary counts, and daily, weekly, and monthly trends. Daily, weekly, and monthly charts use separate accessible tabs; recent, no-results, and most-searched query tables use a second tab group. The selected tabs are represented in the admin URL so refreshes, sorting, and pagination preserve the active reports. Trend charts compare total search volume with the percentage of searches that produced results. The top-query, zero-result, and Recent searches reports include every retained row and use database pagination at 20 rows per page. Their count and page queries are restricted to the configured retention period in SQL.

Search settings tables are limited to 20 visible rows per page. Large database-backed reports, including document extraction issues, use SQL `LIMIT` and `OFFSET`; bounded configuration and chart-detail tables are paginated in the admin page without discarding form fields. Index action notices are dismissible, and dismissal of the current rebuild-required warning is stored per administrator until a new rebuild requirement is created.

When a visitor clicks a server-rendered "Did you mean" link, a signed analytics event ID marks the originating no-result row as corrected. Corrected rows remain part of total search volume, count as successful journeys in the results percentage, and are excluded from no-result reports. Data retention is configurable.

The "Queries with no results" report is ordered by the most recently searched query by default. Administrators can sort the paginated report by search count or last-searched time in either direction; sorting is performed by the database before pagination.

Each no-results analytics event also records whether "Did you mean" suggestions were rendered and the suggestion text shown at that time. The grouped no-results report displays the most recently shown suggestion for each query; older analytics rows created before this field existed display "No".

## Admin Settings

Settings -> Search is restricted to administrators via `manage_options`.

Main settings areas:

- Field weights
- Post type include/exclude and weights
- ACF keyword field names
- Query parsing
- Exact phrase searches
- Stopwords
- Synonyms
- Ranking boosts
- Index management
- Document extraction issue notices
- Analytics
- Ranking test and per-result score explanations

Settings that change indexed searchable content require a rebuild. Ranking-only settings usually take effect immediately because weights are applied at query time.

## ACF Keyword Fields

Configured ACF field names are read during indexing. Text-compatible values are flattened and normalized into the `acf_keywords` index field.

Arrays, repeaters, and flexible content values are flattened conservatively. Binary data and oversized nested structures should not be indexed.

ACF keywords are stored separately from title, excerpt, and content so they can have their own weight and exact-entry matching behavior.

## Teams

Team results can use the ACF `team_url` field as their indexed URL. If the Team URL is external, frontend results and autocomplete can display a domain pill.

Teams can be excluded from search with the ACF `team_exclude_from_search` field.

## Document Index Indicator

For the `documents` post type, the admin UI displays a simple search-index label:

- Indexed
- Needs reindex
- PDF issue
- Not indexed
- Not searchable

This indicator reads the custom index row and lightweight PDF metadata. It does not extract or parse PDFs on the edit screen.

## Security Notes

The search layer uses prepared SQL for user input. Rendered snippets and highlighted output are escaped before adding `<mark>` tags.

Search indexing excludes private, draft, trashed, password-protected, revision, and autosave content. Administrators should still be careful that publicly searchable posts do not reference sensitive files.

PDF and DOCX extraction happen during indexing, not during visitor search requests. Both extractors run locally and do not send files to an external service.

## Performance Notes

Search requests query the custom index table, not `wp_posts` and not `wp_postmeta` scans.

Performance features:

- Custom search index table
- FULLTEXT indexes
- Query-time filters and pagination
- Accurate counts from indexed rows
- Source/content hashes to skip unchanged indexing
- Batched rebuilds
- No visitor-time PDF or DOCX extraction
- Browser-side autocomplete cache
- Object-cache autocomplete cache

Known limits:

- `VFWP_Intranet_Search_Service::MAX_PER_PAGE` is `50`.
- `VFWP_Intranet_Search_Service::MAX_OFFSET` is `5000`.
- PDF extraction is best effort and bounded by file size, stream size, text size, and time limits.
- DOCX extraction is bounded by archive size, decompressed XML size, and extracted text size.

## Operational Checklist

After deploying search-related changes:

1. Visit Settings -> Search and confirm schema/index status.
2. Run a changed-content reindex, or a full rebuild when settings/schema/content extraction changed.
3. Check document extraction issue notices.
4. Test a normal page query.
5. Test a Document query that should match attached PDF or DOCX text.
6. Test each visible filter.
7. Test pagination.
8. Test autocomplete.
9. Test no-results and Did you mean.
10. Check PHP logs for indexing or search errors.

Useful commands:

```bash
wp theme-search index status
wp theme-search index rebuild
wp theme-search index rebuild --clear
wp theme-search index test_snippets
```

## Relevanssi

The frontend search system should not require Relevanssi at runtime. If Relevanssi is disabled, uninstalled, or deleted, frontend search should continue to use this custom theme search index.

Do not delete old Relevanssi database data blindly. If cleanup is needed, treat it as a separate database maintenance task.
