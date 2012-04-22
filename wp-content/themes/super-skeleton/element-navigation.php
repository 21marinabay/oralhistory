		<!-- Menu -->
		<div class="sixteen columns" id="menu" > 
			
			<?php if(get_option_tree('header_social') == 'No') { $header_social = 'sixteen omega'; } ?>
			
			<div class="twelve columns alpha navigation <?php echo $header_social; ?>" >
				
				<!-- DEFAULT NAVIGATION -->
				<?php wp_nav_menu( array(
					 'container' =>false,
					 'theme_location' => 'topbar',
					 'menu_class' => 'sf-menu light',
					 'echo' => true,
					 'before' => '',
					 'after' => '',
					 'link_before' => '',
					 'link_after' => '',
					 'depth' => 0,
					 'walker' => new description_walker())
				 ); ?>
				 <!-- /DEFAULT NAVIGATION -->
				 
				 
				 <!-- RESPONSIVE NAVIGATION FLIP -->
				<form id="responsive-nav" action="" method="post">
				<select class="chzn-select">
				<option value="">Navigation</option>
				<?php 
				
				$menu = wp_nav_menu(array('theme_location' => 'topbar_small', 'echo' => false));
				   if (preg_match_all('#(<a [^<]+</a>)#',$menu,$matches)) {
				      $hrefpat = '/(href *= *([\"\']?)([^\"\' ]+)\2)/';
				      foreach ($matches[0] as $link) {
				         // Do something with the link
				         if (preg_match($hrefpat,$link,$hrefs)) {
				            $href = $hrefs[3];
				         }
				         if (preg_match('#>([^<]+)<#',$link,$names)) {
				            $name = $names[1];
				         }
				         echo "<option value=\"$href\">$name</option>";
				      }
				   }				
				
				?>
				</select>
				</form>
				<!-- /END RESPONSIVE NAV -->
							 
			</div>	
			
			<?php if (get_option_tree('header_social') == 'No') { } else { ?>	
			<div class="four columns omega" id="tagline">
				<!-- <p>This is the site tagline</p> -->
				<ul class="social">
					<?php if (get_option_tree('social_google')) : ?><li><a href="<?php echo get_option_tree('social_google'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/google_plus_32.png" alt="google" title="Google+" /></a></li><?php endif; ?>
					<?php if (get_option_tree('social_twitter')) : ?><li><a href="<?php echo get_option_tree('social_twitter'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/twitter_32.png" alt="twitter" title="Twitter"/></a></li><?php endif; ?>
					<?php if (get_option_tree('social_facebook')) : ?><li><a href="<?php echo get_option_tree('social_facebook'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/facebook_32.png" alt="facebook" title="Facebook" /></a></li><?php endif; ?>
					<?php if (get_option_tree('social_youtube')) : ?><li><a href="<?php echo get_option_tree('social_youtube'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/youtube_32.png" alt="youtube" title="You Tube" /></a></li><?php endif; ?>
					<?php if (get_option_tree('social_vimeo')) : ?><li><a href="<?php echo get_option_tree('social_vimeo'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/vimeo_32.png" alt="vimeo" title="Vimeo" /></a></li><?php endif; ?>
					<?php if (get_option_tree('social_linkedin')) : ?><li><a href="<?php echo get_option_tree('social_linkedin'); ?>"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/linkedin_32.png" alt="linkedin" title="LinkedIn" /></a></li><?php endif; ?>
					<li><a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><img src="<?php echo WP_THEME_URL; ?>/assets/images/theme/social-icons/rss_32.png" alt="RSS" title="RSS" /></a></li>
				</ul>
			</div>	
			<?php } ?>
			
<!--			<hr class="remove-top"/> -->
		</div>		 
		<!-- /End Menu -->