<?php

class epic_Scripts_Styles{

    public $epic_settings;
    public function __construct() {    
        $this->epic_settings = get_option('epic_options');
    }
    
    public function registration($params){
        
        // Loading scripts and styles only when required
        /* Password Stregth Checker Script */
        if (!wp_script_is('form-validate')) {
            wp_register_script('form-validate', epic_url . 'js/form-validate.js', array('jquery'));
            wp_enqueue_script('form-validate');

            $validate_strings = epic_form_validate_setting();
            wp_localize_script('form-validate', 'Validate', $validate_strings);
        }

        // Include password strength meter from WordPress core
        wp_enqueue_script('password-strength-meter');

        if (!wp_style_is('epic_password_meter')) {
            wp_register_style('epic_password_meter', epic_url . 'css/password-meter.css');
            wp_enqueue_style('epic_password_meter');
        }

        if (!wp_style_is('epic_date_picker')) {
            wp_register_style('epic_date_picker', epic_url . 'css/epic-datepicker.css');
            wp_enqueue_style('epic_date_picker');
        }


        if (!wp_script_is('epic_date_picker_js')) {
            wp_register_script('epic_date_picker_js', epic_url . 'js/epic-datepicker.js', array('jquery'));
            wp_enqueue_script('epic_date_picker_js');

            // Set date picker default settings
            $date_picker_array = epic_date_picker_setting();
            wp_localize_script('epic_date_picker_js', 'epicDatePicker', $date_picker_array);
        }

        do_action('epic_add_registration_scripts');
        
    } 
    
}

$epic_scripts_styles = new epic_Scripts_Styles();