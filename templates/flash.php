<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="browser-check-flash" class="browser-check browser-check-message" data-required-version="<?= $required_version ?>" style="display: none;">
    <?= sprintf(
        __( 'Your Flash Player version %s outdated. To work site requires Flash Player did not lower than version %s.', 'browser-check' ),
        '<span class="browser-check-flash-current-version"></span>',
        '<span class="browser-check-flash-required-version">' . $required_version . '</span>'
    ); ?>
    <a href="https://get.adobe.com/flashplayer/" target="_blank">
        <?= __( 'Update your Flash Player.', 'browser-check' ); ?>
    </a>
</div>
<div id="browser-check-no-flash" style="display: none;">
    <?= __( 'To work site requires Flash Player.', 'browser-check' ); ?>
    <a href="https://get.adobe.com/flashplayer/" target="_blank">
        <?= __( 'Install Flash Player.', 'browser-check' ); ?>
    </a>
</div>