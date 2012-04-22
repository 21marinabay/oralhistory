<?php 

/*
Plugin Name: UberMenu - WordPress Mega Menu Plugin
Plugin URI: http://wpmegamenu.com
Description: Create highly customizable Mega Menus through an easy-to-use WordPress Plugin.  **Note that if you get an invalid error upon installation, you need to unzip the file before installing.  Please be sure to follow the <a href="http://bit.ly/i1zVXL" target="_blank">installation instructions</a> precisely.
Version: 1.1.4
Author: Chris Mavricos, SevenSpark
Author URI: http://sevenspark.com
License: You should have purchased a license from http://codecanyon.net/item/ubermenu-wordpress-mega-menu-plugin/154703?ref=sevenspark
*/

/*  Copyright 2011  Chris Mavricos, SevenSpark (email : chris@sevenspark.com) */

/* Constants */
define('WPMEGA_NAV_LOCS', 	'wp-mega-menu-nav-locations');
define('WPMEGA_SETTINGS', 	'wp-mega-menu-settings');
define('WPMEGA_STYLES', 	'wp-mega-menu-styles');
define('WPMEGA_PLUGIN_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
define('WPMEGA_TT', 		WPMEGA_PLUGIN_URL.'timthumb/tt.php');

/* Load Required Files */
require_once('wpMegaWalker.php');				//Handles Menu Walkers for UberMenu front end and Menu Management Backend
require_once('wp-uber-menu-admin.php');			//Handles Administrative functionality
require_once('wp-uber-menu-shortcodes.php');	//Adds useful shortcodes
require_once('ss_style_generator.php');			//Helps generate user-defined CSS styles

$WPMEGA_DEMO = FALSE; //TRUE;					//This should be FALSE for most users
if($WPMEGA_DEMO) require_once('demo.php'); 

/*
 * Initialization Procedures
 */
function wpmega_init(){

	$settings = wpmega_getSettings();
	
	if(!is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ))){
		//Actions
		add_action('wp_print_styles', 'wpmega_load_css');
		add_action('wp_head', 'wpmega_insert_css', 100);				//for the inline CSS option
		add_action('wp_head', 'wpmega_inline', 101);
		
		if($settings['wpmega-iefix'] != 'off') add_action('wp_head', 'wpmega_iefix');	//You can safely disable this if you are including it elsewhere
		
		//Load Javascript unless disabled
		if($settings['wpmega-jquery'] != 'off') add_action('init', 'wpmega_load_js', 500);	
	}	
	
	//Filters
	add_filter('wp_nav_menu_args', 'wpmega_menu_filter', 500);  	//filters arguments passed to wp_nav_menu
	
	if($settings['wpmega-easyintegrate'] == 'on') add_action('init', 'wpmega_register_ubermenu_location');
	
}
add_action( 'plugins_loaded', 'wpmega_init');

//add_action( 'after_setup_theme', 'wpmega_register_sidebars');
add_action( 'widgets_init', 'wpmega_register_sidebars', 500);

/*
 * Add Support for Thumbnails on Menu Items
 *
 * This function adds support without override the theme's support for thumbnails
 * Note we could just call add_theme_support('post-thumbnails') without specifying a post type,
 * but this would make it look like users could set featured images on themes that don't support it
 * so we don't want that.
 */
function wpmega_support_thumbs(){
	
	global $_wp_theme_features;
	$post_types = array('nav_menu_item');
	
	$alreadySet = false;
	
	//Check to see if some features are already supported so that we don't override anything
	if(isset($_wp_theme_features['post-thumbnails']) && is_array($_wp_theme_features['post-thumbnails'][0])){
		$post_types = array_merge($post_types, $_wp_theme_features['post-thumbnails'][0]);
	}
	//If they already tuned it on for EVERY type, then we don't need to do anything more
	elseif(isset($_wp_theme_features['post-thumbnails']) && $_wp_theme_features['post-thumbnails'] == 1){
		$alreadySet = true;
	}
	
	if(!$alreadySet) add_theme_support('post-thumbnails', $post_types);
	
	add_post_type_support('nav_menu_item', 'thumbnail'); //wp33
}

