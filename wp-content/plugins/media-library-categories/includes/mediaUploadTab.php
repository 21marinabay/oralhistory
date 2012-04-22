<?php


function addMediaUploadTab($tabs) {
	$newtab = array('mlc' => __('Media Categories', 'mlc'));
	return array_merge($tabs, $newtab);
}
add_filter('media_upload_tabs', 'addMediaUploadTab');


function MediaUploadTabClicked() {
global $wpdb;

	if ( isset($_POST['insertshortcode']) ) {
		$mlc =0;
		if ( isset($_POST['selectedCategory']) ){
			$mlc = (int) $_POST['selectedCategory'];
		}
		$html="[mediacategories categories=\"$mlc\"]";
		
		return media_send_to_editor($html);
	}
	else if ( isset($_POST['savesort']) ) {
		if ( isset($_POST['selectedCategory']) && isset($_POST['newsortorder']) && isset($_POST['taxonomyID'])){
			$mlc = (int) $_POST['selectedCategory'];
			$newsortorder =  $_POST['newsortorder'];
			$taxonomyID =  $_POST['taxonomyID'];
			if($newsortorder!="")
			{
				$ids = explode(",", $newsortorder);
				$i=1;
				foreach ($ids as $id)
				{
					//update term_taxonomy with sort
					$updatequery = "UPDATE $wpdb->term_relationships set term_order=$i where object_id=$id AND term_taxonomy_id=$taxonomyID;";
					$updateresult = $wpdb->query($updatequery);
					$i+=1;
				}
			}
		}
	}
	else if ( isset($_POST['insertselected']) ) {
		$selected = $_POST['selected'];
		$html="";
		if( is_array($selected)) {	
			foreach ( array_keys($selected) as $attachmentID ) {
				$image = $_POST['image'][$attachmentID];
				
				$alttext = stripslashes( htmlspecialchars ($image['alttext'], ENT_QUOTES));
				$description = stripslashes (htmlspecialchars($image['description'], ENT_QUOTES));
				
				$clean_description = preg_replace("/\n|\r\n|\r$/", " ", $description);
				$class=$image['align'];
				
				if(contains($image['mimetype'],"image"))
				{
					$imgurl = wp_get_attachment_image_src($attachmentID, $image['image-size']);
					$imgfullsizeurl = wp_get_attachment_image_src($attachmentID, "full");
					$img = "<img src='$imgurl[0]' alt='$alttext' class='$class' />";
					$html .= "<a href='$imgfullsizeurl[0]' title='$clean_description' target='_blank'>$img</a><br />";
				}
				else
				{
					$fileurl=$image['url'];
					$title=$image['title'];
					$html .= "<a href='$fileurl' title='$clean_description' target='_blank'>$title</a><br />";
				}
			}
		}
		return media_send_to_editor($html);
	}
	else if ( isset($_POST['send']) ) {
		$keys = array_keys($_POST['send']);
		$send_id = (int) array_shift($keys);
		$image = $_POST['image'][$send_id];
		$alttext = stripslashes( htmlspecialchars ($image['alttext'], ENT_QUOTES));
		$description = stripslashes (htmlspecialchars($image['description'], ENT_QUOTES));
		
		$clean_description = preg_replace("/\n|\r\n|\r$/", " ", $description);
		$class=$image['align'];
		
		if(contains($image['mimetype'],"image"))
		{
			$imgurl = wp_get_attachment_image_src($send_id, $image['image-size']);
			$imgfullsizeurl = wp_get_attachment_image_src($send_id, "full");
			$html = "<img src='$imgurl[0]' alt='$alttext' class='$class' />";
			$html = "<a href='$imgfullsizeurl[0]' title='$clean_description' target='_blank'>$html</a>";
		}
		else
		{
			$fileurl=$image['url'];
			$title=$image['title'];
			$html = "<a href='$fileurl' title='$clean_description' target='_blank'>$title</a>";
		}
		
		
		// Return it to TinyMCE
		return media_send_to_editor($html);
	}






    return wp_iframe( 'media_tab_render');
}
add_action('media_upload_mlc', 'MediaUploadTabClicked');

	
	
