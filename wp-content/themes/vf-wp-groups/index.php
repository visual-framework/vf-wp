<?php

if (class_exists('VF_Global_Header')) {
    VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
  }
if (class_exists('VF_EBI_Global_Header')) {
    VF_Plugin::render(VF_EBI_Global_Header::get_plugin('vf_ebi_global_header'));
  }
if (class_exists('VF_Breadcrumbs')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
  }
get_header();

global $vf_theme;
$page_for_posts_id = get_option( 'page_for_posts' );
$title = $vf_theme->get_title();
$slug = get_page_by_path( 'blog' ); 
$custom_template = get_field('vf_groups_custom_blog_template', $page_for_posts_id);
$endpoint1 = get_field('wp_rest_api_1', $page_for_posts_id);
$endpoint2 = get_field('wp_rest_api_2', $page_for_posts_id);
$endpoint3 = get_field('wp_rest_api_3', $page_for_posts_id);

if ($custom_template) {
  if (class_exists('VF_WP_Hero_Blog')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_hero_blog'));
  }
  if (class_exists('VF_Navigation')) {
    VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
  } }
else {
  if (class_exists('VF_WP_Groups_Header')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_groups_header'));
  }
  
}
if ($custom_template) {echo '<br>';}
?>

<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $page_for_posts_id ) ); ?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800">
    <div class="vf-grid__col--span-2">
      <?php
       if (!$custom_template) { ?>
      <h1 class="vf-text vf-text-heading--1">
        <?php echo esc_html($title); ?>
      </h1>
      <?php } ?>
      <div id="vf-blog-container">

      <?php

      if (empty($endpoint1) && empty($endpoint2) && empty($endpoint3)) {
      while (have_posts()) {
        the_post();
        get_template_part('partials/vf-summary--article');
        if ( ! $vf_theme->is_last_post()) {
          echo '<hr class="vf-divider">';
        }
      }  }
      else {
        $fetchPosts = '<script>
        document.addEventListener("DOMContentLoaded", async function() {
          const postsPerPage = 10; 
          let currentPage = 1; 
      
          const container = document.querySelector("#vf-blog-container");
          const loadingSpinner = document.createElement("div");
          loadingSpinner.classList.add("loader");
      
          window.changePage = function(page) {
            currentPage = page;
            fetchAndDisplayLatestProjects(currentPage);
          }
      
          async function fetchAndDisplayLatestProjects(page) {
            loadingSpinner.style.display = "inline-block";
            container.innerHTML = ""; 
            container.appendChild(loadingSpinner);
      
            const endpoints = [
              "' . esc_js(get_field('wp_rest_api_1',  $page_for_posts_id)) . '",
              "' . esc_js(get_field('wp_rest_api_2',  $page_for_posts_id)) . '",
              "' . esc_js(get_field('wp_rest_api_3',  $page_for_posts_id)) . '"
            ].filter(Boolean);
      
            let mergedPosts = [];
      
            for (const endpoint of endpoints) {
              try {
                const response = await fetch(endpoint);
                if (!response.ok) throw new Error("Network error: " + response.statusText);
                const data = await response.json();
                if (Array.isArray(data)) {
                  mergedPosts = mergedPosts.concat(data);
                } else if (Array.isArray(data.posts)) {
                  mergedPosts = mergedPosts.concat(data.posts);
                }
              } catch (error) {
                console.error("Fetch error:", error);
              }
            }
      
            if (mergedPosts.length === 0) {
              loadingSpinner.style.display = "none";
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
      
            const paginatedPosts = normalizedPosts.slice((page - 1) * postsPerPage, page * postsPerPage);
            const totalPages = Math.ceil(normalizedPosts.length / postsPerPage);
      
            container.innerHTML = `
              ${paginatedPosts.map(post => `
                <article class="vf-summary vf-summary--news">
                  ${post.image ? `
                    <span class="vf-summary__meta | vf-u-margin__bottom--200">
                      <time class="vf-summary__date" datetime="${new Date(post.date).toISOString()}">
                        ${new Date(post.date).toLocaleDateString("en-GB", { day: "numeric", month: "long", year: "numeric" })}
                      </time>
                    </span>
                    <img class="vf-summary__image" src="${post.image}" alt="${post.title}" style="height: auto;" loading="lazy">
                  ` : `
                    <span class="vf-summary__meta | vf-u-margin__bottom--200" style="grid-column: 2/-1;">
                      <time class="vf-summary__date" datetime="${new Date(post.date).toISOString()}">
                        ${new Date(post.date).toLocaleDateString("en-GB", { day: "numeric", month: "long", year: "numeric" })}
                      </time>
                    </span>
                  `}
                  <h2 class="vf-summary__title">
                    <a href="${post.url}" class="vf-summary__link" target="_blank" rel="noopener noreferrer">${post.title}</a>
                  </h2>
                  <p class="vf-summary__text">${post.excerpt}</p>
                </article>
              `).join("")}
      
              ${generatePagination(totalPages, page)}
            `;
      
            loadingSpinner.style.display = "none";
          }
      
          function generatePagination(totalPages, currentPage) {
            let paginationHTML = `<nav class="vf-pagination" aria-label="Pagination"><ul class="vf-pagination__list">`;
      
            // Previous Button
            if (currentPage > 1) {
              paginationHTML += `
                <li class="vf-pagination__item vf-pagination__item--previous-page">
                  <a href="javascript:void(0);" class="vf-pagination__link" onclick="changePage(${currentPage - 1})">
                    Previous<span class="vf-u-sr-only"> page</span>
                  </a>
                </li>`;
            }
      
            const pagesToShow = [];
            pagesToShow.push(1, 2); // Always show first two pages
      
            // Add pages around current page
            for (let i = currentPage - 1; i <= currentPage + 1; i++) {
              if (i > 2 && i < totalPages - 1) pagesToShow.push(i);
            }
      
            pagesToShow.push(totalPages - 1, totalPages); // Always show last two pages
      
            const uniquePages = [...new Set(pagesToShow)].filter(page => page >= 1 && page <= totalPages).sort((a, b) => a - b);
      
            uniquePages.forEach((page, index) => {
              if (index > 0 && uniquePages[index] - uniquePages[index - 1] > 1) {
                paginationHTML += `<li class="vf-pagination__item"><span class="vf-pagination__label">...</span></li>`;
              }
      
              if (page === currentPage) {
                paginationHTML += `
                  <li class="vf-pagination__item vf-pagination__item--is-active">
                    <span class="vf-pagination__label" aria-current="page">
                      <span class="vf-u-sr-only">Page </span>${page}
                    </span>
                  </li>`;
              } else {
                paginationHTML += `
                  <li class="vf-pagination__item">
                    <a href="javascript:void(0);" class="vf-pagination__link" onclick="changePage(${page})">
                      ${page}<span class="vf-u-sr-only"> page</span>
                    </a>
                  </li>`;
              }
            });
      
            // Next Button
            if (currentPage < totalPages) {
              paginationHTML += `
                <li class="vf-pagination__item vf-pagination__item--next-page">
                  <a href="javascript:void(0);" class="vf-pagination__link" onclick="changePage(${currentPage + 1})">
                    Next<span class="vf-u-sr-only"> page</span>
                  </a>
                </li>`;
            }
      
            paginationHTML += `</ul></nav>`;
            return paginationHTML;
          }
      
          fetchAndDisplayLatestProjects(currentPage);
        });
      </script>';
      
      echo $fetchPosts;
      

      }?>
      </div>
      <?php
      vf_pagination();
      ?>
    </div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <div>
      <?php vf_sidebar('sidebar-blog'); ?>
    </div>
    <?php } ?>
</div>
<?php
// Global Footer
if (class_exists('VF_Global_Footer')) {
    VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
  }
if (class_exists('VF_EBI_Global_Footer')) {
    VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
  }
  
get_footer();
  ?>