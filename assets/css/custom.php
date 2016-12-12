<?php
/**
 * Sets up the WordPress Environment.
 *
 * @package WordPress
 */
require_once( dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

header( 'Content-Type:text/css;charset=utf-8' );

echo UixThemeSwitch::correct_code( get_option( 'uix_ss_opt_cssnewcode', UixThemeSwitch::default_code( 'css' ) ) );