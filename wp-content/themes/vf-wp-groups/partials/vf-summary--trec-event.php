<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_start_date',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$end_date = get_field('vf_event_end_date',$post_id);
$end = DateTime::createFromFormat('j M Y', $end_date);
$location = get_field('vf_event_trec_location',$post_id);
$city = get_field('vf_event_trec_city',$post_id); 
$partners = get_field('vf_event_trec_partners',$post_id); 
$registration = get_field('vf_event_trec_registration',$post_id); 

?>

<article class="vf-summary vf-summary--event | vf-u-margin__bottom--200" data-jplist-item>
  <p class="vf-summary__date">
    <?php if ( ! empty($start_date)) { ?>
    <?php       // Event dates
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
           } 
        else {
          echo $start->format('j F Y'); 
        } 
     ?>
    <?php } ?>
    <?php if ( ! empty($city)) { ?>
    <span class="vf-summary__location | city-<?php echo $city; ?>"><?php echo ' | ' . $city. ', '; ?>
    </span><?php } ?>
    <?php if ( ! empty($location)) { ?>
    <span class="vf-summary__location"><?php echo strtoupper($location); ?>
    </span>
    <?php } ?>
  </p>
  <h3 class="vf-summary__title | vf-u-margin__bottom--100 | name " style="font-weight: 500;">
    <?php the_title(); ?>
  </h3>
  <div>
    <p class="vf-summary__text" style="font-size: 19px;"><?php echo get_the_excerpt(); ?></p>
    <div class="vf-grid vf-grid__col-2 | vf-u-margin__top--200">
      <div <?php if ( empty($partners) && empty($registration)) { echo 'class="vf-u-margin__top--400"'; } ?>>
        <?php if ( ! empty($partners)) { ?>
        <p class="vf-u-margin--0"><span style="font-weight: 500;">Partners: </span><span
            class="vf-summary__location"><?php echo $partners; ?></span></p>
        <?php } ?>
        <?php if ( ! empty($registration)) { ?>
        <button class="vf-button vf-button--link" onclick="location.href='<?php echo $registration; ?>';"
          style="padding-left: 0; border-left: 0;">Registration</button>
        <?php } ?>
      </div>
      <div>
      </div>
    </div>
  </div>
  <hr class="vf-divider">
</article>
