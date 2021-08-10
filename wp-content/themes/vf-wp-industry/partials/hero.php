<?php

$title = get_the_title($post->post_parent);
$event_type = get_field('vf_event_industry_event_type', $post->post_parent);
$displayed = get_field('vf_event_industry_displayed', $post->post_parent);
$hero_image = get_field('vf_event_industry_hero', $post->post_parent);
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
?>

<section class="vf-hero | vf-u-fullbleed | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      <?php if ($hero_image) {
        ?>--vf-hero--bg-image: url('<?php echo esc_url($hero_image); ?>');
        <?php
      }

      else {
        ?>--vf-hero--bg-image-size: auto 28.5rem;
        <?php
      }

      ?>
    }

  </style>

  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <p class="vf-hero__kicker">
      <?php
        if (!empty ($displayed)) {
          echo esc_html($displayed);
        }
        elseif ($event_type) {
           echo esc_html($event_type->name);
        }
      ?>
    </p>
    <h2 class="vf-hero__heading" style="font-size: 30px;">
      <?php echo $title; ?>
    </h2>
  </div>
</section>

<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <li id="menu-item-63" class="vf-navigation__item"><a href="<?php echo get_home_url() ?>"
        class="vf-navigation__link">Home</a></li>
    <li id="menu-item-409" class="vf-navigation__item"><a
        href="<?php echo get_home_url() . '/private/members-area/'; ?>" class="vf-navigation__link"
        aria-current="page">Members area</a></li>
    <li id="menu-item-472" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/private/workshops/'; ?>"
        class="vf-navigation__link">Workshops</a></li>
    <li id="menu-item-410" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/smes/'; ?>"
        class="vf-navigation__link">SMEs</a></li>
    <li id="menu-item-51" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/our-approach/'; ?>"
        class="vf-navigation__link">Our approach</a></li>
    <li id="menu-item-50" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/contact-us'; ?>"
        class="vf-navigation__link">Contact us</a></li>
  </ul>
</nav>
