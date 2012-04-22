<?php 

define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
// Our variables

// Our variables  
$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 0;  
$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;  
//echo $data = $page ;

$needle = "uploads" ;


$pos = strpos($page, $needle);
$pos = $pos -1 ;

$newstring =  substr($page,$pos);
$newstring = str_replace("/","\\",$newstring);
$newstring = str_replace("-"," ",$newstring);

$newstringPrepend = "c:\\wamp\\www\\wp-content" ;
$newstring = $newstringPrepend.$newstring ; 
 




//$variable = "http://10.0.102.180/wp-content/uploads/2011/12/1.16_Advise to Students_TCT.srt" ;

//$variable = str_replace(" ","%20",$page);

//$variable ;

//error_log("connecting to file  ".datetime() , 0);

//$lines = file('http://10.0.102.180/wp-content/uploads/2011/12/1.16_Advise to Students_TCT.srt');

//echo $var = file_get_contents($page);


$lines = file($newstring);







$data ="" ;
$ln= 0;

	//$file = fopen($handle, "r") or exit("Unable to open file!");
	//Output a line of the file until the end is reached
	
	foreach ($lines as $line_num => $line) {
			
		$value = substr($line,0,1);
			
		if (is_numeric($value)) {
			$ifminutes = substr($line,3,2);
			if($ifminutes!="00"){
				$ifminutes = $ifminutes*60 ;
			}
			$ifseconds = substr($line,6,2);
			$seektime = $ifminutes+$ifseconds ;

		} else {
			$line = "<a href=\"#\" id=\"seekclick\" seek=".$seektime.">".$line."</a>" ;
			$data = $data.$line ;
		}
	}
	echo $data ;
?>