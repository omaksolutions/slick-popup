<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function splite_uninstall_plugin() { // Uninstallation actions here
	
	global $splite_opts; 
	$option_name = 'splite_opts'; 	
	$delete_data = $splite_opts['delete_data'];	
	$send_test_email = true;
	$site_url = site_url(); 	
	//delete_option($option_name);
}

// Do the action
splite_uninstall_plugin();

?>