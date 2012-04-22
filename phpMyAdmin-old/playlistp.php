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

$directory = "c:\\wamp\\www\\wp-content\\uploads\\2011\\12" ;
//$files = read_folder_directory ($directory);
if ($files)
{
				echo "<table border='1'>";
     foreach ($files as $file)
     {
           
		   $ext = end(explode('.', $file));
		   if($ext=="mp3" || $ext=="mp4")
		   {
			
				$topicname = explode("_",$file);		  
				$topic = str_replace("-"," ",$topicname[1]);
				$authorname =  substr($topicname[3],0,-4);
				//echo "<h2>".$topicname."<br/></h2>" ;
				echo "<tr>";
				echo "<td>";
				echo $file ;
				echo "</td><td>";
				echo "<td>";
				echo $topicname[0] ;
				echo "</td><td>"; 
				echo $topic ;
				echo "</td><td>";
				echo "</td><td>"; 
				echo $topicname[2] ;
				echo "</td><td>";
				echo "</td><td>"; 
				echo $authorname ;
				echo "</td></tr>"; 
				
				
				//$sql = "INSERT INTO `history`.`playlisttopic` (`ID`,`File`,`IndexValue`,`Topic`,`SubTopic`,`Author`) VALUES (`NULL`,'".$file.",".$topicname[0].",".$topic.",".$topicname[2].",".$authorname."');";
				
				$sql = "INSERT INTO `history`.`playlisttopic` (`ID`, `File`, `IndexValue`, `Topic`, `SubTopic`, `Author`) VALUES (NULL,'".$file."','".$topicname[0]."','".$topic."','".$topicname[2]."','".$authorname."');";
				
				
				$result = mysql_query($sql);
				if (!$result) {
				 $message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $sql;
					die($message);
				 }
				
		   }
     }
	 echo "</table>";
}

 
 // Get Data by Group
 

// Get all the data from by topic 
$result1 = mysql_query("SELECT * FROM playlisttopic group by IndexValue ")
or die(mysql_error());  

$srno = "1" ;
echo "<table border='1'>";
echo "<tr> <th>No</th> <th>IndexValue</th><th>Topic</th> </tr>";
// keeps getting the next row until there are no more to get
while($row = mysql_fetch_array( $result1 )) {
	// Print out the contents of each row into a table
	echo "<tr><td>"; 
	echo $srno ;
	echo "</td><td>"; 
	echo $row['IndexValue'];
	echo "</td><td>"; 
	echo $row['Topic'];
	echo "</td></tr>"; 
	
	//echo $sqlTopic = "INSERT INTO `history`.`wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, \'1\', \'2011-12-30 09:50:42\', \'0000-00-00 00:00:00\', \'\', \'".$row['Topic']."\', \'\', \'draft\', \'closed\', \'closed\', \'\', \'\', \'\', \'\', \'2011-12-30 09:50:42\', \'0000-00-00 00:00:00\', \'\', \'0\', \'http://10.0.102.180/?p=2235\', \'0\', \'jw_playlist\', \'\', \'0\');";
	echo $sqlTopic = "INSERT INTO `history`.`wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '1', '2011-12-30 09:50:42', '0000-00-00 00:00:00', '', '".$row['Topic']."', '', 'draft', 'closed', 'closed', '', '', '', '', '2011-12-30 09:50:42', '0000-00-00 00:00:00', '', '0', 'http://10.0.102.180/?p=2235', '0', 'jw_playlist', '', '0');";
	echo "</br>";
	$result2 = mysql_query($sqlTopic);
				if (!$result2) {
				 $message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $sqlTopic;
					die($message);
				 }
	
	
	$srno= $srno+1 ;
} 

echo "</table>";

////////////////////////////////BY AUTHOR ////////////////

// Get all the data from by topic 
$result1 = mysql_query("SELECT * FROM playlisttopic group by Author ")
or die(mysql_error());  

$srno = "1" ;
echo "<table border='1'>";
echo "<tr> <th>No</th> <th>Author</th><th>Topic</th> </tr>";
// keeps getting the next row until there are no more to get
while($row = mysql_fetch_array( $result1 )) {
	// Print out the contents of each row into a table
	echo "<tr><td>"; 
	echo $srno ;
	echo "</td><td>"; 
	echo $row['Author'];
	echo "</td><td>"; 
	echo $row['Topic'];
	echo "</td></tr>"; 
	
	echo $sqlAuthor = "INSERT INTO `history`.`wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '1', '2011-12-30 09:50:42', '0000-00-00 00:00:00', '', '".$row['Author']."', '', 'draft', 'closed', 'closed', '', '', '', '', '2011-12-30 09:50:42', '0000-00-00 00:00:00', '', '0', 'http://10.0.102.180/?p=2235', '0', 'jw_playlist', '', '0');";
	echo "</br>" ;
	$result3 = mysql_query($sqlAuthor);
				if (!$result3) {
				 $message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $sqlAuthor;
					die($message);
				 }
	
	$srno= $srno+1 ;
} 

echo "</table>";


///////////////////////////////BY AUTHOR ////////////////







?>




<?php



mysql_close($con);
?>


<?php

function read_folder_directory($dir)
    {
        $listDir = array();
        if($handler = opendir($dir)) {
				while (($sub = readdir($handler)) !== FALSE) { 
                  
                        $listDir[] = $sub;
                    
                }
            }
            closedir($handler);
        
        return $listDir;
    }
?>