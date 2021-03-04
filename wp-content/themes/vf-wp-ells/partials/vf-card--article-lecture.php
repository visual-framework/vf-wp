<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$start_date = get_field('il_start_date');
$registration_link = get_field('il_registration_link');
$application_deadline = get_field('il_application_deadline');
$summary = get_field('il_summary');

?>

<section
  class="vf-content | vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--800 vf-u-padding__top--800 vf-u-margin__bottom--600">
  <div class="vf-grid vf-grid__col-4">
    <div class="vf-grid__col--span-2 | vf-content">
      <h3> Upcoming lecture </h3>
      <p><?php echo ($summary); ?></p>
      <hr class="vf-divider">
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Registration
          deadline</span> <span class="vf-u-text-color--grey"><br><?php echo esc_html($application_deadline); ?></span>
      </p>
      <a href="<?php echo esc_url($registration_link); ?>"
        class="vf-button vf-button--primary vf-button--sm">Register</a>
    </div>
    <div class="vf-grid__col--span-2">
      <article class="vf-card vf-card--brand vf-card--bordered">
        <?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image' ) ); ?>
        <div class="vf-card__content | vf-stack vf-stack--400">
          <h3 class="vf-card__heading">
            <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?></a>
          </h3>
          <p class="vf-card__subheading">
            <?php echo get_the_excerpt(); ?></p>
          <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php echo ($start_date); ?></time>
        </div>
      </article>
    </div>
  </div>
</section>
