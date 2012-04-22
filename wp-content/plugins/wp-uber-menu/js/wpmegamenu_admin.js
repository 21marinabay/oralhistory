/***********************************************
 * UberMenu Administrative JavaScript
 * 
 * @author Chris Mavricos, Sevenspark http://sevenspark.com
 * @version 1.1.4
 * Last modified 2011-12-12
 * 
 ***********************************************/

jQuery(document).ready(function($){
	
	var DEBUG = false;
	
	/** Menu Panel Add New Item Override **/
	/* This overrides the normal addItemToMenu Function, in order to call a different callback which invokes the custom walker */
	if(typeof wpNavMenu != 'undefined'){
		wpNavMenu.addItemToMenu = function(menuItem, processMethod, callback) {
			var menu = $('#menu').val(),
			nonce = $('#menu-settings-column-nonce').val();
		
			processMethod = processMethod || function(){};
			callback = callback || function(){};
		
			params = {
				//'action': 'add-menu-item',
				"action": "wpmega-add-menu-item",
				"menu": menu,
				"menu-settings-column-nonce": nonce,
				"menu-item": menuItem
			};			
		
			$.post( ajaxurl, params, function(menuMarkup) {
				var ins = $('#menu-instructions');
				processMethod(menuMarkup, params);
				if( ! ins.hasClass('menu-instructions-inactive') && ins.siblings().length )
					ins.addClass('menu-instructions-inactive');
				callback();
			});
		};
	}
	
	
	/** Menu Panel Mega Options **/
	//$('p.wpmega-custom').hide();
	$('.wpmega-atts').hide();
	$('.wpmega-showhide-menu-ops').live('click', function(e){
		$(this).siblings('.wpmega-atts').slideToggle();
		return false;
	});
	
	/** Menu Panel Choosing Images **/
	var megaMenuAdminItemID = 0;
	$('.set-menu-item-thumb').click(function(){
		megaMenuAdminItemID = $(this).attr('id');
	});
	
	$('#TB_window').live("unload",
		function(){
			
			var $item = $('#'+megaMenuAdminItemID);
			$item.addClass('wpmega-loading-img');
			
			$.ajax({
				type:	'POST',
				cache:	false,
				url:	ajaxurl,
				data:	{ "action" : "wpmega_getMenuImage",	"id" : megaMenuAdminItemID },
				error:	function(req, status, errorThrown){
					if(DEBUG) console.log('Error: '+status+' | '+errorThrown);
				},
				success: function(data, status, req){
					$item.removeClass('wpmega-loading-img');
					if(data == '' || data.image == ''){
						$item.text('Set Thumbnail');
					}
					else{
						$item.html(data.image);
						$('a#remove-post-thumbnail-'+data.id).remove();
						$item.after(
								'<div class="remove-item-thumb" id="remove-item-thumb-'+data.id+'">'+
									'<a href="#" id="remove-post-thumbnail-'+data.id+'" '+
										'onclick="wpmega_remove_thumb(\''+ data.remove_nonce +'\', '+	data.id+'); return false; ">'+
										'Remove image</a></div>');
					}
				}
			});
			
		});
	
	

	/** For Menus Panel - setup Navigation Locations **/
	$('#wp-mega-menu-navlocs-submit').click(function(){
		var $waiting = $(this).parent().find('.waiting');
		$waiting.fadeIn();
		
		var data = new Array();
		$('#nav-menu-theme-megamenus input[name="wp-mega-menu-nav-loc"]').each(function(){
			if($(this).is(':checked')) data.push($(this).val());
		});
		data = data.join(',');
		
		$.ajax({
			type:	'POST',
			cache:	false,
			url:	ajaxurl,
			data:	{ "action" : "megaMenu_updateNavLocs",	"data" : data },
			error:	function(req, status, errorThrown){
				if(DEBUG) console.log('Error: '+status+' | '+errorThrown);
			},
			success: function(data, status, req){
				$waiting.fadeOut();
			}
		});		
		return false;
	});
	
	/** Setup Color Pickers **/	
	$('input[type="text"].colorPicker').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
			$(el).css({ 'background-color' : '#'+hex} );
			
			color = $(el).val();
			if(color == '') color = '#ffffff';
			else $(el).css('background-color', '#'+color);
			$(el).attr('title', color);
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
	
	//Set initial Color
	$('input[type="text"].colorPicker').each(function(){
		if($(this).val() != '')
			$(this).css({"background-color" : "#"+$(this).val()});
		$(this).attr('title', $(this).val());
		
		$(this).change(function(){
			if($(this).val() == '')
				$(this).css('background-color', '#ffffff');
		});
	});
	
	
	/** Showing and Hiding Style Generator **/
	$('input[name="wpmega-style"]').change(function(){		
		var style_source = $(this).val();		
		if(style_source == 'preset') wpmega_showHideStyleGen(false);
		else wpmega_showHideStyleGen(true);	
	});
	
	if($('input[name="wpmega-style"]:checked').val() == 'preset'){
		wpmega_showHideStyleGen(false);
	}
	
	function wpmega_showHideStyleGen(show){
		if(show) $('.style-gen').slideDown();
		else $('.style-gen').slideUp();
	}
	
	
	$('#wp-mega-menu-preview-button').click(function(){
		
		var data = $('#wpmega-options').serialize();
		var $preview = $('#wpmega-style-preview');
		var $style = $('#wpmega-style-css');
		$preview.addClass('wpmega-loading').html('');
		$style.hide();
					
		$.ajax({
			type:	'POST',
			cache:	false,
			url:	ajaxurl,
			data:	{ "action" : "wpmega_getPreview",	"data" : data }, 
			dataType: 'json',
			error:	function(req, status, errorThrown){
				if(DEBUG) console.log('Error: '+status+' | '+errorThrown);
			},
			success: function(data, status, req){
				//remove any previous <style>
				$('#wpmega-preview-css').remove();
				
				//add styles
				if(data.style == ''){
					var link = data.link;
					
					if(link != ''){
						var $existing = $('#wpmega-style-preset-link');
						if($existing.size() == 0){
							$('head').append(link);
						}
						else{
							$existing.replaceWith(link);
						}
					}
				}	
				else{
					var style = '<style id="wpmega-preview-css" type="text/css">'+data.style+'</style>';
					$('head').append(style);
				}
				
				//show preview
				$preview.removeClass('wpmega-loading').html(data.content);
				
				$style.html(
						'<h3>Generated CSS</h3>'+
						'<input type="checkbox" name="edit-css" id="edit-css"><label for="edit-css">Edit CSS Manually '+
						'(Check this box to save your custom CSS changes below when the options are Saved)</label>'+
						'<div class="ss-admin-op-desc">Warning: Clicking "Preview" will override any manual changes you make!</div>'+
						'<textarea id="wpmega-style-css-code" name="wpmega-style-css-code" readonly>'+data.style+'</textarea>')
					.slideDown();
				
				$('#edit-css').change(function(){
					if($(this).is(':checked')) $('#wpmega-style-css-code').removeAttr('readonly').addClass('wpmega-editable');
					else $('#wpmega-style-css-code').attr('readonly', true).removeClass('wpmega-editable');
				});
				
				wpMegaMenu_init();			
			}
		});		
		return false;
	});
	
	/* Image Height and Width Synchronization */
	$('input#image-width, input#image-height, input#image-width-pad').attr('readonly', true);
	$('input#wpmega-image-width').change(function(){
		$('input#image-width').val($(this).val());
		$('input#image-width-pad').val($(this).val());
	});
	$('input#wpmega-image-height').change(function(){
		$('input#image-height').val($(this).val());
	});
	
	/* Input Sliding Interface */
	$('.ss-admin-op input[type="checkbox"], #wpmega-demo .ss-admin-op input[type="radio"]')	//not(.ss-radio-gallery input)
		//.add('.spark-panel input[type="checkbox"], .spark-panel input[type="radio"]')
		.each(function(k, el){
		var tog = $(el).is(':checked') ? 'on' : 'off';
		var $toggle = $('<label class="ss-toggle-onoff '+tog+'" for="'+$(el).attr('id')+
							'"><span class="ss-toggle-inner"><span class="ss-toggle-on">On</span><span class="ss-toggle-mid"></span><span class="ss-toggle-off">Off</span></span></label>');
		
		
		switch($(el).attr('type')){
		
			case 'checkbox':
		
				$(el).after($toggle);
				$(el).hide();
				
				$toggle.click(function(){
					
					//console.log($(el).is(':checked') ? 'checked' : 'not checked');
					
					if($(el).is(':checked')){
						//console.log('checked');
						var $this = $(this);
						$this.find('.ss-toggle-inner').animate({
							'margin-left'	:	'-51px'
						}, 'normal', function(){
							$this.removeClass('on').addClass('off');
						});
						$(el).attr('checked', false);
					}
					else{
						//console.log('not checked');
						var $this = $(this);
						$this.find('.ss-toggle-inner').animate({
							'margin-left'	:	'0px'
						}, 'normal', function(){
							$this.removeClass('off').addClass('on');
						});
						$(el).attr('checked', true);
					}
					
					return false;	//stops the label click from reversing the check, which is necessary in IE
				});
				break;
				
			case 'radio' :
				var $label = $(el).next('label');
				var labelText = $label.text();
				$label.hide();
				//console.log(labelText);
				
				$(el).after('<span class="ss-tog-label">'+labelText+'</span>');
				$(el).after($toggle);				
				$(el).hide();
				
				$toggle.click(function(){
					if($(this).prev().is(':checked')){
						//Do nothing, it's double clicking a radio button
					}
					else{
						
						var oldID = $('input[name="'+$(el).attr('name')+'"]:checked').attr('id');
						
						//turn on
						var $this = $(this);
						$this.find('.ss-toggle-inner').animate({
							'margin-left'	:	'0px'
						}, 'normal', function(){
							$this.removeClass('off').addClass('on');
						});
						//$this.prev().attr('checked', true);
						$(el).attr('checked', true);
						
						//turn off the old
						$('label[for="'+oldID+'"] .ss-toggle-inner').animate({
							'margin-left'	:	'-51px'
						}, 'normal', function(){
							$(this).parent('label').removeClass('on').addClass('off');
						})
						.siblings('input[type="radio"]').attr('checked', false);
					}
					return false;
				});
				break;
		}
	});
	
	/* Slide Toggle the Advanced options */
	$('.wpmega-config-section').click(function(){
		var id = $(this).attr('id');
		$('.sub-'+id).toggle();
	});
	$('.sub-container').hide();
		//&#9662;
	
	$('#wpmega-thanks-cleared').click(function(){
		$.ajax({
			type:	'GET',
			cache:	false,
			url:	ajaxurl,
			data:	{ "action" : "wpmega_showThanksCleared",	"cleared" : 'yup' },
			error:	function(req, status, errorThrown){
				if(DEBUG) console.log('Error: '+status+' | '+errorThrown);
			},
			success: function(data, status, req){
				$('.wpmega-thanks').slideUp(function(){
					$(this).html(data.response).slideDown(function(){
						$thanks = $(this);
						$button = $('<span style="font-size:12px; margin:0px 0px 0px 20px; padding:5px;" class="ss-button">Dismiss</span>');
						$button.click(function(){
							$thanks.fadeOut();
						});
						$(this).append($button);
					});
				});
			}
		});
		return false;
	});
	
	
	//For WordPress 3.3
	var menuItemID = 0;
	WPSetThumbnailID = function(id){
		menuItemID = id;
		var field = jQuery('input[value="_thumbnail_id"]', '#list-table');
		if ( field.size() > 0 ) {
			jQuery('#meta\\[' + field.attr('id').match(/[0-9]+/) + '\\]\\[value\\]').text(id);
		}
	};
	
	
	//For WordPress 3.3
	WPSetThumbnailHTML = function(html){
		
		var $item = $('#'+megaMenuAdminItemID);
		$item.addClass('wpmega-loading-img');
		
		$.ajax({
			type:	'POST',
			cache:	false,
			url:	ajaxurl,
			data:	{ "action" : "wpmega_getMenuImage",	"id" : megaMenuAdminItemID },
			error:	function(req, status, errorThrown){
				if(DEBUG) console.log('Error: '+status+' | '+errorThrown);
			},
			success: function(data, status, req){
				$item.removeClass('wpmega-loading-img');
				if(data == '' || data.image == ''){
					$item.text('Set Thumbnail');
				}
				else{
					$item.html(data.image);
					$('a#remove-post-thumbnail-'+data.id).remove();
					$item.after(
							'<div class="remove-item-thumb" id="remove-item-thumb-'+data.id+'">'+
								'<a href="#" id="remove-post-thumbnail-'+data.id+'" '+
									'onclick="wpmega_remove_thumb(\''+ data.remove_nonce +'\', '+	data.id+'); return false; ">'+
									'Remove image</a></div>');
				}
			}
		});
	};
	
	//For WordPress 3.3
	WPRemoveThumbnail = function(nonce){};
	
	//For WordPress 3.2
	WPSetAsThumbnail = function(a,b){}
});

function wpmega_remove_thumb(nonce, id){
	jQuery.post(ajaxurl, {
		action:"set-post-thumbnail", post_id: id, thumbnail_id: -1, _ajax_nonce: nonce, cookie: encodeURIComponent(document.cookie)
	}, function(str){
		if ( str == '0' ) {
			alert( setPostThumbnailL10n.error );
		} else {
			if(str != '-1'){
				jQuery('a#set-post-thumbnail-'+id).html('Set Thumbnail');
				jQuery('a#remove-post-thumbnail-'+id).remove();
			}
		}
	});
};


