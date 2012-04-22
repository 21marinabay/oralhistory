<?php
/**
 * Registering meta boxes
 *
 * In this file, I'll show you how to extend the class to add more field type (in this case, the 'taxonomy' type)
 * All the definitions of meta boxes are listed below with comments, please read them carefully.
 * Note that each validation method of the Validation Class MUST return value instead of boolean as before
 *
 * You also should read the changelog to know what has been changed
 *
 * For more information, please visit: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 *
 */

/********************* BEGIN EXTENDING CLASS ***********************/

/**
 * Extend RW_Meta_Box class
 * Add field type: 'taxonomy'
 */
 
function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', WP_PLUGIN_URL.'/uploader.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}

function my_admin_styles() {
wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {
add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');
}


class RW_Meta_Box_Taxonomy extends RW_Meta_Box {
	
	function add_missed_values() {
		parent::add_missed_values();
		
		// add 'multiple' option to taxonomy field with checkbox_list type
		foreach ($this->_meta_box['fields'] as $key => $field) {
			if ('taxonomy' == $field['type'] && 'checkbox_list' == $field['options']['type']) {
				$this->_meta_box['fields'][$key]['multiple'] = true;
			}
		}
	}
	
	// show taxonomy list
	function show_field_taxonomy($field, $meta) {
		global $post;
		
		if (!is_array($meta)) $meta = (array) $meta;
		
		$this->show_field_begin($field, $meta);
		
		$options = $field['options'];
		$terms = get_terms($options['taxonomy'], $options['args']);
		
		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($terms as $term) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked(in_array($term->slug, $meta), true, false) . " /> $term->name<br/>";
			}
		}
		// select
		else {
			echo "<select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";
			// echo "<option label=''>Select a category...</option>";
			foreach ($terms as $term) {
				echo "<option value='$term->slug'" . selected(in_array($term->slug, $meta), true, false) . ">$term->name</option>";
			}
			echo "</select>";
		}
		
		$this->show_field_end($field, $meta);
	}
}

/********************* END EXTENDING CLASS ***********************/

/********************* BEGIN DEFINITION OF META BOXES ***********************/

// prefix of meta keys, optional
// use underscore (_) at the beginning to make keys hidden, for example $prefix = '_rw_';
// you also can make prefix empty to disable it
$prefix = '';

$meta_boxes = array();

// Post meta box
$meta_boxes[] = array(
	'id' => 'personal',							// meta box id, unique per meta box
	'title' => 'SuperSkeleton Post Options',			// meta box title
	'pages' => array('post'),	// post types, accept custom post types as well, default is array('post'); optional
	'context' => 'normal',						// where the meta box appear: normal (default), advanced, side; optional
	'priority' => 'high',						// order of meta box: high (default), low; optional

	'fields' => array(							// list of meta fields
		array(
			'name' => 'Custom Background Image URL',			
			'desc' => 'Upload an image, then place the URL here. ie: http://yoursite.com/images/custom_bg_image.jpg',	
			'id' => $prefix . 'custom_background_image',				
			'type' => 'text',					
			'std' => '',		
			'style' => '',
			'validate_func' => 'check_name'		
		),
		
		array(
			'name' => 'Lightbox Link',	
			'desc' => 'Insert a URL for an image or video (Vimeo, YouTube, or .MOV) to be launched inside a lightbox from the post thumbnail. See the <a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">lightbox documentation</a> for acceptable media.',	
			'id' => $prefix . 'lightbox_link',	
			'type' => 'text',					
			'std' => '',
			'style' => '',	
			'validate_func' => 'check_name'	
		),		
		
		array(
			'name' => 'Flip Columns?',	
			'desc' => 'Do you want to swap the sides for the Main Column and Sidebar Column?',	
			'id' => $prefix . 'column_flip',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Yes' => 'Yes',
				'No' => 'No'
				)
			),
			
		array(
			'name' => 'Remove Sidebar?',	
			'desc' => 'Do you want to remove the sidebar and use the full-width template for this post?',	
			'id' => $prefix . 'remove_sidebar',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Yes' => 'Yes',
				'No' => 'No'
				)
			),
		
		array(
			'name' => 'Remove the From-The-Blog Row?',	
			'desc' => 'Do you want to remove the From-The-Blog row that shows up in the footer?',	
			'id' => $prefix . 'breakout_hide',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Yes' => 'Yes',
				'No' => 'No'
				)
			),
			
	)
);


// Page meta box
$meta_boxes[] = array(
	'id' => 'personal',							// meta box id, unique per meta box
	'title' => 'SuperSkeleton Page Options',			// meta box title
	'pages' => array('page'),	// post types, accept custom post types as well, default is array('post'); optional
	'context' => 'normal',						// where the meta box appear: normal (default), advanced, side; optional
	'priority' => 'high',						// order of meta box: high (default), low; optional

	'fields' => array(							// list of meta fields
		array(
			'name' => 'Custom Background Image URL',			
			'desc' => 'Upload an image, then place the URL here. ie: http://yoursite.com/images/custom_bg_image.jpg',	
			'id' => $prefix . 'custom_background_image',				
			'type' => 'text',					
			'std' => '',		
			'style' => '',
			'validate_func' => 'check_name'		
		),

		array(
			'name' => 'Category Filter',
			'id' => $prefix . 'category_filter',
			'type' => 'taxonomy',	
			'desc' => 'Select which categories you would like included (if this page template uses blog posts)',
			'options' => array(
				'taxonomy' => 'category',
				'std' => '',
				'type' => 'checkbox_list',					// how to show taxonomy? 'select' (default) or 'checkbox_list'
				'args' => array()					// arguments to query taxonomy, see http://goo.gl/795Vm	
			)			
		),
		
		array(
			'name' => 'Post Count Limit',	
			'desc' => 'If this is a page template that uses blog posts, set a number for how many posts you want to show up per page. The default is set to show ALL posts found within the above category(s).',	
			'id' => $prefix . 'post_count',	
			'type' => 'text',					
			'std' => '',
			'style' => '',	
			'validate_func' => 'check_name'	
		),		
		
		array(
			'name' => 'Flip Columns?',	
			'desc' => 'Do you want to swap the sides for the Main Column and Sidebar Column?',	
			'id' => $prefix . 'column_flip',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Yes' => 'Yes',
				'No' => 'No'
				)
			),
			
		array(
			'name' => 'Remove the From-The-Blog Row?',	
			'desc' => 'Do you want to remove the From-The-Blog row that shows up in the footer?',	
			'id' => $prefix . 'breakout_hide',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Yes' => 'Yes',
				'No' => 'No'
				)
			),
			
		array(
			'name' => 'Portfolio View Default',	
			'desc' => 'If this is a Portfolio page template, you can set the default view here.',	
			'id' => $prefix . 'portfolio_view',	
			'type' => 'radio',	
			'options' => array(						// array of key => value pairs for select box
				'Grid' => 'Grid',
				'Hybrid' => 'Hybrid',
				'List' => 'List'
				)
		),		
			
	)
);



foreach ($meta_boxes as $meta_box) {
	new RW_Meta_Box_Taxonomy($meta_box);
}

/********************* END DEFINITION OF META BOXES ***********************/

/********************* BEGIN VALIDATION ***********************/

/**
 * Validation class
 * Define ALL validation methods inside this class
 * Use the names of these methods in the definition of meta boxes (key 'validate_func' of each field)
 */
class RW_Meta_Box_Validate {
	function check_name($text) {
		if ($text == 'Anh Tran') {
			return 'He is Rilwis';
		}
		return $text;
	}
}

/********************* END VALIDATION ***********************/
?>