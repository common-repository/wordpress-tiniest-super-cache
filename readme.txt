=== Plugin Name ===
Contributors: Ahlul Faradish Resha
Donate link: http://ahlul.web.id/donation
Tags: html,static,cache,page load,boost
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: 0.9.9

Wordpress tiniest cache plugin ever with just 2KB engine. Boost your page load time 10000 times faster, and save lots of your memory.

== Description ==

Wordpress tiniest cache plugin ever with just 2KB engine. Boost your page load time 10000 times faster, and save lots of your memory.

For Bugs & Feature request post your comment at http://ahlul.web.id/2011/11/06/wordpress-tiniest-super-cache.html

== Installation ==

1. Download it here. or search from WordPress Admin > Plugin > Add New.
1. Install and activate from WordPress Admin > Plugins page.
1. Go to the WordPress Admin Options page > Tiniest Super Cache, and set the options as you wish.
1. Enable cache feature.

== Frequently Asked Questions ==

= Are you sure this plugin can boost my page load time 10000 times faster? =

Yes, it make sense.. why? because this plugin save loaded page as static files. Then when the page is requested again this plugin will read directly the cached file.

This plugin will save lots of your memory, because if cached file is found this plugin will cut all wordpress process from the top. So as we know if you just hook wordpress process in middle (as others do) it will not work, because almost of wordpress process like database query performed before template is loaded.

= Will this plugin broke wordpress process? =

This plugin will cache every page that process by server, that mean if you have script that process by browser ie: javascript, will not cache by this plugin. So this will not break your theme.

= Will wp-admin cached by this plugin too? =

No, this plugin will ignore all url that contain "wp-".

= How the cached file flushed? =

This plugin will flush or will not use cached file if it receive GET or POST request. And can be flush too from this panel.

= Will this plugin return 404 status for 404 page? =

Hmm, since this plugin still at beta version I don't have much time to develop this plugin. So currently it will return status 200 (OK) for all cached url. But don't worry I'll upgrade this feature next time.

== Screenshots ==

No screenshoot...

== Changelog ==

= 0.9 =

FIRST VERSION (BETA) 

= 0.9.1 =

Fix bug of blank page that cause by empty cached file. Now it delete automatically if engine read empty file.

= 0.9.8 =

Fix some minor bugs, and add new feature for list hardcached page.

= 0.9.9 =

Add new option to ignore GET and POST

== Upgrade Notice ==

No update notice yet