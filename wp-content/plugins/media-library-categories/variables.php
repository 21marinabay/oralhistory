<?php

global $rl_dir, $rl_base, $rl_markets;
$rl_dir=dirname(plugin_basename(__FILE__)); //plugin absolute server directory name
$rl_base=get_option('siteurl')."/wp-content/plugins/".$rl_dir; //URL to plugin directory
$rl_path=ABSPATH."wp-content/plugins/".$rl_dir; //absolute server pather to plugin directory

$view_link="| <a href='".get_option('siteurl')."/wp-admin/admin.php?page=$rl_dir/view.php'>View Media Categories</a>";



?>