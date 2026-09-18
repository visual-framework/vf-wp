# Search: Non-Technical Guide

This guide explains how the intranet search works for editors, content owners, and administrators. It avoids code details and focuses on what the search does, what you can control, and what to check when something does not appear as expected.

## What Search Does

The intranet search helps people find content across the site, including:

- Pages
- Teams
- People
- Documents
- Announcements
- News
- Events
- Training

Documents can also be found by text inside an attached PDF, if the PDF contains readable text.

## How Search Finds Results

Search does not scan the whole WordPress site every time someone types a query. Instead, the site keeps a separate search index. Think of this index as a prepared catalogue of searchable content.

When content is published or updated, the search index is updated. When someone searches, the site searches this prepared index, which is much faster than checking every page and field live.

## What Appears In Search

Search includes content that is:

- Published
- Publicly searchable
- Not password protected
- Enabled in the search settings

Search does not include:

- Drafts
- Private content
- Trashed content
- Password-protected content
- Disabled content types
- Teams marked as excluded from search

## Search Filters

The filters on the search page help people narrow results by category.

Available categories include:

- Pages
- Teams
- People
- Documents
- Announcements
- News
- Events
- Training

Each filter can show a count. The count tells users how many results are available for that category for the current search.

Filters with no results can be disabled so users do not click into an empty category.

## Documents And PDFs

Documents work slightly differently from normal pages.

For a Document post, the search checks:

- The Document title
- The Document excerpt
- The Document content
- Configured search keywords
- Text extracted from the attached PDF

This means a Document can appear when someone searches for words that are inside the uploaded PDF, even if those words are not in the Document title.

### Important PDF Limits

PDF text search only works when the PDF contains real readable text.

It usually works for:

- PDFs exported from Word, Google Docs, InDesign, or similar tools
- PDFs where text can be selected and copied

It does not work for:

- Scanned image PDFs
- Photos of documents
- Password-protected PDFs
- Corrupt PDFs
- PDFs that are too large for the configured safety limits

The system does not perform OCR. If a PDF is just an image scan, search cannot read the words inside it.

## PDF Index Status Label

On Document edit screens, there is a simple search index label near the uploaded file.

Possible labels include:

- Indexed
- Needs reindex
- PDF issue
- Not indexed
- Not searchable

What they mean:

- Indexed: The Document is in the search index.
- Needs reindex: The Document or its attached file changed and search should be updated.
- PDF issue: The Document may be indexed, but the attached PDF text could not be read.
- Not indexed: The Document is not currently in the search index.
- Not searchable: The Document is not eligible for search, often because it is not published.

## Search Result Snippets

Each search result can show a short text snippet.

The snippet tries to show the most useful part of the content, not just the beginning of the page. If the match is inside a PDF, the snippet can come from the extracted PDF text.

Matched words are highlighted with a light marker.

Search does not change the original page, post, or PDF. Highlighting only appears in the search results.

## Autocomplete Suggestions

When users type in the search box, the site can show suggestions.

Suggestions may include:

- A "Search for..." option
- Matching page or post titles
- Useful phrase suggestions
- Badges showing the content type
- External-link indicators for Team results that go to another website

The suggestion list is designed to appear quickly. It first shows the basic search option immediately, then fills in richer suggestions as they load.

## Did You Mean

If a search has no results, the site may show "Did you mean" suggestions.

These suggestions are based on similar indexed words, titles, keywords, and phrases. They are only shown when the suggested search is expected to return results.

## Search Keywords

Administrators can configure ACF field names that should be treated as search keyword fields.

These are not visible search terms entered by users. They are field names used by editors or content types.

For example, a configured field name might be:

```text
keywords
```

If a page has a keyword field containing:

```text
it services, helpdesk, computer support
```

then those phrases can help the page appear for relevant searches.

Keyword matches can be given a higher priority than normal content, depending on the search settings.

## Exact Phrase Searches

Some short or special phrases can be configured so search treats them as one phrase.

This is useful when individual words would be too broad.

Example:

```text
it services
```

If this is configured as an exact phrase, search can avoid treating `it` and `services` as separate weak words.

## Synonyms

Administrators can add synonym rules.

Example:

```text
it members = it services members
```

This means that when someone searches for `it members`, search can treat it like `it services members`.

Synonyms are useful when people use different wording for the same thing.

## Ranking And Weights

Search results are ordered by relevance.

In general, stronger matches appear higher:

- Exact title matches are very strong.
- Title matches are stronger than body text matches.
- Configured keyword matches can be strong.
- Excerpt matches are stronger than general content matches.
- PDF text is searchable, but usually weighted like main content.

Administrators can adjust weights in Settings -> Search.

Common weights include:

- Title
- ACF keyword fields
- Excerpt
- Main content
- Post type weights

Changing ranking weights usually affects results immediately.

Changing what content is indexed usually requires rebuilding the index.

## Search Analytics

Search analytics can record what people searched for.

Analytics can show:

- Recent searches
- Popular searches
- Searches with no results
- Total searches
- Unique queries

Depending on settings, analytics may also store the logged-in user's email address. This should be used thoughtfully and only when needed.

## Admin Settings

The search settings are under:

```text
Settings -> Search
```

Only administrators should be able to access this page.

From this page, administrators can manage:

- Field weights
- Content types included in search
- Post type weights
- ACF keyword field names
- Minimum word length
- Stopwords
- Exact phrase searches
- Synonyms
- Ranking boosts
- Search analytics
- Index rebuilds
- PDF extraction issue notices

## Rebuilding The Search Index

Sometimes the search index needs to be rebuilt.

You may need to rebuild after:

- Changing which post types are searchable
- Changing configured ACF keyword field names
- Changing PDF extraction behavior
- Importing or migrating lots of content
- Seeing content that should appear but does not
- Seeing stale Document PDF status labels

A full rebuild runs in batches. It should not try to process the entire site in one request.

The Settings -> Search page shows progress and index status.

## What To Check If A Result Is Missing

If expected content does not appear in search, check:

1. Is the content published?
2. Is it private, draft, trashed, or password protected?
3. Is its post type enabled in Settings -> Search?
4. Is the correct filter selected on the search page?
5. Has the search index been rebuilt or updated?
6. For Documents, is the uploaded file a PDF?
7. For PDFs, does the PDF contain selectable text?
8. Does the Document show a PDF issue label?
9. Are the search terms too short or listed as stopwords?
10. Is the expected phrase configured as an exact phrase or keyword if needed?

## What To Check If Results Look Wrong

If results appear but the order feels wrong, check:

- Field weights
- Post type weights
- ACF keyword fields
- Exact phrase settings
- Synonym settings
- Whether the matching text is in title, excerpt, keyword, content, or PDF text

Title and keyword matches are usually meant to rank higher than general body or PDF text matches.

## Relevanssi

This search system is designed to work without Relevanssi.

If Relevanssi is disabled or removed, search should continue to work as long as the custom search index exists and is up to date.

Old Relevanssi database data should not be deleted casually. Treat that as a separate maintenance task.

## Quick Summary

- Search uses a prepared index for speed.
- Published public content can appear in results.
- Documents can be found by text inside attached readable PDFs.
- Scanned PDFs are not searchable unless OCR text already exists.
- Snippets and highlights are created only for search result display.
- Autocomplete uses the same indexed search system.
- Administrators manage search from Settings -> Search.
- Rebuild the index when searchable content settings change or results seem stale.
