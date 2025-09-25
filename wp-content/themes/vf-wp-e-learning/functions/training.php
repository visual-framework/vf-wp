<?php

// add_action('expire_trainings', 'expire_trainings_function');

// function expire_trainings_function() {
//     $today = date('Ymd');
//     $args = array(
//         'post_type' => array('training'), // post types you want to check
//         'posts_per_page' => -1 
//     );
//     $posts = get_posts($args);
//     foreach($posts as $p){
//         $expiredate = get_field('vf-wp-training-start_date', $p->ID, false, false); // get the raw date from the db
//         if ($expiredate) {
//             if($expiredate <= $today){
//                 $postdata = array(
//                     'ID' => $p->ID,
//                     'post_status' => 'draft'
//                 );
//                 wp_update_post($postdata);
//             }
//         }
//     }
// }

// adds settings to run cron and unpublish trainings manually

add_action('admin_menu', 'settings_training_menu');

function settings_training_menu(){
  add_submenu_page('edit.php?post_type=training', 'Training settings', 'Settings', 'manage_options', 'training-slug', 'settings_training_admin_page');
}

function settings_training_admin_page() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.')    );
  }
  // Start building the page
  echo '<div class="wrap">';
  echo '<h2>Settings</h2>';
  settings_errors();



  // EMBL-EBI
  if (isset($_POST['sync_training_od']) && check_admin_referer('sync_training_od_clicked')) {
   vfwp_intranet_cron_process_training_on_demand_data();
  }
  echo '<h3>Sync EMBL-EBI on-demand Trainings data</h3>';
  echo '<form action="edit.php?post_type=training&page=training-slug" method="post">';
  wp_nonce_field('sync_training_od_clicked');
  echo '<input type="hidden" value="true" name="sync_training_od" />';
  submit_button('Sync');
  echo '</form>';
  
  
  // Unpublish
  // if (isset($_POST['unpublish_training']) && check_admin_referer('unpublish_training_clicked')) {
  //   expire_trainings_function();
  // }
  // echo '<h3>Unpublish past trainings manually</h2>';
  // echo '<form action="edit.php?post_type=training&page=training-slug" method="post">';
  // wp_nonce_field('unpublish_training_clicked');
  // echo '<input type="hidden" value="true" name="unpublish_training" />';
  // submit_button('Unpublish');
  // echo '</form>';
  
// Delete
  // echo '<h3>Delete all training posts</h3>';
  // if (isset($_POST['delete_training']) && check_admin_referer('delete_training_clicked')) {
  //   delete_all_training_posts();
  // }
  // echo '<form action="edit.php?post_type=training&page=training-slug" method="post">';
  // wp_nonce_field('delete_training_clicked');
  // echo '<input type="hidden" value="true" name="delete_training" />';
  // submit_button( __( 'Delete', 'textdomain' ), 'delete' );
  // echo '</form>';
  echo '</div>';

}



/*
 * Redirect pages to external links
 */

add_action( 'template_redirect', 'trainingRedirect' );
 function trainingRedirect(){
     $redirect = get_post_meta( get_the_ID(), 'vf-wp-training-url', true );
     if (is_page() || is_singular('training')) {
     if( $redirect ){
         wp_redirect( $redirect );
     } }
 }