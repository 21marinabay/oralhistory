<?php 
/***************************************************************************
 * ss_style_generator.php
 * 
 * Style Generator
 * Version 1.1.3
 * Last Updated: 2010-01-17
 * @author Chris Mavricos, Sevenspark, http://sevenspark.com
 * 
 * Copyright Chris Mavricos, Sevenspark 
 * 
 ***************************************************************************/


/*
 * Creates the User Interface for the Style Generator
 */
function sssg_generateStyleUI($settings, $vals){
	
	$html = '<div>';
	
	foreach($settings as $id => $ops){
		
		$html.= '<div class="ss-style-generator-container ss-admin-op ss-admin-op-'.$ops['type'].'">';
		$label = '<label class="ss-admin-op-title" for="'.$id.'">'.$ops['display'].'</label>';
		$units = '<span class="ss-admin-op-units">'.$ops['units'].'</span>';
		$val = $vals[$id];
		
		switch($ops['type']){
			
			case 'text':
				$html.= $label;
				$html.= '<input type="text" id="'.$id.'" name="'.$id.'" value="'.$val.'"/>';
				$html.= $units;
				$html.= '<span class="ss-admin-op-desc">'.$ops['desc'].'</span>';
				break;
			
			case 'color':
				$html.= $label;
				$html.= '<input class="colorPicker" type="text" id="'.$id.'" name="'.$id.'" value="'.$val.'" />';
				
				if($ops['color2']){
					$html.= '<input class="colorPicker colorPicker-color2" type="text" id="'.$id.'-color2" name="'.$id.'-color2" value="'.$vals[$id.'-color2'].'" />';					
				}
				
				$html.= '<span class="ss-admin-op-desc">'.$ops['desc'].'</span>';
				break;
				
			case 'header':
				$html.= '<h4>'.$ops['name'].'</h4>';
				break;
		}		
		$html.= '</div>';
	}
	$html.= '</div>';
	
	return $html;
}

/*
 * Special CSS generator for Background Gradients
 */
function sssg_backgroundGradient_special($styles, $id, $ops, $vals){
	
	$bkg = $styles[$ops['sel']]['background'];
	$bkgAr = array();
	$color2 = $vals[$id.'-color2'];
	
	if(empty($color2)) return $styles;
	
	if(!empty($bkg)) $bkgAr[] = $bkg;
	$bkgAr[] = '-moz-linear-gradient(top, #'.$vals[$id].', #'.$color2.')';
	$bkgAr[] = '-webkit-gradient(linear, left top, left bottom, from(#'.$vals[$id].'), to(#'.$color2.'))';
	
	/* IE */	
	if(!$ops['msie8']){
		/* These two styles can screw up child sub menu visibility in POS IE, so they are reserved for non-container elements */
		$styles[$ops['sel']]['-ms-filter'] = '"progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#'.$vals[$id].'\', endColorstr=\'#'.$color2.'\')"';
		$styles[$ops['sel']]['filter'] = 'progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#'.$vals[$id].'\', endColorstr=\'#'.$color2.'\')'; 
	}
	$styles[$ops['sel']]['background'] = $bkgAr;
	
	return $styles;
}

/*
 * Special CSS Generator for Tab Borders
 */
function sssg_tabBorder_special($styles, $id, $ops, $vals){
	
	$color1 = $vals[$id];
	$color2 = $vals[$id.'-color2'];

	if(empty($color2)){
		$styles[$ops['sel']]['border-color'] = 'transparent #'.$color1.' transparent transparent';
	}
	else $styles[$ops['sel']]['border-color'] = 'transparent #'.$color1.' transparent #'.$color2;
	
	$styles[$ops['sel-first']]['border-left-color'] = 'transparent';
	$styles[$ops['sel-last']]['border-right-color'] = 'transparent';
		
	return $styles;
	
}

/*
 * Special CSS Generator for Tab Border Hovering
 */
function sssg_tabBorder_hover_special($styles, $id, $ops, $vals){
	
	if(empty($vals[$id])) return $styles;
	
	$bottomColor = $vals['top-level-item-background-hover'];	
	if(empty($bottomColor)) $bottomColor = 'efefef';
	
	$styles[$ops['sel-special-2']]['border-bottom-color'] = '#'.$bottomColor;
	$styles['#megaMenu ul ul.sub-menu']['top'] = '96%';
		
	return $styles;
}

/*
 * Special CSS Generator for Tab Border-Radius Hovering
 */
function sssg_tabBorderRadius_hover_special($styles, $id, $ops, $vals){
	unset($styles[$ops['sel']][$ops['property']]);
	
	$r = $vals[$id];
	
	$v = $vals[$id] .'px '. $vals[$id].'px 0px 0px';
	$styles['#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li.megaHover a']['border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li.megaHover a']['-moz-border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li:hover > a, #megaMenu ul.megaMenu > li.megaHover a']['-webkit-border-radius'] = $v;
	
	$v = $vals[$id] .'px';
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-mega > ul.sub-menu-1']['border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-mega > ul.sub-menu-1']['-moz-border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-mega > ul.sub-menu-1']['-webkit-border-radius'] = $v;
	
	$v = '0px 0px '.$r.'px '.$r.'px';
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu']['border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu']['-moz-border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu']['-webkit-border-radius'] = $v;
	
	$v = '0px '.$r.'px 0px '.$r.'px';
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu ul.sub-menu']['border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu ul.sub-menu']['-moz-border-radius'] = $v;
	$styles['#megaMenu ul.megaMenu > li.ss-nav-menu-reg ul.sub-menu ul.sub-menu']['-webkit-border-radius'] = $v;
	
	return $styles;
}

/*
 * Generates the CSS from the given settings
 */
function sssg_generateCSS($settings, $vals){
	
	$styles = array();
	$defaults = array(
		'before' => '', 'after'	=> '',
	);
	
	foreach($settings as $id => $ops){
		
		if(empty($vals[$id])) continue;
		
		if(empty($styles[$ops['sel']])) $styles[$ops['sel']] = array();
		
		$ops = wp_parse_args( $ops, $defaults );		
		$val = $ops['before'] . $vals[$id] . $ops['after'];
		
		if(is_array($ops['property'])){
			foreach($ops['property'] as $p){
				$styles[$ops['sel']][$p] = $val;
			}
		}
		else $styles[$ops['sel']][$ops['property']] = $val;
		
		if(!empty($ops['special'])){
			$styles = call_user_func($ops['special'], $styles, $id, $ops, $vals);
		}		
	}
	
	$html = '';
	foreach($styles as $sel => $s){
		
		$html.= $sel .'{'."\n";
		
		foreach ($s as $property => $val){
			if(is_array($val)){
				foreach($val as $v){
					if(!empty($val)) $html.= "\t".$property.':'.$v.';'."\n";
				}
			}
			else if(!empty($val)) $html.= "\t".$property.':'.$val.";\n";
		}
		$html.= '}'."\n";		
	}
	return $html;
}
