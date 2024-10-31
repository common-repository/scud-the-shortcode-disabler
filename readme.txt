=== SCuD - The ShortCode Disabler ===
Contributors: BrianLayman 
Donate link:http://thecodecave.com/donate
Tags: ShortCodes, post, page, disable, shortcode, short, code
Requires at least: 2.5
Tested up to: 3.8.1
Stable tag: 1.0.1

Allows you to disable ShortCodes on a per post/page basis.

== Description ==

When you add this plugin, a metabox is added to the edit post/page screens that allows you to disable ShortCodes in that one particular post/page. Admittedly, this plugin may not be widely needed by the general public. However if you are a plugin developer or documenter, you might find it very useful.

If you want to show examples of how to use a ShortCode and not have that ShortCode activate upon display of the post, this plugin will come to your rescue. The SCuD will disable all ShortCode processing for any post.

SCuD is written to prevent any collateral damage. After the post content is displayed, it activates the all previously disabled ShortCodes again. This allows any ShortCodes that you may be using in sidebar widgets to process correctly.
== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Screenshots ==
1. The Edit Post screen with the Disable ShortCodes metabox displayed.

2. A post that contains shortcodes that are processed unintentionally.

3. A post that contains shortcodes that are not processed because they were targeted by SCuD.

 
== Changelog ==

= 1.0 = 
* Initial Release
= 1.0.1 = 
* Updated for current version of WP
