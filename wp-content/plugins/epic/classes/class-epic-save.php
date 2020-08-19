<?php
/* === TODO  ===
    * join process_registration_upload and process_upload to use reusable function
    * improve the reusability of file upload function

*/
class epic_Save {

    public $allowed_extensions;
    public $usermeta;
	public $allowed_exts;
    public $epic_settings;
    public $changed_fields;
    
    function __construct() {
        
        $this->epic_settings = get_option('epic_options');
        $this->changed_fields = array();

        $this->epic_fileds_array = get_option('epic_profile_fields');
        $this->epic_fileds_meta_value_array = array();
        $this->epic_fileds_meta_type_array = array();
        foreach ($this->epic_fileds_array as $key => $value) {
	       $meta_val = isset($value['meta']) ? $value['meta'] : '';
            $this->epic_fileds_meta_value_array[$meta_val] = $value['name'];
            $this->epic_fileds_meta_type_array[$meta_val] = isset($value['field']) ? $value['field'] : '';
        }

        add_action('init', array($this, 'handle_init'));

        $this->errors = null;

    }

    /* Prepare user meta */

    function prepare($array) {
        foreach ($array as $k => $v) {
            $k = str_replace('-' . $this->userid, '', $k);
            if ($k == 'epic-submit')
                continue;
            $this->usermeta[$k] = $v;
        }
        return $this->usermeta;
    }

    /* Process uploads */

