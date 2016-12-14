<?php
/**
 * Uix Styles Switcher
 *
 * @author UIUX Lab <uiuxlab@gmail.com>
 *
 *
 * Plugin name: Uix Styles Switcher
 * Plugin URI:  https://uiux.cc/wp-plugins/uix-styleswitcher/
 * Description: Uix Styles Switcher creates a simple front-end control panel that helps the site display different styles, colors, fonts, layouts and more.
 * Version:     1.0.0
 * Author:      UIUX Lab
 * Author URI:  https://uiux.cc
 * License:     GPLv2 or later
 * Text Domain: uix-styleswitcher
 * Domain Path: /languages
 */

class UixThemeSwitch {
	
	const PREFIX = 'uix';
	const HELPER = 'uix-styleswitcher-helper';

	
	/**
	 * Initialize
	 *
	 */
	public static function init() {
	
	    self::setup_constants();
	
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'actions_links' ), -10 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'backstage_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontpage_scripts' ) );
		add_action( 'admin_init', array( __CLASS__, 'tc_i18n' ) );
		add_action( 'admin_init', array( __CLASS__, 'load_helper' ) );
		add_action( 'admin_menu', array( __CLASS__, 'options_admin_menu' ) );
		add_filter( 'body_class', array( __CLASS__, 'new_class' ) );
		add_action( 'wp_footer', array( __CLASS__, 'init_ss' ) );
		add_action( 'wp_ajax_uix_styleswitcher_capability_pages_save_settings', array( __CLASS__, 'save' ) );
		
	
	}
	
	/**
	 * Setup plugin constants.
	 *
	 */
	public static  function setup_constants() {

		// Plugin Folder Path.
		if ( ! defined( 'UIX_SS_PLUGIN_DIR' ) ) {
			define( 'UIX_SS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'UIX_SS_PLUGIN_URL' ) ) {
			define( 'UIX_SS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'UIX_SS_PLUGIN_FILE' ) ) {
			define( 'UIX_SS_PLUGIN_FILE', __FILE__ );
		}
	}


	
	/*
	 * Enqueue scripts and styles.
	 *
	 *
	 */
	public static function frontpage_scripts() {
	
		$page_ids = get_option( 'uix_ss_opt_capabilitypages_result' );

		
		if( self::inc_str( $page_ids, get_the_ID() ) || empty( $page_ids ) || $page_ids == get_the_ID() ) {
			
			wp_enqueue_script( self::PREFIX . '-styleswitcher', self::plug_directory() .'assets/js/styleswitcher.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( self::PREFIX . '-styleswitcher-library', self::plug_directory() .'assets/js/library.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_style( self::PREFIX . '-styleswitcher', self::plug_directory() .'assets/css/styleswitch.min.css', false, '1.0', 'all' );
			
			
			//Main stylesheets and scripts to Front-End
			wp_enqueue_script( self::PREFIX . '-styleswitcher-custom', self::plug_directory() .'assets/js/custom.php', array( 'jquery', self::PREFIX . '-styleswitcher' , self::PREFIX . '-styleswitcher-library'), self::ver(), true );
			wp_enqueue_style( self::PREFIX . '-styleswitcher-custom', self::plug_directory() .'assets/css/custom.php', array( self::PREFIX . '-styleswitcher' ), self::ver(), 'all' );
	
		}
		

	}
	
	

	
	/*
	 * Enqueue scripts and styles  in the backstage
	 *
	 *
	 */
	public static function backstage_scripts() {
	
			  //Check if screen ID
			  $currentScreen = get_current_screen();
			  
			  
			  if( self::inc_str( $currentScreen->base, '_page_' ) ) {
			  
				if ( is_admin()) {
					
						//jQuery Accessible Tabs
						wp_enqueue_script( 'accTabs', self::plug_directory() .'admin/js/jquery.accTabs.js', array( 'jquery' ), '0.1.1');
						wp_enqueue_style( 'accTabs', self::plug_directory() .'admin/css/jquery.accTabs.css', false, '0.1.1', 'all');
					
						//Main
						wp_enqueue_style( self::PREFIX . '-styleswitcher', self::plug_directory() .'admin/css/style.css', false, self::ver(), 'all');
						

				}
		  }
		

	}
	
	
	
	/**
	 * Internationalizing  Plugin
	 *
	 */
	public static function tc_i18n() {
	
	
	    load_plugin_textdomain( 'uix-styleswitcher', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'  );
		

	}
	
	/*
	 * The function finds the position of the first occurrence of a string inside another string.
	 *
	 * As strpos may return either FALSE (substring absent) or 0 (substring at start of string), strict versus loose equivalency operators must be used very carefully.
	 *
	 */
	public static function inc_str( $str, $incstr ) {
	
		if ( mb_strlen( strpos( $str, $incstr ), 'UTF8' ) > 0 ) {
			return true;
		} else {
			return false;
		}

	}


	/*
	 * Create customizable menu in backstage  panel
	 *
	 * Add a submenu page
	 *
	 */
	 public static function options_admin_menu() {
		 
		//Add a top level menu page.
		add_menu_page(
			__( 'Uix Switcher Settings', 'uix-styleswitcher' ),
			__( 'Uix Switcher', 'uix-styleswitcher' ),
			'manage_options',
			self::HELPER,
			'uix_styleswitcher_options_page',
			'dashicons-art',
			'83.' . rand( 0, 99 )
			
		);
		
		
        //Add sub links
		add_submenu_page(
			self::HELPER,
			__( 'Custom CSS & JS', 'uix-styleswitcher' ),
			__( 'Custom CSS & JS', 'uix-styleswitcher' ),
			'manage_options',
			'admin.php?page='.self::HELPER.'&tab=general-settings'
		);
		
        //Add sub links
		add_submenu_page(
			self::HELPER,
			__( 'Capability', 'uix-styleswitcher' ),
			__( 'Capability', 'uix-styleswitcher' ),
			'manage_options',
			'admin.php?page='.self::HELPER.'&tab=capability-settings'
		);
		
		
        //Add sub links
		add_submenu_page(
			self::HELPER,
			__( 'Helper', 'uix-styleswitcher' ),
			__( 'Helper', 'uix-styleswitcher' ),
			'manage_options',
			'admin.php?page='.self::HELPER
		);	
		
		// remove the "main" submenue page
		remove_submenu_page( self::HELPER, self::HELPER );
			
			
	 }
	
	
	
	
	/*
	 * Load helper
	 *
	 */
	 public static function load_helper() {
		 
		 require_once UIX_SS_PLUGIN_DIR.'helper/settings.php';
	 }
	
	
	
	/**
	 * Add plugin actions links
	 */
	public static function actions_links( $links ) {
		$links[] = '<a href="' . admin_url( "admin.php?page=".self::HELPER."&tab=usage" ) . '">' . __( 'How to use?', 'uix-styleswitcher' ) . '</a>';
		return $links;
	}
	

	
	
	/*
	 * Get plugin slug
	 *
	 *
	 */
	public static function get_slug() {

         return dirname( plugin_basename( __FILE__ ) );
	
	}
	
	

	/*
	 * Callback the plugin directory
	 *
	 *
	 */
	public static function plug_directory() {

	  return plugin_dir_url( __FILE__ );

	}
	
	/*
	 * Callback the plugin file path
	 *
	 *
	 */
	public static function plug_filepath() {

	  return WP_PLUGIN_DIR .'/'.self::get_slug();

	}	
	

	/*
	 * Returns current plugin version.
	 *
	 *
	 */
	public static function ver() {
	
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_folder = get_plugins( '/' . self::get_slug() );
		$plugin_file = basename( ( __FILE__ ) );
		return $plugin_folder[$plugin_file]['Version'];


	}
	

	
	/*
	 * Extend the default WordPress body classes.
	 *
	 *
	 */
	public static function new_class( $classes ) {
	
	    global $uix_styleswitcher_temp;
        if ( $uix_styleswitcher_temp === true ) { 
			$classes[] = 'uix-styleswitcher-body';
		}
		
		return $classes;

	}
	
	
	/*
	 * Returns correct path for custom css or javascript code
	 *
	 *
	 */
	public static function correct_code( $str ) {
		
		$str = str_replace( '{imagepath}', self::plug_directory().'assets/images/', 
			  str_replace( '&lt;', '<',
			  str_replace( '&gt;', '>',
			
			 $str 
		) ) );
		return $str;

	}
	
	/*
	 * Initialize Styles Switch
	 *
	 *
	 */
	public static function init_ss( $str ) {
		
		echo '<div id="styleswitch"><img id="st-logo" src="'.self::plug_directory().'assets/images/blank.gif" alt="" /></div>'."\n\n";
		
	}
	
	
	
	
		
	/*
	 * Returns default custom code
	 *
	 *
	 */
	public static function default_code( $type ) {
		
		if ( $type == 'css' )  {
			return 'body.pat-1 #stage {
	visibility: visible;
}

body.no-pat #stage {
	visibility: hidden;
}

/* Theme Red */
body.color-1 blockquote {
    border-left-color: #EE6E73;
}

body.color-1 .loader .spinner {
    border-left-color: #EE6E73;
	border-top-color: #EE6E73;
	border-right-color: #EE6E73;
}

body.color-1 .author-card {
    border-top-color: #EE6E73;
}

body.color-1 blockquote:before,
body.color-1 a.link,
body.color-1 .controls .req-icon,
body.color-1 .social-list li:hover a,
body.color-1 .entry-mark a,
body.color-1 .entry-content h1,
body.color-1 .entry-content h2,
body.color-1 .entry-content h3,
body.color-1 .entry-content h4,
body.color-1 .entry-content h5,
body.color-1 .entry-content h6,
body.color-1 .entry-content a,
body.color-1 .entry-meta.wrap li a
{
    color: #EE6E73;
}

body.color-1 a.link:hover,
body.color-1 .entry-mark a:hover,
body.color-1 .entry-content a:hover,
body.color-1 .entry-meta.wrap li a:hover {
	color: #E85254;
}

body.color-1 .portfolio-item .category,
body.color-1 .sidebar-container .widget-title:after,
body.color-1 .comment-content a.respond {
	background: #EE6E73;
}

body.color-1 .button-bg-primary {
	border-color: #EE6E73 !important;
	background: #EE6E73 !important;
}

body.color-1 .sticky .title-style-fieldset:before {
    background: transparent url({imagepath}sticky.png) no-repeat;
}


/* Theme Blue */
body.color-2 blockquote {
    border-left-color: #46BCEC;
}

body.color-2 .loader .spinner {
    border-left-color: #46BCEC;
	border-top-color: #46BCEC;
	border-right-color: #46BCEC;
}

body.color-2 .author-card {
    border-top-color: #46BCEC;
}

body.color-2 blockquote:before,
body.color-2 a.link,
body.color-2 .controls .req-icon,
body.color-2 .social-list li:hover a,
body.color-2 .entry-mark a,
body.color-2 .entry-content h1,
body.color-2 .entry-content h2,
body.color-2 .entry-content h3,
body.color-2 .entry-content h4,
body.color-2 .entry-content h5,
body.color-2 .entry-content h6,
body.color-2 .entry-content a,
body.color-2 .entry-meta.wrap li a
{
    color: #46BCEC;
}

body.color-2 a.link:hover,
body.color-2 .entry-mark a:hover,
body.color-2 .entry-content a:hover,
body.color-2 .entry-meta.wrap li a:hover {
	color: #2EAADC;
}

body.color-2 .portfolio-item .category,
body.color-2 .sidebar-container .widget-title:after,
body.color-2 .comment-content a.respond {
	background: #46BCEC;
}

body.color-2 .button-bg-primary {
	border-color: #46BCEC !important;
	background: #46BCEC !important;
}

body.color-2 {
    color: #383737;
}

body.color-2 h1,
body.color-2 h2,
body.color-2 h3,
body.color-2 h4,
body.color-2 h5,
body.color-2 h6,
body.color-2 .h1,
body.color-2 .h2,
body.color-2 .h3,
body.color-2 .h4,
body.color-2 .h5,
body.color-2 .h6,
body.color-2 li.multi-column > ul .multi-column-title,
body.color-2 li.multi-column > ul > li > a,
body.full-slideshow-header.color-2 .menu-scroll-fixed li.multi-column > ul .multi-column-title,
body.full-slideshow-header.color-2 .menu-scroll-fixed li.multi-column > ul > li > a 
{
	color: #383737;
}

body.full-slideshow-header.color-2 .menu-scroll-fixed li.multi-column > ul > li > a:hover {
	color: #383737 !important;
}

body.color-2 .sticky .title-style-fieldset:before {
    background: transparent url({imagepath}sticky-blue.png) no-repeat;
}

/* Theme Orange */
body.color-3 blockquote {
    border-left-color: #EC9F46;
}

body.color-3 .loader .spinner {
    border-left-color: #EC9F46;
	border-top-color: #EC9F46;
	border-right-color: #EC9F46;
}

body.color-3 .author-card {
    border-top-color: #EC9F46;
}

body.color-3 blockquote:before,
body.color-3 a.link,
body.color-3 .controls .req-icon,
body.color-3 .social-list li:hover a,
body.color-3 .entry-mark a,
body.color-3 .entry-content h1,
body.color-3 .entry-content h2,
body.color-3 .entry-content h3,
body.color-3 .entry-content h4,
body.color-3 .entry-content h5,
body.color-3 .entry-content h6,
body.color-3 .entry-content a,
body.color-3 .entry-meta.wrap li a
{
    color: #EC9F46;
}

body.color-3 a.link:hover,
body.color-3 .entry-mark a:hover,
body.color-3 .entry-content a:hover,
body.color-3 .entry-meta.wrap li a:hover {
	color: #E18C2A;
}

body.color-3 .portfolio-item .category,
body.color-3 .sidebar-container .widget-title:after,
body.color-3 .comment-content a.respond {
	background: #EC9F46;
}

body.color-3 .button-bg-primary {
	border-color: #EC9F46 !important;
	background: #EC9F46 !important;
}

body.color-3 {
    color: #383737;
}

body.color-3 h1,
body.color-3 h2,
body.color-3 h3,
body.color-3 h4,
body.color-3 h5,
body.color-3 h6,
body.color-3 .h1,
body.color-3 .h2,
body.color-3 .h3,
body.color-3 .h4,
body.color-3 .h5,
body.color-3 .h6,
body.color-3 li.multi-column > ul .multi-column-title,
body.color-3 li.multi-column > ul > li > a,
body.full-slideshow-header.color-3 .menu-scroll-fixed li.multi-column > ul .multi-column-title,
body.full-slideshow-header.color-3 .menu-scroll-fixed li.multi-column > ul > li > a 
{
	color: #383737;
}

body.full-slideshow-header.color-3 .menu-scroll-fixed li.multi-column > ul > li > a:hover {
	color: #383737 !important;
}

body.color-3 .sticky .title-style-fieldset:before {
    background: transparent url({imagepath}sticky-orange.png) no-repeat;
}


/* Fonts */
/* latin-ext */
@font-face {
  font-family: \'Oswald\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Oswald Regular\'), local(\'Oswald-Regular\'), url(https://fonts.gstatic.com/s/oswald/v11/Qw6_9HvXRQGg5mMbFR3Phn-_kf6ByYO6CLYdB4HQE-Y.woff2) format(\'woff2\');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: \'Oswald\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Oswald Regular\'), local(\'Oswald-Regular\'), url(https://fonts.gstatic.com/s/oswald/v11/_P8jt3Y65hJ9c4AzRE0V1OvvDin1pK8aKteLpeZ5c0A.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* latin-ext */
@font-face {
  font-family: \'Oswald\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Oswald Bold\'), local(\'Oswald-Bold\'), url(https://fonts.gstatic.com/s/oswald/v11/dI-qzxlKVQA6TUC5RKSb34X0hVgzZQUfRDuZrPvH3D8.woff2) format(\'woff2\');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: \'Oswald\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Oswald Bold\'), local(\'Oswald-Bold\'), url(https://fonts.gstatic.com/s/oswald/v11/bH7276GfdCjMjApa_dkG6ZBw1xU1rKptJj_0jans920.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}

/* latin */
@font-face {
  font-family: \'Dancing Script\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Dancing Script\'), local(\'DancingScript\'), url(https://fonts.gstatic.com/s/dancingscript/v7/DK0eTGXiZjN6yA8zAEyM2ZsM3FTMmj2kTPH3yX99Yaw.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* latin */
@font-face {
  font-family: \'Dancing Script\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Dancing Script Bold\'), local(\'DancingScript-Bold\'), url(https://fonts.gstatic.com/s/dancingscript/v7/KGBfwabt0ZRLA5W1ywjowdZ1pH3HRdX2lLFmZY4nwnk.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}

/* cyrillic-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsIPxuqWfQuZGbz5Rz4Zu1gk.woff2) format(\'woff2\');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsENRpQQ4njX3CLaCqI4awdk.woff2) format(\'woff2\');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsET2KMEyTWEzJqg9U8VS8XM.woff2) format(\'woff2\');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsMH5J2QbmuFthYTFOnnSRco.woff2) format(\'woff2\');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsDcCYxVKuOcslAgPRMZ8RJE.woff2) format(\'woff2\');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsNKDSU5nPdoBdru70FiVyb0.woff2) format(\'woff2\');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 400;
  src: local(\'Roboto Condensed\'), local(\'RobotoCondensed-Regular\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/Zd2E9abXLFGSr9G3YK2MsH4vxAoi6d67T_UKWi0EoHQ.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nBYyuMfI6pbvLqniwcbLofP2Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nIT75Viso9fCesWUO0IzDUX2Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nL8EBb1YR1F8PhofwHtObrz2Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nAro84VToOve-uw23YSmBS72Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nACS0ZgDg4kY8EFPTGlvyHP2Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nGPMCwzADhgEiQ8LZ-01G1L2Ot9t5h1GRSTIE78Whtoh.woff2) format(\'woff2\');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: \'Roboto Condensed\';
  font-style: normal;
  font-weight: 700;
  src: local(\'Roboto Condensed Bold\'), local(\'RobotoCondensed-Bold\'), url(https://fonts.gstatic.com/s/robotocondensed/v13/b9QBgL0iMZfDSpmcXcE8nPX2or14QGUHgbhSBV1Go0E.woff2) format(\'woff2\');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}


body.nav-font-1 .menu-wrapper {
	font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;
	font-weight: 400;
}


body.nav-font-2 .menu-wrapper {
	font-family: \'Oswald\', sans-serif;
	font-weight: 400;
}


body.nav-font-3 .menu-wrapper {
	font-family: Georgia, serif;
	font-weight: 400;
}


body.nav-font-4 .menu-wrapper {
	font-family: \'Dancing Script\', cursive;
	text-transform: none !important;
	font-weight: 700;
}

body.nav-font-5 .menu-wrapper {
	font-family: \'Roboto Condensed\', sans-serif;
	font-weight: 400;
}


body.body-font-1,
body.body-font-1 .font-normal {
	font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;
}


body.body-font-2,
body.body-font-2 .font-normal {
	font-family: \'Oswald\', sans-serif;
}


body.body-font-3,
body.body-font-3 .font-normal {
	font-family: Georgia, serif;
}


body.body-font-4,
body.body-font-4 .font-normal {
	font-family: \'Dancing Script\', cursive;
}

body.body-font-5,
body.body-font-5 .font-normal {
	font-family: \'Roboto Condensed\', sans-serif;
}';
		}
		
		if ( $type == 'js' )  {
			return '( function($) {
"use strict";
    $( function() {

		var $options = {
			1: {
				title: "General Settings",
				icon: \'fa fa-cogs\',
				panel: true,
				active: true,
				fields: {
					1: {
						fieldTitle: \'Page Background Pattern\',
						field: \'selectBox\',
						target: \'body\',
						actionArgs: \'pat-1 no-pat\',
						action: \'switchClass\',
						name: \'bgpt\',
						options: {
							1: {							
								value: \'pat-1\',
								img: \'{imagepath}pat1.png\',
								width: 46,
								height: 46,
								default: true
							},
							2: {
								value: \'no-pat\',
								img: \'{imagepath}transparent.png\',
								width: 46,
								height: 46
								
							}
						}
					},
					2: {
						fieldTitle: \'Page Background Color\',
						field: \'color\',
						id: \'bg\',
						target: \'body\',
						action: \'backgroundChange\'
					},
					3: {
						fieldTitle: \'Home Slider Skin\',
						field: \'selectBox\',
						name: \'slider\',
						target: \'.carousel\',
						action: \'switchClass\',
						actionArgs: \'light dark\',
						options: {
							1: {							
								value: \'dark\',
								text: \'Dark\',
								width: 52,
								height: 25,
								default: true
							},
							2: {
								value: \'light\',
								text: \'Light\',
								width: 52,
								height: 25
							}
						}
					}
					
					
				}
			},
			/* ----  */
			2: {
				title: "Color Schemes",
				icon: \'fa fa-paint-brush\',
				panel: true,
				active: true,
				fields: {
						1: {
						fieldTitle: \'Choose Your Color Scheme\',
						field: \'selectBox\',
						target: \'body\',
						actionArgs: \'color-1 color-2 color-3\',
						action: \'switchClass\',
						name: \'themecolor\',
						options: {
							1: {							
								value: \'color-1\',
								img: \'{imagepath}color-red.png\',
								width: 46,
								height: 46,
								default: true
							},
							2: {
								value: \'color-2\',
								img: \'{imagepath}color-blue.png\',
								width: 46,
								height: 46
								
							},
							3: {
								value: \'color-3\',
								img: \'{imagepath}color-orange.png\',
								width: 46,
								height: 46
							}
						}
					}
					
				}
			},
			/* ---- */
			3: {
				title: "Custom Fonts",
				icon: \'fa fa-font\',
				panel: true,
				active: true,
				fields: {
					1: {
						fieldTitle: \'Primary Navigation\',
						field: \'selectBox\',
						target: \'body\',
						actionArgs: \'nav-font-1 nav-font-2 nav-font-3 nav-font-4 nav-font-5\',
						action: \'switchClass\',
						name: \'themefonts\',
						options: {
							1: {							
								value: \'nav-font-1\',
								img: \'{imagepath}font-1.png\',
								width: 38,
								height: 38,
								default: true
							},
							2: {
								value: \'nav-font-2\',
								img: \'{imagepath}font-2.png\',
								width: 38,
								height: 38
								
							},
							3: {
								value: \'nav-font-3\',
								img: \'{imagepath}font-3.png\',
								width: 38,
								height: 38
							},
							4: {
								value: \'nav-font-4\',
								img: \'{imagepath}font-4.png\',
								width: 38,
								height: 38
							},						
							5: {
								value: \'nav-font-5\',
								img: \'{imagepath}font-5.png\',
								width: 38,
								height: 38
							}
							
						}
					},
					2: {
						fieldTitle: \'Body\',
						field: \'selectBox\',
						target: \'body\',
						actionArgs: \'body-font-1 body-font-2 body-font-3 body-font-4 body-font-5\',
						action: \'switchClass\',
						name: \'themefonts-body\',
						options: {
							1: {							
								value: \'body-font-1\',
								img: \'{imagepath}font-1.png\',
								width: 38,
								height: 38,
								default: true
							},
							2: {
								value: \'body-font-2\',
								img: \'{imagepath}font-2.png\',
								width: 38,
								height: 38
								
							},
							3: {
								value: \'body-font-3\',
								img: \'{imagepath}font-3.png\',
								width: 38,
								height: 38
							},
							4: {
								value: \'body-font-4\',
								img: \'{imagepath}font-4.png\',
								width: 38,
								height: 38
							},						
							5: {
								value: \'body-font-5\',
								img: \'{imagepath}font-5.png\',
								width: 38,
								height: 38
							}
							
						}
						
					}
					
					
				}
			}
			/* ---- */
		}
		
		styleswitch.style({
			height     : 620,
			direction  : \'right\',
			top        : \'4em\',
			width      : 300,
			height     : 500,
			background : \'#fff\',
			color      : \'#333\',
			button     :\'&lt;i class="fa fa-cog fa-spin fa-2x"&gt;&lt;/i&gt;\'
		});
		
		
		styleswitch.addAction({
			name: \'backgroundChange\',
			trigger: \'change\',
			action: function(event) {
				var $this   = $(this);
				var $val    = $this.val();
				var $target = event.data.target;
		
				$( $target ).css({
					\'background-color\': $val
					
				});
				
				
				/*
				document.styleSheets[0].addRule( \'.image:after\', \'background-color: \'+$val+\' !important\' );
				document.styleSheets[0].insertRule( \'.image:after { background-color: \'+$val+\' !important }\', 0);	
				*/
				
			}
		});		
		
		styleswitch.addOptions($options);
		
		
		/*
		
		$( \'input[name="bgpt[]"]\' ).prev( \'span\' ).on( \'click\', function() {
			var _v   = $( this ).next( \'input\' ).val();
			
			if ( _v == \'side-nav-left\' ) {
				$( \'#styleswitch\' ).css( {\'left\': \'auto\', \'right\': 0 } );

			} else {
				$( \'#styleswitch\' ).css( {\'right\': \'auto\', \'left\': 0 } );
			}

		});			
		
		
		$( \'input[name="slider[]"]\' ).prev( \'span\' ).on( \'click\', function() {
			var _v   = $( this ).next( \'input\' ).val();
			
			if ( _v == \'enable\' ) {
				//do something

			} else {
				//do something
			}

		});	
		
		*/
		
		
		

    } );
} ) ( jQuery );';
		}
			
	}
		
		
		/**
		 * Save the capability for this plugin settings
		 *
		 */
		public static function save() {
			
		
			if ( isset( $_POST[ 'custom_meta' ] ) ) {
				$id                         = 1;
				$raw_submitted_settings     = $_POST[ 'custom_meta' ];
				$parsed_submitted_settings  = json_decode( stripslashes( $raw_submitted_settings ), true );
				$submitted_settings         = array();
				
		
				foreach ( $parsed_submitted_settings as $index => $value ) {
					$name = $value[ 'name' ];
					
					
					
					// find values between square brackets
					preg_match_all( "/\[(.*?)\]/", $name, $matches );
		
					if ( isset( $matches[1][0] ) && isset( $matches[1][1] ) ) {
						$location = $matches[1][0];
						$setting = $matches[1][1];
						
						
						$submitted_settings[$location][$setting] = $value[ 'value' ];
					}
				}
				update_option( 'uix_ss_opt_capabilitypages_options_'.$id, $submitted_settings );
				update_option( 'uix_ss_opt_capabilitypages_result', $_POST[ 'result' ] );
				
				
			}
			
	
			wp_die();
	
		}	
		
		
		
			
	
}

add_action( 'plugins_loaded', array( 'UixThemeSwitch', 'init' ) );
