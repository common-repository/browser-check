<?php
/*
Plugin Name: Browser Check
Plugin URI: http://bear-coder.com/project/browser-check/
Version: 1.2.0
Text Domain: browser-check
Description: Check browser version, JavaScript, Cookie, Adobe Flash Player.
Author: Yaroslav Klochikhin
Author URI: http://bear-coder.com
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BROWSER_CHECK_PATH', plugin_dir_path( __FILE__ ) );
define( 'BROWSER_CHECK_URL', plugins_url( '/', __FILE__ ) );

function browser_check_i18n() {
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'browser-check', false, $plugin_dir . '/i18n/' );
}

add_action( 'plugins_loaded', 'browser_check_i18n' );

if( is_admin() ) {
    require_once BROWSER_CHECK_PATH . 'class-settings.php';

    function browser_check_activate() {
        Browser_Check_Settings::add_default();
    }
    register_activation_hook( __FILE__, 'browser_check_activate' );

    new Browser_Check_Settings();
}
else {
    require_once BROWSER_CHECK_PATH . 'class-notice.php';
    new Browser_Check_Notice();
}