<?php

// Building the admin interface

?>

<style>
	.wrap {
		margin: 30px auto 20px;
	}
	h2.page-header {
		padding:15px 10px;
		background:#333;
		background: #5A811D;
		border:1px solid #ccc;
		color:#efefef
	}
	.form-container {
		border:1px solid #ccc; padding:40px 20px;
	}
	.branding-link {
		float:right;
	}
	.branding-link, .branding-link a{
		color: #efefef;
		font-size: 0.6em;
		text-decoration: none;
	}
	.branding-link a:hover{
		color: #fff;
		text-decoration: underline;
		text-shadow: 0 0 1px #ccc;
	}
	.submit {
		text-align: center !important; 
	}
	#submit {
	  background: none repeat scroll 0 0 #5a811d;
	  border: 1px solid #39601c;
	  height: auto;
	  padding: 10px 20px;
	  width: auto;
	}
	.notice-bottom {
	  background-color: #5A811D;
	  padding: 10px 20px;
	  color: #efefef;
	  font-size: 14px;
	  border-radius: 10px;
	  border: 2px solid #406700;
	}
</style>

<?php 
	$default_description = 'Description of the form<br/>Default: Please fill our short form and one of our friendly team members will call you back.</u>';	
	$plugin_version = '1.2.6';
?>

<div class="wrap">
	<h2 class="page-header">
		Slick Popup Options - <?php echo $plugin_version; ?>
		<!--<span class="branding-link"><a href="http://www.omaksolutions.com" title="Om Ak Solutions">Om Ak Solutions</a></span>-->
		<span class="branding-link">Need Help? Skype Me: ak.singla47</span>
	</h2>
	
	<div class="form-container" style="">
		<form method="post" action="options.php">

			<?php settings_fields( 'mypopup-group' ); ?>
			<?php do_settings_sections( 'slick-options.php' ); ?> 
			
			<style>
				.form-table tr {
					border-right: 10px solid #333;
					border-top: 1px solid #ccc;
				}
				.form-table th {
				  background: #333;
				  text-align: center;
				  color: #bbb;
				}
				.help-box {
				  background: none repeat scroll 0 0 #ddd;
				  color: #333;
				  font-size: 11px !important;
				  font-weight: bold;
				  text-align: left;
				}
				.default-text {
					font-size: 12px;
					color: #777;
				}
			</style>
			
			<table class="form-table">

				<tr valign="top">
					<th scope="row">Theme Color</th>
					<td><input type="text" class="colorpicker-field" name="primary_color" data-default-color="#074C97" value="<?php echo get_option('primary_color'); ?>" /></td>
					<td class="help-box">Button and Form Header background color<br/><u>Default: #074C97</u></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Border Color</th>
					<td><input type="text" class="colorpicker-field" name="border_color" data-default-color="#276AB2" value="<?php echo get_option('border_color'); ?>" /></td>
					<td class="help-box">Form Border Color<br/><u>Default: #276AB2</u></td>
				</tr>
				
				<!--
				<tr valign="top" >
					<th scope="row">Contact Form 7 Shortcode</th>
					<td><input type="text" name="cf7_shortcode" value="<?php echo get_option('cf7_shortcode'); ?>" /></td>
					<td class="help-box"> class="help-box">>Default: [contact-form-7 id="142" title="Contact Page Form"]</td>
				</tr>
				-->
				
				<tr valign="top">
					<th scope="row">Contact Form 7 ID</th>
					<td><input type="text" name="cf7_id" value="<?php echo get_option('cf7_id'); ?>" /></td>
					<td class="help-box">Id of the "Contact Form 7" form.</br><u>Important</u></td>
				</tr>
				
				<!--
				<tr valign="top">
					<th scope="row">Contact Form 7 Title</th>
					<td><input type="text" name="cf7_title" value="<?php echo get_option('cf7_title'); ?>" /></td>
					<td class="help-box"><span style="font-weight:bold;"><u>Optional.</u></strgon> Form title as in CF7 backend, default: Submit Paper Form</td>
				</tr>
				-->

				<tr valign="top">
					<th scope="row">Form Title</th>
					<td><input type="text" name="form_title" value="<?php echo get_option('form_title'); ?>" /></td>
					<td class="help-box">Header text of the form<br/>Default: Contact Us - We care to help!</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Form Description</th>
					<td>
						<textarea placeholder="A couple lines before the form" name="form_description" style="width:100%;"><?php echo get_option('form_description'); ?></textarea>
					</td>
					<td class="help-box"><?php echo $default_description; ?></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Side Button Text</th>
					<td>						
						<?php 							
							if( get_option('side_image') == 'have-a-query' )
								$choice = 1; 
							elseif( get_option('side_image') == 'get-a-quote' )
								$choice = 2; 
							else
								$choice = 3; 
						?>
						<select name="side_image">
							<option value="have-a-query" <?php if($choice==1) echo 'selected'; ?> >Have a Query?</option>
							<option value="get-a-quote" <?php if($choice==2) echo 'selected'; ?>>Get a Quote</option>
							<option value="contact-us" <?php if($choice==3) echo 'selected'; ?>>Contact Us</option>
						</select>
					</td>
					<td class="help-box">Default Text: Have a query?</td>
				</tr>

			</table>
			
			<div style="display:block; margin: 15px 0;"></div>
			
			<?php 
				$icons = array( 'fb', 'twitter', 'gplus', 'linkedin', 'rss', 'website' ); ?>
			
			<table class="form-table" style="display:none;">

				<?php 
					foreach( $icons as $icon ) { 
						//echo key( $icon ) ); 
						switch ( $icon ) {
							case 'fb': $title = 'Facebook'; $default_text = 'Like us on Facebook'; break; 
							case 'twitter': $title = 'Twitter'; $default_text = 'Follow us on Twitter'; break; 
							case 'gplus': $title = 'Google Plus'; $default_text = 'Connect on Gplus'; break; 
							case 'linkedin': $title = 'LinkedIn'; $default_text = 'Know us on LinkedIn'; break; 
							case 'rss': $title = 'RSS'; $default_text = 'Subscribe to RSS'; break; 
							case 'website': $title = 'Website URL'; $default_text = 'Web Presence'; break; 
						}
				?>
						<tr valign="top">
							<th scope="row"><?php echo $title; ?></th>
							<td>
								<input type="text" name="<?php echo $icon. '_url'; ?>" value="<?php echo get_option( $icon. '_url'); ?>" />
								<br/><span class="default-text">URL with http://</span>
							</td>
							<td>
								<input type="text" name="<?php echo $icon. '_alt'; ?>" value="<?php echo get_option( $icon. '_alt'); ?>" />
								<br/><span class="default-text">Alt Text, Default: <?php echo $default_text; ?></span>
							</td>
						</tr>
				<?php } ?>

			</table>

			<?php submit_button(); ?>
			
			<br/><br/>
			<div class="notice-bottom">
				We are continuously trying to enhance our plugin with new features. If you need a feature, modfication or if you found a bug, 
				just shoot a message at my <strong>Skype: ak.singla47</strong>
			</div>
			
			<br/><br/>
			<div class="update">
				<h2>Are you tired of updating your plugins and themes, or core updates? </h2>
				<p>Try our <a href="http://www.wordpress.org/plugins/wp-automatic-updates" title="WP Automatic Updates WordPress Plugin">WP Automatic Updates</a> plugin.
			</div>

		</form>

	</div> 
</div> 