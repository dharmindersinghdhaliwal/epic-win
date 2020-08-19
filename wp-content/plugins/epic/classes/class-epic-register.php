<?php

class epic_Register {

    function __construct() {
        add_action('init', array($this, 'handle_init'));
        add_action('init', array($this, 'epic_password_nag_handler'));
             
        $this->errors = null;

        add_action('epic_before_registration_restrictions',array($this, 'epic_before_registration_restrictions'), 10 ,2);
    }

    /* Prepare user meta */
    function prepare($array) {


        /* epic Filters for omit saving custom or hidden fields */
        $skip_save_registration_fields_params = array();
        $skipped_reg_fields = apply_filters( 'epic_skip_save_registration_fields', array() , $skip_save_registration_fields_params);
        // End Filters

        foreach ($array as $k => $v) {
            if ($k == 'epic-register')
                continue;
            if (!(false === strpos($k, 'epic-hidden')))
                continue;
            if(in_array($k, $skipped_reg_fields))
                continue;
            $this->usermeta[$k] = $v;
        }
        return $this->usermeta;
    }

    /* Handle/return any errors */

    function handle() {
        global $epic_captcha_loader,$epic_save;
        require_once(ABSPATH . 'wp-includes/pluggable.php');

        if (get_option('users_can_register') == '1') {          
            
            foreach ($this->usermeta as $key => $value) {
                
                /* Validate username */
                if ($key == 'user_login') {

                    /* epic Action for validating username before creating new user */
                    do_action('epic_validate_username',$value);
                    // End Action

                    if (esc_attr($value) == '') {
                        $this->errors[] = __('Please enter a username.', 'epic');
                    } elseif (username_exists($value)) {
                        $this->errors[] = __('This username is already registered. Please choose another one.', 'epic');
                    }
                }

                /* Validate email */
                if ($key == 'user_email') {
                    if (esc_attr($value) == '') {
                        $this->errors[] = __('Please type your e-mail address.', 'epic');
                    } elseif (!is_email($value)) {
                        $this->errors[] = __('The email address isn\'t correct.', 'epic');
                    } elseif (email_exists($value)) {
                        $this->errors[] = __('This email is already registered, please choose another one.', 'epic');
                    }
                }


                /* epic filter for adding restrictions before custom field type saving */
                $registration_custom_field_type_restrictions_params = array('meta' => $key, 'value' => $value);
                $this->errors = apply_filters('epic_registration_custom_field_type_restrictions', $this->errors, $registration_custom_field_type_restrictions_params);
                /* END filter */ 
            }

            if (!epic_is_in_post('no_captcha', 'yes')) {
                if (!$epic_captcha_loader->validate_captcha(epic_post_value('captcha_plugin'))) {
                    $this->errors[] = __('Please complete Captcha Test first.', 'epic');
                }
            }
            
            // Handle file upload field validations
            $upload_params = array('username' => $this->usermeta['user_login']);
            $epic_save->process_registration_upload($_FILES,'1',$upload_params);
            $reg_upload_errors = isset($epic_save->errors) ? $epic_save->errors : '';

            if(is_array($reg_upload_errors)){
                $this->errors = (array) $this->errors;
                foreach($reg_upload_errors as $error){
                    array_push($this->errors,$error);
                }
            }
            
        } else {
            $this->errors[] = __('Registration is disabled for this site.', 'epic');
        }
    }

    /* Create user */

