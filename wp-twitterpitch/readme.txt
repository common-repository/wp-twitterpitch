=== Plugin Name ===
Contributors: technosailor
Tags: twitter, template tag
Requires at least: 2.5
Tested up to: 2.6-alpha
Stable tag: trunk

This plugin provides PR companies and users wanting to pitch, a means to give an "elevator" pitch in 140 characters or less.
== Description ==

WP-TwitterPitch is all about getting the pitch delivered to you in the form you want to get it delivered - in other words in Twitter format. If you're like me, then your Twitter direct message box is a lot like your email inbox. Personally, I don't want to get pitches from PR companies in certain email inboxes. For whatever reason, I may not check them or they are personal, etc.

Twitter, however, provides the ultimate quick-messaging system. This plugin provides a template tag that you can drop anywhere in your theme. Clicking the link provides lightbox-like functionality for a "pitch form". Using the form does not require a Twitter account (but does require that *you* have a secondary Twitter account you can use for this purpose, since you can't send Direct Messages to yourself via Twitter). **Note:** Your WP-TwitterPitch Twitter account must follow the account that is being pitched and vica versa. This is a one-off action (hopefully, depending on Twitter) and only needs to be done when setting up WP-TwitterPitch.

Messages sent from the form are DMmed to the account getting the pitch and the form is limited to 140 characters or less. The beauty of linguistic efficiency.

== Installation ==

1. Upload the `wp-twitterpitch` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit Admin options to include Twitter ID to pitch, Twitter ID and Password to send Twitter pitches as, as well as a message to "pitchers" that will be displayed in the form after the pitch has been sent.
1. Place `<?php twitterpitch(); ?>` wherever you want the link to appear

== Frequently Asked Questions ==

= Are there any special requirements for using this plugin? =

Unfortunately, yes. You will need to ahve two twitter accounts. One is the twitter account you want to recieve direct message pitches. Generally, this is your primary Twitter account. Additionally, you will need a second Twitter account. WordPress will, in essence, act as the Twitter client. Messages sent via WP-TwitterForm will be sent via this account.

Secondly, you will need to have PHP 5.2+. The reason for this is that JSON support is not inherently in-built to earlier versions - and I like JSON because it's lightweight and easy on the CPUs of high-traffic or weak servers. Easier than XML parsing anyway. This may be optional, in future revisions, depending on "consumer demand".

Lower versions of PHP, if you can get it installed, can use the pecl JSON module. This is outside the scope of this plugin though. Google it.

= Are you interested in buying...? =

No.