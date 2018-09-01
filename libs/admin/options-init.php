<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
	if ( ! class_exists( 'Redux' ) ) {
	  // Delete tgmpa dissmiss flag
	  delete_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_myarcadetheme' );
	  return;
	}

	/** remove redux menu under the tools **/
	function splite_remove_redux_menu() {
	  remove_submenu_page('tools.php','redux-about');
	}
	add_action( 'admin_menu', 'splite_remove_redux_menu', 12 );

	// Deactivate News Flash
	$GLOBALS['redux_notice_check'] = 0;

    // This is your option name where all the Redux data is stored.
    $opt_name = "splite_opts";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); //For use with some settings. Not necessary.
	require_once(ABSPATH.'wp-admin/includes/plugin.php');	
	$plugin = get_plugin_data( plugin_dir_path( __FILE__ ) .'../../slick-popup.php' );

    $args = array(
        'opt_name' => 'splite_opts',
        'dev_mode' => false,
		'ajax_save' => true,
		'allow_tracking' => false,
		'tour' => false,  
        'use_cdn' => true,
        'display_name' => $plugin['Name'] . ' Lite',
        'display_version' => $plugin['Version'],
        'page_slug' => 'slick-options',
        'page_title' => $plugin['Name'] . ' Options',
        'intro_text' => $plugin['Description'],
        'footer_text' => __('We will continue to innovate new features, if you have a suggestion just let us know.', 'sp-lite-txt-domain' ),
        'admin_bar' => false,
		//'page_parent' => 'sp-lite',
        'menu_type' => 'menu',
        'menu_icon' => plugins_url( 'img/menu_icon.png', __FILE__ ),
        'menu_title' => 'Slick Popup Lite',
        'allow_sub_menu' => false,
        'page_parent_post_type' => '',
        'default_show' => TRUE,
        'default_mark' => '*',
        'google_api_key' => 'AIzaSyB8QWjiiDqvVuTgOP1F394771EHteUu2CU',
        'class' => 'splite_container',
		
        'hints' => array(
            'icon' => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color' => '#23282D',
			'icon_size' => 'normal',
            'tip_style' => array(
				'color'   => 'red',
				'shadow'  => true,
				'rounded' => false,
				'style'   => 'cluetip',
			),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect'   => 'fade',
					'duration' => '50',
					'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'fade',
					'duration' => '50',
					'event'    => 'click mouseleave',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => FALSE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
        'hide_reset' => TRUE,
		'footer_credit' => 'Slick Popup Lite by <a href="http://www.slickpopup.com/">Om Ak Solutions</a>',
    );

    
    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'] = array(); 
	$args['admin_bar_links'][] = array(
        'id'    => 'sp-demo',
        'href'  => 'http://www.slick-popup.omaksolutions.com/',
        'title' => __( 'Demo', 'sp-lite-txt-domain' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'sp-support',
        'href'  => 'https://wordpress.org/support/plugin/slick-popup',
        'title' => __( 'Support', 'sp-lite-txt-domain' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'sp-docs',
        'href'  => 'http://www.slick-popup.omaksolutions.com/docs',
        'title' => __( 'Documentation', 'sp-lite-txt-domain' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'] = array(); 
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/OmAkSolutions',
        'title' => __('Like us on Facebook', 'sp-lite-txt-domain' ),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/OmAkSolutions',
        'title' => __('Follow us on Twitter', 'sp-lite-txt-domain' ),
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/Om-Ak-Solutions',
        'title' => __('Find us on LinkedIn', 'sp-lite-txt-domain' ),
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '', 'sp-lite-txt-domain' ), $v );
    } else {
        $args['intro_text'] = __( '', 'sp-lite-txt-domain' );
    }
	
	// Intro Text Emptied
	$args['intro_text'] = sprintf( __( '', 'sp-lite-txt-domain' ), $v );	
	
    // Add content after the form.
    $args['footer_text'] = __( '<p>We will continue to innovate new features, if you have a suggestion just let us know at <strong>poke@slickpopup.com</strong></p>', 'sp-lite-txt-domain' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'sp-pro-helptab-1',
            'title'   => __( 'Support', 'sp-lite-txt-domain' ),
            'content' => __( '<p>If you face any issues using the plugin, please shoot us an e-mail at: poke@slickpopup.com</p>', 'sp-lite-txt-domain' )
        ),
		array(
            'id'      => 'sp-pro-helptab-2',
            'title'   => __( 'Support', 'sp-lite-txt-domain' ),
            'content' => __( '<p>If you face any issues using the plugin, please shoot us an e-mail at: poke@slickpopup.com</p>', 'sp-lite-txt-domain' )
        ),
    );
	unset( $tabs[1] );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p><strong>We are mostly online at Skype: ak.singla47</strong></p>', 'sp-lite-txt-domain' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
	
	/////////////////////////////////////////////////
	// SECTION: Configuration
	/////////////////////////////////////////////////
	if ( 1 ) {
    	Redux::setSection( $opt_name, array(
			'title'  => __( 'Configuration', 'sp-lite-txt-domain' ),
			'id'     => 'configuration-settings',
			'desc'   => __( '', 'sp-lite-txt-domain' ),
			'icon'   => 'el el-cog',
			'fields' => array(						
				array(
					'id'       => 'plugin_state',
					'type'     => 'switch',
					'title'    => __( 'Plugin State', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'The power switch.', 'sp-lite-txt-domain' ),
					'default'  => 1,
					'on'       => __('Enable', 'sp-lite-txt-domain' ),
					'off'      => __('Disable', 'sp-lite-txt-domain' ),
				),
				array(
					'id'            => 'form-id',
					'type'          => 'select',
					'data' 			=> 'posts',
                    'args' 			=> array('post_type' => array('wpcf7_contact_form'), 'posts_per_page' => -1),
					'required' 		=> array( 'plugin_state', '=', '1' ),
					'title'         => __( 'Form to use?', 'sp-lite-txt-domain' ),
					'subtitle'      => __( '<span style="color:red;font-weight:bold;display:inline;">IMPORTANT!</span><br/>Choose the Contact Form 7 form to be used in the popup.', 'sp-lite-txt-domain' ),
					'desc'          => __( '<a target="_blank" href="', 'sp-lite-txt-domain' ) .admin_url( '/admin.php?page=wpcf7' ). __( '">See all Contact Forms</a>', 'sp-lite-txt-domain' ),
				),		
				array(
					'id'            => 'where_to_show',
					'type'          => 'select',
					'required' 		=> array( 'plugin_state', '=', '1' ),
					'title'         => __( 'Where to show the form?', 'sp-lite-txt-domain' ),
					'subtitle'      => __( 'Choose the display of the popup form.', 'sp-lite-txt-domain' ),
					'desc'          => __( '', 'sp-lite-txt-domain' ),
					'options'  => array(
								'everywhere' => 'Everywhere',
								'onselected' => 'Only Selected Pages',
								'notonselected' => __('Not On Selected Pages', 'sp-lite-txt-domain' ),
							),
					'default'  => 'everywhere'
				),
				array(
					'id'            => 'choose_pages',
					'type'          => 'select',
					'multi'          => true,
					'data' 			=> 'pages',
                    'args' 			=> array( 'posts_per_page' => -1),
					'required' 		=> array( array('plugin_state', '=', '1'), array('where_to_show', '!=', 'everywhere') ),
					'title'         => __( 'Choose Your Pages', 'sp-lite-txt-domain' ),
					'subtitle'      => __( 'Select the pages to exclude or include for popup form display.', 'sp-lite-txt-domain' ),
					'desc'          => __( '<a target="_blank" href="', 'sp-lite-txt-domain' ) .admin_url( '/edit.php?post_type=page' ). __( '">See all Pages</a>', 'sp-lite-txt-domain' ),
				),				
				array(
					'id'       => 'plugin_state_on_mobile',
					'type'     => 'switch',
					'required' => array( 'plugin_state', '=', '1' ),
					'title'    => __( 'Mobile State', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Enable/Disable on Mobile View.', 'sp-lite-txt-domain' ),
					'default' => __( '<b>Default:</b> Enable', 'sp-lite-txt-domain' ),
					'default'  => 1,
					'on'       => __('Enable', 'sp-lite-txt-domain' ),
					'off'      => __('Disable', 'sp-lite-txt-domain' ),
					'hint'     => array(
						'title'     => 'Mobile State',
						'content'   => 'Disable - will complete switch off all functionality of the plugin on the front-end.',
					),
				),					
				array(
					'id'       => 'delete_data',
					'type'     => 'switch',
					'required' => array( 'plugin_state', '=', '1' ),
					'title'    => __( 'Keep Settings', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Keep/Delete plugin settings after uninstallation.', 'sp-lite-txt-domain' ),
					'default'  => 0,
					'on'       => __('Delete', 'sp-lite-txt-domain' ),
					'off'      => __('Keep', 'sp-lite-txt-domain' ),
					'hint'     => array(
						'title'     => __('Keep Settings', 'sp-lite-txt-domain' ),
						'content'   => __('Choose <b>Keep</b> if you do not plan to copmletely remove the plugin settings after uninstallation.', 'sp-lite-txt-domain' ),
					),
				),		
			)
		) );
	} // endif 1

	/////////////////////////////////////////////////
	// SECTION: Popup Styles
	/////////////////////////////////////////////////
	if ( 1 ) {
		Redux::setSection( $opt_name, array(
			'title' => __( 'Popup Styles', 'sp-lite-txt-domain' ),
			'id'    => 'popup-styles',
			'desc'  => __( '', 'sp-lite-txt-domain' ),
			'icon'  => 'el el-comment',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Layout & Color Scheme (layout)
				////////////////////////////////////////////////
					array(
						'id'       => 'section-layout',
						'type'     => 'section',				
						'title'    => __( 'Layout & Color Scheme', 'sp-lite-txt-domain' ),
						'subtitle' => __( 'Choose your desired layout and color scheme.', 'sp-lite-txt-domain' ),
						'indent'   => true, // Indent all options below until the next 'section' option is set.
					),
						array(
							'id'       => 'choose-layout',
							//'type'     => 'select',
							'type'     => 'image_select',
							'title'    => __( 'Choose Layout', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Choose one of the three layouts available.', 'sp-lite-txt-domain' ),
							'desc'     => __( '', 'sp-lite-txt-domain' ),
							//Must provide key => value pairs for select options
							'options'  => array(								
								'centered' => array(
									'alt' => __('Centered Layout', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/layout-centered.png',
									'title' => __('Centered', 'sp-lite-txt-domain' ),
								),
								'full' => array(
									'alt' => __('Full Height', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/layout-full.png',
									'title' => __('Full Height', 'sp-lite-txt-domain' ),
								),
							),
							'default'  => 'centered',
							'hint'     => array(
								'title'     => __('Choose Layout', 'sp-lite-txt-domain' ),
								'content'   => __('Currently two layouts available: <b>Full</b> and <b>Centered</b>. Full means full height popup in the center of the screen, and Centered has some space above and below the popup.', 'sp-lite-txt-domain' ),
							),
						),
						array(
							'id'       => 'popup-corners',
							'type'     => 'select',
							'title'    => __( 'Popup Corners', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Choose the radius of the popup border.', 'sp-lite-txt-domain' ),
							'desc'     => __( '<b>Default:</b> Square (Zero roundness)', 'sp-lite-txt-domain' ),
							//Must provide key => value pairs for select options
							'options'  => array(
								'square' => 'Square',					
								'rounded' => 'Rounded',
								'custom' => __('Set Your Own', 'sp-lite-txt-domain' ),
							),
							'default'  => 'square'
						),		
							array(
								'id'       => 'custom-popup-border',
								'type'           => 'dimensions',             
								'required' => array( 'popup-corners', '=', 'custom' ),
								'output'   => array( '' ),
								'units'          => array( 'px', '%' ),    // You can specify a unit value. Possible: px, em, %
								'units_extended' => 'true',  // Allow users to select any type of unit
								'title'          => __( 'Popup Border Radius', 'sp-lite-txt-domain' ),
								'subtitle'       => __( 'Set a border radius property for the popup.', 'sp-lite-txt-domain' ),
								'desc'           => __( 'Units: px or % (50% is max).', 'sp-lite-txt-domain' ),
								'height'         => false,
								'default'        => array(
									'width'  => 20,
									'height' => 100,
								)
							),	
						array(
							'id'       => 'choose-color-scheme',
							'type'     => 'image_select',
							'title'    => __( 'Color Scheme', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Choose your desired cover scheme.', 'sp-lite-txt-domain' ),
							'desc'     => __( '<span style="font-weight:bold;font-size:1.1em;">Choose one of our pre-defined color schemes or set your own. <a href="https://codecanyon.net/item/slick-popup-pro-/16115931?ref=OmAkSols">More in Pro</a></span>', 'sp-lite-txt-domain' ),
							'options'  => array(
								'master_red' => array(
									'alt' => __('Master Red', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-master-red.png',
									'title' => __('Master Red', 'sp-lite-txt-domain' ),
								),
								'creamy_orange' => array(
									'alt' => __('Creamy Orange', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-creamy-orange.png',
									'title' => __('Creamy Orange', 'sp-lite-txt-domain' ),
								),
								'light_blue' => array(
									'alt' => __('Light Blue', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-light-blue.png',
									'title' => __('Light Blue', 'sp-lite-txt-domain' ),
								),
								'cool_green' => array(
									'alt' => __('Cool Green', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-cool-green.png',
									'title' => __('Cool Green', 'sp-lite-txt-domain' ),
								),						
								'dark' => array(
									'alt' => __('Classic Grey', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-classic-grey.png',
									'title' => __('Classic Grey', 'sp-lite-txt-domain' ),
								),
								'custom_theme' => array(
									'alt' => __('Set Your Own', 'sp-lite-txt-domain' ),
									'img' => SPLITE_PLUGIN_IMG_URL . '/scheme-custom-theme.png',
									'title' => __('Set Your Own', 'sp-lite-txt-domain' ),
								),
							),
							'default'  => 'cool_green'
						),
							// If Color Scheme = custom_theme
							array(
								'id'       => 'custom-theme-color',
								'type'     => 'color',
								'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
								'output'   => array( '' ),
								'title'    => __( 'Your Theme Color', 'sp-lite-txt-domain' ),
								'subtitle' => __( 'Pick a color for theme of your popup.', 'sp-lite-txt-domain' ),
								'desc' => __( 'This color will be used to create theme of your popup.', 'sp-lite-txt-domain' ),
								'default'  => '#333',
							),
							array(
								'id'       => 'custom-form-background-color',
								'type'     => 'background',
								'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
								'output'   => array( '' ),
								'title'    => __( 'Form Background', 'sp-lite-txt-domain' ),
								'subtitle' => __( 'Pick a color for form background.', 'sp-lite-txt-domain' ),
								'desc' => __( 'This color will be used as the popup form background.', 'sp-lite-txt-domain' ),
								'default'  => array(
									'background-color' => '#EFEFEF',
									'background-image' => '',
									'background-size' => 'cover',
									'background-position' => 'center center',
									'background-attachment' => '',
									'background-repeat' => 'no-repeat',
								),
							),							
							array(
								'id'       => 'custom-text-color',
								'type'     => 'color',
								'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
								'output'   => array( '' ),
								'title'    => __( 'Your Text Color', 'sp-lite-txt-domain' ),
								'subtitle' => __( 'Pick a color for any text element added in form.', 'sp-lite-txt-domain' ),
								'desc' => __( 'This also applies to <strong>Close Icon "X"</strong> and <strong>form submission response.</strong>', 'sp-lite-txt-domain' ),
								'default'  => '#EFEFEF',
							),
					
				/////////////////////////////////////////////////
				// Section: Heading & Description 
				////////////////////////////////////////////////
				array(
						'id'       => 'section-heading-description',
						'type'     => 'section',
						'title'    => __( 'Heading & Description', 'sp-lite-txt-domain' ),
						'subtitle' => __( 'Choose your desired heading and description settings.', 'sp-lite-txt-domain' ),
						'indent'   => true, // Indent all options below until the next 'section' option is set.
					),
						array(
							'id'       => 'popup-heading',
							'type'     => 'text',
							'title'    => __( 'Heading', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Main heading on the popup.', 'sp-lite-txt-domain' ),
							'desc'     => __( '<b>Default:</b> STILL NOT SURE WHAT TO DO?', 'sp-lite-txt-domain' ),
							'default'  => 'STILL NOT SURE WHAT TO DO?',
							'hint'      => array(
								'title'     => 'Popup Heading',
								'content'   => 'Main heading of the popup.',
							),
						),
						array(
							'id'       => 'popup-cta-text',
							'type'     => 'editor',
							'title'    => __( 'Call To Action', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Main description that will actually make your visitor to fill up the form.', 'sp-lite-txt-domain' ),
							'desc'     => __( '<b>Default:</b> We are glad that you preferred to contact us. Please fill our short form and one of our friendly team members will contact you back shortly.', 'sp-lite-txt-domain' ),
							'default'  => 'We are glad that you preferred to contact us. Please fill our short form and one of our friendly team members will contact you back.',
							'hint'      => array(
								'title'     => 'Call To Action',
								'content'   => 'This text will appear above the form. Choose something that encourages user to fill up the form.',
							),
						),
					
				////////////////////////////////////
				// Section: Submit Button
				////////////////////////////////////
				array(
						'id'       => 'section-submit-button',
						'type'     => 'section',
						'title'    => __( 'Submit Button', 'sp-lite-txt-domain' ),
						'subtitle' => __( 'Choose your desired settings for Submit Button.', 'sp-lite-txt-domain' ),
						'indent'   => true, // Indent all options below until the next 'section' option is set.
					),
					array(
						'id'       => 'choose-submit-button',
						'type'     => 'select',
						'title'    => __( 'Submit Button Styles', 'sp-lite-txt-domain' ),
						'subtitle' => __( 'Choose appearance of the form <b>Submit</b> button.', 'sp-lite-txt-domain' ),
						'desc'     => __( '<b>Default:</b> Inherit', 'sp-lite-txt-domain' ),
						//Must provide key => value pairs for select options
						'options'  => array(
							'inherit_from_theme' => __('Use styles from theme', 'sp-lite-txt-domain' ),
							'inherit_from_color_scheme' => __('Inherit from color scheme', 'sp-lite-txt-domain' ),
							'custom' => __('Set your own colors', 'sp-lite-txt-domain' ),
						),
						'default'  => 'inherit_from_color_scheme',
						'hint'      => array(
							'title'     => __( 'Color Scheme', 'sp-lite-txt-domain' ),
							'content'   =>  __( 'Choose one of the pre-packed color themes or create your own.', 'sp-lite-txt-domain' ),
						),
					),	
						// If choose-submit-button = custom
						array(
							'id'       => 'submit-button-background',
							'type'     => 'background',
							'required' => array( 'choose-submit-button', '=', 'custom' ),
							'output'   => array( '' ),
							'title'    => __( 'Button Background', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Choose background color for the "Submit" button.', 'sp-lite-txt-domain' ),
							'default'   => array( 
								'background-color' => '#333333',
							),
							'background-color'			=> true,
							'background-repeat'			=> false,
							'background-attachment'		=> false,
							'background-position'		=> false,
							'background-image'			=> false,
							'background-clip'			=> false,
							'background-origin'			=> false,
							'background-size'			=> false,
							'preview_media'				=> false,
							'preview'					=> false,
							'preview_height'			=> false,
							'transparent'				=> false,
						),			
						/* Hidden since 1.4.0 - was not being used
						array(
							'id'       => 'submit-button-border',
							'type'     => 'border',
							'required' => array( 'choose-submit-button', '=', 'custom' ),
							'title'    => __( 'Button Border', 'sp-lite-txt-domain' ),
							'subtitle' => __( 'Set properties for the submit button border.', 'sp-lite-txt-domain' ),
							'output'   => array( '' ),
							'all'      => false,
							// An array of CSS selectors to apply this font style to
							'desc'     => __( '', 'sp-lite-txt-domain' ),
							'default'  => array(
								'border-color'  => '#f5f5f5',
								'border-style'  => 'solid',
								'border-top'    => '2px',
								'border-right'  => '2px',
								'border-bottom' => '2px',
								'border-left'   => '2px'
							)
						),
						*/
				) // end fields array
			)
		);
	}
	
	
	/////////////////////////////////////////////////
	// SECTION: Popup Animations
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Popup Effects', 'sp-lite-txt-domain' ),
			'id'    => 'loader-popup-effects',
			'desc'  => __( 'Control the popup activation mode and animation effects.', 'sp-lite-txt-domain' ),
			'icon'  => 'el el-iphone-home',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Activation Mode
				////////////////////////////////////////////////				
				array(
					'id'       => 'section-activation-mode',
					'type'     => 'section',				
					'title'    => __( 'Activation Mode', 'sp-lite-txt-domain' ),
					'subtitle' => __( '', 'sp-lite-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'            => 'activation_mode',
						'type'          => 'select',
						'title'         => __( 'How to activate popup?', 'sp-lite-txt-domain' ),
						'subtitle'      => __( 'Choose how the popup should activate.', 'sp-lite-txt-domain' ),
						'desc'          => __( '4 modes: On-click, Auto Popup, On-scroll Popup, On-exit Popup', 'sp-lite-txt-domain' ),
						'options'  => array(
									'manually' => __('On-Click (Default)', 'sp-lite-txt-domain' ),
								),
						'default'  => 'manually'
					),
					array(
						'id'     => 'notice-splite-activation-mode',
						'type'   => 'info',
						'style'   => 'warning',
						'notice' => false,
						'desc'   => __( '<span style="font-weight:bold;font-size:1.1em;">More features in Slick Popup Pro. <a href="https://codecanyon.net/item/slick-popup-pro-/16115931?ref=OmAkSols">Buy Now</a></span>', 'sp-lite-txt-domain' )
					),						
						
				/////////////////////////////////////////////////
				// Section: Animations
				////////////////////////////////////////////////				
				array(
					'id'       => 'section-animations',
					'type'     => 'section',				
					'title'    => __( 'Animations settings', 'sp-lite-txt-domain' ),
					'subtitle' => __( '', 'sp-lite-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'       => 'loader-animation',
						'type'     => 'select',
						'title'    => __( 'onLoad Effect', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Animation when loading popup', 'sp-pro-txt-domain' ),
						'desc'     => __( '', 'sp-pro-txt-domain' ),
						'default'  => 'fadeInDown',
						'options'  => array(
							'FadeIn Effects' => array(
								'fadeIn' => 'fadeIn',
								'fadeInDown' => 'fadeInDown',
								'fadeInUp' => 'fadeInUp',
								'fadeInRight' => 'fadeInRight',
								'fadeInLeft' => 'fadeInLeft',
							),
							'Attention Seekers' => array(
								'zoomIn' => 'zoomIn',
								'rotateIn' => 'rotateIn',
							),
						),
					),
					array(
						'id'       => 'loader-speed',
						'type'     => 'slider', 
						'title'    => __('onLoad Speed', 'sp-pro-txt-domain'),
						'subtitle' => __('Set Popup load speed','sp-pro-txt-domain'),
						'desc'     => __('Min:0.1, Max:5, Best: 0-1' , 'sp-pro-txt-domain'),
						'default' => .7,
						'min' => 0,
						'step' => .1,
						'max' => 5,
						'resolution' => 0.01,
						'display_value' => 'text'
					),	
					array(
						'id'       => 'unloader-animation',
						'type'     => 'select',
						'title'    => __( 'unLoad Effect', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Animation when unloading popup', 'sp-pro-txt-domain' ),
						'desc'     => __( '', 'sp-pro-txt-domain' ),
						'default'  => 'fadeOutDown',						
						'options'  => array(
							'FadeOut Effects' => array(
								'fadeOut' => 'fadeOut',
								'fadeOutDown' => 'fadeOutDown',
								'fadeOutUp' => 'fadeOutUp',
								'fadeOutRight' => 'fadeOutRight',
								'fadeOutLeft' => 'fadeOutLeft',
							),
							'Attention Seekers' => array(
								'zoomOut' => 'zoomOut',
								'rotateOut' => 'rotateOut',
							),
						),
					),
					array(
						'id'       => 'unloader-speed',
						'type'     => 'slider', 
						'title'    => __('unload Speed', 'sp-pro-txt-domain'),
						'subtitle' => __('Set Popup unload speed','sp-pro-txt-domain'),
						'desc'     => __('Min:0.1, Max:5, Best: 0-1' , 'sp-pro-txt-domain'),
						'default' => .3,
						'min' => 0,
						'step' => .1,
						'max' => 5,
						'resolution' => 0.01,
						'display_value' => 'text'
					),	
				// End Animations
			)
		) );
	}
	
	/////////////////////////////////////////////////
	// SECTION: Side Button
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Side Button', 'sp-lite-txt-domain' ),
			'id'    => 'side-button-settings',
			'desc'  => __( 'Options to change position and color scheme for the side button.', 'sp-lite-txt-domain' ),
			'icon'  => 'el el-iphone-home',
			'fields'     => array(				
				array(
					'id'       => 'side-button-position',
					'type'     => 'select',
					'title'    => __( 'Choose Position', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Choose the position of side button.', 'sp-lite-txt-domain' ),
					'desc'     => __( '', 'sp-lite-txt-domain' ),
					//Must provide key => value pairs for select options
					'options'  => array(
						'pos_right' => __( 'Right', 'sp-lite-txt-domain' ),
						'pos_left' => __( 'Left', 'sp-lite-txt-domain' ),
						'pos_none' => __( 'None (Hide)', 'sp-lite-txt-domain' ),
					),
					'default'  => 'pos_right'
				),				
				array(
					'id'       => 'side-button-text',
					'type'     => 'text',
					'title'    => __( 'Button Text', 'sp-lite-txt-domain' ),
					'subtitle'     => __( 'What should your button say?', 'sp-lite-txt-domain' ),
					'desc' => __( '<b>Suggestions:</b> "Need Help?" "Subscribe" "Get a quote!" "Have a query?"<br/><b>Default:</b> Contact Us', 'sp-lite-txt-domain' ),
					'default'  => 'CONTACT US',
				),	
				array(
					'id'       => 'choose-side-button',
					'type'     => 'select',
					'title'    => __( 'Side Button Scheme', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Choose styles and appearance.', 'sp-lite-txt-domain' ),
					'desc'     => __( '<b>Default:</b> Inherit From Color Scheme', 'sp-lite-txt-domain' ),
					//Must provide key => value pairs for select options
					'options'  => array(
						'inherit' => __('Inherit From Color Scheme', 'sp-lite-txt-domain' ),
						'custom' => __('Set Your Own', 'sp-lite-txt-domain' ),
					),
					'default'  => 'inherit'
				),			
				array(
					'id'       => 'side-button-background',
					'type'     => 'background',
					'required' => array( 'choose-side-button', '=', 'custom' ),
					'output'   => array( '' ),
					'title'    => __( 'Button Background', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Button background with image, color, etc.', 'sp-lite-txt-domain' ),
					'default'   => array( 
							'background-color' => '#333333',
						),
					'background-color'			=> true,
					'background-repeat'			=> false,
					'background-attachment'		=> false,
					'background-position'		=> false,
					'background-image'			=> false,
					'background-clip'			=> false,
					'background-origin'			=> false,
					'background-size'			=> false,
					'preview_media'				=> false,
					'preview'					=> false,
					'preview_height'			=> false,
					'transparent'			=> false,
				),
			)
		) );
	}
	
	
	/////////////////////////////////////////////////
	// SECTION: Typography
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Typography', 'sp-lite-txt-domain' ),
			'id'    => 'typography-settings',
			'desc'  => __( 'Choose your desired font styles.', 'sp-lite-txt-domain' ),
			'icon'  => 'el el-iphone-home',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Heading & Description (typography)
				////////////////////////////////////////////////				
				array(
					'id'       => 'heading-typography',
					'type'     => 'typography',
					//'required' => array( 'use_heading_font', '=', 1 ),
					'title'    => __( 'Heading Font', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Specify the heading font properties.', 'sp-lite-txt-domain' ),
					'desc'		=> __('Font Color is important to look good with your choosen color scheme.', 'sp-lite-txt-domain' ),
					'google'   => true,
					'default'  => array(
						'color'       => 	'#F1F1F1',
						'font-size'   => 	'28px',
						'line-height' =>	'32px',
						'font-family' => 	'Open Sans',
						'font-weight' => 	'900',
					),
					'text-align'	=> false,
					'font-subsets'	=> false,
				),
				
				array(
					'id'       => 'cta-typography',
					'type'     => 'typography',
					//'required' => array( 'use_cta_font', '=', 1 ),
					'title'    => __( 'Call To Action Font', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Specify these font properties.', 'sp-lite-txt-domain' ),
					'google'   => true,
					'default'  => array(
						'color'       => '#484848',
						'font-size'   => '13px',
						'line-height'   => '21px',
						'font-family' => 'Noto Sans',
						'font-weight' => 	'normal',
						'text-align' => 	'center',
					),
					'font-subsets'	=> false,
				),	
				
				array(
					'id'       => 'submit-button-typography',
					'type'     => 'typography',
					//'required' => array( 'use_submit_button_font', '=', 1 ),
					'title'    => __( 'Submit Button Font', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Specify the submit button font properties.', 'sp-lite-txt-domain' ),
					'google'   => true,
					'default'  => array(						
						'font-family' 	=> 'Open Sans',
						'color'       	=> '#F1F1F1',
						'font-size'   	=> '22px',
						'line-height'   => '24px',
						'font-weight' 	=> '700',
					),				
					'text-align'	=> false,
					'font-subsets'	=> false,
				),	
						
				array(
					'id'       => 'side-button-typography',
					'type'     => 'typography',
					//'required' => array( 'use_side_button_font', '=', 1 ),
					'title'    => __( 'Side Button Font', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Typography and Font properties.', 'sp-lite-txt-domain' ),
					'google'   => true,
					'default'  => array(
						'font-family' 	=> 'Open Sans',
						'color'       => '#F1F1F1',
						'font-size'   => '14px',
						'line-height'   => '18px',
						'font-weight' 	=> '700',
					),				
					'text-align'	=> false,
					'font-subsets'	=> false,
				),
			)
		) );
	}
	//Typography Settings Ends Here	
	
	/////////////////////////////////////////////////
	// SECTION: Advanced Settings
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Custom CSS', 'sp-lite-txt-domain' ),
			'id'    => 'advance-settings-settings',
			'desc'  => __( 'Custom CSS code to style your popup.', 'sp-lite-txt-domain' ),
			'icon'  => 'el el-cog',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Heading & Description (typography)
				////////////////////////////////////////////////				
				array(
					'id'       => 'custom-css-code',
					'type'     => 'ace_editor',
					'title'    => __( 'CSS Code', 'sp-lite-txt-domain' ),
					'subtitle' => __( 'Paste your CSS code here.', 'sp-lite-txt-domain' ),
					'mode'     => 'css',
					'theme'    => 'monokai',
					'desc'     => '<br/>Contact our support for help: poke@slickpopup.com',
					'default'  => "#splite_popup_box span.wpcf7-not-valid-tip{\n\t\t\t\t   \n\t\t\t}"
				),
			)
		) );
	}