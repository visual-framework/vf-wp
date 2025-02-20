<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Get search values
$limit = get_field('limit');
$variant = get_field('variant');
$type = get_field('type');
$embl_terms = get_field('embl_terms');
$keyword = get_field('keyword');
$ids = get_field('ids');
$tags = get_field('tags');
$display = get_field('display_publication');
$fetch = get_field('news_fetch');

if (empty($display)) {
  $display = 'embl';
}
if (empty($variant)) {
  $variant = 'vf-news-item-default';
}

// Validate values
$limit = intval($limit);
$limit = $limit < 1 || $limit > 20 ? 3 : $limit;
$keyword = trim($keyword ?? '');
$ids = explode(',', $ids ?? '');
$ids = array_map('trim', $ids);
$tags = explode(',', $tags ?? '');
$tags = array_map('trim', $tags);


if ($fetch == 'default' || empty($fetch)) {

if (is_int($embl_terms)) {
  $embl_terms = array($embl_terms);
}

// Setup base API URL
$url = VF_Cache::get_api_url();
$url .= '/pattern.html';
$url = add_query_arg(array(
  'source'                    => 'contenthub',
  'pattern'                   => $variant,
  'filter-content-type'       => 'article',
  'filter-field-value[field_target_display]' => $display,
  'sort-field-value[created]' => 'DESC',
), $url);

// Add limit query var
$url = add_query_arg(array(
  'limit' => $limit
), $url);

// Add EMBL Taxonomy filter query var
if (
  $type === 'taxonomy'
  && is_array($embl_terms)
  && function_exists('embl_taxonomy_get_term')
) {
  $key = EMBL_Taxonomy::META_IDS;
  $term = embl_taxonomy_get_term(intval($embl_terms[0]));
  if ($term && array_key_exists($key, $term->meta)) {
    $id = array_pop($term->meta[$key]);
    $url = add_query_arg(array(
      'filter-field-value[field_embl_taxonomy_terms.entity.uuid]' => $id
    ), $url);
  }
}

// Add keyword filter query var
if (
  $type === 'keyword'
  && ! empty($keyword)
) {
  $url = add_query_arg(array(
    'filter-all-fields' => $keyword
  ), $url);
}

// Add IDs filter query var
if ($type === 'ids') {
  $url = add_query_arg(array(
    'filter-id' => implode(',', $ids)
  ), $url);
}

// Add tags filter query var
if ($type === 'tags') {
  $url = add_query_arg(array(
    'filter-field-contains[field_article_tags.entity.name]' => implode(',', $tags)
  ), $url);
}

// Request HTML from the Content Hub API
$content = VF_Cache::fetch($url);
$hash = VF_Cache::hash(
  esc_url_raw($url)
);

// Escape and show error if nothing found
if (
  vf_cache_empty($content)
  || ( vf_html_empty($content) && $is_preview )
) {
  if ($is_preview) {
?>
<div class="vf-banner vf-banner--alert vf-banner--danger">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('No articles were found.', 'vfwp'); ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
  }
  return;
}

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

echo $content;
}

else if($fetch == 'custom') {
// Show default preview instruction
if (empty(get_field('wprest_api_1')) && empty(get_field('wprest_api_2'))) {
  if ($is_preview) { ?>
<div class="vf-banner vf-banner--alert vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('Please add at least one endpoint.', 'vfwp'); ?>
    </p>
  </div>
</div>
<?php }
  return; }
?>

<div id="vf-news-container"></div>
<?php





$fetchPosts = '<script>
document.addEventListener("DOMContentLoaded", async function() {
  let cachedPosts = null;

  async function fetchAndDisplayLatestProjects() {
    if (cachedPosts) {
      renderPosts(cachedPosts);
      return;
    }

    const endpoints = [
      "' . esc_js(get_field('wprest_api_1')) . '",
      "' . esc_js(get_field('wprest_api_2')) . '"
    ].filter(Boolean);

    const header = "' . esc_js(get_field('section_header_text')) . '";
    const headerURL = "' . esc_js(get_field('section_header_url')) . '";
    console.log("Fetching from:", endpoints);

    try {
      const responses = await Promise.all(endpoints.map(endpoint => fetch(endpoint)));
      const data = await Promise.all(responses.map(response => response.ok ? response.json() : Promise.reject(response.statusText)));

      let mergedPosts = data.flatMap(item => Array.isArray(item) ? item : (Array.isArray(item.posts) ? item.posts : []));
      if (mergedPosts.length === 0) {
        console.warn("No posts found.");
        return;
      }

      cachedPosts = mergedPosts.map(post => ({
        id: post.id,
        title: post.title?.rendered || post.title || "No title",
        date: post.date || new Date().toISOString(),
        excerpt: post.excerpt?.rendered ? post.excerpt.rendered.replace(/<[^>]+>/g, "") : (post.excerpt || ""),
        image: post.featured_image_src,
        url: post.link || post.url || "#"
      })).sort((a, b) => new Date(b.date) - new Date(a.date));

      cachedPosts = cachedPosts.slice(0, 4);
      renderPosts(cachedPosts, header, headerURL);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  function renderPosts(posts, header, headerURL) {
    const container = document.querySelector("#vf-news-container");
    if (!container) {
      console.error("Container with ID #vf-news-container not found.");
      return;
    }

    const htmlContent = `
      <section class="vf-news-container vf-news-container--featured | vf-stack">
        <div class="vf-section-header">
          <h2 class="vf-section-header__heading vf-section-header__heading--is-link" id="section-link">
            <a href="${headerURL}">${header}</a>
            <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
            </svg>
          </h2>
        </div>

        <div class="vf-news-container__content | vf-grid vf-grid__col-4">
          ${posts.map(post => `
            <article class="vf-summary vf-summary--news">
              <span class="vf-summary__date">
                ${new Date(post.date).toLocaleDateString("en-GB", { day: "numeric", month: "long", year: "numeric" })}
              </span>
              <img class="vf-summary__image" src="${post.image}" alt="${post.title}" loading="lazy">
              <h3 class="vf-summary__title" style="margin-bottom: 1rem;">
                <a class="vf-summary__link" href="${post.url}" target="_blank" rel="noopener noreferrer">${post.title}</a>
              </h3>
            </article>
          `).join("")}
        </div>
      </section>
    `;
    container.innerHTML = htmlContent;
  }

  fetchAndDisplayLatestProjects();
});
</script>
';
                          
                          echo $fetchPosts;
                          // Re-add wrappers after content

                        }


else if($fetch == 'contenthub') {
  if ($is_preview) { ?>
<div class="vf-banner vf-banner--alert vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('This is a block placeholder. Please check the preview.', 'vfwp'); ?>
    </p>
  </div>
</div>

<?php }

  $contenthubHTML = get_field('contenthub_data_fetch');
  echo $contenthubHTML; }

// Re-add wrappers after content
?>
