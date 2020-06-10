<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Block is container style
$is_container = (bool) get_field('is_container');

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};

if (
  ! have_rows('tabs')
) {
  $admin_banner(__('Please add a tab', 'vfwp'));
  return;
} ?>

<?php
// Open wrappers for container
if ($is_container) { ?>
<div class="vf-grid">
  <div>
    <?php } ?>

    <?php if (have_rows('tabs')): ?>
    <div class="vf-tabs">
      <ul class="vf-tabs__list" data-vf-js-tabs>
        <?php while( have_rows('tabs') ): the_row();
        $title = get_sub_field('title'); ?>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--<?php echo get_row_index(); ?>"><?php echo ($title); ?></a>
        </li>
        <?php endwhile; ?>
      </ul>
    </div>
    <?php endif; ?>

    <?php if (have_rows('tabs')): ?>
    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <?php while( have_rows('tabs') ): the_row(); 
      $title = get_sub_field('title');
      $text = get_sub_field('text', false, false); ?>
      <section class="vf-tabs__section" id="vf-tabs__section--<?php echo get_row_index();?>">
        <h2><?php echo ($title); ?></h2>
        <?php if (! empty ($text)) { ?>
          <p><?php echo ($text); ?></p>
        <?php } ?>

        <!-- Nested tabs-->
        <?php if (have_rows('nested_tabs')): ?>
        <div class="vf-tabs">
          <ul class="vf-tabs__list" data-vf-js-tabs>
            <?php while( have_rows('nested_tabs') ): the_row();
            $nested_title = get_sub_field('nested_title'); ?>
            <li class="vf-tabs__item">
              <a class="vf-tabs__link"
                href="#vf-tabs__section-nested--<?php echo get_row_index(); ?>"><?php echo ($nested_title); ?></a>
            </li>
            <?php endwhile; ?>
          </ul>
        </div>
        <?php endif; ?>

        <?php if (have_rows('nested_tabs')): ?>
        <div class="vf-tabs-content" data-vf-js-tabs-content>
          <?php while( have_rows('nested_tabs') ): the_row(); 
          $nested_title = get_sub_field('nested_title');
          $nested_text = get_sub_field('nested_text', false, false); ?>
          <section class="vf-tabs__section" id="vf-tabs__section--<?php echo get_row_index();?>">
            <h2><?php echo ($nested_title); ?></h2>
            <p><?php echo ($nested_text); ?></p>
          </section>
          <?php endwhile; ?>
        </div>
        <!-- End nested tabs-->
        <?php endif; ?>
      </section>
      <?php endwhile; ?>
    </div>
    <!--/vf-tabs-content-->
    <?php endif; ?>
  </div>
  <!--/vf-grid-->
  <?php
// Close wrappers for container
if ($is_container) { ?>
</div>
</div>
<?php } ?>