<?php
if( ! defined( 'ABSPATH' ) ) exit;

class Browser_Check_Browser
{
    private $browser_name = null;
    private $browser_version = null;
    private $check_browser = null;

    private $browsers;

    function __construct() {
        $this->browsers = array(
            'firefox' => __( 'Mozilla Firefox', 'browser-check' ),
            'msie' => __( 'Internet Explorer', 'browser-check' ),
            'opera' => __( 'Opera', 'browser-check' ),
            'chrome' => __( 'Google Chrome', 'browser-check' ),
            'safari' => __( 'Safari', 'browser-check' )
        );
    }

    private function determine() {
        $agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
        preg_match_all( '~opera|msie|trident|edge|chrome|firefox|safari~i', $agent, $match_browser_words );
        $browser_words = $match_browser_words[0];
        $browser_name = false;
        $browser_version = false;
        $match_version = false;
        if( in_array( 'opera', $browser_words ) ) {
            $browser_name = 'opera';
            preg_match( "~($browser_name|version)[/ ]?([0-9.]*)$~i", $agent, $match_version );
        }
        elseif( in_array( 'msie', $browser_words )
            or in_array( 'edge', $browser_words )
            or in_array( 'trident', $browser_words ) ) {
            $browser_name = 'msie';
            preg_match( "~($browser_name|edge|rv:)[/ ]?([0-9.]*)~i", $agent, $match_version );
        }
        elseif( in_array( 'chrome', $browser_words ) ) {
            $browser_name = 'chrome';
        }
        elseif( in_array( 'firefox', $browser_words ) ) {
            $browser_name = 'firefox';
        }
        elseif( in_array( 'safari', $browser_words ) ) {
            $browser_name = 'safari';
            preg_match( "~(version)[/ ]?([0-9.]*)~i", $agent, $match_version );
        }
        if( ! $browser_name ) return;
        if( ! $match_version ) preg_match( "~($browser_name)[/ ]?([0-9.]*)~i", $agent, $match_version );
        if( $match_version ) {
            if( strpos( $match_version[2], '.' ) !== false )
                $browser_version = substr( $match_version[2], 0, strpos( $match_version[2], '.' ) );
            else
                $browser_version = $match_version[2];
        }
        if( $browser_name == 'safari' and ( $browser_version > 80 or ! $browser_version ) ) $browser_version = 2;
        $this->browser_name = $browser_name;
        $this->browser_version = $browser_version;
    }

    public function get_name() {
        if( $this->browser_name === null )  $this->determine();
        return $this->browser_name;
    }

    public function get_version( $format = true ) {
        if( $this->browser_version === null )  $this->determine();
        if( $format and $this->browser_name == 'safari' and $this->browser_version == 2 )
            return '2-';
        return $this->browser_version;
    }

    public function get_title() {
        if( $this->browser_name === null )  $this->determine();
        if( $this->browser_name == 'msie' and $this->browser_version >= 12 )
            return __( 'Edge', 'browser-check' );
        return $this->browsers[$this->browser_name];
    }

    public function check() {
        if( $this->check_browser === null ) {
            $this->check_browser = true;
            $option = get_option( 'browser_check' );
            $browser_name = $this->get_name();
            if( is_numeric( $option[$browser_name] ) ) {
                $min_browser_version = $option[$browser_name];
                $browser_version = $this->get_version( false );
                if( $min_browser_version > $browser_version )
                    $this->check_browser = false;
            }
        }
        return $this->check_browser;
    }
}