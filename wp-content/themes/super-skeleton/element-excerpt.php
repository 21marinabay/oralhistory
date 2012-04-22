				<!-- THE POST EXCERPT -->	
				<div class="the_content post type-post hentry excerpt clearfix" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
					
					<hr class="partial-bottom" />
					
					<div class="my-avatar">
						<a href="<?php the_permalink(); ?>">
							<?php echo get_avatar( get_the_author_meta('email'), '32' ); ?>
						</a>
					</div>		
					
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					
<!--					<div class="date"> 
						Posted on <?php the_time('jS');?> <?php the_time('F');?>, by <?php the_author(); ?> in <?php the_category(', ') ?>. <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>				
					</div>	 -->
				 	
				 	
				 	<!-- Thumbnail + Excerpt-->
				 	<?php if (has_post_thumbnail( $post->ID )) {
				 		
						// Check for Sencha preferences, set the variable if the user wants it.
						// Unused as of 1.04 for the time being until some bugs get sorted out
				 		if (get_option_tree('sencha') == 'Yes') { 
							$sencha = 'http://src.sencha.io/';
						} else {
							$sencha = '';
						} 
						
						// Grab the URL for the thumbnail (featured image)
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
						
						// Check for a lightbox link, if it exists, use that as the value. 
						// If it doesn't, use the featured image URL from above.
						if(get_custom_field('lightbox_link')) { 							
							$lightbox_link = get_custom_field('lightbox_link'); 							
						} else {							
							$lightbox_link = $image[0];							
						}
						
						?>
						<div class="three columns alpha">
							<div class="aside"> 
								<a href="<?php if (get_option_tree('open_as_lightbox') == 'Yes') { echo $lightbox_link; } else { the_permalink(); } ?>" <?php if (get_option_tree('open_as_lightbox') == 'Yes') { ?>data-rel="prettyPhoto[Gallery]"<?php } ?>>
									<img src="<?php echo $sencha; ?><?php echo $image[0]; ?>" alt="<?php the_title(); ?>" />
								</a>
							</div> 
						</div>															
					 
						<div class="eight columns omega">
							<?php the_excerpt(); ?>
						</div> 
						
						<br class="clearfix" />
					
					<?php } else { ?>	 
						<div>
							<?php the_excerpt(); ?>
						</div> 
					<?php } ?>					
					
					
					<!-- META AREA -->
					<div class="meta-space">					
						<div class="tags clearfix <?php echo get_option_tree('tags_color'); ?>">
							<img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/tag.png" class="tag_icon" />
							<?php the_tags(' ',' '); ?>				
						</div>				
					</div> 
					<!-- /META AREA -->
				</div>
				<!-- /THE POST EXCERPT -->