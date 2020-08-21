<?php

get_header();

?>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <h2>
      <?php echo esc_html(VF_Events::get_archive_title()); ?>
    </h2>
    <div>
    <?php
    while (have_posts()) {
      the_post();
      $post_id = get_the_ID();
      $start_date = get_field(
        'vf_event_start_date',
        $post_id
      );
      $location = get_field(
        'vf_event_location',
        $post_id
      );
      $event_type = get_field('vf_event_event_type'); ?>

      <article class="vf-summary vf-summary--event">
        <?php if ( ! empty($start_date)) { ?>
        <p class="vf-summary__date">
          <?php echo esc_html($start_date); ?>
        </p>
        <?php } ?>
        <h3 class="vf-summary__title">
          <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
            <?php the_title(); ?>
          </a>
        </h3>
        <?php if ( ! empty(get_the_excerpt())) { ?>
        <p class="vf-summary__text">
          <?php echo strip_tags(get_the_excerpt()); ?>
        </p>
        <?php } ?>
        <p class="vf-summary__text">
          <?php echo esc_html($event_type->name); ?>
        </p>
        <?php if ( ! empty($location)) { ?>
        <p class="vf-summary__location">
          <?php echo esc_html($location); ?>
        </p>
        <?php } ?>
      </article>
      <!--/vf-summary-->
      <?php

      // Output divider after all but last post
      if ($wp_query->current_post < $wp_query->post_count - 1) {
        echo '<hr class="vf-divider">';
      }

    } // while (have_posts())
    ?>

  <?php
    $pagination = VF_Events::get_archive_pages();
    if ($pagination['previous'] || $pagination['next']) { ?>
      <nav class="vf-pagination" aria-label="<?php esc_attr_e('Pagination', 'vfwp'); ?>">
        <ul class="vf-pagination__list">
          <?php if ($pagination['previous']) { ?>
          <li class="vf-pagination__item vf-pagination__item--previous-page">
            <a href="<?php echo esc_url($pagination['previous']); ?>" class="vf-pagination__link">
              <?php
            printf(
              '%1$s<span class="vf-u-sr-only"> %2$s</span>',
              _x('Previous', 'events pagination', 'vfwp'),
              _x('page', 'events pagination', 'vfwp')
            );
            ?>
            </a>
          </li>
          <?php } ?>
          <?php if ($pagination['next']) { ?>
          <li class="vf-pagination__item vf-pagination__item--next-page">
            <a href="<?php echo esc_url($pagination['next']); ?>" class="vf-pagination__link">
              <?php
            printf(
              '%1$s<span class="vf-u-sr-only"> %2$s</span>',
              _x('Next', 'events pagination', 'vfwp'),
              _x('page', 'events pagination', 'vfwp')
            );
            ?>
            </a>
          </li>
          <?php } ?>
        </ul>
      </nav>
      <!--/vf-pagination-->
      <?php } ?>

      <?php
    // View alternate past/upcoming archives
    $is_past = VF_Events::is_past_archive();
    $alt_url = VF_Events::get_archive_link( ! $is_past);
    $alt_title = sprintf(
      _x('View %1$s', 'event archive link', 'vfwp'),
      VF_Events::get_archive_title( ! $is_past)
    );
    if ( ! is_tax()) {
    ?>
      <p class="vf-text-body">
        <a href="<?php echo esc_url($alt_url); ?>">
          <?php echo esc_html($alt_title); ?>
        </a>
      </p>
      <?php } ?>

    </div>

  </div>
  <div>
    <a href="https://www.embl.de/training/events/whyembl/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">Why EMBL Events?</h3>
      <p class="vf-box__text">Reasons to attend an event at EMBL</p>
    </a>

    <a href="https://www.embl.de/training/events/about/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">About us</h3>
      <p class="vf-box__text">This is some more content that would be in the box.</p>
    </a>
    <a href="https://www.embl.de/training/events/info_participants/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">Information for participants</h3>
      <p class="vf-box__text">All you need to know before attending an event</p>
    </a>
    <a href="https://www.embl.de/training/events/sponsorship/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">Sponsor an event</h3>
      <p class="vf-box__text">This is some more content that would be in the box.</p>
    </a>
    <a href="https://www.embl.de/training/e-learning/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">E-learning</h3>
      <p class="vf-box__text">This is some more content that would be in the box.</p>
    </a>
    <a href="https://www.embl.de/training/events/whyembl/index.html"
      class="vf-box vf-box--is-link | vf-u-margin__bottom--sm | vf-u-padding__bottom--md | vf-u-padding__top--md">
      <h3 class="vf-box__heading">Newsletter</h3>
      <p class="vf-box__text">This is some more content that would be in the box.</p>
    </a>

    <div class="vf-u-padding--md vf-u-background-color-ui--off-white">
      <h3 class="vf-links__heading">On our blog</h3>
  <?php
  $sidebarloop = new WP_Query(array('posts_per_page' => 3 ));
  while ($sidebarloop->have_posts()) : $sidebarloop->the_post();?>
    <article class="vf-summary vf-summary--article | vf-u-margin__bottom--sm">
      <h2 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="font-size: 19px;"><?php echo esc_html(get_the_title()); ?></a>
      </h2>
      <span class="vf-summary__meta">
        <a class="vf-summary__author vf-summary__link"
            href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author();?></a>
        <time class="vf-summary__date" title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      </span>
    </article>
  <?php endwhile; ?>
  <?php wp_reset_postdata(); ?>
    </div>
    <div class="vf-u-padding--md">
      <svg aria-hidden="true" display="none" class="vf-icon-collection vf-icon-collection--social">
        <defs>
          <g id="vf-social--linkedin">
            <rect xmlns="http://www.w3.org/2000/svg" width="5" height="14" x="2" y="8.5" rx=".5" ry=".5" />
            <ellipse xmlns="http://www.w3.org/2000/svg" cx="4.48" cy="4" rx="2.48" ry="2.5" />
            <path xmlns="http://www.w3.org/2000/svg"
              d="M18.5,22.5h3A.5.5,0,0,0,22,22V13.6C22,9.83,19.87,8,16.89,8a4.21,4.21,0,0,0-3.17,1.27A.41.41,0,0,1,13,9a.5.5,0,0,0-.5-.5h-3A.5.5,0,0,0,9,9V22a.5.5,0,0,0,.5.5h3A.5.5,0,0,0,13,22V14.5a2.5,2.5,0,0,1,5,0V22A.5.5,0,0,0,18.5,22.5Z" />
          </g>
          <g id="vf-social--facebook">
            <path xmlns="http://www.w3.org/2000/svg"
              d="m18.14 7.17a.5.5 0 0 0 -.37-.17h-3.77v-1.41c0-.28.06-.6.51-.6h3a.44.44 0 0 0 .35-.15.5.5 0 0 0 .14-.34v-4a.5.5 0 0 0 -.5-.5h-4.33c-4.8 0-5.17 4.1-5.17 5.35v1.65h-2.5a.5.5 0 0 0 -.5.5v4a.5.5 0 0 0 .5.5h2.5v11.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-11.5h3.35a.5.5 0 0 0 .5-.45l.42-4a.5.5 0 0 0 -.13-.38z" />
          </g>
          <g id="vf-social--twitter">
            <path xmlns="http://www.w3.org/2000/svg"
              d="M23.32,6.44a.5.5,0,0,0-.2-.87l-.79-.2A.5.5,0,0,1,22,4.67l.44-.89a.5.5,0,0,0-.58-.7l-2,.56a.5.5,0,0,1-.44-.08,5,5,0,0,0-3-1,5,5,0,0,0-5,5v.36a.25.25,0,0,1-.22.25c-2.81.33-5.5-1.1-8.4-4.44a.51.51,0,0,0-.51-.15A.5.5,0,0,0,2,4a7.58,7.58,0,0,0,.46,4.92.25.25,0,0,1-.26.36L1.08,9.06a.5.5,0,0,0-.57.59,5.15,5.15,0,0,0,2.37,3.78.25.25,0,0,1,0,.45l-.53.21a.5.5,0,0,0-.26.69,4.36,4.36,0,0,0,3.2,2.48.25.25,0,0,1,0,.47A10.94,10.94,0,0,1,1,18.56a.5.5,0,0,0-.2,1,20.06,20.06,0,0,0,8.14,1.93,12.58,12.58,0,0,0,7-2A12.5,12.5,0,0,0,21.5,9.06V8.19a.5.5,0,0,1,.18-.38Z" />
          </g>
          <g id="vf-social--youtube">
            <path xmlns="http://www.w3.org/2000/svg"
              d="M20.06,3.5H3.94A3.94,3.94,0,0,0,0,7.44v9.12A3.94,3.94,0,0,0,3.94,20.5H20.06A3.94,3.94,0,0,0,24,16.56V7.44A3.94,3.94,0,0,0,20.06,3.5ZM16.54,12,9.77,16.36A.5.5,0,0,1,9,15.94V7.28a.5.5,0,0,1,.77-.42l6.77,4.33a.5.5,0,0,1,0,.84Z" />
          </g>
          <g id="vf-social--instagram">
            <path xmlns="http://www.w3.org/2000/svg"
              d="M17.5,0H6.5A6.51,6.51,0,0,0,0,6.5v11A6.51,6.51,0,0,0,6.5,24h11A6.51,6.51,0,0,0,24,17.5V6.5A6.51,6.51,0,0,0,17.5,0ZM12,17.5A5.5,5.5,0,1,1,17.5,12,5.5,5.5,0,0,1,12,17.5Zm6.5-11A1.5,1.5,0,1,1,20,5,1.5,1.5,0,0,1,18.5,6.5Z" />
          </g>
        </defs>
      </svg>
      <div class="vf-social-links">
        <h3 class="vf-social-links__heading">
          Follow us
        </h3>
        <ul class="vf-social-links__list">
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">twitter</span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--twitter"></use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">facebook</span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--facebook"></use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">instagram</span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--instagram" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--instagram"></use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">youtube</span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--youtube" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--youtube"></use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">linkedin</span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--linkedin"></use>
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="vf-u-padding__left--md">
      <a href="http://eepurl.com/bZBiNj" class="vf-link vf-link--primary ">Sign up for our Newsletter</a>
    </div>
  </div>
</section>

<?php

get_footer();

?>
