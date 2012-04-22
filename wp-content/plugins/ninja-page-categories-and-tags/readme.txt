=== Ninja Page Categories and Tags ===
Contributors: jameslaws, kstover
Donate link: http://wpninjas.net
Tags: page, pages, category, categories, tag, tags
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 1.2.2

A simple plugin that allows the user to assign categories and tags to pages.

== Description ==

This plugin will enable the categories and tags boxes on the edit page screen. You can assign these to pages just as you would to a post.

With version 1.2 we've added a very simple options page where you can choose if you would like to add categories, tags or both to your pages as well as options to have your pages show up in archive pages, add excerpts to pages, adjust excerpt length, and the ability to display child pages on parent pages. You can also add child pages on a per page basis with the [ninja_child_pages] shortcode and easily display categories or tags in your page templates with our ninja_pages_display_terms function.


== Installation ==

*Note* This plugin requires at least version 3.0 of WordPress.

1. Upload the plugin folder (i.e. ninja_page_cats_tags) to the /wp-content/plugins/ directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You can now add categories and tags to your pages.


== Frequently Asked Questions ==

= I've upgraded but now it appears the plugin no longer works. What am I missing? =

Since version 1.2 we added a settings page under Settings > Ninja Pages. After your inital upgrade you will need to visit this page and turn on any options that you would like to use. The plugin isn't broken, it simply doesn't know what features you want until you tell it.

= How do I display the categories or tags that are attcahed to posts? =

You can add this super simple function to your Page template:

	if( function_exists( 'ninja_pages_display_terms' ) ) {
		echo ninja_pages_display_terms( 'category', ',' );
	}

You will obviously want to wrap them with opening and closing php tags. This function takes two parameters. The first is the taxonomy, in our case this will be either 'category' or 'post_tag'. The second is a seperator. In the example above we use a comma.

= How can I style my child page listing on parent pages? =

Below is the basic markup for the child pages listing. Using basic CSS you can style these any way you like.

	WRAPS ENTIRES LIST OF CHILDREN
	<div id="ninja-children-wrap">

		WRAPS EACH CHILD PAGE
		<div class="ninja-child-wrap">
			IF THERE IS A THUMBNAIL
				<div class="ninja-child-thumbnail">
					CHILD THUMBNAIL
				</div>
			END THUMBNAIL
			<h3 class="ninja-child-title"><a href="">CHILD PAGE TITLE</a></h3>
			<div class="ninja-child-entry">
				CHILD PAGE EXCERPT
			</div>
		</div>

	</div>


== Changelog ==

= 1.2.2 =
* Fixed links in child pagess list
* Added check for theme support of post-thumbnails and add support if not found.

= 1.2.1 =
* Various bug fixes and improved logic.

= 1.2 =
* Added an options page and some new functions, shortcodes, etc.

= 1.1 =
* Added code so that the pages categorized or tagged will show up in archives.

= 1.0 =
* First version of Ninja Page Categories and Tags released.

== Upgrade Notice ==

= 1.2.2 =
Adds new features, fixes common issues, and adds a settings page. Please visit settings > Ninja Pages after upgrading to turn on the features that you desire.