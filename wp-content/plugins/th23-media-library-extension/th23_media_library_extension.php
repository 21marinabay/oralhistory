<?php
/*
Plugin Name: th23 Media Library Extension
Description: Adds advanced filter options to the Media Library - to fully leverage it's capabilities, additionally install the <a href='http://wordpress.org/extend/plugins/shuffle/'>Shuffle</a> plugin.
Author: Thorsten Hartmann
Version: 0.2
Author URI: http://th23.net
License: GPL3
*/

// check if "shuffle" plugin is installed and activated
$th23_shuffle = false;
if (isset($shuffle_post_id)) {
	$th23_shuffle = true;
}

// add link to filtered (and if "shuffle" is installed) ordered attachments 
function th23_media_add_filter_link($actions, $post) {

	$actions = array_merge($actions, array(
		'th23_media' => '<a href="upload.php?post_parent=' . $post->ID . '&post_parent_selected=1">' . __('Show Media') . '</a>'
	));
	
	return $actions;

}
add_filter('post_row_actions', 'th23_media_add_filter_link', 9, 2);
add_filter('page_row_actions', 'th23_media_add_filter_link', 9, 2);
 
// ### TODO: Add columns for "Caption", "Description", "Alternate Text" -> only one used for me is title
// ### TODO: Add Quick Edit feature to Media Library -> can do via Gallery in posts/ pages

// replace standard "attached to" column
function th23_media_columns($defaults) {
	
	global $th23_shuffle;

	// if needed, remove standard "shuffle" column for "Detach" link
	if ($th23_shuffle) {
		unset($defaults['shuffle']);
	}

	// re-define standard "attached to" column
	$defaults_new = array();
	foreach ($defaults as $default_key => $default_value) {
		if ($default_key == 'parent') {
			$default_key = 'th23_attached_to';
			$default_value = __('Attached to');
		}
		$defaults_new[$default_key] = $default_value;
	}
	$defaults = $defaults_new;
	return $defaults;

}
add_filter('manage_media_columns', 'th23_media_columns');

// populate new "attached to" column with standard items (post title, link, date)
// and add additional useful links
function th23_media_custom_column($column_name, $id) {

	global $th23_shuffle;

	if ($column_name == 'th23_attached_to') {    
		$parent_id = (int) get_post_field('post_parent', (int) $id);
		if ( $parent_id > 0 ) {
			echo '<strong><a href="' . get_edit_post_link($parent_id) . '">' . _draft_or_post_title($parent_id) . '</a></strong>';
			echo '<p>' . get_the_time(__('Y/m/d'), $parent_id) . '</p>';
			// add additional links
			echo '<div class="row-actions">';
			// for "re-attach"
			echo '<a class="hide-if-no-js" onclick="findPosts.open(\'media[]\',\'' . $id . '\');return false;" href="#the-list">' . __('Re-Attach') . '</a>';
			// for "detach", if "shuffle" installed/ activated
			if ($th23_shuffle) {
				echo ' | <a href="admin.php?action=shuffle_detach&amp;post_id=' . $id . '">' . __('Detach') . '</a>';
			}
			// for advanced filter "only media attached to this item"
			echo ' | <a href="upload.php?post_parent=' . $parent_id . '&post_parent_selected=1">' . __('Show Media') . '</a>';
			// for "shuffle attached media", if "shuffle" installed/ activated
			if ($th23_shuffle) {
				echo ' | ' . shuffle_do_link($parent_id, __('Shuffle Media'));
			}
			echo '</div>';
		} else {
			echo __('(Unattached)') . '<br />';
			echo '<div class="row-actions"><a class="hide-if-no-js" onclick="findPosts.open(\'media[]\',\'' . $id . '\');return false;" href="#the-list">' . __('Attach') . '</a></div>';
		}	
	}

}
add_action('manage_media_custom_column', 'th23_media_custom_column', 10, 2);

