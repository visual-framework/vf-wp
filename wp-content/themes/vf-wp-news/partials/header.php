<?php get_template_part('partials/head'); ?>
<?php vf_header(); ?>


<div class="vf-body">
  <section class="vf-intro | embl-grid embl-grid--has-centered-content">
    <div>
      <!-- empty -->
    </div>
    <div>
      <h1 class="vf-intro__heading vf-intro__heading--has-tag">
        <a class="vf-link" href="<?php echo home_url(); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
        <a href=""
          class="vf-badge vf-badge--primary vf-badge--phases">beta</a></h1>
      <p class="vf-lede">
        <?php
        /**
         * Description option is filtered in `functions/theme.php`
         * to update via the Content Hub cache
         */
        echo get_bloginfo('description');
        ?>      
      </p>
      <!-- <p class="vf-intro__text"></p> -->
    </div>
  </section>
</div>

  
  
<header class="vf-header vf-header--inlay">
  <?php get_template_part('partials/vf-navigation'); ?>
</header>