add_action( 'after_setup_theme', 'wpmega_support_thumbs', 500);	//go near the end, so we don't get overridden

/*
 * Load CSS Styles
 */
function wpmega_load_css(){
	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	wp_enqueue_style('wpmega-basic', 	$tmp.'styles/basic.css', 					false, '1.1', 'all');
	
	$settings = wpmega_getSettings();
	if($settings['wpmega-style'] == 'preset' || empty($settings['wpmega-style'])){				
		
		$id = empty($settings['wpmega-style-preset']) ? $id = 'grey-white' : $settings['wpmega-style-preset'];		
		if(!empty($id)){
			global $wpmega_stylePresets;
			$href = $wpmega_stylePresets[$id]['path'];
			wp_enqueue_style('wpmega-'.$id, $href, false, '1.1', 'all'); 
		}		
	} 
}

/*
 * Apply IE Fixes
 */
function wpmega_iefix(){
	?>
	<!--[if lt IE 8]>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
	<![endif]-->
	<?php 
}

/*
 * Load JavaScript
 */
function wpmega_load_js(){

	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));	
	$settings = wpmega_getSettings();	
	
	// Load jQuery - optionally disable for when dumb themes don't include jquery properly
	if($settings['wpmega-include-jquery'] != 'off') wp_enqueue_script('jquery');

	// Load Hover Intent
	if($settings['wpmega-include-hoverintent'] != 'off') 
		wp_enqueue_script('hoverintent', $tmp.'js/hoverIntent.js', array('jquery'), false, true);

	// UberMenu - include at the end to override other scripts
	if($settings['wpmega-debug'] == 'on') 	wp_enqueue_script('wpubermenu', $tmp.'js/wpmegamenu.dev.js', array(), false, true);		
	else 									wp_enqueue_script('wpubermenu', $tmp.'js/wpmegamenu.min.js', array(), false, true);	
	
}

/*
 * Insert User-Generated CSS, if required
 */
function wpmega_insert_css(){
	$settings = get_option(WPMEGA_SETTINGS);
	if($settings['wpmega-style'] == 'inline'){
		?>
		<style type="text/css">
			<?php echo wpmega_getMegaMenuCSS(); ?>		
		</style>		
		<?php
	}
}

function wpmega_inline(){
	$settings = get_option(WPMEGA_SETTINGS);
	//ss_dump($settings);
	/** INLINE CSS SPECIAL **/
	$css = '';
	
	$menuW = $settings['wpmega-container-w'];
	if(!empty($menuW)){
		$css.= "\n\t\t".'#megaMenu{
			width: '.$menuW.'px;		
		}';
	}
	
	$submenuMW = $settings['wpmega-max-submenu-w'];
	if(!empty($submenuMW)){
		$css.= "\n\t\t".'#megaMenu ul.sub-menu-1{
			max-width: '.$submenuMW.'px !important;		
		}';
	}

	if(!empty($css)){
	?>
	<style type="text/css"><?php echo $css; ?>
		
	</style>		
	<?php
	}
	
	/** INLINE JS SPECIAL **/

	$js = '';
	$hoverInterval = $settings['wpmega-hover-interval'];
	if($hoverInterval != ''){
		$js.= "\n\t\t".'wpmegasettings.hoverinterval = '.$hoverInterval.';';	
	}
	
	if($settings['wpmega-jquery-noconflict'] == 'on'){
		$js.= "\n\t\t".'wpmegasettings.noconflict = true;';
	}
	else $js.= "\n\t\t".'wpmegasettings.noconflict = false;';
	
	if(!empty($js)){
		$js = 'var wpmegasettings = {};'.$js;
	
	
	
	?>
<script type="text/javascript">
		<?php echo $js; ?>
		
	</script>
	<?php 	
	}
}

/*
 * Apply options to the Menu via the filter
 */
