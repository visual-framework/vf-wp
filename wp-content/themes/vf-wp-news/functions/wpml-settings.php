<?php
// custom language switcher for the WPML plugin
if ( function_exists( 'icl_object_id' ) ) {
    function languages_links_switcher(){
       $languages = icl_get_languages('skip_missing=1');
       if(1 < count($languages)){ echo __(' <div class="vf-banner vf-banner--alert vf-banner--info | vf-u-margin__bottom--200">
         <div class="vf-banner__content">
           <style>
             .vf-banner__content p {
               font-size: 16px !important;
               margin: 0px !important;
             }
           </style>
           <p class="vf-banner__text">This article is also available in ');
       
             foreach($languages as $l){
             if(!$l['active']) $langs[] = '<a href="'.$l['url'].'"><img class="wpml-ls-flag iclflag" src="' . $l['country_flag_url'].'" />&nbsp;' .$l['native_name'].'</a>';
             }
             echo join(' and ', array_filter(array_merge(array(join(', ', array_slice($langs, 0, -1))), array_slice($langs,
             -1)), 'strlen'));
       
             echo __('
           </p>
         </div>
         </div>' );
       
         }
         }
         
       
         // Show linked WPML posts in a loop
       function wpml_post_languages_in_loop() {
           $home_url = get_home_url();
   
         $thispostid = get_the_ID();
         $post_trid = apply_filters('wpml_element_trid', NULL, get_the_ID(), 'post_' . get_post_type());
         $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0&orderby=code' );
         if (empty($post_trid))
             return;
         $translation = apply_filters('wpml_get_element_translations', NULL, $post_trid, 'post_' . get_post_type());
         if (1 < count($translation)) {
           echo '<p class="vf-summary__meta">';
             foreach ($translation as $l) {
                 if ($l->element_id != $thispostid) {
                     $langs[] = '<a href="' . apply_filters('wpml_permalink', ( get_permalink($l->element_id)), $l->language_code) . '"><img class="wpml-ls-flag iclflag" src="' . $home_url . '/wp-content/plugins/sitepress-multilingual-cms/res/flags/' . $l->language_code .'.png" />' . '</a>';
                     }
             }
             echo join(' &nbsp; ', $langs);
             echo '</p>';
         }
       }
   
       function wpml_post_languages_in_loop_card() {
           $thispostid = get_the_ID();
           $post_trid = apply_filters('wpml_element_trid', NULL, get_the_ID(), 'post_' . get_post_type());
           $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0&orderby=code' );
           if (empty($post_trid))
               return;
           $translation = apply_filters('wpml_get_element_translations', NULL, $post_trid, 'post_' . get_post_type());
           if (1 < count($translation)) {
             echo '<p class="vf-card__text" style="margin: 0px;">';
               foreach ($translation as $l) {
                   if ($l->element_id != $thispostid) {
                       $langs[] = '<a class="vf-card__link" href="' . apply_filters('wpml_permalink', ( get_permalink($l->element_id)), $l->language_code) . '"><img class="wpml-ls-flag iclflag" src="'.$languages[$l->language_code]['country_flag_url'].'" />' . '</a>';
                       }
               }
               echo join(' &nbsp; ', $langs);
               echo '</p>';
           }
         }
       }
?>