(function( $ ) {
    $( document ).ready( function() {
        var $browser_check_style = $( '#browser_check_style' );

        var browser_check_style_val = $browser_check_style.val();

        $( '#description-style-' + browser_check_style_val ).show();

        $browser_check_style.change( function() {
            browser_check_style_val = $browser_check_style.val();
            $( '.description-style' ).hide();
            $( '#description-style-' + browser_check_style_val ).show();
        } );

        $( '#browser_check_javascript' ).change( function() {
            if( ! $( this ).is( ':checked' ) ) {
                $( '#browser_check_cookie' ).prop( 'checked', false );
                $( '#browser_check_flash' ).val( '' );
            }
        } );

        $( '#browser_check_cookie' ).change( function() {
            if( $( this ).is( ':checked' ) ) {
                $( '#browser_check_javascript' ).prop( 'checked', true );
            }
        } );

        $( '#browser_check_flash' ).change( function() {
            if( $( this ).val() >= 0 ) {
                $( '#browser_check_javascript' ).prop( 'checked', true );
            }
        } );

        $( '#browser_check_hide_cancel' ).change( function() {
            if( $( this ).is( ':checked' ) ) {
                $( '#browser_check_time_of_cancellation' ).val( '' );
            }
        } );

        $( '#browser_check_time_of_cancellation' ).change( function() {
            if( $( this ).val() >= 0 ) {
                $( '#browser_check_hide_cancel' ).prop( 'checked', false );
            }
        } );
    } );
})( jQuery );