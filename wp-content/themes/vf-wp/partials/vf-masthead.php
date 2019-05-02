<div class="vf-masthead" style="background-color: var(--vf-masthead__color--background, var(--vf-masthead__color--background-default)); color: var(--vf-masthead__color--foreground, var(--vf-masthead__color--foreround-default) );">
    <div class="vf-masthead__title">
        <h1 class="vf-masthead__heading">
          <a class="vf-masthead__heading__link" href="<?php echo home_url(); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
          <span class="vf-masthead__heading--additional">
          <?php
          if ( class_exists('VF_Cache') ) {
            $uuid = vf__get_site_uuid();
            $short_description = VF_Cache::get_post('https://dev.beta.embl.org/api/v1/pattern.html?filter-content-type=profiles&filter-uuid='.$uuid.'&pattern=node-strapline&source=contenthub');
            // we don't want any of the contentHub `div`s. `span`s are OK though
            $short_description = str_replace(array('<div', '</div>'), array('<span', '</span>'), $short_description);
            print $short_description;
          }
          ?>
          </span>
        </h1>
    </div>
</div>

<!--/vf-masthead-->
