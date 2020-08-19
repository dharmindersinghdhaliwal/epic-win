<?php

class epic_Custom_Fields{
    
    public $epic_settings;
    
    public function __construct(){
        add_filter('epic_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('epic_init_options', array($this,'general_settings') );
        add_filter('epic_default_module_settings', array($this,'default_module_settings') );
        add_filter('epic_option_with_checkbox', array($this,'option_with_checkbox') );
        
        
        add_action( 'epic_addon_module_tabs',array($this, 'module_tabs') );
        add_action( 'epic_addon_module_settings',array($this, 'module_settings') );   

        add_action('wp_ajax_epic_save_separator_field_groups', array($this, 'epic_save_separator_field_groups'));
    }
        
    public function settings_list($settings){
        return $settings;
    }

    public function general_settings($settings){
        return $settings;
    }
        
    public function default_module_settings($settings){
        return $settings;
    }

    public function option_with_checkbox($settings){
        return $settings;
    }
    
    public function module_tabs(){

        echo '<li class="epic-tab " id="epic-custom-fields-settings-tab">'. __('Custom Fields','epic').'</li>';
        echo '<li class="epic-tab " id="epic-separator-field-groups-settings-tab">'. __('Separator Field Groups','epic').'</li>';
    }

    public function module_settings(){
        global $epic_template_loader;

        ob_start();
        $epic_template_loader->get_template_part('custom-fields');
        $epic_template_loader->get_template_part('separator-field-groups');
        $display = ob_get_clean();        
        echo $display;
    }

    public function epic_save_separator_field_groups(){
        $data = array();

        parse_str($_POST['data'], $data);

        $separator_group_fields = isset($data['separator_group_fields']) ? $data['separator_group_fields'] : array();

        update_option('epic_separator_group_fields',$separator_group_fields);

        echo json_encode(array('status' => 'success'));exit;
    }

}

add_action( 'plugins_loaded', 'epic_custom_fields_plugin_init' );

function epic_custom_fields_plugin_init(){
    global $epic_custom_fields;
    $epic_custom_fields = new epic_Custom_Fields();
}