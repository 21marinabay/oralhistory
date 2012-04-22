<?php get_header(); ?>

 
<!-- Super Container -->
<div class="super-container main-content-area" id="section-content">

	<!-- 960 Container -->
	<div class="container">
		
		<!-- CONTENT -->
		<div class="eleven columns content">
			
			<!-- 404 MESSAGE -->
			<?php if ( ! have_posts() ) : ?>
				<h2 class="title"><span>Ohhhh Snap! We can't find the page...</span></h2>
				<div class="the_content">	
					<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'sidewinder' ); ?></p>
					<?php get_template_part( 'element', 'search' ); ?>
				</div>
			<?php endif; ?>
			
			<!-- THE POST LOOP -->
			<?php while ( have_posts() ) : the_post(); ?>	
				
				<h2 class="title"><span><?php the_title(); ?></span></h2>
				
				<!-- Featured Image -->
				<?php if(get_option_tree('show_featured_image') == 'Yes') : ?>
	
					<?php if (has_post_thumbnail( $post->ID )) {
						
						if (get_option_tree('sencha') == 'Yes') { 
							$sencha = 'http://src.sencha.io/';
						} else {
							$sencha = '';
						} 
								
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
						<a href="<?php echo $image[0]; ?>" data-rel="prettyPhoto">
							<img class="aligncenter" src="<?php echo $sencha; ?><?php echo $image[0]; ?>" alt="<?php the_title(); ?>" />
						</a>
								
					<br class="clearfix" />					
					<?php } else {} ?>	 
					
				<?php endif; ?>
			
				<?php the_content(); ?>
				
				<?php wp_link_pages('before=<br /><div id="page-links"><span>Pages:</span>&after=</div><hr />&link_before=<div>&link_after=</div>'); ?>
				
				
				
				
			<?php endwhile; ?>	
			
		</div>	
		<!-- /CONTENT -->
		
		<!-- ============================================== -->
		
		<!-- SIDEBAR -->
		<div class="five columns sidebar">
			
			<?php dynamic_sidebar( 'default-widget-area' ); ?>	
				
		</div>
		<!-- /SIDEBAR -->

	</div>
	<!-- /End 960 Container -->
	
</div>
<!-- /End Super Container -->


<!-- ============================================== -->


<?php get_footer(); ?>