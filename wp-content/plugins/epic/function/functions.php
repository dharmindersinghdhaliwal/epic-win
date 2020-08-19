<?php
// General Functions for Plugin
if (!function_exists('epic_is_post')) {

    function epic_is_post() {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post')
            return true;
        else
            return false;
    }

}

if (!function_exists('epic_is_in_post')) {

    function epic_is_in_post($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if (isset($_POST[$key])) {
                if ($val == '')
                    return true;
                else if ($_POST[$key] == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if (!function_exists('epic_is_get')) {

    function epic_is_get() {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'get')
            return true;
        else
            return false;
    }

}


if (!function_exists('epic_is_in_get')) {

    function epic_is_in_get($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if (isset($_GET[$key])) {
                if ($val == '')
                    return true;
                else if ($_GET[$key] == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if (!function_exists('epic_not_null')) {

    function epic_not_null($value) {
        if (is_array($value)) {
            if (sizeof($value) > 0)
                return true;
            else
                return false;
        }
        else {
            if ((is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0))
                return true;
            else
                return false;
        }
    }

}



if (!function_exists('epic_get_value')) {

    function epic_get_value($key='') {
        if ($key != '') {
            if (isset($_GET[$key]) && epic_not_null($_GET[$key])) {
                if (!is_array($_GET[$key]))
                    return trim($_GET[$key]);
                else
                    return $_GET[$key];
            }

            else
                return '';
        }
        else
            return '';
    }

}


if (!function_exists('epic_post_value')) {

    function epic_post_value($key='') {
        if ($key != '') {
            if (isset($_POST[$key]) && epic_not_null($_POST[$key])) {
                if (!is_array($_POST[$key]))
                    return trim($_POST[$key]);
                else
                    return $_POST[$key];
            }
            else
                return '';
        }
        else
            return '';
    }

}


if (!function_exists('epic_is_opera')) {

    function epic_is_opera() {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return preg_match('/opera/i', $user_agent);
    }

}

if (!function_exists('epic_is_safari')) {

    function epic_is_safari() {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return (preg_match('/safari/i', $user_agent) && !preg_match('/chrome/i', $user_agent));
    }

}

// Check with the magic quotes functionality Start
function stripslashess(&$item) {
    $item = stripslashes($item);
}

if (get_magic_quotes_gpc ()) {
    array_walk_recursive($_GET, 'stripslashess');
    array_walk_recursive($_POST, 'stripslashess');
    array_walk_recursive($_SERVER, 'stripslashess');
}


if (!function_exists('remove_script_tags')) {

    function remove_script_tags($text) {
        $text = str_ireplace("<script>", "", $text);
        $text = str_ireplace("</script>", "", $text);

        return $text;
    }

}

if (!function_exists('epic_date_format_to_standerd')) {

    function epic_date_format_to_standerd($date, $format) {

        switch ($format) {
            case 'mm/dd/yy':
                $new_format = 'm/d/Y';
                break;

            case 'yy/mm/dd':
                $new_format = 'Y/m/d';
                break;

            case 'dd/mm/yy':
                $new_format = 'd/m/Y';
                break;

            case 'yy-mm-dd':
                $new_format = 'Y-m-d';
                break;

            case 'dd-mm-yy':
                $new_format = 'd-m-Y';
                break;

            case 'mm-dd-yy':
                $new_format = 'm-d-Y';
                break;

            case 'MM d, yy':
                $new_format = 'F d, Y';
                break;

            case 'd M, y':
                $new_format = 'd M, y';
                break;

            case 'd MM, y':
                $new_format = 'd F, y';
                break;

            case 'DD, d MM, yy':
                $new_format = 'l, d F, Y';
                break;

            default:
                $new_format = 'm/d/Y';
                break;
        }

        if(function_exists('date_create_from_format')){
            $date = date_create_from_format($new_format, $date);
            $date = date_format($date, 'm/d/Y');
        }
        
        return $date;
    }

}

if (!function_exists('epic_date_format_to_custom')) {

    function epic_date_format_to_custom($date, $format) {

        switch ($format) {
            case 'mm/dd/yy':
                $date = new DateTime($date);
                $datetime = $date->format("m/d/Y");
                break;

            case 'yy/mm/dd':
                $date = new DateTime($date);
                $datetime = $date->format("Y/m/d");
                break;

            case 'dd/mm/yy':
                $date = new DateTime($date);
                $datetime = $date->format("d/m/Y");
                break;

            case 'yy-mm-dd':
                $date = new DateTime($date);
                $datetime = $date->format("Y-m-d");
                break;

            case 'dd-mm-yy':
                $date = new DateTime($date);
                $datetime = $date->format("d-m-Y");
                break;

            case 'mm-dd-yy':
                $date = new DateTime($date);
                $datetime = $date->format("m-d-Y");
                break;

            case 'MM d, yy':
                $date = new DateTime($date);
                $datetime = $date->format("F j, Y");
                break;

            case 'd M, y':
                $date = new DateTime($date);
                $datetime = $date->format("j M, y");
                break;

            case 'd MM, y':
                $date = new DateTime($date);
                $datetime = $date->format("j F, y");
                break;

            case 'DD, d MM, yy':
                $date = new DateTime($date);
                $datetime = $date->format("l, j F, Y");
                break;

            default:
                $date = new DateTime($date);
                $datetime = $date->format("m/d/Y");
                break;
        }

        return $datetime;
    }

}


if (!function_exists('epic_get_uploads_folder_details')) {

    function epic_get_uploads_folder_details() {

        // Checking for valid uploads folder
        if (!( $upload_dir = wp_upload_dir() ))
            return false;

        return $upload_dir;
    }

}

if (!function_exists('epic_manage_string_for_meta')) {

    function epic_manage_string_for_meta($string='') {
        $badChars = array(' ', ',', '$', '&', '\'', ':', '<', '>', '[', ']', '{', '}', '#', '%', '@', '/', ';', '=', '?', '\\', '^', '|', '~', '(', ')', '"', '.');
        $string = str_replace($badChars, '_', trim($string));
        $string = trim($string, '_');
        $string = str_replace('___', '_', trim($string));
        $string = str_replace('__', '_', trim($string));
        return strtolower($string);
    }

}

if (!function_exists('epic_update_user_cache')) {

    function epic_update_user_cache($user_id) {
        global $wpdb;

        $meta_values_query = "SELECT meta_key, meta_value FROM " . $wpdb->usermeta . " WHERE meta_key!='_epic_search_cache' AND user_id=" . esc_sql($user_id);

        $meta_data = $wpdb->get_results($meta_values_query, 'ARRAY_A');


        $profile_fields = get_option('epic_profile_fields');

        $epic_fields_meta = array();
        foreach ($profile_fields as $key => $value) {
            if ($value['type'] == 'usermeta') {

                $epic_fields_meta[] = $value['meta'];
            }
        }

        $search_cache = array();

        foreach ($meta_data as $k => $v) {
            if ($v['meta_key'] == $wpdb->get_blog_prefix() . "capabilities") {
                $roles = unserialize($v['meta_value']);
                foreach ($roles as $role_key => $role_value) {
                    $search_cache[] = 'role::' . $role_key;
                }
            } 
            else if('user_pass' == $v['meta_key'] || 'user_pass_confirm' == $v['meta_key'] ){
                // Skip these fields for search cache
            }
            else if('epic_user_profile_status' == $v['meta_key'] || 'epic_approval_status' == $v['meta_key'] || 'epic_activation_status' == $v['meta_key']){
                // Add user statuses to cache to prevent showing in search results
                $search_cache[] = $v['meta_key'] . '::' . $v['meta_value'];
            }
            else {

                if (in_array($v['meta_key'], $epic_fields_meta)) {
                    if ($v['meta_value'] == '' || $v['meta_value'] == '0') {
                        $search_cache[] = $v['meta_key'] . '::' . $v['meta_value'];
                    } else {
                        $multi_data = explode(',', $v['meta_value']);
                        foreach ($multi_data as $data_key => $data_value) {
                            $search_cache[] = $v['meta_key'] . '::' . trim($data_value);
                        }
                    }
                }
            }
        }

        $user = get_user_by( 'id', $user_id );
        $search_cache[] = 'username::' . trim($user->data->user_login);
        
        $search_cache_string = '';
        $search_cache_string = implode('||', $search_cache);

        update_user_meta($user_id, '_epic_search_cache', $search_cache_string);
    }

}

if (!function_exists('epic_cron_user_cache')) {

    function epic_cron_user_cache() {
        global $wpdb;

        $current_option = get_option('epic_options');

        // Execute Only if set to yes
        if (isset($current_option['use_cron']) && $current_option['use_cron'] == '1') {
            $last_processed_user = get_option('epic_cron_processed_user_id');
            if ($last_processed_user == '') {
                $last_processed_user = 0;
            }

            $limit = 25;

            $user_query = "SELECT ID FROM " . $wpdb->users . " WHERE ID>'" . esc_sql($last_processed_user) . "' ORDER BY ID ASC LIMIT " . $limit;

            $users = $wpdb->get_results($user_query, 'ARRAY_A');

            $count = 0;
            foreach ($users as $key => $value) {
                epic_update_user_cache($value['ID']);

                update_option('epic_cron_processed_user_id', $value['ID']);

                $count++;
            }

            // All users completed, so resetting value to 0
            if ($count < $limit) {
                update_option('epic_cron_processed_user_id', '0');
            }
        }
    }

}

if (!function_exists('epic_activation')) {

    function epic_activation() {
        if (!wp_next_scheduled('epic_process_cache_cron')) {
            wp_schedule_event(time(), 'hourly', 'epic_process_cache_cron');
        }
    }

}

if (!function_exists('epic_deactivation')) {

    function epic_deactivation() {
        wp_clear_scheduled_hook('epic_process_cache_cron');
    }

}

if (!function_exists('epic_video_url_customizer')) {

    function epic_video_url_customizer($url) {
        $url_parts = parse_url($url);
        if ($url_parts) {
            $host = isset($url_parts['host']) ? $url_parts['host'] : '';
            $query = isset($url_parts['query']) ? $url_parts['query'] : '';
            $path = isset($url_parts['path']) ? $url_parts['path'] : '';
            $player_url = '';
            if ('www.youtube.com' == $host) {
                $player_url = epic_youtube_url_customizer($query);
            } else if ('vimeo.com' == $host) {
                $player_url = epic_vimeo_url_customizer($path);
            } else if('youtu.be' == $host){
                $player_url = epic_youtube_short_url_customizer($path);
            }
            return $player_url;
        } else {
            return false;
        }
    }

}

if (!function_exists('epic_vimeo_url_customizer')) {

    function epic_vimeo_url_customizer($path) {
        $player_url = '//player.vimeo.com/video' . $path;
        return $player_url;
    }

}


if (!function_exists('epic_youtube_url_customizer')) {

    function epic_youtube_url_customizer($query) {

        $query_parts = explode('=', $query);
        $video_str = isset($query_parts[1]) ? $query_parts[1] : '';
        $player_url = '//www.youtube.com/embed/' . $video_str;
        return $player_url;
    }
}

if (!function_exists('epic_video_type_css')) {

    function epic_video_type_css($url) {
        $url_parts = parse_url($url);
        $player_details = array();
        $player_details['height'] = '281';
        $player_details['width'] = '500';
        if ($url_parts) {
            $host = isset($url_parts['host']) ? $url_parts['host'] : '';

            if ('www.youtube.com' == $host) {
                $player_details['height'] = '315';
                $player_details['width'] = '560';
            } else if ('vimeo.com' == $host) {
                $player_details['height'] = '281';
                $player_details['width'] = '500';
            }
            return $player_details;
        } else {
            return $player_details;
        }
    }

}




if (!function_exists('epic_add_query_string')) {

    function epic_add_query_string($link, $query_str) {

        $build_url = $link;

        $query_comp = explode('&', $query_str);

        foreach ($query_comp as $param) {
            $params = explode('=', $param);
            $key = isset($params[0]) ? $params[0] : '';
            $value = isset($params[1]) ? $params[1] : '';
            $build_url = esc_url_raw(add_query_arg($key, $value, $build_url));
        }

        return $build_url;
    }

}


if (!function_exists('epic_date_picker_setting')) {

    function epic_date_picker_setting() {
        // Set date format from admin settings
        $epic_settings = get_option('epic_options');
        $epic_date_format = (string) isset($epic_settings['date_format']) ? $epic_settings['date_format'] : 'mm/dd/yy';

        $date_picker_array = array(
            'closeText' => __('Done','epic'),
            'prevText' => __('Prev','epic'),
            'nextText' => __('Next','epic'),
            'currentText' => __('Today','epic'),
            'monthNames' => array(
                'Jan' => __('January','epic'),
                'Feb' => __('February','epic'),
                'Mar' => __('March','epic'),
                'Apr' => __('April','epic'),
                'May' => __('May','epic'),
                'Jun' => __('June','epic'),
                'Jul' => __('July','epic'),
                'Aug' => __('August','epic'),
                'Sep' => __('September','epic'),
                'Oct' => __('October','epic'),
                'Nov' => __('November','epic'),
                'Dec' => __('December','epic')
            ),
            'monthNamesShort' => array(
                'Jan' => __('Jan','epic'),
                'Feb' => __('Feb','epic'),
                'Mar' => __('Mar','epic'),
                'Apr' => __('Apr','epic'),
                'May' => __('May','epic'),
                'Jun' => __('Jun','epic'),
                'Jul' => __('Jul','epic'),
                'Aug' => __('Aug','epic'),
                'Sep' => __('Sep','epic'),
                'Oct' => __('Oct','epic'),
                'Nov' => __('Nov','epic'),
                'Dec' => __('Dec','epic')
            ),
            'dayNames' => array(
                'Sun' => __('Sunday','epic'),
                'Mon' => __('Monday','epic'),
                'Tue' => __('Tuesday','epic'),
                'Wed' => __('Wednesday','epic'),
                'Thu' => __('Thursday','epic'),
                'Fri' => __('Friday','epic'),
                'Sat' => __('Saturday','epic')
            ),
            'dayNamesShort' => array(
                'Sun' => __('Sun','epic'),
                'Mon' => __('Mon','epic'),
                'Tue' => __('Tue','epic'),
                'Wed' => __('Wed','epic'),
                'Thu' => __('Thu','epic'),
                'Fri' => __('Fri','epic'),
                'Sat' => __('Sat','epic')
            ),
            'dayNamesMin' => array(
                'Sun' => __('Su','epic'),
                'Mon' => __('Mo','epic'),
                'Tue' => __('Tu','epic'),
                'Wed' => __('We','epic'),
                'Thu' => __('Th','epic'),
                'Fri' => __('Fr','epic'),
                'Sat' => __('Sa','epic')
            ),
            'weekHeader' => __('Wk','epic'),
            'dateFormat' => $epic_date_format,
            'yearRange'  => '1920:2020'
        );

        /* epic Filter for customizing date picker settings */
        $date_picker_array = apply_filters('epic_datepicker_settings', $date_picker_array);
        // End Filter

        return $date_picker_array;
    }

}

if(!function_exists('epic_default_socail_links')) {
    function epic_default_socail_links() {
        add_filter('epic_social_url_user_email', 'epic_format_email_link');
        add_filter('epic_social_url_twitter', 'epic_format_twitter_link');
        add_filter('epic_social_url_facebook', 'epic_format_facebook_link');
        add_filter('epic_social_url_googleplus', 'epic_format_google_link');
    }
}

// Hooking default social url
epic_default_socail_links();


if(!function_exists('epic_format_email_link')) {
    function epic_format_email_link($content){
        return 'mailto:'.$content;
    }
}

if(!function_exists('epic_format_twitter_link')) {
    function epic_format_twitter_link($content){
        return 'http://twitter.com/'.$content;
    }
}

if(!function_exists('epic_format_facebook_link')) {
    function epic_format_facebook_link($content){
        return 'http://www.facebook.com/'.$content;
    }
}

if(!function_exists('epic_format_google_link')) {
    function epic_format_google_link($content){
        return 'https://plus.google.com/'.$content;
    }
}

if (!function_exists('epic_sound_cloud_player')) {

    function epic_sound_cloud_player($url){
        $width = '100%';

        $soundcloud_player_url = 'https://w.soundcloud.com/player/?url='.$url;

        $soundcloud_params = array('color' => 'ff5500',
                                    'auto_play' =>  'false',
                                    'hide_related' => 'false',
                                    'show_artwork' => 'false'
                                );

        $height = (preg_match('/^(.+?)\/(sets|groups|playlists)\/(.+?)$/', $soundcloud_player_url) ) ? '400px' : '150px';

        $soundcloud_player_url = esc_url(add_query_arg( $soundcloud_params, $soundcloud_player_url));

        return sprintf('<iframe width="%s" height="%s" scrolling="no" frameborder="no" src="%s"></iframe>', $width, $height, $soundcloud_player_url);
    }

}

if (!function_exists('epic_youtube_short_url_customizer')) {

    function epic_youtube_short_url_customizer($path) {
        $player_url = '//www.youtube.com/embed' . $path;
        return $player_url;
    }
}


if (!function_exists('epic_admin_approval_notification')) {

    function epic_admin_approval_notification($user_id,$link) {
        global $epic_email_templates;

        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = __('Your account has been approved successfully. ','epic') . "\r\n\r\n";
        
        $message .= sprintf(__('Username: %s','epic'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s','epic'), $user_email) . "\r\n";

        $message .= __('You can now log in to use your account using the following link.','epic') . "\r\n\r\n";
        $message .= sprintf('%s', $link) . "\r\n\r\n";
        $message .= __('Thanks','epic') . "\r\n";

        /* epic Filter for customizing user approval email content  */
        $message  = apply_filters('epic_new_user_admin_approval_content',$message,$user_login,$user_email);
        // End Filter

        $subject  = sprintf(__('[%s] User Account Approved','epic'), get_option('blogname'));
        /* epic Filter for customizing user approval email subject  */
        $subject  = apply_filters('epic_new_user_admin_approval_subject',$subject);
        // End Filter  

        $send_params = array('email' => $user_email, 'username' => $user_login, 'login_link' => $link);
        $email_status = $epic_email_templates->epic_send_emails('approval_notify_user', $user_email ,$subject,$message,$send_params,$user_id);
 
        // @wp_mail(
        //     $user_email,
        //     $subject,
        //     $message
        // );

        
    }

}


if (!function_exists('epic_form_validate_setting')) {

    function epic_form_validate_setting() {
        
        $epic_settings = get_option('epic_options');

        $validate_strings = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ErrMsg' => array(
                'similartousername' => __('Your password is too similar to your username.', 'epic'),
                'mismatch' => __('Both passwords do not match.', 'epic'),
                'tooshort' => __('Your password is too short.', 'epic'),
                'veryweak' => __('Your password strength is too weak.', 'epic'),
                'weak' => __('Your password strength is weak.', 'epic'),
                'usernamerequired' => __('Please provide username.', 'epic'),
                'emailrequired' => __('Please provide email address.', 'epic'),
                'validemailrequired' => __('Please provide valid email address.', 'epic'),
                'usernameexists' => __('That username is already taken, please try a different one.', 'epic'),
                'emailexists' => __('The email you entered is already registered. Please try a new email or log in to your existing account.', 'epic')
            ),
            'MeterMsg' => array(
                'similartousername' => __('Your password is too similar to your username.', 'epic'),
                'mismatch' => __('Both passwords do not match.', 'epic'),
                'tooshort' => __('Your password is too short.', 'epic'),
                'veryweak' => __('Very weak', 'epic'),
                'weak' => __('Weak', 'epic'),
                'medium' => __('Medium', 'epic'),
                'good' => __('Good', 'epic'),
                'strong' => __('Strong', 'epic')
            ),
            'Err' => __('ERROR', 'epic'),
            'PasswordStrength' => $epic_settings['enforce_password_strength'],
            'MinPassStrength' => __('Minimum password strength level should be', 'epic'),
            'FieldRequiredText' => __(' is required.','epic'),
            'NewPasswordMsg' => __(' New password is required.','epic'),
            'ConfirmPassMsg' => __(' Confirm new password is required.','epic'),
        );



        /* epic Filter for customizing form validate settings */
        $validate_strings = apply_filters('epic_form_validate_settings', $validate_strings);
        // End Filter

        return $validate_strings;
    }

}

if (!function_exists('epic_tinymce_language_setting')) {

    function epic_tinymce_language_setting() {

                                 
        $lang_strings = array(
            'InsertepicShortcode'           => __('Insert epic Shortcode','epic'),
            'LoginRegistrationForms'        => __('Login / Registration Forms','epic'),
            'FrontRegistrationForm'         => __('Front-end Registration Form','epic'),
            'RegFormCustomRedirect'         => __('Registration Form with Custom Redirect','epic'),
            'RegFormCaptcha'                => __('Registration Form with Captcha','epic'),
            'RegFormNoCaptcha'              => __('Registration Form without Captcha','epic'),
            'FrontLoginForm'                => __('Front-end Login Form','epic'),
            'SidebarLoginWidget'            => __('Sidebar Login Widget (use in text widget)','epic'),
            'LoginFormCustomRedirect'       => __('Login Form with Custom Redirect','epic'),
            'LogoutButton'                  => __('Logout Button','epic'),
            'LogoutButtonCustomRedirect'    => __('Logout Button with Custom Redirect','epic'),
            'SingleProfile'                 => __('Single Profile','epic'),
            'LoggedUserProfile'             => __('Logged in User Profile','epic'),
            'LoggedUserProfileUserID'       => __('Logged in User Profile showing User ID','epic'),

            'LoggedUserProfileHideStats'    => __('Logged in User Profile without Stats','epic'),
            'LoggedUserProfileUserRole'     => __('Logged in User Profile showing User Role','epic'),
            'LoggedUserProfileStatus'       => __('Logged in User Profile showing Profile Status','epic'),
            'LoggedUserProfileLogoutRedirect' => __('Logged in User Profile with Logout Redirect','epic'),

            'PostAuthorProfile'             => __('Post Author Profile','epic'),
            'SpecificUserProfile'           => __('Specific User Profile','epic'),
            'MultipleProfilesMemberList'    => __('Multiple Profiles / Member List','epic'),
            'GroupSpecificUsers'            => __('Group of Specific Users','epic'),
            'AllUsers'                      => __('All Users','epic'),
            'AllUsersCompactView'           => __('All Users in Compact View','epic'),
            'AllUsersCompactViewHalfWidth'  => __('All Users in Compact View, Half Width','epic'),
            'AllUsersModalWindow'           => __('All Users in Modal Windows','epic'),
            'AllUsersNewWindow'           => __('All Users in New Windows','epic'),
            'UsersBasedUserRole'            => __('Users Based on User Role','epic'),
            'AdministratorUsersOnly'        => __('Administrator Users Only','epic'),
            'AllUsersOrderedDisplayName'    => __('All Users Ordered by Display Name','epic'),
            'AllUsersOrderedPostCount'      => __('All Users Ordered by Post Count','epic'),
            'AllUsersOrderedRegistrationDate' => __('All Users Ordered by Registration Date','epic'),
            'AllUsersOrderedCustomField'    => __('All Users Ordered by Custom Field','epic'),
            'AllUsersUserID'                => __('All Users showing User ID','epic'),  
            'GroupUsersCustomField'         => __('Group Users by Custom Field Values','epic'),          
            'HideUsersUntilSearch'          => __('Hide All Users until Search','epic'),
            'SearchProfile'                 => __('Search Profiles','epic'),
            'SearchCustomFieldFilters'      => __('Search with Custom Field Filters','epic'),
            'PrivateContentLoginRequired'   => __('Private Content (Login Required)','epic'),
            'ShortcodeOptionExamples'       => __('Shortcode Option Examples','epic'),
            'HideUserStatistics'            => __('Hide User Statistics','epic'),
            'HideUserSocialBar'             => __('Hide User Social Bar','epic'),
            'HalfWidthProfileView'          => __('1/2 Width Profile View','epic'),
            'CompactViewNoExtraFields'      => __('Compact View (No extra fields)','epic'),
            'CustomizedProfileFields'       => __('Customized Profile Fields','epic'),
            'ShowUserIDProfiles'            => __('Show User ID on Profiles','epic'),
            'LimitResultsMemberList'        => __('Limit Results on Member List','epic'),
            'ShowResultCountMemberList'     => __('Show Result Count on Member List','epic'),

        );

        return $lang_strings;
    }

}



if (!function_exists('epic_current_page_url')) {

    function epic_current_page_url() {
      $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }

}

if (!function_exists('epic_delete_uploads_folder_files')) {

    function epic_delete_uploads_folder_files($image_url) {

        if ($upload_dir = epic_get_uploads_folder_details()) {

            $image_folder_link = $upload_dir['baseurl'] . "/epic/";
            $image_name = str_replace($image_folder_link, '', $image_url);

            $epic_upload_path = $upload_dir['basedir'] . "/epic/";
            if(unlink($epic_upload_path . $image_name)){
                return true;
            }
        }

        return false;
    }

}


if (!function_exists('epic_stripslashes_deep')) {

    function epic_stripslashes_deep($value){
        // $value = is_array($value) ?
        //             array_map('stripslashes_deep', $value) :
        //             stripslashes($value);

        return $value;
    }

}

if (!function_exists('epic_match_template_tags')) {
    function epic_match_template_tags($template_name, $text, $allowed_tags, $params,$user_id = ''){

        $allowed_profile_tags = array();
        $profile_fields = get_option('epic_profile_fields');
        foreach ($profile_fields as $key => $value) {
            array_push($allowed_profile_tags, $value['meta']);
        }

        $allowed_email_profile_tags_params = array('name' => $template_name, 'user_id' => $user_id);
        $allowed_profile_tags = apply_filters('epic_allowed_email_profile_tags', $allowed_profile_tags, $allowed_email_profile_tags_params);

        preg_match_all("/{%(.*?)%}/",$text, $matches, PREG_PATTERN_ORDER);

        if(is_array($matches) && isset($matches[1])){
            foreach ($matches[1] as $key => $tag) {

                $replacement = '';
                if(in_array($tag, $allowed_tags)){
                    switch($tag){
                        case 'blog_name';
                            $replacement = get_option('blogname');
                            break;
                        case 'reset_page_url';
                            $replacement = $params['reset_page_url'];
                            break;
                        case 'network_home_url';
                            $replacement = network_home_url( '/' );
                            break;
                        case 'login_link';
                            $replacement = $params['login_link'];
                            break;
                        case 'username';
                            $replacement = $params['username'];
                            break;
                        case 'password';
                            $replacement = $params['password'];
                            break;
                        case 'email';
                            $replacement = $params['email'];
                            break;
                        case 'full_name';
                            $replacement = $params['full_name'];
                            break;
                        case 'activation_link';
                            $replacement = $params['activation_link'];
                            break;
                        case 'email_two_factor_login_link';
                            $replacement = $params['email_two_factor_login_link'];
                            break;
                        case 'approval_link_backend';
                            $replacement = admin_url('users.php');
                            break;
                        case 'changed_fields';
                            $replacement = '';
                            foreach ($params['changed_fields'] as $key => $value) {
                                $replacement .= __('Field Key','epic'). "   :" . $value['meta']. "\r\n";
                                $replacement .= __('Previous Value','epic'). "   :" . $value['prev_value']. "\r\n";
                                $replacement .= __('Updated Value','epic'). "   :" . $value['new_value']. "\r\n\r\n";
                            }
                            break;
                        case 'profile_delete_confirm_link';
                            $replacement = $params['profile_delete_confirm_link'];
                    }
                }else{
                    $email_conditional_message_parts_params = array('tag' => $tag, 'params' => $params,
                                                                   'user_id' => $user_id );
                    $replacement = apply_filters('epic_email_conditional_message_parts', '', $email_conditional_message_parts_params);

                }

                if( in_array($tag, $allowed_profile_tags) && '' != $user_id ){
                    $replacement = get_user_meta($user_id, $tag, true);
                }

                $text = str_replace('{%'.$tag.'%}', "$replacement", $text);
            }
        }
        return $text;
    }

}

if (!function_exists('epic_get_gravatar_url')) {
    function epic_get_gravatar_url( $email ) {
        $hash = md5( strtolower( trim ( $email ) ) );
        return 'http://gravatar.com/avatar/' . $hash;
    }
}

if (!function_exists('epic_addons_feed')) {
    function epic_addons_feed() {
        global $epic_template_loader,$epic_addon_template_data;

        $epic_addon_template_data['active_plugins'] = get_option('active_plugins');
        
        $addons_json = wp_remote_get( 'http://www.epicaddons.innovativephp.com/addons.json');  
        
//$addons_json = wp_remote_get( 'http://d3t0oesq8995hv.cloudfront.net/woocommerce-addons.json', array( 'user-agent' => 'WooCommerce Addons Page' ) );

        if ( ! is_wp_error( $addons_json ) ) {

            $addons = json_decode( wp_remote_retrieve_body($addons_json) );
            $addons = $addons->featured;

            


            
        }else{
            $addons = array();             
            $addons['invitation_codes'] = array(
                                        'title'     => 'epic Invitation Codes',
                                        'image'     => 'http://www.epicaddons.innovativephp.com/wp-content/uploads/2014/12/invitationcodes.png',
                                        'desc'      => 'epic Invitation Codes can be used to only allow registration for invited users. Admin can send invitations to email addresses.Registration will be blocked for normal users without valid code. You can create unlimited codes with limited number of users. Invitation codes can be enabled/disabled at any time using settings.',
                                        'name'      => 'epic-invitation-codes/epic-invitation-codes.php',
                                        'type'      => __('Free','epic'),
                                        'download'  => 'http://profileplugin.com/invitation-codes-addon/',

                                        );

            $addons['all_in_one'] = array(
                                        'title'     => 'epic All In One',
                                        'image'     => 'http://www.epicaddons.innovativephp.com/wp-content/uploads/2015/02/uaio.png',
                                        'desc'      => 'epic All In One is an addon created to provide addon features for User Profiles Made Easy. This addon is a combination of other addons as well as frequently added new features. Current version contains social logins, custom field types and profile tab management.',
                                        'name'      => 'epic-all-in-one/epic-all-in-one.php',
                                        'type'      => __('Premium','epic'),
                                        'download'  => 'http://www.epicaddons.innovativephp.com/epic-all-in-one/',

                                        );

            $addons['custom_field_types'] = array(
                                        'title'     => 'epic Custom Field Types',
                                        'image'     => 'http://www.epicaddons.innovativephp.com/wp-content/uploads/2014/11/epicft.png',
                                        'desc'      => 'epic Custom Field Types is an addon created to add custom field types for epic custom fields section. You need User Profiles Made Easy plugin to use this addon. This addon extends the default predefined field types by providing more variations to suit specific requirements of each site. Currently it offers over 10 new field types and over 15 variations.',
                                        'name'      => 'epic-field-types-addon/epic-field-types.php',
                                        'type'      => __('Premium','epic'),
                                        'download'  => 'http://www.epicaddons.innovativephp.com/epic-custom-field-types/',

                                        );

            $addons['social_login'] = array(
                                        'title'     => 'epic Social',
                                        'image'     => 'https://static-2.gumroad.com/res/gumroad/files/e096c7e44d214d08bcc6ebf098b42730/original/scl1.jpg',
                                        'desc'      => 'epic Social is an addon created to integrate social networking capabilities into epic profiles. Initial version contains the registration and login support for most popular social sites such as Facebook, Twitter and LinkedIn. More sites and social networking capabilities such as wall posts, friends, followers will be added in future versions',
                                        'name'      => 'epic_social_addon/epic-social.php',
                                        'type'      => __('Premium','epic'),
                                        'download'  => 'http://www.epicaddons.innovativephp.com/epic-social/',

                                        );
        }

        
        
        $epic_addon_template_data['addons'] = $addons;
        
        ob_start();
        $epic_template_loader->get_template_part('addons','feed');
        $display = ob_get_clean();
        echo $display;
    }
}


if (!function_exists('epic_get_default_email_address')) {
    function epic_get_default_email_address() {
        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@' . $sitename;
        return $from_email;
    }
}

if (!function_exists('epic_profile_visibility_info')) {

    function epic_profile_visibility_info($profile_visibility,$profile_title_display){
        global $epic;
        
        extract($profile_visibility);
		
        $display = '<div class="epic-profile-visibility-info ">
                        <div class="epic-left">
                            <div class="epic-pic">
                                ' . $epic->pic($user_id, 50) . '
                            </div>
                            <div class="epic-name">
                                <div class="epic-field-name">' . $profile_title_display  .'</div>
                            </div>
                        </div>
                        <div class="epic-clear"></div>
                        <div class="epic-visibility-message">'. $info .'</div>
                        <div class="epic-clear"></div>
                  </div>';
        return $display;
    }
}

if (!function_exists('epic_is_subpage')) {
    function epic_is_subpage() {
        global $post;                              

        if ( is_page() && $post->post_parent ) {   
            return $post->post_parent;             

        } else {                                   
            return false;                          
        }
    }
}

if (!function_exists('epic_top_ancestors')) {
    function epic_top_ancestors($parents,$post_id = '') {
        global $post;    
        $post_id = ($post_id != '') ? $post_id : $post->ID;
        $current = get_post($post_id);

        if ( is_page() && $current->post_parent ) {  
            array_push($parents,$current->post_parent);
            return epic_top_ancestors($parents,$current->post_parent);             

        } else {                                   
            return $parents;                          
        }
    }
}

if (!function_exists('epic_array_keys_to_values')) {
    function epic_array_keys_to_values($array){
        $converted_array = array();
        foreach($array as $k=>$v){
            if(is_array($v)){
                foreach($v as $k1=>$v1){
                    $converted_array[$v1] = $k;
                }
            }else{
                $converted_array[$v] = $k;
            }      
        }
        return $converted_array;
    }
}

if (!function_exists('epic_get_user_id_by_profile_url')) {
    function epic_get_user_id_by_profile_url(){
        global $epic,$wp_query,$epic_options;
        
        $current_option = $epic_options->epic_settings; 
        
        if (isset($_REQUEST['viewuser']) && $epic->user_exists($_REQUEST['viewuser']) ) {
            $id = $_REQUEST['viewuser'];
        } elseif (isset($_REQUEST['username']) ) {
            // View profiles by username in default permalinks

            $userdata = get_user_by('login', $_REQUEST['username']);
            if ($userdata != false) {
                $id = $userdata->data->ID;
            }

        } elseif (isset($wp_query->query_vars['epic_profile_filter'])) {

            // View profiles by username/user id in custom permalinks
            $epic_profile_filter_value = $wp_query->query_vars['epic_profile_filter'];
            $epic_profile_filter_value = str_replace('-at-', '@', urldecode($epic_profile_filter_value));

            if (isset($current_option['profile_url_type']) && 2 == $current_option['profile_url_type']) {

                $userdata = get_user_by('login', $epic_profile_filter_value);
                if ($userdata != false) {
                    $id = $userdata->data->ID;
                }
            } else {
                $id = $epic_profile_filter_value;
            }

        } else {
     
            // Current logged ins users profile being viewed
            $id = $epic->logged_in_user;
        }
        return $id;
    }
}