<?php

function bipenc_load_scripts(){

  $scripts = get_bip_enc_data();

  foreach ($scripts as $script) {

      $countfile = 1;
      
      $filetousearray = unserialize($script['filestouse']);

      foreach ($filetousearray as $file) {
        
        $extension  = explode(".", $file[0]);
        $count      = count($extension);
        $extension  = $extension[$count - 1];


        if(substr( $script['databaseKey'], 0, 16) == 'custom_addition_'){
              
              $filejs = $filecss = $file[0];

        }else{

              $filejs   = plugins_url( '../assets/js/' . $file[0], __FILE__ );
              $filecss  = plugins_url( '../assets/css/'. $file[0], __FILE__ );

        }

        if($extension == 'css'){

            wp_register_style( $script['databaseKey'] . '-' . $countfile , $filecss , false, '1.0.0' );

              if(get_option( $script['databaseKey']) == '1'){

                  wp_enqueue_style($script['databaseKey'] . '-' . $countfile);

                  global $wp_styles;

                  if($file[1]){
                
                      $wp_styles->registered[$script['databaseKey'] . '-' . $countfile]->add_data( 'conditional', $file[1]);

                  }

             }

        }

        if($extension == 'js'){

            if(get_option($script['databaseKey']) == '1'){

            	wp_enqueue_script($script['databaseKey'], $filejs, array( 'jquery' ), '1.0' );
          	
          	}
            
        }

        $countfile++;

      }
         
  }

}

add_action('wp_enqueue_scripts','bipenc_load_scripts');