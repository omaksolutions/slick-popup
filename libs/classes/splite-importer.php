<?php 

/*
* splite_notice_dismissable
* Ajax action to do tasks on notice dismissable
* Require class: sp-dismissable
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
	
	$formId = splite_import_cf7_demo(array('title'=>$title));

	$form = get_page_by_title($title, 'OBJECT', 'wpcf7_contact_form');
	
	if($formId) {
		$edit_link = '<a target="_blank" href="'.admin_url('/admin.php?page=wpcf7&post='.$form->ID.'&action=edit').'"><strong>Edit Form</strong></a>';
		$global_options = '<a target="_blank" href="'.admin_url('/admin.php?page=slick-options').'"><strong>Set Popup</strong></a>';
		$ajaxy['reason'] = 'Imported.<br>'.$edit_link. ' - '.$global_options; 		
		wp_send_json_success($ajaxy); 
		wp_die(); 
	}
	
	$ajaxy['reason'] = 'Could not be imported.';
	wp_send_json_success($ajaxy); 
	wp_die(); 
}

function splite_import_cf7_demo($args=array()) {	
	
	if(!sizeof($args) or !isset($args['title']) or empty($args['title']) ) return false; 
	
	extract($args); 
	
	while( get_page_by_title($title, 'OBJECT', 'wpcf7_contact_form') ) {		
		$form = get_page_by_title($title, 'OBJECT', 'wpcf7_contact_form');
		$ajaxy['form_id'] = $form->ID;			
		$edit_link = '<a target="_blank" href="'.admin_url('/admin.php?page=wpcf7&post='.$form->ID.'&action=edit').'"><strong>Edit Form</strong></a>';
		$global_options = '<a target="_blank" href="'.admin_url('/admin.php?page=slick-options').'"><strong>Set Popup</strong></a>';
		$ajaxy['reason'] =  'Already exists.<br>'.$edit_link. ' - '.$global_options; 		
		//wp_send_json_error($form); 
		wp_send_json_error($ajaxy); 
		wp_die(); 
	}
	
	$contact_form = WPCF7_ContactForm::get_template( array(
		'title' => ucwords(str_replace('-', ' ',$title))
	));	
	
	$form = $contact_form->prop( 'form' );
	$mail = $contact_form->prop( 'mail' );
	$messages = $contact_form->prop( 'messages' );
	
	$messages['invalid_required'] = 'X'; 
	$messages['invalid_email'] = 'X'; 	
	$messages['invalid_number'] = 'X'; 	
	$mail['use_html'] = true;
	switch($title) {
		case 'basic-enquiry': 
			$form = '
<div class="spp-row">
	<div class="spp-left-col">[text* your-name placeholder "Full name"]</div>
	<div class="spp-right-col">[tel* your-phone placeholder "Phone"]</div>
	<div class="spp-clear"></div>
	<div class="spp-full-col">[email* your-email placeholder "Email"]</div>
	<div class="spp-full-col">[textarea your-message placeholder "Message"]</div>
	<div class="spp-clear"></div>
	[submit "SUBMIT"]
</div>';
			$mail['subject'] = "There is an enquiry from [your-name], [your-email].";

			$mail['from'] = "[your-name] <[your-email]>";

			$mail['body'] = "
Hello admin, 

A customer has put up an enquiry. Here are the details and content of the enquiry. 
<strong>Name:</strong> [your-name]
<strong>Email:</strong> [your-email]
<strong>Phone:</strong> [your-phone]

<strong>Message Body:</strong>
[your-message]


The admin is advised to go through the customer's enquiry and revert him soon.";
			break;
		
		case 'subscribe':
			$form = '
<div class="spp-row">
	<div class="spp-left-col">[text* first-name placeholder "First Name"]</div>
	<div class="spp-right-col">[text* last-name placeholder "Last Name"]</div>
	<div class="spp-clear"></div>
	<div class="spp-full-col">[email* your-email placeholder "Email"]</div>
	<div class="spp-clear"></div>
	[submit "SUBSCRIBE"]
</div>'; 

		$mail['subject'] = "[last-name], [first-name] has subscribed to your newsletter.";

		$mail['from'] = "[first-name] <[your-email]>";

			$mail['body'] = '
Hello admin

A customer has subscribed to your updates.
<strong>Name:</strong> [last-name], [first-name]
<strong>Email:</strong> [your-email]

The admin is advised to check the users profile.';
			break; 

		case 'unsubscribe':
			$form = '
<div class="spp-row">
	<div class="spp-full-col">[select* unsubscribe-reason "Unsubscribe Reason" "Too many emails" "Content irrelevant" "Didn’t know you were subscribing " "Too much or too little content"]</div>
	<div class="spp-full-col">[email* your-email placeholder "Email"]</div>
	<div class="spp-clear"></div>
	[submit "UNSUBSCRIBE"]
</div>';

			$mail['subject'] = "You have been unsubscribed from [your-email].";

			$mail['from'] = "<[your-email]>";
		
			$mail['body'] = '
Hello admin

A customer has unsubscribed to your updates.
<strong>Email:</strong> [your-email]
<strong>Reason: </strong> [unsubscribe-reason]

The admin is advised to check the users profile.';

			break;	
		
		case 'booking':
			$form = '
<div class="spp-row">
	<div class="spp-left-col">[text* your-name placeholder "Name*"]</div>
	<div class="spp-right-col">[email* your-email placeholder "Email*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">[tel* your-phone placeholder "Phone*"]</div>
	<div class="spp-right-col">[text* your-street placeholder "Street*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">[text* your-city placeholder "City*"]</div>
	<div class="spp-right-col">[text* your-state placeholder "State*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">[text* your-country placeholder "Country*"]</div>
	<div class="spp-right-col">[text* your-postalcode placeholder "Postal Code*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">Date of Arrival<br />[date* your-arrive placeholder "Arrive Date"]</div>
	<div class="spp-right-col">Occupants*<br />[select* your-occupents include_blank "1" "2" "3" "4" "5" "6" "7" "8" "9" "10"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">No. of Nights<br />[select*  your-nights include_blank "1" "2" "3" "4" "5" "6" "7" "8" "9" "10"]</div>
	<div class="spp-right-col">No. of Rooms<br />[select* your-rooms include_blank "1" "2" "3" "4" "5" "6" "7" "8" "9" "10"]</div>
	<div class="spp-clear"></div>
	<div style="display:block;">[textarea your-additionalInfo placeholder "Additional Info"]</div>
	[submit "BOOK NOW"]
</div>';

			$mail['subject'] = "There has been a booking from [your-name], [your-email].";

			$mail['from'] = "[your-name] <[your-email]>";
		
			$mail['body'] = '
Hello admin

A customer has put a booking request.<br>
<strong>Name:</strong> [your-name]
<strong>Email:</strong> [your-email]
<strong>Phone:</strong> [your-phone]
<strong>Street:</strong> [your-street]
<strong>City:</strong> [your-city]
<strong>State:</strong> [your-state]
<strong>Country:</strong> [your-country]
<strong>Postal code:</strong> [your-postalcode]
<strong>Arrival Date:</strong> [your-arrive]
<strong>No. of Occupents:</strong> [your-occupents]
<strong>No. of Nights:</strong> [your-nights]
<strong>No. of Rooms:</strong> [your-rooms]
<strong>Additional Info:</strong> [your-additionalInfo]

The admin is advised to check the following details.';

			break;	

		case 'get-a-quote':
			$form = '
<div class="spp-row">
	<div class="spp-left-col">[text* your-fname placeholder "First Name*"]</div>
	<div class="spp-right-col">[text* your-lname placeholder "Last Name*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">[email* your-email placeholder "Email*"]</div>
	<div class="spp-right-col">[text* your-city placeholder "City*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">[text* your-state placeholder "State*"]</div>
	<div class="spp-right-col">[text* your-country placeholder "Country*"]</div>
	<div class="spp-clear"></div>
	<div class="spp-left-col">Select estimated project due date<br />[date* your-estimate]</div>
	<div class="spp-right-col">Indicate urgency of your request*<br />[select* your-request include_blank "Low" "Normal" "High"]</div>
	<div class="spp-clear"></div>
	<div class="spp-full-col">Send me a price quotation for the following service:<br />[checkbox checkbox label_first "Installation‎"][checkbox checkbox label_first "Maintenance‎"]</div>
	<div class="spp-clear"></div>
	<div class="spp-full-col">Provide us any further information you think may be important:[textarea* your-Info]</div>
	[submit "Submit"]
</div>';

			$mail['subject'] = "There has been a Quote request from [your-fname], [your-email].";

			$mail['from'] = "[your-fname] <[your-email]>";
		
			$mail['body'] = '
Hello admin, 

A customer has contacted us. Here are the details and content of the request. 

<strong>First Name:</strong> [your-fname]
<strong>Last Name:</strong> [your-lname]
<strong>Email:</strong> [your-email]
<strong>City:</strong> [your-city]
<strong>State:</strong> [your-state]
<strong>Country:</strong> [your-country]
<strong>Estimate Project Date:</strong> [your-estimate]
<strong>Urgency Request:</strong> [your-request]
<strong>Service:</strong> [checkbox]
<strong>Further Info:</strong> [your-Info]

The admin is advised to go through the customers request and revert him back soon.';

			break;

		case 'survey':
			$form = '
<div class="spp-row">
	<strong style="font-style:italic">Please help us to serve you better by completing this survey. It should take around 5 minutes to complete.</strong>
	<div class="spp-left-col">[text* your-name placeholder "Full Name"]</div>
	<div class="spp-right-col">[email* your-email placeholder "Email"]</div>
	<div class="spp-clear"></div>
	<ol class="survey" type="I">
		<li> Overall, how satisfied are you with our product / service?<br /> [radio survey-satisfied "Very Satisfied" "Satisfied" "Neutral" "Unsatisfied" "Very Unsatisfied"] </li>
		<li> Would you recommend our product / service to others? <br />[radio survey-recommend "Definitely" "Probably" "Not Sure" "Probably Not" "Definitely Not"] </li>
		<li> How long have you used our product / service?<br /> [radio survey-timeofusage "Less than a month" "1-6 months" "1-3 years" "Over 3 Years" "Never used"] </li>
		<li> How often do you use our product / service? <br />[radio survey-useourproduct "Once a week" "2 to 3 times a month" "Once a month" "Less than once a month"] </li>
		<li> What aspect of the product / service were you most satisfied by?<br /> [radio survey-aspectoftheproduct "Quality" "Price" "Purchase Experience" "Usage Experience" "Customer Service"] </li>
		<li> Overall, the product / service met my expectations? <br />[radio survey-Overallofproduct "Strongly Agree" "Agree" "Neutral" "Disagree" "Strongly Disagree" "Dont Know"] </li>
		<li> Thinking of similar products / services offered by others, how would you compare the product / service offered by us? <br />[radio survey-similarproduct "Much Better" "Somewhat Better" "About the Same" "Somewhat Worse" "Much Worse" "Dont Know"] </li>
	</ol>
	<div class="spp-clear"></div>
	<div class="spp-full-col">What do you like about the product / service?[textarea survey-aboutproduct]</div>
	[submit "SUBMIT"]
</div>';

			$mail['subject'] = "[your-name] has filled out the survey form for you.";

			$mail['from'] = "[your-name] <[your-email]>";

			$mail['body'] = '
Hello admin, 

A customer Submit survey. Here are the details and content of the request. 

<strong>Full Name:</strong> [your-name]
<strong>Email:</strong> [your-email]
<strong>How Satisfied:</strong> [survey-satisfied]
<strong>Recommend:</strong> [survey-recommend]
<strong>Time of Usage:</strong> [survey-timeofusage]
<strong>Use Product:</strong> [survey-useourproduct]
<strong>Aspect of Product:</strong> [survey-aspectoftheproduct]
<strong>Overall Product:</strong> [survey-Overallofproduct]
<strong>Think Similar Product:</strong> [survey-similarproduct]


<strong>Message Body:</strong>
[survey-aboutproduct]';

			break;		

		default: 
			$form = '
<div class="spp-row">
	<h2 style="text-align:center;line-height:1.5em;margin-bottom:20px;">Happy to <strong>Help</h2>
	<div class="spp-left-col">[text* your-name placeholder "Name"]</div>
	<div class="spp-right-col">[email* your-email placeholder "Email"]</div>
	<div class="spp-full-col">[textarea your-message placeholder "Message"]</div>
	<div class="spp-clear"></div>
	[submit "SUBMIT"]
</div>';

			$mail['subject'] = "You have recieved a message from [your-name], [your-email].";

			$mail['from'] = "[your-name] <[your-email]>";

			$mail['body'] = "
Hello admin, 

A customer has contacted us. Here are the details and content of the request. 

<strong>Full Name:</strong> [full-name]
<strong>Email:</strong> [your-email]

<strong>Message Body:</strong>
[your-message]


The admin is advised to go through the customer's request and revert him back soon.

</div>";
	}
	
	//wp_die(print_r(array($title, $form, $mail), true)); 
	$contact_form->set_properties( array( 'mail' => $mail, 'form' => $form, 'messages' => $messages ) );
	
	$formId = $contact_form->save();	
	
	//global $sp_opts; $sp_opts['last_import'] = time(); 	
	update_option('splite_last_import', time());
	return $formId; 
}

?>