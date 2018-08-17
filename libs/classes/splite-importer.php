<?php 

/*
* splite_action_importDemo
* New import feature to setup individual Popups
*/
add_action( 'wp_ajax_splite_action_importDemo', 'splite_action_importDemo' );
function splite_action_importDemo() {
	
	$ajaxy = array(); 
	// If Nothing is posted through AJAX
	if( !isset($_POST) OR !isset($_POST['title']) ) 
		wp_send_json_error( 'Try again. Nothing Sent to server.' ); 
	
	$title = $_POST['title'];
	
	$formId = splite_import_cf7_demo(array('title'=>$title, $force=>$_POST['force']));
	
	if($formId) {
		$ajaxy['reason'] = '<a target="_blank" href="'.admin_url('/admin.php?page=wpcf7&post='.$form->ID.'&action=edit').'">'.$title.'</a>' . ' form imported.'; 		
		wp_send_json_success($ajaxy); 
		wp_die(); 
	}
	
	$ajaxy['reason'] = $title . ' could not be imported.';
	wp_send_json_success($ajaxy); 
	wp_die(); 
}

function splite_import_cf7_demo($args=array()) {	
	
	if(!sizeof($args) or !isset($args['title']) or empty($args['title']) ) return false; 
	
	extract($args); 
	
	while( get_page_by_title($title, 'OBJECT', 'wpcf7_contact_form') ) {		
		if($force=='true') {
			$title .= '-'.$id; 
		}
		else {
			$form = get_page_by_title($title, 'OBJECT', 'wpcf7_contact_form');
			$ajaxy['form_id'] = $form->ID;			
			$ajaxy['reason'] = '<a target="_blank" href="'.admin_url('/admin.php?page=wpcf7&post='.$form->ID.'&action=edit').'">'.$title.'</a> already exists.';
			//wp_send_json_error($form); 
			wp_send_json_error($ajaxy); 
			wp_die(); 
		}
	}
	
	$contact_form = WPCF7_ContactForm::get_template( array(
		'title' => $title,
	));	
	
	$form = $contact_form->prop( 'form' );
	$mail = $contact_form->prop( 'mail' );
	$messages = $contact_form->prop( 'messages' );
	
	$messages['invalid_required'] = 'X'; 
	$messages['invalid_email'] = 'X'; 	
	$messages['invalid_number'] = 'X'; 	
	
	switch($title) {
		case 'Basic Enquiry': 
			$form = '		
				<div class="col-md-6" style="overflow: hidden;">
					<h2 style="text-align:center;line-height:1.5em;margin-bottom:20px;">Basic <strong>Equiry</strong></h2>
					<p style="float: left;width: 100%; padding-right: 5px;">[text* your-name placeholder "Full name"]</p>
					<p style="float: left;width: 50%; padding-right: 5px">[email* your-email placeholder "Email"]</p>
					<p style="float: left;width: 50%; padding-left: 5px">[tel* your-phone placeholder "Phone"]</p>
					<p style="float: left;width: 100%; padding-right: 5px;">[textarea your-message placeholder "Message"]</p>
					<p style="clear: both;display: block;">[submit "SUBMIT"]</p>
				</div>';

			$mail['body'] = "Hello admin, 

				A customer has put up an enquiry. Here are the details and content of the enquiry. 
				<strong>Name:</strong> [your-name]
				<strong>Email:</strong> [your-email]
				<strong>Phone:</strong> [your-phone]

				<strong>Message Body:</strong>
				[your-message]


				The admin is advised to go through the customer's enquiry and revert him soon.";
				
			break;
		
		case 'Subscribe':
			$form = '
				<div class="col-md-6" style="overflow: hidden;">
					<h2 style="text-align:center;line-height:1.5em;margin-bottom:20px;"><strong>SUBSCRIBE</strong></h2>
					<p style="float: left;width: 50%;padding-right: 5px">[text* first-name placeholder "First Name"]</p>
					<p style="float: left;width: 50%;padding-left: 5px">[text* last-name placeholder "Last Name"]</p>
					<p style="float: left;width: 100%;padding: 5px;">[email* your-email placeholder "Email"]</p>
					<p style="clear: both;display: block;">[submit "SUBSCRIBE"]</p>
				</div>'; 

			$mail['body'] = 'Hello admin

			A customer has subscribed to your updates.
			<strong>Name:</strong> [first-name][last-name]
			<strong>Email:</strong> [your-email]

			The admin is advised to check the users profile.';

			break; 

		case 'Unsubscribe':
			$form = '
				<div class="col-md-6" style="overflow: hidden;">
					<h2 style="text-align:center;line-height:1.5em;margin-bottom:20px;"><strong>UNSUBSCRIBE</strong></h2>
					<p style="float: left;width: 50%;padding-right: 5px">[text* first-name placeholder "First Name"]</p>
					<p style="float: left;width: 50%;padding-left: 5px">[text* last-name placeholder "Last Name"]</p>
					<p style="float: left;width: 100%;padding: 5px;">[email* your-email placeholder "Email"]</p>
					<p style="clear: both;display: block;">[submit "UNSUBSCRIBE"]</p>
				</div>';

			$mail['body'] = 'Hello admin

			A customer has unsubscribed to your updates.
			<strong>Name:</strong> [first-name][last-name]
			<strong>Email:</strong> [your-email]

			The admin is advised to check the users profile.';

			break;	
		
		default: 
			$form = '
				<div class="" style="overflow:hidden;">
					<h2 style="text-align:center;line-height:1.5em;margin-bottom:20px;">Happy to <strong>Help</h2>
					<p style="float:left;width:50%;padding-right:5px;">
						[text* your-name placeholder "Name"]
						[email* your-email placeholder "Email"]
					</p>
					<p style="float:left;width:50%;padding-left:5px;">[textarea your-message placeholder "Message"]</p>
					<p>[submit "SUBMIT"]</p>
				</div>';
			$mail['body'] = "Hello admin, 

				A customer has contacted us. Here are the details and content of the request. 

				<strong>Full Name:</strong> [full-name]
				<strong>Email:</strong> [your-email]

				<strong>Message Body:</strong>
				[your-message]


				The admin is advised to go through the customer's request and revert him back soon.

				</div>";
	}
	
	$contact_form->set_properties( array( 'mail' => $mail, 'form' => $form, 'messages' => $messages ) );
	
	$formId = $contact_form->save();	
	
	//global $sp_opts; $sp_opts['last_import'] = time(); 	
	update_option('splite_last_import', time());
	return $formId; 
}

?>