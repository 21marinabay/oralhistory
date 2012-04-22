=== Plugin Name ===
Contributors: ounziw 
Donate link: http://pledgie.com/campaigns/8706
Tags: show id admin post
Requires at least: 2.9
Tested up to: 2.9.1
Stable tag: trunk

This plugin shows post/page/category/tag/comment/media/user IDs on admin's edit post/page/category/tag/comment/media/user pages. You can see the ID when you visit an edit post/page/category/tag/comment/media/user page.

== Description ==

This plugin shows post/page/category/tag/comment/media/user IDs on admin's edit post/page/category/tag/comment/media/user pages. You can see the ID when you visit an edit post/page/category/tag/comment/media/user page. This plugin requires WP2.9 or later.

Since this plugin hooks on 'edit' link, only users with priviledges can see IDs. For example, a user of author role can see post IDs, while a user of subscriber role cannot.

== Installation ==

== Frequently Asked Questions ==

= I want to see IDs when the mouse pointer is out. =

If you use Version 2.0, you can see IDs without rollover.
If you use Version 1.0, please update, or add the code shown below. 

function always_showid() {
?&gt;
&lt;style type="text/css"&gt;div.row-actions{visibility:visible !important;}&lt;/style&gt;
&lt;?php
}

add_action( 'admin_head', 'always_showid' );

== Screenshots ==

1. This is a screenshot for Admin > Posts > Edit Posts.

== Changelog ==

= 2.0 =
media/user IDs are shown.
IDs are shown without rollover.

= 1.0 =
post/page/category/tag/comment IDs are shown.

== Upgrade Notice ==
Please update if you want to see IDs without rollover.
