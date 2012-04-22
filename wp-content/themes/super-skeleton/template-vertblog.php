<?php
/*
 * Template Name: Traditional Blog
*/

get_header(); 
?>


<!-- Super Container -->
<div class="super-container full-width main-content-area" id="section-content">


	<!-- 960 Container -->
	<div class="container">		
		
		
		<!-- CONTENT -->
		<div class="eleven columns content">
		
			
			<h2 class="title"><span><?php the_title(); ?></span></h2>
			
			
			<!-- ============================================== -->			
			
			
			<!-- CATEGORY QUERY + START OF THE LOOP -->
			<?php get_template_part( 'element', 'categoryfilterquery' ); ?>
			<?php while (have_posts()) : the_post(); ?>		
									
			
				<?php get_template_part( 'element', 'excerpt' ); ?>		
				
							
			<?php endwhile; ?>
			<!-- /STOP LOOP -->
			
			
			<!-- ============================================== -->		
			
		
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