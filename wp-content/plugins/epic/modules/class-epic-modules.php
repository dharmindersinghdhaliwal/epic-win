<?php

class epic_Modules{

	public function __construct(){

		if (is_admin ()) {
            add_action('wp_ajax_save_epic_module_settings', array($this, 'epic_save_module_settings'));
        	add_action('wp_ajax_reset_epic_module_settings', array($this, 'epic_reset_module_settings'));
            
        }

	}

	public function epic_save_module_settings(){
        
        $current_options = get_option('epic_options');

        $this->array_field_options = array(
                'epic-site-lockdown-settings' =>                                                                                                array('site_lockdown_allowed_pages','site_lockdown_allowed_posts','site_lockdown_status'), 
                'epic-email-general-settings' =>                                                                                                array('email_from_name','email_from_address','notifications_all_admins'),
                'epic-custom-fields-settings' =>                                                                                                array('help_text_html','profile_collapsible_tabs','profile_collapsible_tabs_display'),
        							);
        /* Add the settings for addons through filters */
        $this->array_field_options = apply_filters('epic_module_settings_array_fields',$this->array_field_options);
        
        parse_str($_POST['data'], $setting_data);        
        
        foreach($setting_data as $key=>$value)
                $current_options[$key]=$value;

        if(count($this->array_field_options[epic_post_value('current_tab')]) > 0){
    
            foreach($this->array_field_options[epic_post_value('current_tab')] as $key=>$value)
            {
                
                if(!array_key_exists($value, $setting_data)){
                    if(in_array($value, array('site_lockdown_allowed_pages','site_lockdown_allowed_posts'))){
                        $current_options[$value]='';
                    }else{
                        $current_options[$value]='0';
                    }
                    
                }
                    
            }
        }

        
        
        update_option('epic_options', $current_options);
        echo json_encode(array('status'=>'success')); exit;
	}

	public function epic_reset_module_settings(){
		global $epic_admin;
        if(epic_is_post() && epic_is_in_post('current_tab')){

            if(isset($epic_admin->default_module_settings[epic_post_value('current_tab')])){
                $current_options = get_option('epic_options');

                foreach($epic_admin->default_module_settings[epic_post_value('current_tab')] as $key=>$value)
                    $current_options[$key] = $value;
                
                update_option('epic_options', $current_options);
                echo json_encode(array('status'=>'success')); exit;
            }
        }
    }

}

$epic_modules = new epic_Modules();