<?php

/*
* splite_notice_dismissable
* Ajax action to do tasks on notice dismissable
* Require class: splite-dismissable
*/
add_action( 'wp_ajax_splite_notice_dismissable', 'splite_notice_dismissable' );
function splite_notice_dismissable() {
	
	$data_btn = isset($_POST['dataBtn']) ? $_POST['dataBtn'] : '';
	
	if(empty($data_btn)) return; 
	
	$today = DateTime::createFromFormat('U', current_time('U')); 
	
	switch($data_btn) {
		case 'ask-later': 
			$ask_later = get_option('splite_review_notice') ? get_option('splite_review_notice') : 0; 
			update_option('splite_review_notice', ++$ask_later); 
			break; 
		case 'ask-never': 
			update_option('splite_review_notice', 0); 
			break; 
	}
		
	wp_send_json_success(); 
	wp_die(); 
}

add_action( 'admin_notices', 'splite_admin_notices' );
function splite_admin_notices() {
	
	$install_date = get_option('splite_install_date', 0); 
	$install_date_object = DateTime::createFromFormat('Y-m-d H:i:s', $install_date);
	
	// review_notice - numeric counter for multiplying 14 days
	$review_notice = get_option('splite_review_notice') ? get_option('splite_review_notice') : 1; 
	
	if($install_date OR ! is_a($install_date_object, 'DATEIME')) {
		update_option('splite_install_date', current_time('Y-m-d H:i:s')); 
		return; 
	}
		
	$today = DateTime::createFromFormat('U', current_time('U')); 
	$diff = $today->diff($install_date_object); 
	//print_r($diff); 
	
	if($diff->d >= 14*$review_notice and $review_notice!=0) {
		echo '<div class="notice notice-success">
			<h2 style="margin:0.5em 0;">Hope you are enjoying - <span style="color:#0073aa;">Slick Popup Lite</span></h2>
			<p>
			'.__( 'Thanks for using one of the best WordPress Popup Plugin for Contact Form 7. We hope that it has been useful for you and would like you to leave review on WordPres.org website, it will help us improve the product features.', 'slick-popup' ).'
			<br><br>
			<a class="button-primary" href="'.admin_url('admin.php?page=slick-options').'">Leave a Review</a>
			&nbsp;<a class="button-link splite-dismissable" data-btn="ask-later" href="#">Ask Later</a> |
			<a class="button-link splite-dismissable" data-btn="ask-never" href="#">Never Show Again</a></p>
		</div>';		
	}
}


/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
function splite_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if( get_transient( 'splite_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">Thanks for updating - <span style="color:#0073aa;">Slick Popup Lite</span></h2>
			<p>
			'.__( 'One of the best WordPress Popup Plugin for Contact Form 7. ', 'slick-popup' ).'
			<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both; font-weight: bold;"><a href="'.admin_url('admin.php?page=slick-options').'">Go to Settings</a> | <a href="'.admin_url('admin.php?page=import-demos').'">Import Demo Forms</a> </span>
			</p>
		</div>';
		
		// Save splite_install_date for already existing users (before: 1.5.3)
		if(!get_option('splite_install_date'))
			update_option('splite_install_date', current_time('Y-m-d H:i:s')); 			
		
		delete_transient( 'splite_updated' );
	}
}
add_action( 'admin_notices', 'splite_display_update_notice' );

/**
 * Show a notice to anyone who has just installed the plugin for the first time
 * This notice shouldn't display to anyone who has just updated this plugin
 */
function splite_display_install_notice() {
	// Check the transient to see if we've just activated the plugin
	if(get_transient( 'splite_activated' ) ) {
		
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">Thanks for installing - <span style="color:#0073aa;">Slick Popup Lite</span></h2>
			<p>
			'.__( 'One of the best WordPress Popup Plugin for Contact Form 7. ', 'slick-popup' ).'
			<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both; font-weight: bold;"><a href="'.admin_url('admin.php?page=slick-options').'">Go to Settings</a> | <a href="'.admin_url('admin.php?page=import-demos').'">Import Demo Forms</a> </span>
			</p>
		</div>';
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'splite_activated' );
	}
}
add_action( 'admin_notices', 'splite_display_install_notice' );

?>