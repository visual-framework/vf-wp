<?php

get_header();

global $vf_theme;

?>

<?php the_content(); ?>

<section class="embl-grid embl-grid--has-centered-content">
 <div>
 </div> 
  <?php the_content(); ?>   
 </div>
</section>
<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

<?php

get_footer();

?>
