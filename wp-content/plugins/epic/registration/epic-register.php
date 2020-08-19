<?php

/* Hook into registration form */
add_action('register_form', 'epic_add_custom_fields');
//add_action('register_post','epic_validate_fields',10,3);
add_action('registration_errors', 'epic_validate_fields', 10, 3);
add_action('user_register', 'epic_save_extra_fields');

//Adds custom styling to the log-in/resgister/forgot password pages
add_action('login_head', 'epic_login_head');

function epic_add_custom_fields() {

    global $predefined;

    $fields = get_option('epic_profile_fields');

    $required_fields = array();

    foreach ($fields as $profile_field) {

        extract($profile_field);

        if ($type == 'separator')
            continue; /* Do not show separators */
        if ($field == 'password')
            continue; /* Do not allow passwords */
        if ($meta == 'user_email')
            continue; /* Duplicate remove */

        if (!isset($profile_field['show_in_register']) || $show_in_register == 0)
            continue; /* Only marked fields included */

        if (isset($profile_field['required']) && $profile_field['required'] == '1')
            $required_fields[] = $meta;

        print "<p>
        <label><br/>" . $name . "<br/>";

        /* Switch field type */

        $value = '';
        if (isset($_POST['epic'][$meta]))
            $value = $_POST['epic'][$meta];
        // text, textarea, select, radio, checkbox, datetime
        switch ($field) {

            case 'text':
                print '<input type="text" name="epic[' . $meta . ']" id="epic[' . $meta . ']" class="input" value="' . $value . '" size="25" />';
                break;

            case 'datetime':
                print '<input type="text" name="epic[' . $meta . ']" id="epic[' . $meta . ']" class="input epic-datepicker" value="' . $value . '" size="25" />';
                break;

            case 'textarea':
                $params = array('meta'=>$meta);
                $custom_editor_styles = apply_filters('epic_text_editor_styles','',$params);
                print '<textarea name="epic[' . $meta . ']" id="epic[' . $meta . ']" class="input epic-textarea '.$custom_editor_styles.' " size="20">' . stripslashes($value) . '</textarea>';
                break;
                
            case 'select':
                $loop = array();
                if (isset($profile_field['predefined_loop']) && $profile_field['predefined_loop'] != '' && $profile_field['predefined_loop'] != '0') {
                    $loop = $predefined->get_array($profile_field['predefined_loop']);
                } else if (isset($profile_field['choices']) && $profile_field['choices'] != '') {
                    $loop = explode(PHP_EOL, $profile_field['choices']);
                }
                $display = '';
                if (count($loop) > 0) {
                    $display .= '<select class="input" name="epic[' . $meta . ']" id="epic[' . $meta . ']">';
                    foreach ($loop as $option) {
                        $option = trim($option);

                        $display .= '<option value="' . $option . '" ' . selected($value, $option, 0) . '>' . $option . '</option>';
                    }
                    $display .= '</select>';
                }
                print $display;

                break;

            case 'radio':
                $display = '';
                if (isset($profile_field['choices'])) {
                    $loop = explode(PHP_EOL, $profile_field['choices']);
                }

                if (isset($loop) && $loop[0] != '') {
                    $counter = 0;
                    foreach ($loop as $option) {
                        if ($counter > 0)
                            $required_class = '';
                        // Added as per http://codecanyon.net/item/user-profiles-made-easy-wordpress-plugin/discussion/4109874?filter=All+Discussion&page=27#comment_4352415
                        $option = trim($option);
                        $display .= '<label class="epic-radio"><input type="radio" class="epic-register-radio" title="epic[' . $meta . ']" name="epic[' . $meta . ']" value="' . $option . '" ';

                        $display.=checked($value, $option, 0);

                        $display .= '/> ' . $option . '</label><br />';
                        $counter++;
                    }
                    unset($loop);
                }

                print $display;
                break;

            case 'checkbox':
                $display = '';
                if (isset($profile_field['choices'])) {
                    $loop = explode(PHP_EOL, $profile_field['choices']);
                }

                if (isset($loop) && $loop[0] != '') {
                    $counter = 0;
                    foreach ($loop as $option) {

                        if ($counter > 0)
                            $required_class = '';

                        // Added as per http://codecanyon.net/item/user-profiles-made-easy-wordpress-plugin/discussion/4109874?filter=All+Discussion&page=27#comment_4352415
                        $option = trim($option);
                        $display .= '<label class="epic-checkbox"><input type="checkbox" class="epic-register-checkbox" title="epic[' . $meta . ']" name="epic[' . $meta . '][]" value="' . $option . '" ';

                        if (is_array($value) && in_array($option, $value)) {
                            $display .= 'checked="checked"';
                        }

                        $display .= '/> ' . $option . '</label><br />';

                        $counter++;
                    }

                    unset($loop);
                }

                print $display;
                break;
        }

        print "</label>
        </p>";

        if (count($required_fields) > 0) {
            print '<input type="hidden" name="required_fields" value="' . implode(',', $required_fields) . '" />';
        }
    }
}

/* Validate extra fields */

