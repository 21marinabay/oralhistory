=== Media Library Categories ===
Contributors: Rick Mead
Donate link: http://sites.google.com/site/medialibarycategories/
Tags: media, library, hierarchical, categories, shortcode, list, sort
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 1.1.1

Media Library Categories adds custom categories for use in the media categories. Media items can then be sorted per category.

== Description ==

Media Library Categories is a WordPress plugin that lets you add custom categories for use in the media categories. Media items can then be sorted per category.

= Translations =

This plugin distributes with translations for the following language(s):

* English - default -

== Installation ==

1. Unpack the download-package
2. Upload folder include all files to the `/wp-content/plugins/` directory. 
3. Activate the plugin through the `Plugins` menu in WordPress
4. Add a media category
5. Set the media category to a media library item
6. Add the shortcode to a page or post
	a. shortcode attributes include:
		  categories = (at least 1 required. Comma delimmited list brings back more in the order of the delimmited list),
	      ul_class = (default is ''),
		  ul_id = (default is the media category id),
		  thumnail_size = (default is 'thumbnail' but also available: medium, large or full)
		  include_link = (default is true. this link wraps the image or file with link to the full size image or file),
		  target = (default is '_blank'),
		  rel = (default is '')
7. (Optional) Copy the file taxonomy-media_category.php to your template. 
	a. This will allow you to view an archive of the media category via a url. ex: http://www.domainname.com/media/[media category]/ 
		1) files sorted by date descending
	
	
== Screenshots ==

1. Administration interface in WordPress 3.2.1
2. Administration Sorting Items interface in WordPress 3.2.1
3. Selecting Media Categories for a library item
4. Bulk add, filter and column added to the Media Library Administration.
5. Sort Media Categories page.

== Upgrade Notice ==
* In version 1.1 the permissions were updated to use the roles and capabilities. Once you update you will need to go to the options page and save your permissions. This will give you access to the rest of the plugin. (Thanks basos)
* In version 1.0.6 the way the sort is saved, changed. The sort was being saved in an option per term but now are saved within the term_relationship table. To keep your previous sort all you need to do is visit the sort page for each media category. During the load of the sort page there is a check for the old option and updates the term_relationship table with the sort from the option and then empties the option.

== Changelog ==
= 1.1.1 =
* Bugfix: Sorting Media Categories relied on saving to the term_order in the terms table. 
This has been removed in the latest wordpress release. As a temporary fix I am adding a 
check for this column and if it doesnt exist I am re-adding it. 

= 1.1.0 =
* Enhancement: Media Upload Tab added to allow easy adding of images from media categories and media category shortcodes
* Enhancement: Sorting of Media Categories
* Enhancement: Filtering on Media library keeps selected and shows children categories as well
* Bugfix: Shortcode 'limit' is now defaulted to '0' which will return all results.
* Bugfix: Error messages while wordpress is in debug mode.
* Bugfix: updated capabilities from using level to looking for roles

= 1.0.8 =
* Bugfix : Added missing function "cleanQuery"

= 1.0.7 =
* Bugfix : Miroslav Stampar found a sql injection vulnerability in the sort.php page. 
* Bugfix : watchieee provided a fix to remove residual term relationships. 
* Enhancement : imHavoc provided an update to the shortcodes.php to add Limit, cleaner wpdf, and implement a caption

= 1.0.6 =
* Bugfix : changed the way the sort is saved. They were being saved into a option. The sort is now save in the term_relationship table.
* Bugfix : 3.1 changed how it handled the parse_query, so this broke the filter on the media library. A change was made to update the where statement.

= 1.0.5 =
* Bugfix : made sure I was calling the add tinymce code to only the add.php page
* NEW : Hierarchical categories 
* NEW : Media category archive page (you will have to copy the file to your template). The archive page can be view by visiting the url /media/[media category]/.
* NEW : Choose the lowest available security role to have the admin pages and options page available. Set in options page. (defaults to Administrator)

= 1.0.4 =
* Bugfix : changed how select is implemented so it shows in flash upload
* Bugfix : filter on library page was not working
* Bugfix : (I hope) I am not sure how to add jquery to the sort.php page without using the wp_enqueue_script so I chose to wrap it in an if statement to only do this for the sort page. Any suggestions are welcomed. I want to use the embedded wordpress jquery but was unsuccessful.

= 1.0.3 =
* Bugfix : fix edit of category name doesn't take

= 1.0.2 =
* Bugfix : comma location in shortcodes.php

= 1.0.1 =
* Bugfix : fix ie issue in edit form of library item
* NEW : in shortcode, added rel to link for lightbox integration (thanks lilqhgal)

= 1.0.0 =
* Released to public