function wpmega_menu_filter($args){

	$settings = wpmega_getSettings();
	
	//Check to See if this Menu Should be Megafied
	$location = $args['theme_location'];
	$activeLocs = get_option(WPMEGA_NAV_LOCS, array()); 

	//STRICT
	if($settings['wpmega-strict'] == 'on'){
		//Strict Mode requires the location to be set and for that location to be activated
		if((empty($location) || !in_array($location, $activeLocs)) && $args['megaMenu'] != true) return $args;
	}
	//LENIENT
	else{
		//In the Event that the LOCATION is empty, that means the theme author has not 
		//created the menu using the location properly, so we'll go ahead and megafy the menu
		if($args['megaMenu'] != true && !empty($location) && !in_array($location, $activeLocs)) return $args;	//megaMenu setting for manual wp_nav_menu	
	}
	
	
	$args['walker'] 			= new wpMegaWalker();
	$args['container_id'] 		= 'megaMenu';
	$args['container_class'] 	= 'megaMenuContainer megaMenu-nojs';
	$args['menu_class']			= 'megaMenu';
	$args['depth']				= 0;
	$args['items_wrap']			= '<ul id="%1$s" class="%2$s">%3$s</ul>'; //This is the default, to override any stupidity
	
	if($settings['wpmega-html5'] == 'on')				$args['container'] 		= 'nav';
	else 												$args['container'] 		= 'div';	
	
	if($settings['wpmega-style'] == 'preset') 			$args['container_class'].= ' wpmega-preset-'.$settings['wpmega-style-preset'];
	
	if($settings['wpmega-orientation'] == 'vertical')	$args['container_class'].= ' megaMenuVertical';
	else 												$args['container_class'].= ' megaMenuHorizontal';
	
	if($settings['wpmega-transition'] == 'fade')		$args['container_class'].= ' megaMenuFade';
	
	if($settings['wpmega-trigger'] == 'click')			$args['container_class'].= ' megaMenuOnClick';
	else												$args['container_class'].= ' megaMenuOnHover';

	if($settings['wpmega-autoAlign'] == 'on')			$args['container_class'].= ' wpmega-autoAlign';
	
	if($settings['wpmega-jquery'] != 'off')				$args['container_class'].= ' wpmega-withjs';
	else 												$args['container_class'].= ' wpmega-nojs';
	
	if($settings['wpmega-remove-conflicts'] != 'off')	$args['container_class'].= ' wpmega-noconflict';
	
	if($settings['wpmega-minimizeresidual'] == 'on')	$args['menu_id'] = 'megaUber';
	
	return $args;
}

/*
 * Get UberMenu's settings
 */
function wpmega_getSettings(){
	$settings = get_option(WPMEGA_SETTINGS);
	$settings = apply_filters('wpmega_settings_filter', $settings);
	return $settings;
}

/*
 * Get the Post Thumbnail Information
 */
function wpmega_get_post_thumbnail_info($id){
	$i = get_post($id);
	
	$upath = wpmega_get_upload_path();	
	$img_url = get_bloginfo('url').'/'.$upath.'/'.get_post_meta($id, "_wp_attached_file", $single = true);
	$img_title = str_replace('"','\'', $i->post_title);	
	
	return array(
		'url'		=>	$img_url,
		'title'		=>	$img_title,
	);
}

/*
 * Get the Post Thumbnail Image
 */
function wpmega_getPostImage($id, $w=30, $h=30, $default_img = false){
	
	if(empty($w)) $w = 30; if(empty($h)) $h = 30;
	
	if (has_post_thumbnail( $id ) ){
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
		$src = $image[0];
				
		return wpmega_build_img($src, $w, $h);
		
		/*$settings = get_option(WPMEGA_SETTINGS);
		if($settings['wpmega-usetimthumb'] == 'on'){	//echo '<pre>'.wpmega_tt($src, $w, $h).'</pre>';
			return wpmega_tt($src, $w, $h);
		}
		else return '<img height="'.$h.'" width="'.$w.'" src="'.$src.'" alt="" />';*/
	}
	else if($default_img){
		//Use Default Image if Post does not have featured image
		return wpmega_build_img($default_img, $w, $h);
	}
	return '';
}
function wpmega_build_img($src, $w, $h){
	
	if(is_ssl()) $src = str_replace('http://', 'https://', $src);
	
	$settings = get_option(WPMEGA_SETTINGS);
	if($settings['wpmega-usetimthumb'] == 'on'){
		return wpmega_tt($src, $w, $h);
	}
	else return '<img height="'.$h.'" width="'.$w.'" src="'.$src.'" alt="" />';
}