    function create() {
        global $epic_roles,$epic_email_templates,$epic_save;
        
        require_once(ABSPATH . 'wp-includes/pluggable.php');

        // Verify whether registration form name is modified
        if(isset($_POST['epic-register-form-name'])){

            $epic_secret_key = get_option('epic_secret_key');
            $register_form_name = $_POST['epic-register-form-name'];
            $register_form_name_hash = $_POST['epic-hidden-register-form-name-hash'];

            if($register_form_name_hash != hash('sha256', $register_form_name.$epic_secret_key) ){
                // Invailid form name was defined by manually editing
                $this->errors[] = __('Invalid registration form.','epic');
                return;
            }
            $this->registration_form_name = $register_form_name;
        }


        /* epic action for adding restrictions before registration */
        $before_registration_validation_params = array();
        do_action('epic_before_registration_restrictions', $this->usermeta , $before_registration_validation_params);
        /* END action */ 

        /* Create profile when there is no error */
        if (!isset($this->errors)) {

            // Set date format from admin settings
            $epic_settings = get_option('epic_options');
            $epic_date_format = (string) isset($epic_settings['date_format']) ? $epic_settings['date_format'] : 'mm/dd/yy';

            /* Create account, update user meta */
            $sanitized_user_login = sanitize_user($_POST['user_login']);

            /* Get password */
            if (isset($_POST['user_pass']) && $_POST['user_pass'] != '') {
                $user_pass = $_POST['user_pass'];
            } else {
                $user_pass = wp_generate_password(12, false);

                /* epic Filters for before registration head section */
                $registration_generated_password_params = array('meta' => $this->usermeta);
                $user_pass = apply_filters( 'epic_registration_generated_password', $user_pass , $registration_generated_password_params);
                // End Filters
            }

            /* New user */
            $user_id = wp_create_user($sanitized_user_login, $user_pass, $_POST['user_email']);
            if (!$user_id) {

                /* epic action for handling failure in new user creation */
                $new_user_registration_fail_params = array();
                do_action('epic_new_user_registration_fail', $user_id , $new_user_registration_fail_params);
                /* END action */

            } else {
                global $epic;
                
                /* Force custom user role on registration using shortcode attributes */
                
                if(isset($_POST['epic-hidden-register-form-user-role'])){
                    $epic_secret_key = get_option('epic_secret_key');
                    $register_user_role = $_POST['epic-hidden-register-form-user-role'];
                    $register_user_role_hash = $_POST['epic-hidden-register-form-user-role-hash'];

                    if($register_user_role_hash == hash('sha256', $register_user_role.$epic_secret_key) && get_role($register_user_role) ){
                        $user = new WP_User( $user_id );
                        $user->set_role( $register_user_role );
                    }else{
                        // Invailid user role was defined by manually editing
                        return;
                    }
                }else{
                    /* Allow users to select the role without forcing */

                    $allow_user_role_registration = $epic_settings['select_user_role_in_registration'];
                    // Set new users role specified in the registration page
                    // This will only used when Select User Role in Registration setting is enabled
                    $allowed_user_roles = $epic_roles->epic_allowed_user_roles_registration();
                    $user_role = isset($this->usermeta['user_role']) ? $this->usermeta['user_role'] : '';

                    if(!empty($user_role) && isset($allowed_user_roles[$user_role]) && $allow_user_role_registration){

                        $user = new WP_User( $user_id );
                        $user->set_role( $user_role );
                    }  
                }

                // Get profile fields
                $profile_fields = get_option('epic_profile_fields');

                // Get list of dattime fields
                $date_time_fields = array();

                foreach ($profile_fields as $key => $field) {
                    extract($field);

                    // Filter date/time custom fields
                    if (isset($profile_fields[$key]['field']) && $profile_fields[$key]['field'] == 'datetime') {
                        array_push($date_time_fields, $profile_fields[$key]['meta']);
                    }
                }

                /* Now update all user meta */
                foreach ($this->usermeta as $key => $value) {

                    // save checkboxes
                    if (is_array($value)) { // checkboxes
                        $value = implode(', ', $value);
                    }

                    if (in_array($key, $date_time_fields)) {
                        if('' != $value){
                            $formatted_date = epic_date_format_to_standerd($value, $epic_date_format);
                            $value = $formatted_date;
                        }
                    }

                    /* epic action for adding custom filtering for each field save registration */
                    $before_registration_field_update_params = array('user_id' => $user_id, 'meta' => $key, 'value' => $value);
                    do_action('epic_before_registration_field_update', $before_registration_field_update_params);
                    /* END action */

                    // Prevent passwords from saving in user meta table
                    if('user_pass' != $key && 'user_pass_confirm' != $key){
                        update_user_meta($user_id, $key, esc_attr($value));
                    }

                    /* epic action for adding custom filtering for each field save registration */
                    $after_registration_field_update_params = array('user_id' => $user_id, 'meta' => $key, 'value' => $value);
                    do_action('epic_after_registration_field_update', $after_registration_field_update_params);
                    /* END action */ 

                    /* update core fields - email, url, pass */
                    if (in_array($key, array('user_email', 'user_url', 'display_name'))) {
                        wp_update_user(array('ID' => $user_id, $key => esc_attr($value)));
                    }
                }

                // Save file upload fields on registration
                $epic_save->userid = $user_id;
                $upload_params = array();
                $epic_save->process_registration_upload($_FILES,'2',$upload_params);
                // Check user selected passwrod setting for saving the activation details

            }



            // Set approval status when user profile approvals are enabled
            $approval_setting_status = $this->validate_user_approval();
            if($approval_setting_status){
                $approval_status = 'INACTIVE';
                update_user_meta($user_id, 'epic_approval_status', $approval_status);
            }else{
                $approval_status = 'ACTIVE';
                update_user_meta($user_id, 'epic_approval_status', $approval_status);
            }

            // Set Profile Status to active by default
            update_user_meta( $user_id, 'epic_user_profile_status', 'ACTIVE' );

            // Set the password nag when user selected password setting is disabled
            // Set activation status and codes when selected password setting is enabled
            $epic_settings = get_option('epic_options');
            $set_pass = (boolean) $epic_settings['set_password'];
            $activation_setting_status = $this->validate_email_confirmation();


            $activation_status = '';
            if (!$set_pass) {                
                update_user_option($user_id, 'default_password_nag', true, true); //Set up the Password change nag.
            }

            if($activation_setting_status){
                $activation_status = 'INACTIVE';
                update_user_meta($user_id, 'epic_activation_status', $activation_status);
            }else{
                $activation_status = 'ACTIVE';
                update_user_meta($user_id, 'epic_activation_status', $activation_status);
            }

            $activation_code = wp_generate_password(12, false);

            update_user_meta($user_id, 'epic_activation_code',$activation_code);


            // Set automatic login based on the setting value in admin
            if ($this->validate_automatic_login()) {
                wp_set_auth_cookie($user_id, false, is_ssl());
            }

            /* action after Account Creation */
            do_action('epic_user_register', $user_id);


            if ( (!empty($activation_status) && 'INACTIVE' == $activation_status) || 
                 (!empty($approval_status) && 'INACTIVE' == $approval_status)) {
                
                $user = new WP_User( $user_id );
                $username = $user->user_login;
                
                $current_option = get_option('epic_options');
                $link = get_permalink($current_option['profile_page_id']);
                $query_str = "epic_action=epic_activate&epic_id=" . $user_id . "&epic_activation_code=" . $activation_code;
                $activation_link = epic_add_query_string($link, $query_str);
                
                
                if('INACTIVE' == $activation_status && 'INACTIVE' == $approval_status){
                    // Activation and approval enabled
                    $send_params = array('activation_link' => $activation_link, 'username' => $username , 'email' => $user->user_email,'password' => $user_pass);
                    
                    if($current_option['notifications_all_admins']){
                        $admin_emails_list = implode(',',$epic_roles->get_admin_emails());
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_activation_approval_admin' , $admin_emails_list , '' , '' ,$send_params,$user_id);
                    }else{
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_activation_approval_admin' , get_option('admin_email') , '' , '' ,$send_params,$user_id);
                    }
                    
                    
                    $email_status = $epic_email_templates->epic_send_emails('reg_activation_approval_user', $user->user_email , '' , '' ,$send_params,$user_id);
                    
                    
                }else if('INACTIVE' == $activation_status){
                    // Activation enabled 
                    
                    $send_params = array('activation_link' => $activation_link,'username' => $username , 'email' => $user->user_email,'password' => $user_pass);
                    
                    if($current_option['notifications_all_admins']){
                        $admin_emails_list = implode(',',$epic_roles->get_admin_emails());
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_activation_admin' , $admin_emails_list , '' , '' ,$send_params,$user_id);
                    }else{
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_activation_admin' , get_option('admin_email') , '' , '' ,$send_params,$user_id);
                    }
                    
                    
                    
                    $email_status = $epic_email_templates->epic_send_emails('reg_activation_user', $user->user_email , '' , '' ,$send_params,$user_id);
                    
                }else if('INACTIVE' == $approval_status){
                    // Approval enabled
                    
                    $send_params = array('username' => $username , 'email' => $user->user_email, 'password' => $user_pass);
                    
                    if($current_option['notifications_all_admins']){
                        $admin_emails_list = implode(',',$epic_roles->get_admin_emails());
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_approval_admin' , $admin_emails_list , '' , '' ,$send_params,$user_id);
                    }else{
                        $admin_email_status = $epic_email_templates->epic_send_emails( 'reg_approval_admin' , get_option('admin_email') , '' , '' ,$send_params,$user_id);
                    }
                    
                    $email_status = $epic_email_templates->epic_send_emails('reg_approval_user', $user->user_email , '' , '' ,$send_params,$user_id);
                    
                }
                
                //epic_new_user_notification($user_id, $user_pass,$activation_status,$activation_code);
                
            }else{
                $user = new WP_User( $user_id );
                $username = $user->user_login;

                $current_option = get_option('epic_options');
                $link = get_permalink($current_option['login_page_id']);

                $send_params = array('username' => $username , 'password' => $user_pass, 'login_link' => $link);
                $email_status = $epic_email_templates->epic_send_emails('reg_default_user', '' , '' , '' ,$send_params,$user_id);
                $send_params = array('username' => $username , 'email' => $user->user_email);
                $admin_email_status = $epic_email_templates->epic_send_emails('reg_default_admin', '' , '' , '' ,$send_params,$user_id);
            
                if(!$email_status && !$admin_email_status){
                    wp_new_user_notification($user_id, $user_pass);
                }
                
            }
        }
    }

