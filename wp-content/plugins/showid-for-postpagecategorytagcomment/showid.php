<?php
/*
Plugin Name: ShowID for Post/Page/Category/Tag/Comment
Plugin URI: http://ounziw.com/2010/02/05/showid/
Description: This plugin shows post/page/category/tag/comment/media/user IDs on admin's edit post/page/category/tag/commentmedia/user pages. You can see the IDs when you visit an edit post/page/category/tag/comment/media/user page. This plugin requires WP2.9 or later.
Version: 2.0
Author: Fumito Mizuno
Author URI: http://ounziw.com/
*/

function always_showid() {
?>
<style type="text/css">div.row-actions{visibility:visible !important;}</style>
<?php
}
add_action( 'admin_head', 'always_showid' );

function userid_add($actions,$user_object) {
$actions['edit'] = "ID:".$user_object->ID." | ".$actions['edit'] ;
return $actions;
}
add_filter('user_row_actions', 'userid_add', '10', '2');

function mediaid_add($actions,$post) {
$actions['edit'] = "ID:".$post->ID." | ".$actions['edit'] ;
return $actions;
}
add_filter('media_row_actions', 'mediaid_add', '10', '2');

function link_catid_add($actions,$category) {
$actions['edit'] = "ID:".$category->term_id." | ".$actions['edit'] ;
return $actions;
}
add_filter('link_cat_row_actions', 'link_catid_add', '10', '2');

function postid_show($actions,$post) {
	if ( current_user_can('edit_posts') ) {
		$actions['edit'] = "ID:" . $post->ID. " | " . $actions['edit'] ;
	}
	return $actions;
}
add_filter('post_row_actions', 'postid_show', '10', '2');

function pageid_show($actions,$page) {
	if ( current_user_can('edit_pages') ) {
		$actions['edit'] = "ID:" . $page->ID. " | " . $actions['edit'] ;
	}
	return $actions;
}
add_filter('page_row_actions', 'pageid_show', '10', '2');

function catid_show($actions,$category) {
	if ( current_user_can('manage_categories') ) {
		$actions['edit'] = "ID:" . $category->term_id. " | " . $actions['edit'] ;
	}
	return $actions;
}
add_filter('cat_row_actions', 'catid_show', '10', '2');

function tagid_show($actions,$tag) {
	if ( current_user_can('edit_posts') ) {
		$actions['edit'] = "ID:" . $tag->term_id. " | " . $actions['edit'] ;
	}
	return $actions;
}
add_filter('tag_row_actions', 'tagid_show', '10', '2');

function commentid_show($actions,$comment) {
	if ( current_user_can('moderate_comments') ) {
		$actions['edit'] = "ID:" . $comment->comment_ID. " | " . $actions['edit'] ;
	}
	return $actions;
}
add_filter('comment_row_actions', 'commentid_show', '10', '2');
