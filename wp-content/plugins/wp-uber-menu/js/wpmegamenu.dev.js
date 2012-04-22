/***********************************************
 * UberMenu JavaScript
 * 
 * @author Chris Mavricos, Sevenspark http://sevenspark.com
 * @version 1.1.3
 * Last modified 2011-08-02
 * 
 ***********************************************/

var $u = jQuery;
if(wpmegasettings.noconflict){
	$u = jQuery.noConflict();
}

jQuery(document).ready(function($){
	$u.wpmega = {};
	$u.wpmega.trans = jQuery('#megaMenu').hasClass('megaMenuFade') ? 'fade' : 'slide';
	
	//Client Side
	wpMegaMenu_init();
});

function wpMegaMenu_init(){
	
	//remove events and styles that might be added by the theme, as long as "Remove Conflicts" is not deactivated
	$u('#megaMenu.wpmega-noconflict ul, #megaMenu.wpmega-noconflict ul li, #megaMenu.wpmega-noconflict ul li a')
		.removeAttr('style').unbind();

	$u('#megaMenu').removeClass('megaMenu-nojs').addClass('megaMenu-withjs');
	$u('#megaMenu > ul > li:has(ul)').addClass('mega-with-sub');
	$u('#megaMenu li.ss-nav-menu-reg li:has(ul)').addClass('megaReg-with-sub');
	
	var wpmega_trans = $u('#megaMenu').hasClass('megaMenuFade') ? 'fade' : 'slide';
	var hoverinterval = wpmegasettings && wpmegasettings.hoverinterval != null ? wpmegasettings.hoverinterval : 100;
	
	/* Mega Menu width should max out at parent width, unless it has been set in the override */
	var bwidth = parseInt($u('#megaMenu ul.megaMenu').css('border-right-width'));
	bwidth = isNaN(bwidth) ? 0 : 2*bwidth;
	var sbwidth = parseInt($u('#megaMenu ul li ul.sub-menu-1').css('border-right-width'));
	sbwidth = isNaN(sbwidth) ? 0 : 2*sbwidth;	
	if($u('#megaMenu ul li ul.sub-menu-1').css('maxWidth') == 'none'){
		$u('#megaMenu ul li ul.sub-menu-1').css({
			'maxWidth'	: 	$u('#megaMenu').outerWidth() + bwidth - sbwidth
		});
	}
	
	/* Mega Menus */	
	if($u('#megaMenu').hasClass('megaMenuOnClick')){				//Could remove this and apply to #megaMenu.megaMenuOnClick... I think this is more efficient though
		$u('#megaMenu li.ss-nav-menu-mega.ss-nav-menu-item-depth-0 > a')
			.click(function(){
				
				//here we click on anchor rather than li so that we can capture click for a particular link 
				//rather than submenu, which would render any contained links useless
				
				var $li = $u(this).parent('li');

				//Normal Links				
				if($li.has('ul').size() == 0){ return true; };
				
				if($li.hasClass('wpmega-expanded')){
					$li.removeClass('wpmega-expanded');
					megaMenuCloseSubMenu($li, false);
				}
				else{
					$li.addClass('wpmega-expanded');
					wpmega_showMega($li.get(0));
				}
				
				return false;
			});		
	}
	
	else{ 
		$u('#megaMenu li.ss-nav-menu-mega.ss-nav-menu-item-depth-0')
			.hoverIntent({
				over: function(){				
					wpmega_showMega(this);
				}, 			
				out: function(e){
					if(typeof e === 'object' && $u(e.fromElement).is('#megaMenu form, #megaMenu input, #megaMenu select, #megaMenu textarea, #megaMenu label')){
						return; //Chrome has difficulty with Form element hovers
					}
					megaMenuCloseSubMenu($u(this), false);
				},				
				timeout: 500,
				interval: hoverinterval,
				sensitivity: 2
			});
	}
		
	/* Flyout Menus */
	if($u('#megaMenu').hasClass('megaMenuOnClick')){
		$u('#megaMenu li.ss-nav-menu-reg > a, #megaMenu li.ss-nav-menu-reg li > a')
			.click(function(){
				
				var $li = $u(this).parent('li');
				
				//Normal Links
				if($li.has('ul').size() == 0){ return true; };
				
				if($li.hasClass('wpmega-expanded')){
					$li.removeClass('wpmega-expanded');
					megaMenuCloseSubMenu($li, false);
				}
				else{
					$li.addClass('wpmega-expanded');					
					wpmega_showReg($li.get(0));
				}
				return false;
			});
	}
	else{
		$u('#megaMenu li.ss-nav-menu-reg, #megaMenu li.ss-nav-menu-reg li')
			.hoverIntent({		
				over: function(){
					wpmega_showReg(this);				
				}, 			
				out: function(){
					megaMenuCloseSubMenu($u(this), false);
				},			
				interval: hoverinterval,
				timeout: 500,
				sensitivity: 2
			});
	}
	
	$u('.wpmega-nonlink.wpmega-widgetarea li:last-child').addClass('wpmega-last-child');
}

