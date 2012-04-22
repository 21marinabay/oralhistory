<?php

// [mediacategories foo="foo-value"]
function mediacategories_func($atts) {
global $wpdb;

	//extract with shortcodes
	extract(shortcode_atts(array(
      'categories' => array(),
      'limit' => 0,
      'ul_class' => '',
	  'ul_id' => '',
	  'thumnail_size' => 'thumbnail', //thumbnail, medium, large or full
	  'include_link' => true,
	  'target' => '_blank',
	  'rel' => ''
    ), $atts));

	$optionsName = 'mc_options';
	$options = get_option($optionsName);

	$content = "";
	$where = '';
	if($categories)	{
		$currentCats = explode(",", $categories);
		foreach ($currentCats as $termID) {
      $where = " && tt.term_id=".$termID;
      $content .= "<ul id='".(($ul_id!='')?"".$ul_id."_$termID":$termID)."'".(($ul_class!='')?" class='".$ul_class."'":"").">";

      $query = 	"SELECT p.*, a.term_order FROM " . $wpdb->prefix . "posts p
        inner join " . $wpdb->prefix . "term_relationships a on a.object_id = p.ID
        inner join " . $wpdb->prefix . "term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id
        inner join " . $wpdb->prefix . "terms tt on ttt.term_id = tt.term_id
        where ttt.taxonomy='".mc::$taxonomy."' $where order by a.term_order asc";
        if($limit>0) 
		{
			$query.="LIMIT ".$limit;
		}
      $results = $wpdb->get_results($query, 'ARRAY_A');
      $num_rows = $wpdb->num_rows;

      if ($results) {
        if($num_rows > 0)	{
          $i = 1;
          foreach($results as $row) {
            $label = $row['post_title'];
            $id = $row['ID'];
            $fileUrl = $row['guid'];
            $caption = $row['post_excerpt'];
            $mime = $row['post_mime_type'];

            $thumb = wp_get_attachment_image_src( $id, $thumnail_size );
            if($mime=='image/jpeg'
            || $mime=='image/jpg'
            || $mime=='image/gif'
            || $mime=='image/png'
            || $mime=='image/bmp'
            || $mime=='image/tiff') {
              $content .= "<li>".(($include_link=="true")?"<a href='".$fileUrl."' target='$target' rel='$rel'>":"")."<img src='".$thumb[0]."' alt='".$caption."' />".(($include_link=="true")?"</a>":"")."</li>";
            } else {
              $content .= "<li>".(($include_link=="true")?"<a href='".$fileUrl."' target='$target' rel='$rel'>":"").$label.(($include_link=="true")?"</a>":"")."</li>";
            }
          }
        }
      } else {
        //echo "Error!".$wpdb->print_error().$query;
      } 							  

      $content .=  "</ul>";
		}
	}

return $content;
}

add_shortcode('mediacategories', 'mediacategories_func');
?>