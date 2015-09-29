<?php

/*
Plugin Name: 	BIP Enqueue Stuff
Plugin URI: 	http://www.wpmaz.uk
Description:    Enqueue the essentials for a BiP site
Version: 		0.2
Author: 		Mario Jaconelli
Author URI:  	http://www.wpmaz.uk
*/

include('inc/load-scripts.php');
include('inc/admin.php');

function bipenc_create_table(){

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "bipenqueue"; 

	$sql = "CREATE TABLE $table_name (

	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  databaseKey varchar(55) DEFAULT '' NOT NULL,
	  defaultState varchar(55) DEFAULT '0' NOT NULL,
	  displayName varchar(55) DEFAULT '0' NOT NULL,
	  filestouse text,
	  UNIQUE KEY id (id)

	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );


}

function bipenc_add_initial_data(){

	global $wpdb;

	$table_name = $wpdb->prefix . 'bipenqueue';

	$wpdb->replace( 
		$table_name, 
				array(
				'id'			=> '1',	
				'databaseKey' 	=> 'bip_use_pure',
			   	'defaultState' 	=> '1',
			   	'displayName'	=> 'Pure Framework',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('pure.css'),
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '2',
				'databaseKey' 	=> 'bip_use_pure_framework',
			   	'defaultState' 	=> '1',
			   	'displayName'	=> 'Pure framework fallbacks (for older IE versions)',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('grids-responsive-old-ie.css', 'lte IE 8'),
			   								array('grids-responsive.css', 'lt IE 8')
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '3',
				'databaseKey' 	=> 'bip_use_dt',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'Datatables',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('datatables.js')
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '4',
				'databaseKey' 	=> 'bip_use_dt_css',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'Datatables CSS',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('datatables.css')
			   							  ))
			  )
	);
		$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '5',
				'databaseKey' 	=> 'bip_use_wow',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'WOW.js',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('wow.js')
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '6',
				'databaseKey' 	=> 'bip_use_modal',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'modal.js',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('modal.js'),
			   								array('modal.css')
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(
				'id'			=> '7',	
				'databaseKey' 	=> 'animatecss',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'animate.css',
			   	'filestouse'	=>  maybe_serialize(array(
			   				   								array('animate.css')
			   				   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '8',
				'databaseKey' 	=> 'superfishmenu',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'Superfish Menu',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('superfish-menu.css')
			   							  ))
			  )
	);
	$wpdb->replace( 
		$table_name, 
		array(	
				'id'			=> '9',
				'databaseKey' 	=> 'bip-flexisel',
			   	'defaultState' 	=> '0',
			   	'displayName'	=> 'Flexicel',
			   	'filestouse'	=>  maybe_serialize(array(
			   								array('flexiselstyle.css'),
			   								array('jquery.flexisel.js'),
			   							  ))
			  )
	);


}

function bip_enc_activate() {

    bipenc_create_table();

    bipenc_add_initial_data();

    // Create default options in database upon activation

	$options_to_add = get_bip_enc_data();

	foreach ($options_to_add as $options_to_add) {
		
		add_option( $options_to_add['databaseKey'], $options_to_add['defaultState'], '');

	}

	add_option( 'bip_pure_wrap_width', 1020, '');
}

register_activation_hook( __FILE__, 'bip_enc_activate' );


function get_bip_enc_data(){

	global $wpdb;

	$query = "SELECT * from wp_bipenqueue";

	$bip_data = $wpdb->get_results( $query, ARRAY_A );

	return $bip_data;

}

function custom_upload_mimes ( $existing_mimes = array() ) {

	$existing_mimes['js'] = 'application/javascript';
	$existing_mimes['css'] = 'text/css';

return $existing_mimes;
}

add_filter('upload_mimes', 'custom_upload_mimes');