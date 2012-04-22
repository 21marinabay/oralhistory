<?php get_header(); ?>


<!-- ============================================== -->


<!-- FlexSlider -->
<?php if(get_option_tree('frontpage_slider') == 'Yes') { ?>
	<?php get_template_part( 'element', 'flexslider' ); ?>
<?php } ?>	


<!-- ============================================== -->


<?php if(get_option_tree('frontpage_blurbrow') == 'No' ) { } else { ?>
<!-- Super Container - Three Column Row - Content comes from OptionTree -->
<div class="super-container full-width" id="section-thirds">

	<!-- 960 Container -->
	<div class="container">		
		
		<?php if (get_option_tree('sencha') == 'Yes') { 
				$sencha = 'http://src.sencha.io/';
			  } else {
				$sencha = '';
			  } ?>
		
		<!-- 1/3 Columns -->
		<div class="one-third column">				
				<div>							
						<img src="<?php echo get_option_tree('homepage_blurb_1_image'); ?>" alt="image"/>
						<span></span>				    
				</div>			
				<div class="module-meta">
					<h5><a href="<?php echo get_option_tree('homepage_blurb_1_url'); ?>"><?php echo get_option_tree('homepage_blurb_1_title'); ?></a></h5>	
					<p><?php echo get_option_tree('homepage_blurb_1_text'); ?></p>
				</div>					
		</div>

		<div class="one-third column">				
				<div>							
						<img src="<?php echo get_option_tree('homepage_blurb_2_image'); ?>" alt="image"/>
						<span></span>				    
				</div>			
				<div class="module-meta">
					<h5><a href="<?php echo get_option_tree('homepage_blurb_2_url'); ?>"><?php echo get_option_tree('homepage_blurb_2_title'); ?></a></h5>	
					<p><?php echo get_option_tree('homepage_blurb_2_text'); ?></p>
				</div>					
		</div>

		<div class="one-third column">				
			<div class="module">	
				<div>							
						<img src="<?php echo get_option_tree('homepage_blurb_3_image'); ?>" alt="image"/>
						<span></span>				    
				</div>			
				<div class="module-meta">
					<h5><a href="<?php echo get_option_tree('homepage_blurb_3_url'); ?>"><?php echo get_option_tree('homepage_blurb_3_title'); ?></a></h5>	
					<p><?php echo get_option_tree('homepage_blurb_3_text'); ?></p>
				</div>						
			</div>				
		</div>		
		<!-- /End 1/3 Columns -->
		
	</div>
	<!-- /End 960 Container -->

</div>
<!-- /End Super Container -->
<?php } ?>	


<!-- ============================================== -->


<?php get_footer(); ?>