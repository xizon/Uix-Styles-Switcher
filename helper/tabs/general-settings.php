<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// variables for the field and option names 
$hidden_field_name = 'submit_hidden_uix_ss_custom';



// If they did, this hidden field will be set to 'Y'
if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	
	// Save the posted value in the database
	update_option( 'uix_ss_opt_cssnewcode', wp_unslash( $_POST[ 'uix_ss_opt_cssnewcode' ] ) );
	update_option( 'uix_ss_opt_jsnewcode', wp_unslash( $_POST[ 'uix_ss_opt_jsnewcode' ] ) );


	// Put a "settings saved" message on the screen
	echo '<div class="updated"><p><strong>'.__('Settings saved.', 'uix-styleswitcher' ).'</strong></p></div>';

 }  


if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'general-settings' ) {
	

?>

    <form name="form1" method="post" action="">
    
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        

        <div class="uix-d-tabs">
            <h3><?php _e( 'Create new <strong>styles</strong> to your website. ', 'uix-styleswitcher' ); ?></h3>
            <div>
                <table class="form-table">
                  <tr>
                    <th scope="row">
                      <?php _e( 'CSS Code', 'uix-styleswitcher' ); ?>
                      <p><a href="javascript:" id="uix_styleswitcher_view_css"><?php _e( '&rarr; Sample', 'uix-styleswitcher' ); ?></a></p>
                    </th>
                    <td>
                      <textarea name="uix_ss_opt_cssnewcode" class="regular-text" rows="25" style="width:98%;"><?php echo esc_textarea( get_option( 'uix_ss_opt_cssnewcode', UixThemeSwitch::default_code( 'css' ) ) ); ?></textarea>
                    </td>
                  </tr>
                </table> 
            </div>
            
            
            <h3><?php _e( 'Create new <strong>scripts</strong> to your website.', 'uix-styleswitcher' ); ?></h3>
            <div>
            
                <table class="form-table">
                  <tr>
                    <th scope="row">
                      <?php _e( 'Javascript Code', 'uix-styleswitcher' ); ?>
                      <p><a href="javascript:" id="uix_styleswitcher_view_js"><?php _e( '&rarr; Sample', 'uix-styleswitcher' ); ?></a></p>
                    </th>
                    <td>
                      <textarea name="uix_ss_opt_jsnewcode" class="regular-text" rows="25" style="width:98%;"><?php echo esc_textarea( get_option( 'uix_ss_opt_jsnewcode', UixThemeSwitch::default_code( 'js' ) ) ); ?></textarea>
                    </td>
                  </tr>
                </table> 


            </div>
        </div><!-- /.uix-d-tabs -->    
        

        <?php submit_button(); ?>
        
         <div class="uix-styleswitcher-dialog-mask"></div>
         <div class="uix-styleswitcher-dialog" id="uix-styleswitcher-view-css-container">  
            <textarea rows="15" style=" width:95%;" class="regular-text" onClick="select();"><?php echo UixThemeSwitch::default_code( 'css' ); ?></textarea>
            <a href="javascript:" id="uix_styleswitcher_close_css" class="close button button-primary"><?php _e( 'Close', 'uix-styleswitcher' ); ?></a>  
        </div>
         <div class="uix-styleswitcher-dialog" id="uix-styleswitcher-view-js-container">  
            <textarea rows="15" style=" width:95%;" class="regular-text" onClick="select();"><?php echo UixThemeSwitch::default_code( 'js' ); ?></textarea>
            <a href="javascript:" id="uix_styleswitcher_close_js" class="close button button-primary"><?php _e( 'Close', 'uix-styleswitcher' ); ?></a>  
        </div>

        <script type="text/javascript">
		( function( $ ) {
		"use strict";
			$( function() {
		
				//css
				var dialog_uix_styleswitcher_css = $( '#uix-styleswitcher-view-css-container, .uix-styleswitcher-dialog-mask' );  
							
				$( '#uix_styleswitcher_view_css' ).on( 'click', function( e ) {
					e.preventDefault();
					dialog_uix_styleswitcher_css.show();
				});
				$( '#uix_styleswitcher_close_css' ).on( 'click', function( e ) {
					e.preventDefault();
					dialog_uix_styleswitcher_css.hide();
				});
		
				
				//javascript
				var dialog_uix_styleswitcher_js = $( '#uix-styleswitcher-view-js-container, .uix-styleswitcher-dialog-mask' );  
				
								
				$( '#uix_styleswitcher_view_js' ).on( 'click', function( e ) {
					e.preventDefault();
					dialog_uix_styleswitcher_js.show();
				});
				$( '#uix_styleswitcher_close_js' ).on( 'click', function( e ) {
					e.preventDefault();
					dialog_uix_styleswitcher_js.hide();
				});
				
				
				//Tab
				if ( $( document.body ).width() > 768 ) {
					$( '.uix-d-tabs' ).accTabs();
				}
					
				
			} );
			
		} ) ( jQuery );
        </script>

    
    </form>


    
<?php } ?>