<?php

class epic_Woocommerce{
    
    public $epic_settings;
    public $woocommerce_tab_status;
    
    public function __construct(){
        add_action('wp_enqueue_scripts', array($this,'scripts_styles'));
        add_action('admin_enqueue_scripts', array($this,'admin_scripts_styles'));

        add_filter('epic_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('epic_init_options', array($this,'general_settings') );
        add_filter('epic_default_module_settings', array($this,'default_module_settings') );
        add_filter('epic_option_with_checkbox', array($this,'option_with_checkbox') );
        
        
        add_action( 'epic_addon_module_tabs',array($this, 'module_tabs') );
        add_action( 'epic_addon_module_settings',array($this, 'module_settings') );   
        
        add_action('init',array($this,'intialize_woo_manager'));
        
        add_shortcode('epic_woo_account', array($this,'display_my_account'));
        add_filter('epic_profile_tab_items', array($this,'profile_tab_items'),10,2);
        add_filter('epic_profile_view_forms',array($this,'profile_view_forms'),10,2);
        
    }
    
    public function scripts_styles(){
            $link_woo_css = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/css/woocommerce.css';
            wp_register_style('woocommerce-general-css', $link_woo_css);
            wp_enqueue_style( 'woocommerce-general-css');
            
            wp_register_style('epic-woo', epic_url . 'css/epic-woo.css');
            wp_enqueue_style( 'epic-woo');
            
            wp_register_script('epic-woo', epic_url . 'js/epic-woo.js',array( 'jquery'));
            wp_enqueue_script( 'epic-woo');
        
        }
        
        public function admin_scripts_styles(){
            if(is_admin()){
                wp_register_script('epic-woo-admin', epic_url . 'admin/js/epic-woo-admin.js',array( 'jquery'));
                wp_enqueue_script( 'epic-woo-admin');
            }
        }
        
        public function settings_list($settings){
            $settings['epic-woocommerce-settings'] = array('woocommerce_profile_tab_status');
            return $settings;
        }
        
        public function general_settings($settings){
            $settings['woocommerce_profile_tab_status'] = '0';
            return $settings;
        }
        
        public function default_module_settings($settings){
            $settings['epic-woocommerce-settings'] = array(
                                                            'woocommerce_profile_tab_status' => '0',
                                                            );
            return $settings;
        }
        
        public function option_with_checkbox($settings){
            array_push($settings,'woocommerce_profile_tab_status');
            return $settings;
        }
    
        public function module_tabs(){
        
            echo '<li class="epic-tab " id="epic-woocommerce-settings-tab">'. __('Woocommerce','epic').'</li>';
        }

        public function module_settings(){
            global $epic_template_loader;

            ob_start();
            $epic_template_loader->get_template_part('woocommerce','list');
            $display = ob_get_clean();        
            echo $display;
        }
    
        public function intialize_woo_manager(){
            $this->current_user = get_current_user_id();
            $this->epic_settings = get_option('epic_options');

            $this->woocommerce_tab_status = $this->epic_settings['woocommerce_profile_tab_status'];
        }

        public function display_my_account(){

            global $epic_template_loader;

            ob_start();
            $epic_template_loader->get_template_part('my-account');        
            $display = ob_get_clean();
            return $display;
        }
    
        public function profile_tab_items($display,$params){
            if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
                $this->woocommerce_tab_status){

                $display .= '<div class="epic-profile-tab" data-tab-id="epic-woocommerce-panel" >
                            <i class="epic-profile-icon epic-icon-dashboard"></i>
                        </div>';
            }

            return $display;
        }

        public function profile_view_forms($display,$params){
            extract($params);
            if($view != 'compact'){
                if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
                    $this->woocommerce_tab_status){

                    $display .= '<div id="epic-woocommerce-panel" class="epic-profile-tab-panel" style="display:none;" >
                                    '.do_shortcode("[epic_woo_account]").'       
                                </div>';
                }
            }

            return $display;
        }

}

add_action( 'plugins_loaded', 'epic_woo_plugin_init' );

function epic_woo_plugin_init(){
    global $epic_woocommerce;
    if(class_exists('WooCommerce')){
        $epic_woocommerce = new epic_Woocommerce();
    }
}