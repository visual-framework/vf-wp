# Theme Search System

This directory contains the custom indexed search system for the `vf-wp-intranet` theme. It replaces application-level Relevanssi usage with a theme-owned database index, query service, snippets, highlighting, PDF text extraction for Document posts, autocomplete, analytics, admin settings, and batch index management.

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
  Creates the custom index table and analytics table. The index table uses normal indexes plus FULLTEXT indexes for title, excerpt, content, ACF keywords, and combined text.

- `class-search-index-repository.php`
  Handles low-level persistence for indexed rows, counts, rebuild token updates, issue clearing, and table truncation.

- `class-search-indexer.php`
  Indexes posts, pages, custom post types, ACF keyword fields, and Document PDF text. Registers post lifecycle hooks so saves, status changes, trashing, restoring, deletion, upload-file changes, and attachment-file changes update or remove index rows.

- `class-search-pdf-extractor.php`
  Dependency-free, pure PHP PDF text extraction. It extracts machine-readable PDF text only. It does not OCR scanned PDFs.

- `class-search-query-parser.php`
  Normalizes visitor queries, applies synonyms, handles exact phrase rules, stopwords, minimum word length, protected phrases, quoted phrases, and builds safe FULLTEXT boolean query terms.

- `class-search-service.php`
  Runs searches against the custom index, applies filters, computes relevance, paginates efficiently, and returns structured result data.

- `class-search-snippet-service.php`
  Selects result snippets from indexed fields and returns display-safe highlighted title and snippet output using `<mark>`.

- `class-search-frontend.php`
  Connects the existing frontend search UI to `SearchService`. It preserves the existing query parameters, filter definitions, result counts, pagination, and filter counters.

- `class-search-suggestions.php`
  Provides AJAX autocomplete suggestions from the index plus configured exact phrases. It also powers "Did you mean" suggestions for no-result states.

- `class-search-settings.php`
  Renders Settings -> Search, including weights, query parsing, synonyms, analytics, diagnostics, index management, and PDF issue reporting.

- `class-search-index-manager.php`
  Runs full and changed-content rebuilds in safe batches, prevents overlapping rebuilds, tracks progress, and handles admin/AJAX rebuild actions.

- `class-search-cli-command.php`
  Adds `wp theme-search index ...` commands for status, rebuilds, and snippet tests.

- `class-search-analytics.php`
  Logs frontend search queries, result counts, zero-result queries, optional user email, and retention cleanup.

- `class-search-document-index-status.php`
  Adds a lightweight admin label for Document posts showing whether the attached PDF/search row is indexed, stale, not indexed, or has a PDF issue.

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

### Analytics

Table: `{prefix}vf_search_analytics`

Stores normalized query text, total results, filter hash/json, page number, result page size, timestamp, optional user email, and source.

## What Gets Indexed

The indexer only includes publicly searchable content:

- Published posts
- Published pages
- Enabled public custom post types from Settings -> Search
- Documents post type when enabled
- Document PDF text from the `upload_file` ACF field when the uploaded file is a PDF

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

## Document PDF Search

PDF search is currently integrated into the `documents` post type, not returned as a separate standalone PDF content type.

For a Document post:

1. The indexer reads the `upload_file` ACF field.
2. If the attachment is a PDF, `class-search-pdf-extractor.php` extracts machine-readable text during indexing.
3. The extracted text is normalized and stored in the custom search index row's `content` field.
4. The full extracted PDF text is not stored in an ACF field or duplicated into large postmeta rows.
5. Lightweight metadata is stored on the Document post for diagnostics:
   - `_vfwp_search_pdf_attachment_id`
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

## Indexing Lifecycle

`VFWP_Intranet_Search_Indexer` registers hooks for:

- `save_post`
- `acf/save_post`
- `transition_post_status`
- `trashed_post`
- `untrashed_post`
- `before_delete_post`
- `added_post_meta`, `updated_post_meta`, `deleted_post_meta` for Document `upload_file`
- Attachment updates for PDFs referenced by Documents

The indexer builds a `source_hash` before expensive PDF extraction. If searchable source data has not changed and the schema version is current, the row is skipped.

The repository also stores `content_hash` to avoid unnecessary updates.

## Rebuilds and Batch Processing

Settings -> Search includes controls for:

- Full rebuild
- Reindex changed content
- Clear/recreate index
- Clear PDF extraction issue notices

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
- Synonyms from settings
- Boolean FULLTEXT query construction

Settings that affect parsing:

- Minimum word length
- Stopwords
- Exact phrase searches
- Synonyms

Exact phrase entries are treated as protected phrases. For example, if `it services` is configured as an exact phrase, a query containing that phrase keeps it together instead of treating `it` and `services` as independent weak terms.

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
- Main content/PDF text: `1`

ACF keyword matching is delimiter-aware and exact-entry based. A keyword entry like `services prices` should not satisfy a search for `it services` unless `it services` is also a configured matching keyword or content/title match.

Post-type weights and ranking boosts are configurable in Settings -> Search. The admin UI also includes calculated ranking-priority information to explain how the final priority values are derived.

## Snippets and Highlighting

`VFWP_Intranet_Search_Snippet_Service` adds display fields to each result.

It returns:

- Raw title/snippet fields
- Highlighted display title
- Highlighted display snippet
- Source field used for the snippet

Snippet behavior:

- For normal web content, excerpt is preferred before content.
- For PDF-backed Document content, extracted PDF content can be used when it contains the match.
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

The current content type is fixed to web/document-post results. Document PDF text is searched through the Documents post type.

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

Candidates are derived from bounded indexed titles, ACF keywords, configured exact phrases, and term/phrase edit-distance checks. Suggestions are only added if they lead to actual indexed results.

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

Analytics intentionally logs only page 1 frontend searches. It includes reports for top queries, zero-result queries, recent searches, and summary counts. Data retention is configurable.

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
- PDF extraction issue notices
- Analytics
- Diagnostics/ranking explanations

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

PDF extraction happens during indexing, not during visitor search requests. The current extractor is local pure PHP and does not send PDF files to an external service.

## Performance Notes

Search requests query the custom index table, not `wp_posts` and not `wp_postmeta` scans.

Performance features:

- Custom search index table
- FULLTEXT indexes
- Query-time filters and pagination
- Accurate counts from indexed rows
- Source/content hashes to skip unchanged indexing
- Batched rebuilds
- No visitor-time PDF extraction
- Browser-side autocomplete cache
- Object-cache autocomplete cache

Known limits:

- `VFWP_Intranet_Search_Service::MAX_PER_PAGE` is `50`.
- `VFWP_Intranet_Search_Service::MAX_OFFSET` is `5000`.
- PDF extraction is best effort and bounded by file size, stream size, text size, and time limits.

## Operational Checklist

After deploying search-related changes:

1. Visit Settings -> Search and confirm schema/index status.
2. Run a changed-content reindex, or a full rebuild when settings/schema/content extraction changed.
3. Check PDF extraction issue notices.
4. Test a normal page query.
5. Test a Document query that should match PDF text.
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
