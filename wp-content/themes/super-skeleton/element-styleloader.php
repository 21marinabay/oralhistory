<?php ?>
<!-- THIS FILE LOADS ALL THE CUSTOMIZATIONS FROM THE THEME OPTIONS PANEL  -->


<!-- Custom CSS Modifications from the Admin Panel -->
<style type="text/css">

/* Insert the rest of the custom CSS from the admin panel */ 
<?php echo get_option_tree('customcss'); ?> 
	 
	 
	/* Add a custom bg if it exists */
	<?php $homepage_bg = get_option_tree("default_bg");
			
	if(get_custom_field('custom_background_image')) { ?>
		body, body:after {background: url('<?php echo get_custom_field('custom_background_image',true); ?>') top left fixed repeat;}
		h2.title span, ul.tabs li a.active {background: none;}
	<?php } elseif (isset($homepage_bg[0])) { ?>
	 		body, body:after {background: url('<?php echo get_option_tree("default_bg"); ?>') top left fixed repeat;}
	 		h2.title span, ul.tabs li a.active {background: none;}
	<?php } else {} ?>
	 
	<?php global $theme_options; ?>
	 
	 
	/* This is your link hover color */
	<?php $link_hover_color = get_option_tree("link_hover_color"); if (isset($link_hover_color[0])) { ?>		
		#section-header li a:hover, a:hover {color: <?php echo get_option_tree('link_hover_color');?>;}
	<?php } else {} ?>	
	
	/* This is your link color */
	<?php $link_color = get_option_tree("link_color"); if (isset($link_color[0])) { ?>		
		#section-header li a, a {color: <?php echo get_option_tree('link_color'); ?>;}
	<?php } else {} ?>
	
	/* This is your visited link color */
	<?php $link_visited_color = get_option_tree("link_visited_color"); if (isset($link_visited_color[0])) { ?>
		a:visited {color: <?php echo get_option_tree('link_visited_color'); ?>;}
	<?php } else {} ?>		
	
	
</style>


<!-- ALTERNATIVE HEADLINE FONT OVERRIDE - For TypeKit/GoogleFonts Insertion -->	
<?php $altfont = get_option_tree("alt_fontreplace"); if (isset($altfont[0])) { 	
	echo get_option_tree("alt_fontreplace");
	
	} else { ?>

		<!-- FONT REPLACEMENT LOADER 1. Checks for opt-outs, then Cufon, then Google and loads the appropriate files. -->
		<?php 
		$rawfont = get_option_tree('fontreplace');
		$googlefont = str_replace("+", " ", $rawfont);
		$cufonfont = str_replace("-", "", $rawfont);
		
		if(get_option_tree('fontreplace') == 'No-Font-Replacement') { } 
			 
		else { ?> 
			
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $googlefont; ?>" />
			<style type="text/css">
			h1, h2, h3, h4, h5, h6, #section-header strong, #section-header span {
			  font-family: '<?php echo $googlefont; ?>', sans-serif;
			   -webkit-transform: rotate(-0.01deg);
			   -moz-transform: rotate(-0.01deg);
			   text-rendering: optimizeLegibility;
			   -webkit-font-smoothing: antialiased;
			}
			</style>
		
		<?php } ?>
	
<?php } ?>
<!-- // END HEADLINE FONT OVERRIDE -->	



<!-- Hide the top bar / optional -->
<?php if (get_option_tree('top_hat') == 'No') { ?>	
	<style type="text/css">
	#section-tophat{display: none; height: 0px !important; margin: 0; padding: 0;}
	html{margin-top: -40px;}
	</style>
<?php } ?> 



<!-- Check for Column Flipping -->
<?php if(get_custom_field('column_flip') == 'Yes') : ?>
	<style type="text/css">
	.main-content-area .eleven.columns{float: right !important;}
	</style>
<?php endif; ?>

<!-- Check for Force-Hiding of the Breakout Row -->
<?php if(get_custom_field('breakout_hide') == 'Yes') : ?>

<style type="text/css">
	#breakout-row{display: none;}
</style>

<?php endif; ?>