<?php 

/***************************************************************************
 * wp-uber-menu-shortcodes.php
 * 
 * UberMenu Shortcodes
 * Version 1.1.4
 * Last Updated: 2011-12-12
 * @author Chris Mavricos, Sevenspark, http://sevenspark.com
 * 
 * Copyright Chris Mavricos, Sevenspark 
 * 
 ***************************************************************************/

add_shortcode('uberMenu_easyIntegrate' , 'uberMenu_easyIntegrate');

/*
 * Recent Posts Shortcodes - optional Image (via "Featured Image" functionality).
 */
function wpmega_recent_posts($atts){
	
	extract(shortcode_atts(array(
		'num'		=>	3,		
		'img'		=>	'on',
		'excerpt'	=>	'off',
		'category'	=>	'',
		'default_img' => false,
		'offset'	=>	0,
	), $atts));
	
	$args = array(
		'numberposts'	=>	$num,
		'offset'		=>	$offset,
	);
	if(!empty($category)){
		if(is_numeric($category)){
			$args['category'] = $category;
		}
		else $args['category_name'] = $category;		
	}
	
	$posts = get_posts($args);
	
	$class = 'wpmega-postlist';
	if($img == 'on') $class.= ' wpmega-postlist-w-img';
	
	$html= '<ul class="'.$class.'">';
	foreach($posts as $post){
	  		
		$ex = $post->post_excerpt;
		if($ex == '') $ex = $post->post_content;
		//$ex = wpmega_trim($ex, 20);
		$ex = strip_tags($ex);
		if(strlen($ex) > 50) $ex = substr($ex, 0, 50).'...';
		
		$post_url = get_permalink($post->ID);
		
		$image = '';
		$w = 45;
		$h = 45;

		if($img == 'on') $image = wpmega_getPostImage($post->ID, $w, $h, $default_img);
						
    	$html.= '<li>'.	$image.
    				'<div class="wpmega-postlist-title"><a href="'.$post_url.'">'.$post->post_title.'</a></div>';
    				
    	if($excerpt == 'on')
    		$html.= '<div class="wpmega-postlist-content">'.$ex.'</div>';
    		
    	$html.= 	'<div class="clear"></div>'.
    			'</li>';
	}
	$html.= '</ul>';
	
	return $html;
}
add_shortcode('wpmega-recent-posts', 'wpmega_recent_posts');

/*
 * Column Group Shortcode - must wrap [wpmega-col] shortcode
 */
function wpmega_colgroup($atts, $data){

	$col_index = 0;
	
	$pattern = get_shortcode_regex();
		
	$pat = '/\[wpmega\-col(?<atts>.*?)\]'.'(?<data>.*?)'.'\[\/wpmega\-col\]/s';		//trailing /s makes dot (.) match newlines
	preg_match_all($pat, $data, $matches, PREG_SET_ORDER);
		
	$columns = array(); 
	
	foreach($matches as $m){
		
		//get the colspan
		$colspan_pat = '/colspan="(?<colspan>[\d]*?)"/';
		preg_match($colspan_pat, $m['atts'], $match);
		$colspan = isset($match['colspan']) ? $match['colspan'] : 1;
		
		$col_index += $colspan;	//increment by colspan, so if we have 2 cols in a 2/3rds format it's a 3-col with a 2-span and a 1-span
		
		$columns[] = '[wpmega-col '.$m['atts'].' col_index="'.$col_index.'" ]'.$m['data'].'[/wpmega-col]';
	}
	
	$html.='<div class="ss-colgroup ss-colgroup-'.$col_index.'">';
	
	foreach($columns as $c){		
		$html.= do_shortcode($c);		
	}
	
	$html.= '<div class="clear"></div></div>';
	
	return $html;
}
add_shortcode('wpmega-colgroup', 'wpmega_colgroup');

/*
 * Column Shortcode
 */
function wpmega_col($atts, $data){
	extract(shortcode_atts(array(
		'colspan'		=>	1,
		'col_index'		=>	0,
	), $atts));
	
	$col_index;
	$data = do_shortcode($data);
	$data = wpmega_trim_tag($data, array('br', 'br/', 'br /'));
	return '<div class="ss-col ss-col-'.$col_index.' ss-colspan-'.$colspan.'">'.$data.'</div>';
}
add_shortcode('wpmega-col', 'wpmega_col');

/** this allows shortcodes in widgets **/
add_filter('widget_text', 'do_shortcode');

/* Tag Trimming Helper Function */
function wpmega_trim_tag($s, $tags){
	$s = trim($s);
	foreach($tags as $tag){
		$tag = '<'.$tag.'>';
		if(strpos($s, $tag) === 0){
			$s = substr($s, strlen($tag));	
		}
		if(strpos($s, $tag) === strlen($s) - strlen($tag)){
			$s = substr($s, 0, strlen($s) - strlen($tag));
		}		
	}	
	return $s;
}

