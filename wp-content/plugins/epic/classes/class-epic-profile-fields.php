<?php

class epic_Profile_Fields {

    private $user_roles;
    private $epic_profile_statuses;

    function __construct() {
        $this->epic_profile_statuses = array(
                                            'INACTIVE' => __('Inactive','epic'),
                                            'ACTIVE'   => __('Active','epic')
                                        );

        /* epic Filter for adding custom profile status */
        $epic_custom_profile_statuses = apply_filters('epic_custom_profile_statuses',array());
        $this->epic_profile_statuses = array_merge($this->epic_profile_statuses,$epic_custom_profile_statuses);
        // End Filter
    }

    /* Returns the available mandatory fields for backend profile */
    public function epic_backend_mandatory_fields($epic_settings,$user) {

        $display = '';

        if($epic_settings['profile_view_status'] || current_user_can('manage_options') || current_user_can('manage_epic_options') ){
      
            $display .= '<tr>';
            $profile_status_label = __('Profile Status','epic');
            $display .= '<th scope="row"><label for="' . $profile_status_label . '">' . $profile_status_label . '</label></th>';

            $current_profile_status = esc_attr(get_the_author_meta('epic_user_profile_status', $user->ID));

            $display .= '<td><select class="input" name="epic[epic_user_profile_status]" id="epic_user_profile_status">';
                        foreach ($this->epic_profile_statuses as $status=>$display_status) {
                            $status = trim($status);

                            $display .= '<option value="' . $status . '" ' . selected($current_profile_status, $status, 0) . '>' . $display_status . '</option>';
                        }
            $display .= '</select></td></tr>';
        }
        
        if($epic_settings['email_two_factor_verification_status'] || current_user_can('manage_options') || current_user_can('manage_epic_options') ){
            
            $display .= '<tr>';
            $label = __('Email Authentication','epic');
            $display .= '<th scope="row"><label for="' . $label . '">' . $label . '</label></th>';

            $current_profile_status = esc_attr(get_the_author_meta('epic_email_two_factor_status', $user->ID));

            $display .= '<td><select class="input" name="epic[epic_email_two_factor_status]" id="epic_email_two_factor_status">';
            $display .= '<option value="0" ' . selected($current_profile_status, '0', 0) . '>' . __('Disable','epic') . '</option>';
            $display .= '<option value="1" ' . selected($current_profile_status, '1', 0) . '>' . __('Enable','epic') . '</option>';
               
            $display .= '</select></td></tr>';
            
        }

        return $display;
    }

    public function epic_frontend_mandatory_fields($epic_settings,$user_id,$profile_user_id){

        $display = '';

        if($epic_settings['profile_view_status']){

            $current_profile_status = esc_attr(get_the_author_meta('epic_user_profile_status', $profile_user_id));

            $display .= '<div class="epic-field epic-edit">';
            $display .= '<label class="epic-field-type" for="epic_user_profile_status-' . $profile_user_id . '">';

            $name     = __('Profile Status','epic');
            
            $display .= '<i class="epic-icon epic-icon-unlock-alt"></i>';
            $display .= '<span>' . apply_filters('epic_edit_profile_label_epic_user_profile_status', $name) . '</span></label>';
    
            $display .= '<div class="epic-field-value">';
            $display .= '<select class="epic-input " name="epic_user_profile_status-' . $profile_user_id . '" id="epic_user_profile_status-' . $profile_user_id . '" >';
                            foreach ($this->epic_profile_statuses as $status=>$display_status) {
                                $status = trim($status);

                                $display .= '<option value="' . $status . '" ' . selected($current_profile_status, $status, 0) . '>' . $display_status . '</option>';
                            }
            $display .= '</select>';
            $display .= '<div class="epic-clear"></div>';
            $display .= '</div></div>';
        }
        
        if($epic_settings['email_two_factor_verification_status']){
            
            $current_profile_status = esc_attr(get_the_author_meta('epic_email_two_factor_status', $profile_user_id));

            $display .= '<div class="epic-field epic-edit">';
            $display .= '<label class="epic-field-type" for="epic_email_two_factor_status-' . $profile_user_id . '">';

            $name     = __('Email Authentication','epic');
            
            $display .= '<i class="epic-icon epic-icon-unlock-alt"></i>';
            $display .= '<span>' . apply_filters('epic_edit_profile_label_email_two_factor_status', $name) . '</span></label>';
    
            $display .= '<div class="epic-field-value">';
            $display .= '<select class="epic-input " name="epic_email_two_factor_status-' . $profile_user_id . '" id="epic_email_two_factor_status-' . $profile_user_id . '" >';
            $display .= '<option value="0" ' . selected($current_profile_status, '0', 0) . '>' . __('Disable','epic') . '</option>';
            $display .= '<option value="1" ' . selected($current_profile_status, '1', 0) . '>' . __('Enable','epic') . '</option>';                            
                            
            $display .= '</select>';
            $display .= '<div class="epic-clear"></div>';
            $display .= '</div></div>';
            
        }

        return $display;

    }
}

$epic_profile_fields = new epic_Profile_Fields();