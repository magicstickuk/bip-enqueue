<?php

function bipenc_admin_page(){

	add_submenu_page('options-general.php','BiP Enqueue', 'BiP Enqueue', 'manage_options', 'bip-enc','bip_enc_admin_menu_markup');
  
}

add_action( 'admin_menu', 'bipenc_admin_page' );

function bip_enc_manage_new_file($post_data){

	if(isset($post_data['upload-submit'])){

			if(isset($post_data['fileid'])){

				$fileid 			= $post_data['fileid'];
				$fileurl 			= wp_get_attachment_url( $fileid );
				$fileDisplayName 	= $post_data['uniqueName'];
				$fileDatabaseKey 	= "custom_addition_" . uniqid();

					global $wpdb;

					$table_name 	= $wpdb->prefix . 'bipenqueue';

					$wpdb->insert( 
						
						$table_name, 
							array(
							'databaseKey' 	=> $fileDatabaseKey,
						   	'defaultState' 	=> '0',
						   	'displayName'	=> $fileDisplayName,
						   	'filestouse'	=>  maybe_serialize(array(
						   						
						   							array($fileurl),

						   						))
			 	 			)
					);

			}
			
		}

}

function bip_enc_manage_deletion($get_data){

	if(isset($get_data['deletemj'])){

		global $wpdb;

		$wpdb->delete( $wpdb->prefix . 'bipenqueue', array( 'databaseKey' => $get_data['deletemj'] ) );

	}

}

function bip_enc_update_options($post_data){

	$enc_array = get_bip_enc_data();

		if(isset($post_data['main-submit'])){

			foreach ($enc_array as $enc) {
				
				${ $enc['databaseKey'] } = $post_data[ $enc['databaseKey'] ] ? '1' : '0';

				update_option($enc['databaseKey'], ${ $enc['databaseKey'] });

			}

			$pure_wrap_width = $post_data['pure-wrap-width'] ? $post_data['pure-wrap-width'] : get_option('bip_pure_wrap_width');

		}
}

function bip_enc_admin_menu_markup(){

		//Manage the new file if added.

		bip_enc_manage_new_file($_REQUEST);

		//Manage Deletion of custom enqueue

		bip_enc_manage_deletion($_GET);

		// Update Options

		bip_enc_update_options($_REQUEST);

		// Load newly updated stored data

		$enc_array = get_bip_enc_data();

		foreach ($enc_array as $enc) {
				
			${ $enc['databaseKey'] } = get_option( $enc['databaseKey'] );

		}

    	$pure_wrap_width = get_option('bip_pure_wrap_width');

		ob_start()?>

		<div class="wrap">

	    	<h1>BiP Enqueue Settings</h1>

	    	<div class="metabox">

		    	<form id="thefirstform" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

		    	<?php foreach ($enc_array as $enc) :?>

		    		<div class="inputContainer">

		    			<input type="checkbox" name="<?php echo $enc['databaseKey']; ?>" value="<?php echo $enc['databaseKey']; ?>" <?php echo ${ $enc['databaseKey'] }  ==  1 ? 'checked' : ''; ?>/><?php echo $enc['displayName']; ?>

		    			<?php if(substr( $enc['databaseKey'], 0, 16) == 'custom_addition_'):?>

		    					<div class="bipenc-delete">

		    						<a href="<?php echo $_SERVER['REQUEST_URI'] ?>&deletemj=<?php echo $enc['databaseKey']; ?>">Delete</a>

		    					</div>

		    			<?php endif; ?>

		    		</div>

		    	<?php endforeach; ?>

		    	  <h3>Related Settings</h3>
			
		          Set the width of the pure wrap:
		          		
		          <input style="width:50px" type="text" name="pure-wrap-width" value="<?php echo $pure_wrap_width; ?>"/> px<br><br>

		          <input type="submit" value="Save Settings" class="button-primary main-dup-button" name="main-submit">

				</form><br>

				<hr>

				<form id="addanenqueue" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					
					<h3>Add a new file to manage</h3>
			
		          	Add a file so you can manage its enquement.<br><br>
		          	<input type="submit" value="Pick file to add" class="button-secondary main-dup-button aria_upload_button" id="pickfilebutton" name="pick-file">

					<br><br>

					Give this file a name for the list above <input type="text" value="" name="uniqueName">

					<br><br>

					<input type="submit" value="Save New Addition" class="button-primary main-dup-button" name="upload-submit">

					<input id="addfilecontainer" type="hidden" value="" name="fileid"/>

				</form>

			</div>

 		</div>

<?php 

}

function hook_css(){

	if(get_option( 'bip_use_pure') == '1'){

  		$output = "<style> .pure-g { max-width : " . get_option('bip_pure_wrap_width'). "px; margin: 0 auto } </style>";
  		
  		echo $output;

  	}

}

add_action('wp_head','hook_css');

function bipenc_load_admin_styles(){

		if(get_current_screen()->id == 'settings_page_bip-enc'){

			wp_register_style( 'bipenc-admin-styles', plugins_url( '/css/admin.css', __FILE__ ) , false, '1.0.0' );

			wp_enqueue_style( 'bipenc-admin-styles');

			wp_enqueue_script( 'bip-enc-admin-js', plugins_url( '/css/admin.js', __FILE__ ) , array('jquery'), '1.0.0');

			wp_enqueue_media();

		}
		
}

add_action('admin_enqueue_scripts','bipenc_load_admin_styles');