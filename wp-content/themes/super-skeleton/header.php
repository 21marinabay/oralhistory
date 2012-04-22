<!DOCTYPE html >
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

	<title><?php 		
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;
	
		wp_title( '|', true, 'right' );
	 
		// Add the blog name.
		bloginfo( 'name' );
	
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
	
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'mdnw' ), max( $paged, $page ) );
	
		?></title>
	
	<meta charset="utf-8">	
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.7.min.js?ver=1.7'></script>
	
	<?php $theme_options = get_option('option_tree'); ?>
	<?php wp_head(); ?>	
        <!-- Menu is not working properly in IE so got the uberMenu to the top -->
        <?php uberMenu_easyIntegrate(); ?>
	
	<!-- Mobile Specific Metas
  	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	


	<!-- CSS
  	================================================== -->
  	<!-- Load the basic skeleton and plugin CSS, then the main theme stylesheet -->   
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/base.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/skeleton.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/comments.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/buttons.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/flexslider.css" />
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/superfish.css" />
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/javascripts/chosen/chosen.css" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/javascripts/prettyPhoto/css/prettyPhoto.css" />

	
	<!-- Alternate rLightbox
	<link type="text/css" rel="stylesheet" href="< ?php echo get_template_directory_uri(); ?>/assets/javascripts/rlightbox_css/ui-lightness/jquery-ui-1.8.16.custom.css" />
	<link type="text/css" rel="stylesheet" href="< ?php echo get_template_directory_uri(); ?>/assets/javascripts/rlightbox_css/lightbox.min.css" />
	-->
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/styles.css" />
	
	
    <!-- Load the font stack from the Options Panel -->
    <?php if (get_option_tree('default_fontstack') == 'Serif') { ?>
    <link rel="stylesheet" href="<?php echo WP_THEME_URL; ?>/assets/stylesheets/typography-serif.css" />
    <?php } else { ?>
    <link rel="stylesheet" href="<?php echo WP_THEME_URL; ?>/assets/stylesheets/typography-sans.css" />
    <?php } ?>
    
    <!-- Load the default skin from the Options Panel -->
    <?php $lightdark = '';
     
    if (get_option_tree('default_skin') == 'Dark') { ?>
		<link rel="stylesheet" href="<?php echo WP_THEME_URL; ?>/assets/stylesheets/skin-dark.css" />
		<?php $lightdark = '-footer'; ?>
	<?php } else if (get_option_tree('default_skin') == 'Clean') { ?>
		<link rel="stylesheet" href="<?php echo WP_THEME_URL; ?>/assets/stylesheets/skin-clean.css" />
		
	<?php } else {} ?>

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" />


	<!-- Favicons, StyleLoader, and basic WP stuff
	================================================== -->
	<link rel="shortcut icon"href="<?php echo get_option_tree('favicon'); ?>" type="image/gif" />
	
	<?php get_template_part( 'element', 'styleloader' ); ?>
	
	<?php if ( ! isset( $content_width ) ) 
	    $content_width = 960;
	?>	
	


</head>


<!-- Start the Markup
================================================== -->
<body <?php body_class(); ?> >

<div style="margin: auto; margin-top:40px; padding:0; width:960px;">
		<?php get_template_part( 'element', 'navigation' ); ?>
</div>
	
		</header>




<?php if (get_option_tree('top_hat') == 'No') { } else { ?>	
	<?php get_template_part( 'element', 'tophat' ); ?>
<?php } ?>
	
<!-- Super Container for entire site -->
<div class="super-container full-width" id="section-header">

	<!-- 960 Container -->
	<div class="container">			
		
		<!-- Header -->
		<header>
		<div class="sixteen columns"  style="background: url('http://202.161.41.98/wp-content/uploads/2011/12/oralhistory-back940x100.png') bottom no-repeat;">
			 
			 
			<?php if(get_option_tree('logo_center') == 'Yes') { $logo_center = 'sixteen omega'; ?>
				<style type="text/css">.logospace{text-align: center;}</style>			
			<?php } ?>
			 
			 
			<!-- Branding -->
			<div class="six columns alpha logospace <?php echo $logo_center; ?>">
				<a href="<?php echo home_url(); ?>/" title="<?php echo bloginfo('blog_name'); ?>">
					<h1 id="logo" style="margin-top: 10px; margin-bottom:10px;">
						<?php $logopath = (get_option_tree('logo')) ? get_option_tree('logo') : WP_THEME_URL . "/assets/images/theme/pacifico-logo/logo$lightdark.png"; ?>    		    		
	        			<img id="logotype" src="<?php echo $logopath; ?>" alt="<?php echo bloginfo('blog_name'); ?>" />
	         		</h1>
				</a>
			</div>
			<!-- /End Branding -->
			
			
			<!-- Promo Space -->
			<?php if(get_option_tree('logo_center') == 'Yes') { } else { ?>
			<div class="ten columns omega">
				<?php if (get_option_tree('header_promo')) : ?>
					<a href="<?php echo get_option_tree('header_promo_url'); ?>" class="header-advert">
						<img src="<?php echo get_option_tree('header_promo'); ?>" alt="advert" />
					</a>
				<?php endif; ?>
			</div> 
			<?php } ?>
			<!-- /Promo Space -->
			
			
		</div>

		<!-- /End Header -->	
	
	</div>
	<!-- End 960 Container -->
	
</div>
<!-- End SuperContainer -->


<!-- ============================================== -->