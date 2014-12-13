<?php
 /*
=== [Slick Popup] ===

Plugin Name:  Slick Popup
Plugin URI:   http://www.omaksolutions.com
Description:  Beautiful, Slick and customizable pop-up compatible with Contact Form 7.
Author URI:   http://www.omaksolutions.com 
Author:       Om Ak Solutions 
 Version:      0.1
*/

//include 'other-options.php';

add_action( 'admin_bar_menu', 'toolbar_link_to_slick_popup', 999 );
function toolbar_link_to_slick_popup( $wp_admin_bar ) {
	if ( current_user_can('manage_options') ) {
		$args = array(
			'id'    => 'slick_popup',
			'title' => 'Slick Popup',
			'href'  => admin_url('admin.php?page=slick-options.php'),
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );
	}
}

//SETUP
register_activation_hook(__FILE__,'mypopup_install'); 
function mypopup_install(){
	// Empty Activation Hook
}


/////////////////////////////////////////
// Initialise the plugin and scripts
/////////////////////////////////////////
add_action('init','slick_popup_init');
function slick_popup_init(){
    add_html_and_scripts();
}

/////////////////////////////////////////
// Add Html And Scripts
/////////////////////////////////////////
function add_html_and_scripts(){
	// Add Pop Up to Footer Here
	add_action('wp_footer', 'add_my_popup');
	add_action('wp_footer', 'option_css');
	add_action( 'wp_enqueue_scripts', 'enqueue_popup_scripts' );	
}


// Create Menu Page
add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	
	// Page Title, Page Menu Name, Capability, 'Admin Page Unique Slug, Fallback 
	// http://codex.wordpress.org/Function_Reference/add_options_page
	add_menu_page('Slick Popup Options', 'Slick Popup', 'manage_options', 'slick-options.php', 'my_plugin_options');
	
	//call register settings function
	add_action( 'admin_init', 'register_settings' ); 
}

// Tell whih is our admin page
function my_plugin_options(){
     include('admin/slick-options.php');
} 

// Tell what are our options
function register_settings() {

	add_settings_section(
		'layout_section',
		'Layout Settings',
		'layout_section_callback_function',
		'layout'
	);
	
	$fields = array(
		array( 'id'=>'primary_color', 'title'=>'Primary Color', 'callback'=>'', 'menupage'=>'slick-options.php', 'section'=>'default' )
	);
		//register our settings
	register_setting( 'mypopup-group', 'primary_color' );
	register_setting( 'mypopup-group', 'border_color' );
	
	register_setting( 'mypopup-group', 'form_title' );
	register_setting( 'mypopup-group', 'form_description' );
	
	register_setting( 'mypopup-group', 'cf7_shortcode' );
	register_setting( 'mypopup-group', 'cf7_id' );
	register_setting( 'mypopup-group', 'cf7_title' );
	
	register_setting( 'mypopup-group', 'side_image' );
	
	
	
	$icons = array( 'fb', 'twitter', 'gplus', 'linkedin', 'rss', 'website' );
	
	foreach( $icons as $icon ) {
		register_setting( 'mypopup-group', $icon. '_url' );
		register_setting( 'mypopup-group', $icon. '_alt' );
	}
	
} 


function add_my_popup() {
	if( !is_admin() ) {
		$cf7_code = get_option('cf7_shortcode')?get_option('cf7_shortcode'):'[contact-form-7 id="142" title="Contact Page Form"]';
		$cf7_id = get_option('cf7_id')?get_option('cf7_id'):'1';
		$cf7_title = get_option('cf7_title')?get_option('cf7_title'):'Submit Paper Form';
		
		$form_title = get_option('form_title')?get_option('form_title'):'Contact Us - We care to help!';
		$form_description = get_option('form_title')?get_option('form_description'):'Please fill our short form and one of our friendly team members will call you back.';
		$side_image = get_option('side_image')?get_option('side_image'):'have-a-query';		
		?>
		<!-- Pop Up Box and Curtain Arrangement -->
			<div id="curtain" onClick="unloadPopupBox();" style=""></div>
			<div id="popup_box">  
				<div id="popup_title">
					<?php echo $form_title; ?>
				</div>
				<div id="form_container">
					<p id="popup_description">
						<?php echo $form_description; ?>
					</p>
					
					<?php
						//echo $cf7_code; 
						echo do_shortcode( '[contact-form-7 id="' .$cf7_id. '" title="' .$cf7_title. '"]'); 
					?>
				</div>
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
			   <a id="popupBoxClose" onClick="unloadPopupBox();">X</a>  
			</div>
			<div  class="side-enquiry-holder holder-<?php echo $side_image; ?>" >
				<a onClick="loadPopupBox();" class="side-enquiry <?php echo $side_image; ?>">Have a query?</a>
			</div>
			<!-- Pop Up Box and Curtain Arrangement -->
<?php
	}
}

