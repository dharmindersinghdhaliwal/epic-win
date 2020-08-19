<?php
    global $epic_admin;
    if( !get_option('epic_email_templates')){
        global $epic_email_templates;
        $epic_email_templates->epic_reset_all_templates();
    }
?>

<tr valign="top">
    <th scope="row"><label for="Email Template Title"><?php _e('Email Template Title', 'epic'); ?></label></th>
    <td>
        <?php 
            $email_templates = array(                                
                                '0'                             => __('Select Template', 'epic'),
                                'reg_default_user'              => __('Default registration for users', 'epic'),
                                'reg_default_admin'             => __('Default registration for admin', 'epic'),
                                'reg_activation_approval_user'  => __('Registration with email confirmation and approval for Users', 'epic'),
                                'reg_activation_approval_admin' => __('Registration with email confirmation and approval for Admin', 'epic'),
                                'reg_activation_user'           => __('Registration with email confirmation for Users', 'epic'),
                                'reg_activation_admin'          => __('Registration with email confirmation for Admin', 'epic'),
                                'reg_approval_user'             => __('Registration with approval for Users', 'epic'),
                                'reg_approval_admin'            => __('Registration with approval for Admin', 'epic'),
                                'nofify_profile_update'         => __('Notify Profile Update', 'epic'),
                                'forgot_password'               => __('Forgot Password', 'epic'),
                                'approval_notify_user'          => __('User approval notification for users', 'epic'),
                                'delete_profile_confirm'        => __('Profile removal confirmation for users', 'epic'),
                                        // 'reg_approval_user'         => __('Registration with approvals for users', 'epic'),
                                        // 'reg_approval_admin'        => __('Registration with approvals for admin', 'epic'),
                                    );
            
            echo epic_Html::drop_down(array('name'=>'email_template','id'=>'email_template','class'=> 'chosen-admin_setting', 'style' => 'width:80%'), $email_templates, '0');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select the email template name.', 'epic') ?>"></i>
    </td>
</tr>
<tr valign="top" style='display:none'>
    <th scope="row"><label for="Email Status"><?php _e('Email Status', 'epic'); ?></label></th>
    <td class='site_content_allowed_roles'>
        <?php 
            $email_statuses = array('0' => __('Disabled - Don\'t send Email', 'epic'), '1' => __('Enabled - Send Email', 'epic'));
            echo epic_Html::drop_down(array('name'=>'email_status','id'=>'email_status','class'=> 'chosen-admin_setting'), $email_statuses, '1');
            
            
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Enable/Disable sending a specific email.', 'epic') ?>"></i>
    </td>
</tr>
<tr valign="top" style='display:none'>
    <th scope="row"><label for="Email Subject"><?php _e('Email Subject', 'epic'); ?></label></th>
    <td>
        <?php 
           
            echo epic_Html::text_box(array('name' => 'email_subject', 'id' => 'email_subject', 'class' => 'regular-text', 'value' => ''));
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Edit the subject of email template.', 'epic') ?>"></i>
    </td>
</tr>
<tr valign="top" style='display:none'>
    <th scope="row"><label for="Email Template Editor"><?php _e('Email Template Editor', 'epic'); ?></label></th>
    <td >
        <?php 
            echo epic_Html::text_area(array('name' => 'email_template_editor', 'id' => 'email_template_editor', 'class' => 'large-text code text-area', 'value' => '', 'cols' => '50', 'style' => 'min-height:300px;width:90% !important;'));
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Edit the contents of email template.', 'epic') ?>"></i>
    </td>
</tr>