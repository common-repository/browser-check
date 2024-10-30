=== Browser Check ===
Contributors: Yarick
Tags: browser, check, javascript, cookie, flash, old, block
Requires at least: 4.0.0
Tested up to: 4.4.1
Stable tag: 1.2.0
License: GPLv2

Check browser version, JavaScript, Cookie, Adobe Flash Player version. Warn user if old.

== Description ==

This plugin notifies user if browser check failed. Available checks:

* Browser version (Chrome, Firefox, Safari, Opera, IE)
* JavaScript enable
* Cookie enable (require JavaScript)
* Adobe Flash Player version (require JavaScript)

You can select style for notice. Or disable plugin CSS and write own.

User can close notice to time you set in minutes. It use cookie. You can disable close button.

Also you may set next check time in days. When check is successful.

Browser Check may be integrated with theme. Just place this code: "do_action('browser_check')" in your theme. And disable plugin css from "Settings -> Browser Check" page and write own css for best look in your theme.

Supported languages

* English
* Russian

== Installation ==

1. Upload 'browser-check' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins’ SubPanel in WordPress
3. Configure plugin (admin panel -> Settings -> Browser Check)

== Screenshots ==

1. Warn user
2. Backend

== Changelog ==

= 1.0.0 =
* First release

= 1.1.0 =
* Improved source code
* Improved browser detect
* Add Edge browser

= 1.2.0 =
* fix javascript in admin panel
* fix "None" style
* add "browser_check" action for use in themes
* add "hide cancellation button" option