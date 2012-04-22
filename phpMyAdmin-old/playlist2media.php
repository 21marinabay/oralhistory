<?php

define('WP_USE_THEMES', false);
require_once('../wp-load.php');	

//Create MYSQL connection and Keep it ready

$con = mysql_connect("localhost","root","media123");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("history", $con);

// Get all the data from by topic 
$result = mysql_query("SELECT *
FROM `playlisttopic`
GROUP BY Topic")
or die(mysql_error());  


$srno = "1" ;

// keeps getting the next row until there are no more to get
while($row = mysql_fetch_array( $result )) {
	// Print out the contents of each row into a table
	echo "<table border='1'>";
	echo "<tr> <th>No</th> <th>IndexValue</th><th>WP POSTID</th><th>Topic</th> </tr>";
	echo "<tr><td>"; 
	echo $srno ;
	echo "</td><td>"; 
	echo $postid = $row['IndexValue'];
	echo "</td><td>"; 
	 
    $sql2 = "SELECT * FROM `wp_posts` WHERE `post_title` ='".$row['Topic']."' and ID > 2260";
	// Get all the data from by topic 
	$result2 = mysql_query($sql2) or die(mysql_error());  
	$row2= mysql_fetch_array( $result2 );
	
	
	echo $playlistId =  $row2['ID'];
	echo "</td><td>"; 
	echo $row['Topic'];
	echo "</td></tr>"; 
	echo "</table>";
	
	$postid = $postid."%" ;
	
	echo $sql3 = "SELECT * FROM `wp_posts` WHERE `post_title` like '".$postid."' and post_type='attachment' ";
	// Get all the data from by topic 
	$result3 = mysql_query($sql3) or die(mysql_error()); 
    $mediano = "1"	;
	$values = "" ;
	echo "<table border='2' >";
	echo "<tr> <th>No</th> <th>IndexValue</th><th>WP POSTID</th><th>Topic</th> </tr>";
	while($row3 = mysql_fetch_array( $result3 )) {
	// Print out the contents of each row into a table
	
	echo "<tr><td>"; 
	echo $mediano ;
	echo "</td><td>"; 
	echo $value = $row3['ID'];
	echo "</td><td>"; 
	 
   
	
	echo $row3['post_title'];
	echo "</td><td>"; 
	echo $row['post_type'];
	echo "</td></tr>"; 
	
	$mediano = $mediano+1 ;
	$values = $values.$value.",";
	}
	echo "</table>";
	//echo $values ;
	echo $playlistId."</br>";
	echo $values = substr($values,0,-1);
	
	//echo $sql3 = "INSERT INTO `history`.`wp_postmeta` (`meta_id` ,`post_id` ,`meta_key` ,`meta_value`)VALUES ('NULL', '".$playlistId."', 'jwplayermodule_playlist_items', '".$values."')" ;
	echo "</br>" ;
	$result3 = mysql_query($sql3);
				if (!$result3) {
				 $message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $sqlAuthor;
					die($message);
				 }
	
	$srno = $srno+1;	
	}


?>