function media_tab_render() {
	
	global $wpdb, $wp_query, $wp_locale, $type, $tab, $post_mime_types, $mc_var;
	
	media_upload_header();
	
	$post_id 	= intval($_REQUEST['post_id']);
	$mlc 	= 0;
	$attachments = array();
	$categories=array();
	$currentsortorder="";
	$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=mlc&post_id=$post_id", 'admin');

	
		
	// Get number of images in gallery	
	if ( isset($_REQUEST['selectedCategory']) ){
		$mlc = (int) $_REQUEST['selectedCategory'];
		$taxonomyID=$wpdb->get_var(("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy where term_id=$mlc"));
		
		$categories=$mc_var->get_category_hierarchical_list($mlc);
		
		$query = 	"SELECT p.*, a.term_order FROM " . $wpdb->prefix . "posts p
        inner join " . $wpdb->prefix . "term_relationships a on a.object_id = p.ID
        inner join " . $wpdb->prefix . "term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id
        inner join " . $wpdb->prefix . "terms tt on ttt.term_id = tt.term_id
        where ttt.taxonomy='".mc::$taxonomy."' && tt.term_id=$mlc order by a.term_order asc";
		$attachments = $wpdb->get_results($query, 'ARRAY_A');
			
		
	}
	
	// WP-Core code for Post-thumbnail
	$calling_post_id = 0;
	if ( isset( $_GET['post_id'] ) )
		$calling_post_id = $_GET['post_id'];
?>
	
<form id="filter" action="" method="get">
<input type="hidden" name="type" value="<?php echo esc_attr( $GLOBALS['type'] ); ?>" />
<input type="hidden" name="tab" value="<?php echo esc_attr( $GLOBALS['tab'] ); ?>" />
<input type="hidden" name="post_id" value="<?php echo (int) $post_id; ?>" />
<div class="tablenav">
	
	
	<div class="alignleft actions">
		<select id="selectedCategory" name="selectedCategory" style="width:250px;">
			<option value="0" <?php selected('0', $mlc); ?> ><?php esc_attr( _e('-- Select --',"mlc") ); ?></option>
			<?php
				echo $mc_var->get_category_hierarchical_selectoptions($mlc);
			?>
		</select>
		<input type="submit" id="show-Category" value="<?php esc_attr( _e('Select &#187;','mlc') ); ?>" class="button-secondary" />
	</div>
	<br style="clear:both;" />
</div>
</form>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#media-items").sortable({
		   stop: function(event, ui) {
				$("#newsortorder").val("");
				var mediaItems = $("#media-items div.mlcsortable:not('.ui-sortable-placeholder')");
				mediaItems.each(function(index) {
					var id = $(this).attr("attachmentid");
					//alert(index + ': ' + id);
					var currentsortorder= $("#newsortorder").val();
					$("#newsortorder").val(currentsortorder+(currentsortorder!=""?",":"")+id);
				});
		   }
		});
	});
