<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<style>
    .wp-admin select, .wp-admin input[type=text], .wp-admin input[type=number] {
        min-width: 170px;
    }
</style>
<div class="wrap">
    <h2><?= __( 'Settings', 'browser-check' ) ?></h2>
    <p><?= __( "Check user's browser for compliance with the specified requirements. " .
            "User will be warned by a message if check fails.",
            'browser-check' ) ?>
    </p>
    <form method="post" action="options.php">
        <?php settings_fields( 'browser_check' ); ?>
        <?php do_settings_sections( 'browser-check-settings' ); ?>
        <?php submit_button(); ?>
    </form>
</div>