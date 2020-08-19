<?php

class epic_Posts_Pages{
    
    public $epic_settings;

	function __construct() {
        
        
        add_filter('epic_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('epic_init_options', array($this,'general_settings') );
        add_filter('epic_default_module_settings', array($this,'default_module_settings') );

        add_action('epic_addon_module_tabs',array($this, 'module_tabs') );
        add_action('epic_addon_module_settings',array($this, 'module_settings') );   
        
        add_filter( 'the_content', array($this,'content_filter') , 20 );
        
        add_action('wp_ajax_epic_change_post_feature_status', array($this,'epic_change_post_feature_status'));
        
        add_shortcode('epic_post_features_bar', array($this,'epic_post_features_bar'));
        
        
        // TODO - Check user roles before enabling the tab
        add_shortcode('epic_favorite_posts_list', array($this,'epic_favorite_posts_list'));
        add_shortcode('epic_reader_posts_list', array($this,'epic_reader_posts_list'));
        add_shortcode('epic_recommended_posts_list', array($this,'epic_recommended_posts_list'));
        
        
        add_filter('epic_profile_tab_items', array($this,'profile_tab_items'),10,2);
        add_filter('epic_profile_view_forms',array($this,'profile_view_forms'),10,2);
        
        add_action('init',array($this,'intialize_posts_pages'));
	}
    

    
    public function settings_list($settings){
        $settings['epic-posts-pages-settings'] = array('favorite_enabled_post_types','reader_enabled_post_types','recommend_enabled_post_types','favorite_default_featured_image','reader_default_featured_image','recommend_default_featured_image','featured_image_enabled_types','reader_enabled_user_roles','favorite_enabled_user_roles','recommend_enabled_user_roles','post_button_panel_status','reader_enabled_status','favorite_enabled_status','recommend_enabled_status','content_before_post_buttons');
        return $settings;
    }
        
    public function general_settings($settings){
        $settings['favorite_enabled_post_types'] = '0';
        $settings['reader_enabled_post_types'] = '0';
        $settings['recommend_enabled_post_types'] = '0';
        $settings['favorite_default_featured_image'] = '';
        $settings['reader_default_featured_image'] = '';
        $settings['recommend_default_featured_image'] = '';
        $settings['featured_image_enabled_types'] = '';
        $settings['reader_enabled_user_roles'] = '';
        $settings['favorite_enabled_user_roles'] = '';
        $settings['recommend_enabled_user_roles'] = '';
        $settings['post_button_panel_status'] = '0';
        $settings['reader_enabled_status'] = '0';
        $settings['favorite_enabled_status'] = '0';
        $settings['recommend_enabled_status'] = '0';
        $settings['content_before_post_buttons'] = '';
        return $settings;
    }

    public function default_module_settings($settings){
        $settings['epic-posts-pages-settings'] = array(
                                            'favorite_enabled_post_types' => '0',
                                            'reader_enabled_post_types' => '0',
                                            'recommend_enabled_post_types' => '0',
                                            'favorite_default_featured_image' => '',
                                            'reader_default_featured_image' => '',
                                            'recommend_default_featured_image' => '',
                                            'featured_image_enabled_types' => '',
                              
                                            'reader_enabled_user_roles' => '',
                                            'favorite_enabled_user_roles' => '',
                                            'recommend_enabled_user_roles' => '',
                                            'post_button_panel_status' => '0',
                                            'reader_enabled_status' => '0',
                                            'favorite_enabled_status' => '0',
                                            'recommend_enabled_status' => '0',
                                            'content_before_post_buttons' => '',
                                        );
        return $settings;
    }

    
    public function module_tabs(){
        echo '<li class="epic-tab " id="epic-posts-pages-settings-tab">'. __('Posts and Pages','epic').'</li>';
    }

    public function module_settings(){
        global $epic_template_loader,$wpdb;
        
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        }
        
        /* Check and create database table for posts features */
        $table_name = $wpdb->prefix.'epic_post_features';
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
                      id int(11) NOT NULL auto_increment,
                      post_id int(11) DEFAULT NULL,
                      user_id int(11) NOT NULL,
                      read_status tinyint(1) DEFAULT NULL,
                      recommend_status tinyint(1) DEFAULT NULL,
                      favorite_status tinyint(1) DEFAULT NULL,
                      PRIMARY KEY (id)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;";
            $wpdb->query($sql);
        }

