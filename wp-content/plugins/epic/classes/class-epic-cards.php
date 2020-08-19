<?php

class epic_Cards {

    public $epic_options;
    public $epic_card_attributes;
    public $searched_users;

    function __construct() {
        $this->epic_options = get_option('epic_options');
    }
    
    public function epic_scripts_styles_profile_cards(){
        global $epic;
        
        /* Google fonts */
        if ('0' == $this->epic_options['disable_opensans_google_font']) {
            wp_register_style('epic_google_fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&subset=latin,latin-ext');
            wp_enqueue_style('epic_google_fonts');
        }

        /* Font Awesome */
        wp_register_style('epic_font_awesome', epic_url . 'css/font-awesome.min.css');
        wp_enqueue_style('epic_font_awesome');

        /* Main css file */
        wp_register_style('epic_css', epic_url . 'css/epic.css');
        wp_enqueue_style('epic_css');

        /* Add style */
        if ($epic->get_option('style')) {
            wp_register_style('epic_style', epic_url . 'styles/' . $epic->get_option('style') . '.css');
            wp_enqueue_style('epic_style');
        }

        /* Responsive */
        wp_register_style('epic_responsive', epic_url . 'css/epic-responsive.css');
        wp_enqueue_style('epic_responsive');

        do_action('epic_add_style_scripts_frontend');
    }
    
