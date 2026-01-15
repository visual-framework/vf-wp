<?php
$image_url = get_field('image_url');
$image = get_field('image');
if (is_array($image) && isset($image['ID'])) {
    $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
        'class'    => 'vf-profile__image',
        'loading'  => 'lazy',
        'itemprop' => 'image',
    ));
}

$name = get_field('full_name');
$link = get_field('profile_link'); // could be string or array
$email = get_field('profile_email');
$phone = get_field('profile_phone');
$layout = get_field('layout');
$size = get_field('size');
$text_editor = get_field('text_editor', false, false);
$text_input = get_field('text_input');

// Safe default for layout
if (empty($layout)) {
    $layout = 'inline';
}

// Safe extraction of link
$link_url = '';
$link_target = '_self';

if (!empty($link) && is_array($link)) {
    $link_url = $link['url'] ?? '';
    $link_target = $link['target'] ?? '_self';
} elseif (!empty($link) && is_string($link)) {
    $link_url = $link;
    $link_target = '_self';
}
?>

<article class="vf-profile vf-profile--very-easy vf-profile--<?php echo esc_attr($size); ?> vf-profile--<?php echo esc_attr($layout); ?> | vf-u-margin__bottom--400">

    <?php 
    if (!empty($image)) {
        echo $image; 
    } elseif (!empty($image_url)) { ?>
        <img class="vf-profile__image" src="<?php echo esc_url($image_url); ?>" >  
    <?php } else { ?>
        <img class="vf-profile__image" src="https://dev.assets.emblstatic.net/vf/v2.0.0-alpha.6/assets/vf-summary/assets/vf-icon--avatar.svg" > 
    <?php } ?>

    <h3 class="vf-profile__title" <?php if ($layout == 'block') echo 'style="text-align: center;"'; ?>>
        <?php if (!empty($link_url)) : ?>
            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="vf-profile__link"><?php echo esc_html($name); ?></a>
        <?php else : ?>
            <?php echo esc_html($name); ?>
        <?php endif; ?>
    </h3>
    
    <?php 
    if (have_rows('text')): 
        while(have_rows('text')): the_row(); 
            $profile_text = get_sub_field('profile_text', false, false); ?>
            <p class="vf-profile__text" <?php if ($layout == 'block') echo 'style="text-align: center;"'; ?>>
                <?php echo esc_html($profile_text); ?>
            </p>
    <?php endwhile; endif; ?>

    <?php if (!empty($text_editor)) : ?>
        <div class="vf-content"><?php echo $text_editor; ?></div>
    <?php endif; ?>

    <?php if (!empty($email)) : ?>
        <p class="vf-profile__email | vf-u-last-item ">
            <a href="mailto:<?php echo esc_attr($email); ?>" class="vf-profile__link vf-profile__link--secondary"><?php echo esc_html($email); ?></a>
        </p>
    <?php endif; ?>  

    <?php if (!empty($phone)) : ?>
        <p class="vf-profile__phone">
            <a href="tel:<?php echo esc_attr($phone); ?>" class="vf-profile__link vf-profile__link--secondary"><?php echo esc_html($phone); ?></a>
        </p>
    <?php endif; ?>

</article>
