<?php
/*
 * Template Name: Full Width
*/



get_header();

wp_register_script('smu', get_bloginfo('template_url') . '/smu.js', array('jquery') );  

wp_enqueue_script('smu'); 


?>


<!-- Super Container -->
<div class="super-container full-width main-content-area" id="section-content">

	<!-- 960 Container -->
	<div class="container">
		
		<!-- CONTENT -->
		<div class="sixteen columns content">
			
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
		
		</div>
		<div class="sixteen columns content">
				<?php the_content(); ?>
				
			<?php endwhile; ?>	
			
		</div>
		<div class="sixteen columns content">
			<ul class="tabs">
				<li><a class="active" href="#transcript"><strong>Interactive Transcript</strong></a></li>
				<li><a href="#biodata"><strong>Speaker information and Transcript download</strong></a></li>
			</ul>

			<ul class="tabs-content"><!-- Give ID that matches HREF of above anchors --></p>
				<li id="transcript" class="active">
					<div class="ten columns content" >
					   <div class="interact" style="margin-left:10px;" ><p><strong>INTERACTIVE TRANSCRIPT</strong></p></div>
<div style="margin-left:10px;"><em>You can navigate to another segment of the current media that is playing, by choosing the text below</em></div>
						<div class="transcript">
						<p></p>
						</div>
					</div>
				</li>
				<li id="biodata">
				 
				
					<div class="ten columns content biofull">
					</div>
				</li>
			</ul>

		</div>			
		
		
		
		
		<!-- /CONTENT -->
		

	</div>
	<!-- /End 960 Container -->
	
</div>
<!-- /End Super Container -->


<!-- ============================================== -->

<!-- below this  is footer function -->
<?php get_footer(); ?>
<!-- above this  is footer function -->