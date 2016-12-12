<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// Only if administrator
if( current_user_can( 'administrator' ) ) {
	

	if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'capability-settings' ) {
		

?>

    <form method="post" action="" id="uix-styleswitcher-capability-pages">
    
    
        <?php wp_nonce_field( 'uix_ss_capabilitysettings' ); ?>
        
        <h4><?php _e( 'The capability for this plugin to be displayed to the pages of front-end you need.', 'uix-styleswitcher' ); ?></h4>
        <p class="description"><?php _e( 'You can leave all checkboxes unchecked for the capability for this plugin to be displayed to the all pages of front-end.', 'uix-styleswitcher' ); ?></p>

       <table class="form-table">
          
          <input type="hidden" name="uix_ss_opt_capabilitypages_result" value="<?php echo esc_attr( get_option( 'uix_ss_opt_capabilitypages_result', '' ) ); ?>" >
          
          <tr>
            <th scope="row">
              <?php _e( 'Choose Pages You Want', 'uix-styleswitcher' ); ?>
              
              <div class="uix-styleswitcher-capability-pages-result"></div>
            </th>
             <td>
               
                <p>
                   <?php 
				    $id = 1;
					$saved_settings = get_option( 'uix_ss_opt_capabilitypages_options_'.$id );  
                    $pages = get_pages(); 
                    foreach ( $pages as $page ) {
						?>
                        <input type="checkbox" data-pagid="<?php echo esc_attr( $page->ID ); ?>" name="uix_ss_opt_capabilitypages[<?php echo esc_attr( $id ); ?>][enabled<?php echo esc_attr( $page->ID ); ?>]" value="1" <?php checked( isset( $saved_settings[ $id ][ 'enabled'.esc_attr( $page->ID ) ] ) ); ?> ><?php echo $page->post_title; ?><br>
                        
                        <?php
                    }
                     ?>
                </p>
                
                
  
            </td>         
            
          </tr>
          
          
           <script type="text/javascript">
			( function( $ ) {
			"use strict";
				$( function() {
			
				   /*! 
					 * 
					 * Uix Page Builder Anchor Links
					 * ---------------------------------------------------
				    */ 
					$( 'input[name^="uix_ss_opt_capabilitypages"]' ).on( 'change', function() {
						
					
						var settings = JSON.stringify($( "[name^='uix_ss_opt_capabilitypages']" ).serializeArray()),
						    _curValue = $( this ).attr( 'data-pagid' ),
							_tarValue = $( '[name="uix_ss_opt_capabilitypages_result"]' ).val() + ',',
							_result;

							
		
						if ( _tarValue.indexOf( _curValue + ',' ) < 0 ) {
							_result = _tarValue + _curValue + ',';
						} else {
							_result = _tarValue.replace( _curValue + ',', '' );
						}
				
						$( '[name="uix_ss_opt_capabilitypages_result"]' ).val( _result.substring( 0, _result.length-1 ).replace(/^,/, '') );		
		
				    
						// retrieve the widget settings form
						$.post( ajaxurl, {
							action          : 'uix_styleswitcher_capability_pages_save_settings',
							custom_meta     : settings	,
							result          : $( '[name="uix_ss_opt_capabilitypages_result"]' ).val()
						}, function ( response ) {
							
							$( '.uix-styleswitcher-capability-pages-result' ).show().html( '<div class="updated"><p><strong><?php  _e( 'Settings saved.', 'uix-styleswitcher' ); ?></strong></p></div>' );
							setTimeout( function () {
								$( '.uix-styleswitcher-capability-pages-result' ).hide();
							}, 700 );
							
						});
					});
		
				} );
				
			} ) ( jQuery );  
		   </script>
           
          
        </table>
        
        <span class="uix-styleswitcher-capability-pages-result"></span>
          
    </form>


    
<?php 
    } 
}
?>