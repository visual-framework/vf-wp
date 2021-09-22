<?php
$image = get_field('image');
$image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-profile__image',
    'loading'  => 'lazy',
    'itemprop' => 'image',
));

$name = get_field('full_name');
$link = get_field('profile_link');
$email = get_field('profile_email');
$phone = get_field('profile_phone');
$layout = get_field('layout');
$size = get_field('size');

if (empty($layout)) {
    $layout == 'inline';
}


?>
<?php if ($layout == 'block') { ?>
    <article class="vf-profile vf-profile--very-easy vf-profile--<?php echo $size; ?> vf-profile--block | vf-u-margin__bottom--400">
<?php } ?>

<?php if ($layout == 'inline') { ?>
    <article class="vf-profile vf-profile--very-easy vf-profile--<?php echo $size; ?> vf-profile--inline | vf-u-margin__bottom--400">
<?php } ?>

    <?php if (! empty($image)) {
        echo $image; 
    }
    else { ?>
        <img class="vf-profile__image" src="https://dev.assets.emblstatic.net/vf/v2.0.0-alpha.6/assets/vf-summary/assets/vf-icon--avatar.svg" > 

    <?php }?>

    <?php if (! empty($link)) { ?>
    <h3 class="vf-profile__title"
    <?php if ($layout == 'block') { ?>
       style="text-align: center;"
    <?php } ?>>
        <a href="<?php echo esc_url($link); ?>" class="vf-profile__link"><?php echo $name; ?></a>
    </h3>
    <?php }

    else { ?>
    <h3 class="vf-profile__title"
    <?php if ($layout == 'block') { ?>
       style="text-align: center;"
    <?php } ?>>
        <?php echo $name; ?>
    </h3>
    <?php } ?>
    
    <?php 
    if (have_rows('text')): 
     while( have_rows('text') ): the_row(); 
      $profile_text = get_sub_field('profile_text', false, false); ?>
      <p class="vf-profile__text"
      <?php if ($layout == 'block') { ?>
       style="text-align: center;"
       <?php } ?>
       >
       <?php echo $profile_text; ?></p>
    <?php endwhile; endif; ?>
    
    <?php if (! empty($email)) { ?>
    <p class="vf-profile__email | vf-u-last-item ">
        <a href="mailto:<?php echo $email; ?>" class="vf-profile__link vf-profile__link--secondary"><?php echo $email; ?></a>
    </p>
    <?php } ?>  

    <?php if (! empty($phone)) { ?>
    <p class="vf-profile__phone">
        <a href="tel:<?php echo $phone; ?>" class="vf-profile__link vf-profile__link--secondary"><?php echo $phone; ?></a>
    </p>
    <?php } ?>

</article>