    /* Get errors display */

    function get_errors() {
        global $epic;
        $display = null;

        $error_result = array();

        if (isset($this->errors) && count($this->errors) > 0) {
            $display .= '<div class="epic-errors">';
            foreach ($this->errors as $newError) {

                $display .= '<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>' . $newError . '</span>';
            }
            $display .= '</div>';

            $error_result['status'] = "error";
            $error_result['display'] = $display;
        } else {

            $this->registered = 1;

            $epic_settings = get_option('epic_options');

            // Display custom registraion message
            if (isset($epic_settings['msg_register_success'])) {

                $reg_success_msg = $epic_settings['msg_register_success'];
                $approval_setting_status = $this->validate_user_approval();
                if($approval_setting_status){
                    $reg_success_msg .= __('Your account is pending approval.','epic');
                }
                $display .= '<div class="epic-success"><span><i class="epic-icon epic-icon-ok"></i>' . $reg_success_msg . '</span></div>';
            }

            // Add text/HTML setting to be displayed after registration message
            if (isset($epic_settings['html_register_success_after']) && !empty($epic_settings['html_register_success_after'])) {
                $display .= '<div class="epic-success-html">' . remove_script_tags($epic_settings['html_register_success_after']) . '</div>';
            }


            if (isset($_POST['redirect_to'])) {
                wp_redirect($_POST['redirect_to']);
            } else {
                // Redirect to profile page after registration when automatic login is set to true
                if ($this->validate_automatic_login()) {

                    // Redirect to custom page based on the values provided in settings section

                    $register_redirect_page_id = (int) isset($epic_settings['register_redirect_page_id']) ? $epic_settings['register_redirect_page_id'] : 0;
                    
                    if ($register_redirect_page_id) {
                        $url = get_permalink($register_redirect_page_id);
                        wp_redirect($url);
                    }
                }
            }

            $error_result['status'] = "success";
            $error_result['display'] = $display;
        }
        return $error_result;
    }

