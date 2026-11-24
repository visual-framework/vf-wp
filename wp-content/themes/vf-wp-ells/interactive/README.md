# ELLS interactive activities

Add a **Shortcode** block to a WordPress page while the ELLS theme is active:

```text
[ells_interactive case="epigenetic-clock"]
```

This renders all four supplied exercises in one box with Back, Next and direct
step navigation. Answers survive navigation within the page. Reloading the page
starts a new attempt. Each embed has independent state. Steps may be visited in
any order; navigation does not imply that answers are correct. Reset belongs to
the current exercise. Nothing is submitted or stored on the server.

The original fifth “Quiz” link was not included: only four step files were
provided. Add a reviewed fifth step to the manifest when its content is ready.

## Manage cases in WordPress

Open **Interactive cases → Add interactive case** in the WordPress admin.

1. Enter an activity title, click **Add step**, and give the step a title and an
   HTML file. Repeat for the remaining steps.
2. Use **Move up**, **Move down**, and **Remove step** to arrange the flow.
3. **Save Draft**, then click **Preview saved case**. The preview uses the real
   player and your last saved files; unsaved edits are not included.
4. **Publish** the case, click **Copy shortcode**, then paste it into a Shortcode
   block on a page. The shortcode looks like `[ells_interactive case="uploaded-123"]`.

The case list also displays the shortcode. Replacing files or reordering steps
keeps the same shortcode. Draft cases can only be rendered for administrators;
published cases can appear on public pages. Trashing the case makes its shortcode
unavailable to visitors. Cases shipped with the theme, including **Build an
epigenetic clock**, are imported into **All cases** automatically on an
administrator's first admin visit. They can be edited there like uploaded cases.
The original `[ells_interactive case="epigenetic-clock"]` shortcode continues to
work and follows the saved admin version. Imports do not overwrite edits or
recreate cases you delete. Packaged files remain a fallback until the first import.

Uploads require both `manage_options` and `unfiltered_html`. On multisite this
normally means a network super administrator. HTML lives in WordPress post meta,
not in publicly executable upload files. No general Media Library upload rules
are relaxed. Cases support up to 20 steps, with 1 MB per UTF-8 HTML file. Back up
the WordPress database to preserve uploaded cases.

Files must be self-contained: inline JavaScript and CSS work; external scripts,
network requests, fonts, linked images and relative asset files are blocked in
uploaded cases. Use data URLs for images. This restriction also applies to the
admin preview. PHP files/code are rejected. The known progress bars and resize
scripts in the original EMBL exports are removed automatically. Other HTML
exports may need their own page navigation adapted before upload. Automatic
resizing and accessibility helpers are injected by the player.

## Add a case shipped with the theme (developer workflow)

1. Create `interactive/cases/my-case/` in this theme.
2. Place reviewed, self-contained HTML exercises there, using lowercase filenames
   such as `step-1.html`. Keep relative assets local. Remove old site navigation,
   external font requests and old iframe resizing code.
3. Include `../../assets/step.css` in each HTML head and
   `../../assets/step.js` before its closing body tag. The script reports content
   height to the player, adds accessible names to table controls and announces
   feedback. Give other controls explicit labels and keyboard interactions.
4. Add `case.json`:

```json
{
  "version": 1,
  "title": "My activity",
  "steps": [
    { "title": "First exercise", "file": "step-1.html" },
    { "title": "Second exercise", "file": "step-2.html" }
  ]
}
```

5. Use `[ells_interactive case="my-case"]` on a page. The step count and
   navigation are generated from the manifest; no player code changes are needed.

Deploy the whole theme, including `interactive/`. There is no build step or
additional plugin requirement. Packaged cases are deployed with the theme;
admin-uploaded cases are stored separately in the database. Remote case URLs
are unsupported.
Each exercise runs in a sandbox with scripts enabled, without access to the
WordPress document, cookies, forms, popups or top-level navigation. This protects
the page from CSS, duplicate IDs and global function collisions in legacy files.
Do not add `allow-same-origin` to the sandbox. Player messages are checked against
the sending iframe window. For future custom steps, send
`{type: 'ells:height', height: number}` to the parent when content size changes,
or use the supplied helper. The current height limit is 20,000 pixels per step.

## Verification

Run PHP syntax checks on the new PHP module and theme functions. For an isolated
browser check, run `php -S 127.0.0.1:9482 -t .` from the repository, then:

```sh
NODE_PATH=/path/to/node_modules node tests/ells-interactive/browser.cjs
```

The fixture uses the real shortcode renderer with WordPress helper stubs. The
browser check exercises answers, navigation/state retention, resets, the graph,
mobile sizing and independent copies. Also verify a real WordPress Shortcode
block on staging before release; this fixture does not test the site's plugins
or content filters.

If host PHP is unavailable, render `preview.php` with the existing PHP container
to a temporary HTML file, then pass `--fixture /absolute/path/to/preview.html` to
the browser test. This starts its own local static server on port 9482.

`tests/ells-interactive/wp-check.php` runs with WP-CLI `eval-file` on a local ELLS
installation and checks permissions, nonce handling, draft/trash visibility,
HTML normalization, storage and sandbox rendering. It deletes its temporary case.
`admin-browser.cjs` tests the real admin upload/reorder/preview/publish flow and a
public page. Pass a temporary administrator session JSON file (an admin `url`
and `cookies` array) and optionally a directory containing the original
`Step 1.html` through `Step 4.html` files. This test currently targets the repository's
`emblebivfwp_php` local container; it removes its test case/page and logs out its
temporary session afterward. Do not run integration fixtures against production.
