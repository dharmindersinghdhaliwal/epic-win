<?php

class epic_Profile{

    public $epic_settings;
    function __construct() {
        add_filter( 'epic_profile_tabbed_sections', array($this, 'profile_tabbed_sections') ,10,2);
        
        
        $this->epic_settings = get_option('epic_options');
        
        add_action('wp_ajax_epic_profile_delete_request', array($this, 'profile_delete_request'));
        
        add_action( 'init', array($this,'process_profile_delete_confirmation'));
    }
    
    public function  profile_tabbed_sections($display,$params){
        global $epic_template_loader,$epic_profile_tabs_params,$epic;
        
        extract($params);
        
        $profile_tab_status = $this->epic_settings['profile_tabs_display_status'];
        if('disabled' == $profile_tab_status){
            return '';
        }else if('enabled_members' == $profile_tab_status && !is_user_logged_in()){
            return '';
        }else if('enabled_owner' == $profile_tab_status && !$epic->can_edit_profile($epic->logged_in_user, $id) ){
            return '';
        }
        
        $epic_profile_tabs_params = $params;
        $epic_profile_tabs_params['initial_display'] = $this->epic_settings['profile_tabs_initial_display_status'];
        
        $tab_display_status = false;
	    if($group == '' && $view == ''){
            $tab_display_status = true;
        }
        
        $tab_display_status = apply_filters('epic_profile_tabs_display_status',$tab_display_status,$params);

        if($tab_display_status){

            ob_start();
            $epic_template_loader->get_template_part('profile-tabs');
            $display = ob_get_clean();
            return $display;
        }

        return $display;
    }
    
    
    
    public function profile_delete_request(){
        global $epic_email_templates;
        
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $profile_delete_hash = wp_generate_password(12,false);
        if($user_id != 0){
            
            $user = new WP_User( $user_id );
            $username = $user->user_login;  
            
            // Create profile delete confirmation link
            $link = get_permalink($this->epic_settings['login_page_id']);
            $query_str = "epic_action=epic_delete_profile&epic_id=" . $user_id . "&epic_profile_delete_code=" . $profile_delete_hash;
            $profile_delete_confirm_link = epic_add_query_string($link, $query_str);
            
            
            // Send email with profile remove confirmation link
            $send_params = array('profile_delete_confirm_link' => $profile_delete_confirm_link, 'username' => $username , 'email' => $user->user_email);
                    
            $admin_email_status = $epic_email_templates->epic_send_emails( 'delete_profile_confirm' , $user->user_email , '' , '' ,$send_params,$user_id);
            
            // Add random key to verify the delete profile confirmation link
            update_user_meta($user_id,'_epic_delete_profile_hash',$profile_delete_hash);
            echo json_encode(array('status' => 'success', 'msg' => __('Profile removal request success. We sent you an email with confirmation link. Please click the link to confirm the request.','epic')));
                             
                             
        }else{
            echo json_encode(array('status' => 'error', 'msg' => __('Profile removal request failed.','epic')));
        }
                             
        exit;
    }
    
    public function process_profile_delete_confirmation(){
        global $wpdb,$epic_login;
        
        if( isset( $_GET['epic_action'] ) && $_GET['epic_action'] == 'epic_delete_profile'){
            $user_id = isset($_GET['epic_id']) ? $_GET['epic_id'] : '';
            $epic_profile_delete_code = isset($_GET['epic_profile_delete_code']) ? $_GET['epic_profile_delete_code'] : '';
            
            $delete_code = get_user_meta($user_id,'_epic_delete_profile_hash',true);
            if($delete_code == $epic_profile_delete_code){
            
                $sql = $wpdb->prepare("DELETE FROM $wpdb->users WHERE ID = %d",$user_id);
                $wpdb->query($sql);
                $sql = $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id = %d",$user_id);
                $wpdb->query($sql);
                
                $epic_login->delete_profile_message = __('Profile deleted successfully.','epic');
                $epic_login->delete_profile_message_status  = 'epic-success';
            }else{
                $epic_login->delete_profile_message = __('Invalid profile delete request.','epic');
                $epic_login->delete_profile_message_status  = 'epic-errors';
            }
        }
    }

}

$epic_profile = new epic_Profile();