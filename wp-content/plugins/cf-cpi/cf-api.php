<?php
if(!class_exists('WP_CodeFlox')){
	/**
	* CLASS: WP_CF_API
	*
	* Description: This class deals with all minor and some time major
	* utility across the website. If any class needs to use quick fixe
	* it has to extends this one.
	*
	* @author: Manoj Singh
	* @copyright: Copyright 2017. All rights reserved
	* @version: 0.0.1
	* @testedupto: 3.7.2
	*/
	//add_action('plugins_loaded',array('WP_CodeFlox','init'));
	class WP_CodeFlox{
		function init(){
        	$class = __CLASS__;
        	new $class;
    	}
		function __construct(){
		}
		public static function get_id_by_slug($slug){
			$page	=	get_page_by_path($slug);
			if($page){
				return $page->ID;
			}
			else{
				return null;
			}
		} 
		public static function is_slug_exists($post_name){
			global $wpdb;
			if($wpdb->get_row("SELECT post_name FROM ".$wpdb->prefix."posts WHERE post_name='".$post_name."'",'ARRAY_A')){
				return true;
			}
			else{
				return false;
			}
		}
		public static function create_page($name,$slug,$content,$parent,$page_template="default"){
			if(!WP_CodeFlox::is_slug_exists($slug)){
				$page_defin	=	array($slug=>array('title'=>$name,'content'=>$content));
				foreach($page_defin as $sl=>$pg){
					$query=new WP_Query('pagename='.$sl);
					if(!$query->have_posts()){
						wp_insert_post(
							array(
							'post_content'	=>	$pg['content'],
							'post_name'		=>	$sl,
							'post_title'	=>	$pg['title'],
							'page_template'	=>	$page_template,		#'template-dashboard.php',
							'post_status'	=>	'publish',
							'post_type'		=>	'page',
							'ping_status'	=>	'closed',
							'comment_status'=>	'closed',
							'post_parent'	=>	$parent)
						);
					}
				}
			}
		}
		
	}
	$wp_codeflox=new WP_CodeFlox();
}