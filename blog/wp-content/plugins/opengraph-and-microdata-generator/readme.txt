=== Opengraph and Microdata Generator ===
Contributors: itsabhik
Donate link: http://www.itsabhik.com/partners/advertise-on-itsabhik/
Tags: facebook, opengraph, open graph, schema, schema microdata, microdata, seo, optimization, optimizer
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 2.2

Adds Facebook OpenGraph and Schema.Org compatible microdata to header to help search engines to show rich snippet and index your blog far more better.

== Description ==

A lightweight plugin to add Facebook Opengraph and Schema.org microdata in the <head> section of your blog. The Facebook Opengraph and Schema.org microdata lets you optimize your blog much more better and helps search engines to index your website better to show rich snippet.

This plugin also let Facebook, Google+ and Twitter users to share your blog with proper title, description, url and image.

* The post title as Facebook Share/Like title and Microdata title.

* The first image found in the post as the Facebook Share/Like thumbnail. Ability to define default thumbnail if no image is found on the post. This also applies for Microdata.

* Custom excerpt as the Facebook Share/Like description. Brings the first 250 characters of the blog post if no custom excerpt is found. This also applies for Microdata.

* The Post URL as the Facebook Share/Like URL. Doesn't matter if you have pretty permalinks on. This also applies to Microdata.

Here is a live demo on my <a href="http://www.itsabhik.com/wp-plugins/opengraph-microdata-generator.html">Plugin Homepage</a>. If you find my plugins useful, add me in your circles on <a href="https://plus.google.com/106671843900352433725?rel=author">Google +</a>.

If you like the plugin, please vote a 5 Star. ===>>
Thanks

== Installation ==

Installing and customizing is easy, just follow these simple steps:

1. Upload 'opengraph-microdata-generator' folder and all it's contents to the '/wp-content/plugins/' directory.
1. Activate the plugin 'Opengraph and Microdata Generator' through the 'Plugins' menu in WordPress
1. **Important:** Add this line manually `itemscope itemtype="http://schema.org/Blog"` to your theme's <html> tag in header.php so that it looks something like `<html itemscope itemtype="http://schema.org/Blog" ... />`. This has to done manually as there are no automated way.
1. Change settings at Settings > OpenGraph/Microdata page. Fill in your Facebook Admin ID, Default thumbnail URL, Blog Language and desired word length for desctiption.

== Frequently Asked Questions ==

= How do I check if the plugin is working? =

Check your page source and see the tags at <head> section.

== Screenshots ==

1. Sample Output from the plugin
2. Settings Page

== Changelog ==

= 1.0 =
* Intro Version

= 1.1 =
* Fixed a minor conflict between OG Image and Microdata Image.

= 2.0 =
* Added Options page at under Dashboard > Settings > OpenGraph/MicroData.
* Added ability to add your own default thumbnail.
* Two new Opengraph property added.

= 2.1 =
* Fixed a small bug with Facebook Debugger

= 2.2 =
* Fixed issues with quotes and doublequotes which were breaking the meta tag.
* Added setting to have your own word length in description.

== Upgrade Notice ==
* Follow the Standard Procedure