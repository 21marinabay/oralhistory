<?php
/*
 * Template Name: Hybrid Blog (2-columns)
*/

get_header(); 
?>


<!-- Super Container -->
<div class="super-container full-width main-content-area hybrid-blog-2" id="section-content">


	<!-- 960 Container -->
	<div class="container">		
		
		
		<!-- CONTENT -->
		<div class="sixteen columns content">		
			
			<h2 class="title"><span><?php the_title(); ?></span></h2>
		
		</div>
			
			<!-- ============================================== -->			
			
			
			<!-- CATEGORY QUERY + START OF THE LOOP -->
			<?php get_template_part( 'element', 'categoryfilterquery' ); ?>
			<?php while (have_posts()) : the_post(); ?>		
									
				<div class="eight columns hybrid">
				
				
				<!-- THE POST EXCERPT -->	
				<div class="the_content post type-post hentry excerpt clearfix" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
					
					<hr class="partial-bottom" />
						
					
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					 
				 	
				 	
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
						<div class="">
							<div class="aside"> 
								<a href="<?php if (get_option_tree('open_as_lightbox') == 'Yes') { echo $lightbox_link; } else { the_permalink(); } ?>" <?php if (get_option_tree('open_as_lightbox') == 'Yes') { ?>data-rel="prettyPhoto[Gallery]"<?php } ?>>
									<img src="<?php echo $sencha; ?><?php echo $image[0]; ?>" alt="<?php the_title(); ?>" />
								</a>
							</div> 
						</div>															
					 
						<div class="">
							<?php echo excerpt(30); ?>
						</div> 
						
					
					<?php } else { ?>	 
						<div>
							<?php echo excerpt(30); ?>
						</div> 
					<?php } ?>					
					
					
					
				</div>
				<!-- /THE POST EXCERPT -->
				
				
				</div>
							
			<?php endwhile; ?>
			<!-- /STOP LOOP -->
			
			
			<!-- ============================================== -->		
			
		<div class="sixteen columns content">	
		
		<!-- Previous / More Entries -->
		<br />
		<hr />
		<div class="article_nav">
			<div class="p button"><?php next_posts_link('Previous Posts'); ?></div>
			<div class="m button"><?php previous_posts_link('Next Posts'); ?></div>
		</div>
		<br class="clearfix" />
		<!-- </Previous / More Entries -->
		
		</div>	
		<!-- /CONTENT -->
		
		
		<!-- ============================================== -->
			
				

	</div>
	<!-- /End 960 Container -->
	
</div>
<!-- /End Super Container -->


<!-- ============================================== -->


<?php get_footer(); ?>