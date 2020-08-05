<?php

$info_box = get_field('vf_event_info_box', $post->post_parent);

$cco_box_items = get_field('vf_event_cco_box_items', $post->post_parent);

$accommodation = get_field('accommodation', $post->post_parent);
$financial = get_field('financial', $post->post_parent);
$childcare = get_field('childcare', $post->post_parent);
$faq = get_field('faq', $post->post_parent);
$travel = get_field('travel', $post->post_parent);
$code = get_field('code', $post->post_parent);
$onsite = get_field('onsite', $post->post_parent);
$terms = get_field('terms', $post->post_parent);
$data = get_field('data', $post->post_parent);
$contact = get_field('contact', $post->post_parent);

?>

<?php if ($info_box == 1) { ?>
      <div class="vf-box vf-box--normal vf-box-theme--quinary" style="display: inline-table;">
        <h3 class="vf-box__heading">Information for participants</h3>

        <?php
        ///// Financial Assistance
        if ($cco_box_items && in_array('financial', $cco_box_items)) { ?>
        <?php if ($financial['financial_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $financial['financial_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/fellowships/index.html">
        <?php }?>
        <p class="vf-box__text">Financial Assistance</p></a>
        <p class="vf-box__text">Find out more about the Registration Fee Waivers and Travel Grants available for EMBL Courses and Conferences in Heidelberg</p>
        <?php } ?>

        <?php
        ///// Childcare
        if ($cco_box_items && in_array('childcare', $cco_box_items)) { ?>
        <?php if ($childcare['childcare_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $childcare['childcare_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/childcare/index.html">
        <?php }?>
        <p class="vf-box__text">Childcare</p></a>
        <p class="vf-box__text">EMBL offers onsite childcare for many of the conferences and symposia run at EMBL Heidelberg.</p>
        <?php } ?>

        <?php
        ///// Accommodation
        if ($cco_box_items && in_array('accommodation', $cco_box_items)) { ?>
        <?php if ($accommodation['accommodation_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $accommodation['accommodation_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/accommodation/index.html">
        <?php }?>
        <p class="vf-box__text">Accommodation</p></a>
        <p class="vf-box__text">For all courses and conferences at EMBL the Course and Conference Office blocks accommodation in advance to secure rooms for their guests.</p>
        <?php } ?>

        <?php
        ///// FAQ
        if ($cco_box_items && in_array('faqs', $cco_box_items)) { ?>
        <?php if ($faq['faq_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $faq['faq_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/faqs/index.html">
        <?php }?>
        <p class="vf-box__text">FAQ</p></a>
        <p class="vf-box__text">We aim to provide you with all the necessary and relevant information in order to make your participation at one of our events as enjoyable as possible. </p>
        <?php } ?>

        <?php
        ///// Travel info
        if ($cco_box_items && in_array('travel_information', $cco_box_items)) { ?>
        <?php if ($travel['travel_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $travel['travel_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/travel_information/index.html">
        <?php }?>
        <p class="vf-box__text">Travel information</p></a>
        <p class="vf-box__text">During conferences EMBL arranges frequent bus transfers between EMBL and various stops around Heidelberg.</p>
        <?php } ?>

        <?php
        ///// Code of Conduct
        if ($cco_box_items && in_array('conduct', $cco_box_items)) { ?>
        <?php if ($code['code_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $code['code_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/codeofconduct/index.html">
        <?php }?>
        <p class="vf-box__text">Code of Conduct</p></a>
        <p class="vf-box__text">The EMBL Course and Conference Office is dedicated to providing a harassment-free learning experience for everyone.</p>
        <?php } ?>

        <?php
        ///// Onsite info
        if ($cco_box_items && in_array('onsite_information', $cco_box_items)) { ?>
        <?php if ($onsite['onsite_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $onsite['onsite_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/onsiteinfo/index.html">
        <?php }?>
        <p class="vf-box__text">Onsite Information</p></a>
        <p class="vf-box__text">Useful information for when you arrive onsite at the event.</p>
        <?php } ?>

        <?php
        ///// Terms and Conditions
        if ($cco_box_items && in_array('terms', $cco_box_items)) { ?>
        <?php if ($terms['terms_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $terms['terms_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/terms/index.html">
        <?php }?>
        <p class="vf-box__text">Terms and Conditions</p></a>
        <p class="vf-box__text">Don't forget to read the small print associated with your course or conference registration.</p>
        <?php } ?>

        <?php
        ///// Info for data Subjects
        if ($cco_box_items && in_array('data_subjects', $cco_box_items)) { ?>
        <?php if ($data['data_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $data['data_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/information-for-data-subjects/index.html">
        <?php }?>
        <p class="vf-box__text">Information for Data Subjects</p></a>
        <p class="vf-box__text">We take your data seriously. Please read our policy on how we handle your data.</p>
        <?php } ?>

        <?php
        ///// Contact
        if ($cco_box_items && in_array('contact', $cco_box_items)) { ?>
        <?php if ($contact['contact_link']) { ?>
        <a class= "vf-link" href="<?php echo esc_url( $contact['contact_link'] );?>"> 
        <?php } else { ?>
        <a class= "vf-link" href="https://www.embo-embl-symposia.org/info_participants/contact/index.html">
        <?php } ?>
        <p class="vf-box__text">Contact</p></a>
        <p class="vf-box__text">All the details you need to get in touch with the EMBL Course and Conference Office.</p>
        <?php } ?>

      </div>
    <?php } ?>
