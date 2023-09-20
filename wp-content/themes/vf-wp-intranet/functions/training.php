<?php
// expire trainings on date field.
if (!wp_next_scheduled('expire_trainings')){
  wp_schedule_event(time(), 'daily', 'expire_trainings'); // this can be hourly, twicedaily, or daily
}

add_action('expire_trainings', 'expire_trainings_function');

function expire_trainings_function() {
    $today = date('Ymd');
    $args = array(
        'post_type' => array('training'), // post types you want to check
        'posts_per_page' => -1 
    );
    $posts = get_posts($args);
    foreach($posts as $p){
        $expiredate = get_field('vf-wp-training-start_date', $p->ID, false, false); // get the raw date from the db
        if ($expiredate) {
            if($expiredate <= $today){
                $postdata = array(
                    'ID' => $p->ID,
                    'post_status' => 'draft'
                );
                wp_update_post($postdata);
            }
        }
    }
}

// adds settings to run cron and unpublish trainings manually

add_action('admin_menu', 'settings_training_menu');

function settings_training_menu(){
  add_submenu_page('edit.php?post_type=training', 'Training settings', 'Settings', 'edit_posts', 'training-slug', 'settings_training_admin_page');
}

function settings_training_admin_page() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.')    );
  }
  // Start building the page
  echo '<div class="wrap">';
  echo '<h2>Settings</h2>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['unpublish_training']) && check_admin_referer('unpublish_training_clicked')) {
    // the button has been pressed AND we've passed the security check
    expire_trainings_function();
  }
  echo '<h3>Unpublish past trainings manually</h2>';
  echo '<form action="edit.php?post_type=training&page=training-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('unpublish_training_clicked');
  echo '<input type="hidden" value="true" name="unpublish_training" />';
  submit_button('Unpublish');
  echo '</form>';



  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['sync_training']) && check_admin_referer('sync_training_clicked')) {
    // the button has been pressed AND we've passed the security check
    vfwp_intranet_cron_process_training_data();
  }
  echo '<h3>Sync training data</h3>';
  echo '<form action="edit.php?post_type=training&page=sync-training-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('sync_training_clicked');
  echo '<input type="hidden" value="true" name="sync_training" />';
  submit_button('Sync');
  echo '</form>';




  echo '<h3>Delete all training posts</h3>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['delete_training']) && check_admin_referer('delete_training_clicked')) {
    // the button has been pressed AND we've passed the security check
    delete_all_training_posts();
  }
  echo '<form action="edit.php?post_type=training&page=delete-training-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('delete_training_clicked');
  echo '<input type="hidden" value="true" name="delete_training" />';
  submit_button('Delete');
  echo '</form>';



  echo '<h3>Sync BioIT</h3>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['sync_bioit_training']) && check_admin_referer('sync_bioit_training_clicked')) {
    // the button has been pressed AND we've passed the security check
    vfwp_intranet_cron_process_bioit_data();
    }
  echo '<form action="edit.php?post_type=training&page=delete-training-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('sync_bioit_training_clicked');
  echo '<input type="hidden" value="true" name="sync_bioit_training" />';
  submit_button('Sync BioIT');
  echo '</form>';


  echo '</div>';

}

// excludes drafts posts from the alternative dates selection
add_filter( 'acf/fields/relationship/query','relationship_options_filter', 10, 3);

function relationship_options_filter($options, $field, $the_post) {
	
	$options['post_status'] = array('publish');
	$options['meta_key'] = 'vf-wp-training-start_date';
	$options['orderby'] = 'meta_value';
	$options['order'] = 'ASC';
	
	return $options;
}

add_filter('acf/fields/relationship/result', 'my_acf_fields_relationship_result', 10, 4);
function my_acf_fields_relationship_result( $text, $post, $field, $post_id ) {
    $startDate = get_field( 'vf-wp-training-start_date', $post->ID );
    if( $startDate ) {
        $text .= ' ' . sprintf( '(%s)', $startDate );
    }
    return $text;
}

?>