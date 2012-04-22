<!-- Breakout Row -->
<?php if(get_option_tree('homepage_breakout_section') == 'Yes') { ?>
<?php get_template_part( 'element', 'breakoutrow' ); ?>
<?php } ?>


<!-- ============================================== -->


<!-- Super Container | Footer Widget Space (Optional) -->
<?php if (get_option_tree('footer_widgets') == 'Yes') { ?>
<div class="super-container full-width" id="section-footer">

<!-- 960 Container -->
<div class="container">
<!-- footer -->
<footer>
<div class="sixteen columns" id="header">
<br />
<!-- 1/4 -->
<div class="five columns alpha">
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-widget-1') ) ?>
</div>
<!-- /End 1/4 -->
<!-- 2/4 -->
<div class="five columns">
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-widget-2') ) ?>
</div>
<!-- /End 2/4 -->
<!-- 3/4 -->
<div class="three columns">
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-widget-3') ) ?>
</div>
<!-- /End 3/4 -->
<!-- 4/4 -->
<div class="three columns omega">
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-widget-4') ) ?>
</div>
<!-- /End 4/4 -->
<br />
</div>
</footer>
<!-- /End Footer -->

</div>
<!-- /End 960 Container -->
</div>
<!-- /End Super Container -->
<?php } else{} ?>


<!-- ============================================== -->


<!-- Super Container - SubFooter Space -->
<div class="super-container full-width" id="section-sub-footer">

<!-- 960 Container -->
<div class="container">

<div class="sixteen columns">
<?php if (get_option_tree('footer_blurb_left')) : ?><span class="copyright"><?php echo get_option_tree('footer_blurb_left'); ?></span><?php endif; ?>
<?php if (get_option_tree('footer_social') == 'Yes') { ?>
<ul class="social">
<?php if (get_option_tree('social_google')) : ?><li><a href="<?php echo get_option_tree('social_google'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/google_plus_32.png" alt="google" title="Google+" /></a></li><?php endif; ?>
<?php if (get_option_tree('social_twitter')) : ?><li><a href="<?php echo get_option_tree('social_twitter'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/twitter_32.png" alt="twitter" title="Twitter"/></a></li><?php endif; ?>
<?php if (get_option_tree('social_facebook')) : ?><li><a href="<?php echo get_option_tree('social_facebook'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/facebook_32.png" alt="facebook" title="Facebook" /></a></li><?php endif; ?>
<?php if (get_option_tree('social_youtube')) : ?><li><a href="<?php echo get_option_tree('social_youtube'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/youtube_32.png" alt="youtube" title="You Tube" /></a></li><?php endif; ?>
<?php if (get_option_tree('social_vimeo')) : ?><li><a href="<?php echo get_option_tree('social_vimeo'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/vimeo_32.png" alt="vimeo" title="Vimeo" /></a></li><?php endif; ?>
<?php if (get_option_tree('social_linkedin')) : ?><li><a href="<?php echo get_option_tree('social_linkedin'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/linkedin_32.png" alt="linkedin" title="LinkedIn" /></a></li><?php endif; ?>
<li><a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/rss_32.png" alt="RSS" title="RSS" /></a></li>
</ul>
<?php } else{} ?>
<?php if (get_option_tree('footer_blurb_right')) : ?><span class="colophon"><?php echo get_option_tree('footer_blurb_right'); ?></span><?php endif; ?>
</div>

</div>
<!-- /End 960 Container -->
</div>
<!-- /End Super Container -->

<?php wp_footer(); ?>

<?php if(get_option_tree('chosen') == 'Yes') : ?>
<script type="text/javascript"> jQuery(".chzn-select").chosen(); </script>
<?php else : ?>
<style type="text/css">
select.chzn-select {
height: 30px;
padding: 6px;
width: 100%; }
</style>
<?php endif; ?>


<!-- Default Portfolio View - Grabs the variable set in the template-xxx.php files -->
<?php
if(isset($GLOBALS['portfolio_view']) && $GLOBALS[ 'portfolio_view' ] == 'Hybrid') : ?>

<script type="text/javascript">
(function($) {
// Hybrid View Defaults
jQuery("#portfolio-list .module-container").removeClass("sixteen columns").addClass("four columns");
jQuery("#portfolio-list .module-img").removeClass("twelve columns alpha");
jQuery("#portfolio-list .module-meta").fadeIn(300).removeClass("omega").addClass("four columns alpha visible");
jQuery(".list_btn").css("opacity","1");
jQuery(".hybrid_btn").css("opacity","0.5");
jQuery(".grid_btn").css("opacity","1");
})(jQuery);
</script>

<?php elseif(isset($GLOBALS['portfolio_view']) && $GLOBALS[ 'portfolio_view' ] == 'List') : ?>

<script type="text/javascript">
(function($) {
// List View Defaults
jQuery("#portfolio-list .module-container").removeClass("four columns").addClass("sixteen columns");
jQuery("#portfolio-list .module-img").addClass("twelve columns alpha");
jQuery("#portfolio-list .module-meta").fadeIn(300).removeClass("alpha").addClass("four columns omega visible");
jQuery(".list_btn").css("opacity","0.5");
jQuery(".hybrid_btn").css("opacity","1");
jQuery(".grid_btn").css("opacity","1");
})(jQuery);
</script>
<?php else : ?>
<script type="text/javascript">
(function($) {
// Grid View Defaults
// $("#portfolio-list .module-container").removeClass("sixteen columns").addClass("four columns");
// $("#portfolio-list .module-img").removeClass("twelve columns alpha");
// $("#portfolio-list .module-meta").fadeOut(100).removeClass("four columns alpha omega visible");
jQuery(".list_btn").css("opacity","1");
jQuery(".hybrid_btn").css("opacity","1");
jQuery(".grid_btn").css("opacity","0.5");
})(jQuery);
</script>
<?php endif; ?>
<!-- End Default Portfolio View -->
<!-- OPENTRACKER HTML START -->
<script src="http://script.opentracker.net/?site=oralhistory.smu.edu.sg"></script> <noscript><a href="http://www.opentracker.net" target="_blank"> <img src="http://img.opentracker.net/?cmd=nojs&site=oralhistory.smu.edu.sg" alt="Web Analytics" border="0"> </a></noscript>
<!-- OPENTRACKER HTML END -->

</body>
</html>