    function process_upload($array) {

        /* File upload conditions */
        $this->allowed_extensions = array("image/gif", "image/jpeg", "image/png");
        
		$this->allowed_exts = array('gif','png','jpeg','jpg');

        $this->allowed_non_image_extensions = apply_filters('epic_non_image_extensions',array());
        $this->allowed_non_image_exts = apply_filters('epic_non_image_exts',array());

        $settings = get_option('epic_options');


        // Set default to 500KB
        $this->max_size = 512000;
        
        $this->image_height = 0;
        $this->image_width  = 0;
        
        // Trigger update for file fields
        $limited_fields = array();
        $param_field_updates = array('user_id' => $this->userid);
        $limited_fields = apply_filters('epic_trigger_field_update',$limited_fields,$param_field_updates);

        // Setting Max File Size set from admin
        if (isset($settings['avatar_max_size']) && $settings['avatar_max_size'] > 0)
            $this->max_size = $settings['avatar_max_size'] * 1024 * 1024;

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $array) {
                
                extract($array);
                if ($name) {

                    $clean_file = true;

                    if(in_array($type, $this->allowed_extensions)){
                        // Security Check Start
                        // Checking for Image size. If this is a valid image (not tempered) then this function will return width and height and other values in return.
                        $image_data = @getimagesize($tmp_name);

                        
                        if (!isset($image_data[0]) || !isset($image_data[1])){
                            $clean_file = false;
                            
                        }else{
                            $this->image_height = $image_data[1];
                            $this->image_width  = $image_data[0];
                        }
                            
                        // Security Check End
                    }                   

                    $clean_key = str_replace('-' . $this->userid, '', $key);

                    /* epic action for adding restrictions before uploading files */
                    $before_upload_profile_files_params = array();
                    do_action('epic_before_upload_profile_files', $this->userid, $clean_key, $before_upload_profile_files_params);
                    /* END action */   
                    
                    $field_label = $this->epic_fileds_meta_value_array[$clean_key];

                    if (!in_array($type, $this->allowed_extensions) && !in_array($type, $this->allowed_non_image_extensions)) {
                        $this->errors[$clean_key] = sprintf(__('The file you have selected for %s has a file extension that is not allowed. Please choose a different file.','epic'), $field_label).'<br/>';
                    } elseif ($size > $this->max_size) {
                        $this->errors[$clean_key] = sprintf(__('The file you have selected for %s exceeds the maximum allowed file size.', 'epic'), $field_label).'<br/>';
                    } elseif ($clean_file == false) {
                        $this->errors[$clean_key] = sprintf(__('The file you selected for %s appears to be corrupt or not a real image file.', 'epic'), $field_label).'<br/>';
                    } elseif (!preg_match("/.(".implode("|",$this->allowed_exts).")$/i",$name) && !preg_match("/.(".implode("|",$this->allowed_non_image_exts).")$/i",$name)) {
						$this->errors[$clean_key] = sprintf(__('The file you have selected for %s has a file extension that is not allowed. Please choose a different file.', 'epic'), $field_label).'<br/>';
					} 

                    else {
                        
                        $upload_file_custom_validation_params = array('id'=>$this->userid, 'key'=>$key, 'height'=>$this->image_height, 'width'=> $this->image_width, 'field_label'=>$field_label );
                        $custom_errors = apply_filters('epic_upload_file_custom_validation',array('status'=>false, 'msg'=>'') ,$upload_file_custom_validation_params);

                        if(!$custom_errors['status']){
                            /* Upload image */
                            // Checking for valid uploads folder
                            if ($upload_dir = epic_get_uploads_folder_details()) {
                                $target_path = $upload_dir['basedir'] . "/epic/";

                                // Checking for upload directory, if not exists then new created.
                                if (!is_dir($target_path))
                                    mkdir($target_path, 0777);

                                $base_name = sanitize_file_name(basename($name));

                                $target_path = $target_path . time() . '_' . $base_name;

                                $nice_url = $upload_dir['baseurl'] . "/epic/";
                                $nice_url = $nice_url . time() . '_' . $base_name;
                                move_uploaded_file($tmp_name, $target_path);

                                /* Clean the previous file allocated for the current upload field */
                                $current_field_url = get_user_meta($this->userid, $clean_key, true);
                                if('' != $current_field_url){
                                    epic_delete_uploads_folder_files($current_field_url);                                
                                }                            

                                /* Now we have the nice url */
                                /* Store in usermeta */
                                
                                if(in_array($clean_key, $limited_fields)){
                                    $prev_value = get_user_meta($this->userid,$clean_key,true);
                                    if($prev_value != stripslashes_deep(esc_attr($nice_url)) ){
                                        array_push($this->changed_fields, array('meta'=> $clean_key, 'prev_value'=> $prev_value, 'new_value'=>$nice_url));
                                    }
                                }
                                
                                
                                update_user_meta($this->userid, $clean_key, $nice_url);
                            }
                        }else{
                            $this->errors[$clean_key] = $custom_errors['msg'];
                        }
                    }

                    /* epic action for removing restrictions after uploading files */
                    $after_upload_profile_files_params = array();
                    do_action('epic_after_upload_profile_files', $this->userid, $clean_key, $after_upload_profile_files_params);
                    /* END action */
                }
            }
        }
    }

    /* Handle/return any errors */

    function handle() {
        if (is_array($this->usermeta)) {
            foreach ($this->usermeta as $key => $value) {

                /* Validate email */
                if ($key == 'user_email') {
                    if (!is_email($value)) {
                        $this->errors[$key] = __('E-mail address was not updated. Please enter a valid e-mail.', 'epic');
                    }
                }

                /* Validate password */
                if ($key == 'user_pass') {
                    if (esc_attr($value) != '') {
                        if ($this->usermeta['user_pass'] != $this->usermeta['user_pass_confirm']) {
                            $this->errors[$key] = __('Your passwords do not match.', 'epic');
                        }
                    }
                }


                /* epic filter for adding restrictions before custom field type saving */
                $frontend_custom_field_type_restrictions_params = array('meta' => $key, 'value' => $value);
                $this->errors = apply_filters('epic_frontend_custom_field_type_restrictions', $this->errors, $frontend_custom_field_type_restrictions_params);
                /* END filter */ 
            }
        }
    }

    /* Update user meta */

    function update() {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        

        /* Update profile when there is no error */
        if (!isset($this->errors)) {
            
            $limited_fields = array();
            $param_field_updates = array('user_id' => $this->userid);
            $limited_fields = apply_filters('epic_trigger_field_update',$limited_fields,$param_field_updates);

            // Get list of dattime fields
            $date_time_fields = array();

            foreach ($this->epic_fileds_array as $key => $field) {
                extract($field);

                if (isset($this->epic_fileds_array[$key]['field']) && $this->epic_fileds_array[$key]['field'] == 'checkbox') {
                    
                    if(in_array($meta, $limited_fields)){
                        $prev_value = get_user_meta($this->userid,$meta,true);
                        
                        if($prev_value != null && !isset($this->usermeta[$meta]) ){
                            array_push($this->changed_fields, array('meta'=> $meta, 'prev_value'=> $prev_value, 'new_value'=> ''));
                        }
                        
                        if(!isset($this->usermeta[$meta])){
                            update_user_meta($this->userid, $meta, null); 
                        }
                    }else{
                       update_user_meta($this->userid, $meta, null); 
                    }
                    
                }

                // Filter date/time custom fields
                if (isset($this->epic_fileds_array[$key]['field']) && $this->epic_fileds_array[$key]['field'] == 'datetime') {
                    array_push($date_time_fields, $this->epic_fileds_array[$key]['meta']);
                }
            }


            if (is_array($this->usermeta)) {
                foreach ($this->usermeta as $key => $value) {

                    /* Update profile when there is no error */
                    if (!isset($this->errors[$key])) {

                        // save checkboxes
                        if (is_array($value)) { // checkboxes
                            $value = implode(', ', $value);
                        }

                        //
                        
                        $epic_date_format = (string) isset($this->epic_settings['date_format']) ? $this->epic_settings['date_format'] : 'mm/dd/yy';

                        if (in_array($key, $date_time_fields)) {
                            if (!empty($value)) {
                                $formatted_date = epic_date_format_to_standerd($value, $epic_date_format);
                                $value = $formatted_date;
                            }
                        }

                        /* epic Actions for checking extra fields or hidden data in profile edit form */     
                        
                        if(in_array($key, $limited_fields)){
                            $prev_value = get_user_meta($this->userid,$key,true);
                            if($prev_value != stripslashes_deep(esc_attr($value)) ){
                                array_push($this->changed_fields, array('meta'=>$key, 'prev_value'=> $prev_value, 'new_value'=>$value));
                            }
                        }
                        // End Filter

                        // Prevent passwords from saving in user meta table
                        if('user_pass' != $key && 'user_pass_confirm' != $key){
                            
                            $profile_fields_encode_status = apply_filters('epic_profile_fields_encode_status',true,$key);
                            if($profile_fields_encode_status){
                                update_user_meta($this->userid, $key, esc_attr($value));
                            }else{
                                update_user_meta($this->userid, $key, $value);
                            }                            
                        }
                        

                        /* update core fields - email, url, pass */
                        if ((in_array($key, array('user_email', 'user_url', 'display_name')) ) || ($key == 'user_pass' && esc_attr($value) != '')) {

                            $result = wp_update_user(array('ID' => $this->userid, $key => esc_attr($value)));

                            /* epic Action for after changing password */
                            if(!is_wp_error($result) && 'user_pass' == $key){
                                do_action('epic_after_password_change', $this->userid);
                            }
                            // End Filter


                        }
                    }
                }

                
                
                // Implementing the email sending capabilities
            }

        }
    }

    /* Get errors display */

    function get_errors($id) {
        global $epic;
        $display = null;

        /* epic action for adding errors before save profile */
        $before_profile_update_error_params = array('post_data' => $_POST, 'files' => $_FILES);
        do_action('epic_before_profile_update_errors', $this->userid, $before_profile_update_error_params);
        /* END action */       

        if (isset($this->errors) && count($this->errors) > 0) {
            $display .= '<div class="epic-errors">';
            foreach ($this->errors as $newError) {
                $display .= '<span class="epic-error"><i class="epic-icon epic-icon-remove"></i>' . $newError . '</span>';
            }
            $display .= '</div>';
        } else {
            /* Success message */
            if ($id == $epic->logged_in_user) {
                $display .= '<div class="epic-success"><span><i class="epic-icon epic-icon-ok"></i>' . __('Your profile was updated.', 'epic') . '</span></div>';
            } else {
                $display .= '<div class="epic-success"><span><i class="epic-icon epic-icon-ok"></i>' . __('Profile was updated.', 'epic') . '</span></div>';
            }
        }
        return $display;
    }

    /* Initializing login class on init action  */

    function handle_init() {
        
        /* Form is fired */
        foreach ($_POST as $k => $v) {
            if (strstr($k, 'epic-submit-')) {

                // User ID
                $this->userid = str_replace('epic-submit-', '', $k);

                /* epic action before save profile */
                $before_profile_update_params = array('post_data' => $_POST, 'files' => $_FILES);
                do_action('epic_before_profile_update', $this->userid, $before_profile_update_params);
                /* END action */

                // Prepare fields prior to update
                $this->prepare($_POST);

                // upload files
                $this->process_upload($_FILES);

                // Error handler
                $this->handle();

                // Update fields
                $this->update();
                
                $this->notify_profile_updates();

                /* action after save profile */
                do_action('epic_profile_update', $this->userid);
            } else if (strstr($k, 'epic-upload-submit-')) {

                // User ID
                $this->userid = str_replace('epic-upload-submit-', '', $k);

                // upload files
                $this->process_upload($_FILES);
                
                $this->notify_profile_updates();
                
            } else if (strstr($k, 'epic-crop-submit-')) {

                // User ID
                $this->userid = str_replace('epic-crop-submit-', '', $k);
            } else if (strstr($k, 'epic-crop-save-')) {

                // User ID
                $this->userid = str_replace('epic-crop-save-', '', $k);
            }
        }
    }
    
    
    
    function process_registration_upload($array,$upload_status,$params = array() ) {
        
        $username = isset($params['username']) ? $params['username'] : '' ;

        /* File upload conditions */
        $this->allowed_extensions = array("image/gif", "image/jpeg", "image/png");
        
		$this->allowed_exts = array('gif','png','jpeg','jpg');

        $this->allowed_non_image_extensions = apply_filters('epic_non_image_extensions',array());
        $this->allowed_non_image_exts = apply_filters('epic_non_image_exts',array());

        $settings = get_option('epic_options');


        // Set default to 500KB
        $this->max_size = 512000;
        
        $this->image_height = 0;
        $this->image_width  = 0;

        // Setting Max File Size set from admin
        if (isset($settings['avatar_max_size']) && $settings['avatar_max_size'] > 0)
            $this->max_size = $settings['avatar_max_size'] * 1024 * 1024;

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $array) {
                
                extract($array);
                if ($name) {

                    $clean_file = true;

                    if(in_array($type, $this->allowed_extensions)){
                        // Security Check Start
                        // Checking for Image size. If this is a valid image (not tempered) then this function will return width and height and other values in return.
                        $image_data = @getimagesize($tmp_name);

                        
                        if (!isset($image_data[0]) || !isset($image_data[1])){
                            $clean_file = false;
                            
                        }else{
                            $this->image_height = $image_data[1];
                            $this->image_width  = $image_data[0];
                        }
                            
                        // Security Check End
                    }                   

                    $clean_key = $key;

                    /* epic action for adding restrictions before uploading files */
                    $before_upload_profile_files_params = array();
                    do_action('epic_register_before_upload_profile_files', $username, $clean_key, $before_upload_profile_files_params);
                    /* END action */   
                    
                    $field_label = $this->epic_fileds_meta_value_array[$clean_key];
                    
                    // $upload_status 1 - Validation and $upload_status 2 - Uploading
    
                    if($upload_status == '1'){
                        
                        if (!in_array($type, $this->allowed_extensions) && !in_array($type, $this->allowed_non_image_extensions)) {
                            $this->errors[$clean_key] = sprintf(__('The file you have selected for %s has a file extension that is not allowed. Please choose a different file.','epic'), $field_label).'<br/>';
                        } elseif ($size > $this->max_size) {
                            $this->errors[$clean_key] = sprintf(__('The file you have selected for %s exceeds the maximum allowed file size.', 'epic'), $field_label).'<br/>';
                        } elseif ($clean_file == false) {
                            $this->errors[$clean_key] = sprintf(__('The file you selected for %s appears to be corrupt or not a real image file.', 'epic'), $field_label).'<br/>';
                        } elseif (!preg_match("/.(".implode("|",$this->allowed_exts).")$/i",$name) && !preg_match("/.(".implode("|",$this->allowed_non_image_exts).")$/i",$name)) {
                            $this->errors[$clean_key] = sprintf(__('The file you have selected for %s has a file extension that is not allowed. Please choose a different file.', 'epic'), $field_label).'<br/>';
                        } else{
                            
                            $upload_file_custom_validation_params = array('username'=> $username, 'key'=>$key, 'height'=>$this->image_height, 'width'=> $this->image_width, 'field_label'=>$field_label );
                            $custom_errors = apply_filters('epic_registration_upload_file_custom_validation',array('status'=>false, 'msg'=>'') ,$upload_file_custom_validation_params);
                            
                            if($custom_errors['status']){
                                $this->errors[$clean_key] = $custom_errors['msg'];
                            }
                            
                        }
                        
                    }else if($upload_status == '2'){

                        /* Upload image */
                        // Checking for valid uploads folder
                        if ($upload_dir = epic_get_uploads_folder_details()) {
                            $target_path = $upload_dir['basedir'] . "/epic/";

                            // Checking for upload directory, if not exists then new created.
                            if (!is_dir($target_path))
                                mkdir($target_path, 0777);

                            $base_name = sanitize_file_name(basename($name));

                            $target_path = $target_path . time() . '_' . $base_name;

                            $nice_url = $upload_dir['baseurl'] . "/epic/";
                            $nice_url = $nice_url . time() . '_' . $base_name;
                            move_uploaded_file($tmp_name, $target_path);

                            /* Clean the previous file allocated for the current upload field */
                            $current_field_url = get_user_meta($this->userid, $clean_key, true);
                            if('' != $current_field_url){
                                epic_delete_uploads_folder_files($current_field_url);                                
                            }                            

                            /* Now we have the nice url */
                            /* Store in usermeta */
                            update_user_meta($this->userid, $clean_key, $nice_url);
                        }
                        
                    }


                    /* epic action for removing restrictions after uploading files */
                    $after_upload_profile_files_params = array();
                    do_action('epic_registration_after_upload_profile_files', $username, $clean_key, $after_upload_profile_files_params);
                    /* END action */
                }
            }
        }
    }
    
    
    public function notify_profile_updates(){
       
        if(is_array($this->changed_fields) && count($this->changed_fields) != 0){

            $this->notify_field_update = true;

            /* epic Actions for executing custom functions on profile data change */
            $profile_field_update_triggered_params = array('changed_fields' => $this->changed_fields, 'user_id' => $this->userid );
            do_action('epic_profile_field_update_triggered',$profile_field_update_triggered_params);                   
            // End action

            if($this->notify_field_update){
                $full_name = get_user_meta($this->userid, 'first_name', true). ' ' . get_user_meta($this->userid, 'last_name', true);
                $subject = __('Profile Information Update','epic');
                $message = sprintf(__('%s has updated profile information.','epic'), $full_name) . "\r\n\r\n";
                $message .= sprintf(__('Please find the updated information below.','epic'), $full_name) . "\r\n\r\n";

                foreach ($this->changed_fields as $key => $value) {
                    $message .= __('Field Key','epic'). "   :" . $value['meta']. "\r\n";
                    $message .= __('Previous Value','epic'). "   :" . $value['prev_value']. "\r\n";
                    $message .= __('Updated Value','epic'). "   :" . $value['new_value']. "\r\n\r\n";
                }

                $message .= __('Thanks','epic') . "\r\n";
                $message .= sprintf(__('%s'), get_option('blogname'),'epic') . "\r\n";
   
                
                global $epic_email_templates,$epic_roles;
                $send_params = array('full_name' => $full_name, 'changed_fields' => $this->changed_fields);

                if($this->epic_settings['notifications_all_admins']){
                    $admin_emails_list = implode(',',$epic_roles->get_admin_emails());
                    $epic_email_templates->epic_send_emails('nofify_profile_update', $admin_emails_list,$subject,$message,$send_params,$this->userid);
                }else{
                    $epic_email_templates->epic_send_emails('nofify_profile_update', get_option('admin_email'),$subject,$message,$send_params,$this->userid);
                }


            }

        }
    }

}

$epic_save = new epic_Save();