function wpmega_showMega(li){
	
	megaMenuCloseAllSubMenus(li);
	
	$u(li).addClass('megaHover');

	var $subMenu = $u(li).find('ul.sub-menu-1');
	
	//Setup the first time
	if($u(li).hasClass('ss-nav-menu-mega') && !$subMenu.hasClass('megaMenu-calc')){
		wpmega_setupMegaSubMenu($u(li), $subMenu);
	}
				
	if($u.wpmega.trans == 'fade') $subMenu.fadeIn();
	else $subMenu.slideDown();
	
}

function wpmega_showReg(li){
	
	//At top level, close all
	if($u(li).hasClass('ss-nav-menu-reg')) megaMenuCloseAllSubMenus(li);
	else $u(li).siblings().each(function(){ megaMenuCloseSubMenu($u(this), true) });	//auto close all siblings' sub-menus
	
	$u(li).addClass('megaHover');

	var $subMenu = $u(li).find('> ul.sub-menu');
	
	if($u.wpmega.trans == 'fade') $subMenu.fadeIn();
	else $subMenu.slideDown();
	
	//Horizontal Menus
	if($u(li).parents('#megaMenu').hasClass('megaMenuHorizontal')){
	
		if($subMenu.size() > 0 && $u(li).hasClass('ss-nav-menu-reg') && !$u(li).hasClass('megaMenu-calc')){
			
			borderWidthTop = parseInt($subMenu.css('border-bottom-width'));
			borderWidthBot = parseInt($subMenu.css('border-top-width'));
			borderWidth = borderWidthTop + borderWidthBot;
			
			$a = $u(li).find('> a');
			if(borderWidth > 0)
				$subMenu.css('top', ($a.outerHeight() + ($a.offset().top - $u(li).offset().top) - borderWidthBot)+'px');
			
			if($subMenu.outerWidth() < $u(li).outerWidth()){				
				var minWidth = 
						$u(li).outerWidth()
						- parseInt( $subMenu.css('borderLeftWidth') ) 
						- parseInt( $subMenu.css('borderRightWidth') );

				$subMenu.css('width', minWidth + 'px');				
			}
			$u(li).addClass('megaMenu-calc');
		}	
	}
	//Vertical Menus
	else{
		$subMenu.css('left', $u(li).width()-1);
	}
}