// add some media filters to the page
function th23_media_filter() {
	
	global $wpdb, $query_string, $th23_shuffle;

	// only add filters in Media Library
	if (strpos($_SERVER['PHP_SELF'], 'upload.php') === false) return;

	// check for date selection
	$m = th23_request_var('m', 0);

	// author selection
	$post_authors = $wpdb->get_results("SELECT a.post_author, b.display_name 
		FROM $wpdb->posts a LEFT JOIN $wpdb->users b ON a.post_author = b.ID 
		WHERE a.post_type = 'attachment' AND a.post_author > 0 
		GROUP BY a.post_author 
		ORDER BY b.display_name ASC");
	$post_author_options = array(0 => array(0, __('Show all authors')));
	foreach ($post_authors as $post_author) {
		$post_author_options[$post_author->post_author] = array($post_author->post_author, $post_author->display_name);
	}
	unset($post_authors);

	$author_id = th23_request_var('author', 0);

	if (!isset($post_author_options[$author_id])) {
		$author_id = 0;
	}

	echo th23_build_select('author', $post_author_options, $author_id);

	// post_parent selection
	$post_parents = $wpdb->get_results("SELECT a.ID, a.post_title, (SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_parent = a.ID) AS attachment_count 
		FROM $wpdb->posts a 
		WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'draft' OR post_status = 'pending') 
		ORDER BY a.post_title ASC");
	$post_parent_options = array(-1 => array(-1, __('Show all parents')), 0 => array(0, __('(Unattached)')));
	foreach ($post_parents as $post_parent) {
		if ($post_parent->attachment_count > 0) {
			$post_parent_options[$post_parent->ID] = array($post_parent->ID, $post_parent->post_title);
		}
	}
	unset($post_parents);
	
	$post_parent_id = th23_request_var('post_parent', -1);

	if (!isset($post_parent_options[$post_parent_id])) {
		$post_parent_id = -1;
	}

	echo '&nbsp;';
	echo th23_build_select('post_parent', $post_parent_options, $post_parent_id, 'post_parent');

	$th23_menu_order_show = false;
	if ($th23_shuffle) {
		echo '<input type="hidden" id="post_parent_selected" name="post_parent_selected" value="0" />';
		echo '<script type="text/javascript">';
		echo '//<![CDATA[' . "\n";
		echo 'var t = document.getElementById("post_parent");' . "\n";
		echo 't.onchange = function(){var b = document.getElementById("post_parent_selected"); b.value = 1;};' . "\n";
		echo '//]]>' . "\n";
		echo '</script>';
		if ($m == 0 && $author_id == 0 && $post_parent_id > 0) {
			$th23_menu_order_show = true;
			$th23_menu_order_force = th23_request_var('post_parent_selected', false);
		}
	}
	
	// orderby and order selection
	echo '&nbsp;' . __('Order by');

	$orderby_options = array(
		'title' => array('title', __('File title')), 
		'date' => array('date', __('Date uploaded')), 
	);
	if ($th23_menu_order_show) {
		$orderby_options['menu_order'] = array('menu_order', __('Shuffle order'));
	}
	$orderby = ($th23_menu_order_force) ? 'menu_order' : th23_request_var('orderby', '');

	$order_options = array(
		'asc' => array('asc', __('Ascending')),
		'dsc' => array('dsc', __('Descending'))
	);
	$order = ($th23_menu_order_force) ? 'asc' : th23_request_var('order', '');

	if (!isset($orderby_options[$orderby])) {
		$orderby = 'date';
		$order = 'dsc';
	}
	if (!isset($order_options[$order])) {
		$order = 'dsc';
	}

	echo '&nbsp;';
	echo th23_build_select('orderby', $orderby_options, $orderby);
	echo '&nbsp;';
	echo th23_build_select('order', $order_options, $order);

	// apply filter to post selection
	$post_parent_query = ($post_parent_id >= 0) ? '&post_parent=' . $post_parent_id : '';
	query_posts($query_string . '&author=' . $author_id . $post_parent_query . "&orderby=" . $orderby . "&order=" . $order);

}
add_action('restrict_manage_posts', 'th23_media_filter');

// request variable without to care about _GET and _POST
function th23_request_var($name, $default) {

	if (!isset($_GET[$name]) && !isset($_POST[$name])) {
		return (is_array($default)) ? array() : $default;
	}

	$var = isset($_POST[$name]) ? $_POST[$name] : $_GET[$name];

	$type = gettype($default);
	
	settype($var, $type);

	if ($type == 'string') {
		// phpBB way: return trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $var), ENT_COMPAT, 'UTF-8', false));
		return esc_attr($var);
	}

	return $var;

}

// build select drop down
// param $options needs to be an array('value', 'title', 'css'), title and css can be left empty
function th23_build_select($name, $options, $selected = "", $id = "", $class = "", $multiple = false) {

	if ($id) {
		$id_html = ' id="' . $id . '"';
	}

	if ($class) {
		$class_html = ' class="' . $class . '"';
	}

	if ($multiple) {
		$name .= '[]';
		$multiple_html = ' multiple="multiple"';
	} else {
		$multiple_html = '';
	}

	$html_select = '<select name="' . $name . '"' . $id_html . $class_html . $multiple_html . '>';

	if (is_array($options)) {
		foreach ($options as $option) {
			
			if (!is_array($option) || !isset($option[0])) {
				continue;
			}
			$value = (string) $option[0];

			if ($multiple && is_array($selected)) {
				$selected_html = (in_array($value, $selected)) ? ' selected="selected"' : '';
			} else {
				$selected_html = ($value == $selected) ? ' selected="selected"' : '';
			}

			$title = (isset($option[1])) ? (string) $option[1] : $value;

			$style_html = (isset($option[2])) ? ' style="' . (string) $option[2] . '"' : '';
			
			$html_select .= '<option value="' . $value . '"' . $selected_html . $style_html . '>' . $title . '</option>';

		}
	}

	return $html_select . '</select>';

}

?>