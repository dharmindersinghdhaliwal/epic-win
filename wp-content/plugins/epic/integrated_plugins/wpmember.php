<?php

/* Implementation of actions and filters for adding compatiblity
 * with WP-Member plugin
 *
 * Note : Not supported for wpmember offline payments
 */


function epic_validate_wpmember(){
        if( defined('WPMEMBER_PLUGIN_URL') ) {
                return true;
        }else{
                return false;
        }
}

/* Listening to changes in wpmember user status*/
// $listen_member_status_change_params = array('action' => 'update','status' => 'expired');
add_action('wpmember_listen_member_status_change','epic_wpmember_listen_member_status_change',10,2);
function epic_wpmember_listen_member_status_change($user_id,$params){

        if( !epic_validate_wpmember() )
                return;

        extract($params);
        if($action == 'update'){
                if($status == 'active'){
                        update_user_meta( $user_id, 'epic_wpmember_profile_status', 'ACTIVE' );

                        update_user_meta( $user_id, 'epic_activation_status', "ACTIVE");
                        update_user_meta( $user_id, 'epic_approval_status', 'ACTIVE' );
                        update_user_meta( $user_id, 'epic_user_profile_status', 'ACTIVE' );
                }else{
                        update_user_meta( $user_id, 'epic_wpmember_profile_status', 'INACTIVE' );
                }

        }else if($action == 'delete'){
                update_user_meta( $user_id, 'epic_wpmember_profile_status', 'INACTIVE' );
        }
}
/* End Action */

add_action( 'wpmember_user_register','epic_wpmember_user_register',10,2);
function epic_wpmember_user_register($user_id, $args){
        
        if( !epic_validate_wpmember() )
                return;

        $user_info = get_userdata($user_id);
        update_user_meta($user_id, 'first_name', $user_info->first_name);
        update_user_meta($user_id, 'last_name', $user_info->last_name);
        update_user_meta($user_id, 'display_name', $user_info->first_name.' '.$user_info->last_name);

        update_user_meta( $user_id, 'epic_activation_status', "ACTIVE");
        update_user_meta( $user_id, 'epic_approval_status', 'ACTIVE' );
        update_user_meta( $user_id, 'epic_user_profile_status', 'ACTIVE' );

        update_user_meta( $user_id, 'epic_wpmember_profile_status', 'INACTIVE' );
        epic_update_user_cache($user_id);
        
}

add_filter('epic_validate_profile_visibility','epic_wpmember_validate_profile_visibility');
function epic_wpmember_validate_profile_visibility($params){
    $args = $params;
    extract($args);

    if( !epic_validate_wpmember() )
                return $params;

    if( 'INACTIVE' == get_user_meta($user_id, 'epic_wpmember_profile_status', true) && !user_can($user_id,'manage_options')){
        $params['status'] = false;
        $params['info'] = __('Please purchase a package to continue. Click ','epic'). '<a href="' . get_edit_user_link($user_id) . '">' . __('Here','epic') . '</a>';
    }

    return $params;
}

add_action('epic_before_login_restrictions','epic_wpmember_before_login_restrictions', 10 ,2);
function epic_wpmember_before_login_restrictions($usermeta, $params){
    global $epic_login;

    $user_data = get_user_by( 'login', $usermeta['user_login'] );
    if(!$user_data){
        $user_id = 0;
    }else{
        $user_id = $user_data->ID;
    }

    
    if( get_user_meta( $user_id, 'wpmember_confirmation_key', true ) != false ){
        $epic_login->errors[] = __('You need to confirm your email address.','epic');
    }   
}