/*
 * Get the Upload Path
 */
function wpmega_get_upload_path(){
	$upath = get_option('upload_path');
	if($upath == '') $upath = 'wp-content/uploads';
	else $upath = trim($upath, '/');
	
	return $upath;
}

/*
 * Variable Dump Utility
 */
if(!function_exists('ss_dump')){
	function ss_dump($var){
		echo '<pre>'; 
		print_r($var);
		echo '</pre>';
	}
}

/*
 * TimThumb function
 */
function wpmega_tt($src, $w, $h, $title='', $alt='', $zc=1){  //, $rel=''){
	
	if(stristr(trim($src), 'http://') != 0){
		$src = get_bloginfo('url').trim($src);
	}
	
	$ttsrc = WPMEGA_TT;
	
	if(is_ssl()) $ttsrc = str_replace('http://', 'https://', $ttsrc);

	$img = '<img src="'.$ttsrc.
				'?src='.$src.
				'&amp;w='.$w.
				'&amp;h='.$h.
				'&amp;zc='.$zc.
				'" alt="'.$alt.'" title="'.$title.'"';
	$img.= '/>';
	return $img;
}


/* Registering Sidebars */
function wpmega_register_sidebars(){
	if(function_exists('register_sidebars')){
		$settings = wpmega_getSettings();
		$numSidebars = $settings['wpmega-sidebars'];
		if(!empty($numSidebars)){
			if($numSidebars == 1){
				register_sidebar(array(
					'name'          => __('UberMenu Widget Area 1'),
					'id'            => 'wpmega-sidebar',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '</h2>' 
				));				
			}
			else{
				register_sidebars( $numSidebars, array(
					'name'          => __('UberMenu Widget Area %d'),
					'id'            => 'wpmega-sidebar',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '</h2>' 
				));
			}
		}
	}
}
/* 
 * Show a sidebar
 */
function wpmega_sidebar($name){
	
	if(function_exists('dynamic_sidebar')){
		ob_start();
		echo '<ul id="wpmega-'.sanitize_title($name).'">';
		dynamic_sidebar($name);		
		echo '</ul>';
		return ob_get_clean();
	}
	return 'none';
}

/*
 * List the available sidebars
 */
function wpmega_sidebar_list(){
	
	$sb = array();
	$settings = wpmega_getSettings();
	$numSidebars = $settings['wpmega-sidebars'];
	
	for($k = 0; $k < $numSidebars; $k++){
		$val = 'UberMenu Widget Area '.($k+1);
		$sb[$val] = $val;
	}
	return $sb;
}

/*
 * Show a sidebar select box
 */
function wpmega_sidebar_select($id){	
	
	$fid = 'edit-menu-item-sidebars-'.$id;
	$name = 'menu-item-sidebars['.$id.']';
	$selection = get_post_meta( $id, '_menu_item_sidebars', true);
	
	$ops = wpmega_sidebar_list();
	if(empty($ops)) return '';
	
	$html.= '<select id="'.$fid.'" name="'.$name.'" class="edit-menu-item-sidebars">';
	
	$html.= '<option value=""></option>';
	foreach($ops as $opVal => $op){
		$selected = $opVal == $selection ? 'selected="selected"' : '';
		$html.= '<option value="'.$opVal.'" '.$selected.' >'.$op.'</option>';
	}
			
	$html.= '</select>';
	
	return $html;
}

/*
 * Count the number of widgets in a sidebar area
 */
function wpmega_sidebar_count($index){
	
	global $wp_registered_sidebars, $wp_registered_widgets;

	if ( is_int($index) ) {
		$index = "sidebar-$index";
	} else {
		$index = sanitize_title($index);
		foreach ( (array) $wp_registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
		return false;

	$sidebar = $wp_registered_sidebars[$index];
	
	return count($sidebars_widgets[$index]);
}


/* Easy Integration for non-WP3 Menus */
function wpmega_register_ubermenu_location() {
	register_nav_menu('ubermenu' , __( 'Uber Menu' ));
}
function uberMenu_easyIntegrate(){
	wp_nav_menu( array( 'theme_location' => 'ubermenu' , 'megaMenu' => true ) );	
}
