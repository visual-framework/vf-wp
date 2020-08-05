<?php

$info_box = get_field('vf_event_info_box', $post->post_parent);

$box_items = get_field('vf_event_box_items', $post->post_parent);

$accommodation = get_field('accommodation');

?>

<?php if ($info_box == 1) { ?>
      <div class="vf-box vf-box--normal vf-box-theme--quinary" style="display: inline-table;">
        <h3 class="vf-box__heading">Information for participants</h3>

        <?php 
        if ($box_items && in_array('financial', $box_items)) { ?>
        <a class= "vf-link" href="#"><p class="vf-box__text">Financial Assistance</p></a>
        <p class="vf-box__text">Find out more about the Registration Fee Waivers and Travel Grants available for EMBL Courses and Conferences in Heidelberg</p>
        <?php } ?>

        <?php if ($box_items && in_array('childcare', $box_items)) { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/childcare/index.html"><p class="vf-box__text">Childcare</p></a>
        <p class="vf-box__text">EMBL offers onsite childcare for many of the conferences and symposia run at EMBL Heidelberg.</p>
        <?php } ?>

        <?php if ($box_items && in_array('accommodation', $box_items)) { ?>
        <a class= "vf-link" href="
        <?php if ($accommodation) { 
          echo esc_url( $accommodation['accommodation_link'] );?>"
          <?php } else { ?>
             "https://www.embo-embl-symposia.org/info_participants/accommodation/index.html"; <?php } ?>><p class="vf-box__text">Accommodation</p></a>
        <p class="vf-box__text">For all courses and conferences at EMBL the Course and Conference Office blocks accommodation in advance to secure rooms for their guests.</p>
        <?php } ?>

        <?php if ($box_items && in_array('faqs', $box_items)) { ?>
        <a class= "vf-link" href="#"><p class="vf-box__text">FAQ</p></a>
        <p class="vf-box__text">We aim to provide you with all the necessary and relevant information in order to make your participation at one of our events as enjoyable as possible. </p>
        <?php } ?>

        <?php if ($box_items && in_array('travel_information', $box_items)) { ?>
        <a class= "vf-link" href="#"><p class="vf-box__text">Travel information</p></a>
        <p class="vf-box__text">During conferences EMBL arranges frequent bus transfers between EMBL and various stops around Heidelberg.</p>
        <?php } ?>

        <?php if ($box_items && in_array('terms', $box_items)) { ?>
        <a class= "vf-link" href="#"><p class="vf-box__text">Terms and Conditions</p></a>
        <p class="vf-box__text">Don't forget to read the small print associated with your course or conference registration.</p>
        <?php } ?>

        <?php if ($box_items && in_array('contact', $box_items)) { ?>
        <a class= "vf-link" href="#"><p class="vf-box__text">Contact</p></a>
        <p class="vf-box__text">All the details you need to get in touch with the EMBL Course and Conference Office.</p>
        <?php } ?>

      </div>
    <?php } ?>
