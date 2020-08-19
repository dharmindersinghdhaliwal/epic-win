<?php
	/* Add shortcodes */
	add_shortcode('epic', 'epic_shortcode');
	add_shortcode('epic_search', 'epic_search');
	add_shortcode('epic_registration', 'epic_registration');
	add_shortcode('epic_login', 'epic_login');
	add_shortcode('epic_private', 'epic_private');
	add_shortcode('epic_logout', 'epic_logout');
	add_shortcode('epic_reset_password', 'epic_reset_password');
    add_shortcode('epic_member', 'epic_member_content');
    add_shortcode('epic_non_member', 'epic_non_member_content');    
    //add_shortcode('epic', 'epic_shortcode');	
	function epic_shortcode($atts) {
		if(isset($_GET['member'])){
			$atts=array('id'=>$_GET['member']);
		}
		global $epic;
		return $epic->display( $atts );
	}	
	function epic_search($atts) {
		global $epic;
		return $epic->search($atts);
	}	
	function epic_registration($atts) {
		global $epic;
		if (is_user_logged_in()) {
			return $epic->display( $atts );
		}else{
			return $epic->show_registration( $atts );
		}		
	}	
	function epic_login($atts) {
		global $epic;
		if (!is_user_logged_in()) {
		return $epic->login( $atts );
		} else {		
		return $epic->display_mini_profile( $atts );
		}
	}	
	function epic_private($atts, $content = null) {

		global $epic_private_content;
		$private_content_result = $epic_private_content->validate_private_content($atts, $content);
		return $epic_private_content->get_restriction_message($atts,$content,$private_content_result);
		// TODO - Call result message generation function and output the message
	}	
	function epic_logout($atts) {
		global $epic;
		return $epic->logout($atts);
	}
	function epic_reset_password($atts) {
		global $epic;
		if (!is_user_logged_in()) {
			return $epic->epic_reset_password($atts);
		} else {
			return $epic->display_mini_profile( $atts );
		}
	}
    function epic_member_content($atts,$content){
        if (is_user_logged_in()) {
			return do_shortcode($content);
		} else {
			return '';
		}
    }
    function epic_non_member_content($atts,$content){
        if (!is_user_logged_in()) {
			return do_shortcode($content);
		} else {
			return '';
		}
    }
?>