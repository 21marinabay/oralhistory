<?php 

define('WPMEGA_NOTEXT', '--notext--');
define('WPMEGA_SKIP', '--divide--');
define('WPMEGA_DIVIDER', '<hr class="wpmega-divider" />'); // '<div class="wpmega-divider"></div>');

/*
 * Walker for the Front End UberMenu
 */
class wpMegaWalker extends Walker_Nav_Menu{

	private $index = 0;
	
	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu sub-menu-".($depth+1)."\">\n";
	}
	
	function start_el(&$output, $item, $depth, $args){
		$settings = wpmega_getSettings(); //get_option(WPMEGA_SETTINGS);
		
		//Test shortcode settings
		$shortcode = get_post_meta( $item->ID, '_menu_item_shortcode', true);
		$shortcodeOn = $depth > 0 && $settings['wpmega-shortcodes'] == 'on' && !empty($shortcode) ? true : false;
		
		//Test sidebar settings
		$sidebar = get_post_meta( $item->ID, '_menu_item_sidebars', true);
		$sidebarOn = ($settings['wpmega-top-level-widgets'] == 'on' || $depth > 0) && !empty($settings['wpmega-sidebars']) && !empty($sidebar) ? true : false;
		
		//For --Divides-- with no Content
		if(($item->title == '' || $item->title == WPMEGA_SKIP) && !$shortcodeOn  && !$sidebarOn ){ 
			if($item->title == WPMEGA_SKIP) $output.= '<li id="menu-item-'. $item->ID.'" class="wpmega-divider-container">'.WPMEGA_DIVIDER; //.'</li>'; 
			return; 
		}	//perhaps the filter should be called here
		          
		global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
        //Handle class names depending on menu item settings
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        //The Basics
        if($depth == 0) $classes[] = 'ss-nav-menu-item-'.$this->index++;
        $classes[] = 'ss-nav-menu-item-depth-'.$depth;
           
        //Megafy (top level)
        if($depth == 0 && get_post_meta( $item->ID, '_menu_item_isMega', true) != 'off' ) $classes[] = 'ss-nav-menu-mega';
        else if($depth == 0) $classes[] = 'ss-nav-menu-reg';
        
        //Right Align
        if($depth == 0 && get_post_meta( $item->ID, '_menu_item_alignRight', true) == 'on' ) $classes[] = 'ss-nav-menu-mega-alignRight';
        
        //Second Level - Vertical Division
        if($depth == 1){
        	if(get_post_meta( $item->ID, '_menu_item_verticaldivision', true) == 'on' ) $classes[] = 'ss-nav-menu-verticaldivision';
        }
        
        //Third Level
        if($depth >= 2){
	        if(get_post_meta( $item->ID, '_menu_item_isheader', true) == 'on' ) $classes[] = 'ss-nav-menu-header';			//Headers
	        if(get_post_meta( $item->ID, '_menu_item_highlight', true) == 'on' ) $classes[] = 'ss-nav-menu-highlight';		//Highlights
	        if(get_post_meta( $item->ID, '_menu_item_newcol', true) == 'on' ){												//New Columns
	        	$output.= '</ul></li>';
	        	$output.= '<li class="menu-item ss-nav-menu-item-depth-'.($depth-1).' sub-menu-newcol">'.
	        				'<a href="#">&nbsp;</a><ul class="sub-menu sub-menu-'.$depth.'">';
	        }
        }
        
        //Thumbnail
        $thumb = wpmega_getMenuImage($item->ID, $settings['wpmega-image-width'], $settings['wpmega-image-height']);
        if(!empty($thumb)) $classes[] = 'ss-nav-menu-with-img';
        if($item->title == WPMEGA_NOTEXT) $classes[] = 'ss-nav-menu-notext';
        
        if($sidebarOn) $classes[] = 'ss-sidebar';
        if($shortcodeOn) $classes[] = 'ss-shortcode';
        
		$prepend = '<span class="wpmega-link-title">';
        $append = '</span>';
        $description  = ! empty( $item->description ) ? '<span class="wpmega-item-description">'.esc_attr( $item->description ).'</span>' : '';
        
		if(	(	$depth == 0		&& 	$settings['wpmega-description-0'] != 'on')	||
			(	$depth == 1		&& 	$settings['wpmega-description-1'] != 'on')	||
			(	$depth >= 2		&& 	$settings['wpmega-description-2'] != 'on')  ){
        	$description = '';
        }
        
        if(!empty($description)) $classes[] = 'ss-nav-menu-with-desc';
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        //$attributes .= ! empty( $item->class )      ? ' class="'  . esc_attr( $item->class      ) .'"' : '';

        
		
        $item_output = '';
        
        if($shortcodeOn || $sidebarOn){
        	if(!empty($item->title) && trim($item->title) != ''){ 
        		if($item->title == WPMEGA_SKIP){
        			$item_output.= WPMEGA_DIVIDER;
        		}
        		else if($item->title != WPMEGA_NOTEXT){
        			$item_output.= '<a '.$attributes.'>'.apply_filters( 'the_title', $item->title, $item->ID ).'</a>';
        		}
        	}
        	
        	$class = 'wpmega-nonlink';
        	
        	$gooeyCenter = '';
        	if($shortcodeOn){
        		$gooeyCenter = do_shortcode($shortcode);
        	}
        	if($sidebarOn){
        		$class.= ' wpmega-widgetarea ss-colgroup-'.wpmega_sidebar_count($sidebar);	
        		$gooeyCenter = wpmega_sidebar($sidebar);
        	}
        	
        	$item_output.= '<div class="'.$class.'">';
        	$item_output.= $gooeyCenter;
        	$item_output.= '<div class="clear"></div>';	
        	$item_output.= '</div>';
        	
        }
        else if(!empty($item->title) && trim($item->title) != ''){
        	$title = apply_filters( 'the_title', $item->title, $item->ID );
        	if($item->title == WPMEGA_NOTEXT) $title = $prepend = $append = '';
        	
	        $item_output = $args->before;
	                
	        $item_output.= '<a'. $attributes .'>';
	        $item_output.= $thumb;
	        $item_output.= $args->link_before .$prepend . $title . $append;
	        $item_output.= $description . $args->link_after;
	        $item_output.= '</a>';
	        
	        $item_output .= $args->after;
        }

       	$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


class wpMegaWalkerEdit extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int $depth Depth of page.
	 */
	function start_lvl(&$output) {}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int $depth Depth of page.
	 */
	function end_lvl(&$output) {
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth, $args) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = $original_object->post_title;
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)'), $item->title );
		}

		$title = empty( $item->label ) ? $title : $item->label;

		?>

		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><?php echo esc_html( $title ); ?></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php _e('Edit Menu Item'); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>"><?php _e( 'Edit Menu Item' ); ?></a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description description-thin">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<?php _e( 'Link Target' ); ?><br />
						<select id="edit-menu-item-target-<?php echo $item_id; ?>" class="widefat edit-menu-item-target" name="menu-item-target[<?php echo $item_id; ?>]">
							<option value="" <?php selected( $item->target, ''); ?>><?php _e('Same window or tab'); ?></option>
							<option value="_blank" <?php selected( $item->target, '_blank'); ?>><?php _e('New window or tab'); ?></option>
						</select>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); ?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
					</label>
				</p>
				
				
				<!--  START MEGAWALKER ATTS -->
				
				<?php 
					$settings = wpmega_getSettings();
					
					$minSidebarLevel = 1;
					if($settings['wpmega-top-level-widgets'] == 'on'){
						$minSidebarLevel = 0;
					}
					
					echo '<a href="#" class="wpmega-showhide-menu-ops">Show/Hide UberMenu Options</a>';
				?>
				
				<div class="wpmega-atts">
				
				<?php if($settings['wpmega-shortcodes'] == 'on'):?>
				<?php //echo get_post_meta( $item->ID, '_menu_item_shortcode', true); ?>
				<p class="field-description description description-wide wpmega-custom wpmega-l1-plus wpmega-shortcode">
					<label for="edit-menu-item-shortcode-<?php echo $item_id; ?>">
						<input type="text" id="edit-menu-item-shortcode-<?php echo $item_id; ?>" class="edit-menu-item-shortcode" name="menu-item-shortcode[<?php echo $item_id; ?>]" size="30" value="<?php echo htmlspecialchars(get_post_meta( $item->ID, '_menu_item_shortcode', true)); ?>" />
						<span class="ss-desc" title="Display this content, rather than a link.  This input accepts shortcodes so you can display things like contact forms, search boxes, or galleries"><?php _e('Content Override'); ?></span>
					</label>
				</p>
				<?php endif; ?>
				
				<?php if($settings['wpmega-sidebars'] > 0):?>
					<p class="field-description description description-wide wpmega-custom wpmega-l<?php echo $minSidebarLevel;?>-plus wpmega-sidebars">
						<label for="edit-menu-item-sidebars-<?php echo $item_id; ?>">
							<?php echo wpmega_sidebar_select($item_id); ?>
							<span class="description"><?php _e('Display a Widget Area'); ?></span>
						</label>
					</p>
				<?php endif; ?>
				
				<?php //if($depth >= 2):?>
				<p class="field-description description description-wide wpmega-custom wpmega-l2-plus wpmega-isheader">
					<label for="edit-menu-item-isheader-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-isheader-<?php echo $item_id; ?>" class="edit-menu-item-isheader" name="menu-item-isheader[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_isheader', true) == 'on') echo 'checked="checked"';?> />
						<span class="description"><?php	_e('Display this item as a header?'); ?></span>
					</label>
				</p>
				<p class="field-description description description-wide wpmega-custom wpmega-l2-plus wpmega-highlight">
					<label for="edit-menu-item-highlight-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-highlight-<?php echo $item_id; ?>" class="edit-menu-item-highlight" name="menu-item-highlight[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_highlight', true) == 'on') echo 'checked="checked"';?> />
						<span class="description"><?php _e('Highlight this item'); ?></span>
					</label>
				</p>
				<p class="field-description description description-wide wpmega-custom wpmega-l2-plus wpmega-newcol">
					<label for="edit-menu-item-newcol-<?php echo $item_id; ?>">
						
						<input type="checkbox" id="edit-menu-item-newcol-<?php echo $item_id; ?>" class="edit-menu-item-newcol" name="menu-item-newcol[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_newcol', true) == 'on') echo 'checked="checked"';?> />
						<span class="description"><?php _e('Start a new mega column (under same category)?'); ?></span>
					</label>
				</p>
				<?php //endif; ?>
				
				<?php //if($depth == 1):?>
				<p class="field-description description description-wide wpmega-custom wpmega-l1 wpmega-verticaldivision">
					<label for="edit-menu-item-verticaldivision-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-verticaldivision-<?php echo $item_id; ?>" class="edit-menu-item-verticaldivision" name="menu-item-verticaldivision[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_verticaldivision', true) == 'on') echo 'checked="checked"';?> />
						<span class="description"><?php _e('Vertical Division: (Start a new row with this item)'); ?></span>
					</label>
				</p>
				
				<?php //endif; ?>
				
				<p class="field-description description description-wide wpmega-custom wpmega-l0 wpmega-isMega">
					<label for="edit-menu-item-isMega-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-isMega-<?php echo $item_id; ?>" class="edit-menu-item-isMega" name="menu-item-isMega[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_isMega', true) != 'off') echo 'checked="checked"';?> />
						<span class="description"><?php _e('MegaMenu this Tab'); ?></span>
					</label>
				</p>
				<p class="field-description description description-wide wpmega-custom wpmega-l0 wpmega-alignRight">
					<label for="edit-menu-item-alignRight-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-alignRight-<?php echo $item_id; ?>" class="edit-menu-item-alignRight" name="menu-item-alignRight[<?php echo $item_id; ?>]" <?php if(get_post_meta( $item->ID, '_menu_item_alignRight', true) == 'on') echo 'checked="checked"';?> />
						<span class="description"><?php _e('Align Submenu to Right'); ?></span>
					</label>
				</p>
				
				<?php 
					//global $temp_ID; 
					//$temp_ID = $item_id; 
					global $post_ID;		//wp33
					$post_ID = $item_id;	//wp33
					
					$iframeSrc = get_upload_iframe_src('image').'&amp;tab=type&amp;width=640&amp;height=589' ; //media-upload.php?post_id=<?php echo $item_id; &amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=589 
					$wp_mega_link = "Set Thumbnail";
					$wp_mega_img = wpmega_getMenuImage($item_id);
					if(!empty($wp_mega_img)){
						$wp_mega_link = $wp_mega_img;
						$ajax_nonce = wp_create_nonce( "set_post_thumbnail-$item_id" );
						$wp_mega_link.= '<div class="remove-item-thumb" id="remove-item-thumb-'.$item_id.
										'"><a href="#" id="remove-post-thumbnail-'.$item_id.
										'" onclick="wpmega_remove_thumb(\'' . $ajax_nonce . '\', '.$item_id.');return false;">' 
										. esc_html__( 'Remove image' ) . '</a></div>'; 
					}
				?>
				<p class="wpmega-custom-all"><a class="thickbox set-menu-item-thumb" id="set-post-thumbnail-<?php echo $item_id; ?>" href="<?php echo $iframeSrc; ?>" title="Set Thumbnail"><?php 
				echo $wp_mega_link;
				?></a></p>
				</div>
				<!--  END MEGAWALKER ATTS -->




				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php	echo add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}
