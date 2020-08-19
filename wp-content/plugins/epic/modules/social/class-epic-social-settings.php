<?php

class epic_Social_Settings{
	
	public function __construct(){
        add_action( 'epic_addon_module_tabs',array($this, 'module_tabs') );
        add_action( 'epic_addon_module_settings',array($this, 'module_settings') );
        
        add_filter('epic_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('epic_init_options', array($this,'general_settings') );
        add_filter('epic_default_module_settings', array($this,'default_module_settings') );
        add_filter('epic_option_with_checkbox', array($this,'option_with_checkbox') );
        
    }
    
    public function module_tabs(){        
        echo '<li class="epic-tab " id="epic-social-login-settings-tab">'. __('Social Login','epic').'</li>';
        
    }
    
    public function settings_list($settings){
        $settings['epic-social-login-settings'] = array('social_login_allowed_networks','social_login_facebook_app_id','social_login_facebook_app_secret',
      'social_login_google_client_id','social_login_google_client_secret' ,'social_login_twitter_app_key','social_login_twitter_app_secret' ,'social_login_linkedin_app_key','social_login_linkedin_app_secret','social_login_display_message'                                                 );
        return $settings;
    }

    public function general_settings($settings){
        $settings['social_login_allowed_networks'] = '';
        $settings['social_login_facebook_app_id'] = '';
        $settings['social_login_facebook_app_secret'] = '';
        
        $settings['social_login_google_client_id'] = '';
        $settings['social_login_google_client_secret'] = '';
        $settings['social_login_twitter_app_key'] = '';
        $settings['social_login_twitter_app_secret'] = '';
        $settings['social_login_linkedin_app_key'] = '';
        $settings['social_login_linkedin_app_secret'] = '';
        $settings['social_login_display_message'] = '';
        return $settings;
    }

    public function default_module_settings($settings){
        $settings['epic-social-login-settings'] = array(
                                                        'social_login_allowed_networks' => '',
                                                        'social_login_facebook_app_id' => '',
                                                        'social_login_facebook_app_secret' => '',
                                                        'social_login_google_client_id' => '',
                                                        'social_login_google_client_secret' => '',
                                                        'social_login_twitter_app_key' => '',
                                                        'social_login_twitter_app_secret' => '',
                                                        'social_login_linkedin_app_key' => '',
                                                        'social_login_linkedin_app_secret' => '',
                                                        'social_login_display_message' => '',

                                                        );
        return $settings;
    }

    public function option_with_checkbox($settings){
        array_push($settings,'social_login_allowed_networks');
        return $settings;
    }
    
    public function module_settings(){
        global $epic_template_loader;

        ob_start();
        $epic_template_loader->get_template_part('social','settings');
        $display = ob_get_clean();        
        echo $display;
    }
}

$epic_social_settings = new epic_Social_Settings();
