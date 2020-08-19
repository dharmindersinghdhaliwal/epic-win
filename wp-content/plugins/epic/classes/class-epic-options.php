<?php

class epic_Options {

    public $epic_settings;
    public $epic_profile_fields;
    public $epic_profile_fields_by_key;

    function __construct() {
        add_action('init',array($this,'epic_init'));
        
        $this->epic_settings                = array();
        $this->epic_profile_fields          = array();
        $this->epic_profile_fields_by_key   = array();
        
        $this->epic_init_settings();
        $this->epic_init_profile_fields();
    }
    
    public function epic_init(){
        $this->epic_init_settings();
        $this->epic_init_profile_fields();
    }

    public function epic_init_settings(){
        $this->epic_settings = get_option('epic_options');
        
    }
    
    public function epic_init_profile_fields(){
        $this->epic_profile_fields = get_option('epic_profile_fields');
        
        foreach($this->epic_profile_fields as $key => $field){
            $meta = isset($field['meta']) ? $field['meta'] : '';
            if($meta != ''){
                if (!isset($field['deleted']))
                    $field['deleted'] = 0;

                if (!isset($field['private']))
                    $field['private'] = 0;
                
                $field['key_index'] = $key;
                
                $this->epic_profile_fields_by_key[$meta] = $field;
            }
        }

    }
}

$epic_options = new epic_Options();