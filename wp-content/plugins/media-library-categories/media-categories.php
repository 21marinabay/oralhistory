<?php
/*
Plugin Name: Media Categories
Plugin URI: https://sites.google.com/site/medialibarycategories/
Description: Media Library Categories is a WordPress plugin that lets you add custom categories for use in the media library. Media items can then be sorted per category.
Author: Hart Associates (Rick Mead)
Version: 1.1.1
Author URI: http://www.hartinc.com
*/   
   


/**
*  wp-content and plugin urls/paths
*/
// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

include_once("variables.php");
	

// Plugin Hooks
register_activation_hook( __FILE__, array('mc','mc_plugin_activate') );
register_uninstall_hook( __FILE__, array('mc','mc_plugin_uninstall') );
           
	
if (!class_exists('mc')) {
    class mc {
        /**
        * @var string The options string name for this plugin
        */
        static public  $optionsName = 'mc_options';
        
        /**
        * @var string $localizationDomain Domain used for localization
        */
        static public  $localizationDomain = "mc";

        static public $taxonomy = 'media_category' ;
        
        /**
        * @var string $pluginurl The path to this plugin
        */ 
        var $thispluginurl = '';
        /**
        * @var string $pluginurlpath The path to this plugin
        */
        var $thispluginpath = '';
            
        /**
        * @var array $options Stores the options for this plugin
        */
        var $options = array();
        
		
		var $isAdmin = true;
        
		var $optionsmenuRoleCapabilityLevel=10;
		var $adminmenuRoleCapabilityLevel=10;
		
		var $optionsmenuRole='administrator';
		var $adminmenuRole='administrator';
		var $assignmenuRole='author';

		static public $adminCapability = 'manage_media_categories';
		static public $assignCapability = 'assign_media_categories';
		static public $optionsMenuCapability = 'manage_options' ;
		
		
        //Class Functions
        /**
        * PHP 4 Compatible Constructor
        */
        function mc(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
			$admin_init = 0;
			$attachment_fields_to_edit = 22;
			$attachment_fields_to_save = 0;			
		   
            //"Constants" setup
            $this->thispluginurl = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->thispluginpath = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)).'/';           
		   
			//Initialize the options
            $this->getOptions();						
						
			//Check For Term_Order Column
            add_action('init', array(&$this,'CheckForTermOrderColumn'));
			
			//Register Media category type and default
            add_action('init', array(&$this,"create_my_taxonomies"), 0);
			
			
			//Menu for Media Categories Admin section
			add_action('admin_menu',  array(&$this,"mediaCategory_add_admin"), 0);
			
			//Menu for Media Categories Options Admin section
			add_action("admin_menu", array(&$this,"admin_menu_link"), 1);
			
			//Add admin js scripts
            add_action('admin_init', array(&$this, 'add_admin_scripts'), $admin_init++);
		
			
			//Add Filters for editing and saving media records
			add_filter('attachment_fields_to_edit', array(&$this, 'add_media_category_field'), $attachment_fields_to_edit++, 2);
			add_filter('attachment_fields_to_save', array(&$this, 'save_media_category_field'), $attachment_fields_to_save++, 2);
		
			//Add custom column to media library admin page
			add_filter('manage_media_columns',  array(&$this, 'add_media_column'));
			add_action('manage_media_custom_column',  array(&$this, 'manage_media_column'), 10, 2);

			//Add custom filter dropdown to media library admin page
			add_action('restrict_manage_posts',array(&$this, 'restrict_media_by_category'));
			add_filter('posts_where', array(&$this, 'convert_attachment_id_to_taxonomy_term_in_query'));
			
			add_filter('admin_head',array(&$this, 'show_tinyMCE'));

			//for making sure the rewrites show  'www.url.com/media/[media category slug]'
			add_action('admin_init', 'flush_rewrite_rules', $admin_init++);
			add_action('admin_init', array(&$this,"delete_init"), $admin_init++);
			
			add_action('admin_head', array(&$this,'bulk_mlc_action_javascript'));
			add_action('wp_ajax_bulk_mlc_action', array(&$this,'bulk_mlc_action_callback'));
			
			
        }
		
		
		
		
		
		
		///
		// Functions Called From Init
		///
		
		public function CheckForTermOrderColumn()
		{
			global $wpdb;
			
			//-- CHECK FOR term_order column ---
				$query = "
				SELECT COUNT(*) FROM information_schema.COLUMNS 
				WHERE 	
				TABLE_NAME = '$wpdb->terms' 
				AND COLUMN_NAME = 'term_order';
				";

				$row_count = $wpdb->get_var( $wpdb->prepare( $query ) );
			//-- END CHECK FOR term_order column ---
			
			//-- IF term_order column doesnt exist re-add it ---
				$query = "
				ALTER TABLE $wpdb->terms ADD term_order int(4) NOT NULL default 0;;
				";
				if($row_count==0)
				{
					$wpdb->query($query);
				}	
			//-- END IF term_order column doesnt exist re-add it ---
		}
		
		
		
		public function delete_init() {
		  if (current_user_can('delete_posts')) add_action('delete_post', array(&$this,'mc_delete_post_relations'), 10);
		}
		public function mc_delete_post_relations($pID) {
			wp_delete_object_term_relationships($pID, mc::$taxonomy);
			return true;
		}
		
		
		function getOptions() {
            if (!$theOptions = get_option(self::$optionsName)) {
                $theOptions = array('default'=>'options');
				update_option(self::$optionsName, $theOptions);
            }
            $this->options = $theOptions;
			
			/* if($this->options['optionsmenuRoleCapabilityLevel'] !=null && $this->options['optionsmenuRoleCapabilityLevel'] !='' && is_numeric($this->options['optionsmenuRoleCapabilityLevel']))
			{
				$this->optionsmenuRoleCapabilityLevel=$this->options['optionsmenuRoleCapabilityLevel'];
			}
			
			if($this->options['adminmenuRoleCapabilityLevel'] !=null && $this->options['adminmenuRoleCapabilityLevel'] !='' && is_numeric($this->options['adminmenuRoleCapabilityLevel']))
			{
				$this->adminmenuRoleCapabilityLevel=$this->options['adminmenuRoleCapabilityLevel'];
			} */
				
			
        }
		function create_my_taxonomies() {
			register_taxonomy(
				mc::$taxonomy,
				'attachment',
				array(
					'hierarchical' => true,
					'label' => __('Media Categories', self::$localizationDomain),
					'public' => true,
					'show_ui' => true,
					'query_var' => 'media_categories',
					'rewrite' => array('slug' => 'media'),
					'capabilities' => array(
						'assign_terms' => self::$assignCapability,
						'manage_terms' => self::$adminCapability,
						'delete_terms' => self::$adminCapability,
						'edit_terms' => self::$adminCapability,
					),
				)
			);
			
			$isterm = term_exists( 'Default', mc::$taxonomy ); // array is returned if taxonomy is given
			$parent_term_id = '0'; // get numeric term id
			if(!$isterm)
			{
				wp_insert_term(
				  'Default', // the term 
				  mc::$taxonomy, // the taxonomy
				  array(
					'description'=> 'The default media category.',
					'slug' => 'default',
					'parent'=> $parent_term_id
				  )
				);
			}
			
			$term = term_exists( 'Default', mc::$taxonomy ); // array is returned if taxonomy is given
			if($term)
			{
				if($this->options['mc_default_media_category'] ==null ||$this->options['mc_default_media_category'] =='')
				{
					$this->options['mc_default_media_category'] = $term["term_id"];  
					$this->saveAdminOptions();
				}
			}
			
			
		}
		function restrict_admin(){
 			//global $current_user;
			//get_currentuserinfo();

			//if not admin, die with message
			if ( current_user_can('administrator') ) {
				$this->isAdmin = false;
			}
		}
		public function add_admin_scripts() {
			global $pagenow;
			if ($pagenow=='admin.php' &&
					isset($_GET['page']) && $_GET['page']=='media-library-categories/sort.php' &&
					isset($_GET['termid']) && is_numeric($_GET['termid'])) {				
				
				// Insert jQuery 1.4.2
				wp_enqueue_script(
					 'jqueryrequired', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js', false); 
				 
				wp_enqueue_script(
					'WPMediaCategory-jquery-sort',
					$this->thispluginurl. 'js/jquery.tablednd_0_5.js', array( 'jqueryrequired' ));
			
				wp_enqueue_script(
				'WPMediaCategory-jquery-init',
				$this->thispluginurl. 'js/jquery.admin.js', array( 'jqueryrequired', 'WPMediaCategory-jquery-sort' ));
				
			}
		}
		function mediaCategory_add_admin() {
			global $rl_dir, $rl_base, $text_domain;
			add_submenu_page("upload.php", "Media Categories", "Media Categories", (self::$assignCapability), $rl_dir."/view.php");
			
			add_submenu_page($rl_dir."/view.php", "Sort Categories", "Sort Categories", (self::$assignCapability), $rl_dir."/sortCategories.php");
			add_submenu_page($rl_dir."/view.php", "Sort", "Sort", (self::$assignCapability), $rl_dir."/sort.php");
			add_submenu_page($rl_dir."/view.php", "Add Media Category", "Add Media Category", self::$adminCapability, $rl_dir."/add.php");
			
				
		}
        function admin_menu_link() {
            add_options_page('Media Category Options', 'Media Categories Options', self::$optionsMenuCapability, basename(__FILE__), array(&$this,'admin_options_page'));
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), self::$optionsMenuCapability, 2 );			
        }
		public function add_media_category_field($fields, $object) {

			$taxonomy_obj = get_taxonomy(mc::$taxonomy);
			if ( !current_user_can($taxonomy_obj->cap->assign_terms) ) {
				unset( $fields[mc::$taxonomy]) ;
			}
			else 
			{
				unset( $fields[mc::$taxonomy]) ;
				$html='<select id="media-category" name="attachments['.$object->ID.'][media-categories][]" multiple="multiple" style="height:100px;">';
								
				$categories = $this->get_category_hierarchical_terms();
				$selected_categories = (array)wp_get_object_terms($object->ID, mc::$taxonomy);
				
				if (!empty($categories) && !empty($selected_categories)) {
					
					foreach ($categories AS $category) {
					
						$select='';
						foreach ($selected_categories AS $category_selected) {
							if ($category["id"]==$category_selected->term_id) {
								$select = 'selected="selected"';
								break;
							}
						}
						$html.="<option value='".$category["id"]."'  $select>".$category["name"]."</option>";
						
					}

				} 
				else
				{
					foreach ($categories AS $category) {
						$html.="<option value='".$category["id"]."'>".$category["name"]."</option>";
					}
				}

				$html.='</select>';
				
				$selectedValue = '';
				foreach($selected_categories as $cat)
				{
					$selectedValue.= (($selectedValue=="")?"":",").$cat->term_id;
				}

				$label = __('Media Categories', self::$localizationDomain );
				$fields[mc::$taxonomy] = array(
					'label' => $label,
					'input' => 'html',
					'html' =>  $html,
					'value' => $selectedValue,
					'helps' => ''
				); 
				
			}
			return $fields;
		}
		public function save_media_category_field($post, $attachment) {
			$terms = array();
			if ( 
				$attachment 
				&& 
				isset($attachment["media-categories"])
			) {
				foreach ($attachment['media-categories'] as $termID)
				{
					$term = get_term( $termID, mc::$taxonomy  );
					array_push($terms, $term->name);
				}
			}
			//push the new values for this attachment
			$taxonomy_obj = get_taxonomy(mc::$taxonomy);
			if ( current_user_can($taxonomy_obj->cap->assign_terms) )
				wp_set_object_terms($post['ID'], $terms, mc::$taxonomy, false); 
			
			return $post;
		}
		function add_media_column($posts_columns) {
			// Add a new column
			$posts_columns['att_cats'] = _x('Categories', 'column name');
		 
			return $posts_columns;
		}
		function manage_media_column($column_name, $id) {
			
			switch($column_name) {
			case 'att_cats':
				$tagparent = "upload.php?";
				
				$categories = (array)wp_get_object_terms($id, mc::$taxonomy);
				
				if (!empty($categories)) {
				
					$currentLabels = '';
					foreach ($categories AS $category) {
						$currentLabels .= (($currentLabels != "")?", ":"").$category->name;
					}
					
					echo $currentLabels;
				}else {
					_e('No Categories');
				}
				break;
			default:
				break;
			}
		 
		}
		function restrict_media_by_category() {
			global $pagenow;
			global $typenow;
			global $wp_query;
			if ($pagenow=='upload.php') {
			
			
				
				print '<input type="submit" value="Filter" class="button-secondary" id="post-query-submit" name="">';
			
			
				$taxonomy = mc::$taxonomy;
				$media_taxonomy = get_taxonomy($taxonomy);
				$selected = 0;				
				if(isset($_GET['media_category']) && is_numeric($_GET['media_category'])&& $_GET['media_category']!=0 && $_GET['media_category']>-2)
				{
					$selected = $_GET['media_category'];
				}
				
				print '<select class="postform" id="media_category" name="media_category">';
				print '<option '.($selected==0?'selected="selected"':'').' value="0">Show All Media Categories</option>';
				print $this->get_category_hierarchical_selectoptions($selected, 0);
				print '<option '.($selected==-1?'selected="selected"':'').' value="-1">No Categories</option>';
				print '</select>';
				
				print '<input type="submit" value="Bulk Add" onclick="BulkAddToMediaCategory();return false;" class="" id="" name="">';
				print "
				<script type=\"text/javascript\">
					function BulkAddToMediaCategory()
					{
						var e = document.getElementById(\"media_category\");
						var selectedMediaCategory = e.options[e.selectedIndex].value;
						if(selectedMediaCategory>0)
						{
							var inputs = document.getElementsByTagName(\"input\"); //or document.forms[0].elements;
							var cbs = []; //will contain all checkboxes
							var checked = ''; //will contain all checked checkboxes
							for (var i = 0; i < inputs.length; i++) {
							  if (inputs[i].type == \"checkbox\" && inputs[i].name==\"media[]\") {
								cbs.push(inputs[i]);
								if (inputs[i].checked) {
								  checked += ((checked=='')?'':',')+inputs[i].value;
								}
							  }
							}
							
							SendBulkMLCAction(selectedMediaCategory, checked);
						}
						else
						{
							alert('Select one media category to bulk add.');
						}
						
						
					}
				</script>
				";
			}
		}
		function convert_attachment_id_to_taxonomy_term_in_query($where) {
			global $pagenow;
			global $wpdb;
				
			if( $pagenow=='upload.php' &&
					isset($_GET[mc::$taxonomy]) && is_numeric($_GET[mc::$taxonomy])&& $_GET[mc::$taxonomy]!=0 && $_GET[mc::$taxonomy]>-2 ) {
					
				if($_GET[mc::$taxonomy]==-1)
				{
					$subquery = "	SELECT Distinct r.object_id
									FROM wp_templateterm_relationships r
									INNER JOIN wp_templateterm_taxonomy tax on tax.term_taxonomy_id = r.term_taxonomy_id
									where taxonomy = 'media_category'"; 
																					
					$where .= " AND ID NOT IN ($subquery)";		
					
				}
				else
				{
					$commadelimited_ids = $this->get_commadelimited_ids($_GET[mc::$taxonomy], $_GET[mc::$taxonomy]);
					$ids = explode(",", $commadelimited_ids);
					$filterwhere = '';
					foreach ($ids as $id)
					{
						$filterwhere .= (($filterwhere == '')?'':' OR ')."tax.term_id=".$id;
					}
					$subquery = "	SELECT r.object_id FROM $wpdb->term_relationships r
									INNER JOIN $wpdb->term_taxonomy tax on tax.term_taxonomy_id = r.term_taxonomy_id
									WHERE ".$filterwhere; 
						
						
					$where .= " AND ID IN ($subquery)";
				}
			}
			
			return $where;
		}
		function show_tinyMCE() {
			global $pagenow;
			if ($pagenow=='admin.php' &&
					isset($_GET['page']) && $_GET['page']=='media-library-categories/add.php') {
				wp_enqueue_script( 'common' );
				wp_enqueue_script( 'jquery-color' );
				wp_print_scripts('editor');
				if (function_exists('add_thickbox')) add_thickbox();
				wp_print_scripts('media-upload');
				if (function_exists('wp_tiny_mce')) wp_tiny_mce();
				wp_admin_css();
				wp_enqueue_script('utils');
				do_action("admin_print_styles-post-php");
				do_action('admin_print_styles');
				remove_all_filters('mce_external_plugins');
				}
		}  
		
		
		function bulk_mlc_action_javascript() {
		?>
			<script type="text/javascript" >		
			function SendBulkMLCAction(selectedMediaCategory, attachments)
			{
				var data = {
					action: 'bulk_mlc_action',
					bulk: selectedMediaCategory,
					attachments: attachments
				};

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					if(response>0)
					{
						var url = document.URL;
						if(url.indexOf("?")>0)
						{
							url+="&media_category="+response;
						}
						else
						{
							url+="?media_category="+response;
						}
						window.location=url;
					}
				});
			}
			</script>
		<?php
		}
		function bulk_mlc_action_callback() {
			global $wpdb; // this is how you get access to the database

			if(isset($_POST["bulk"])&&$_POST["bulk"]&&isset($_POST["attachments"])&&$_POST["attachments"]) {
	
				$selectedMediaCategoryID=$_POST["bulk"];
				
				$attachmentsCommaDelimmited=$_POST["attachments"];
				$attachments = explode(",", $attachmentsCommaDelimmited);
				
				$cnt = count($attachments);
				foreach($attachments as $attachmentid)
				{	
					$terms = array();
					
					$bulkterm = get_term( $selectedMediaCategoryID, 'media_category' );
					array_push($terms, $bulkterm->name);
					
					$currentTerms = (array)wp_get_object_terms($attachmentid, 'media_category');
					foreach($currentTerms as $ct)
					{
						array_push($terms, $ct->name);
					}
				
					wp_set_object_terms($attachmentid, $terms, 'media_category', false); 
				}
				
				echo $selectedMediaCategoryID;
				
			}

			die(); // this is required to return a proper result
		}
		///
		// END Functions Called From Init
		///
		
		
		
		
		

		
		
		
		
		
			
		public function get_category_hierarchical_list($parentID = 0, $num_per_page=0, $start=0) {
			$return = array();
			if($num_per_page==0)
			{
				$args = array(
					'hide_empty' => false,
					'parent' => (int)$parentID,
					'hierarchical' => false,				
					'taxonomy' => mc::$taxonomy,
					'offset'=>$start,
				'orderby' => 'term_order',
				'order' => 'ASC'
				);
			}
			else
			{
				$args = array(
					'hide_empty' => false,
					'parent' => (int)$parentID,
					'hierarchical' => false,				
					'taxonomy' => mc::$taxonomy,
					'orderby' => 'term_order',
					'order' => 'ASC',
					'number'=>$num_per_page,
					'offset'=>$start
				);
			}
			
			$categorias = $this->get_media_categories($args);
			
			if (empty($categorias)) return $return;

			foreach ($categorias AS $categoria) {
				$array = array();
				$array['id'] = $categoria->term_id;
				$array['name'] = $categoria->name;
				$array['slug'] = $categoria->category_nicename;
				$array['children'] = $this->get_category_hierarchical_list($categoria->term_id);
				$return[] = $array;
			}			
			
			return $return;
		}
		
		
		public function get_category_hierarchical_terms($parentID = 0, $return = array(), $dashes ='') {
			
			$args = array(
					'hide_empty' => false,
					'parent' => (int)$parentID,
					'hierarchical' => false,				
					'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
				);
			
			
			$categorias = $this->get_media_categories($args);
			
			if (empty($categorias)) return $return;

			if($parentID>0)$dashes.='&mdash;';
				
			foreach ($categorias AS $categoria) {
				
				
					$array = array();
					$array['id'] = $categoria->term_id;
					$array['name'] = $dashes.' '.$categoria->name;
					$array['slug'] = $categoria->category_nicename;
					$array['description'] = $categoria->description;
					$array['count'] = $categoria->count;
					
					$_attachments = array();
					
					
						$attachmentIds = get_objects_in_term( $categoria->term_id, mc::$taxonomy, $args );
			
						$args = array(
								'orderby'         => 'post_date',
								'order'           => 'DESC',
								'include'         => $attachmentIds,
								'post_type'       => 'attachment',
								); 
						$attachments = get_posts($args);
						if(count($attachments>0)){
							foreach ( $attachments as $attachment ) {
								
								$mime = strtolower($attachment->post_mime_type);
								
								$_array = array();
								$_array['id'] = $attachment->ID;
								$_array['title'] = $attachment->post_title;
												
								if($mime=='image/jpeg'
									|| $mime=='image/jpg'
									|| $mime=='image/gif'
									|| $mime=='image/png'
									|| $mime=='image/bmp'
									|| $mime=='image/tiff'
									)
								{
									$thumb = wp_get_attachment_thumb_url( $attachment->ID );
									$fullsize = $attachment->guid;
									
									$_array['thumb'] = $thumb;								
									$_array['fileurl'] = $attachment->guid;
								}
								else
								{
									$_array['thumb'] = '';								
									$_array['fileurl'] = $attachment->guid;
								}
								$_attachments[]=$_array;
							}
						}
						
					$array['attachments'] =$_attachments;
					$return[] = $array;
				
				$return = $this->get_category_hierarchical_terms($categoria->term_id, $return, $dashes);
			}			
			
			return $return;
		}
		
		
		public function get_category_hierarchical_selectoptions($selected=0, $parentID = 0, $return ='', $dashes ='') {
						
			$args = array(
				'hide_empty' => false,
				'parent' => (int)$parentID,
				'hierarchical' => false,				
				'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
			);
			$categorias = $this->get_media_categories($args);
			
			if($parentID>0)$dashes.='-';
				
			foreach ($categorias AS $categoria) {
				
				$selectedhtml='';
				
				
				if($selected==$categoria->term_id)
				{
					$selectedhtml = " selected='selected' ";
				}
				
				$return .="<option $selectedhtml value='".$categoria->term_id."'>$dashes".$categoria->name."</option>";
				
				$return =$this->get_category_hierarchical_selectoptions($selected, $categoria->term_id, $return, $dashes);
			
			}			
			
			return $return;
		}
		
		public function get_category_hierarchical_sortlist($parentID = 0, $return ='', $dashes ='') {
						
			$args = array(
				'hide_empty' => false,
				'parent' => (int)$parentID,
				'hierarchical' => false,				
				'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
			);
			$categorias = $this->get_media_categories($args);
			if($parentID>0)$dashes.='-';
				
			foreach ($categorias AS $categoria) {
				
				$selectedhtml='';
				
				$return .="
				<li id=\"list_$categoria->term_id\">
					<div>$categoria->name</div>
					<ol>
				";
							$return =$this->get_category_hierarchical_sortlist($categoria->term_id, $return, $dashes);
				$return .="
					</ol>
				</li>";
			}			
			
			return $return;
		}
		
		public function get_category_select($selected=0, $parentID = 0, $return ='') {
			
			$selectInput = $this->get_category_hierarchical_selectoptions($selected, $parentID, $return);
			
			return "<select>".$selectInput."</select>";
		}
		
		
		public function get_category_archive($term_id=0, $parentID = 0, $return = '') {
			
			$args = null;
			if($term_id>0)
			{
				$args = array(
						'hide_empty' => false,
						'include' => (int)$term_id,
						'hierarchical' => false,				
						'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
					);
			}
			if($parentID>0)
			{
				$args = array(
						'hide_empty' => false,
						'parent' => (int)$parentID,
						'hierarchical' => false,				
						'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
					);
			}
			
			$categorias = $this->get_media_categories($args);
			
			if (empty($categorias)) return $return;

			foreach ($categorias AS $categoria) {
				$return .="<div id='category_".$categoria->term_id."'>";
				$return .="<h2>". $categoria->name ."</h2>";
				$return .=(($categoria->description!='')?'<p>'.$categoria->description.'</p>':''); 
				
				$return .="<ul>";
					
							$attachmentIds = get_objects_in_term( $categoria->term_id, mc::$taxonomy, $args );
				
							$args = array(
									'orderby'         => 'post_date',
									'order'           => 'DESC',
									'include'         => $attachmentIds,
									'post_type'       => 'attachment',
									); 
							$attachments = get_posts($args);
							if(count($attachments>0)):
								foreach ( $attachments as $attachment ) {
									
									$mime = strtolower($attachment->post_mime_type);
													
									if($mime=='image/jpeg'
										|| $mime=='image/jpg'
										|| $mime=='image/gif'
										|| $mime=='image/png'
										|| $mime=='image/bmp'
										|| $mime=='image/tiff'
										)
									{
										$thumb = wp_get_attachment_thumb_url( $attachment->ID );
										$fullsize = $attachment->guid;
										$return .="<li>";
												$return .="<a href='". $fullsize ."' target='_blank'>";
													$return .="<img class='thumb' src='". $thumb ."' alt='". $attachment->post_title ."' />	";
												$return .="</a>";
										$return .="</li>";
									}
									else
									{
										$return .="<li>";
												$return .="<a href='". $attachment->guid ."' target='_blank'>". $attachment->post_title ."</a>";
										$return .="</li>";
									}
									
								}
							endif;
				$return .="</ul>";	
				
				$return = $this->get_category_archive(0, $categoria->term_id, $return);
				$return .="</div>";	
				
				
			}			
			
			return $return;
		}
		
		function get_commadelimited_ids($parentID = 0, $return ='') {
				
						
			$args = array(
				'hide_empty' => false,
				'parent' => (int)$parentID,
				'hierarchical' => false,				
				'taxonomy' => mc::$taxonomy,
				'orderby' => 'term_order',
				'order' => 'ASC'
			);
			$categorias = $this->get_media_categories($args);
				
			foreach ($categorias AS $categoria) {
				
				
				$return .=(($return=="")?"":",")."$categoria->term_id";
				$return =$this->get_commadelimited_ids($categoria->term_id, $return);
				
			}			
			
			return $return;
		}
		//This method instead to sort by term_order
		function get_media_categories($args = array())
		{
			global $wpdb;
			$ret = array();
			if(empty($args))
			{
				$args = array(
					'parent' => 0,
					'taxonomy' => mc::$taxonomy,
					'orderby' => 'term_order',
					'order' => 'ASC'
				);
			}	
			
			$query ="
				SELECT 
					tax.term_id,
					term.name, 
					term.slug, 
					term.term_group,
					tax.term_taxonomy_id, 
					tax.taxonomy,
					tax.description, 
					tax.parent, 
					tax.count, 
					term.term_order,
					tax.count as category_count,
					tax.description as category_description,
					term.name as cat_name,
					term.slug as category_nicename,
					tax.parent as category_parent
				FROM $wpdb->terms term
				INNER JOIN $wpdb->term_taxonomy tax ON tax.term_id = term.term_id
				WHERE ";
				
				if(isset($args['parent']))
				{
					$query .= "parent = ".$args['parent']." AND ";
				}
				$query .= "taxonomy = '".$args['taxonomy']."'
				order by ".$args['orderby']." ".$args['order']."
			";
			if(array_key_exists("number",$args) && array_key_exists("offset",$args))
			{
				$query.= " LIMIT ".$args['offset'].", ".$args['number'];
			}
			
			$categories = $wpdb->get_results( $query );
			
			if (empty($categories)) return $ret;				
			
			return $categories;
			
		}
		function saveAdminOptions(){
            return update_option(self::$optionsName, $this->options);
        }
        function filter_plugin_actions($links, $file) {
           $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
           array_unshift( $links, $settings_link ); // before other links

           return $links;
        }
        function admin_options_page() { 
			global $rl_dir, $rl_base, $text_domain;
			global $wp_roles;
		
            if(isset($_POST['mc_save']) && $_POST['mc_save']){
                if (! wp_verify_nonce($_POST['_wpnonce'], 'mc-update-options') ) die('Whoops! There was a problem with the data you posted. Please go back and try again.'); 
				$this->options['mc_default_media_category'] = $_POST['mc_default_media_category'];               
                                          
                $this->saveAdminOptions();
                $adminRoles = @$_POST['mc_adminRoles'] ;
                $assignRoles = @$_POST['mc_assignRoles'];
                foreach ($wp_roles->get_names() as $role_name => $formal_name ) {
                	$role = get_role( $role_name ) ;
                	if ($adminRoles !== null && is_array($adminRoles) &&
                				in_array($role_name, $adminRoles)) {
                		$role->add_cap( mc::$adminCapability ) ;
                	}
                	else {
                		$role->remove_cap( mc::$adminCapability );
                	}
                    if ($assignRoles !== null && is_array($assignRoles) &&
                				in_array($role_name, $assignRoles)) {
                		$role->add_cap( mc::$assignCapability ) ;
                	}
                	else {
                		$role->remove_cap( mc::$assignCapability );
                	}
                }
                echo '<div class="updated"><p>Success! Your changes were sucessfully saved!</p></div>';
            }
			
?>                                
                <div class="wrap">
                <h2>Media Category Options <a class='button add-new-h2' href='admin.php?page=<?php echo $rl_dir ?>/view.php'>Manage Media Categories</a></h2>
                <form method="post" id="mc_options">
                <?php wp_nonce_field('mc-update-options'); ?>
                    <table width="100%" cellspacing="2" cellpadding="5" class="form-table"> 
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Default Category ID:', self::$localizationDomain); ?></th> 
                            <td><input name="mc_default_media_category" type="text" id="mc_default_media_category" size="45" value="<?php echo $this->options['mc_default_media_category'] ;?>"/>
                        </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Manager Role Access:', self::$localizationDomain); ?></th> 
                            <td>
								<select name="mc_adminRoles[]" id="mc_adminRoles" multiple="multiple" style="height: 120px;">
									<?php						
										foreach ($wp_roles->get_names() as $role_name => $formal_name):
											$role_O = get_role( $role_name ) ;
									?>
											<option <?php echo ($role_O->has_cap(mc::$adminCapability)?'selected="selected" ': '') ?>value="<?php echo $role_name?>"><?php echo $formal_name?></option>
									<?php 						
										endforeach; 
									?>
                            </select>
							</td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Options Page Role Access:', self::$localizationDomain); ?></th> 
                            <td>
                            <select name="mc_assignRoles[]" id="mc_assignRoles" multiple="multiple" style="height: 120px;">
								<?php						
									foreach ($wp_roles->get_names() as $role_name => $formal_name):
										$role_O = get_role( $role_name ) ;
								?>
										<option <?php echo ($role_O->has_cap(mc::$assignCapability)?'selected="selected" ': '') ?>value="<?php echo $role_name?>"><?php echo $formal_name?></option>
								<?php 						
									endforeach; 
								?>
                            </select>
                        </td> 
                        </tr>
						
                        <tr>
                            <th colspan=2><input type="submit" name="mc_save" value="Save" /></th>
                        </tr>
                    </table>
                </form>
                <?php
        }
        
               
		/*
		 * Plugin activation
		 */
		static function mc_plugin_activate()
		{
			// Initialize default capabilities
			
			$role = get_role( 'administrator' ); 
			$role->add_cap( self::$adminCapability );
			$role->add_cap( self::$assignCapability );
			
			$role = get_role( 'editor' ); 
			$role->add_cap( self::$adminCapability );
			$role->add_cap( self::$assignCapability );
			
			$role = get_role( 'author' ); 
			$role->add_cap( self::$assignCapability );	
		}
		
		/*
		 * Plugin deactivation
		 */
		static function mc_plugin_uninstall()
		{
			global $wp_roles;
			// Initialize default capabilities
			
			$rolenames = $wp_roles->get_names() ;
			foreach ( $rolenames as $rolename => $displ ) {
				$role = get_role( $rolename );
				$role->remove_cap( self::$adminCapability );
				$role->remove_cap( self::$assignCapability );
			}

			delete_option(self::$optionsName);
		}       
    
         
  } //End Class
} //End if class exists statement

//instantiate the class
if (class_exists('mc')) {

    $mc_var = new mc();

}

/* ============================
* Plugin Shortcodes
* ============================
*/ 
include_once("includes/shortcodes.php");

/* ============================
* Media Upload Tab
* ============================
*/ 
include_once("includes/mediaUploadTab.php");

/* ============================
* MISC FUNCTIONS
* ============================
*/
function contains($mystring, $findme) {
        $pos = strpos($mystring, $findme);
 
        if($pos === false) {
                // string needle NOT found in haystack
                return false;
        }
        else {
                // string needle found in haystack
                return true;
        }
 
}
function cleanQuery($string)
{
  if(get_magic_quotes_gpc())  // prevents duplicate backslashes
  {
	$string = stripslashes($string);
  }
  if (phpversion() >= '4.3.0')
  {
	$string = mysql_real_escape_string($string);
  }
  else
  {
	$string = mysql_escape_string($string);
  }
  return $string;
}



?>
