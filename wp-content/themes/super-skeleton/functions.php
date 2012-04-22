<?php

/**
* Initiate Theme Options
*
* @uses wp_deregister_script()
* @uses wp_register_script()
* @uses wp_enqueue_script()
* @uses register_nav_menus()
* @uses add_theme_support()
* @uses is_admin()
*
* @access public
* @since 1.0.0
*
* @return void

* Ok, Now that's out of the way, let's rock and roll!

*/


/* Define our theme URL constant */
if(!defined('WP_THEME_URL')) {
	define( 'WP_THEME_URL', get_template_directory_uri());
}


/* Check for theme updates! */
require('update-notifier.php');

/* Instantiate the TGM Plugin Activation Class! */
require_once( get_template_directory() . '/functions/tgm-plugin-activation/class-tgm-plugin-activation.php' );
add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );

/* Pre-load our OptionTree Plugin + any others we need */
function my_theme_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		/** This is an example of how to include a plugin pre-packaged with a theme */
		array(
			'name' => 'ScrollToTop',
			'slug' => 'dynamic-to-top',
			'required' => false
		),
		
		/** This is an example of how to include a plugin from the WordPress Plugin Repository */
		array(
			'name' => 'OptionTree',
			'slug' => 'option-tree',
			'required' => true
		)
	);

	/** Change this to your theme text domain, used for internationalising strings */
	// $theme_text_domain = 'skeleton';

	/**
	 * Array of configuration settings. Uncomment and amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * uncomment the strings and domain.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		/*'domain'       => $theme_text_domain,         // Text domain - likely want to be the same as your theme. */
		/*'default_path' => '',                         // Default absolute path to pre-packaged plugins */
		/*'menu'         => 'install-required-plugins', // Menu slug */
		/*'notices'      => true,                       // Show admin notices or not */
		'strings'      => array(
			/*'page_title'             				=> __( 'Install Required Plugins', $theme_text_domain ), // */
			/*'menu_title'             				=> __( 'Install Plugins', $theme_text_domain ), // */
			/*'instructions_install'   				=> __( 'The %1$s plugin is required for this theme. Click on the big blue button below to install and activate %1$s.', $theme_text_domain ), // %1$s = plugin name */
			/*'instructions_install_recommended'	=> __( 'The %1$s plugin is recommended for this theme. Click on the big blue button below to install and activate %1$s.', $theme_text_domain ), // %1$s = plugin name, %2$s = plugins page URL */
			/*'instructions_activate'  				=> __( 'The %1$s plugin is installed but currently inactive. Please go to the <a href="%2$s">plugin administration page</a> page to activate it.', $theme_text_domain ), // %1$s = plugin name, %2$s = plugins page URL */
			'button'                 				=> __( 'Install %s Now', $theme_text_domain ), // %1$s = plugin name */
			/*'installing'             				=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name */
			/*'oops'                   				=> __( 'Something went wrong with the plugin API.', $theme_text_domain ), // */
			/*'notice_can_install_required'     	=> __( 'This theme requires the following plugins: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			/*'notice_can_install_recommended'		=> __( 'This theme recommends the following plugins: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			/*'notice_cannot_install'  				=> __( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', $theme_text_domain ), // %1$s = plugin name */
			/*'notice_can_activate_required'    	=> __( 'The following required plugins are currently inactive: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			/*'notice_can_activate_recommended'		=> __( 'The following recommended plugins are currently inactive: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			/*'notice_cannot_activate' 				=> __( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', $theme_text_domain ), // %1$s = plugin name */
			/*'return'                 				=> __( 'Return to Required Plugins Installer', $theme_text_domain ), // */
			/*'plugin_activated' 	   				=> __( 'Plugin activated successfully.', $theme_text_domain ) // */
		)
	);

	tgmpa( $plugins, $config );

}



/* Load Theme Specific Widgets, Shortcodes, the ability to pull custom fields on the frontend, and our custom meta-boxes for the theme */
include('functions/widgets/search_widget.php');
include('functions/widgets/social_widget.php');
include('functions/shortcodes.php'); 
include('functions/custom-field.php');
include_once 'functions/meta-box/meta-box-3.2.2.class.php';
include 'functions/meta-box/meta-box-usage.php';


/* Activate Our Theme Widgets */
add_action('widgets_init', create_function('', 'return register_widget("SearchWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("SocialWidget");'));


