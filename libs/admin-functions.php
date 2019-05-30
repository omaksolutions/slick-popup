<?php

/*
* splite_notice_dismissable
* Ajax action to do tasks on notice dismissable
* Require class: splite-dismissable
*/
add_action( 'wp_ajax_splite_notice_dismissable', 'splite_notice_dismissable' );
function splite_notice_dismissable() {
	
	// Sanitize string for added security
	$data_btn = isset($_POST['dataBtn']) ? sanitize_text_field($_POST['dataBtn']) : '';
	
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

/**
 * Show a notice to anyone who has to give a review after using the plugin for 14 days
 * This notice shouldn't display to who has to given a review already
**/
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
			<h3>Hope you are enjoying - <span class="color">Slick Popup Lite</span></h3>
			<div class="row">
					<div class="splite-notice-left"><img src="'.splite_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image"></div>
					<div class="splite-notice-right">
						<p>'.esc_html__( 'Thanks for using one of the best WordPress Popup Plugin for Contact Form 7. We hope that it has been useful for you and would like you to leave review on WordPres.org website, it will help us improve the product features.', 'slick-popup' ).'</p>
						<p><a class="button-primary" href="https://wordpress.org/support/plugin/slick-popup/reviews/">Leave a Review</a>
						&nbsp;<a class="button-link splite-dismissable" data-btn="ask-later" href="#">Ask Later</a> |
						<a class="button-link splite-dismissable" data-btn="ask-never" href="#">Never Show Again</a></p>
					</div>
				</div>
		</div>';		
	}
}
add_action( 'admin_notices', 'splite_admin_notices' );


/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
**/
function splite_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if(get_transient( 'splite_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
				<div class="row">
					<div class="splite-notice-left"><img src="'.splite_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image"></div>
					<div class="splite-notice-right">
						<h4>Thanks for updating - <span class="color">Slick Popup Lite</span></h4>
						<p>'.__( 'One of the best WordPress Popup Plugin for Contact Form 7. ', 'slick-popup' ).'
						<span class="admin-links"><a href="'.admin_url('admin.php?page=slick-options').'">Go to Settings</a> | <a href="'.admin_url('admin.php?page=import-demos').'">Import Demo Forms</a> </span></p>
					</div>
				</div>
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
**/
function splite_display_install_notice() {
	// Check the transient to see if we've just activated the plugin
	if(get_transient( 'splite_activated' ) ) {
		
		echo '<div class="notice notice-success is-dismissible">
				<div class="row">
					<div class="splite-notice-left"><img src="'.splite_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image"></div>
					<div class="splite-notice-right">
						<h4>Thanks for installing - <span class="color">Slick Popup Lite</span></h4>
						<p>'.__( 'One of the best WordPress Popup Plugin for Contact Form 7. ', 'slick-popup' ).'
						<span class="admin-links"><a href="'.admin_url('admin.php?page=slick-options').'">Go to Settings</a> | <a href="'.admin_url('admin.php?page=import-demos').'">Import Demo Forms</a> </span></p>
					</div>
				</div>
			</div>';
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'splite_activated' );
	}
}
add_action( 'admin_notices', 'splite_display_install_notice' );

/**
 * Show a notice for the user who has kept the temporary grant access on for more than  14 days
 * This notice shouldn't display to anyone who has removed the temporary access
**/
function splite_grant_access_alert() {
	
	if(!username_exists('slickpopupteam') OR !email_exists('poke@slickpopup.com')) {
		return; 
	}
	
	$access_granted = get_option('splite_grant_access_time') ? get_option('splite_grant_access_time') : current_time('Y-m-d H:i:s');
	$access_granted_object = DateTime::createFromFormat('Y-m-d H:i:s', $access_granted);
	$today = DateTime::createFromFormat('U', current_time('U'));
	$diff = $today->diff($access_granted_object);

	if($diff->days >=14) {
		echo '<div class="notice notice-success is-dismissible">';
			echo '<div class="row">';
				echo '<div class="sppro-notice-left">';
					echo '<img src="'.splite_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image">';
				echo '</div>';
				echo '<div class="sppro-notice-right">';
					echo '<h4>'.esc_html__('Support Team Access','slick-popup').' - <span class="color">Slick Popup</span></h4>';
					echo '<p>'.esc_html__('Dear User, it has been ','slick-popup').'<strong>'.esc_html__('more than 14 days','slick-popup').'</strong>'.esc_html__(' since you have granted access to the Support Team. We advice you to click on the revoke access button.', 'slick-popup' ); 
					echo '<p>';
						if(!username_exists('slickpopupteam') && !email_exists('poke@slickpopup.com'))
							echo '<button class="button button-primary splite-ajax-btn" data-ajax-action="action_splite_support_access" data-todo="createuser">Grant Access <i class="fa fa-user"></i></button>';
						else
							echo '<button class="button button-primary splite-ajax-btn" data-ajax-action="action_splite_support_access" data-todo="deleteuser">Revoke Access <i class="fa fa-user"></i></button>';
						
						echo '<span class="splite-loader splite-loader-styles"><i class="fa fa-refresh fa-spin splite-loader-fa-styles"></i></span>';
					echo '</p>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}
add_action('admin_notices', 'splite_grant_access_alert');

?>