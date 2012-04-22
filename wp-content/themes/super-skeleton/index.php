<?php get_header(); ?>


<!-- Super Container -->
<div class="super-container full-width main-content-area" id="section-content">


	<!-- 960 Container -->
	<div class="container">		
		
		
		<!-- CONTENT -->
		<div class="eleven columns content">
		
			
			<h2 class="title"><span>
								
				<!-- TAG CONDITIONAL TITLE -->
				<?php if ( is_tag() ) :	?>			
					Tag: <?php single_tag_title(); ?>
				<?php endif; ?>
							
				<!-- CATEGORY CONDITIONAL TITLE -->
				<?php if ( is_category() ) :	?>			
					Category: <?php single_cat_title(); ?>
				<?php endif; ?>	
			</span></h2>
			
			
			<!-- ============================================== -->
			
			
			<!-- QUERY + START OF THE LOOP -->
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>		
			
				<?php get_template_part( 'element', 'excerpt' ); ?>				
							
			<?php endwhile; ?>				
			<?php endif; ?>
			<!-- /POST LOOP -->
			
			
			<!-- ============================================== -->
			
			
		<!-- Previous / More Entries -->
		<div class="article_nav">
		<hr />
			<div class="p button"><?php next_posts_link('Previous Posts'); ?></div>
			<div class="m button"><?php previous_posts_link('Next Posts'); ?></div>
		</div>
		<!-- </Previous / More Entries -->
		
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