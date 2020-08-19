<?php

add_filter( 'wpseo_canonical','epic_wpseo_canonical');

function epic_wpseo_canonical($canonical){
    global $post;
    
    $current_page_url = $canonical;
        
    $epic_options = get_option('epic_options');
    $profile_page_id = isset($epic_options['profile_page_id']) ? $epic_options['profile_page_id'] : '0';
    if(isset($post->ID) && $post->ID == $profile_page_id && $profile_page_id != '0'){
        $current_page_url = epic_current_page_url();

        $parsed_url = parse_url($current_page_url);
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';

        $current_page_url = $scheme.$user.$pass.$host.$port.$path;
    }
	return $current_page_url;
}



function epic_mail_from($old) {
    $current_option = get_option('epic_options');
    return $current_option['email_from_address'];
}
function epic_mail_from_name($old) {
    $current_option = get_option('epic_options');
    return $current_option['email_from_name'];
}


add_filter('epic_can_hide_custom_filter_status','epic_can_hide_custom_filter_status',10,2);
function epic_can_hide_custom_filter_status($status, $params){
    global $epic_roles;
    extract($params);

    
    if($can_hide == '5'){  
        if(is_user_logged_in()){
            $current_user_id = get_current_user_id();
            if($current_user_id == $user_id){
                $status = TRUE;
            }else{
                $can_hide_role_list = isset($can_hide_role_list)? $can_hide_role_list : '';
               
                $status = $epic_roles->epic_hide_fields_by_user_role_status($current_user_id,$can_hide_role_list);

            }
            
        }else{
            $status = FALSE;
        }
    }

    
    return $status;
}