<?php
class epic_Reset_Password {
	function __construct() {
    	add_action( 'init', array($this, 'handle_init' ) );
	}
	
	/*Prepare user meta*/
	function prepare ($array ) {
		foreach($array as $k => $v) {
			if ($k == 'epic-reset-password') continue;
			$this->usermeta[$k] = $v;
		}
		return $this->usermeta;
	}

	/*Handle/return any errors*/
	function handle() {
		// Get username from reset password link to get user
        $login = isset($_GET['login']) ? $_GET['login'] : '';
        $user_data = get_user_by('login', $login);
        $user_id = '';
        if (($user_data instanceof WP_User)) {
            $user_id = $user_data->ID;
        }
        $epic_settings = get_option('epic_options');	    
		require_once(ABSPATH . 'wp-includes/pluggable.php');
		foreach($this->usermeta as $key => $value) {		
			if ($key == 'epic_new_password') {
				if (esc_attr($value) == '') {
					$this->errors[] = __('The new password field is empty.','epic');
				}
			}			
			if ($key == 'epic_confirm_new_password') {
				if (esc_attr($value) == '') {
					$this->errors[] = __('The confirm new password field is empty.','epic');
				}
			}

		}
		if(!empty($this->usermeta['epic_new_password']) && !empty($this->usermeta['epic_confirm_new_password'])
			&& $this->usermeta['epic_new_password'] != $this->usermeta['epic_confirm_new_password']){
			$this->errors[] = __('The passwords do not match.','epic');
		}	
			/* attempt to signon */
			if (!is_array($this->errors)) {
				$reset_pass_key = get_user_meta($user_id, 'epic_reset_pass_key' , true);				
				$key = isset($_GET['key']) ? $_GET['key'] : '';
				if(($reset_pass_key != $key)){
				//if('expired' == $reset_pass_key || ($key != '' && $reset_pass_key != $key)){
					$reset_password_page_id = (int) isset($epic_settings['reset_password_page_id']) ? $epic_settings['reset_password_page_id'] : 0;
        			if ($reset_password_page_id) {
                		$url = get_permalink($reset_password_page_id);
                		$url = epic_add_query_string($url,'epic_reset_status=expired');
            		}            		
					wp_redirect( $url );exit;
				}
				wp_update_user(array('ID' => $user_id, 'user_pass' => esc_attr($this->usermeta['epic_new_password'])));
				// Expire the reset password key after usage
				//update_user_meta($user_id, 'epic_reset_pass_key','expired');
                /*$login_redirect_page_id = (int) isset($epic_settings['login_page_id']) ? $epic_settings['login_page_id'] : 0;
      			if ($login_redirect_page_id) {
                	$url = get_permalink($login_redirect_page_id);
                	wp_redirect( $url );exit;
            	}*/
					wp_redirect(get_site_url().'/login');
			}		
	}

	/*Get errors display*/
	function get_errors() {
		global $epic;
		$display = null;
		// Get global login redirect settings
        $epic_settings = get_option('epic_options');
        $login_redirect_page_id = (int) isset($epic_settings['login_redirect_page_id']) ? $epic_settings['login_redirect_page_id'] : 0;
        $reset_password_page_id = (int) isset($epic_settings['reset_password_page_id']) ? $epic_settings['reset_password_page_id'] : 0;

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $key = isset($_GET['key']) ? $_GET['key'] : ''; 
        $login = isset($_GET['login']) ? $_GET['login'] : '';
        $epic_reset_status = isset($_GET['epic_reset_status']) ? $_GET['epic_reset_status'] : '';
        $info_message = '';
        $user_data = get_user_by('login', $login);
		$user_id = '';
        if (($user_data instanceof WP_User)) {
            $user_id = $user_data->ID;
        }

        if('epic_reset_pass' == $action && '' != $key){
            $reset_pass_key = get_user_meta($user_id, 'epic_reset_pass_key' , true);			
            if('expired' == $reset_pass_key){
            	$this->errors[] = __('This password key has expired or has already been used. Please initiate a new password reset.','epic');            
            	if ($reset_password_page_id) {
                	$url = get_permalink($reset_password_page_id);
                	$url = epic_add_query_string($url,'epic_reset_status=expired');
            	}
				wp_redirect( $url );				
            }else if($reset_pass_key != $key){
                $this->errors[] = __('Invalid Reset Password Key','epic');
            }
            else{
                $this->success[] = __('Please enter the new password.','epic');
            }
        }elseif('expired' == $epic_reset_status){

        	$this->errors[] = __('This password key has expired or has already been used. Please initiate a new password reset.','epic');            
        }
		
		if (isset($this->errors) && is_array($this->errors)) 	{
		    $display .= '<div class="epic-errors">';		
			foreach($this->errors as $newError) {				
				$display .= '<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'.$newError.'</span>';			
			}
			$display .= '</div>';
		} else if (isset($this->success) && is_array($this->success)) {
		    $display .= '<div class="epic-success">';		
			foreach($this->success as $newMsg) {				
				$display .= '<span class="epic-success epic-success-block"><i class="epic-icon epic-icon-ok"></i>'.$newMsg.'</span>';			
			}
			$display .= '</div>';
		}

		else {                        
      		if ($login_redirect_page_id) {
                $url = get_permalink($login_redirect_page_id);
            } else {
				$url = $_SERVER['REQUEST_URI'];
			}
			wp_redirect( $url );
		}
		return $display;
	}

    /* Initializing login class on init action  */
	function handle_init(){
		/*Form is fired*/
		if ( isset( $_POST['epic-reset-password'] ) ) {
			/* Prepare array of fields */
			$this->prepare( $_POST );
			// Setting default to false;
			$this->errors = false;
			/* Validate, get errors, etc before we login a user */
			$this->handle();

		}
	}

}

$epic_reset_password = new epic_Reset_Password();