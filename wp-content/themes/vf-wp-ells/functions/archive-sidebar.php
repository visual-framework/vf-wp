<?php
/** Editor-managed content for the teacher training archive's third column. */
defined('ABSPATH') || exit;

add_filter('vf/widgets/sidebars', function ($sidebars) {
    $sidebars[] = array(
        'id' => 'ells-teacher-training-sidebar',
        'name' => __('Teacher training archive — right column', 'vf-wp-ells'),
        'description' => __('Content beside the introduction on the Teacher training archive only. Add an Image, Paragraph, or other block here. Leave empty to show no content in this column.', 'vf-wp-ells'),
        'before_widget' => '<div id="%1$s" class="ells-training-sidebar__widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="vf-text vf-text-heading--3">',
        'after_title' => '</h3>',
    );
    return $sidebars;
}, 20);
