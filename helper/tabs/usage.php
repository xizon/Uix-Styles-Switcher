<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'usage' ) {
?>


        <p>
           <?php _e( '<h4 class="uix-bg-custom-title">1. After activating your theme, you can see a prompt pointed out as absolutely critical. Go to <strong>"Appearance -> Install Plugins"</strong>.
Or, upload the plugin to wordpress, Activate it. (Access the path (/wp-content/plugins/) And upload files there.)</h4>', 'uix-styleswitcher' ); ?>
        </p>  
        <p>
           <img src="<?php echo UixThemeSwitch::plug_directory(); ?>helper/img/plug.jpg" alt="">
        </p> 
        <p>
           <?php _e( '<h4 class="uix-bg-custom-title">2. Create custom styles and scripts to your website, Go to <strong>"Uix Switcher -> Custom CSS & JS"</strong>.</h4>', 'uix-styleswitcher' ); ?>
     
        </p>
        
        <p>
           <?php _e( '<h4 class="uix-bg-custom-title">3. Set up capability for this plugin to be displayed to the front-end for pages you need, Go to <strong>"Uix Switcher -> Capability"</strong>.</h4>', 'uix-styleswitcher' ); ?>
     
        </p>
           
<?php } ?>