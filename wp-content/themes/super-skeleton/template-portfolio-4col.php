<?php
/*
 * Template Name: 4 Column Portfolio
*/

get_header();
?>

<!-- Default Portfolio View Variable for Footer -->					
<?php 					
if(get_custom_field('portfolio_view') == 'Hybrid') : 
	$GLOBALS[ 'portfolio_view' ] = 'Hybrid';
elseif(get_custom_field('portfolio_view') == 'List') :
	$GLOBALS[ 'portfolio_view' ] = 'List';					
else :			
	$GLOBALS[ 'portfolio_view' ] = 'Grid';
endif; 
?>					
<!-- End Default Portfolio View - Variable will be used in the footer -->


<!-- Super Container -->
<div class="super-container full-width main-content-area portfolio-4" id="section-content" >


	<!-- 960 Container -->
	<div class="container" style="position:static;" >		
				
					
		<!-- CONTENT -->
		
			<!-- Page Title -->
			<div class="sixteen columns content">			
				<h2 class="title"><span><?php the_title(); ?></span></h2>
			</div>
			
			<!-- Filter Navigation -->
			<div class="sixteen columns portfolio-nav">

					
				<p class="portfolio-view">
					<span>Layout:</span>
					<span class="grid_btn 4-col-grid"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/btn_grid.png" alt="Grid View" /></span> 
					<span class="hybrid_btn 4-col-hybrid"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/btn_hybrid.png" alt="Hybrid View" /></span>
					<span class="list_btn 4-col-list"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/btn_list.png" alt="List View" /></span>
				</p>
			</div> 
			
			<!-- Portfolio List-->  
			<div id="portfolio-list" class="content">
		
			<!-- CATEGORY QUERY + START OF THE LOOP -->
			
			
			<?php get_template_part( 'element', 'categoryfilterquery' ); ?>
			
			
			
			
			<?php while (have_posts()) : the_post(); ?>
			
				<!-- ============================================ -->
			
				<?php if (has_post_thumbnail( $post->ID )) {
						
							//print($post->ID);
						
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
							$custom_lightbox = 'Yes';
						} else {							
							$lightbox_link = $image[0];		
							$custom_lightbox = 'No';
						}
						
						?>
						
						<!-- Begin Portfolio Module Container -->
						<div class="four columns module-container 				
							
							<?php //FILTERS: Here's where we add in the individual category slugs for each individual post
							
								//Declare our post slug - we'll use it later for the lightbox gallery hook
								$post_slug = str_replace(" ", "-",$post->post_name);
											
								$postcats = get_the_category();
								if ($postcats) {
								  foreach($postcats as $cat) {
									echo $cat->slug . ' '; 
								  }
								}
							?>">
							
							<!-- Begin Module -->
							<div class="module" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
						
								<!-- Begin Module Image -->
								<div class="module-img">							
									<a href="<?php if (get_option_tree('open_as_lightbox') == 'Yes') { echo $lightbox_link; } else { the_permalink(); } ?>" <?php if (get_option_tree('open_as_lightbox') == 'Yes') { ?>data-rel="prettyPhoto[<?php echo $post_slug; ?>]"<?php } ?>>
										<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" />
										<span></span>
									</a>							
									<div class="lightboxLink">
										<a class="popLink boxLink" href="<?php echo $lightbox_link; ?>" data-rel="prettyPhoto[<?php the_title(); ?>]" title="<?php the_title(); ?>"></a>
									</div>						    
									<div class="thumbLink">
										<a class="popLink" href="<?php the_permalink(); ?>" title="Full Post"></a>
									</div>						    
								</div>
								<!-- End Module Image -->
								
								<!-- Begin Module Meta -->
								<div class="module-meta">
									<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>	
									<hr class="half-bottom" />
									<p><?php echo excerpt(25); ?></p									
											
									<!-- Invisible Lightbox Gallery Links -->
									<?php //Loop through the posts image attachment for the lightbox gallery
										if (get_option_tree('open_as_lightbox') == 'Yes') { ?>
										<!-- Display our invisible gallery links if they exist - for the lightbox -->
										<div style="display: none;" class="gallery_links">								
											<?php 								
											$args = array(
												'order'          => 'ASC',
												'post_type'      => 'attachment',
												'post_parent'    => $post->ID,
												'post_mime_type' => 'image',
												'post_status'    => null,
												'exclude' => get_post_thumbnail_id(),
												'numberposts'    => null
											);
											
											$attachments = get_posts($args);			
											$post_slug = str_replace(" ", "-",$post->post_name);											
											
											if ($attachments) {
												foreach ($attachments as $attachment) {
													$attachment_url = wp_get_attachment_url($attachment->ID, 'full');
													echo '<a data-rel="prettyPhoto['.$post_slug.']" href="'.$attachment_url.'"></a>';
												}
											}
											?>
										</div>	
									<?php } ?>
									<!-- End Invisible Lightbox Gallery Links -->
									
								</div>	
								<!-- End Module Meta -->
								
							</div>
							<!-- End Module -->
							
						</div>
						<!-- End Module Container -->
				
				<?php } ?>
			
				<!-- ============================================ -->
						
			<?php endwhile; ?>
			<!-- /POST LOOP -->
			
		<!-- Previous / More Entries -->
		<div class="article_nav sixteen columns">
		<!-- <br />
		<hr /> -->
			<div class="p button"><?php next_posts_link('Previous Posts'); ?></div>
			<div class="m button"><?php previous_posts_link('Next Posts'); ?></div>
		</div>
		<br class="clearfix" />
		<!-- </Previous / More Entries -->
		
		</div>	
		<!-- /CONTENT -->
				

	</div>
	<!-- /End 960 Container -->
	
</div>
<!-- /End Super Container -->


<!-- ============================================== -->

					
					
<?php get_footer(); ?>