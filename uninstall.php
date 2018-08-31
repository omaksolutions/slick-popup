<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


function splite_uninstall_plugin() { // Uninstallation actions here
	
	$delete_data = get_option('splite_delete_data') ? get_option('splite_delete_data') : 0; 
	
	if($delete_data) {
		delete_option('splite_opts');
		delete_option('splite_delete_data');
		delete_option('splite_install_date');
	}
}

?>