    public function epic_author_profile($args){
        global $epic,$epic_template_loader,$epic_template_args;
        
        $epic_template_args =  array();
        
        /* Arguments */
        $defaults = array(
            'id'                => null,
            'template'          => null,
            'pic_style'         => 'rounded',
            'background_color'  => '#FFF',
            'font_color'        => '#000'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $this->epic_card_attributes = $args;

        extract($args, EXTR_SKIP);
        
        $this->epic_scripts_styles_profile_cards();
        

        // Show custom field as profile title
        $profile_title_field = $this->epic_options['profile_title_field'];
        // Get value of profile title field or default display name if empty
        
        
        $epic_template_args['id']                   = $id;
        
        $epic_template_args['pic_style']            = 'epic-profile-pic-'. $pic_style;   
        $epic_template_args['background_color']     = $background_color;  
        $epic_template_args['font_color']           = $font_color;  
        $epic_template_args['template']             = $template;
        
        
        $epic_template_args['profile_title_display'] = $epic->epic_profile_title_value($profile_title_field, $id);
        $epic_template_args['profile_url']          = $epic->profile_link($id);
        $epic_template_args['user_pic']             = get_user_meta($id,'user_pic',true);
        $epic_template_args['profile_pic_display']  = '<a href="' . $epic_template_args['profile_url'] . '">' . $epic->pic($id, 50) . '</a>';
        $epic_template_args['description']          = get_user_meta($id,'description',true);
        $epic_template_args['social_buttons']       = $epic->show_user_social_profiles($id, array('tag'=>'ul', 'sub_tag'=>'li'));
        
        ob_start();
        
        switch($template){
            case 'author_design_one':                
                $epic_template_loader->get_template_part('author-card','one');
                $display = ob_get_clean();
                break;
            
            case 'author_design_two':            
                $epic_template_loader->get_template_part('author-card','two');
                $display = ob_get_clean();
                break;
            
            case 'author_design_three':            
                $epic_template_loader->get_template_part('author-card','three');
                $display = ob_get_clean();
                break;
            
            case 'author_design_four':            
                $epic_template_loader->get_template_part('author-card','four');
                $display = ob_get_clean();
                break;
        }
      
        return $display;
    }
    
    public function epic_team_profile($args,$content){
        global $epic, $epic_template_loader, $epic_template_args;
        
        $defaults = array(
            'id' => null,
            'view' => null,
            'group' => null,
            'width' => 1,
            'users_per_page' => 100,
            'orderby' => 'registered',
            'order' => 'desc',
            'orderby_custom' => false,
            'role' => null,
            'group_meta' => null,
            'group_meta_value' => null,
            'display' => '',
            'limit_results' => false,
            'hide_admins' => false,
            'show_random' => 'no',
            'result_range_start' => false,
            'result_range_count' => false,
            
            'id'                => null,
            'template'          => null,
            'pic_style'         => 'rounded',
            'background_color'  => '#FFF',
            'font_color'        => '#000',
            'team_name'         => '',
        );
        
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

        $epic_template_args =  array();        
        $this->epic_scripts_styles_profile_cards();
        
        $users = $this->load_searched_member_list($args);

        // Show custom field as profile title
        $profile_title_field = $this->epic_options['profile_title_field'];
        // Get value of profile title field or default display name if empty
        
        $user_details = array();
        foreach($users as $user){
            $id = $user;
            $single_user = array();
            $single_user['id'] = $user;
            $single_user['profile_title_display'] = $epic->epic_profile_title_value($profile_title_field, $id);
            $single_user['profile_url'] = $epic->profile_link($id);
            $single_user['user_pic'] = get_user_meta($id,'user_pic',true);
            $single_user['profile_pic_display'] = '<a href="' . $single_user['profile_url'] . '">' . $epic->pic($id, 50) . '</a>';
            $single_user['description'] = get_user_meta($id,'description',true);;
            $single_user['social_buttons'] = $epic->show_user_social_profiles($id, array('tag'=>'ul', 'sub_tag'=>'li'));
            
            array_push($user_details,$single_user);
        }
        
        
        $epic_template_args['id']                   = $id;        
        $epic_template_args['pic_style']            = 'epic-profile-pic-'. $pic_style;   
        $epic_template_args['background_color']     = $background_color;  
        $epic_template_args['font_color']           = $font_color;  
        $epic_template_args['template']             = $template;        
        $epic_template_args['team_name']            = $team_name;
        $epic_template_args['team_description']     = trim($content);
        $epic_template_args['users']                = $user_details;
        //echo "<pre>";print_r($epic_template_args);exit;
        ob_start();
        
        switch($template){
            case 'team_design_one':                
                $epic_template_loader->get_template_part('team-card','one');
                $display = ob_get_clean();
                break;
            
            case 'team_design_two':            
                $epic_template_loader->get_template_part('team-card','two');
                $display = ob_get_clean();
                break;
            
            case 'team_design_three':            
                $epic_template_loader->get_template_part('team-card','three');
                $display = ob_get_clean();
                break;
            
            case 'team_design_four':            
                $epic_template_loader->get_template_part('team-card','four');
                $display = ob_get_clean();
                break;
            
            case 'team_design_five':            
                $epic_template_loader->get_template_part('team-card','five');
                $display = ob_get_clean();
                break;
            
            case 'team_design_six':            
                $epic_template_loader->get_template_part('team-card','six');
                $display = ob_get_clean();
                break;
            
            case 'team_design_sevan':            
                $epic_template_loader->get_template_part('team-card','sevan');
                $display = ob_get_clean();
                break;
        }
      
        return $display;
    }
    
    public function epic_slider_profiles($args){
        global $epic, $epic_template_loader, $epic_template_args;
        
        $defaults = array(
            'id' => null,
            'view' => null,
            'group' => null,
            'width' => 1,
            'users_per_page' => 100,
            'orderby' => 'registered',
            'order' => 'desc',
            'orderby_custom' => false,
            'role' => null,
            'group_meta' => null,
            'group_meta_value' => null,
            'display' => '',
            'limit_results' => false,
            'hide_admins' => false,
            'show_random' => 'no',
            'result_range_start' => false,
            'result_range_count' => false,
            
            'id'                => null,
            'template'          => null,
            'pic_style'         => 'rounded',
            'background_color'  => '#FFF',
            'font_color'        => '#000',
            'slider'            => 'flexSlider'
        );
        
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

        $epic_template_args =  array();        
        $this->epic_scripts_styles_profile_cards();
        
        $users = $this->load_searched_member_list($args);

        // Show custom field as profile title
        $profile_title_field = $this->epic_options['profile_title_field'];
        // Get value of profile title field or default display name if empty
        
        $user_details = array();
        foreach($users as $user){
            $id = $user;
            $single_user = array();
            $single_user['id'] = $user;
            $single_user['profile_title_display'] = $epic->epic_profile_title_value($profile_title_field, $id);
            $single_user['profile_url'] = $epic->profile_link($id);
            $single_user['user_pic'] = get_user_meta($id,'user_pic',true);
            if($single_user['user_pic'] == ''){
                $single_user['user_pic'] = epic_get_gravatar_url(get_user_meta($id,'user_email',true));
            }
            
            $single_user['profile_pic_display'] = '<a href="' . $single_user['profile_url'] . '">' . $epic->pic($id, 100) . '</a>';
            $single_user['description'] = get_user_meta($id,'description',true);;
            $single_user['social_buttons'] = $epic->show_user_social_profiles($id, array('tag'=>'ul', 'sub_tag'=>'li'));
            
            array_push($user_details,$single_user);
        }
        
        
        $epic_template_args['id']                   = $id;        
        $epic_template_args['pic_style']            = 'epic-profile-pic-'. $pic_style;   
        $epic_template_args['background_color']     = $background_color;  
        $epic_template_args['font_color']           = $font_color;  
        $epic_template_args['template']             = $template;        
        $epic_template_args['users']                = $user_details;
        
        //echo "<pre>";print_r($epic_template_args);exit;
        ob_start();
        
        $template_parts     = explode('_',$template);
        $template_part_main = $template_parts[0] . '-card';
        $template_part_sub  = isset($template_parts[2]) ? $template_parts[2] : '';
        
        wp_register_script('epic_sliders', epic_url . 'integrated_plugins/sliders/epic-sliders.js', array('jquery'));
        wp_enqueue_script('epic_sliders');
        
        switch($slider){
            case 'flexSlider':
                $epic_template_args['slider'] = 'flexSlider';
            
                wp_register_style('epic_flex_slider_style', epic_url . 'integrated_plugins/sliders/woothemes-flexSlider/flexslider.css');
                wp_enqueue_style('epic_flex_slider_style');
            
                wp_register_script('epic_flex_slider', epic_url . 'integrated_plugins/sliders/woothemes-flexSlider/jquery.flexslider-min.js', array('jquery'));
                wp_enqueue_script('epic_flex_slider');
            
                $epic_template_loader->get_template_part($template_part_main,$template_part_sub);
                break;
            
            default:
                break;
        }
        
        
        $display = ob_get_clean();
        
        return $display;
    }
    
    public function load_searched_member_list($args){
        global $epic;
        
        extract($args, EXTR_SKIP);
        
        $epic->epic_args                        = $args;
        $epic->profile_orderby_custom_status    = $orderby_custom;
        $epic->profile_order_field              = $orderby;
        $epic->profile_order = 'asc';
        
        if (strtolower($order) == 'asc' || strtolower($order) == 'desc')
            $epic->profile_order = $order;
        
        $epic->profile_role = $role;
        $epic->hide_admin_role = $hide_admins;
        
        $epic->show_random = $show_random;
        $epic->result_range_start = $result_range_start;
        $epic->result_range_count = $result_range_count;

        $this->epic_card_attributes = $args;
        
        $search_args = array('per_page' => $users_per_page , 'orderby' => $orderby, 'order' => $order,
                            'show_random' => $show_random, 'result_range_start'=> $result_range_start,
                            'result_range_count' => $result_range_count);
        
        unset($epic->searched_users);
        
        if ($users_per_page) {
            $search_args = $epic->setup_page($search_args, $users_per_page);
        }
        
        $users = array();
        /* Ignore id if group is used */
        if($group){
            if ($group != 'all') {
                $users = explode(',', $group);
            }else{
                if (!isset($epic->searched_users)) {
                    $epic->search_result($search_args);
                }

                foreach ($epic->searched_users as $user) {
                    $users[] = $user->ID;
                }
            }
        }
        else if($id){
            $users[] = $id;
        }
        else {
            $current_user = wp_get_current_user();
            if (($current_user instanceof WP_User)) {
                $users[] = $current_user->ID;
            }else{
                $users = array();
            }
            
        }
        
        return $users;
    }
    
    
    
}

$epic_cards = new epic_Cards();


/* Shortcodes for author card templates */
add_shortcode('epic_author_card', 'epic_author_card');
add_shortcode('epic_team_card', 'epic_team_card');
add_shortcode('epic_slider_card', 'epic_slider_card');

function epic_author_card($atts) {
    global $epic_cards;
    return $epic_cards->epic_author_profile($atts);
}

function epic_team_card($atts,$content) {
    global $epic_cards;
    return $epic_cards->epic_team_profile($atts,$content);
}

function epic_slider_card($atts,$content) {
    global $epic_cards;
    return $epic_cards->epic_slider_profiles($atts);
}