function option_css() {
	
	$primary_color = get_option('primary_color')?get_option('primary_color'):'#074C97';
	$border_color = get_option('border_color')?get_option('border_color'):'#276AB2';
	
	?>
	
	<?php if( !is_admin() ) { ?>
		<style>
			#popup_box .wpcf7-form-control.wpcf7-submit,
			.side-enquiry-holder, #popup_title {
				background: <?php echo $primary_color; ?>;
			}
			#popup_box {
				border: 3px solid <?php echo $border_color; ?>;
			}
		</style>
<?php	
	}
}

function enqueue_popup_scripts() {
	if ( !is_admin() ) {
		wp_register_style( 'popup-css', plugins_url( '/css.css', __FILE__ ) );
		wp_enqueue_style( 'popup-css' ); 
		
		wp_register_script( 'popup-js', plugins_url( '/custom.js', __FILE__ ) );
		wp_enqueue_script( 'popup-js' ); 
	}
}



function create_share_button() {

	$atts = shortcode_atts( array(
		'fb' => array(
			'url' => 'http://www.facebook.com/OmAkSolutions',
			'alt' => 'Like us on Facebook',
			'class' => 'fb',
		),
		'twitter' => array(
			'url' => 'https://twitter.com/SinglaAk',
			'alt' => 'Follow us on Twitter',
			'class' => 'twitter',
		),
		'gplus' => array(
			'url' => 'http://in.linkedin.com/pub/ankit-singla/62/647/4b4',
			'alt' => 'Connect on Gplus',
			'class' => 'gplus',
		),
		'linkedin' => array(
			'url' => 'http://in.linkedin.com/pub/ankit-singla/62/647/4b4',
			'alt' => 'Know us on LinkedIn',
			'class' => 'linkedin',
		),
		'rss' => array(
			'url' => 'http://in.linkedin.com/pub/ankit-singla/62/647/4b4',
			'alt' => 'Subscribe to RSS',
			'class' => 'rss',
		),
		'website' => array(
			'url' => 'http://in.linkedin.com/pub/ankit-singla/62/647/4b4',
			'alt' => 'Web Presence',
			'class' => 'website',
		)
	), $atts );
	
	 
	 // Get Values From Options Page
	$icons = array( 'fb', 'twitter', 'gplus', 'linkedin', 'rss', 'website' );
		foreach( $icons as $icon ) {
			$atts[$icon]['url'] = get_option($icon. '_url') ? get_option($icon. '_url') : '';
			//$atts[$icon]['alt'] = get_option($icon. '_alt') ? get_option($icon. '_alt') : '';
		}
	
	$output = ''; 
	$is_True = True;
	
	//$output .= '<br/>KeyAtts: ' .key($atts);
	if( 0 ) {
		foreach( $atts as $attri ) {
			if( isset($attri['url']) AND ! empty($attri['url']) ) {
				$output .= '<br/>Key: ' .key($attri). ' URL: ' .$attri['url']. ' Alt: ' .$attri['alt'];
			}
		}
	}
	
	$output .= '<ul class="omaksociallinks">';
	
	foreach( $atts as $attri ) {
		
		if( isset($attri['url']) AND ! empty($attri['url']) ) {
			
			if( $attri['class'] == 'website' )
				$target_window = ''; 
			else 
				$target_window = ' target="_blank" '; 
				
			$output .= '<li class="' .$attri['class']. '">';
				$output .= '<a href="' .$attri['url']. '" title="' .$attri['alt']. '"' .$target_window. '>';
					//$output .= $attri['alt'];
					$output .= 'A';
				$output .=  '</a>';
			$output .= '</li>';
			$output .= '<!-- icon icon-' .$attri['class']. '-->';
		}
	}
	
	$output .= '</ul>';
	/*
	$output .= '<div class="omaksharer">';
		$output .= '<a onclick="' .$onClick. '" href="http://www.facebook.com/sharer/sharer.php?u=' .get_the_permalink(). '&t=' .get_the_title(). '" class="fb-share" id="onclick-popup">';
			$output .= '<img class="fb-icon" src="' .plugins_url( 'images/fb-icon.png', __FILE__ ). '" style="border:0 !important;">';
			$output .= '<span class="share-text">' .$atts['button_text']. '</span>';
			$output .= '<span id="fb-share-count" style="">';
				$output .= '<img src="' .plugins_url( 'images/arrow-white.png', __FILE__ ). '" style="border:0 !important;">';
				$output .= $fb_data;
			$output .= '</span>';
		$output .= '</a>';
	$output .= '</div>';
	*/
	return $output;
}
add_shortcode( 'OmakSocialLinks', 'create_share_button' ); 

?>