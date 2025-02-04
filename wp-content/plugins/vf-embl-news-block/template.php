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
$section_header = get_field('section_header'); // Fetch the section header from ACF
// Extract the values from the link array (url, title, target)
if ($section_header) {
  $section_header_url = $section_header['url'];
} else {
  $section_header_title = 'Latest news'; // Fallback title if no value is found
}

add_filter(
  'vf/theme/content/is_block_wrapped/name=acf/vf-embl-news-block',
  '__return_false'
);


$fetchPosts = '<script>
document.addEventListener("DOMContentLoaded", async function() {
  async function fetchAndDisplayLatestProjects() {
    const endpoint1 = "' . esc_js(get_field('wprest_api_1')) . '";
    const endpoint2 = "' . esc_js(get_field('wprest_api_2')) . '";
    
    console.log("Fetching from:", endpoint1, endpoint2); // Debugging
    
    const endpoints = [endpoint1, endpoint2].filter(Boolean); // Filter out empty endpoints
    let mergedPosts = [];
    
        for (const endpoint of endpoints) {
            try {
              const response = await fetch(endpoint);
              if (!response.ok) throw new Error("Network response was not ok: " + response.statusText);
              const data = await response.json();
              console.log("Data from", endpoint, data); // Debugging
              
              if (Array.isArray(data)) {
                mergedPosts = mergedPosts.concat(data);
                } else if (Array.isArray(data.posts)) {
                  mergedPosts = mergedPosts.concat(data.posts);
                  }
                  } catch (error) {
                    console.error("Error fetching data from", endpoint, error);
                    }
                    }
                    
                    if (mergedPosts.length === 0) {
                      console.warn("No posts found.");
                      return;
                      }
                      
                      const normalizedPosts = mergedPosts.map(post => ({
                        id: post.id,
                        title: post.title?.rendered || post.title || "No title",
                        date: post.date || new Date().toISOString(),
                        excerpt: post.excerpt?.rendered ? post.excerpt.rendered.replace(/<[^>]+>/g, "") : (post.excerpt || ""),
                        image: post.featured_image_src,
                        url: post.link || post.url || "#"
                        }));
                        
                        normalizedPosts.sort((a, b) => new Date(b.date) - new Date(a.date));
                        const latestPosts = normalizedPosts.slice(0, 4);
                        
                        const container = document.querySelector("#vf-news-container");
                        if (!container) {
                          console.error("Container with ID #vf-news-container not found.");
                          return;
                          }
                          
                          container.innerHTML = `
                          <section class="vf-news-container vf-news-container--featured | vf-stack">
                          <div class="vf-section-header">
                          <h2 class="vf-section-header__heading">Latest news</h2>
                          </div>
                          <div class="vf-news-container__content | vf-grid vf-grid__col-4">
                          ${latestPosts.map(post => `
                          <article class="vf-summary vf-summary--news">
                          <span class="vf-summary__date">${new Date(post.date).toLocaleDateString("en-GB", { day: "numeric", month: "long", year: "numeric" })}</span>
                          <img class="vf-summary__image" src="${post.image}" alt="${post.title}" loading="lazy">
                          <h3 class="vf-summary__title" style="margin-bottom: 1rem;"><a class="vf-summary__link" href="${post.url}" target="_blank" rel="noopener noreferrer">${post.title}</a></h3>
                          </article>
                          `).join("")}
                          </div>
                          </section>
                          `;
                          }
                          
                          fetchAndDisplayLatestProjects();
                          });
                          
                          </script>';
                          
                          echo $fetchPosts;

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


?>
