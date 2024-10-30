<?php
if( ! defined( 'ABSPATH' ) ) exit;

class Browser_Check_Settings {
    private $options;

    private $styles;

    function __construct() {
        $this->styles = array(
            'basic' => __( 'Basic', 'browser-check' ),
            'minimal' => __( 'Minimal', 'browser-check' ),
            'none' => __( 'None', 'browser-check' )
        );

        add_action( 'admin_menu', array( $this, 'add_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public static function add_default()
    {
        $default_option = array(
            'chrome' => '',
            'firefox' => '',
            'opera' => '',
            'safari' => '',
            'msie' => '',
            'javascript' => '',
            'cookie' => '',
            'flash' => '',
            'style' => 'basic',
            'hide_cancel' => '',
            'time_of_cancellation' => '30',
            'time_between_checks' => '1'
        );
        $option = get_option( 'browser_check' );
        $option = ( $option and is_array( $option ) ) ? array_merge( $default_option, $option ) : $default_option;
        add_option( 'browser_check', $option );
    }

    public function enqueue_scripts( $hook ) {
        if( $hook != 'settings_page_browser-check-settings' ) return;
        wp_enqueue_script( 'browser-check-admin', BROWSER_CHECK_URL . 'assets/js/admin.min.js', array( 'jquery' ) );
    }

    public function add_page() {
        add_options_page(
            __( 'Settings', 'browser-check' ),
            __( 'Browser check', 'browser-check' ),
            'manage_options',
            'browser-check-settings',
            array( $this, 'echo_page' )
        );
    }

    public function echo_page() {
        $this->options = get_option( 'browser_check' );
        include BROWSER_CHECK_PATH . 'templates/settings.php';
    }

    public function register_settings() {
        register_setting(
            'browser_check',
            'browser_check',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'browsers',
            __( 'Browsers', 'browser-check' ),
            array( $this, 'echo_section_browsers' ),
            'browser-check-settings'
        );

        add_settings_field(
            'chrome',
            __( 'Google Chrome', 'browser-check' ),
            array( $this, 'echo_field_chrome' ),
            'browser-check-settings',
            'browsers'
        );

        add_settings_field(
            'firefox',
            __( 'Mozilla Firefox', 'browser-check' ),
            array( $this, 'echo_field_firefox' ),
            'browser-check-settings',
            'browsers'
        );

        add_settings_field(
            'opera',
            __( 'Opera', 'browser-check' ),
            array( $this, 'echo_field_opera' ),
            'browser-check-settings',
            'browsers'
        );

        add_settings_field(
            'safari',
            __( 'Safari', 'browser-check' ),
            array( $this, 'echo_field_safari' ),
            'browser-check-settings',
            'browsers'
        );

        add_settings_field(
            'msie',
            __( 'Internet Explorer', 'browser-check' ) . ' (' . __( 'Edge', 'browser-check' ) . ')',
            array( $this, 'echo_field_msie' ),
            'browser-check-settings',
            'browsers'
        );

        add_settings_section(
            'other',
            __( 'Other', 'browser-check' ),
            array( $this, 'echo_section_other' ),
            'browser-check-settings'
        );

        add_settings_field(
            'javascript',
            __( 'JavaScript', 'browser-check' ),
            array( $this, 'echo_field_javascript' ),
            'browser-check-settings',
            'other'
        );

        add_settings_field(
            'cookie',
            __( 'Cookie', 'browser-check' ),
            array( $this, 'echo_field_cookie' ),
            'browser-check-settings',
            'other'
        );

        add_settings_field(
            'flash',
            __( 'Adobe Flash Player', 'browser-check' ),
            array( $this, 'echo_field_flash' ),
            'browser-check-settings',
            'other'
        );

        add_settings_section(
            'display',
            __( 'Display', 'browser-check' ),
            array( $this, 'echo_section_display' ),
            'browser-check-settings'
        );

        add_settings_field(
            'style',
            __( 'Style', 'browser-check' ),
            array( $this, 'echo_field_style' ),
            'browser-check-settings',
            'display'
        );

        add_settings_field(
            'hide_cancel',
            __( 'Hide cancellation button', 'browser-check' ),
            array( $this, 'echo_field_hide_cancel' ),
            'browser-check-settings',
            'display'
        );

        add_settings_field(
            'time_of_cancellation',
            __( 'Time of cancellation', 'browser-check' ),
            array( $this, 'echo_field_time_of_cancellation' ),
            'browser-check-settings',
            'display'
        );

        add_settings_field(
            'time_between_checks',
            __( 'Time between checks', 'browser-check' ),
            array( $this, 'echo_field_time_between_checks' ),
            'browser-check-settings',
            'display'
        );
    }

    public function echo_section_browsers() {
        echo '<p>', __( 'For these browsers, specify the minimum required version. ' .
                'Leave field blank if the browser does not have to be checked.', 'browser-check' ), '</p>';
    }

    public function echo_section_other() {
        echo '<p>', __( "Other checks user's browser.", 'browser-check' ), '</p>';
    }

    public function echo_section_display() {
        echo '<p>', __( 'Settings display messages.', 'browser-check' ), '</p>';
    }

    public function sanitize( $input ) {
        return $input;
    }

    public function echo_field_number( $option_name ) {
        echo '<input type="number" id="browser_check_' . $option_name  . '" name="browser_check[' . $option_name .
            ']" value="' . $this->options[$option_name] . '" min="0">';
    }

    public function echo_field_chrome() {
        $this->echo_field_number( 'chrome' );
    }

    public function echo_field_firefox() {
        $this->echo_field_number( 'firefox' );
    }

    public function echo_field_opera() {
        $this->echo_field_number( 'opera' );
    }

    public function echo_field_safari() {
        $this->echo_field_number( 'safari' );
    }

    public function echo_field_msie() {
        $this->echo_field_number( 'msie' );
    }

    public function echo_field_checkbox( $option_name ) {
        echo '<input type="checkbox" id="browser_check_' . $option_name  .
            '" name="browser_check[' . $option_name . ']" value="1"';
        checked( $this->options[$option_name], 1 );
        echo '>';
    }

    public function echo_field_javascript() {
        $this->echo_field_checkbox( 'javascript' );
    }

    public function echo_field_cookie() {
        $this->echo_field_checkbox( 'cookie' );
        echo '<p class="description">', __( 'JavaScript is required to check Cookie.', 'browser-check' ), '</p>';
    }

    public function echo_field_flash() {
        $this->echo_field_number( 'flash' );
        echo '<p class="description">',
        __( 'Specify a minimum required version of Flash Player. Leave field blank if check is not needed. ' .
            'JavaScript is required for check.', 'browser-check' ),
        '</p>';
    }

    public function echo_field_style() {
        $html = '<select id="browser_check_style" name="browser_check[style]">';
        foreach( $this->styles as $style_name => $style_title ) {
            $html .= '<option value="' . $style_name . '"' .
                selected( $this->options['style'], $style_name, false ) . '>' .
                __( $style_title, 'browser-check' ) . '</option>';
        }
        $html .= '</select>';
        echo $html;
        echo '<style>.description-style{display: none;}</style>',
        '<p class="description description-style" id="description-style-basic">',
        __( 'The basic set of CSS. It includes styles for different screen sizes.', 'browser-check' ),
        '</p>',
        '<p class="description description-style" id="description-style-minimal">',
        __( 'The minimum set of CSS. Select if you want to write most styles.', 'browser-check' ),
        '</p>',
        '<p class="description description-style" id="description-style-none">',
        __( 'CSS does not apply. Select if you want to write a styles completely.', 'browser-check' ),
        '</p>';
    }

    public function echo_field_hide_cancel() {
        $this->echo_field_checkbox( 'hide_cancel' );
    }

    public function echo_field_time_of_cancellation() {
        $this->echo_field_number( 'time_of_cancellation' );
        echo ' ', __( 'Minutes', 'browser-check' ),
        '<p class="description">',
        __( 'User can hide the message on check of browser for this time. ' .
            'Not recommended leave field empty or equal to 0, if hide cancellation button checkbox not selected. ' .
            'Because then the message will be closed for the session. ' .
            'Duration of session depends on settings of browser.', 'browser-check' ),
        '</p>';
    }

    public function echo_field_time_between_checks() {
        $this->echo_field_number( 'time_between_checks' );
        echo ' ', __( 'Days', 'browser-check' ),
        '<p class="description">',
        __( 'Successful check hide the message on check of browser for this time. ' .
            'Not recommended leave field empty or equal to 0. ' .
            'Because then the message will be closed for the session. ' .
            'Duration of session depends on settings of browser.', 'browser-check' ),
        '</p>';
    }
}