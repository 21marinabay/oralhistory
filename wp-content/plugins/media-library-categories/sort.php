<div class='wrap'>
<?php
global $wpdb;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			


$taxonomy = get_taxonomy(mc::$taxonomy);
$optionsName = 'mc_options';
$options = get_option($optionsName);
$insert_sort = '';


$termid=0;

if (isset($_GET['termid'])&& is_numeric($_GET['termid'])){ $termid=cleanQuery($_GET['termid']);}

if($termid==0)
{
	$status = 505;
	wp_redirect( $location, $status );
	exit;
}

$taxonomyid=$wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy where term_id=$termid"));

$where = '';
if($termid)
{
	$where .= " && tt.term_id=".$termid;
}

//---- This is in place for version before 1.0.6 -------------------
$currentSort='';
if(isset($options['mc_media_category_'.$termid.'_sort']))
{
	if($options['mc_media_category_'.$termid.'_sort'] !=null ||$options['mc_media_category_'.$termid.'_sort'] =='')
	{
		$currentSort=$options['mc_media_category_'.$termid.'_sort'];
	}
}
//---- END -------------------------------------------------------
 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
 if ($_POST && isset($_POST["act"]) && $_POST["act"]=="sort" && isset($_POST["sortinput"]) && $_POST["sortinput"] && $_POST["sortinput"]!='') {
	 	
		if (!current_user_can($taxonomy->cap->assign_terms)) {
			wp_die( __( 'Cheatin&#8217; uh?' ) );
 		}	
		
		$ids = explode(",", $_POST["sortinput"]);
		$i=1;
		foreach ($ids as $id)
		{
			//update term_taxonomy with sort
			$updatequery = "UPDATE $wpdb->term_relationships set term_order=$i where object_id=$id AND term_taxonomy_id=$taxonomyid;";
			$updateresult = $wpdb->query($updatequery);	
			$i+=1;
		}
		print "<div class='highlight'>Sort Saved Successfully $view_link</div> "; 
		
} 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

print "
<link media='all' type='text/css' href=\"/wp-admin/css/colors-fresh.css\" id=\"colors-css\" rel=\"stylesheet\">
<form>
	<table cellpadding='0px' cellspacing='0px' width='100%'>
		<tr>
			<td>
				<h2>Sort Media Category Files <a class='button add-new-h2' href='admin.php?page=$rl_dir/view.php'>View Media Categories</a></h2>
			</td>
			
		</tr>
		<tr>
			<td align='right'>
				
			</td>
		</tr>
	</table>
</form>";



print "<form name='itemsForm' method='post'>";
		//---- This is in place for version before 1.0.6 -------------------
		if($currentSort!='')
		{
			$ids = explode(",", $currentSort);
			$i=1;
			foreach ($ids as $id)
			{
				//update term_taxonomy with sort
				$updatequery = "UPDATE $wpdb->term_relationships set term_order=$i where object_id=$id AND term_taxonomy_id=$taxonomyid;";
				$updateresult =$wpdb->query($updatequery);	
			
				$i+=1;
			}
			//remove old sort
			$options['mc_media_category_'.$termid.'_sort']='';
			update_option($optionsName, $options);
		}
		//---- END -------------------------------------------------------
		
		
		$query = 	"SELECT p.*, a.term_order FROM $wpdb->posts p
					inner join $wpdb->term_relationships a on a.object_id = p.ID
					inner join $wpdb->term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id
					inner join $wpdb->terms tt on ttt.term_id = tt.term_id
					where ttt.taxonomy='".mc::$taxonomy."' $where order by a.term_order asc;";
 
		$results = $wpdb->get_results($query); 
		
		
		 
print "
			

<br>
<input type='hidden' id='sortinput' name='sortinput' value='' />
<table id='table-1' class='widefat' cellspacing=0>
    <thead>
        <tr class='nodrop nodrag'>
			<th>File ID</th>
			<th>Name</th>
			<th>File</th>
        </tr>
    </thead>";
	
	
	
	
	if ($results){ 
		$num_rows = count($results);
		if($num_rows>0)
		{
			$i=1;
			$bgcol="";
			foreach ( $results as $row ) 
 { 
					
				$label = $row->post_title;
				$id = $row->ID;
				$fileUrl = $row->guid;
				$mime = $row->post_mime_type;
				
				$thumbnailSize = 'thumbnail';
				$thumb = wp_get_attachment_image_src( $id, 'thumbnail' ); 
				
				$currentOrderBy = $row->term_order;
				
				if($mime=='image/jpeg')
				{
					print "
					<tr id='$id' style='background-color:$bgcol'>
						<td>$id</td>
						<td>$label</td>
						<td><img src='".$thumb[0]."' /></td>
					</tr>";
				}
				else
				{
					print "
					<tr id='$id' style='background-color:$bgcol'>
						<td>$id</td>
						<td>".$fileUrl."</td>
					</tr>";
				}
				
				$i+=1;
				
				
			}
		}
		}
		
	
	
	
	
	

    
    
print " <tfoot>
        <tr class='nodrop nodrag'>
			<th>File ID</th>
			<th>Name</th>
			<th>File</th>
        </tr>
    </tfoot>
</table>
<br />
<input type='submit' value='Save Sort' class='button'>
<input name='act' type='hidden' value='sort'><br>";

print "</form>";

	
?>
</div>