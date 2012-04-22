<?php

class SearchWidget extends WP_Widget {
    function SearchWidget() {
        parent::WP_Widget(false, $name = 'Skeleton Search');	
    }

    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
              <?php echo $before_widget; ?>
              <?php get_search_form(); ?>
              <?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);		
        return $instance;
    }

    function form($instance) {				
        $title = esc_attr($instance['title']);        
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
                       
        <?php 
    }

} 

?>