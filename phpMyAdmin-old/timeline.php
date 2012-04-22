<? header('Content-type: text/xml'); ?>
<?php
    require("../lib/config.php");
    require("../lib/functions.php");
    require("../lib/tag_functions.php");
    require("../user/database.php");
    require("../user/user.php");
    require("../lib/forms.php");
?>

<?php


  $pbpathrev  = strrev($PATH_INFO);
  $pbslashpos = strpos($pbpathrev, '-');
  $pbpathcut  = substr($pbpathrev, 0, $pbslashpos);
  $pbdotpos   = strpos($pbpathcut, '.');
  $pbrevimgid = substr($pbpathcut, ($pbdotpos +1));
  $currentset = strrev($pbrevimgid);



$rssWriter = new PhotoRSSWriter;
$rssWriter->display($currentset);

class PhotoRSSWriter{

  function display($currentset){
    echo $this->getRss($currentset);
  }

  function getRss($currentset){
    $rss  = $this->getRssHeader($currentset);
    $rss .= $this->getRssItems($currentset);
    $rss .= $this->getRssFooter();
    return $rss;
  }

  function getRssHeader($currentset){
    $rssHeader  = '<rss version="2.0"
             xmlns:media="http://search.yahoo.com/mrss/"
             xmlns:atom="http://www.w3.org/2005/Atom">
            <channel>
    <title>My Website '.$currentset.'</title>
    <link>http://www.mywebsite.com/</link>
    <description>My Website is a great collection of my things.</description>
    <language>en-us</language>
    <lastBuildDate>' . date("D, d M Y h:i:s") . ' EST</lastBuildDate>
    <atom:link href="http://mywebsite.com/photos.rss" rel="self" type="application/rss+xml" />
';

        if($currentset > 0)
        {
          $rssHeader .='<atom:link rel="previous" href="http://www.mywebsite.com/rss/photos-'.($currentset-1).'.rss" />
';
        }

        $rssHeader .='<atom:link rel="next" href="http://www.mywebsite.com/rss/photos-'.($currentset+1).'.rss" />
';



    return $rssHeader;
  }

  function getRssFooter(){
    $rssFooter .= '  </channel>
</rss>';
    return $rssFooter;
  }

  function getRssItems($currentset){
    //Set up Database connection
    mysql_connect ('localhost:3306','weblog','weblog');
    mysql_select_db ('weblog_new');

    $query = "select c.id from content c inner join content_images ci on c.id = ci.content_id";

    $exec = mysql_query($query);
    $browsekey  = mysql_num_rows($exec);

        $startindex =0;
        $endindex=99;

        if($currentset > 0)
        {
          $startindex = ($currentset*100)+$startindex;
          $endindex = ($currentset*100)+$endindex;
        }


    $rssItems = "";

        // Get the Array of rss items
        $rssItemArray = getRssItems($startindex, $endindex);
        
    foreach ($rssItems as $result) {
      $id = $result['item_id'];
      $title = $this->getBareText($result["text"], 50);
      $text = $this->getBareText($result["text"]);
      $name = $result["author"];
      $img_id = $result["img_id"];
      $timestamp = $result["blog_created"];

      $rssItems .= "    <item>
      <title>" .$title . "</title>
      <link>http://www.mywebsite.com/browse/show-" .$id. ".html</link>
      <media:thumbnail url=\"http://www.mywebsite.com/img/image/".$img_id.".jpg\"/>
      <media:content url=\"http://www.mywebsite.com/img/orig/" .$img_id . ".jpg\"/>
      <guid isPermaLink=\"false\">".$img_id."</guid>
    </item>\r\n";

    }

    return $rssItems;

  }

  function getRssItems($startindex, $endindex) {
        // goes out and gets an array of data, one row per rss item. 
  }

  function getBareText($text, $crop = false){
    $text = $crop? crop_string(remove_ubb($text), $crop) : $text;
    $text = htmlspecialchars($text, ENT_QUOTES);
    $text = trim($text);
    return $text;
  }
}
?>