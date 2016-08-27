=== Plugin Name ===
Contributors: eyn
Donate link: http://www.channel-ai.com/blog/donate.php?plugin=flv-embed
Tags: flv, flash, video, media, embed, sitemap, Google
Requires at least: 2.0
Tested up to: 2.3
Stable tag: 1.0

Standards compliant Flash video (FLV) embedding in your blog posts. Supports video sitemap generation.

== Description ==

Adds Flash videos (FLV) into your blog. Video sitemap generation is supported.

Features:

* uses simple, intuitive tags to generate Flash video (FLV) movies for your posts
* supports video sitemap generation
* standards compliant: valid XHTML
* many options are configurable, such as autostart, show/hide control bar, player colour, poster image, full screen button etc.
* supports text only output for RSS that prompt readers to visit the original post for Flash content
* supports outputting poster image for RSS if desired
* accessibility: requires Javascript to display FLV player, but will prompt user to enable Javascript when disabled
* accessibility: prompt user to download latest Flash player if it is not installed or too old
* accessibility: no annoying “click to activate” for IE users

== Screenshots ==

1. FLV Embed plugin in action
2. FLV Embed option panel
3. FLV Embed sitemap option panel

== Usage ==

This plugin uses one universal, standard tag style:

	[flv:url width height]

* url - the URL of the FLV file you want to embed, the path can either be site-relative or absolute
* width - width of the FLV movie
* height - height of the FLV movie

More usage details can be found at the plugin's homepage.

== Installation ==

1. Download and extract the “flv-embed” folder
1. Upload the “flv-embed” folder to your WordPress plugin directory, usually “wp-content/plugins”
1. Activate the plugin in your WordPress admin panel

== Frequently Asked Questions ==

= I keep getting “Get the latest Flash Player to see this player.” message even though I have the latest Flash player. Why? =

It is very likely that `wp_head()` function is missing in your theme’s header. To fix this, put the following code into your WordPress theme’s header file, right before the `</head>`:
	
	<?php wp_head(); ?>

= Why doesn’t my flash video stretch properly? =

You need a better encoder. You can also try different settings for `$flv_overstretch` option. 

= How can I align the video in the middle? =

Put the following code into your theme’s stylesheet (for easy way of doing this, see MyCSS):
	
	#player1, #player2, #player3 {text-align: center;}

= How can I get the cool-sexy-fade-away-interface look as seen in the demo? =

Set `$flv_showcontrolbar` to 0. ;)

= I really like this plugin, what can I do to help? =

Your donation helps me continue the support and development of this plugin. On the other hand, you can also spread the love by linking back to this page and share this (hopefully) useful plugin with your readers.

== History ==

1.0 [2007.12.30]

* Upgraded FLV Player to 3.12
* Added: Admin option panel
* Added: Video sitemap support
* Added: Option to show/hide volume button
* Added: Option to show/hide stop button
* Added: Option to show large control bar for visually impaired users

0.3.2 [2007.05.31]

* Added: Option to show poster image in feeds

0.3.1 [2007.05.19]

* Fixed: poster path problem

0.3 [2007.05.10]

* Upgraded FLV Player to 3.7
* Switched to SWFObject instead of ufo.js
* Added: Option to show/hide logo with link
* Added: Option to set poster and flv movie path
* Added: Option to show/hide icons
* Added: Option to set volume
* Added: Optional tag parameter for poster path

0.2 [2007.02.16]

* Upgraded FLV Player to 3.5
* Added: Option to show/hide controller bar
* Added: Option to change buffer length
* Added: Option to set overstretch
* Fixed: IE display problem

0.1 [2007.01.09]

* Initial release
