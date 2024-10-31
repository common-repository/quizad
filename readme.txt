=== QuizAd - Quiz Advertisement Plugin ===
Contributors: quizad
Tags: softpaywall, paywall, ads, zarabianie
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 1.5.4
License: LGPLv3 or later
Requires PHP: 5.4
License URL: http://www.gnu.org/licenses/lgpl-3.0.html

== Description ==

This plugin allows QuizAd advertisement platform to manage quiz links on wordpress website.

== Installation ==

== External tools ==

1. We use Google reCaptcha V3 technology to stop robots from random registrations (a form of attack).
   Accessing endpoint `https://www.recaptcha.net/recaptcha/api.js` serves that purpose.
2. We use our API endpoint, hosted on `api.contexter.net` domain.
3. We use Chart.js is open source and available under the MIT license.

== External HTML source code ==

This plugin injects HTML source code on end-user's page. That source code is injected in `head` and `body` part
(posts content, to be clear). The origin of those data is http://api.contexter.net . When registration is successfully
completed we deliver this source code when user enters `/wp-admin/admin.php?page=my-submenu-zaawansowane` admin page
and is stored in Wordpress database.

Later our visitors will have those HTML sections injected in their HTML source code in order to attach
ads to their pages/posts.

== Frequently Asked Questions ==

1) What is the main WWW address for platform:

https://www.quizad.pl

== Upgrade Notice ==

No upgrades yet.

== Screenshots ==

1. This screen shot description corresponds registration
/QuizAd/assets/images/registration.png

2. This screen shot description corresponds placements
/QuizAd/assets/images/placements.png

3. This screen shot description corresponds statistics
/QuizAd/assets/images/statistics.png

== Changelog ==

= 1.5.4 =
Initialize Splash&Roll aplication connection

= 1.5.3 =
Initialize Splash&Roll aplication connection

= 1.5.2 =
Initialize Splash&Roll aplication connection

= 1.5.1 =
Initialize Splash&Roll aplication connection

= 1.5.0 =
Initialize Splash&Roll aplication connection

= 1.4.14 =
Tested up to wp 6.4

= 1.4.13 =
Tested up to wp 6.2

= 1.4.12 =
Tested up to wp 6.1

= 1.4.11 =
Tested up to wp 6.0.1

= 1.4.10 =
Tested up to wp 6.0

= 1.4.9 =
Tested up to wp 6.0

= 1.4.8 =
Tested up to: 5.9

= 1.4.5 =

Tested and ready to use on WP 5.8
Minor bugs

= 1.4.0 =

Initial login application.
Initial reinstall plugin.
Initial debug plugin.
Initial resending registration email.
Initial remove account in Context360.

= 1.3.0 =

Initial split content to sentences.

= 1.2.0 =

Initial excluded tags.

= 1.1.0 =

Initial search bar with excluded placements.