    /* Initializing login class on init action */

    function handle_init() {
        /* Form is fired */

        if (isset($_POST['epic-register-form'])) {

            /* Prepare array of fields */
            $this->prepare($_POST);

            /* Validate, get errors, etc before we create account */
            $this->handle();

            /* Create account */
            $this->create();
        }
    }

    // Valdate automatic login based on set password
    function validate_automatic_login() {

        $automatic_login_status = FALSE;

        $epic_settings = get_option('epic_options');

        $set_pass = (boolean) $epic_settings['set_password'];
        $automatic_login = (boolean) $epic_settings['automatic_login'];

        if ($set_pass && $automatic_login) {
            $automatic_login_status = TRUE;
        }
        return $automatic_login_status;
    }

    function disable_password_nag($current_status) {
        return 0;
    }

    // Disable password nag notice in the admin for user setup passwords
    function epic_password_nag_handler() {

        if (is_user_logged_in ()) {
            $current_user = wp_get_current_user();

            if (!get_user_option('default_password_nag', $current_user->ID)) {
                add_filter('get_user_option_default_password_nag', array($this, 'disable_password_nag'));
            }
        }
    }

    // Activate users by verifying the activation code against the username
    function epic_user_activation_handler(){

        $message = array();

        if(is_user_logged_in()){
            return;
        }

        if(isset($_GET['epic_action']) && $_GET['epic_action'] == 'epic_activate'){

            $user_id = isset($_GET['epic_id']) ? $_GET['epic_id'] : '';
            $activation_code = isset($_GET['epic_activation_code']) ? $_GET['epic_activation_code'] : '';
            $act_status = get_user_meta($user_id, 'epic_activation_status',TRUE);

            
            if('ACTIVE' == $act_status && $activation_code == get_user_meta($user_id, 'epic_activation_code', TRUE)){

                if('INACTIVE' == get_user_meta($user_id, 'epic_approval_status', TRUE)){

                }else{
                    update_user_meta($user_id, 'epic_activation_status', "ACTIVE");
                    $message['msg'] = __('Account already activated. You can now login.' , 'epic');
                    $message['status'] = 'success';

                    /* epic Action for User Activation Failure */
                    do_action('epic_activation_failed',$user_id,$activation_code,$message['msg']);
                    // End Action
                }

                
                 
            }else if($activation_code == get_user_meta($user_id, 'epic_activation_code', TRUE)){
                update_user_meta($user_id, 'epic_activation_status', "ACTIVE");
                // Show messages based on approval status
                if('INACTIVE' == get_user_meta($user_id, 'epic_approval_status', TRUE)){
                    $message['msg'] = __('Your email has been verified. Please wait for moderator approval.' , 'epic');
                    $message['status'] = 'errors';
                }else{
                    $message['msg'] = __('Activation successful. You can now login.' , 'epic');
                    $message['status'] = 'success';
                }
                

                /* epic Action for User Activation Success */
                do_action('epic_activation_success',$user_id,$activation_code);
                // End Action
                 
                epic_update_user_cache($user_id);
            }
            else{
                $message['msg'] = __('Activation failed. Please use a valid activation code.' , 'epic');
                $message['status'] = 'errors';

                /* epic Action for User Activation Failure */
                do_action('epic_activation_failed',$user_id,$activation_code,$message['msg']);
                // End Action
            }
        }

        return $message;
    }

