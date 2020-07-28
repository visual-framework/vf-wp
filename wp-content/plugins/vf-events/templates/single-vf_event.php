<?php

get_header();

global $post;

$start_date = get_field(
  'vf_event_start_date',
  $post->ID
);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field(
  'vf_event_end_date',
  $post->ID
);
$end = DateTime::createFromFormat('j M Y', $end_date);


$location = get_field(
  'vf_event_location',
  $post->ID
);

$location = strtoupper($location);

$submission_closing = get_field(
  'vf_event_submission_closing',
  $post->ID
);

$registration_closing = get_field(
  'vf_event_registration_closing',
  $post->ID
);

$summary = get_field(
  'vf_event_summary',
  $post->ID
);

$additional_info = get_field(
  'vf_event_additional_info',
  $post->ID
);

$event_type = get_field(
  'vf_event_event_type',
  $post->ID
);

$registration_link = get_field(
  'vf_event_registration_link',
  $post->ID
);

$image = get_field('vf_event_logo');
$image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

?>

<section class="vf-hero vf-hero--inlay vf-hero--hard  | vf-hero-theme--primary | vf-u-margin__bottom--xxl">
    <style>
  .vf-hero {
    --vf-hero-bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/vf-hero-intense.png');
    --vf-hero-grid__row--initial: 384px;
  }

  </style>
        <div class="vf-hero__content">
    <h2 class="vf-hero__heading">
      EMBL <?php echo esc_html($event_type); ?>   </h2>
    <p class="vf-hero__text" style="font-size: 32px;">
    <?php the_title(); ?>
    </p>
  </div>
</section>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-u-padding--md | vf-u-padding__right--0">
    <p class="vf-text-body vf-text-body--2">

      <?php 
        if ($end_date) { 
          echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); ?> 
      <?php } 
        else {
          echo $start->format('j M Y'); 
        }
      ?>
      &nbsp;&nbsp;&nbsp; <?php echo esc_html($location); ?></p>

    <p class="vf-text-body vf-text-body--4" style="font-weight: 400;">Application deadline: <?php echo esc_html($registration_closing); ?></p>

    <?php if ( ! empty($submission_closing)) { ?>
    <p class="vf-text-body vf-text-body--4" style="font-weight: 400;">Abstract submission deadline: <?php echo esc_html($submission_closing); ?></p>
    <?php } ?>

  </div>

  <div class="vf-u-padding__top--md">
    <?php if ( ! empty($registration_link)) { ?>
      <a href="<?php echo esc_url($registration_link); ?>"><button class="vf-button vf-button--primary vf-button--sm">Register</button></a>
    <?php } ?>
  </div>
  <div>
    <figure class="vf-figure vf-figure--align vf-figure--align-centered">
    <?php echo($image); ?> 
    </figure>
  </div>
</section>

<hr class="vf-divider">

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <?php 
      if ( ! empty($summary)) { 
        echo ($summary);
       } 
      else {
        the_content();
      }
      ?>
    </div>
    <div>
      <div class="vf-box vf-box--normal vf-box-theme--quinary">
        <h3 class="vf-box__heading">Information for participants</h3>
        <a class= "vf-link" href="#"><p class="vf-box__text">Financial Assistance</p></a>
        <p class="vf-box__text">Find out more about the Registration Fee Waivers and Travel Grants available for EMBL Courses and Conferences in Heidelberg</p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">Childcare</p></a>
        <p class="vf-box__text">EMBL offers onsite childcare for many of the conferences and symposia run at EMBL Heidelberg.</p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">Accommodation</p></a>
        <p class="vf-box__text">For all courses and conferences at EMBL the Course and Conference Office blocks accommodation in advance to secure rooms for their guests.</p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">FAQ</p></a>
        <p class="vf-box__text">We aim to provide you with all the necessary and relevant information in order to make your participation at one of our events as enjoyable as possible. </p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">Travel information</p></a>
        <p class="vf-box__text">During conferences EMBL arranges frequent bus transfers between EMBL and various stops around Heidelberg.</p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">Terms and Conditions</p></a>
        <p class="vf-box__text">Don't forget to read the small print associated with your course or conference registration.</p>
        
        <a class= "vf-link" href="#"><p class="vf-box__text">Contact</p></a>
        <p class="vf-box__text">All the details you need to get in touch with the EMBL Course and Conference Office.</p>
      </div>
    </div>
  </section>
  
  <section class="vf-content">
    <?php 
      if ($summary) {
        the_content();
      }
      ?>
    
    <?php if ( ! empty($additional_info)) { ?>
        <h2>
          <?php esc_html_e('Additional Information', 'vfwp'); ?>
        </h2>
        <p><?php echo ($additional_info); ?></p>
    <?php } ?>    
</section>
<section class="vf-content">
  <p>EMBL Courses and Conferences are kindly supported by our <a href="https://www.embl.de/aboutus/support-embl/corporate-partnership-programme/index.html">Corporate Partnership Programme</a></p>
  <p><b>Founder partners</b></p>
  <div class="vf-grid vf-grid__col-6">
      <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/leica-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/olympus-logo.jpg" style="width: 120px">
    </figure>
  </div>
  <p><b>Corporate partners</b></p>
  <div class="vf-grid vf-grid__col-6">
      <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/10x-Genomics_120x82.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/bd-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Bio-Rad.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/boehringer-ingelheim-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/eppendorf-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/gsk-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Milteniy-Biotec_logo120.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/NetApp_logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Stilla-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/thermofisher-logo.jpg" style="width: 120px">
    </figure>
  </div>
  <p><b>Associate partners</b></p>
  <div class="vf-grid vf-grid__col-6">
      <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/merck-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/NEB_logo_120.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/nikon-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Promega_logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Roche_logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/sanofi-logo.jpg" style="width: 120px">
    </figure>
    <figure class="vf-figure">
      <img class="vf-figure__image | vf-u-margin__bottom--0" src="https://www.embl.de/layout/images/cco/3rd_column_text/cpp-logos/Sartorius.jpg" style="width: 120px">
    </figure>
  </div>    
</section>

<?php

get_footer();

?>