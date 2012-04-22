<?php 

/*
 * This file contains the code necessary to set up a demo site.  It's not activated by
 * default.  
 */

function wpmega_demo_init(){
	if(!is_admin()){
		add_action('wp_print_styles', 'wpmega_demo_load_css');
		add_action('init', 'wpmega_demo_load_js');
	}	
}
add_action( 'plugins_loaded', 'wpmega_demo_init');

function wpmega_demo_load_css(){
	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	wp_enqueue_style('wpmega-demo', 	$tmp.'styles/demo.css', 					false, '1.0', 'all');
}

function wpmega_demo_load_js(){
	$tmp = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	wp_enqueue_script('wpmegamenu-demo', $tmp.'js/demo.js');
}


function wpmega_demo(){
	
	$html = '<div id="wpmega-demo"><div id="wpmega-demo-inner">';
	
	$html.= '<h2>Demo Controls</h2>';
	$html.= '<div class="wpmega-demo-sub">Try out a few of the basic options to see what UberMenu can do.  '.
			'There are many more available via the control panel!</div>';
	
	$descdef = isset($_GET['wpmega-demo-description-0']) ? $_GET['wpmega-demo-description-0'] : 'off';
	$styledef = isset($_GET['wpmega-demo-style']) ? $_GET['wpmega-demo-style'] : 'grey-white';
	$orientdef = isset($_GET['wpmega-demo-orientation']) ? $_GET['wpmega-demo-orientation'] : 'horizontal';
	$transdef = isset($_GET['wpmega-demo-transition']) ? $_GET['wpmega-demo-transition'] : 'slide';
	$triggerdef = isset($_GET['wpmega-demo-trigger']) ? $_GET['wpmega-demo-trigger'] : 'hover';
	
	$html.= '<form action="'.get_bloginfo('url').'">';	
	
	
	
	$html.= wpmega_admin_option('wpmega-demo-transition', array(
		'name'		=>	'Transition',
		'type'		=>	'radio',
		'ops'		=>	array(
							'slide'		=>	'Slide',
							'fade'		=>	'Fade'
						),
		'default'	=>	$transdef,
	));
	
	$html.= wpmega_admin_option('wpmega-demo-trigger', array(
		'name'		=>	'Trigger',
		'type'		=>	'radio',
		'ops'		=>	array(
							'hover'	=>	'Hover',
							'click'	=>	'Click'
		),
		'default'	=>	$triggerdef,
	));
	
	$html.= wpmega_admin_option('wpmega-demo-orientation', array(
		'name'		=>	'Orientation',
		'type'		=>	'radio',
		'ops'		=>	array(
							'horizontal'	=>	'Horizontal',
							'vertical'		=>	'Vertical'
						),
		'default'	=>	$orientdef,
	));
		
	$html.= wpmega_admin_option('wpmega-demo-description-0', array(
		'name'		=>	'Display',
		'type'		=>	'checkbox',
		'class'		=>	'wpmega-admin-op-box',
		'default'	=>	$descdef,
		'before'	=>	'<div class="ss-admin-op-title">Top-Level Descriptions</div>'
	));

	$demoOps = wpmega_getStylePresetOps();
	unset($demoOps['custom']);	
	$html.= wpmega_admin_option('wpmega-demo-style', array(
		'name'		=>	'Style Preset',
		'type'		=>	'select',
		'ops'		=>	$demoOps,
		'default'	=>	$styledef,
		'class'		=>	'clear'
	));
	
	$html.= '<div class="ss-admin-op">';
	$html.= '<input type="hidden" value="is_submitted" name="is_submitted" />';
	$html.= '<input class="ss-button" type="submit" value="Preview Style"/>';
	$html.= '</div>';
	$html.= '<div class="clear"></div>';
	$html.= '</form>';
	
	$html.= '</div></div>';
	
	return $html;	
}

add_shortcode('wpmega-demo', 'wpmega_demo');

function wpmega_demo_settings_filter_callback($settings){
	
	if(isset($_GET['is_submitted'])){		
		if(isset($_GET['wpmega-demo-description-0'])) $settings['wpmega-description-0'] =  $_GET['wpmega-demo-description-0'];
		if(isset($_GET['wpmega-demo-orientation'])) $settings['wpmega-orientation'] =  $_GET['wpmega-demo-orientation'];
		if(isset($_GET['wpmega-demo-transition'])) $settings['wpmega-transition'] =  $_GET['wpmega-demo-transition'];
		if(isset($_GET['wpmega-demo-trigger'])) $settings['wpmega-trigger'] =  $_GET['wpmega-demo-trigger'];
		if(isset($_GET['wpmega-demo-style']) && $_GET['wpmega-demo-style'] != 'none') $settings['wpmega-style-preset'] = $_GET['wpmega-demo-style'];
	}
	
	return $settings;
}
add_filter('wpmega_settings_filter', 'wpmega_demo_settings_filter_callback');

?>