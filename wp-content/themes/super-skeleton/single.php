<?php get_header(); ?>

  
<!-- Super Container -->
<div class="super-container main-content-area" id="section-content">

<!-- CATEGORY QUERY + START OF THE LOOP -->
<?php while (have_posts()) : the_post(); ?>		

	<!-- 960 Container -->
	<div class="container">		
		
		<?php if(get_custom_field('remove_sidebar') == 'Yes') { $remove_sidebar = 'sixteen'; } ?>
		
		<!-- CONTENT -->
		<div class="eleven columns content <?php echo $remove_sidebar; ?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			
			<h2 class="title"><span><?php the_title(); ?></span></h2>
			
			<hr class="half-bottom" />
					
										
			<div class="date"> 
				Posted on <?php the_time('jS');?> <?php the_time('F');?>, by <?php the_author(); ?> in <?php the_category(', ') ?>. <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>				
			</div>	 
				 	
			<hr />
				 	
			<!-- Featured Image -->
			<?php if(get_option_tree('show_featured_image') == 'Yes') : ?>

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
					<a href="<?php echo $lightbox_link; ?>" data-rel="prettyPhoto[Gallery]">
						<img class="aligncenter" src="<?php echo $sencha; ?><?php echo $image[0]; ?>" alt="<?php the_title(); ?>" />
					</a>
							
				<br class="clearfix" />					
				<?php } else {} ?>	 
				
			<?php endif; ?>
				
				
				<!-- THE POST LOOP -->				
				
				
				<!-- ============================================ -->
			
			
				<!-- THE POST CONTENT -->	
				<div class="the_content post type-post hentry excerpt clearfix">	
					
					
					
					<?php the_content(); ?>
					<br />
					<?php wp_link_pages('before=<div id="page-links"><span>Pages:</span>&after=</div>&link_before=<div>&link_after=</div>'); ?>
					<hr />
										
					
					<!-- META AREA -->
					<div class="meta-space">					
						<div class="tags clearfix <?php echo get_option_tree('tags_color'); ?>">
							<img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/tag.png" class="tag_icon" />
							<?php the_tags(' ',' '); ?>				
						</div>				
					</div> 
					<!-- /META AREA -->
					
				
				</div>
				<!-- /THE POST CONTENT -->
				
				<br />
				<hr class="remove-top"/>
				
				<!-- COMMENTS SECTION -->
				<?php comments_template(); ?>
				<div class="hidden"><?php wp_list_comments(); ?></div>
				<?php next_comments_link(); previous_comments_link(); ?>
				<div class="hidden"><?php comment_form(); ?></div>
				<!-- COMMENTS-SECTION -->
				
				
							
			<?php endwhile; ?>
			<!-- /POST LOOP -->			
	
		
		</div>	
		<!-- /CONTENT -->
		
		
		
		
		<!-- ============================================== -->
		
		
		<?php if(get_custom_field('remove_sidebar') == 'Yes') { } else { ?>
		<!-- SIDEBAR -->
		<div class="five columns sidebar">
			
			<?php dynamic_sidebar( 'default-widget-area' ); ?>	
				
		</div>
		<!-- /SIDEBAR -->	
		<?php } ?>
				

	</div>
	<!-- /End 960 Container -->
	
</div>
<!-- /End Super Container -->


<!-- ============================================== -->


<?php get_footer(); ?>