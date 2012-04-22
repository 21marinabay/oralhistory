<?php
function get_custom_field($key,$echo=false) {
global $post;
if(!isset($post->ID)) return null;
$custom_field = get_post_meta($post->ID,$key,true);
if($echo==false) return $custom_field;
echo $custom_field;
}
?>