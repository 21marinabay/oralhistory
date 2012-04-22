<?php
function ninjapages_child_pages( $content ) {

	global $post; // required

	$options = get_option('ninja_pages_options');
	if( isset( $options['display_children'] ) ) {
		$content = $content;
	} else {
		$content = '';
	}
	if( !is_archive() ) {
		
		$args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$children = new WP_Query( $args );

		//print_r($args);
		if ( $children ) {

			$content .= '<div id="ninja-children-wrap">';

			while( $children->have_posts()) : $children->the_post();

				$content .= '<div class="ninja-child-wrap">';
				if( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail() ) {
					$content .= '<div class="ninja-child-thumbnail">';
					$content .= get_the_post_thumbnail( $post->ID, 'thumbnail' );
					$content .= '</div>';
				}
				$content .= '<h3 class="ninja-child-title">';
					$content .= '<a href="' . get_permalink() . '">';
					$content .= get_the_title();
					$content .= '</a>';
				$content .= '</h3>';
				$content .= '<div class="ninja-child-entry">';
					$content .= get_the_excerpt();
				$content .= '</div>';
				$content .= '</div>';

			endwhile;

			$content .= '</div>';

		}

	}

	return $content;

}
add_shortcode('ninja_child_pages', 'ninjapages_child_pages');