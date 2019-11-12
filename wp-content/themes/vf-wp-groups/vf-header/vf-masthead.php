<style>
  .vf-masthead {
    background-color: var(--vf-masthead__color--background, var(--vf-masthead__color--background-default));
    color: var(--vf-masthead__color--foreground, var(--vf-masthead__color--foreground-default));
  }
</style>
<div data-vf-js-masthead class="vf-masthead">
  <div class="vf-masthead__inner">
    <div class="vf-masthead__title">
      <h1 class="vf-masthead__heading">
        <a class="vf-masthead__heading__link" href="<?php echo home_url(); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
        <span class="vf-masthead__heading--additional">
          <?php
          /**
           * Description option is filtered in `functions/theme.php`
           * to update via the Content Hub cache
           */
          echo get_bloginfo('description');
          ?>
        </span>
      </h1>
    </div>
  </div>
</div>
<!--/vf-masthead-->