</script>
<style type="text/css">
    .filename {
    left: 47px;
    position: absolute;
    width: 480px;
}
</style>
<form id="library-form" method="post" enctype="multipart/form-data" action="<?php echo esc_attr($form_action_url); ?>"  class="media-upload-form">

	<?php wp_nonce_field('mlc-media-form'); ?>

	<script type="text/javascript">
	<!--
	jQuery(function($){
		var preloaded = $(".media-item.preloaded");
		if ( preloaded.length > 0 ) {
			preloaded.each(function(){prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');});
			updateMediaForm();
		}
	});
	-->
	</script>
	
	<div id="media-items">
	<?php
	
	
	if( is_array($attachments)) {	
		foreach ( $attachments as $attachment ) {
			
			$currentsortorder.=($currentsortorder!=""?",":"").$attachment["ID"];		
		
			$mime = strtolower($attachment['post_mime_type']);
			
			if(contains($mime, "image"))
			{ //IMAGE
				$thumb="";
				$fileurl="";
				
				$thumb = wp_get_attachment_thumb_url( $attachment["ID"] );
				$fileurl = $attachment["guid"];
				
				?>
				<div id='media-item-<?php echo $attachment["ID"] ?>' attachmentid="<?php echo $attachment["ID"] ?>" class='media-item preloaded mlcsortable'>
				  <input name="image[<?php echo $attachment["ID"] ?>][mimetype]" id="image-mimetype-<?php echo $attachment["ID"] ?>"  value="<?php echo $mime ?>" type="hidden" />
				 <div class='filename'></div>
				  <a class='toggle describe-toggle-on' href='#'><?php esc_attr( _e('Show', "mlc") ); ?></a>
				  <a class='toggle describe-toggle-off' href='#'><?php esc_attr( _e('Hide', "mlc") );?></a>
				  <div class='filename new'><input class="mlccheckbox" type="checkbox" name="selected[<?php echo $attachment["ID"] ?>]" id="mlc_<?php echo $attachment["ID"] ?>" style="margin:5px;" /><?php echo stripslashes( wp_html_excerpt($attachment["post_title"],60) ); ?></div>
				  <table class='slidetoggle describe startclosed'><tbody>
					  <tr>
						<td rowspan='4' style="padding:0px;" align="center"><img class='thumbnail' alt='<?php echo esc_attr( $attachment["post_excerpt"] ); ?>' src='<?php echo esc_attr( $thumb ); ?>'/></td>
						<td><?php esc_attr( _e('Media ID:', "mlc") ); ?><?php echo $attachment["ID"] ?></td>
					  </tr>
					  <tr><td><?php echo esc_attr( $fileurl ); ?></td></tr>
					  <tr><td><?php echo esc_attr( stripslashes($attachment["post_excerpt"]) ); ?></td></tr>
					  <tr><td>&nbsp;</td></tr>
					  <tr>
						  <th valign="top" class="label" scope="row"><label for="image[<?php echo $attachment["ID"] ?>][alttext]"><span class="alignleft">Alternate Text</span><br class="clear"></label></th>
						  <td class="field"><input id="image[<?php echo $attachment["ID"] ?>][alttext]" name="image[<?php echo $attachment["ID"] ?>][alttext]" value="<?php esc_attr_e( stripslashes($attachment["post_excerpt"]) ); ?>" type="text"/></td>
					  </tr>	
					  <tr class="post_content">
							<th valign="top" class="label" scope="row">
							<label for="image[<?php echo $attachment["ID"] ?>][description]"><span class="alignleft">Description</span><br class="clear"></label>
							</th>
							<td class="field"><textarea name="image[<?php echo $attachment["ID"] ?>][description]" id="image[<?php echo $attachment["ID"] ?>][description]"><?php esc_attr_e( stripslashes($attachment["post_content"]) ); ?></textarea></td>
					  </tr>
						<tr class="align">
							<th valign="top" class="label" scope="row"><label for="image[<?php echo $attachment["ID"] ?>][align]"><span class="alignleft">Alignment</span><br class="clear"></label></th>
							<td class="field">
								<input name="image[<?php echo $attachment["ID"] ?>][align]" id="image-align-none-<?php echo $attachment["ID"] ?>" checked="checked" value="none" type="radio" />
								<label for="image-align-none-<?php echo $attachment["ID"] ?>" class="align image-align-none-label"><?php esc_attr_e("None") ;?></label>
								<input name="image[<?php echo $attachment["ID"] ?>][align]" id="image-align-left-<?php echo $attachment["ID"] ?>" value="left" type="radio" />
								<label for="image-align-left-<?php echo $attachment["ID"] ?>" class="align image-align-left-label"><?php esc_attr_e("Left") ;?></label>
								<input name="image[<?php echo $attachment["ID"] ?>][align]" id="image-align-center-<?php echo $attachment["ID"] ?>" value="center" type="radio" />
								<label for="image-align-center-<?php echo $attachment["ID"] ?>" class="align image-align-center-label"><?php esc_attr_e("Center") ;?></label>
								<input name="image[<?php echo $attachment["ID"] ?>][align]" id="image-align-right-<?php echo $attachment["ID"] ?>" value="right" type="radio" />
								<label for="image-align-right-<?php echo $attachment["ID"] ?>" class="align image-align-right-label"><?php esc_attr_e("Right") ;?></label>
							</td>
						</tr>
						<tr class="image-size">
							<th valign="top" class="label" scope="row"><label for="image[<?php echo $attachment["ID"] ?>][image-size]">
								<span class="alignleft">Size</span><br class="clear"></label>
							</th>
							<td class="field">
								<div class="image-size-item">
									<input type="radio" checked="checked" value="thumbnail" id="image-size-thumbnail-<?php echo $attachment["ID"] ?>" name="image[<?php echo $attachment["ID"] ?>][image-size]" >
									<label for="image-size-thumbnail-<?php echo $attachment["ID"] ?>">Thumbnail</label>
								</div>
								<div class="image-size-item">
									<input type="radio" value="medium" id="image-size-medium-<?php echo $attachment["ID"] ?>" name="image[<?php echo $attachment["ID"] ?>][image-size]" >
									<label for="image-size-medium-<?php echo $attachment["ID"] ?>">Medium</label>
								</div>
								<div class="image-size-item">
									<input type="radio" value="large" id="image-size-large-<?php echo $attachment["ID"] ?>" name="image[<?php echo $attachment["ID"] ?>][image-size]" >
									<label for="image-size-large-<?php echo $attachment["ID"] ?>">Large</label>
								</div>
								<div class="image-size-item">
									<input type="radio" value="full" id="image-size-full-<?php echo $attachment["ID"] ?>" name="image[<?php echo $attachment["ID"] ?>][image-size]">
									<label for="image-size-full-<?php echo $attachment["ID"] ?>">Full Size</label> 
								</div>
							</td>
						</tr>
					    <tr class="submit">
							<td>
								<input type="hidden"  name="image[<?php echo $attachment["ID"] ?>][thumb]" value="<?php echo $thumb ?>" />
								<input type="hidden"  name="image[<?php echo $attachment["ID"] ?>][url]" value="<?php echo $fileurl ?>" />
							</td>
							<td class="savesend">
								<?php
								if ( $calling_post_id && current_theme_supports( 'post-thumbnails', get_post_type( $calling_post_id ) ) )//onclick='NGGSetAsThumbnail(\"$attachment["ID"]\");
									echo "<a class='mlc-post-thumbnail' id='mlc-post-thumbnail-" . $attachment["ID"] . "' href='#' return false;'>" . esc_html__( 'Use as featured image' ) . "</a>";
								?>
								<button type="submit" class="button" value="1" name="send[<?php echo $attachment["ID"] ?>]"><?php esc_html_e( 'Insert into Post' ); ?></button>
							</td>
					   </tr>
				  </tbody></table>
				</div>
			<?php
			}
			else
			{
				$thumb="/wp-includes/images/crystal/document.png";	
					if(contains($mime, "audio"))
					{
						$thumb="/wp-includes/images/crystal/audio.png";	
					}
					else if(contains($mime, "video"))
					{
						$thumb="/wp-includes/images/crystal/video.png";	
					}
				$fileurl = $attachment["guid"];
				
				?>
				<div id='media-item-<?php echo $attachment["ID"] ?>' attachmentid="<?php echo $attachment["ID"] ?>" class='media-item preloaded mlcsortable' >
				  <input name="image[<?php echo $attachment["ID"] ?>][mimetype]" id="image-mimetype-<?php echo $attachment["ID"] ?>"  value="<?php echo $mime ?>" type="hidden" />
				  <div class='filename'></div>
				  <a class='toggle describe-toggle-on' href='#'><?php esc_attr( _e('Show', "mlc") ); ?></a>
				  <a class='toggle describe-toggle-off' href='#'><?php esc_attr( _e('Hide', "mlc") );?></a>
				  <div class='filename new'><input class="mlccheckbox" type="checkbox" name="selected[<?php echo $attachment["ID"] ?>]" id="mlc_<?php echo $attachment["ID"] ?>" style="margin:5px;" /><?php echo stripslashes( wp_html_excerpt($attachment["post_title"],60) ); ?></div>
				  <table class='slidetoggle describe startclosed'><tbody>
					  <tr>
						<td rowspan='4' style="padding:0px;" align="center"><img class='thumbnail' alt='<?php echo esc_attr( $attachment["post_excerpt"] ); ?>' src='<?php echo esc_attr( $thumb ); ?>'/></td>
						<td><?php esc_attr( _e('Media ID:', "mlc") ); ?><?php echo $attachment["ID"] ?></td>
					  </tr>
					  <tr><td><?php echo $attachment["post_title"]; ?></td></tr>
					  <tr><td><?php echo esc_attr( $fileurl ); ?></td></tr>
					  <tr><td><?php echo esc_attr( stripslashes($attachment["post_excerpt"]) ); ?></td></tr>
					  <tr><td>&nbsp;</td></tr>
					  <tr>
						  <th valign="top" class="label" scope="row"><label for="image[<?php echo $attachment["ID"] ?>][alttext]"><span class="alignleft">Alternate Text</span><br class="clear"></label></th>
						  <td class="field"><input id="image[<?php echo $attachment["ID"] ?>][alttext]" name="image[<?php echo $attachment["ID"] ?>][alttext]" value="<?php esc_attr_e( stripslashes($attachment["post_excerpt"]) ); ?>" type="text"/></td>
					  </tr>	
					  <tr class="post_content">
							<th valign="top" class="label" scope="row">
							<label for="image[<?php echo $attachment["ID"] ?>][description]"><span class="alignleft">Description</span><br class="clear"></label>
							</th>
							<td class="field"><textarea name="image[<?php echo $attachment["ID"] ?>][description]" id="image[<?php echo $attachment["ID"] ?>][description]"><?php esc_attr_e( stripslashes($attachment["post_content"]) ); ?></textarea></td>
					  </tr>
						
					   <tr class="submit">
							<td>
								<input type="hidden"  name="image[<?php echo $attachment["ID"] ?>][title]" value="<?php echo $attachment["post_title"] ?>" />
								<input type="hidden"  name="image[<?php echo $attachment["ID"] ?>][thumb]" value="<?php echo $thumb ?>" />
								<input type="hidden"  name="image[<?php echo $attachment["ID"] ?>][url]" value="<?php echo $fileurl ?>" />
							</td>
							<td class="savesend">
								<?php
								if ( $calling_post_id && current_theme_supports( 'post-thumbnails', get_post_type( $calling_post_id ) ) )//onclick='NGGSetAsThumbnail(\"$attachment["ID"]\");
									echo "<a class='mlc-post-thumbnail' id='mlc-post-thumbnail-" . $attachment["ID"] . "' href='#' return false;'>" . esc_html__( 'Use as featured image' ) . "</a>";
								?>
								<button type="submit" class="button" value="1" name="send[<?php echo $attachment["ID"] ?>]"><?php esc_html_e( 'Insert into Post' ); ?></button>
							</td>
					   </tr>
				  </tbody></table>
				</div>
			<?php
			}
		}
	}
	?>
	</div>
	<p class="ml-submit">
		<input type="submit" class="button savebutton" name="insertselected" value="<?php esc_attr( _e('Insert Selected','mlc') ); ?>" />
		<input type="submit" class="button savebutton" name="savesort" value="<?php esc_attr( _e('Save Sort Order','mlc') ); ?>" />
		<input type="submit" class="button savebutton" name="insertshortcode" value="<?php esc_attr( _e('Insert Category Shortcode','mlc') ); ?>" />
	</p>
	<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
	<input type="hidden" name="selectedCategory" id="selectedCategory" value="<?php echo (int) $mlc; ?>" />
	<input type="hidden" name="newsortorder" id="newsortorder" value="<?php echo $currentsortorder; ?>" />
	<input type="hidden" name="taxonomyID" id="taxonomyID" value="<?php echo $taxonomyID; ?>" />
</form>
<?php
	
}



	
	

?>