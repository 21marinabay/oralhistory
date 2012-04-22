<!-- THE POST QUERY -->
<!-- This one's special because it'll look for our category filter and apply some magic -->

			<?php 
			
			if(get_post_custom_values('post_count')) :  
				echo "Amol".$post_array = get_post_custom_values('post_count');
				$post_count = join(',', $post_array);
			else : 
				$post_count = -1;
			endif;
			
		
			if(get_post_custom_values('category_filter')) :     // If the category filter exists on this page...
				$cats = get_post_custom_values('category_filter'); 	// Returns an array of cat-slugs from the custom field.
			
		//	print("Amol-Cats ".$cats);
			
				foreach ( $cats as $cat ) {			
				
					
					$catid = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug='$cat'");		// Grab the cat-id from the DB using the category slug ($cat).
					$acats[] = $catid; 								// Turn the list of ID's into an ARRAY, $acats[]
				}			
				
				$cat_string = join(',', $acats);					// Join the ARRAY into a comma-separated STRING for use in query_posts
				//echo $cat_string;								    // Test
			
			endif;												// End the situation... carry on as usual if there was no category filter.
			?>
		
			
			<?php

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$heading = "menu_order";
			$type = "asc";
			$args=array(
				'cat'=>$cat_string,			   // Query for the cat ID's (because you can't use multiple names or slugs... crazy WP!)
				'posts_per_page'=>$post_count, // Set a posts per page limit
				'paged'=>$paged,				// Basic pagination stuff.
				'orderby'=>$heading,
				'order'=>$type,
				
			   );
			query_posts($args);
			//print_r(array_values(query_posts($args)));
			
		//	<?php //$posts = query_posts( $query_string . '&orderby=title&order=asc' ); 
			
			


			?>
			