        ob_start();
        $epic_template_loader->get_template_part('posts-pages');
        $display = ob_get_clean();        
        echo $display;
    }
    
    public function content_filter($content){
        global $epic_roles,$epic_options,$post;
        if(is_user_logged_in()){
            $user_id = get_current_user_id();
            $user_roles = $epic_roles->epic_get_user_roles_by_id($user_id);
            //echo "<pre>";print_r($user_roles);exit;
            
            $reader_status        = $epic_options->epic_settings['reader_enabled_status'];
            $favorite_status         = $epic_options->epic_settings['favorite_enabled_status'];
            $recommend_status        = $epic_options->epic_settings['recommend_enabled_status'];
            
            $reader_enabled_types           = (array) $epic_options->epic_settings['reader_enabled_post_types'];
            $favorite_enabled_types         = (array) $epic_options->epic_settings['favorite_enabled_post_types'];
            $recommend_enabled_types        = (array) $epic_options->epic_settings['recommend_enabled_post_types'];
            
            $reader_enabled_roles           = (array) $epic_options->epic_settings['reader_enabled_user_roles'];
            $favorite_enabled_user_roles    = (array) $epic_options->epic_settings['favorite_enabled_user_roles'];
            $recommend_enabled_user_roles   = (array) $epic_options->epic_settings['recommend_enabled_user_roles'];
            
            $post_button_location           = $epic_options->epic_settings['post_button_panel_status'];
            
            $reader_enabled_status = false;
            $favorite_enabled_status = false;
            $recommend_enabled_status = false;
            
            if($reader_status == '1'){
                
                // Check user role permission
                if(in_array('all_roles',$reader_enabled_roles)){
                    $reader_enabled_status = true;
                }else{
                    foreach($reader_enabled_roles as $role){
                        if(in_array($role,$user_roles)){
                            $reader_enabled_status = true;
                        }
                    }
                }
        
                if($reader_enabled_status){
                    if(is_page()){
                        if(in_array('page',$reader_enabled_types)){
                            $reader_enabled_status = true;
                        }else{
                            $reader_enabled_status = false;
                        }
                    }
                    
                    if(is_single()){                    
                        if(in_array($post->post_type,$reader_enabled_types)){
                            $reader_enabled_status = true;
                        }else{
                            $reader_enabled_status = false;
                        }
                    }
                }                
            }
            
            if($favorite_status == '1'){
                
                if(in_array('all_roles',$favorite_enabled_user_roles)){
                    $favorite_enabled_status = true;
                }else{
                    foreach($favorite_enabled_user_roles as $role){
                        if(in_array($role,$user_roles)){
                            $favorite_enabled_status = true;
                        }
                    }
                }
                
                
                if($favorite_enabled_status){
                    if(is_page()){
                        if(in_array('page',$favorite_enabled_types)){
                            $favorite_enabled_status = true;
                        }else{
                            $favorite_enabled_status = false;
                        }
                    }
                    
                    if(is_single()){                    
                        if(in_array($post->post_type,$favorite_enabled_types)){
                            $favorite_enabled_status = true;
                        }else{
                            $favorite_enabled_status = false;
                        }
                    }
                }          
                
                
            }
            
            if($recommend_status == '1'){
                
                if(in_array('all_roles',$recommend_enabled_user_roles)){
                    $recommend_enabled_status = true;
                }else{
                    foreach($recommend_enabled_user_roles as $role){
                        if(in_array($role,$user_roles)){
                            $recommend_enabled_status = true;
                        }
                    }
                }
                
                if($recommend_enabled_status){
                    if(is_page()){
                        if(in_array('page',$recommend_enabled_types)){
                            $recommend_enabled_status = true;
                        }else{
                            $recommend_enabled_status = false;
                        }
                    }
                    
                    if(is_single()){                    
                        if(in_array($post->post_type,$recommend_enabled_types)){
                            $recommend_enabled_status = true;
                        }else{
                            $recommend_enabled_status = false;
                        }
                    }
                } 
            }
                
                
            if($reader_enabled_status || $favorite_enabled_status || $recommend_enabled_status){
                $sh_attributes = " reader_enabled_status='".$reader_enabled_status."'  favorite_enabled_status='".$favorite_enabled_status."'  recommend_enabled_status='".$recommend_enabled_status."' ";
                switch($post_button_location){
                    case '0':
                        $content = $content . do_shortcode('[epic_post_features_bar '.$sh_attributes.']');
                        
                        break;
                    case '1':
                        $content = do_shortcode('[epic_post_features_bar '.$sh_attributes.']') . $content;
                        break;
                    case '2':
                        $content = do_shortcode('[epic_post_features_bar '.$sh_attributes.']') .  $content . do_shortcode('[epic_post_features_bar '.$sh_attributes.']');
                        break;
                }
            }

        }

        return $content;
    }
    
    public function epic_change_post_feature_status(){
        global $post,$wpdb;
        
        $type   = isset($_POST['type']) ? $_POST['type'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        
        if($status  == 'active'){
            $status = 0;
        }else{
            $status = 1;
        }
        
        if(is_user_logged_in()){
            $user_id = get_current_user_id();
            
            $sql  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}epic_post_features WHERE post_id = %d AND user_id = %d", $post_id, $user_id );
            
            $set_statement = '';
            $read_status = 0;  
            $recommend_status = 0;  
            $favorite_status = 0;            
                
            switch($type){
                case 'read':
                    $set_statement = ' read_status='.$status.' ';
                    $read_status = 1;
                    break;
                
                case 'recommend':
                    $set_statement = ' recommend_status='.$status.' ';
                    $recommend_status = 1;
                    break;
                
                case 'favorite':
                    $set_statement = ' favorite_status='.$status.' ';
                    $favorite_status = 1;
                    break;
            }
    
            $result = $wpdb->get_row($sql);
            if($result){
                $sql  = $wpdb->prepare( "Update {$wpdb->prefix}epic_post_features set {$set_statement} WHERE post_id = %d AND user_id = %d", $post_id, $user_id );
            }else{
                $sql  = $wpdb->prepare( "Insert into {$wpdb->prefix}epic_post_features(post_id,user_id,read_status,recommend_status,favorite_status) values(%d,%d,%d,%d,%d)", $post_id, $user_id, $read_status, $recommend_status,  $favorite_status );
            }
            
            $wpdb->query($sql);
            
            echo json_encode(array('status'=>'success','type' => $type , 'post_status'=> $status ));
            exit;
            
        }else{
            echo json_encode(array('status'=>'fail','msg' => __('Login failed','epic')));
            exit;
        }
    }
    
    public function epic_post_features_bar($atts){
        global $post,$wpdb,$epic_options;
        
        extract( shortcode_atts( array(
            'reader_enabled_status'   => false,
            'favorite_enabled_status' => false,
            'recommend_enabled_status' => false,
          ), $atts ) );

        wp_register_script('epic_posts', epic_url . 'js/epic-posts.js', array('jquery'));
        wp_enqueue_script('epic_posts');

        $posts_settings = array('AdminAjax' => admin_url('admin-ajax.php'),
                                'Post_ID' => isset($post->ID) ? $post->ID  :0,
                                'Messages' => 
                                array(
                                    'markAsRead' => __('Mark as Read','epic'),
                                    'markAsFavorite' => __('Mark as Favorite','epic'),
                                    'markAsRecommended' => __('Mark as Recommended','epic'),
                                    'markAsUnRead' => __('Mark as Unread','epic'),
                                    'markAsNotFavorite' => __('Remove Favorite','epic'),
                                    'markAsNotRecommended' => __('Remove Recommended','epic'),
                                    'read' => __('Read','epic'),
                                    'favorite' => __('Favorited','epic'),
                                    'recommended' => __('Recommended','epic'),
                                    'unread' => __('Unread','epic'),
                                    'notfavorite' => __('Favorite','epic'),
                                    'notrecommended' => __('Recommend','epic'),
                                    'processing' => __('Processing','epic'),
                                ));
        wp_localize_script('epic_posts', 'epicPosts', $posts_settings);

        $post_id = isset($post->ID) ? $post->ID  : 0;

        $user_id = 0;
        if(is_user_logged_in()){
            $user_id = get_current_user_id();        
        }

        $sql  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}epic_post_features WHERE post_id = %s AND user_id = %s", $post_id, $user_id );

        $display = "<div class='epic-post-features-panel' >";
        $display .= "<div>".$epic_options->epic_settings['content_before_post_buttons']."</div><div class='epic-clear'></div>";

        $result = $wpdb->get_row($sql);
        if($result){
            
            if($reader_enabled_status){
                if($result->read_status){
                    $display .= "<div epic-data-status='active' epic-data-type='read' class='epic-post-features-btn epic-post-features-reading epic-post-features-active' >".__('Read','epic')."</div>";
                }else{
                    $display .= "<div epic-data-status='inactive' epic-data-type='read' class='epic-post-features-btn epic-post-features-reading epic-post-features-inactive' >".__('Unread','epic')."</div>";
                }
            }
            
            if($recommend_enabled_status){
                if($result->recommend_status){
                    $display .= "<div  epic-data-status='active' epic-data-type='recommend' class='epic-post-features-btn epic-post-features-recommend epic-post-features-active' >".__('Recommended','epic')."</div>";
                }else{
                    $display .= "<div epic-data-status='inactive' epic-data-type='recommend' class='epic-post-features-btn epic-post-features-recommend epic-post-features-inactive' >".__('Recommend','epic')."</div>";
                }
            }

            if($favorite_enabled_status){
                if($result->favorite_status){
                    $display .= "<div epic-data-status='active' epic-data-type='favorite' class='epic-post-features-btn epic-post-features-favorite epic-post-features-active' >".__('Favorited','epic')."</div>";
                }else{
                    $display .= "<div epic-data-status='inactive' epic-data-type='favorite' class='epic-post-features-btn epic-post-features-favorite epic-post-features-inactive' >".__('Favorite','epic')."</div>";
                }
            }
        }else{
            if($reader_enabled_status){
                $display .= "<div epic-data-status='inactive' epic-data-type='read' class='epic-post-features-btn epic-post-features-reading epic-post-features-inactive' >".__('Unread','epic')."</div>";
            }
            
            if($recommend_enabled_status){
                $display .= "<div epic-data-status='inactive' epic-data-type='recommend' class='epic-post-features-btn epic-post-features-recommend epic-post-features-inactive' >".__('Recommend','epic')."</div>";
            }
                
            if($favorite_enabled_status){
                $display .= "<div epic-data-status='inactive' epic-data-type='favorite' class='epic-post-features-btn epic-post-features-favorite epic-post-features-inactive' >".__('Favorite','epic')."</div>";
            }

        }

         $display .= "<div class='epic-clear'></div></div>";

        return $display;
    }
    
    public function epic_favorite_posts_list($atts){
        global $post,$wpdb,$epic_options;
        
        extract( shortcode_atts( array(
            'number_of_posts'   => 25 ,
            'featured_image' => 'no',
            'user_id' => 'no',
          ), $atts ) );
        
        $content = '';

        if($user_id != 'no'){
            $user_id = (int) $user_id;
            if( $user_id == '0' ){
                if(is_user_logged_in()){
                    $user_id = get_current_user_id(); 
                    
                    $sql  = $wpdb->prepare( "SELECT upf.*,p.post_title,p.guid FROM {$wpdb->prefix}epic_post_features as upf inner join  $wpdb->posts as p on p.ID = upf.post_id  WHERE upf.favorite_status = %d AND upf.user_id = %d", 1 , $user_id );
                }else{
                    return;
                }
            }else{
                $sql  = $wpdb->prepare( "SELECT upf.*,p.post_title,p.guid FROM {$wpdb->prefix}epic_post_features as upf inner join  $wpdb->posts as p on p.ID = upf.post_id  WHERE upf.favorite_status = %d AND upf.user_id = %d", 1 , $user_id );
            }

            $result = $wpdb->get_results($sql);
            if($result){
                
                $featured_enabled_types = (array) $epic_options->epic_settings['featured_image_enabled_types'];
                $featured_image_setting_status = in_array('favorite',$featured_enabled_types);  
                $featured_image_status = ($featured_image == 'yes' || $featured_image_setting_status);
                
                $content .= '<div class="epic-main epic-main-">';
                foreach($result as $row){
                    
                    
                    if($featured_image_status){
                        
                        $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($row->post_id), 'thumbnail');
                        $image_src = epic_url . 'img/default-post-thumbnail.png';
                        if($epic_options->epic_settings['favorite_default_featured_image'] != ''){
                            $image_src = $epic_options->epic_settings['favorite_default_featured_image'];
                        }
                        if (is_array($image_attributes) && ('' != $image_attributes[0])) {
                            $image_src = $image_attributes[0];
                        }
                        
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-feature-image">
                                        <img src="'.$image_src.'">
                                    </div>
                                    <div class="epic-post-feature-value">
                                        <span>
                                        <a href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                    </div>
                                </div>';
                        
                    }else{
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-field-type"><i class="epic-icon epic-icon-file-text"></i></div>
                                    <div class="epic-post-field-value">
                                        <span>
                                        <a href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                    </div>
                                </div>';
                    }

                    
                    
                }
                $content .= '</div><div class="epic-clear"></div>';
            }
//            echo "<pre>";print_r($result);exit;
        }
        
        return $content;
    }
    
    public function epic_reader_posts_list($atts){
        global $post,$wpdb,$epic_options;
        
        $reader_enabled_types           = (array) $epic_options->epic_settings['reader_enabled_post_types'];
        $reader_enabled_types = "'". implode("','",$reader_enabled_types) ."'";
        
        extract( shortcode_atts( array(
            'number_of_posts'   => 25 ,
            'featured_image' => 'no',
            'user_id' => 'no',
          ), $atts ) );
        
        $content = '';

        if($user_id != 'no'){
            $user_id = (int) $user_id;
            if( $user_id == '0' ){
                if(is_user_logged_in()){
                    $user_id = get_current_user_id(); 
                    
                    $sql  = $wpdb->prepare( "SELECT p.ID as post_id,post_title,p.guid,upf.read_status,upf.favorite_status,upf.recommend_status FROM $wpdb->posts as p  left join {$wpdb->prefix}epic_post_features as upf  on p.ID = upf.post_id and upf.user_id = %d  WHERE p.post_type in (".$reader_enabled_types.") and p.post_status = 'publish' order by p.post_date desc limit %d",  $user_id,$number_of_posts );
                }else{
                    return;
                }
            }else{
                $sql  = $wpdb->prepare( "SELECT p.ID as post_id,post_title,p.guid,upf.read_status,upf.favorite_status,upf.recommend_status FROM $wpdb->posts as p  left join {$wpdb->prefix}epic_post_features as upf  on p.ID = upf.post_id and upf.user_id = %d  WHERE p.post_type in (".$reader_enabled_types.") and p.post_status = 'publish' order by p.post_date desc limit %d",  $user_id,$number_of_posts );
            }


            $result = $wpdb->get_results($sql);

            if($result){
                
                $featured_enabled_types = (array) $epic_options->epic_settings['featured_image_enabled_types'];
                $featured_image_setting_status = in_array('reader',$featured_enabled_types);  
                $featured_image_status = ($featured_image == 'yes' || $featured_image_setting_status);
                
                $content .= '<div class="epic-main epic-main-">';
                foreach($result as $row){
                    
                    $read_status_val = '';
                    
                    
                    if($featured_image_status){
                        
                        if($row->read_status != '1'){
                            $read_status_val = '<span class="epic-read-list-post-status">'.__('Unread','epic').'</span>';
                        }
                        
                        $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($row->post_id), 'thumbnail');
                        $image_src = epic_url . 'img/default-post-thumbnail.png';
                        if($epic_options->epic_settings['reader_default_featured_image'] != ''){
                            $image_src = $epic_options->epic_settings['reader_default_featured_image'];
                        }
                        if (is_array($image_attributes) && ('' != $image_attributes[0])) {
                            $image_src = $image_attributes[0];
                        }
                        
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-feature-image">
                                        <img src="'.$image_src.'">
                                    </div>
                                    <div class="epic-post-feature-value">
                                        <span>
                                        <a target="_blank" href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                        '.$read_status_val.'
                                    </div>
                                </div>';
                        
                    }else{
                        if($row->read_status != '1'){
                            $read_status_val = '<span class="epic-read-list-post-status-plain">'.__('Unread','epic').'</span>';
                        }
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-field-type"><i class="epic-icon epic-icon-file-text"></i></div>
                                    <div class="epic-post-field-value">
                                        <span>
                                        <a target="_blank" href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                        '.$read_status_val.'
                                    </div>
                                </div>';
                    }

                    
                    
                }
                $content .= '</div><div class="epic-clear"></div>';
            }
        }
        
        return $content;
    }
    
    public function epic_recommended_posts_list($atts){
        global $post,$wpdb,$epic_options;
        
        extract( shortcode_atts( array(
            'number_of_posts'   => 25 ,
            'featured_image' => 'no',
            'user_id' => 'no',
          ), $atts ) );
        
        $content = '';

        if($user_id != 'no'){
            $user_id = (int) $user_id;
            if( $user_id == '0' ){
                if(is_user_logged_in()){
                    $user_id = get_current_user_id(); 
                    
                    $sql  = $wpdb->prepare( "SELECT upf.*,p.post_title,p.guid FROM {$wpdb->prefix}epic_post_features as upf inner join  $wpdb->posts as p on p.ID = upf.post_id  WHERE upf.recommend_status = %d AND upf.user_id = %d", 1 , $user_id );
                }else{
                    return;
                }
            }else{
                $sql  = $wpdb->prepare( "SELECT upf.*,p.post_title,p.guid FROM {$wpdb->prefix}epic_post_features as upf inner join  $wpdb->posts as p on p.ID = upf.post_id  WHERE upf.recommend_status = %d AND upf.user_id = %d", 1 , $user_id );
            }

            $result = $wpdb->get_results($sql);
            if($result){
                
                $featured_enabled_types = (array) $epic_options->epic_settings['featured_image_enabled_types'];
                $featured_image_setting_status = in_array('reader',$featured_enabled_types);  
                $featured_image_status = ($featured_image == 'yes' || $featured_image_setting_status);
                
                $content .= '<div class="epic-main epic-main-">';
                foreach($result as $row){
                    
                    
                    if($featured_image_status){
                        
                        $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($row->post_id), 'thumbnail');
                        $image_src = epic_url . 'img/default-post-thumbnail.png';
                        if($epic_options->epic_settings['recommend_default_featured_image'] != ''){
                            $image_src = $epic_options->epic_settings['recommend_default_featured_image'];
                        }
                        if (is_array($image_attributes) && ('' != $image_attributes[0])) {
                            $image_src = $image_attributes[0];
                        }
                        
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-feature-image">
                                        <img src="'.$image_src.'">
                                    </div>
                                    <div class="epic-post-feature-value">
                                        <span>
                                        <a href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                    </div>
                                </div>';
                        
                    }else{
                        $content .= '<div class="epic-field  epic-custom-post-list-field">
                                    <div class="epic-post-field-type"><i class="epic-icon epic-icon-file-text"></i></div>
                                    <div class="epic-post-field-value">
                                        <span>
                                        <a href="'.$row->guid.'">'.$row->post_title.'</a>
                                        </span>
                                    </div>
                                </div>';
                    }

                    
                    
                }
                $content .= '</div><div class="epic-clear"></div>';
            }
        }
        
        return $content;
    }
    
     public function profile_tab_items($display,$params){
        global $epic_options;

        $user_role_data = $this->get_post_features_status_by_role($params['id']);
        extract($user_role_data);
         
        if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
            $this->favorite_enabled_status && $favorite_user_role_status ){

            $display .= '<div class="epic-profile-tab" data-tab-id="epic-favorite-panel" >
                        <i class="epic-profile-icon epic-icon-bookmark"></i>
                    </div>';
        }
         
        if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
            $this->reader_enabled_status && $reader_user_role_status){

            $display .= '<div class="epic-profile-tab" data-tab-id="epic-reader-panel" >
                        <i class="epic-profile-icon epic-icon-th-list"></i>
                    </div>';
        }
         
        if( $this->recommend_enabled_status && $recommend_user_role_status){

            $display .= '<div class="epic-profile-tab" data-tab-id="epic-post-recommend-panel" >
                        <i class="epic-profile-icon epic-icon-share-square "></i>
                    </div>';
        }

        return $display;
    }
    
    public function intialize_posts_pages(){
        global $epic_options;
        $this->favorite_enabled_status = isset($epic_options->epic_settings['favorite_enabled_status']) ? $epic_options->epic_settings['favorite_enabled_status'] : '0' ;
        $this->reader_enabled_status = isset($epic_options->epic_settings['reader_enabled_status']) ? $epic_options->epic_settings['reader_enabled_status'] : '0';
        $this->recommend_enabled_status = isset($epic_options->epic_settings['recommend_enabled_status']) ? $epic_options->epic_settings['recommend_enabled_status'] : '0';
        $this->current_user = get_current_user_id();
        
    }
    
    public function profile_view_forms($display,$params){
        extract($params);
        
        $user_role_data = $this->get_post_features_status_by_role($params['id']);
        extract($user_role_data);
        
        
        if($view != 'compact'){
            if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
                $this->favorite_enabled_status && $favorite_user_role_status){

                $display .= '<div id="epic-favorite-panel" class="epic-profile-tab-panel" style="display:none;" >
                                '.do_shortcode("[epic_favorite_posts_list user_id=".$this->current_user." ]").'       
                            </div>';
            }
            
            if( is_user_logged_in() && $this->current_user && $this->current_user == $params['id'] &&
                $this->reader_enabled_status && $reader_user_role_status){

                $display .= '<div id="epic-reader-panel" class="epic-profile-tab-panel" style="display:none;" >
                                '.do_shortcode("[epic_reader_posts_list user_id=".$this->current_user." ]").'       
                            </div>';
            }
            
            if( $this->recommend_enabled_status && $recommend_user_role_status){

                $display .= '<div id="epic-post-recommend-panel" class="epic-profile-tab-panel" style="display:none;" >
                                '.do_shortcode("[epic_recommended_posts_list user_id=".$params['id']." ]").'       
                            </div>';
            }
        }

        return $display;
    }
    
    public function get_post_features_status_by_role($user_id){
        global $epic_roles,$epic_options;
        
        $user_roles = $epic_roles->epic_get_user_roles_by_id($user_id);
        $reader_enabled_roles           = (array) $epic_options->epic_settings['reader_enabled_user_roles'];
        $favorite_enabled_user_roles    = (array) $epic_options->epic_settings['favorite_enabled_user_roles'];
        $recommend_enabled_user_roles   = (array) $epic_options->epic_settings['recommend_enabled_user_roles'];
         
        $reader_enabled_status = false;
        if(in_array('all_roles',$reader_enabled_roles)){
            $reader_enabled_status = true;
        }else{
            foreach($reader_enabled_roles as $role){
                if(in_array($role,$user_roles)){
                    $reader_enabled_status = true;
                }
            }
        }
         
        $favorite_enabled_status = false;
        if(in_array('all_roles',$favorite_enabled_user_roles)){
            $favorite_enabled_status = true;
        }else{
            foreach($favorite_enabled_user_roles as $role){
                if(in_array($role,$user_roles)){
                    $favorite_enabled_status = true;
                }
            }
        }

        $recommend_enabled_status = false;
        if(in_array('all_roles',$recommend_enabled_user_roles)){
            $recommend_enabled_status = true;
        }else{
            foreach($recommend_enabled_user_roles as $role){
                if(in_array($role,$user_roles)){
                    $recommend_enabled_status = true;
                }
            }
        }
        
        $statuses['reader_user_role_status'] = $reader_enabled_status;
        $statuses['favorite_user_role_status'] = $favorite_enabled_status;
        $statuses['recommend_user_role_status'] = $recommend_enabled_status;
        return $statuses;
    }

}




add_action( 'plugins_loaded', 'epic_posts_pages_plugin_init' );

function epic_posts_pages_plugin_init(){
    global $epic_posts_pages;
    $epic_posts_pages = new epic_Posts_Pages();
}