    // Valdate email confirmation based on automatic login and set password
    function validate_email_confirmation() {

        $email_confirmation_status = FALSE;

        $epic_settings = get_option('epic_options');

        $set_pass = (boolean) $epic_settings['set_password'];
        $automatic_login = (boolean) $epic_settings['automatic_login'];
        $set_email_confirmation = (boolean) $epic_settings['set_email_confirmation'];

        if ($set_pass && !$automatic_login && $set_email_confirmation) {
            $email_confirmation_status = TRUE;
        }
        return $email_confirmation_status;
    }

    // Valdate user approvals based on automatic login and set password
    function validate_user_approval(){

        $user_approval_status = FALSE;

        $epic_settings = get_option('epic_options');

        $set_pass = (boolean) $epic_settings['set_password'];
        $automatic_login = (boolean) $epic_settings['automatic_login'];
        $set_user_approvals = (boolean) $epic_settings['profile_approval_status'];

        if ($set_pass && !$automatic_login && $set_user_approvals) {
            $user_approval_status = TRUE;
        }
        return $user_approval_status;

    }

    function epic_before_registration_restrictions($usermeta, $params){

//        $username = isset($usermeta['user_login']) ? $usermeta['user_login'] : '';
//        $email    = isset($usermeta['user_email']) ? $usermeta['user_email'] : '';
//
//        $this->epic_register_username_restrictions($username);
//        $this->epic_register_email_restrictions($email);
    }

    function epic_register_username_restrictions($username){
        $blocked_usernames = array();
        /* epic filter for defining blocked emails for registration */
        $register_blocked_username_params = array();
        $blocked_usernames = apply_filters('epic_register_blocked_usernames',array(),$register_blocked_username_params);
        /* End filter */ 

        if(in_array($username, $blocked_usernames)){
            $this->errors[] = __('Username you have used is not allowed.','epic');
        }
    }

    function epic_register_email_restrictions($email){
        /* epic filter for defining blocked emails for register */
        $register_blocked_email_params = array();
        $blocked_emails = apply_filters('epic_register_blocked_emails',array(),$register_blocked_email_params);
        /* End filter */ 

        $response = true;

        if(in_array($email, $blocked_emails)){
            $this->errors[] = __('Email you have used is not allowed.','epic');
            $response = false;
        }

        /* epic filter for defining blocked emails for register */
        $register_blocked_email_domain_params = array();
        $blocked_email_domains = apply_filters('epic_register_blocked_email_domains',array(),$register_blocked_email_domain_params);
        /* End filter */ 


        if(is_email($email)){
            $email_domain = explode('@', $email);
            $email_domain = array_pop($email_domain);

            if(in_array($email_domain, $blocked_email_domains)){
                $this->errors[] = __('Email domain you have used is not allowed.','epic');
                $response = false;
            }
        }

        return $response;
        
    }
    

}

$epic_register = new epic_Register();

