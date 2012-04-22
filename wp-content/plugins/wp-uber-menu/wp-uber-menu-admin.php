<?php 

/***************************************************************************
 * wp-uber-menu-admin.php
 * 
 * UberMenu Administrative Functionality
 * Version 1.1.4
 * Last Updated: 2011-12-12
 * @author Chris Mavricos, Sevenspark, http://sevenspark.com
 * 
 * Copyright Chris Mavricos, Sevenspark 
 * 
 ***************************************************************************/

define('WPMEGA_ADMIN_PATH', trim(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)), '/'));

/*
 * Initialization Procedures
 */
function wpmega_admin_init(){
	
	add_action('admin_head', 'wpmega_addMetaBox');
	
	add_action('admin_print_styles-nav-menus.php', 'wpmega_admin_load_js');
	add_action('admin_print_styles-nav-menus.php', 'wpmega_admin_load_css');
	add_action('admin_print_styles-appearance_page_wp-mega-menu', 'wpmega_admin_load_css');
	add_action('admin_print_styles-appearance_page_wp-mega-menu', 'wpmega_admin_load_js');
	
	add_filter( 'wp_edit_nav_menu_walker', 'wpmega_walker_edit' , 500);
	
	add_action( 'wp_update_nav_menu_item', 'wpmega_update_nav_menu_item', 10, 3); //, $menu_id, $menu_item_db_id, $args;
	
	//Add control panel page
	add_action('admin_menu', 'wpmega_options_menu');
	
}
if(is_admin()) add_action( 'plugins_loaded', 'wpmega_admin_init');

/*
 * Load the Admin CSS
 */
function wpmega_admin_load_css(){
	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	wp_enqueue_style('wpmega-admin', 	$tmp.'/styles/admin.css', 					false, '1.1', 'all');
	wp_enqueue_style('thickbox');
	
	wp_enqueue_style('wpmega-basic', 	$tmp.'/styles/basic.css', 					false, '1.1', 'all');
	wp_enqueue_style('colorpicker',		$tmp.'/js/colorpicker/css/colorpicker.css', false, '1.1', 'all');	
}

/*
 * Load the Admin JavaScript
 */
function wpmega_admin_load_js(){

	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	
	//Client Side Stuff
	wp_enqueue_script('jquery');	// Load jQuery
	wp_enqueue_script('wpubermenu', $tmp.'js/wpmegamenu.dev.js', array(), false, true);
	wp_enqueue_script('hoverintent', $tmp.'js/hoverIntent.js', array(), false, true);
	wp_enqueue_script('thickbox');				
	
	//Admin Extras	
	wp_enqueue_script('wpmega-admin-js', $tmp.'js/wpmegamenu_admin.js', array(), false, true);	
	wp_enqueue_script('colorpicker-js', $tmp.'js/colorpicker/js/colorpicker.js', array(), false, true);
	
	add_action('admin_head', 'wpmega_inline', 101);
}

/*
 * Custom Walker Name
 */
function wpmega_walker_edit($className){
	return 'wpMegaWalkerEdit';
}

/*
 * Save and Update the Custom Navigation Menu Item Properties
 */
