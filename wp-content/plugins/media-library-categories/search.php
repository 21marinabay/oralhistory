
<?php

$pos=0;
$end_num=0;
if ($start<0 || $start=="" || !isset($start) || empty($start)) {$start=0;}
if ($num_per_page<0 || $num_per_page=="") {$num_per_page=10;}
$prev=$start-$num_per_page;
$next=$start+$num_per_page;
if (ereg("&start=$start",$_SERVER["QUERY_STRING"])) {
	$prev_page=str_replace("&start=$start","&start=$prev",$_SERVER["QUERY_STRING"]);
	$next_page=str_replace("&start=$start","&start=$next",$_SERVER["QUERY_STRING"]); //echo($next_page);
}
else {
	$prev_page=$_SERVER["QUERY_STRING"]."&start=$prev";
	$next_page=$_SERVER["QUERY_STRING"]."&start=$next";
}
if ($countCategories>$num_per_page) {
	//print "  | ";

	if ((($start/$num_per_page)+1)-5<1) {
		$beginning_link=1;
	}
	else {
		$beginning_link=(($start/$num_per_page)+1)-5;
	}
	if ((($start/$num_per_page)+1)+5>(($countCategories/$num_per_page)+1)) {
		$end_link=(($countCategories/$num_per_page)+1);
	}
	else {
		$end_link=(($start/$num_per_page)+1)+5;
	}
	$pos=($beginning_link-1)*$num_per_page;
		
}
$end_num=$start+$num_per_page;
if($end_num>$countCategories)
{
	$end_num=$countCategories;
}
$cleared=isset($_GET["q"])?ereg_replace("q=".$_GET["q"], "", $_SERVER["REQUEST_URI"]):"";
$extra_text=isset($_GET["q"])? " for your search of <strong>\"".$_GET["q"]."\"</strong>&nbsp;|&nbsp;<a href='$cleared'>Clear&nbsp;Results</a>" : "" ;
?>







<?php if ($countCategories>$num_per_page) : ?>


<div class="tablenav">

<div class="tablenav-pages">

<span class="displaying-num">Displaying <?php echo ($start+1); ?>&ndash;<?php echo $end_num; ?> of <?php echo $countCategories; ?></span>

<?php 
if (($start-$num_per_page)>=0) { ?>
<a href="admin.php?<?php echo $prev_page ?>" rel='nofollow' class="prev page-numbers">&#171;</a>
<?php } ?>

<?php 
	for ($k=$beginning_link; $k<$end_link; $k++) {
		if (ereg("&start=$start",$_SERVER["QUERY_STRING"])) {
			$curr_page=str_replace("&start=$start","&start=$pos",$_SERVER["QUERY_STRING"]);
		}
		else {
			$curr_page=$_SERVER["QUERY_STRING"]."&start=$pos";
		}
		if (($start-($k-1)*$num_per_page)<0 || ($start-($k-1)*$num_per_page)>=$num_per_page) {
			print "<a class='page-numbers' href=\"admin.php?$curr_page\" rel='nofollow'>";
			print $k;
		}else
		{
			print "<span class=\"page-numbers current\">$k</span>";
		}
		
		if (($start-($k-1)*$num_per_page)<0 || ($start-($k-1)*$num_per_page)>=$num_per_page) {
			print "</a>";
		}
		$pos=$pos+$num_per_page;
		print "&nbsp;&nbsp;";
	}
?>

<?php 
if (($start+$num_per_page)<$countCategories) { ?>
<a href="admin.php?<?php echo $next_page ?>" rel='nofollow' class="next page-numbers">&#187;</a><br>
<?php } ?>

</div>

</div>


<?php endif ?>













