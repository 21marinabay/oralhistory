=== Admin Menu Tree Page View ===
Contributors: eskapism, MarsApril
Donate link: http://eskapism.se/sida/donate/
Tags: admin, page, pages, tree, view, admin menu, menu
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 2.2

Get a tree view of all your pages directly in the admin menu. Search, edit, view, re-order/sort and add pages - all with just one click away!

== Description ==

This WordPress plugin adds all your pages to the admin menu, so all your pages will always be available within just one click, 
no matter where you are in the admin area. You can also add pages directly in the tree and you can
quickly find your pages by using the search box.

**Version 2.0 update:** Now you can order your pages with drag-and-drop!

Works perfect in CMS-like WordPress installations with lots of pages in a tree hierarchy.

If you want a tree with all your pages, but don't want it visible all the time, please check out my other plugin
[CMS Tree Page View](http://wordpress.org/extend/plugins/cms-tree-page-view/).

#### Donation and more plugins
* If you like this plugin don't forget to [donate to support further development](http://eskapism.se/sida/donate/).
* Check out some [more WordPress CMS Plugins](http://wordpress.org/extend/plugins/profile/eskapism) by the same author.

== Installation ==

1. Upload the folder "admin-menu-tree-page-view" to "/wp-content/plugins/"
1. Activate the plugin through the "Plugins" menu in WordPress
1. Done!

Now the tree with the pages will be visible in the admin menu to the left.

== Screenshots ==

1. You can expand/collapse sub-pages. Keeps the meny compact, but gives you the option to instantly dig deep down the page hierarchy.
2. Search your pages in real time. Try it; it's wonderful! ;)
3. Quickly add single or multiple pages. Great for setting up the structure for a new site.


== Changelog ==

= 2.2 =
- Fixed: icons where misplaced when using minimized admin menu.
- Fixed: page actions where not visible when using minimized admin menu.
- Fixed: hopefully works better with WPML now.

= 2.1 =
- Fixed: forgot to remove console.log at some places. sorry!
- Updated: Drag and drop now works better. Still not 100%, but I can't find the reason why I does order the pages a bit wrong sometimes. Any ideas?

= 2.0 =
- Added: Now you can order posts with drag and drop. Just click and hold mouse button and move post up/down. But please note that you can only move posts that have the same level/depth in the tree.

= 1.6 =
- Fixed: post titles where not escaped.

= 1.5 =
- Could not edit names in Chrome
- Removed add page-link. pages are instead added automatically. no more clicks; I think feels so much more effective.

= 1.4 =
- moved JS and CSS to own folders
- can now add multiple pages at once
- can now set the status of the created page(s)

= 1.3 =
- An ul that was opened because of a search did not get the minus-sign
- New "popup" with actions when hovering a page. No more clicking to get to the actions. I really like it!

= 1.2.1 =
- The plus-sign/expand link now works at least three levels down in the tree

= 1.2 =
- Tree now always opens up when editing a page, so you will always see the page you're ediiting.
- When searching, the parents of a page with a match is opened, so search hits will always be visible.
- When searching and no pages found, show text "no pages found".
- CSS changes for upcoming admin area CSS changes in WordPress (may look wierd on current/older versions of WordPress...)
- Some preparing for using nestedSortable to order the pages

= 1.1 =
- Children count was sometines wrong.

= 1.0 =
- Added functionality to expand/collapse

= 0.6 =
- View link now uses wordpress function get_permalinks(). Previously you could get non-working links.

= 0.5 =
- Swedish translation added
- Moved load_plugin_textdomain to action "menu" instead of "init"

= 0.4 =
- Fixed a couple of small bugs
- Prepare for translation
- Moved JS to own file

= 0.3 =
- Removed some notices
- Added a search/filter box. Search your pages in real time. I love it! :)

= 0.2 =
- Some CSS changes. The icons and text and smaller now. I think it's better this way, you can fit so many more pages in the tree now.
- Now you can add new pages below or as a child to a page. For me this has been the feature I've missed the most.

= 0.1 =
- It's alive!
