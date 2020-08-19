<?php
    global $epic_admin;
?>


<tr valign="top">
    <th scope="row"><label for="Restricted Section"><?php _e('Restricted Section', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_restriction_section = array(                                
                                        'all_pages'                             => __('Restrict All Pages', 'epic'),
                                        'all_posts'                             => __('Restrict All Posts', 'epic'),
                                        'restrict_selected_pages'               => __('Restrict Selected Pages', 'epic'),
                                        'restrict_selected_posts'               => __('Restrict Selected Posts', 'epic'),
                                        'restrict_sub_selected_pages'           => __('Restrict Sub Pages of Selected Pages', 'epic'),
                                        'restrict_sub_include_selected_pages'   => __('Restrict Sub Pages of Selected Pages (Including Parent Page)', 'epic'),
                                        'restrict_posts_by_categories'          => __('Restrict Posts by Category', 'epic'),
                                    );
            
            echo epic_Html::drop_down(array('name'=>'site_content_section_restrictions','id'=>'site_content_section_restrictions','class'=> 'chosen-admin_setting'), $site_restriction_section, 'all_pages');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select type of content to be restricted.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="<?php _e('Restrict Sub Pages Recursively','epic'); ?>"><?php _e('Restrict Sub Pages Recursively', 'epic'); ?></label></th>
    <td>
        <?php 
            $recursive_page_restrictions = array('0' => __('Disabled','epic'), '1' => __('Enabled','epic') );
            echo epic_Html::drop_down(array('name'=>'site_content_page_recursive_status','id'=>'site_content_page_recursive_status','class'=> 'chosen-admin_setting'), $recursive_page_restrictions, '');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Enable/Disable recursive restriction of sub pages.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="<?php _e('Restricted Pages','epic'); ?>"><?php _e('Restricted Pages', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_content_allowed_pages = $epic_admin->get_all_pages();
            echo epic_Html::drop_down(array('name'=>'site_content_page_restrictions[]','id'=>'site_content_page_restrictions','class'=> 'chosen-admin_setting','multiple'=>''), $site_content_allowed_pages, '');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select pages to be restricted.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="<?php _e('Restricted Posts','epic'); ?>"><?php _e('Restricted Posts', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_content_allowed_posts = $epic_admin->get_all_posts();
            echo epic_Html::drop_down(array('name'=>'site_content_post_restrictions[]','id'=>'site_content_post_restrictions','class'=> 'chosen-admin_setting','multiple'=>''), $site_content_allowed_posts, '');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select posts to be restricted.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="<?php _e('Restricted Categories','epic'); ?>"><?php _e('Restricted Categories', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_content_allowed_categories = $epic_admin->get_all_categories();
            echo epic_Html::drop_down(array('name'=>'site_content_category_restrictions[]','id'=>'site_content_category_restrictions','class'=> 'chosen-admin_setting','multiple'=>''), $site_content_allowed_categories, '');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Posts of selected categories to be restricted.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Allowed Users"><?php _e('Allowed Users', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_restriction_by = array(
                                        'by_all_users'    => __('All Logged in Users', 'epic'),
                                        'by_user_roles'     => __('Specific User Roles', 'epic')
                                    );
            
            echo epic_Html::drop_down(array('name'=>'site_content_user_restrictions','id'=>'site_content_user_restrictions','class'=> 'chosen-admin_setting'), $site_restriction_by, 'by_guest_users');
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select what type of users have access to restricted content.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Allowed User Roles"><?php _e('Allowed User Roles', 'epic'); ?></label></th>
    <td class='site_content_allowed_roles'>
        <?php 
            global $epic_roles;
            $site_allowed_roles = $epic_roles->epic_available_user_roles_restriction_rules();
            
            $checked_value = '';
            foreach ($site_allowed_roles as $role_key => $role) {
                echo epic_Html::check_box(array('name' => 'site_content_allowed_roles[]', 'id' => 'site_content_allowed_roles', 'value' => $role_key),$checked_value).$role.'<br/>';            
            }
            
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select which user roles will be allowed to access this content.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Redirect URL"><?php _e('Redirect URL', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_redirect_allowed_pages = $epic_admin->get_all_pages();
            echo epic_Html::drop_down(array('name'=>'site_content_redirect_url','id'=>'site_content_redirect_url','class'=> 'chosen-admin_setting'), $site_redirect_allowed_pages, '0');
        
                       
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Specify the redirection URL for users with unauthorized access based on this rule.', 'epic') ?>"></i>
    </td>
</tr>

