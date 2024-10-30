<?php
if( ! defined( 'ABSPATH' ) ) exit;

class Browser_Check_Notice
{
    private $browser = null;
    private $did_display = false;

    function __construct() {
        add_action( 'init', array( $this, 'construct_callback' ) );
    }

    public function construct_callback() {
        if( isset( $_COOKIE['browser_check'] ) and $_COOKIE['browser_check'] == 'complete' ) return;

        $option = get_option( 'browser_check' );

        if( isset( $_GET['browser_check'] ) and $_GET['browser_check'] == 'cancel' and ! $option['hide_cancel'] ) {
            if( $_COOKIE['browser_check'] == 'test' ) {
                $this->set_cookie( 'cancel' );
                wp_safe_redirect( home_url() );
                exit;
            }
            else return;
        }

        require_once BROWSER_CHECK_PATH . 'class-browser.php';
        $this->browser = new Browser_Check_Browser();

        if( $this->browser->check() and ! $option['javascript'] ) {
            $this->set_cookie( 'success' );
            return;
        }

        if( ! $this->browser->check() )
            add_action( 'browser_check_notice', array( $this, 'display_browser_message' ) );

        if( $option['javascript'] ) {
            add_action( 'browser_check_notice', array( $this, 'display_javascript_message' ) );

            if( $option['cookie'] )
                add_action( 'browser_check_notice', array( $this, 'display_cookie_message' ) );

            if( $option['flash'] )
                add_action( 'browser_check_notice', array( $this, 'display_flash_message' ) );
        }

        if( ! $this->browser->check() )
            add_action( 'browser_check_notice', array( $this, 'display_cancel' ) );
        elseif( $option['javascript'] )
            add_action( 'browser_check_notice', array( $this, 'display_no_script_cancel' ) );
        else return;

        $this->enqueue_styles();

        $this->enqueue_scripts();

        add_action( 'wp_footer', array( $this, 'display' ) );
        add_action( 'browser_check', array( $this, 'display' ) );

        if( ! isset( $_COOKIE['browser_check'] ) ) $this->set_cookie( 'test' );
    }

    private function set_cookie( $action ) {
        $option = get_option( 'browser_check' );
        $time = 0;
        switch( $action ) {
            case 'success':
                $value = 'complete';
                if( $option['time_between_checks'] )
                    $time = $option['time_between_checks'] * 24 * 60 * 60;
                break;
            case 'cancel':
                $value = 'complete';
                if( $option['time_of_cancellation'] )
                    $time = $option['time_of_cancellation'] * 60;
                break;
            case 'test':
                $value = 'test';
                break;
            default:
                $value = false;
                break;
        }
        if( $value ) {
            $time = ( $time == 0 ) ? 0 : time() + $time;
            setcookie( 'browser_check', $value, $time, COOKIEPATH, COOKIE_DOMAIN );
        }
    }

    private function enqueue_styles() {
        $option = get_option( 'browser_check' );
        if( $option['style'] and $option['style'] != 'none' ) {
            wp_enqueue_style( 'browser-check', BROWSER_CHECK_URL . 'assets/css/' . $option['style'] . '.min.css' );
        }
    }

    private function enqueue_scripts() {
        $option = get_option( 'browser_check' );
        $javascript_libraries = array( 'jquery', 'js-cookie' );
        if( $option['flash'] ) $javascript_libraries[] = 'swfobject';
        wp_enqueue_script( 'js-cookie', BROWSER_CHECK_URL . 'assets/js/js.cookie.min.js' );
        wp_enqueue_script( 'browser-check', BROWSER_CHECK_URL . 'assets/js/script.min.js', $javascript_libraries );
        wp_localize_script( 'browser-check', 'wp_option', $option );
    }

    public function display() {
        if( $this->did_display ) return;
        $this->did_display = true;

        $integrated = ( did_action( 'browser_check' ) ) ? ' class="integrated-to-theme"' : '';

        echo '<div id="browser-check-notice"', $integrated, '>';
        do_action( 'browser_check_notice' );
        echo '</div>';
    }

    public function display_browser_message() {
        if( $this->browser === null ) {
            require_once BROWSER_CHECK_PATH . 'class-browser.php';
            $this->browser = new Browser_Check_Browser();
        }
        $browser_name = $this->browser->get_name();
        $browser_title = $this->browser->get_title();
        $browser_version = $this->browser->get_version();
        include BROWSER_CHECK_PATH . 'templates/browser.php';
    }

    public function display_javascript_message() {
        include BROWSER_CHECK_PATH . 'templates/javascript.php';
    }

    public function display_cookie_message() {
        include BROWSER_CHECK_PATH . 'templates/cookie.php';
    }

    public function display_flash_message() {
        $option = get_option( 'browser_check' );
        $required_version = $option['flash'];
        include BROWSER_CHECK_PATH . 'templates/flash.php';
    }

    public function display_cancel() {
        $option = get_option( 'browser_check' );
        if( $option['hide_cancel'] ) return;
        include BROWSER_CHECK_PATH . 'templates/cancel.php';
    }

    public function display_no_script_cancel() {
        $option = get_option( 'browser_check' );
        if( $option['hide_cancel'] ) return;
        echo '<noscript id="browser-check-no-script-cancel">';
        include BROWSER_CHECK_PATH . 'templates/cancel.php';
        echo '</noscript>';
    }
}