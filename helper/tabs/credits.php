<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'credits' ) {
?>


        <h3>
           <?php _e( 'I would like to give special thanks to credits. The following is a guide to the list of credits for this plugin:', 'uix-styleswitcher' ); ?>
        </h3>  
        <p>
        
        <ul>
            <li><a href="https://github.com/zutigrm/ultimate-styleswitcher" target="_blank" rel="nofollow"><?php _e( 'Ultimate Styleswitcher Framework', 'uix-styleswitcher' ); ?></a></li>
         
        </ul>
        
        </p> 
        
    
<?php } ?>