/* Our Master Function: Does 2 Main things
*  If inside the admin panel, load up our custom style
*  If outside the admin panel, register/enqueue our theme scripts, nav areas, and widget areas.
*/
function init_mdnw() {

	/* Check for OptionTree and load custom styles. Alert users to download the plugin if it's not found. */
	if ( is_admin() ){		
		if (defined('OT_VERSION')) {
		
			function mdnw_option_tree_styles(){
			include('option-tree/option_tree_custom.php');
		}
		
		add_action('option_tree_admin_header', 'mdnw_option_tree_styles');
			
		}
		
		else {
			
			function mdnw_option_tree_fail(){
			echo "<div class=\"update-nag\">Almost There! You need to install the OptionTree plugin to use this theme. Add it from your \"Plugins > Add New\" panel (search Option Tree), or <a href=\"http://wordpress.org/extend/plugins/option-tree/\">download it here</a>!</div>";
		    }
		add_action('admin_notices', 'mdnw_option_tree_fail');
		}		
	}


	/* init_mdnw cont... ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */
	
	



	
	
	
	/* Register all scripts, Nav Areas, and Widget Areas */
	if(!is_admin()){
	
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'http://code.jquery.com/jquery-1.7.min.js', false, '1.7', true);
    	wp_enqueue_script( 'jquery' );
		
				
		wp_register_script( 'Tabs', WP_THEME_URL . '/assets/javascripts/tabs.js', false, null, true);
    	wp_enqueue_script( 'Tabs' );
		
		wp_register_script( 'FlexSlider', WP_THEME_URL . '/assets/javascripts/jquery.flexslider-min.js', false, null, true);
    	wp_enqueue_script( 'FlexSlider' );
		
		wp_register_script( 'Filterable', WP_THEME_URL . '/assets/javascripts/filterable.pack.js', false, null, true);
    	wp_enqueue_script( 'Filterable' );
		
    	wp_register_script( 'prettyPhoto', WP_THEME_URL . '/assets/javascripts/jquery.prettyPhoto.js', false, null, true);
    	wp_enqueue_script( 'prettyPhoto' ); 
		
		//wp_register_script( 'jQueryCore', WP_THEME_URL . '/assets/javascripts/jquery.ui.core.min.js', false, null, true);
    	//wp_enqueue_script( 'jQueryCore' ); 
		
		//wp_register_script( 'jQueryWidget', WP_THEME_URL . '/assets/javascripts/jquery.ui.widget.min.js', false, null, true);
    	//wp_enqueue_script( 'jQueryWidget' ); 
		
    	//wp_register_script( 'rLightbox', WP_THEME_URL . '/assets/javascripts/jquery.ui.rlightbox.min.js', false, null, true);
    	//wp_enqueue_script( 'rLightbox' ); 
    	    	    	
    	wp_register_script( 'HoverIntent', WP_THEME_URL . '/assets/javascripts/jquery.hoverIntent.js', false, null, true);
    	wp_enqueue_script( 'HoverIntent' ); 
    	    	
    	wp_register_script( 'Superfish', WP_THEME_URL . '/assets/javascripts/superfish.js', false, null, true);
    	wp_enqueue_script( 'Superfish' );
	
		wp_register_script( 'SuperSubs', WP_THEME_URL . '/assets/javascripts/supersubs.js', false, null, true);
    	wp_enqueue_script( 'SuperSubs' );
    	
		wp_enqueue_script( 'chosen', WP_THEME_URL . '/assets/javascripts/chosen/chosen.jquery.js', array('jquery'), '0.9', true );    

		wp_register_script( 'Tipsy', WP_THEME_URL . '/assets/javascripts/jquery.tipsy.js', false, null, true);
		wp_enqueue_script( 'Tipsy' );
		
		
		//global $user_ID; if( $user_ID ) : 
		//if( current_user_can('level_10') ) : 
		//	wp_deregister_script( 'Tipsy' );
		//endif; 
		//endif; 
		
		wp_register_script( 'SkeletonKey', WP_THEME_URL . '/assets/javascripts/skeleton-key.js', false, null, true);
    	wp_enqueue_script( 'SkeletonKey' ); 
    	
    	wp_register_script( 'UberMenu', WP_PLUGIN_URL . '/wp-uber-menu/js/wpmegamenu.min.js',array('jquery'), '3.3.1', true);
    	wp_enqueue_script( 'UberMenu' ); 
		
    }


	/* init_mdnw cont... ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */

	
    /* Register Navigation */
    register_nav_menus( array(
		'topbar' => __( 'Top Bar Menu', 'Top Bar Menu' ),
		'topbar_small' => __( 'Top Bar Menu - Responsive Mode', 'Top Bar Menu - Responsive Mode' ),
	) );


	/* init_mdnw cont... ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */

	
	/* Register Sidebar (Right side, next to posts/pages) */
	register_sidebar( array(
		'name' => __( 'Default Post/Page Sidebar' ),
		'id' => 'default-widget-area',
		'description' => __( 'Default widget area for posts/pages. ' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '<hr class="partial-bottom" /></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	/* Register Footer Widgets */
	register_sidebar( array(
		'name' => __( 'Footer Column 1' ),
		'id' => 'footer-widget-1',
		'description' => __( 'The first column in the footer widget area.' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h5 class="footer-widget-title">',
		'after_title' => '</h5>',
	) );	
	
	/* Register Footer Widgets */
	register_sidebar( array(
		'name' => __( 'Footer Column 2' ),
		'id' => 'footer-widget-2',
		'description' => __( 'The second column in the footer widget area.' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h5 class="footer-widget-title">',
		'after_title' => '</h5>',
	) );
	
	/* Register Footer Widgets */
	register_sidebar( array(
		'name' => __( 'Footer Column 3' ),
		'id' => 'footer-widget-3',
		'description' => __( 'The third column in the footer widget area.' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h5 class="footer-widget-title">',
		'after_title' => '</h5>',
	) );
	
	/* Register Footer Widgets */
	register_sidebar( array(
		'name' => __( 'Footer Column 4' ),
		'id' => 'footer-widget-4',
		'description' => __( 'The fourth column in the footer widget area.' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h5 class="footer-widget-title">',
		'after_title' => '</h5>',
	) );
		
		
}    











add_action('init', 'init_mdnw'); /* Run the above function at the init() hook */
/* end init_mdnw ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */


/* ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */


/* Required WP Theme Support */
add_theme_support( 'automatic-feed-links' );

/* Add "Post Thumbnails" Support */
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 480, 300, true );
add_image_size( 'single-post-thumbnail', 480, 9999 );

/* Add filter to the gallery so that we can apply the prettyPhoto tag */
add_filter( 'wp_get_attachment_link', 'sant_prettyadd');
 
function sant_prettyadd ($content) {
	$content = preg_replace("/<a/","<a data-rel=\"prettyPhoto[slides]\"",$content,1);
	return $content;
}


/* ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */


/* Custom Excerpt Length and Prevent <P> Stripping */
/* Use with the default 'THE_EXCERPT()' */
function improved_trim_excerpt($text) { // Fakes an excerpt if needed
  global $post;
  if ( '' == $text ) {
    $text = get_the_content('');
    $text = apply_filters('the_content', $text);
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
	$text = preg_replace('@<style[^>]*?>.*?</style>@si', '', $text);
	$text = preg_replace('@<p class="wp-caption-text"[^>]*?>.*?</p>@si', '', $text);
    $text = str_replace('\]\]\>', ']]&gt;', $text);
    $text = strip_tags($text, '<p>');
    $excerpt_length = 140;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words)> $excerpt_length) {
      array_pop($words);
      array_push($words, '...');
      $text = implode(' ', $words);
    }
  }
return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt'); 

/* Optional Alternative Excerpt - Usage: */
/* 
 * excerpt(40); // 40 chars 
 * Note that this is just "EXCERPT", not the default "THE_EXCERPT"
 * 
*/ 
 
function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }

    function content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      } 
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }


/* ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */


/* Add comment-reply support */
function theme_queue_js(){
  if (!is_admin()){
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
      wp_enqueue_script( 'comment-reply' );
  }
}
add_action('wp_print_scripts', 'theme_queue_js');

/* Disable Page Comments */
function noPgComments($open,$post_id) {
  if (get_post_type($post_id) == 'page') {
    $open = false;
  }
  return $open;
}
add_filter( 'comments_open', 'noPgComments', 10, 2 );


/* ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- */


/* Custom Navigation Menu 
 * 
 * Allows for us to use the Description field as the sub-text to the navigation.
 * 
/* Credit to Christian Kriesi for the initial example of this walker class */
class description_walker extends Walker_Nav_Menu
{
      function start_el(&$output, $item, $depth, $args)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $prepend = '<strong>';
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
           		$description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $description.$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}




function smu_js() {
    // Load jQuery
    if ( !is_admin() ) {
	//	wp_deregister_script( 'jquery' );
	//  wp_register_script( 'jquery', 'http://code.jquery.com/jquery-1.7.min.js', false, '1.7', true);
    //	wp_enqueue_script( 'jquery' );
		
		
		
		wp_register_script('smu', get_bloginfo('template_url') . '/smu.js', array('jquery') );  
        wp_enqueue_script('smu');  
		 }
   
}
//add_action( 'wp_enqueue_scripts', 'smu_js' );







?>