function wpmega_setupMegaSubMenu($item, $subMenu){
	
	$subMenu.css('left', '-999em').show();
	var subWidth = $subMenu.width();
	var subHeight = $subMenu.height();
	var subPad = parseInt($subMenu.css('padding-left')) + parseInt($subMenu.css('padding-right'));
	var subBorderW = parseInt($subMenu.css('border-left-width')) + parseInt($subMenu.css('border-top-width'));
											
	//First, Calculate Width
	var w = new Array();
	var k = 0;
	var maxColW = 0;
	w[k] = subPad; //0
	
	//Clear margins from right-most categories
	$subMenu.find('li.ss-nav-menu-item-depth-1:last').css('marginRight', '0');
	$subMenu.find('li.ss-nav-menu-item-depth-1.ss-nav-menu-verticaldivision').prev().css('marginRight', '0');
	
	//Auto Align
	if($u('#megaMenu').hasClass('wpmega-autoAlign')){
		$subMenu.find('li.ss-nav-menu-item-depth-1').each(function(){
			if($u(this).width() > maxColW) maxColW = $u(this).width();
		});	
		$subMenu.find('li.ss-nav-menu-item-depth-1').width(maxColW);
	}
	
	$subMenu.find('li.ss-nav-menu-item-depth-1').each(function(){
		if($u(this).hasClass('ss-nav-menu-verticaldivision')){
			k++;
			w[k] = subPad; 
		}
		w[k]+= $u(this).outerWidth(true);		//true includes margin
	});
	
	maxW = 0;
	$u.each(w, function(i, val){
		if(val > maxW) maxW = val;
	});
	
	//Horizontal Menus
	if($item.parents('#megaMenu').hasClass('megaMenuHorizontal')){
		
		//Cover up the bottom border if necessary
		var borderWidthTop = parseInt($subMenu.css('border-bottom-width'));
		var borderWidthBot = parseInt($subMenu.css('border-top-width'));
		var borderWidth = borderWidthTop + borderWidthBot;
		
		$a = $item.find('> a');
		
		if(borderWidth > 0)
			$subMenu.css('top', ($a.outerHeight() + ($a.offset().top - $item.offset().top) - borderWidthBot)+'px');
	
		//Make it at least as wide as the top level item
		if(maxW < $item.outerWidth()){
			maxW = $item.outerWidth()
				- parseInt( $subMenu.css('borderLeftWidth') ) 
				- parseInt( $subMenu.css('borderRightWidth') );
			$subMenu.hide().css( { 'width' : maxW , 'left' : 0} );	// ** In this case, align to the left of the Item
		}
		//If it is wider than the top level item, align it center, left, or right
		else{
			//Determine the maximum extents the menu could be
			var maxMaxW = parseInt($u('#megaMenu > ul.megaMenu ul.sub-menu-1').css('maxWidth')) - subPad; // - subBorderW;

			//Some browsers, like Opera, return -1 on the maxWidth call, so we use outerWidth as a second-best alternative
			if(maxMaxW <= 0) maxMaxW = $u('#megaMenu > ul.megaMenu').outerWidth() - subPad - subBorderW;
			
			//If the maxWidth represented by the column widths is larger than the extents imposed by the menu, reduce the maxW
			if(maxW > maxMaxW) maxW = maxMaxW; 
			
			//Set the width of the submenu
			$subMenu.css('width', maxW);
			
			//Then, Calculate Position (align below Link)
			
			var left = 0;										// ** This would align with the submenu's parent item
			var right = 'auto';
			
			//calculate center
			left = (maxW - $item.outerWidth()) / -2;			// ** This centers the submenu below the parent
			
			//If the submenu fits below the top level nav, we'd need to align it to the right or the left if it overflows
			if(maxW <= $u('#megaMenu > ul.megaMenu').outerWidth()){
				//check boundaries - LEFT
				var menuOffsetL = $u('#megaMenu').offset().left;
				var itemOffsetL = $item.offset().left;					
				var menuLeft = -1 * (itemOffsetL - menuOffsetL);	// ** This aligns the submenu with the left edge of the menu
				
				//check boundaries - RIGHT
				//var menuRight = -1 * (itemOffsetL - maxW);		// ** This aligns the submenu with the right edge of the menu
				var menuRight = (itemOffsetL + $item.find('> a').outerWidth()) - (menuOffsetL + $u('#megaMenu ul').outerWidth());
				
				//Align to the Left
				if(left < menuLeft){
					left = menuLeft;
				}
				//Align to the Right
				else if((maxW/2 + $item.find('> a').outerWidth()/2) > $u('#megaMenu ul').outerWidth() + menuLeft){
					right = menuRight;
					left = 'auto';
				}
			}
			//otherwise, we'd want to center below the top level nav
			else{
				var diff = ((maxW - $u('#megaMenu > ul.megaMenu').outerWidth() + subPad + subBorderW) / 2);
				left = -1 * (($item.offset().left) - $u('#megaMenu').offset().left) - diff;
			}
			
			$subMenu.hide().css({ 'right': right, 'left' : left});
		}
	}
	//Vertical Menus
	else{
		
		var $mm = $item.parents('#megaMenu');
		
		//Figure out Top - basically either align it to the top of the menu or to the bottom of the item
		var itemTop = $item.offset().top - $mm.offset().top;
		var itemHeight = $item.outerHeight();
		var subHeight = $subMenu.outerHeight();
		var maxWidth = $mm.outerWidth() - $mm.find('ul.megaMenu').outerWidth();
		if(maxW > maxWidth) maxW = maxWidth;
		
		var top = 0;
		var bottom = 'auto';
		
		if(itemTop + itemHeight < subHeight){
			top = -1 * itemTop;
		}
		else { //if(itemTop + subHeight > $mm.height()){
			top = 'auto';
			bottom = 0;
		}
		$subMenu.hide().css({
			'left'		:		$item.width()-1,
			'width'		:		maxW,
			'top'		:		top,
			'bottom'	:		bottom
		});
		
		
	}
	$subMenu.addClass('megaMenu-calc');
	
}

function megaMenuCloseSubMenu($li, immediate){
		
	var $subMenu = $li.find('> ul.sub-menu');
	
	if(immediate){
		$subMenu.hide();
		$li.removeClass('megaHover').removeClass('wpmega-expanded');
		return;
	}
	
	if($subMenu.size() > 0){
		if($u.wpmega.trans == 'fade') 
			$subMenu.fadeOut('normal', function(){
				$li.removeClass('megaHover').removeClass('wpmega-expanded');
			});
		else $subMenu.slideUp('normal', function(){
				$li.removeClass('megaHover').removeClass('wpmega-expanded');
			});
	}
	else $li.removeClass('megaHover').removeClass('wpmega-expanded');
}

function megaMenuCloseAllSubMenus($not){
	
	var $topLI = $u('#megaMenu ul.megaMenu > li');
	
	if($not != null){ 
		$topLI = $topLI.not($not);
	};
	
	$topLI.each(function(k){
		megaMenuCloseSubMenu($u(this), true);
	});
}
