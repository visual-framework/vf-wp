
<?php

$image = get_field('image');
$title = get_field('title');
$text = get_field('text', false, false);
$page_cont = wpautop($text);
$vf_class = str_replace('<p>', '<p class="vf-summary__text">', $page_cont);

?>


<article class="vf-summary vf-summary--has-image">
    <img class="vf-summary__image vf-summary__image--thumbnail" src="<?php echo get_field('image'); ?>" alt="" style="width: 175px;">
    <h3 class="vf-summary__title">
    <?php echo esc_html($title); ?>
    </a>
    </h3>
    <?php echo ($vf_class)?>    
</article>
