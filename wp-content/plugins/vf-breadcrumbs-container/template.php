<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

if ($is_preview) {
?>
<div class="vf-banner vf-banner--info" style="grid-column: main;">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo esc_html_e('This is a placeholder for the breadcrumbs container.', 'vfwp'); ?>
    </p>
  </div>
</div>
<?php } else { ?>
<nav class="vf-breadcrumbs embl-breadcrumbs-lookup" aria-label="Breadcrumb" data-embl-js-breadcrumbs-lookup>
  <div class="vf-list vf-list--inline | vf-breadcrumbs__list | embl-breadcrumbs-lookup--ghosting"></div>
</nav>
<?php } ?>
