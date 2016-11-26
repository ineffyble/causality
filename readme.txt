=== Plugin Name ===
Contributors: ineffyble
Donate link: https://effy.is/fundable
Tags: social, images
Requires at least: 4.6
Tested up to: 4.6
Stable tag: trunk
License: MIT
License URI: https://opensource.org/licenses/MIT

Causality: promote your cause, and have an effect on your supporters.

== Description ==

Causality allows your cause, charity, or nonprofit to promote your campaign using Twibbon-style image overlays

Causality allows you to set up a series of image overlays. Supporters on your site can easily add an overlay to their profile
picture on social networks, promoting your cause.

== Installation ==

Causality should work nearly out of the box.


1. Install the plugin
2. Activate the plugin
3. Go to the plugin settings
4. Choose a campaign name, and upload some overlays (PNG, 960x960 recommended)
5. To enable "Login with Facebook" functionality, you will need to create a "Facebook app", and enter the ID under Causality settings.

== Adding Facebook integration ==

1. Go to the [Facebook Developers page](https://developers.facebook.com/apps) and choose "*Add a new app*"
2. The Display Name should be the name of your website or organisation - it will be shown to supporters when it asks for their authorisation.
3. Choose a category, this doesn't matter too much.
4. Click *Create App ID*.
5. Click *Get Started* for *Facebook Login*.
6. Click *Save Changes* at the bottom right.
7. Click *Settings* at the top right. Enter your website's domain under "App Domains" (e.g. amnesty.org.au)
8. Click *Add Platform* at the bottom of this page, and choose "Website". Enter your website under Site URL (e.g. http://amnesty.org.au)
9. Copy the "Application ID" shown at the top left. Enter this into your Causality settings.
10. Click *Save Changes*.
11. Finally, click "App Review" at the top left, and toggle the "Make YourApp Public" box to be "Yes".

== Frequently Asked Questions ==

= Can I have it automatically pull the supporter's image from social networks? =

The current version supports Facebook integration - the supporter can choose to Login With Facebook, and use their current
Facebook profile photo as the source.

More social networks hopefully coming soon.

= Can they set their Facebook photo from the plugin? =

Not yet - Facebook have quite restrictive permissions on what apps can do this.
Support will be added to the plugin soon, but you will need to submit your app to Facebook for review if you want to use it.

== Changelog ==

= 0.1 =
First release
