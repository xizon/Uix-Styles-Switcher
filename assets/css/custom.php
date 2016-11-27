<?php
/** Sets up the WordPress Environment. */
header( 'Content-Type:text/css;charset=utf-8' );
require '../../../../../wp-load.php';

echo UixThemeSwitch::correct_code( get_option( 'uix_ss_opt_cssnewcode', UixThemeSwitch::default_code( 'css' ) ) );