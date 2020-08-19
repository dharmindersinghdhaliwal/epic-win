<?php
/*
 *  API for providing epic date for other plugins and themes
 *
*/

/* TODO - Get the link to the user profile by username */
/* TODO - Get the link to the user profile by ID */

class epic_API{

	public $epic_settings;

	public function __construct(){
		$this->epic_settings = get_option('epic_options');;
	}

	/* Get the user ID of logged in user */
	public function current_user_id(){

		$current_user_id = 0;
		if(is_user_logged_in()){
			$current_user_id = get_current_user_id();		
		}	

		return $current_user_id;
	}

	/* Check whether user is logged in to the site */
	public function is_user_loggedin(){
		if(is_user_logged_in()){
			return true;
		}
		return false;
	}

	/* Get the user ID of currently viewed profile */
	public function user_profile_id(){
		global $epic;
		if(isset($epic->current_view_profile_id) && '' != $epic->current_view_profile_id){
			return $epic->current_view_profile_id;
		}
		return false;
	}

	/* Display welcome message for the users using the username */
	public function display_welcome_message(){
		$message = __('Welcome Guest','epic');
		$message = apply_filters('epic_user_welcome_message',$message,'guest');

		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$message = __('Welcome '.$current_user->user_login,'epic');
			$message = apply_filters('epic_user_welcome_message',$message,'member');		
		}	

		return $message;
	}

	/*  Get custom field value for loggedin user */
	public function get_loggedin_profile_field_value($meta_key){

		$field_value = false;

		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$field_value = get_user_meta( $current_user->ID , $meta_key, TRUE);		
			
		}

		return $field_value;
	}

	/*  Get custom field value for user of currently viewed profile */
	public function get_viewed_profile_field_value($meta_key){
		global $epic;

		$field_value = false;

		if(isset($epic->current_view_profile_id) && '' != $epic->current_view_profile_id){
			$field_value = get_user_meta( $epic->current_view_profile_id , $meta_key, TRUE);		
		}

		return $field_value;
	}

	/*  Get custom field value for loggedin user using shortcode */
	public function get_loggedin_profile_field_shortcode_value($args,$content = ''){

		$defaults = array(
            'key' => null,
        );

        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

		return $this->get_loggedin_profile_field_value($key);
	}

	/*  Get custom field value for user of currently viewed profile using shortcode */
	public function get_viewed_profile_field_shortcode_value($args,$content = ''){
		$defaults = array(
            'key' => null,
        );

        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

		return $this->get_viewed_profile_field_value($key);
	}

	/* Get the link to the global profile page defined in epic settings */
	public function profile_page_link(){
		$link = $this->epic_settings['profile_page_id'];
		if($link == 0){
			return false;
		}
		return get_permalink($link);
	}

	/* Get the link to the global member list page defined in epic settings */
	public function member_list_page_link(){
		$link = $this->epic_settings['member_list_page_id'];
		if($link == 0){
			return false;
		}
		return get_permalink($link);
	}

	/* Get the link to the global registration page defined in epic settings */
	public function registration_page_link(){
		$link = $this->epic_settings['registration_page_id'];
		if($link == 0){
			return false;
		}
		return get_permalink($link);
	}

	/* Get the link to the global login page defined in epic settings */
	public function login_page_link(){
		$link = $this->epic_settings['login_page_id'];
		if($link == 0){
			return false;
		}
		return get_permalink($link);
	}

	/* Get the link to the global reset password page defined in epic settings */
	public function reset_password_page_link(){
		$link = $this->epic_settings['reset_password_page_id'];
		if($link == 0){
			return false;
		}
		return get_permalink($link);
	}

	/* Get links to system pages using a shortcode. This function provides the option
	 * to get text link or the actual HTML link
	 */
	public function epic_api_page_link($args,$content = ''){

		$defaults = array(
            'page' => null,
            'link' => 'yes',
        );

        $args = wp_parse_args($args, $defaults);

        $this->epic_args = $args;

        extract($args, EXTR_SKIP);

        $display = '';

        $content_str = array(
        					'login' 			=> __('Login Page Link','epic'),
        					'registration' 		=> __('Registration Page Link','epic'),
        					'reset_password' 	=> __('Reset Password Page Link','epic'),
        					'profile' 			=> __('Profile Page Link','epic'),
        					'member_list' 		=> __('Member List Page Link','epic'),
        				);

        $content = ('' == $content) ? $content_str[$page] : $content;
        $func = $page.'_page_link';
        if('yes' == $link){
        	
			$display = '<a href="'.$this->$func().'">'.$content.'</a>';
		}else{
			$display = $this->$func();
		}

        return $display;
	}

}

$epic_api = new epic_API();


/*
 *  =================================================
 *	Shortcodes of getting the output of API functions
 *  =================================================
 */

add_shortcode('epic_api_user_profile_id', array($epic_api,'user_profile_id'));
add_shortcode('epic_api_current_user_id', array($epic_api,'current_user_id'));
add_shortcode('epic_api_display_welcome_message', array($epic_api,'display_welcome_message'));
add_shortcode('epic_api_get_loggedin_profile_field_value', array($epic_api,'get_loggedin_profile_field_shortcode_value'));
add_shortcode('epic_api_get_viewed_profile_field_value', array($epic_api,'get_viewed_profile_field_shortcode_value'));
add_shortcode('epic_api_page_link', array($epic_api,'epic_api_page_link'));


