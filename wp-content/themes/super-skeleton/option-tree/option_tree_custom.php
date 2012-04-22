<?php ?>

<style type="text/css">
/* My Customizations */

/* Hide the Option Tree Menu */
/* li.toplevel_page_option_tree{display: none;} */

#framework_wrap {
  position: relative !important;
  width: 788px !important;
  margin: 15px auto !important; 
}

#framework_wrap #content .option {
    padding-bottom: 60px !important;
}

#framework_wrap #header {
  -webkit-border-top-left-radius: 8px;
  -webkit-border-top-right-radius: 8px;
  -moz-border-radius-topleft: 8px;
  -moz-border-radius-topright: 8px;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
  background: #6d6d6d url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/header.png) repeat-x left top !important;
  border: 1px solid #555;
  height: 71px;
  width: 785px;
  position: relative;
}

#framework_wrap #header h1 {
	width: 130px;
    height: 31px;
    background: url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/logo_dark.png) no-repeat 0 0 !important;
    text-indent: -9999px;
    margin: 20px 0 0 20px;
}

#framework_wrap #header div.version{left: 155px !important;}

#hyperpanel-wrapper {
background:url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/bg_pattern.png) scroll repeat top center;
color:#666;
font:normal 12px/18px Segoe UI, Arial, Helvetica, sans-serif;
width:100%;
top: 10px;
padding-bottom:50px;
position:relative;
-khtml-border-radius: 20px 0 20px 20px;
-webkit-border-radius: 20px 0 20px 20px;
border-radius: 20px 0 20px 20px;
-moz-border-radius: 20px 0 20px 20px;
}

#hyperpanel-header {
-khtml-border-radius: 20px 0 0 0;
-webkit-border-radius: 20px 0 0 0;
border-radius: 20px 0 0 0;
-moz-border-radius: 20px 0 0 0;

background:url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/header-bg.gif) repeat-x top left;
height:70px;
width:100%;
overflow: hidden;
}

#hyperpanel-logo {
float:left;
position:relative;
}

#hyperpanel-logo span a#home-link {
background:url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/logo.gif) no-repeat top left;
cursor:pointer;
display:block;
height:70px;
position:relative;
width:198px;
z-index:15;
}

#hyperpanel-logo span a#home-link:hover {
background:url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/logo-hover.gif) no-repeat top left;
}

#subnav{
	float: right;
	position: relative;
	top: 21px; 
	right: 20px;
}

#hyperpanel-header input.button-framework {
    cursor: pointer;
    font-size: 12px;
    color: #444;
    text-shadow: 0 1px 0 #fff;
    background: #f3f3f3 url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/btn.png) repeat-x 0 0;
    border: 1px solid #bbb;
    padding: 5px 10px;
}

#hyperpanel-header input:hover.button-framework {
    color: #000;
    border-color: #666;
}

#hyperpanel-header a.button-framework {
    cursor: pointer;
    font-size: 12px;
    color: #888;
    text-shadow: 0 1px 0 #000;
    background: #111 url(<?php echo get_template_directory_uri(); ?>/option-tree/assets/top_btn.gif) repeat-x 0 0;
    border: 1px solid #000;
    padding: 3px 10px 3px 10px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    text-decoration: none;
    float: left;
    position: relative;
    left: 1px;
    margin-right: 2px;
  }

#hyperpanel-header a.button-framework:hover{color: #ffffff;}

#framework_wrap, #framework_wrap #content_wrap .bottom, #framework_wrap #content_wrap{
	    -khtml-border-radius: 0 0 10px 10px;
		-webkit-border-radius: 0 0 10px 10px;
		border-radius: 0 0 10px 10px;
		-moz-border-radius: 0 0 10px 10px;
}

</style>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/option-tree/assets/cufon.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/option-tree/assets/font.Museo.js"></script>
<script type="text/javascript">

	Cufon.replace('h2, h3, h4, h5, h6');

</script>

<div id="hyperpanel-wrapper">
	
	<div id="hyperpanel-header">
	    
	    	<div id="hyperpanel-logo">	                
	            <span><a href="http://makedesignnotwar.com" id="home-link" target="_blank"></a></span>	        
	        </div>
	        
	        <div id="subnav">
	        <a class="button-framework save-options" href="<?php echo get_template_directory_uri(); ?>/help/documentation.pdf" target="_blank">View Documentation</a>
	            
	        <a class="button-framework save-options" href="http://makedesignnotwar.com/redirects/portfolio.html" target="_blank">Theme Portfolio</a>
	            
	        <a class="button-framework save-options" href="http://makedesignnotwar.com/redirects/support.html" target="_blank">Get Support</a>
			</div>
	        
    </div>

<?php 

?>