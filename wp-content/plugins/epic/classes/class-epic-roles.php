<?php

class epic_Roles {

    private $user_roles;

    function __construct() {
        
    }

    /* Returns the available user roles for the application. */
    public function epic_get_available_user_roles($mode = '') {
        global $wp_roles;

        $roles = $wp_roles->get_names();
        // EDit mode automaticaaly contains admin role. So it's removed for selections
        if($mode == 'edit'){
            unset($roles['administrator']);
        }

        return $roles;
    }

    /* Get the roles of the given user */
    public function epic_get_user_roles_by_id($user_id) {
        $user = new WP_User($user_id);
        if (!empty($user->roles) && is_array($user->roles)) {
            $this->user_roles = $user->roles;
            return $user->roles;
        } else {
            $this->user_roles = array();
            return array();
        }
    }

    /* Load fields with empty data based on user show permission */
    public function epic_empty_fields_by_user_role($show_to_user_role, $show_to_user_role_list) {
        global $epic;

        $show_status = FALSE;
        if ('0' == $show_to_user_role) {
            $show_status = TRUE;
        } else {
            $show_to_user_role_list = explode(',', $show_to_user_role_list);

            foreach ($this->user_roles as $role) {
                if (in_array($role, $show_to_user_role_list)) {
                    $show_status = TRUE;
                }
            }
        }


        return $show_status;

    }

    /* Check the permission to show/edit given field by user Id */
    public function epic_fields_by_user_role($user_role, $user_role_list) {

        $show_status = FALSE;
        if ('0' == $user_role) {
            $show_status = TRUE;
        } else {

           if('' !=  $user_role_list){
                $user_role_list = explode(',', $user_role_list);
                foreach ($this->user_roles as $role) {
                    if (in_array($role, $user_role_list)) {
                        $show_status = TRUE;
                    }
                }
            }
            
        }
        return $show_status;
    }

    /* Setting for available user roles at registration */
    public function epic_available_user_roles_registration(){
        global $wp_roles;
        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

        $skipped_roles = array('administrator');

        foreach( $wp_roles->role_names as $role => $name ) {
            if(!in_array($role, $skipped_roles)){
                $user_roles[$role] = $name;
            }
        }

        return $user_roles;
    }

    /* Setting for alowed user roles from available user roles */
    public function epic_allowed_user_roles_registration(){
        global $wp_roles;

        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

        $current_option = get_option('epic_options');

        $user_roles_registration = $current_option['choose_roles_for_registration'];

        
        $allowed_user_roles = is_array($user_roles_registration) ? $user_roles_registration : array($user_roles_registration);

        $default_role = get_option("default_role");
        if(!in_array($default_role, $allowed_user_roles)){
            array_push($allowed_user_roles, $default_role);
        }

        if('' == $current_option['choose_roles_for_registration']){
            $user_roles[$default_role] = $wp_roles->role_names[$default_role];
            return $user_roles;
        }

        foreach ($allowed_user_roles as $usr_role) {
            $user_roles[$usr_role] = $wp_roles->role_names[$usr_role];
        }

        
        return $user_roles;
    }


    /* Setting for available user roles for viewing other profiles */
    public function epic_available_user_roles_view_profile(){
        global $wp_roles;
        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

        $skipped_roles = array();

        foreach( $wp_roles->role_names as $role => $name ) {
            if(!in_array($role, $skipped_roles)){
                $user_roles[$role] = $name;
            }
        }

        return $user_roles;
    }

    /* Setting for available user roles for restriction rules */
    public function epic_available_user_roles_restriction_rules(){
        global $wp_roles;
        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

        $skipped_roles = array('administrator');

        foreach( $wp_roles->role_names as $role => $name ) {
            if(!in_array($role, $skipped_roles)){
                $user_roles[$role] = $name;
            }
        }

        return $user_roles;
    }
    
    /* Get a list of all administrators of the site */
    public function get_admins_list(){
        
        $users_query = new WP_User_Query( array( 
                'role' => 'administrator', 
                'orderby' => 'display_name'
                ) );
        $results = $users_query->get_results();
        return $results;
    }
    
    /* Get a list of emails administrators of the site */
    public function get_admin_emails(){
        
        $admin_emails = array();
            
        $results = $this->get_admins_list();
        foreach($results as $user){
            array_push($admin_emails,$user->user_email);
        }
        
        $admin_main_email = get_option('admin_email');
        if(!in_array($admin_main_email, $admin_emails)){
            array_push($admin_emails,$admin_main_email);
        }
        
        return $admin_emails;
    }
    
    public function get_active_users(){
        
        $users_query = new WP_User_Query( array( 
                'meta_query' => array(
                    'relation' => 'AND',
                    0 => array(
                        'key'     => 'epic_user_profile_status',
                        'value'   => 'ACTIVE',
                        'compare' => '='
                    ),
                    1 => array(
                        'key'     => 'epic_approval_status',
                        'value'   => 'ACTIVE',
                        'compare' => '='
                    ),
                    2 => array(
                        'key'     => 'epic_activation_status',
                        'value'   => 'ACTIVE',
                        'compare' => '='
                    )
                )
                ) );
        $results = $users_query->get_results();
        return $results;
        
    }

    /* hide fields by user role */
    public function epic_hide_fields_by_user_role_status($user_id,$user_role_list) {

        $show_status = TRUE;
        $current_user_roles = $this->epic_get_user_roles_by_id($user_id);
        if('' !=  $user_role_list){
            $user_role_list = explode(',', $user_role_list);
            foreach ($current_user_roles as $role) {
                if (in_array($role, $user_role_list)) {
                    $show_status = FALSE;
                }
            }
        }

        return $show_status;
    }
}

$epic_roles = new epic_Roles();