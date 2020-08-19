<?php
/*  TODO - User Role based login redirection
- normal login
- social login
- epic_two_factor_email_login
*/
class epic_Login {
	public $epic_settings;
	function __construct() {
		add_action( 'init', array($this, 'handle_init' ) );
    	$this->epic_settings = get_option('epic_options');
    	add_action('epic_before_login_restrictions',array($this, 'epic_before_login_restrictions'), 10 ,2);
        add_action('epic_validate_login', array($this,'epic_two_factor_email_verify'));
        add_action( 'init', array($this, 'epic_two_factor_email_login' ) );
        add_action('wp_login', array($this,'epic_save_login_timestamp'), 10, 2 );
        /* Version 2.0.29 
        add_action('wp_ajax_epic_initialize_login_modal', array($this,'epic_initialize_login_modal'));
        add_action('wp_ajax_nopriv_epic_initialize_login_modal', array($this,'epic_initialize_login_modal'));
        */
	}
    /* Initializing login class on init action  */
	function handle_init(){		
		if ( isset( $_POST['epic-login'] ) ){
			$this->prepare( $_POST );
			$this->errors = false;
			$this->handle();
		}
	}
	function prepare ($array ) {
		foreach($array as $k => $v) {
			if ($k == 'epic-login') continue;
			$this->usermeta[$k] = $v;
		}
		return $this->usermeta;
	}
	/*Handle/return any errors*/
	function handle() {
	    global $epic_captcha_loader;
        $login_verify_status = $this->verify_login_form_hash();
        if(!$login_verify_status)
            return;
		require_once(ABSPATH . 'wp-includes/pluggable.php');
		foreach($this->usermeta as $key => $value) {
			if ($key == 'user_login') {
				if (sanitize_user($value) == '') {
					$this->errors[] = __('The username field is empty.','epic');
				}
			}
			if ($key == 'user_pass') {
				if (esc_attr($value) == '') {
					$this->errors[] = __('The password field is empty.','epic');
				}
			}
		}
		/* epic action for adding restrictions before login */
        $before_login_validation_params = array();
        do_action('epic_before_login_restrictions', $this->usermeta , $before_login_validation_params);
        /* END action */ 
		/* Check approval status and activation status before login */
        $this->verify_activation_approval_status();	
		if(!epic_is_in_post('no_captcha','yes')){
		    if(!$epic_captcha_loader->validate_captcha(epic_post_value('captcha_plugin'))){
		        $this->errors[] = __('Please complete Captcha Test first.','epic');
		    }
		}
		/* attempt to sign in */
		$this->signon();		
	}
	/*Get errors display*/
	function get_errors(){
		global $epic;
		$display = null;	
		if (isset($this->errors) && is_array($this->errors)){
		    $display .= '<div class="epic-errors">';
			foreach($this->errors as $newError) {			
				$display .= '<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'.$newError.'</span>';
			}
			$display .= '</div>';
		} else {
            // Get global login redirect settings
            $login_redirect_page_id = (int) isset($this->epic_settings['login_redirect_page_id']) ? $this->epic_settings['login_redirect_page_id'] : 0;
	      	if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
				$url = $_GET['redirect_to'];
			} elseif (isset($_POST['redirect_to']) && !empty($_POST['redirect_to']) ) {
				$url = $_POST['redirect_to'];
			} elseif ($login_redirect_page_id) {
                $url = get_permalink($login_redirect_page_id);
            } else {
				$url = $_SERVER['REQUEST_URI'];
			}
	        $login_redrect_uri_params = array();
	        $url = apply_filters('epic_login_redrect_uri', $url , $this->usermeta, $login_redrect_uri_params);
			wp_redirect( $url );
		}
		return $display;
	}
	function epic_before_login_restrictions($usermeta, $params){
		global $epic_login;
	    $username = $usermeta['user_login'];
	    $email    = '';
	    if( is_email($username) ){
	    	$email    = $username;
	    }
//	    $this->epic_login_username_restrictions($username);
//	    $this->epic_login_email_restrictions($email);
	}
	function epic_login_username_restrictions($username){
		global $epic_login;
		$blocked_usernames = array();
        $login_blocked_username_params = array();
        $blocked_usernames = apply_filters('epic_login_blocked_usernames',array(),$login_blocked_username_params);
		if(in_array($username, $blocked_usernames)){
			$epic_login->errors[] = __('Username you have used is not allowed.','epic');
		}
	}
	function epic_login_email_restrictions($email){
		global $epic_login;
        $login_blocked_email_params = array();
        $blocked_emails = apply_filters('epic_login_blocked_emails',array(),$login_blocked_email_params);
		if(in_array($email, $blocked_emails)){
			$epic_login->errors[] = __('Email you have used is not allowed.','epic');
		}
        $login_blocked_email_domain_params = array();
        $blocked_email_domains = apply_filters('epic_login_blocked_email_domains',array(),$login_blocked_email_domain_params);
        if(is_email($email)){
        	$email_domain = explode('@', $email);
        	$email_domain = array_pop($email_domain);
			if(in_array($email_domain, $blocked_email_domains)){
				$epic_login->errors[] = __('Email domain you have used is not allowed.','epic');
			}
        }
	}
    /* Verify login details and send the verification link to email - 2 Factor authentication with email */
    function epic_two_factor_email_verify($creds){
        global $epic_email_templates;
        if(!$this->errors){
            $user_login = $creds['user_login'];
            if($this->epic_settings['email_two_factor_verification_status']){
                $user = get_user_by( 'login', $user_login );
                if ( $user && wp_check_password( $creds['user_password'], $user->data->user_pass, $user->ID) ){
                    $user_id = $user->ID;
                    if(get_user_meta($user_id,'epic_email_two_factor_status',true)){
                        $link = get_permalink($this->epic_settings['login_page_id']);
                        $verification_code = wp_generate_password();
                        $link = add_query_arg(array('epic_email_two_factor_verify' => $verification_code), $link);
                        $link = add_query_arg(array('epic_email_two_factor_login' => rawurlencode($user->data->user_login)), $link);
                        update_user_meta($user_id,'epic_email_two_factor_code',$verification_code);
                        $send_params = array('username' => $user->data->user_login , 'email' => $user->data->user_email, 'email_two_factor_login_link' => $link);
                        $email_status = $epic_email_templates->epic_send_emails('two_factor_email_verify', $send_params['email'] , '' , '' ,$send_params,$user_id);
                        $this->errors[] = __('Verfication link sent to your email. Please click the link in the email to login','epic');
                    }
                }else{
                    $epic_login->errors[] = __('Incorrect username or password.','epic');
                }
            }
        }
    }
    /* Verify code from email and automatic login for 2 factor email authentication */
    function epic_two_factor_email_login(){
        $verify_code = isset($_GET['epic_email_two_factor_verify']) ? $_GET['epic_email_two_factor_verify'] : '';
        $user_login  = isset($_GET['epic_email_two_factor_login']) ? $_GET['epic_email_two_factor_login'] : '';
        if('' != $verify_code){
            $user = get_user_by( 'login', $user_login );
            if($user){
                $user_id = $user->ID;
                if($verify_code == get_user_meta($user_id,'epic_email_two_factor_code',true)){
                    delete_user_meta($user_id,'epic_email_two_factor_code');
                    // Set automatic login based on the setting value in admin
                    wp_set_auth_cookie($user_id, false, is_ssl());
                    $link = get_permalink($this->epic_settings['login_page_id']);
                    wp_redirect($link);exit;
                }else{
                    $this->errors[] = __('Invalid verification code in link.','epic');
                }
            }else{
                $this->errors[] = __('Invalid verification link.','epic');
            }
        }
    }
    public function verify_login_form_hash(){
		if(isset($_POST['epic-hidden-login-form-name'])){
			$epic_secret_key = get_option('epic_secret_key');
            $login_form_name = $_POST['epic-hidden-login-form-name'];
            $login_form_name_hash = $_POST['epic-hidden-login-form-name-hash'];
            if($login_form_name_hash != hash('sha256', $login_form_name.$epic_secret_key) ){
            	$this->errors[] = __('Invalid login form.','epic');
            	return false;
            }
            $this->login_form_name = $login_form_name;
		}
        return true;
    }
    public function verify_activation_approval_status(){
        if(isset($_POST['user_login']) && '' != $_POST['user_login']){
			$user_email_check = email_exists($_POST['user_login']);
			if($user_email_check){
				$user_data = new stdClass;
				$user_data->ID = $user_email_check;
			}else{
				$user_data = get_user_by( 'login', $_POST['user_login'] );
				if(!$user_data){
					$user_data = new stdClass;
					$user_data->ID = '';
				}
			}
			if('INACTIVE' == get_user_meta($user_data->ID, 'epic_approval_status' , true)){
				$this->errors[] = $this->epic_settings['html_profile_approval_pending_msg'];
			}
			else if('INACTIVE' == get_user_meta($user_data->ID, 'epic_activation_status' , true)){
				$this->errors[] = __('Please confirm your email to activate your account.','epic');
			}
		}
    }
    public function signon(){
        if (!is_array($this->errors)) {
			$creds = array();
			if(is_email($_POST['user_login'])){
			    $user = get_user_by( 'email', $_POST['user_login'] );
			    if($user){
			    	if(isset($user->data->user_login))
				        $creds['user_login'] = $user->data->user_login;
				    else
				        $creds['user_login'] = '';
			    }else{
			    	$creds['user_login'] = sanitize_user($_POST['user_login'],TRUE);
			    }
			}
			// User is trying to login using username
			else{
			    $creds['user_login'] = sanitize_user($_POST['user_login'],TRUE);
			}
			$creds['user_password'] = $_POST['login_user_pass'];
			$creds['remember'] = $_POST['rememberme'];
			$secure_cookie = false;
			if(is_ssl()){
				$secure_cookie = true;
			}
			/* epic Action validating before login */
			do_action('epic_validate_login',$creds);
			// End Action
			if(!$this->errors){
				$user = wp_signon( $creds, $secure_cookie );
				if ( is_wp_error($user) ) {
					if ($user->get_error_code() == 'invalid_username') {
						$this->errors[] = __('Invalid Username or Email','epic');
					}
					if ($user->get_error_code() == 'incorrect_password') {
						$this->errors[] = __('Incorrect Username or Password','epic');
					}
					if ($user->get_error_code() == 'empty_password') {
					    $this->errors[] = __('Please enter a password.','epic');
					}
			        $login_failed_params = array();
			        do_action('epic_login_failed', $this->usermeta, $user, $login_failed_params);
				}else{
					do_action('wp_login');
			        $login_sucess_params = array();
			        do_action('epic_login_sucess', $this->usermeta, $user, $login_sucess_params);
				}
			}
		}
    }
    public function epic_save_login_timestamp($user_login, $user  = null){
        $user_id = isset($user->ID) ? $user->ID : 0;
        if($user_id){
            update_user_meta($user_id,'epic_last_login_time', time() );
        }
    }
    /* Version 2.0.29 
    public function epic_initialize_login_modal(){
        global $epic;
        if (!is_user_logged_in()) {
		  echo do_shortcode('[epic_login]');
		} else {
		  echo do_shortcode('[epic view="compact" ]');
		}
        exit;
        $id = 1;
        $settings = get_option('epic_options');
        // epic Filter for cusmizing profile window shortcode 
        $profile_shortcode = apply_filters('epic_profile_modal_shortcode',$settings['profile_modal_window_shortcode'],$id);
        // End Filter
        $profile_shortcode = str_replace('[epic' ,'[epic modal_view=yes id="'.$id.'" ',$profile_shortcode);
        $display  = do_shortcode($profile_shortcode);
        echo $display;
        exit;
    }
    */
}
$epic_login = new epic_Login();