function wpmega_update_nav_menu_item($menu_id, $menu_item_db_id, $args ){
	
	//shortcode
	$shortcode = $_POST['menu-item-shortcode'][$menu_item_db_id];
	update_post_meta( $menu_item_db_id, '_menu_item_shortcode', $shortcode );
	
	//widget area sidebars
	$sidebars = $_POST['menu-item-sidebars'][$menu_item_db_id];
	update_post_meta( $menu_item_db_id, '_menu_item_sidebars', $sidebars );
	
	//isheader
	$isheader = 'off';
	if($_POST['menu-item-isheader'][$menu_item_db_id] == 'on'){
		$isheader = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_isheader', $isheader );
	
	//verticaldivision
	$verticaldivision = 'off';
	if($_POST['menu-item-verticaldivision'][$menu_item_db_id] == 'on'){
		$verticaldivision = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_verticaldivision', $verticaldivision );
	
	//highlight
	$highlight = 'off';
	if($_POST['menu-item-highlight'][$menu_item_db_id] == 'on'){
		$highlight = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_highlight', $highlight );
	
	//newcol
	$newcol = 'off';
	if($_POST['menu-item-newcol'][$menu_item_db_id] == 'on'){
		$newcol = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_newcol', $newcol );
	
	//isMega
	$isMega = 'off';
	if($_POST['menu-item-isMega'][$menu_item_db_id] == 'on'){
		$isMega = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_isMega', $isMega );
	
	//alignRight
	$alignRight = 'off';
	if($_POST['menu-item-alignRight'][$menu_item_db_id] == 'on'){
		$alignRight = 'on';
	}
	update_post_meta( $menu_item_db_id, '_menu_item_alignRight', $alignRight );
	
}

/*
 * Add the Activate Uber Menu Locations Meta Box to the Appearance > Menus Control Panel
 */
function wpmega_addMetaBox(){
	if ( wp_get_nav_menus() )
		add_meta_box( 'nav-menu-theme-megamenus', __( 'Activate Uber Menu Locations' ), 'wp_nav_menu_theme_megamenus' , 'nav-menus', 'side', 'high' );
}

/*
 * Generates the Activate Uber Menu Locations Meta Box
 */
function wp_nav_menu_theme_megamenus(){

	/* This is just in case JS is not working.  It'll only save the last checked box */
	if(isset($_POST['megaMenu-locations']) && $_POST['megaMenu-locations'] == 'Save'){
		$data = $_POST['wp-mega-menu-nav-loc'];
		$data = explode(',', $data);		
		update_option(WPMEGA_NAV_LOCS, $data);
		echo 'Saved Changes';
	}
	
	$active = get_option(WPMEGA_NAV_LOCS, array());
	
	echo '<div class="megaMenu-metaBox">';	
	echo '<p class="howto">Select the Menu Locations to Megafy.  This must be activated for any Mega Menu Options to affect that Menu Location.</p>';
	
	echo '<form>';
	
	$locs = get_registered_nav_menus();
	
	foreach($locs as $slug => $desc){		
		echo '<label class="menu-item-title" for="megaMenuThemeLoc-'.$slug.'">'.
				'<input class="menu-item-checkbox" type="checkbox" value="'.$slug.'" id="megaMenuThemeLoc-'.$slug.'" name="wp-mega-menu-nav-loc" '.
				checked(in_array($slug, $active), true, false).'/>'.
				$desc.'</label>';
	}
	
	echo '<p class="button-controls">'.
			'<img class="waiting" src="'.esc_url( admin_url( 'images/wpspin_light.gif' ) ).'" alt="" />'.
			'<input id="wp-mega-menu-navlocs-submit" type="submit" class="button-primary" name="megaMenu-locations" value="Save" />'.
			'</p>';
	
	echo '</form>';
	
	echo '<p class="howto">If more than 1 menu is being megafied in your theme, turn on Strict Mode in Appearance > UberMenu > '.
				'Theme Integration.  <br/>Note you can only have 1 UberMenu per page.</p>';
	
	echo '</div>';
}

/*
 * Update the Locations when the Activate Uber Menu Locations Meta Box is Submitted
 */
function megaMenu_updateNavLocs_callback(){
	
	$data = $_POST['data'];	
	$data = explode(',', $data);
	
	update_option(WPMEGA_NAV_LOCS, $data);
	
	echo $data;		
	die();		
}
add_action('wp_ajax_megaMenu_updateNavLocs', 'megaMenu_updateNavLocs_callback');			//For logged in users



/**************************
 * 
 * CONTROL PANEL PAGES
 *  
 *************************/

/*
 * Add the Appearance > Uber Menu control panel
 */
function wpmega_options_menu() {
	add_submenu_page('themes.php', 'Uber Menu Options', 'Uber Menu', 'manage_options', 'wp-mega-menu', 'wpmega_options');
}

/*
 * Generate the UberMenu Options Page
 */
function wpmega_options() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	wpmega_showThanks();
	
	//Update the submitted form
	wpmega_updateSettings();

	echo '<div class="wrap megaMenu-controlPanel">';
	
	echo 	'<div class="uberMeta">
				<a href="http://j.mp/dPmJ8m" target="_blank" title="Read the UberMenu Support Manual">Support Manual</a>
				<a href="http://j.mp/ekannC" title="Frequently Asked Questions"  target="_blank">FAQs</a>
				<a href="http://j.mp/fDpVkP" target="_blank" title="Purchase a license for use on a second installation">Additional License</a>
			</div>';
	
	echo '<form method="post" id="wpmega-options">';
	
	// Settings
	$ops = wpmega_get_admin_options();
	$class = '';
	foreach($ops as $id => $config){
		if($config['type'] == 'header-2') $class = 'wpmega-config-section';	//TODO
		echo wpmega_admin_option($id, $config, $class);
		if($config['type'] == 'header-2') $class = 'sub-container sub-container-'.$id;
	}
	
	//Styles
	echo '<div class="style-gen">';
	echo '<h3>UberMenu Style Generator</h3>';
	echo sssg_generateStyleUI(wpmega_getMenuStyles(), get_option(WPMEGA_STYLES));
	
	$menus = array();
	foreach(wp_get_nav_menus() as $m){
		$menus[$m->slug] = $m->name;
	}
	
	echo '<br/>';	
	echo '</div> <!-- end .style-gen -->  ';
	
	echo '<div class="clear"><h4>Preview</h4></div>';
	
	/* Print the Menu Selection Box */
	echo wpmega_admin_option('menu-preview', array(
							'name'		=>	'Menu to Preview',
							'type'		=>	'select',
							'ops'		=>	$menus,
							
						));
	
	echo '<br/>';
	echo '<input type="hidden" value="is_submitted" name="is_submitted" />';
	echo '<a class="ss-button" href="#" id="wp-mega-menu-preview-button">Preview</a>';
	echo '<input class="ss-button" type="submit" value="Save Options"/>';

	/* Preview Section */
	echo '<div id="wpmega-style-preview"></div>';
	echo '<div class="clear"></div>';
	
	/* Style Generator */
	echo '<div class="style-gen">';
	echo '<div id="wpmega-style-css"><textarea id="wpmega-style-css-code" readonly>'.wpmega_getMegaMenuCSS().'</textarea></div>';
	echo '</div> <!-- end .style-gen -->  ';
	
	echo '</form>';
	
	echo '</div> <!-- end megaMenu-controlPanel -->';	
}

/*
 * Updates the Submitted UberMenu Settings
 */
function wpmega_updateSettings(){
	
	//Only do this on form submission
	if(!isset($_POST['is_submitted'])) return;
		
	//WPMEGA_SETTINGS - go through settings, if checkbox and not set, set to 'off'
	$ops = wpmega_get_admin_options();
	$saveOps = array();
	
	foreach($ops as $key => $o){
		$val = $_POST[$key];
		
		switch($o['type']){
			case 'checkbox':
				if(empty($val)) $val = 'off'; // empty($o['default']) ? 'off' : $o['default']; Don't set to default or we can never have 'off'
				break;		
			case 'header':
				continue;
				break;	
		}
		$saveOps[$key] = $val; 
	}
	
	//Here is where we actually update all the Settings 
	update_option(WPMEGA_SETTINGS, $saveOps);
		
	
	/* WPMEGA_STYLES - Generating CSS */
	
	$styleOps = array();
	foreach(wpmega_getMenuStyles() as $key => $s){	
		$val = $_POST[$key];
		
		switch($s['type']){
			case 'color':
				if($s['color2']){
					$key2 = $key.'-color2';
					$styleOps[$key2] = $_POST[$key2];
				}
				break;			
		}
		
		$styleOps[$key] = $val;		
	}
	
	//Generate and/or store CSS
	if(!isset($_POST['edit-css'])  || $_POST['edit-css'] != 'on'){
		//the CSS is not user-generated, so we'll create it now
		$styleOps['wpmega-style-css-code'] = sssg_generateCSS(wpmega_getMenuStyles(), $_POST);
	}
	else{
		//the CSS is user-generated, so don't recreate it - just save what was submitted
		$styleOps['wpmega-style-css-code'] = $_POST['wpmega-style-css-code'];
	}
	
	//Now save/update the styles
	update_option(WPMEGA_STYLES, $styleOps);
		
}

/*
 * Get the CSS for UberMenu
 */
function wpmega_getMegaMenuCSS(){
	$styles = get_option(WPMEGA_STYLES);
	return $styles['wpmega-style-css-code'];	
}

/*
 * Get the Admin Options for the UberMenu Control Panel
 */
function wpmega_get_admin_options(){
	
	return array(
	
		'wpmega-set'	=>	array(
			'name'		=>	'UberMenu Settings',
			'type'		=>	'header',
			'desc'		=>	''
		
		),
		
		'wpmega-orientation' =>	array(
			'name'		=>	'<strong>Orientation</strong>',
			'type'		=>	'radio',
			'ops'		=>	array(
				'horizontal'	=>	'Horizontal',
				'vertical'		=>	'Vertical'
			),
			'default'	=>	'horizontal',
		),	
		
		'wpmega-transition' =>	array(
			'name'		=>	'<strong>Transition</strong>',
			'type'		=>	'radio',
			'ops'		=>	array(
				'slide'	=>	'Slide',
				'fade'	=>	'Fade'
			),
			'default'	=>	'slide',
		),	
		'wpmega-trigger' =>	array(
			'name'		=>	'<strong>Trigger</strong>',
			'type'		=>	'radio',
			'ops'		=>	array(
				'hover'	=>	'Hover',
				'click'	=>	'Click'
			),
			'default'	=>	'hover',
		),	
		
		/* DESCRIPTION SETTINGS */
		'wpmega-descriptions'	=>	array(
			'name'		=>	'Descriptions &#9662;',
			'type'		=>	'header-2',
			'desc'		=>	''
		
		),
		
		'wpmega-description-0' =>	array(
			'name'		=>	'Display Top-Level Descriptions',
			'type'		=>	'checkbox',
			//'class'		=>	'wpmega-admin-op-box'
		),		
		'wpmega-description-1' =>	array(
			'name'		=>	'Display Sub-Header Descriptions',
			'type'		=>	'checkbox',
		),		
		'wpmega-description-2' =>	array(
			'name'		=>	'Display Sub-Menu Item Descriptions',
			'type'		=>	'checkbox',
		),		
				
		/* IMAGE SETTINGS */
		'wpmega-images'	=>	array(
			'name'		=>	'Image Settings &#9662;',
			'type'		=>	'header-2',
			'desc'		=>	''
		
		),		
		'wpmega-usetimthumb' => array(
			'name'		=>	'Scale and Crop Images',
			'type'		=>	'checkbox',
			'desc'		=>	'Use TimThumb to automatically resize images to the preferred width and height.  You must follow the instructions to setup TimThumb for use.'
		
		),		
		'wpmega-image-width'	=>	array(
			'name'		=>	'Image Width',
			'type'		=>	'text',
			'label'		=>	'px',
			'desc'		=>	'',
		),
		'wpmega-image-height'	=>	array(
			'name'		=>	'Image Height',
			'type'		=>	'text',
			'label'		=>	'px',
			'desc'		=>	'',
		),
		
		/* ADVANCED */
		'wpmega-advanced'	=>	array(
			'name'		=>	'Advanced &#9662;',
			'type'		=>	'header-2',
			'desc'		=>	''		
		),	
		'wpmega-container-w' => array(
			'name'		=>	'Menu Width',
			'type'		=>	'text',
			'label'		=>	'px',
			'desc'		=>	'Enter a width in pixels.  UberMenu automatically sizes to its container, so you only need to use this if you want UberMenu '.
							'to be a different size.  It will automatically be centered when possible.',
		),
		'wpmega-max-submenu-w' => array(
			'name'		=>	'Maximum Submenu Width',
			'type'		=>	'text',
			'label'		=>	'px',
			'desc'		=>	'Normally, a submenu can only be as wide as the top level menu bar.  If you want it to be wider, set the value here.',
		),
		'wpmega-autoAlign' => array(
			'name'		=>	'Auto Align',
			'type'		=>	'checkbox',
			'desc'		=>	'Automatically align the second-level menu items by setting all widths the width of the widest item in each submenu.',
		),
		'wpmega-html5' => array(
			'name'		=>	'HTML5',
			'type'		=>	'checkbox',
			'desc'		=>	'Use the HTML5 <code>&lt;nav&gt;</code> element as the menu container.',
		),
		'wpmega-shortcodes' 	=>	array(
			'name'		=>	'Allow Content Overrides',
			'type'		=>	'checkbox',
			'desc'		=>	'Content Overrides allow you to include non-links in the Mega Menu.  You can use shortcodes, which will allow you to put items like contact forms, search boxes, or galleries in your Menus'
			
		),
		'wpmega-sidebars'	=> array(
			'name'		=>	'Number of Sidebars',
			'type'		=>	'text',
			'desc'		=>	'Enter the number of sidebars that should be generated for the UberMenu.  You can then add widgets through the normal means.'		
		),
		'wpmega-top-level-widgets' => array(
			'name'		=>	'Allow Top-Level Widgets',
			'type'		=>	'checkbox',
			'desc'		=>	'Turn this on to put Widgets in top level menu items, like a search box in the nav bar.',
		),
		'wpmega-jquery' => array(
			'name'		=>	'jQuery Enhanced',
			'type'		=>	'checkbox',
			'desc'		=>	'Disable to use UberMenu without jQuery enhancement.',
			'default'	=>	'on',
		), 
		'wpmega-hover-interval'	=> array(
			'name'		=>	'Hover Interval',
			'type'		=>	'text',
			'label'		=>	'ms',
			'desc'		=>	'The number of milliseconds before the hover event is triggered.  Defaults to 100.'		
		),
		'wpmega-debug' => array(
			'name'		=>	'Debug Mode',
			'type'		=>	'checkbox',
			'desc'		=>	'Run in DEBUG mode.  Disable for production sites.',
		),
		
		
		/* THEME INTEGRATION */
		'wpmega-themeintegration'	=>	array(
			'name'		=>	'Theme Integration &#9662;',
			'type'		=>	'header-2',
			'desc'		=>	'Help for integrating UberMenu with complex or misbehaving themes.'		
		),
		'wpmega-strict' => array(
			'name'		=>	'Strict Mode',
			'type'		=>	'checkbox',
			'desc'		=>	'By default, UberMenu provides some flexibility so it can work even if themes haven\'t '.
							'adhered to WordPress Best Practices in the code.  This leniency can cause the megafication of '.
							'multiple menus, however, so disable it if you\'re having multiple mega menu issues.',
			'default'	=>	'off',
		),
		'wpmega-remove-conflicts' => array(
			'name'		=>	'Remove Theme Conflicts',
			'type'		=>	'checkbox',
			'desc'		=>	'This removes previously registered javascript acting on the menu.',
			'default'	=>	'on',
		),
		'wpmega-minimizeresidual' => array(
			'name'		=>	'Minimize Residual Styling',
			'type'		=>	'checkbox',
			'desc'		=>	'Turn this on if you have styles from your theme that are negatively affecting UberMenu.  '.
							'It will change the ID of the menu\'s top level UL.',
			'default'	=>	'off',
		),
		'wpmega-easyintegrate' => array(
			'name'		=>	'Easy Integration',
			'type'		=>	'checkbox',
			'desc'		=>	'For themes that don\'t properly support WordPress 3 Menus.  Just turn this on and place '.
							'<code>&lt;?php uberMenu_easyIntegrate(); ?&gt;</code> in your header.php file.',
			'default'	=>	'off',
		),
		'wpmega-iefix' => array(
			'name'		=>	'Use IE Fix Script',
			'type'		=>	'checkbox',
			'desc'		=>	'Disable this if it is causing problems or if you are already including it elsewhere.',
			'default'	=>	'on',
		),
		'wpmega-jquery-noconflict' => array(
			'name'		=>	'Run jQuery in noConflict Mode',
			'type'		=>	'checkbox',
			'desc'		=>	'If your theme does not load the default WordPress jQuery library, turn this on.',
			'default'	=>	'off'
		),
		'wpmega-include-jquery' => array(
			'name'		=>	'Include jQuery',
			'type'		=>	'checkbox',
			'desc'		=>	'Don\'t disable this unless you know what you are doing',
			'default'	=>	'on',
		),
		'wpmega-include-hoverintent' => array(
			'name'		=>	'Include Hover Intent',
			'type'		=>	'checkbox',
			'desc'		=>	'You can probably disable this if your theme already includes hoverIntent.js',
			'default'	=>	'on',
		),
		
		/* STYLE SETTINGS */
		'wpmega-style-settings'	=>	array(
			'name'		=>	'Style Settings &#9662;',
			'type'		=>	'header-2',
			'desc'		=>	''		
		),
		'wpmega-style'	=>	array(
			'name'		=>	'Style Application',
			'type'		=>	'radio',
			'ops'		=>	array(
				'preset'	=>	'Use a Preset <span class="ss-admin-op-radio-desc">Select from the Preset Styles below</span>',
				'none'		=>	'Do Nothing <span class="ss-admin-op-radio-desc">I will manually include the styles</span>',
				'inline'	=>	'Include Generated Custom CSS Inline '.
								'<span class="ss-admin-op-radio-desc">(adds <code>&lt;style&gt;</code> tags to <code>&lt;head&gt;</code>)</span>',
			),		
			'default'	=>	'preset'
		),		
		'wpmega-style-preset'	=>	array(
			'name'		=>	'Style Preset',
			'type'		=>	'select',
			'ops'		=>	'wpmega_getStylePresetOps',
			'default'	=>	'grey-white',
		),	
	);	
}

/*
 * Display an Admin Option
 */
function wpmega_admin_option($id, $config, $class=''){
	
	extract($config);
	$settings = get_option(WPMEGA_SETTINGS);
		
	$html = '<div id="container-'.$id.'" class="ss-admin-op '.$class.'">';
	if(!empty($before)) $html.= $before; 
	
	$val = $settings[$id];
	if(!is_numeric($val) && empty($val)) $val = $default;		//must check numeric otherwise we can't use 0
	
	$title = '<label class="ss-admin-op-title" for="'.$id.'">'.$name.'</label>';
	$desc = empty($desc) ? '' : '<span class="ss-admin-op-desc">'.$desc.'</span>';
	$label = '<span class="ss-admin-op-label">'.$label.'</span>';
	
	switch($type){
		
		case 'text':
			$html.= $title;
			$html.= '<input type="text" id="'.$id.'" name="'.$id.'" value="'.$val.'"/>';
			$html.= $label;
			$html.= $desc;
						
			break;
			
		case 'checkbox':
			
			if(empty($val)) $ischecked = $default == 'on' ? true : false;
			else $ischecked = $val == 'on' ? true : false;			
			
			$html.= $title;
			$html.= '<input type="checkbox" id="'.$id.'" name="'.$id.'" '.checked($ischecked, true, false).'/>';
			$html.= $desc;
			$html.= '<div class="clear"></div>';
			
			break;
			
		case 'select':
			
			$html.= '<label class="ss-admin-op-title">'.$name.'</label>'; //$title;
			$html.= '<select id="'.$id.'" name="'.$id.'" >';
			
			if(!is_array($ops)) $ops = $ops();	//if it's not an array it's a function that produces an array
			
			if(is_array($ops)){
				foreach($ops as $opVal => $op){
					$selected = $opVal == $val ? 'selected="selected"' : '';
					
					$html.= '<option value="'.$opVal.'" '.$selected.' >'.$op.'</option>';
				}
			}
			
			$html.= '</select>';
			
			break;
			
		case 'radio':
			
			//$html.= $title;
			$html.= '<label class="ss-admin-op-title">'.$name.'</label>';
			if(is_array($ops)){
				foreach($ops as $opVal => $op){
					
					$ischecked = $val == $opVal ? true : false;
					
					$html.= '<div class="ss-admin-op-radio">';
					$html.= '<input type="radio" id="'.$id.'_'.$opVal.'" name="'.$id.'" value="'.$opVal.'" '.checked($ischecked, true, false).' />';
					$html.= '<label for="'.$id.'_'.$opVal.'">'.$op.'</label>';
					$html.= '</div>';
				}
			}			
			break;
			
		case 'header':
			$html.= '<h3>'.$name.'</h3>';
			break;
			
		case 'header-2':
			$html.= '<h5>'.$name.'</h5>';
			break;
		
	}
	$html.= '</div>';
	
	return $html;
}

/*
 * Get the UberMenu Style Generator options
 */
function wpmega_getMenuStyles(){
	return  array(
	
		/**** MENU BAR ****/
		'menu-bar-header'			=> 	array(
			'name'					=>	'Menu Bar',
			'type'					=>	'header',		
		),		
		'menu-bar-background' 		=>	array(
			'display'				=>	'Background',
			
			'sel'					=>	'#megaMenu ul.megaMenu',
			'property'				=>	'background-color',
			'before'				=>	'#',
			'after'					=>	'',
	
			'type'					=>	'color',
			'color2'				=>	true,
			'special'				=>	'sssg_backgroundGradient_special',
			'msie8'					=>	true,
			'desc'					=>	'Set the second color to create a vertical gradient.  Leave blank for a flat color.'
		),
		
		'menu-bar-border-color'		=>	array(
			'display'				=>	'Border',
			
			'sel'					=>	'#megaMenu ul.megaMenu',
			'property'				=>	'border',
			'before'				=>	'1px solid #',
			'after'					=>	'',
	
			'type'					=>	'color',
			
		),
		
		'menu-bar-border-radius'		=>	array(
			'display'				=>	'Corner Radius',
			
			'sel'					=>	'#megaMenu ul.megaMenu',
			'property'				=>	array('border-radius', '-moz-border-radius', '-webkit-border-radius'),
			'before'				=>	'',
			'after'					=>	'px',
			'units'					=>	'px',
	
			'type'					=>	'text',
			'desc'					=>	'CSS3: Unlikely to work in Internet Explorer'
			
		),
		
		/**** TOP LEVEL ****/
		
		'top-level-header'			=> 	array(
			'name'					=>	'Top Level',
			'type'					=>	'header',		
		),		
		'top-level-item-font-size' 	=> 	array(
		 	'display'				=>	'Font Size',	
		 
			'sel'					=>	'#megaMenu ul.megaMenu > li > a',
			'property'				=>	'font-size',
		
			'type'					=>	'text',
			'desc'					=>	'Example: <em>12px</em> or <em>1.5em</em>'
			
		),		
		'top-level-item-font-color' => 	array(
			'display'				=>	'Font Color',
			'sel'					=>	'#megaMenu ul.megaMenu > li > a',
			'property'				=>	'color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),		
		'top-level-item-text-shadow'=> 	array(
			'display'				=>	'Text Shadow',
			'sel'					=>	'#megaMenu ul.megaMenu > li > a',
			'property'				=>	'text-shadow',
			'before'				=>	'0px 1px 1px #',
	
			'type'					=>	'color',
			
		),		
		'top-level-item-border'=> 	array(
			'display'				=>	'Tab Border (left and right only)',
			'sel'					=>	'#megaMenu ul.megaMenu > li > a',
			'property'				=>	'border-color',
			'before'				=>	'',
			'type'					=>	'color',
			'special'				=>	'sssg_tabBorder_special',
			'color2'				=>	true,
			'sel-first'				=>	'#megaMenu ul.megaMenu > li:first-child > a',
			'sel-last'				=>	'#megaMenu ul.megaMenu > li:last-child > a',
		
		),	
		
		/**** TOP LEVEL HOVER ****/
		'top-level-header-hover'	=> 	array(
			'name'					=>	'Top Level [Hover]',
			'type'					=>	'header',		
		),		
		'top-level-item-background-hover' => 	array(
			'display'				=>	'Tab Background Color [Hover]',
			'sel'					=>	'#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li > a:hover, #megaMenu ul.megaMenu > li.megaHover > a',
			'property'				=>	'background-color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),		
		'top-level-item-font-color-hover' => 	array(
			'display'				=>	'Font Color [Hover]',
			'sel'					=>	'#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li > a:hover, #megaMenu ul.megaMenu > li.megaHover > a',
			'property'				=>	'color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),
		'top-level-item-text-shadow-hover'=> 	array(
			'display'				=>	'Text Shadow [Hover]',
			'sel'					=>	'#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li > a:hover, #megaMenu ul.megaMenu > li.megaHover > a',
			'property'				=>	'text-shadow',
			'before'				=>	'0px 1px 1px #',
			'type'					=>	'color',
		),		
		'top-level-item-border-hover'=> 	array(
			'display'				=>	'Tab and Dropdown Border Color [Hover]',
			'sel'					=>	'#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li.megaHover > a, #megaMenu ul.megaMenu > li.ss-nav-menu-mega > ul.sub-menu-1, #megaMenu ul.megaMenu li.ss-nav-menu-reg ul.sub-menu',
			'property'				=>	'border-color',
			'before'				=>	'#',
			'type'					=>	'color',
		
			'sel-special-2'			=>	'#megaMenu ul.megaMenu > li.ss-nav-menu-mega:hover > a, #megaMenu ul.megaMenu > li.ss-nav-menu-reg.mega-with-sub:hover > a, '.
										'#megaMenu ul.megaMenu > li.ss-nav-menu-mega.megaHover > a, #megaMenu ul.megaMenu > li.ss-nav-menu-reg.mega-with-sub.megaHover > a',
			'sel-special'			=>	'#megaMenu ul.megaMenu > li > ul.sub-menu',
			'special'				=>	'sssg_tabBorder_hover_special',
			
		),	
		
		'top-level-item-border-radius'=> 	array(
			'display'				=>	'Tab and Dropdown Border Radius [Hover]',
			'sel'					=>	'#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li.ss-nav-menu-mega > ul.sub-menu-1, #megaMenu ul.megaMenu li.ss-nav-menu-reg ul.sub-menu',
			'property'				=>	'border-radius',
			'before'				=>	'',
			'after'					=>	'px',
			'type'					=>	'text',
			'units'					=>	'px',
			
			'special'				=>	'sssg_tabBorderRadius_hover_special',
			
		),	
		
		/**** SUB LEVEL ****/
		
		'sub-level-header'			=> 	array(
			'name'					=>	'Sub Menu Level',
			'type'					=>	'header',		
		),
		
		'sub-level-background'		=>	array(
			'display'				=>	'Dropdown Background Color',
			
			'sel'					=>	'#megaMenu li.ss-nav-menu-mega ul.sub-menu.sub-menu-1, #megaMenu li.ss-nav-menu-reg ul.sub-menu',
			'property'				=>	'background-color',
			'before'				=>	'#',
			'after'					=>	'',
	
			'type'					=>	'color',
			'color2'				=>	true,
			'special'				=>	'sssg_backgroundGradient_special',
			//'msie8'					=>	true,
			'desc'					=>	'Set the second color to create a vertical gradient.  Leave blank for a flat color.'
		),
		
		'sub-level-item-font-color' => 	array(
			'display'				=>	'Font Color',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li a, #megaMenu ul ul.sub-menu li a, #megaMenu ul.megaMenu .wpmega-nonlink',
			'property'				=>	'color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),
		
		'sub-level-item-font-size' => 	array(
			'display'				=>	'Font Size',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li a, #megaMenu ul ul.sub-menu li a',
			'property'				=>	'font-size',
	
			'type'					=>	'text',
			'desc'					=>  'Example: 12px or 1.5em',
			
		),
		
		'sub-level-header-font-color' => array(
			'display'				=>	'Header Font Color',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul.sub-menu-1 > li > a, #megaMenu ul li.ss-nav-menu-mega ul.sub-menu-1 > li:hover > a, #megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu .ss-nav-menu-header > a, .wpmega-widgetarea h2.widgettitle',
			'property'				=>	'color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),
		
		'sub-level-header-font-size' => 	array(
			'display'				=>	'Header Font Size',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul.sub-menu-1 > li > a, #megaMenu ul li.ss-nav-menu-mega ul.sub-menu-1 > li:hover > a, #megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu .ss-nav-menu-header > a',
			'property'				=>	'font-size',
			'type'					=>	'text',
			'desc'					=>  'Example: 12px or 1.5em',
			
		),
		
		'sub-level-column-width'	=>	array(
			'display'				=>	'Minimum Column Width',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega  ul.sub-menu li',
			'property'				=>	'min-width',
			'type'					=>	'text',
			'after'					=>	'px',
			'units'					=>	'px',
			'desc'					=>	'You can set the minimum width of the columns in the dropdown.  Useful to align columns in multi-row layouts.'
		
		),
		
		'sub-level-header-hover'	=> 	array(
			'name'					=>	'Sub Menu Level [Hover]',
			'type'					=>	'header',		
		),
		
		'sub-level-item-background-hover' =>	array(
			'display'				=>	'Item Background Color [Hover]',
			
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li a:hover, #megaMenu ul ul.sub-menu > li:hover > a',
			'property'				=>	'background-color',
			'before'				=>	'#',
			'after'					=>	'',
	
			'type'					=>	'color',
		),
		
		'sub-level-item-font-color-hover' => 	array(
			'display'				=>	'Font Color [Hover]',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li a:hover, #megaMenu ul ul.sub-menu > li:hover > a',
			'property'				=>	'color',
			'before'				=>	'#',
	
			'type'					=>	'color',
			
		),
		
		'sub-level-item-border-radius-hover' =>	array(
			'display'				=>	'Corner Radius [Hover]',
			
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li a:hover, #megaMenu ul ul.sub-menu > li:hover > a',
			'property'				=>	array('border-radius', '-moz-border-radius', '-webkit-border-radius'),
			'before'				=>	'',
			'after'					=>	'px',
			'units'					=>	'px',
	
			'type'					=>	'text',
			'desc'					=>	'CSS3: Unlikely to work in Internet Explorer'
			
		),
		
		'sub-level-other-header'	=> 	array(
			'name'					=>	'Other',
			'type'					=>	'header',		
		),
		
		'sub-level-highlight-color'	=>	array(
			'display'				=>	'Highlight Color',
			'type'					=>	'color',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul.sub-menu li.ss-nav-menu-highlight > a, #megaMenu ul li.ss-nav-menu-reg ul.sub-menu li.ss-nav-menu-highlight > a',
			'property'				=>	'color',
			'before'				=>	'#',
			'desc'					=>	'Items that are highlighted will be displayed this color'
		),
		
		'sub-level-secondary-header-font-color'	=>	array(
			'display'				=>	'Secondary Header Font Color',
			'type'					=>	'color',
			'sel'					=>	'#megaMenu ul li.ss-nav-menu-mega ul ul.sub-menu li.ss-nav-menu-header > a, #megaMenu ul ul.sub-menu > li.ss-nav-menu-header > a',
			'property'				=>	'color',
			'before'				=>	'#',
			'desc'					=>	'The color of secondary headers in the submenu'
		),
		
		'image-width'				=>	array(
			'display'				=>	'Image Width',
			'sel'					=>	'.ss-nav-menu-with-img .wpmega-link-title, .ss-nav-menu-with-img .wpmega-link-description',
			'property'				=>	'padding-left',
					
			'type'					=>	'text',
			'after'					=>	'px',
			'units'					=>	'px',
			'desc'					=>	'',
			'special'				=>	'wpmega_addx',
			'addx'					=>	5,
			'desc'					=>	'You can change this setting above.'
		),
		'image-height'	=>	array(
			'display'				=>	'Image Height',
			'sel'					=>	'.ss-nav-menu-with-img',
			'property'				=>	'min-height',
					
			'type'					=>	'text',
			'after'					=>	'px',
			'units'					=>	'px',
			'desc'					=>	'',
			'special'				=>	'wpmega_addx',
			'addx'					=>	5,
			'desc'					=>	'You can change this setting above.'
		),
		
		'image-width-pad'	=>	array(
			'display'				=>	'Image Width Padding',
			'sel'					=>	'.ss-nav-menu-with-img .wpmega-link-title, .ss-nav-menu-with-img .wpmega-link-description',
			'property'				=>	'padding-left',
					
			'type'					=>	'text',
			'after'					=>	'px',
			'units'					=>	'px',
			'desc'					=>	'',
			'special'				=>	'wpmega_addx',
			'addx'					=>	5,
			'desc'					=>	'Auto-generated'
		),
	);
}

/*
 * Get the registered Style Presets as an array of options
 */
function wpmega_getStylePresetOps(){
	global $wpmega_stylePresets;
	
	$ops = array( 'none' => '&nbsp;');
	foreach($wpmega_stylePresets as $id => $s){
		$ops[$id] = $s['name'];
	}	
	return $ops;	
}

$wpmega_stylePresets = array();
/*
 * Register a style preset with UberMenu
 */
function wpmega_registerStylePreset($id, $name, $path){
	global $wpmega_stylePresets;
	$wpmega_stylePresets[$id] = array(
		'name'	=>	$name,
		'path'	=>	$path,	
	);	
}

/* Here is where the Presets are registered */
wpmega_registerStylePreset('grey-white', 	'Black and White', 			WPMEGA_ADMIN_PATH.'/styles/skins/greywhite.css');
wpmega_registerStylePreset('shiny-black', 	'Shiny Black', 				WPMEGA_ADMIN_PATH.'/styles/skins/shinyblack.css');
wpmega_registerStylePreset('simple-green', 	'Simple Green',				WPMEGA_ADMIN_PATH.'/styles/skins/simplegreen.css');
wpmega_registerStylePreset('earthy', 		'Earthy', 					WPMEGA_ADMIN_PATH.'/styles/skins/earthy.css');
wpmega_registerStylePreset('silver-tabs', 	'Silver Tabs',				WPMEGA_ADMIN_PATH.'/styles/skins/silvertabs.css');
wpmega_registerStylePreset('black-silver', 	'Black and Silver', 		WPMEGA_ADMIN_PATH.'/styles/skins/blacksilver.css');
wpmega_registerStylePreset('blue-silver', 	'Blue and Silver', 			WPMEGA_ADMIN_PATH.'/styles/skins/bluesilver.css');
wpmega_registerStylePreset('red-black', 	'Red and Black', 			WPMEGA_ADMIN_PATH.'/styles/skins/redblack.css');
wpmega_registerStylePreset('orange', 		'Burnt Orange', 			WPMEGA_ADMIN_PATH.'/styles/skins/orange.css');
wpmega_registerStylePreset('clean-white', 	'Clean White', 				WPMEGA_ADMIN_PATH.'/styles/skins/cleanwhite.css');
wpmega_registerStylePreset('trans-black', 	'Transparent Black',		WPMEGA_ADMIN_PATH.'/styles/skins/trans_black.css');
wpmega_registerStylePreset('trans-black-hov','Transparent Black Hover',	WPMEGA_ADMIN_PATH.'/styles/skins/trans_black_hover.css');
wpmega_registerStylePreset('tt-silver', 	'Two Tone Silver & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_silver_black.css');
wpmega_registerStylePreset('tt-black', 		'Two Tone Black & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_black_black.css');
wpmega_registerStylePreset('tt-red', 		'Two Tone Red & Black',		WPMEGA_ADMIN_PATH.'/styles/skins/twotone_red_black.css');
wpmega_registerStylePreset('tt-blue', 		'Two Tone Blue & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_blue_black.css');
wpmega_registerStylePreset('tt-green', 		'Two Tone Green & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_green_black.css');
wpmega_registerStylePreset('tt-purple', 	'Two Tone Purple & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_purple_black.css');
wpmega_registerStylePreset('tt-orange', 	'Two Tone Orange & Black',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_orange_black.css');
wpmega_registerStylePreset('tt-silver-s',	'Two Tone Silver & Silver',	WPMEGA_ADMIN_PATH.'/styles/skins/twotone_silver_silver.css');

wpmega_registerStylePreset('custom', 		'Custom', 			WPMEGA_ADMIN_PATH.'/styles/skins/custom.css');

/*
 * Generates a Preview Menu for display in the control panel
 */
function wpmega_getPreview_callback(){
	
	$d = $_POST['data'];
	wp_parse_str($d, $data);
	
	$style_source = $data['wpmega-style'];
	
	$style = '';
	$link = '';
	if($style_source == 'preset'){

		$style = '';
		$id = $data['wpmega-style-preset'];
				
		if(!empty($id)){
			global $wpmega_stylePresets;
			$href = $wpmega_stylePresets[$id]['path'];
			$link = '<link rel="stylesheet" id="wpmega-style-preset-link" href="'.$href.'" type="text/css" media="all" />';
		}
	}
	else{
		$style = sssg_generateCSS(wpmega_getMenuStyles(), $data); //'<style id="wpmega-preview-css" type="text/css">'.
	}	
		
	$html = '<h3>Menu Preview</h3>';
	$html.= '<div class="ss-preview-note">Note: The menu preview gives a general impression of colors and styles, but will not give an exact representation of '.
			'the menu in all cases - especially when using advanced methods like widgets and shortcodes.</div>';
	
	$html.= wp_nav_menu(array('menu' => $data['menu-preview'], 'megaMenu' => TRUE, 'echo' => false));

	$json 			= array();
	$json['content']= wpmega_escape_newlines($html);
	$json['style']	= wpmega_escape_newlines($style);
	$json['link'] 	= $link;
	
	wpmega_json_response($json);
}
add_action('wp_ajax_wpmega_getPreview', 'wpmega_getPreview_callback');			//For logged in users

/*
 * Allows us to override settings for preview or demo purposes
 */
function wpmega_settings_filter_callback($settings){
	
	if(isset($_POST['data'])){
		$d = $_POST['data'];
		wp_parse_str($d, $data);
		
		wpmega_settings_override($settings, 'wpmega-orientation', 	$data);
		wpmega_settings_override($settings, 'wpmega-description-0', $data, 	'off');
		wpmega_settings_override($settings, 'wpmega-description-1', $data, 	'off');
		wpmega_settings_override($settings, 'wpmega-description-2', $data, 	'off');
		wpmega_settings_override($settings, 'wpmega-usetimthumb', 	$data, 	'off');
		wpmega_settings_override($settings, 'wpmega-image-width', 	$data);
		wpmega_settings_override($settings, 'wpmega-image-height', 	$data);		
		wpmega_settings_override($settings, 'wpmega-shortcodes', 	$data, 	'off');		
	}
	
	return $settings;
}
if(is_admin()) add_filter('wpmega_settings_filter', 'wpmega_settings_filter_callback');

/*
 * Allows settings to be overridden
 */
function wpmega_settings_override(&$settings, $id, $data, $default = null){
	if(!empty($data[$id])){
		$settings[$id] = $data[$id];
	}		
	else if(!is_null($default)){
		$settings[$id] = $default;
	} 
}


/*
 * Prints a json response
 */
function wpmega_json_response($data){
		
	header('Content-Type: application/json; charset=UTF-8');	//Set the JSON header so that JSON will validate Client-Side
	
	echo '{ '.wpmega_build_json($data).' }';					//Send the response
		
	die();
}

/*
 * Builds a json object from an array
 */
function wpmega_build_json($ar){
	if($ar == null) return '';
	$txt = '';
	$count = count($ar);
	$k = 1;
	foreach($ar as $key=>$val){	
		$comma = ',';
		if($count == 1 || $count == $k) $comma = '';
		
		if(is_array($val)){
			$txt.= '"'.$key.'" : { ';
			$txt.= ss_build_json($val);
			$txt.= ' }'.$comma."\n ";
		}
		else{
			$quotes = is_numeric($val) ? FALSE : TRUE;	
			$txt.= '"' . str_replace('-', '_', $key).'" : ';
			if($quotes) $txt.= '"';
			$txt.= str_replace('"','\'', $val);
			if($quotes) $txt.= '"';
			$txt.= $comma."\n";			
		}
		$k++;
	}
	return $txt;
}

/*
 * Escape newlines, tabs, and carriage returns
 */
function wpmega_escape_newlines($html){
	
	$html = str_replace("\n", '\\n', $html);
	$html = str_replace("\t", '\\t', $html);
	$html = str_replace("\r", '\\r', $html);
	
	return $html;
	
}

/*
 * Get the Image for a Menu item
 * May use TimThumb, if set in the UberMenu options
 */
function wpmega_getMenuImage($id, $w=50, $h=50){
	
	if(empty($w)) $w = 50; if(empty($h)) $h = 50;
	
	if (has_post_thumbnail( $id ) ){
		$img_id = get_post_thumbnail_id( $id );
		$attachment =& get_post($img_id);
		
		$image = wp_get_attachment_image_src( $img_id, 'single-post-thumbnail' );
		$src = $image[0];
		
		if(is_ssl()) $src = str_replace('http://', 'https://', $src);
		
		$alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
		$title = trim(strip_tags( $attachment->post_title ) );
		if(empty($alt)) $alt = $title;
		
		$settings = get_option(WPMEGA_SETTINGS);
		if($settings['wpmega-usetimthumb'] == 'on'){	
			return wpmega_tt($src, $w, $h, $title, $alt);
		}
		else return '<img height="'.$h.'" width="'.$w.'" src="'.$src.'" alt="'.$alt.'" title="'.$title.'" />';
	}
	return '';
}

/*
 * Get the Image for a Menu Item via AJAX
 */
function wpmega_getMenuImage_callback(){
	
	$id = $_POST['id'];
	
	$id = substr($id, (strrpos($id, '-')+1));
	
	$data = array();
	
	$ajax_nonce = wp_create_nonce( "set_post_thumbnail-$id" );
	$rmvBtn = '<div class="remove-item-thumb" id="remove-item-thumb-'.$id.
				'"><a href="#" id="remove-post-thumbnail-'.$id.
				'" onclick="wpmega_remove_thumb(\'' . $ajax_nonce . '\', '.
				$id.');return false;">' . esc_html__( 'Remove image' ) . '</a></div>';
	
	$data['remove_nonce'] = $ajax_nonce;// $rmvBtn;
	$data['id'] = $id;
	
	$data['image'] = wpmega_getMenuImage($id);
	wpmega_json_response($data);	
}
add_action('wp_ajax_wpmega_getMenuImage', 'wpmega_getMenuImage_callback');			//For logged in users


/*
 * Add to a property.
 */
function wpmega_addx($styles, $id, $ops, $vals){
	
	$add = $ops['addx'] ? $ops['addx'] : 5;
	$styles[$ops['sel']][$ops['property']] = ($vals[$id]+$add) .'px';
	
	return $styles;
	
}

/**
 * This function is paired with a JavaScript Override Function so that we can use our custom Walker rather
 * than the built-in version.  This allows us to include the UberMenu Options as soon as an item is added to the menu,
 * 
 * This is a slightly edited version of case 'add-menu-item' : located in admin-ajax.php
 * 
 * In the future, if WordPress provides a hook or filter, this should be updated to use that instead.
 * 
 */

function wpmega_add_menu_item_callback(){
	
	if ( ! current_user_can( 'edit_theme_options' ) )
		die('-1');

	check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );

	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

	$item_ids = wp_save_nav_menu_items( 0, $_POST['menu-item'] );
	if ( is_wp_error( $item_ids ) )
		die('-1');

	foreach ( (array) $item_ids as $menu_item_id ) {
		$menu_obj = get_post( $menu_item_id );
		if ( ! empty( $menu_obj->ID ) ) {
			$menu_obj = wp_setup_nav_menu_item( $menu_obj );
			$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
			$menu_items[] = $menu_obj;
		}
	}

	if ( ! empty( $menu_items ) ) {
		$args = array(
			'after' => '',
			'before' => '',
			'link_after' => '',
			'link_before' => '',
			//'walker' => new Walker_Nav_Menu_Edit,
			'walker' =>	new wpMegaWalkerEdit,
		);
		echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
	}
}
add_action('wp_ajax_wpmega-add-menu-item', 'wpmega_add_menu_item_callback');			//For logged in users

/**
 * Modifies a string to remove al non ASCII characters and spaces.
 */
function wpmega_slugify($text){
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    
    // trim
    $text = trim($text, '-');
 
    //transliterate
    if (function_exists('iconv')){
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
 
    // lowercase
    $text = strtolower($text);
 
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
 
    if (empty($text)){
        return 'n-a';
    }
 
    return $text;
}

function wpmega_action_links( $links, $file ) {
    if ( $file == 'wp-uber-menu/wp-uber-menu.php' ){
      	$links[] = '<a href="' . admin_url( 'themes.php?page=wp-mega-menu' ) . '">Settings</a>';
		$links[] = '<a href="http://bit.ly/eR0cvC" target="_blank">Support Manual</a>';
    }
    return $links;
}
add_filter('plugin_action_links', 'wpmega_action_links', 10, 2);

function wpmega_showThanks(){
	
	if(isset($_GET['cleared'])){
		update_option('wpmega-thanks', 2);
	}
	if(isset($_GET['reset'])){
		update_option('wpmega-thanks', 1);
	}
	//Pre //Done //Kill
	$status = get_option('wpmega-thanks', 1);
	
	if($status == 2) return;
	
	?>
	<div class="wpmega-thanks">
	<h3>Thank you for installing UberMenu - WordPress Mega Menu Plugin, available exclusively from CodeCanyon!</h3>
	<p>To get started, take a look at the <a href="http://bit.ly/eR0cvC" target="_blank">UberMenu Support Manual</a>, or watch the
	<a href="http://bit.ly/dQaVPJ" target="_blank">screencast</a>.  If you'd like to keep up with UberMenu updates, you can
	follow <a href="http://bit.ly/i1j6wb" target="_blank">@sevenspark</a> on Twitter.</p>
	
	<div class="ops">
	<a class="ss-button button-good" id="wpmega-thanks-cleared" href="<?php echo $_SERVER["REQUEST_URI"].'&cleared=yup';?>">I purchased UberMenu from CodeCanyon</a>
	<a class="ss-button button-bad" href="http://bit.ly/grEsDs">I need a license</a>
	</div>
	<div class="clear"></div>
	</div>
	<?php 
	
}

/*
 * Get the Image for a Menu Item via AJAX
 */
function wpmega_showThanksCleared_callback(){
	
	if(isset($_GET['cleared'])){
		update_option('wpmega-thanks', 2);
	}
	
	$id = substr($id, (strrpos($id, '-')+1));
	
	$data = array();
	
	$ajax_nonce = wp_create_nonce( "thanks-cleared" );
	
	$data['remove_nonce'] = $ajax_nonce;// $rmvBtn;
	$data['response'] = "<h3 style='display:inline-block'>Thank you for your purchase!</h3>";
	wpmega_json_response($data);	
}
add_action('wp_ajax_wpmega_showThanksCleared', 'wpmega_showThanksCleared_callback');			//For logged in users

