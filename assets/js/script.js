(function( $ ) {
    $( document ).ready( function() {
        var Browser_Check = {
            $el: $( '#browser-check-notice' ),
            options: wp_option,
            checks_result: {},
            init: function() {
                if( this.$el.length ) {
                    this.cancel_link();
                    var self = this;
                    $( document ).on( 'successful_check', function( event, check_name ) {
                        self.successful_check_callback( check_name );
                    } );
                    this.checks_result['browser'] = 0;
                    if( this.options['cookie'] ) this.checks_result['cookie'] = 0;
                    if( this.options['flash'] ) this.checks_result['flash'] = 0;
                    this.browser();
                    if( this.options['cookie'] ) this.cookie();
                    if( this.options['flash'] ) this.flash();
                    this.wp_admin_bar_style();
                }
            },
            browser: function() {
                var $el = $( '#browser-check-browser' );
                if( ! $el.length ) $.event.trigger( 'successful_check', ['browser'] );
            },
            cookie: function() {
                var $el = $( '#browser-check-cookie' );
                if( ! navigator.cookieEnabled ) $el.show();
                else {
                    $el.remove();
                    $.event.trigger( 'successful_check', ['cookie'] );
                }
            },
            flash: function() {
                if( typeof swfobject !== 'undefined' ) {
                    var $el = $( '#browser-check-flash' );
                    var current_version = swfobject.getFlashPlayerVersion().major;
                    var required_version = $el.data( 'required-version' );
                    if( current_version < required_version ) {
                        if( current_version ) $( '.browser-check-flash-current-version' ).html( current_version );
                        else {
                            var html = $( '#browser-check-no-flash' ).html();
                            $el.html( html );
                        }
                        $el.show();
                    }
                    else {
                        $el.remove();
                        $.event.trigger( 'successful_check', ['flash'] );
                    }
                }
            },
            successful_check_callback: function( check_name ) {
                if( typeof this.checks_result[check_name] !== 'undefined' ) {
                    this.checks_result[check_name] = 1;
                    var check_complete = true;
                    $.each( this.checks_result, function( name, value ) {
                        if( value != 1 ) {
                            check_complete = false;
                            return false;
                        }
                    } );
                    if( check_complete ) {
                        this.set_cookie( 'success' );
                        this.$el.hide().remove();
                        $( document ).off( 'successful_check' );
                    }
                }
            },
            cancel_link: function() {
                if( this.options['hide_cancel'] ) return;
                var $cancel = $( '#browser-check-cancel' );
                if( ! $cancel.length ) {
                    var no_script_cancel = $( '#browser-check-no-script-cancel' ).text();
                    this.$el.append( no_script_cancel );
                }
                var self = this;
                $cancel.on( 'click', function( event ) {
                    event.preventDefault();
                    self.set_cookie( 'cancel' );
                    self.$el.hide().remove();
                } );
            },
            set_cookie: function( action ) {
                var time = false;
                switch( action ) {
                    case 'success':
                        if( this.options['time_between_checks'] ) {
                            time = this.options['time_between_checks'] / 1.0;
                            Cookies.set( 'browser_check', 'complete', { expires: time } );
                        }
                        else Cookies.set( 'browser_check', 'complete' );
                        break;
                    case 'cancel':
                        if( this.options['time_of_cancellation'] ) {
                            time = this.options['time_of_cancellation'] / 1440.0;
                            Cookies.set( 'browser_check', 'complete', { expires: time } );
                        }
                        else Cookies.set( 'browser_check', 'complete' );
                        break;
                    default:
                        break;
                }
            },
            wp_admin_bar_style: function() {
                if( this.$el.hasClass( 'integrated-to-theme' ) ) return;
                var wp_admin_bar = $( '#wpadminbar' );
                if( wp_admin_bar.length ) {
                    var height = wp_admin_bar.height();
                    $( '.browser-check-message:first' ).css( 'margin-top', height );
                }
            }
        };
        Browser_Check.init();
    } );
})( jQuery );