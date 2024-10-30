<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="browser-check-browser" class="browser-check browser-check-message">
    <?= sprintf(
        __( 'Your browser %s version %s outdated. The site may not work properly. ' .
        'Update your browser or download and install the new one:', 'browser-check' ),
        '<span class="browser-check-browser-title">' . $browser_title . '</span>',
        '<span class="browser-check-browser-version">' . $browser_version . '</span>'
    ); ?>
    <br>
    <a href="https://www.google.com/chrome/" target="_blank"><?= __( 'Google Chrome', 'browser-check' ); ?></a>,
    <a href="https://www.mozilla.org/firefox/" target="_blank"><?= __( 'Mozilla Firefox', 'browser-check' ); ?></a>,
    <a href="http://www.opera.com/" target="_blank"><?= __( 'Opera', 'browser-check' ); ?></a>.
</div>
