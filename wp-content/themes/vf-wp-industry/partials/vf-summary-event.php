<article class="vf-summary vf-summary--event">
     <h5><?php echo $start->format('F Y'); ?></h5>
        <?php if ( ! empty($start_date)) { ?>
        <p class="vf-summary__date">
          <?php       // Event dates
      if ( ! empty($start_date)) {
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
              ?>
        <?php } 
        else {
          echo $start->format('j M Y'); 
        } }
        ?>
        </p>
        <?php } ?>
        <h3 class="vf-summary__title">
          <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
            <?php the_title(); ?>
          </a>
        </h3>
      </article>
