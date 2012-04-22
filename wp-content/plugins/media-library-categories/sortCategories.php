<div class='wrap'>
<?php
global $wpdb;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$optionsName = 'mc_options';
$options = get_option($optionsName);
$insert_sort = '';
 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
 if ($_POST && isset($_POST["act"])&& isset($_POST["sortinput"])&& $_POST["act"]=="sort" && $_POST["sortinput"] && $_POST["sortinput"]!='') {
		//print $_POST[sortinput];
		$cats = explode("|", $_POST["sortinput"]);
		$i=1;
		foreach ($cats as $cat)
		{
			$atts = explode(",", $cat);
			if(count($atts)==3)
			{
				$term_id= $atts[0];
				$parent= $atts[1];
				$term_order= $atts[2];
				if($parent=="root"){$parent=0;}
				if($term_id>0)
				{
					//term_taxonomy update parent			
					$updatequery = "UPDATE $wpdb->term_taxonomy set parent=$parent where term_id=$term_id;";
					$updateresult = $wpdb->query($updatequery);	
					//print "<br>".$updatequery;
					
					
					//terms update term_order
					$updatequery = "UPDATE $wpdb->terms set term_order=$term_order where term_id=$term_id;";
					$updateresult = $wpdb->query($updatequery);	
					//print "<br><br>".$updatequery;
				}
				
			}
			
		}
		print "<div class='highlight'>Sort Saved Successfully $view_link</div> "; 
		
		
} 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

print "
<link media='all' type='text/css' href=\"/wp-admin/css/colors-fresh.css\" id=\"colors-css\" rel=\"stylesheet\">";

print "
<script type='text/javascript' src='$rl_base/js/jquery-1.5.2.min.js'></script>
<script type='text/javascript' src='$rl_base/js/jquery-ui-1.8.11.custom.min.js'></script>
<script type='text/javascript' src='$rl_base/js/jquery.ui.nestedSortable.js'></script>

<script>

	$(document).ready(function(){

		$('ol.sortable').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			maxLevels: 0,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		});

	});
	
	function submitForm()
	{
		var list = $('ol.sortable').nestedSortable('toString');
		$('#sortinput').val(list);
		document.forms[\"itemsForm\"].submit();
	}
</script>
<style type=\"text/css\">
	ol {
		margin: 0;
		padding: 0;
		padding-left: 30px;
	}

	ol.sortable, ol.sortable ol {
		margin: 0 0 0 25px;
		padding: 0;
		list-style-type: none;
	}

	ol.sortable {
		margin: 4em 0;
	}

	.sortable li {
		margin: 7px 0 0 0;
		padding: 0;
	}

	.sortable li div  {
		border: 1px solid black;
		padding: 3px;
		margin: 0;
		cursor: move;
	}

</style>
";

print "
<form>
	<table cellpadding='0px' cellspacing='0px' width='100%'>
		<tr>
			<td>
				<h2>Sort Media Categories <a class='button add-new-h2' href='admin.php?page=$rl_dir/view.php'>View Media Categories</a></h2>
			</td>
			
		</tr>
		<tr>
			<td align='right'>
				
			</td>
		</tr>
	</table>
</form>";



print "<form name='itemsForm' method='post'>";
		
	
print "<ol class='sortable'>";
print $mc_var->get_category_hierarchical_sortlist();
print "</ol>";

print "<br>
<input type='hidden' id='sortinput' name='sortinput' value='' />

<br />
<input type='submit' value='Save Sort' class='button' onclick='submitForm();return false;'>
<input name='act' type='hidden' value='sort'><br>";

print "</form>";

	
?>
</div>