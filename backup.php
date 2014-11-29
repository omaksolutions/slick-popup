 <?php
 /*

**************************************************************************


**************************************************************************
*/


add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	// Page Title, Page Menu Name, Capability, 'Admin Page Unique Slug, Fallback 
	// http://codex.wordpress.org/Function_Reference/add_options_page
	add_options_page('Slick Popup Options', 'Slick Popup', 'manage_options', 'slick-options.php', 'my_plugin_page');
}

function enqueue_popup_scripts() {
	if ( !is_admin() ) {
		wp_register_style( 'popup-css', plugins_url( '/css.css', __FILE__ ) );
		wp_enqueue_style( 'popup-css' ); 
		
		wp_register_script( 'popup-js', plugins_url( '/custom.js', __FILE__ ) );
		wp_enqueue_script( 'popup-js' ); 
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_popup_scripts' );s

function add_my_popup() {

	$form_title = 'Please fill our short form and one of our friendly team members will call you back.';
	
	?>
	<!-- Pop Up Box and Curtain Arrangement -->
		<div id="curtain" onClick="unloadPopupBox();" style=""></div>
		<div id="popup_box">  
			<div class="enquiry-title">
				<?php echo $form_title; ?>
			</div>
			<p class="enquiry-description">
				Please fill our short form and one of our friendly team members will call you back.
			</p>
			
			<div class="form-container">
				<?php
					echo do_shortcode('[contact-form-7 id="142" title="Contact Page Form"]'); 
				?>
			</div>
			<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
		   <a id="popupBoxClose" onClick="unloadPopupBox();">X</a>  
		</div>
		<div  class="side-enquiry-holder">
			<a onClick="loadPopupBox();" class="side-enquiry">Help</a>
		</div>
		<!-- Pop Up Box and Curtain Arrangement -->
<?php
}
add_action('wp_footer', 'add_my_popup');

function option_css() {
	$theme_color = '#074C97';
	$border_color = '#276AB2';
	?>
	
	<?php if( !is_admin() ) { ?>
		<style>
			#popup_box .wpcf7-form-control.wpcf7-submit,
			.side-enquiry-holder, .enquiry-title {
				background-color: <?php echo $theme_color; ?>;
			}
			#popup_box {
				border: 3px solid <?php echo $border_color; ?>;
			}
		</style>
<?php	
	}
}
add_action( 'wp_head', 'option_css' ); 
?>