function epic_validate_fields($errors, $login, $email) {

    // Getting post values which are required
    if (isset($_POST['required_fields']) && $_POST['required_fields'] != '') {
        $required_fields = explode(',', $_POST['required_fields']);

        foreach ($required_fields as $key => $value) {
            if (!isset($_POST['epic'][$value]) || (isset($_POST['epic'][$value]) && $_POST['epic'][$value] == '') || (isset($_POST['epic'][$value]) && is_array($_POST['epic'][$value]) && count($_POST['epic'][$value]) == '0')) {
                $errors->add('empty_required_fields', "Please complete all required fields.");

                // Jumping out of Loop because because atleast one required field is not entered and hence error
                break;
            }
        }
    }

    return $errors;
}

/* Save extra fields */

function epic_save_extra_fields($user_id, $password = "", $meta = array()) {

    if (isset($_POST['epic']) && is_array($_POST['epic'])) {
        $form = $_POST['epic'];

        foreach ($form as $key => $value) {

            if (is_array($value))
                $value = implode(', ', $value);

            update_user_meta($user_id, $key, esc_attr($value));

            /* update core fields - email, url, pass */
            if ((in_array($key, array('user_email', 'user_url', 'display_name')))) {
                wp_update_user(array('ID' => $user_id, $key => esc_attr($value)));
            }
        }
    }
}

/**
 * Adds custom forms to the registration forms.
 */
function epic_login_head() {
    wp_register_style('epic_login_style', epic_url . 'registration/epic-register.css');
    wp_enqueue_style('epic_login_style');


    wp_register_style('epic_date_picker', epic_url . 'css/epic-datepicker.css');
    wp_enqueue_style('epic_date_picker');

    wp_register_script('epic_date_picker_js', epic_url . 'js/epic-datepicker.js', array('jquery'));
    wp_enqueue_script('epic_date_picker_js');

    wp_localize_script('epic_date_picker_js', 'epicDatePicker', epic_date_picker_setting());
}

/*
 * AJAX Function to check for User and Email Existence.
 */

// Adding AJAX action for logged in and guest both.
add_action('wp_ajax_check_email_username', 'check_username_id');
add_action('wp_ajax_nopriv_check_email_username', 'check_username_id');

function check_username_id() {
    $user_name_exists = false;
    $email_exists = false;
    if (isset($_POST['user_name']) && username_exists($_POST['user_name'])) {
        $user_name_exists = true;
    }

    if (isset($_POST['email_id']) && email_exists($_POST['email_id'])) {
        $email_exists = true;
    }

    if ($user_name_exists == false && $email_exists == false) {
        echo json_encode(array("status" => TRUE, "msg" => "success"));
    } else if ($user_name_exists == true && $email_exists == true) {
        echo json_encode(array("status" => FALSE, "msg" => "both_error"));
    } else if ($user_name_exists == true && $email_exists == false) {
        echo json_encode(array("status" => FALSE, "msg" => "user_name_error"));
    } else if ($user_name_exists == false && $email_exists == true) {
        echo json_encode(array("status" => FALSE, "msg" => "email_error"));
    }

    die;
}

// Adding AJAX actions for validating registration fields.
add_action('wp_ajax_validate_register_email', 'check_new_email_registration');
add_action('wp_ajax_nopriv_validate_register_email', 'check_new_email_registration');

function check_new_email_registration() {

    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';

    if (!empty($user_email)) {

        $user_email = sanitize_email($user_email);

        if (is_email($user_email)) {
            // Check the existence of user email from database
            if (email_exists($user_email)) {
                echo json_encode(array("status" => TRUE, "msg" => "RegExistEmail"));
            } else {
                echo json_encode(array("status" => FALSE, "msg" => "RegValidEmail"));
            }
        } else {
            echo json_encode(array("status" => TRUE, "msg" => "RegInvalidEmail"));
        }
    } else {
        echo json_encode(array("status" => TRUE, "msg" => "RegEmptyEmail"));
    }


    die();
}

add_action('wp_ajax_validate_register_username', 'check_new_username_registration');
add_action('wp_ajax_nopriv_validate_register_username', 'check_new_username_registration');

function check_new_username_registration() {

    $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : '';

    // Check the existence of username from database
    if (!empty($user_login)) {

        $filtered_user_login = sanitize_user($user_login, TRUE);

        if ($filtered_user_login == $user_login) {

            if (username_exists($filtered_user_login)) {
                echo json_encode(array("status" => TRUE, "msg" => "RegExistUsername"));
            } else {
                echo json_encode(array("status" => FALSE, "msg" => "RegValidUsername"));
            }
        } else {
            echo json_encode(array("status" => TRUE, "msg" => "RegInValidUsername"));
        }
    } else {
        echo json_encode(array("status" => TRUE, "msg" => "RegEmptyUsername"));
    }



    die();
}


// Adding AJAX action for logged in and guest both.
add_action('wp_ajax_epic_load_user_pic', 'epic_load_user_pic');
add_action('wp_ajax_nopriv_epic_load_user_pic', 'epic_load_user_pic');

function epic_load_user_pic(){
    global $epic;

    $email = $_GET['email'];

    echo $epic->pic($email, 50);
    exit;
}