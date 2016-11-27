<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


if( !isset( $_GET[ 'tab' ] ) || $_GET[ 'tab' ] == 'about' ) {
	
?>

        <p>
            <?php _e( 'Uix Styles Switcher creates a simple front-end control panel that helps the site display different styles, colors, fonts, layouts and more.', 'uix-styleswitcher' ); ?>
        </p>  
       
   
    
<?php } ?>