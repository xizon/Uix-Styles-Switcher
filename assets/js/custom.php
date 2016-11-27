<?php
/** Sets up the WordPress Environment. */
header( 'Content-Type:application/x-javascript;charset=utf-8' );
require '../../../../../wp-load.php';

echo UixThemeSwitch::correct_code( get_option( 'uix_ss_opt_jsnewcode', UixThemeSwitch::default_code( 'js' ) ) );
