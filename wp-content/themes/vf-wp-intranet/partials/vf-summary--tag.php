<?php

$title = esc_html(get_the_title());

?>

    <div>
      <a class="vf-link" href="<?php the_permalink(); ?>">
      <?php echo $title; ?>
      </a>
    </div>
