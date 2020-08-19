<?php $epic_options = get_option('epic_options'); ?>

<div class="wrap">
    <h2><?php _e('epic - Settings','epic')?></h2>
    <div class="updated" id="epic-settings-saved" style="display:none;">
        <p><?php _e('Settings Saved','epic');?></p>
    </div>
    
    <div class="updated" id="epic-settings-reset" style="display:none;">
        <p><?php _e('Settings Reset Completed.','epic');?></p>
    </div>
    
    <div id="epic-tab-group" class="epic-tab-group vertical_tabs">
        <ul id="epic-tabs" class="epic-tabs">
            <li class="epic-tab active" id="epic-general-settings-tab"><?php _e('General Settings','epic');?></li>
            <li class="epic-tab" id="epic-profile-settings-tab"><?php _e('User Profile Settings','epic')?></li>
            <li class="epic-tab" id="epic-system-pages-tab"><?php _e('epic System Pages','epic')?></li>
            <li class="epic-tab" id="epic-redirect-setting-tab"><?php _e('Redirect Settings','epic')?></li>
            <li class="epic-tab" id="epic-registration-option-tab"><?php _e('Registration Options','epic')?></li>
            <li class="epic-tab" id="epic-search-settings-tab"><?php _e('Search Settings','epic')?></li>
            <li class="epic-tab" id="epic-privacy-option-tab"><?php _e('Privacy Options','epic')?></li>
            <li class="epic-tab" id="epic-misc-messages-tab" ><?php _e('Messages','epic')?></li>
            <li class="epic-tab" id="epic-scripts-styles-tab" ><?php _e('Scripts and Styles','epic')?></li>
        </ul>
        <div id="epic-tab-container" class="epic-tab-container" style="min-height: 325px;">
            <div class="epic-tab-content-holder">
                <div class="epic-tab-content" id="epic-general-settings-content">
                    <h3><?php _e('General Settings','epic');?></h3>
                    <form id="epic-general-settings-form">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><label for="style"><?php _e('Style', 'epic'); ?></label></th>
                                    <td>
                                        <?php 
                                            $custom_styles = glob(epic_path.'styles/*.css');
                                            $styles[] =  __('None - I will use custom CSS','epic');
                                            
                                            if(is_array($custom_styles))
                                            {
                                                foreach($custom_styles as $key=>$value)
                                                {
                                                    $name = str_replace('.css','',str_replace(epic_path.'styles/','',$value));
                                                    
                                                    $styles[$name] = $name;
                                                }
                                            }
                                            
                                            echo epic_Html::drop_down(array('name'=>'style','id'=>'style'), $styles, $this->options['style']);
                                            
                                        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select Theme Style or disable CSS output to use your own custom CSS.', 'epic') ?>"></i>
                                    </td>
                                </tr>
                                
                                <tr valign="top">
                                    <th scope="row"><label for="date_format"><?php _e('Date Format', 'epic'); ?></label></th>
                                    <td>
                                    <?php 
                                        
                                        $property = array('name'=>'date_format','id' => 'date_format');

                                        $version_message = '';
                                        if(!function_exists('date_create_from_format') && '-1' == epic_php_version_status){
                                            $data = array(
                                                    'mm/dd/yy' => date('m/d/Y')                                                    
                                            );

                                            $version_message = __('Your server is running PHP '.phpversion().', which does nto support custom date formats. Update to PHP 5.3+ for custom date formats','epic');
                                        }else{
                                            $data = array(
                                                    'mm/dd/yy' => date('m/d/Y'),
                                                    'yy/mm/dd' => date('Y/m/d'),
                                                    'dd/mm/yy' => date('d/m/Y'),
                                                    'yy-mm-dd' => date('Y-m-d'),
                                                    'dd-mm-yy' => date('d-m-Y'),
                                                    'mm-dd-yy' => date('m-d-Y'),
                                                    'MM d, yy' => date('F j, Y'),
                                                    'd M, y' => date('j M, y'),
                                                    'd MM, y' => date('j F, y'),
                                                    'DD, d MM, yy' => date('l, j F, Y')
                                            ); 
                                        }
                                        
                                        echo epic_Html::drop_down($property, $data, $this->options['date_format']);
                                    
                                    ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Select the date format to be used for date picker.', 'epic') ?>"></i>
                                    <p class="description"><?php echo $version_message; ?></p>
                                    </td>
                                </tr>
                                
                                <?php 
                                    
                                    
                                    $this->add_plugin_setting(
                                        'select',
                                        'hide_frontend_admin_bar',
                                        __('Admin Bar', 'epic'),
                                        array(
                                            'enabled' => __('Enabled', 'epic'),
                                            'hide_from_non_admin' => __('Hide from Non-Admin Users', 'epic'),
                                            'hide_from_all' => __('Hide from All Users', 'epic')
                                        ),
                                        __('Optionally hide the WordPress admin bar for logged in users on frontend pages.', 'epic'),
                                        __('Enabled will show the WordPress admin bar to all users. You amy select an option to hide the admin bar on frontend for non-admin users or all users.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'lightbox_avatar_cropping',
                                            __('Lighbox Avatar Cropping', 'epic'),
                                            '1',
                                            __('If checked, users will be able to crop avatar images in a lightbox.', 'epic'),
                                            __('Unchecking this option will enable the default file upload instead of lightbox cropping.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'ajax_profile_field_save',
                                            __('Use AJAX on Custom Fields', 'epic'),
                                            '1',
                                            __('If checked, backend custom fields will be updated and sorted using AJAX.', 'epic'),
                                            __('Checking this option will enable AJAX on backend custom fields. Useful for working with large number of custom fields .', 'epic')
                                    );

                                ?>
                                
                                <tr valign="top">
                                    <th scope="row"><label>&nbsp;</label></th>
                                    <td>
                                        <?php 
                                            echo epic_Html::button('button', array('name'=>'save-epic-general-settings-tab', 'id'=>'save-epic-general-settings-tab', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                            echo '&nbsp;&nbsp;';
                                            echo epic_Html::button('button', array('name'=>'reset-epic-general-settings-tab', 'id'=>'reset-epic-general-settings-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                        ?>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-profile-settings-content" style="display:none;">
                    <h3><?php _e('User Profile Settings','epic');?></h3>
                    <form id="epic-profile-settings-form">
                        <table class="form-table">
                            <tbody>
                                <?php 
                                    $this->add_plugin_setting(
                                            'select',
                                            'clickable_profile',
                                            __('Display Name / User Link Options', 'epic'),
                                            array(
                                                    1 => __('Link to user profiles', 'epic'),
                                                    2 => __('Link to author archives', 'epic'),
                                                    0 => __('No link, show as static text', 'epic')),
                                            __('Enable/disable linking of Display Names on user profiles', 'epic'),
                                            __('This is where the display name on user profiles will link.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'select',
                                            'profile_url_type',
                                            __('Profile Permalinks', 'epic'),
                                            array(
                                                1 => __('User ID', 'epic'),
                                                2 => __('Username', 'epic')),
                                            __('Select profile link type.','epic') .'<br />' .
											__('Username will be written as <code>profile/username/</code>','epic') .'<br />' .
											__('User ID will be writtne as <code>profile/1/</code>', 'epic'),
                                            __('This is the rewrite rule used to link to user profiles.', 'epic')
                                    );
                                    
                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'link_author_posts_page',
                                            __('Link Post Count to Author Archive', 'epic'),
                                            '1',
                                            __('If checked, post/entries count on user profiles will link to the Author archive page.', 'epic'),
                                            __('Unchecking this option will show post count in text only, without linking to Author archive.', 'epic')
                                    );
                                    
                                    $this->add_plugin_setting(
                                            'input',
                                            'avatar_max_size',
                                            __('Maximum allowed user image size', 'epic'), array(),
                                            sprintf(__('Provide file size in megabytes, decimal values are accepted. Your server configuration supports up to <strong>%s</strong>', 'epic'), ini_get('upload_max_filesize')),
                                            __('Users will receive an error message if they try to upload files larger than the limit set here.', 'epic')
                                    );
                                    
                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'show_separator_on_profile',
                                            __('Show separators on profiles', 'epic'), array(),
                                            __('<p>If checked, separators will be displayed when viewing front-end profiles.<br /> Otherwise, separators are displayed only on the registration form and when editing profiles.<br />','epic') .
                                            __('If you are using this option, it is recommended to also enable the next option to show empty fields on profiles.</p>', 'epic'),
                                            __('Separators may be added & edited in the epic Custom Fields section. When using this option, it is recommended to also check the option below to show empty fields on profiles.', 'epic')
                                    );
                                    
                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'show_empty_field_on_profile',
                                            __('Show empty fields on profiles', 'epic'), array(),
                                            __('<p>If checked, empty fields will be displayed when viewing front-end profiles.<br /> Otherwise, only fields populated with data are when viewing front-end profiles.</p>', 'epic'),
                                            __('Empty fields are fields where a user has not filled in any data.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'select',
                                            'profile_title_field',
                                            __('Field for Profile Title', 'epic'),
                                            $this->epic_profile_title_fields(),                                            
                                            __('Select the field to be displayed as profile title. Default and recommeneded choice is <code>Display Name</code>.', 'epic'),
                                            __('This field will be used as the display name on user profiles.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'show_recent_user_posts',
                                            __('Show recent user posts on profiles', 'epic'), array(),
                                            __('If checked, recent posts of the user will be displayed under profile information.', 'epic'),
                                            __('User need to be the author of these posts.', 'epic')
                                    );

                                    $allowed_post_counts = array_combine( range(1,10), range(1,10));
                                    
                                    $this->add_plugin_setting(
                                            'select',
                                            'maximum_allowed_posts',
                                            __('Maximum number of posts', 'epic'),
                                            $allowed_post_counts,
                                            __('Select maximum post count allowed for user profiles.', 'epic'),
                                            __('Given number of user posts are displayed under the profile.', 'epic')
                                    );
                                    

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'show_feature_image_posts',
                                            __('Show featured images on posts', 'epic'), array(),
                                            __('If checked, featured image thumbnails will be displayed with the users recent posts.', 'epic'),
                                            __('If feature image doesnt exist, a default image will be used.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'select',
                                            'website_link_on_profile',
                                            __('Website links on user profiles', 'epic'),
                                            array(
                                                    0 => __('No link, plain text', 'epic'),
                                                    1 => __('Live link', 'epic')),
                                            __('Enable/disable linking of Website on user profiles', 'epic'),
                                            __('This is where the website on user profiles will link.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'input',
                                            'profile_modal_window_shortcode',
                                            __('Shortcode for Profile Popup', 'epic'), array(),
                                            sprintf(__('Provide [epic] shortcode with neccessary attribute values for profile modal window', 'epic'), ini_get('upload_max_filesize')),
                                            __('When modal window is enabled on profile links, this shortcode will be used', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'profile_view_status',
                                            __('Enable Profile Status', 'epic'), array(),
                                            __('If checked, users will be able to change the profile status to active/inactive.', 'epic'),
                                            __('Admin can check this feature to enable the private status feature for users.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'display_profile_status',
                                            __('Display Profile Status', 'epic'), array(),
                                            __('If checked, users will be able to see the status of the profile along with other profile fields.', 'epic'),
                                            __('Admin can check this feature to display the profile status inside profiles.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'link_post_author_to_epic',
                                            __('Link Post Author Link to epic Profile', 'epic'),
                                            array(),
                                            __('If checked, author link in posts will link to the epic profile page.', 'epic'),
                                            __('Unchecking this option will link post author to Author archive.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'display_profile_after_post',
                                            __('Display epic Profile for Post Author after Post Content', 'epic'),
                                            array(),
                                            __('If checked, epic profile of author is displayed after the post content.', 'epic'),
                                            __('Unchecking this option will not display epic author profile.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'select',
                                            'author_post_profile_template',
                                            __('Template for Post Author Profiles', 'epic'),
                                            array(
                                                    '0' => __('Default Design', 'epic'),
                                                    'author_design_one' => __('Author Profile Design 1', 'epic'),
                                                    'author_design_two' => __('Author Profile Design 2', 'epic'),
                                                    'author_design_three' => __('Author Profile Design 3', 'epic'),
                                                    'author_design_four' => __('Author Profile Design 4', 'epic'),
                                                ),
                                            __('Select the template for displaying author profile.', 'epic'),
                                            __('Select the template for displaying author profile.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'email_two_factor_verification_status',
                                            __('Enable Two-Factor authentication with Email', 'epic'), array(),
                                            __('If checked, users will be able to enable Two-Factor authentication with email.', 'epic'),
                                            __('Admin can check this feature to enable Two-Factor authentication with email for users.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                        'select',
                                        'profile_tabs_display_status',
                                        __('Profile Tabs Status', 'epic'),
                                        array(
                                            'disabled' => __('Disabled for All Users', 'epic'),
                                            'enabled' => __('Enabled for All Users', 'epic'),
                                            'enabled_members' => __('Enabled for Logged-In Users', 'epic'),
                                            'enabled_owner' => __('Enabled for Profile Owner', 'epic'),
                                            
                                        ),
                                        __('Enable/disable the profile tabs section', 'epic'),
                                        __('Enable/disable the profile tabs section based on user types.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                        'select',
                                        'profile_tabs_initial_display_status',
                                        __('Profile Tabs Display Status', 'epic'),
                                        array(
                                            'disabled' => __('Hide by default', 'epic'),
                                            'enabled' => __('Display by default', 'epic'),
                                            
                                        ),
                                        __('Display/hide the profile tabs section', 'epic'),
                                        __('Display/hide the profile tabs section in intial view.', 'epic')
                                    );

                                    $this->add_plugin_setting(
                                        'select',
                                        'delete_user_profiles',
                                        __('Delete User Profiles', 'epic'),
                                        array(
                                            'disabled' => __('Users are not allowed to delete profile', 'epic'),
                                            'enabled' => __('Users are allowed to delete profile', 'epic'),
                                            
                                        ),
                                        __('Enable/disable deleting profiles for users.', 'epic'),
                                        __('If enabled, Admin and profile owner will have the permission to delete the profile.', 'epic')
                                    );


                                    $this->add_plugin_setting(
                                            'checkbox',
                                            'profile_cover_image_status',
                                            __('Show Cover Image on Profiles', 'epic'),
                                            '0',
                                            __('If checked, cover image will be displayed on top of the profile header section.', 'epic'),
                                            __('Unchecking this option will display the default profile design without cover image.', 'epic')
                                    );
                                    

                                ?>
                                
                                <tr valign="top">
                                    <th scope="row"><label>&nbsp;</label></th>
                                    <td>
                                        <?php 
                                            echo epic_Html::button('button', array('name'=>'save-epic-profile-settings-tab', 'id'=>'save-epic-profile-settings-tab', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                            echo '&nbsp;&nbsp;';
                                            echo epic_Html::button('button', array('name'=>'reset-epic-profile-settings-tab', 'id'=>'reset-epic-profile-settings-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                            
                                        ?>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-system-pages-content" style="display:none;">
                    <h3><?php _e('epic System Pages','epic');?></h3>
                    <p><?php _e('These pages are automatically created when epic is activated. You can leave them as they are or change to custom pages here.', 'epic'); ?></p>
                    <form id="epic-system-pages-form">
                        <table class="form-table">
                            <tbody>
                            <?php
                                
                            
                                $profile_page_id = isset($epic_options['profile_page_id']) ? $epic_options['profile_page_id'] : '0';
                                $profile_page_btn = ($profile_page_id == '0') ? '' : "<a class='epic-admin-setting-view-link' target='_blank' href='".get_permalink($profile_page_id)."'>". __('View Page','epic') ."</a>";

                                $login_page_id = isset($epic_options['login_page_id']) ? $epic_options['login_page_id'] : '0';
                                $login_page_btn = ($login_page_id == '0') ? '' : "<a class='epic-admin-setting-view-link' target='_blank' href='".get_permalink($login_page_id)."'>". __('View Page','epic') ."</a>";

                                $registration_page_id = isset($epic_options['registration_page_id']) ? $epic_options['registration_page_id'] : '0';
                                $registration_page_btn = ($registration_page_id == '0') ? '' : "<a class='epic-admin-setting-view-link' target='_blank' href='".get_permalink($registration_page_id)."'>". __('View Page','epic') ."</a>";

                                $reset_password_page_id = isset($epic_options['reset_password_page_id']) ? $epic_options['reset_password_page_id'] : '0';
                                $reset_password_page_btn = ($reset_password_page_id == '0') ? '' : "<a class='epic-admin-setting-view-link' target='_blank' href='".get_permalink($reset_password_page_id)."'>". __('View Page','epic') ."</a>";

                                $member_list_page_id = isset($epic_options['member_list_page_id']) ? $epic_options['member_list_page_id'] : '0';
                                $member_list_page_btn = ($member_list_page_id == '0') ? '' : "<a class='epic-admin-setting-view-link' target='_blank' href='".get_permalink($member_list_page_id)."'>". __('View Page','epic') ."</a>";

                                $this->add_plugin_setting(
                                        'select',
                                        'profile_page_id',
                                        __('epic Profile Page', 'epic'),
                                        $this->get_all_pages(),
                                        __('If you wish to change default epic Profile page, you may set it here. Make sure you have the <code>[epic]</code> shortcode on this page.', 'epic') . $profile_page_btn,
                                        __('This page is where users will view their own profiles, or view other user profiles from the member directory if allowed.', 'epic') 
                                );
                                
                                $this->add_plugin_setting(
                                        'select',
                                        'login_page_id',
                                        __('epic Login Page', 'epic'),
                                        $this->get_all_pages(),
                                        __('If you wish to change default epic login page, you may set it here. Make sure you have the <code>[epic_login]</code> shortcode on this page.', 'epic') . $login_page_btn,
                                        __('The default front-end login page.', 'epic')
                                );
                                
                                
                                $this->add_plugin_setting(
                                        'select',
                                        'registration_page_id',
                                        __('epic Registration Page', 'epic'),
                                        $this->get_all_pages(),
                                        __('If you wish to change default epic Registration page, you may set it here. Make sure you have the <code>[epic_registration]</code> shortcode on this page.', 'epic') . $registration_page_btn,
                                        __('The default front-end Registration page where new users will sign up.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'select',
                                        'reset_password_page_id',
                                        __('epic Reset Password Page', 'epic'),
                                        $this->get_all_pages(),
                                        __('If you wish to change default epic Reset Password page, you may set it here. Make sure you have the <code>[epic_reset_password]</code> shortcode on this page.', 'epic') . $reset_password_page_btn,
                                        __('The default front-end Reset Password page where new users will sign up.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'select',
                                        'member_list_page_id',
                                        __('epic Member List Page', 'epic'),
                                        $this->get_all_pages(),
                                        __('If you wish to change default epic Member List page, you may set it here. Make sure you have member list shortcode there, for example: <code>[epic_search] [epic group=all view=compact users_per_page=10]</code> is the default.', 'epic') . $member_list_page_btn,
                                        __('The default front-end Member List page.', 'epic')
                                );
                            ?>
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-system-pages-tab', 'id'=>'save-epic-system-pages-tab', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-system-pages-tab', 'id'=>'reset-epic-system-pages-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                    ?>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-redirect-setting-content" style="display:none;">
                    <h3><?php _e('Redirect Settings','epic');?></h3>
                    <form id="epic-redirect-setting-form">
                        <table class="form-table">
                            <?php 
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'redirect_backend_profile',
                                        __('Redirect Backend Profiles', 'epic'),
                                        '1',
                                        __('If checked, non-admin users who try to access backend WP profiles will be redirected to epic Profile Page specified above.', 'epic'),
                                        __('Checking this option will send all users to the front-end epic Profile Page if they try to access the default backend profile page in wp-admin. The page can be selected in the epic System Pages settings.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'redirect_backend_login',
                                        __('Redirect Backend Login', 'epic'),
                                        '1',
                                        __('If checked, non-admin users who try to access backend login form will be redirected to the front end epic Login Page specified above.', 'epic'),
                                        __('Checking this option will send all users to the front-end epic Login Page if they try to access the default backend login form. The page can be selected in the epic System Pages settings.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'redirect_backend_registration',
                                        __('Redirect Backend Registrations', 'epic'),
                                        '1',
                                        __('If checked, non-admin users who try to access backend registration form will be redirected to the front end epic Registration Page specified above.', 'epic'),
                                        __('Checking this option will send all users to the front-end epic Registration Page if they try to access the default backend registraiton form. The page can be selected in the epic System Pages settings.', 'epic')
                                );
                                
                                
                                $login_page_options = $this->get_all_pages();
                                $login_page_options['0'] = __('Default', 'epic');
                                
                                $this->add_plugin_setting(
                                        'select',
                                        'login_redirect_page_id',
                                        __('Redirect After Login', 'epic'),
                                        $login_page_options,
                                        __('Users will be redirected to the page set here after successfully logging in. <br /> You may over-ride this setting for a specific login form by using the shortcode <code>[epic_login redirect_to="url_here"]</code>', 'epic'),
                                        __('Setting this option to Default will automatically use any redirect specified in the URL, and will not prevent redirect_to set in shortcode option from working. If no redirect is found in the URL and redirect_to option is not set in shortcode option, the login page will simply be reloaded to welcome the logged in user.', 'epic')
                                );
                                
                                $register_page_options = $this->get_all_pages();
                                $register_page_options['0'] = __('Default', 'epic');
                                
                                $this->add_plugin_setting(
                                        'select',
                                        'register_redirect_page_id',
                                        __('Redirect After Registration', 'epic'),
                                        $register_page_options,
                                        __('New users will be redirected to the page set here after successfully registering using the epic registration form. <br /> You may over-ride this setting for a specific registration form by using the shortcode <code>[epic_registration redirect_to="url_here"]</code>', 'epic'),
                                        __('Setting this option to Default will show the Register Success message instead of redirecting to a custom page.', 'epic')
                                );
                            ?>
                            
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-redirect-setting-tab', 'id'=>'save-epic-redirect-setting-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-redirect-setting-tab', 'id'=>'reset-epic-redirect-setting-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                    ?>
                                </td>
                            </tr>
                            
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-registration-option-content" style="display:none;">
                    <h3><?php _e('Registration Options','epic');?></h3>
                    <form id="epic-registration-option-form">
                        <table class="form-table">
                            <?php 
                                $this->add_plugin_setting(
                                        'select',
                                        'set_password',
                                        __('User Selected Passwords', 'epic'),
                                        array(
                                                1 => __('Enabled, allow users to set password', 'epic'),
                                                0 => __('Disabled, email a random password to users', 'epic')),
                                        __('Enable or disable setting a user selected password at registration', 'epic'),
                                        __('If enabled, users can choose their own password at registration. If disabled, WordPress will email users a random password when they register.', 'epic')
                                );
                                
                                // Automatic Login Selection Start
                                $this->add_plugin_setting(
                                        'select',
                                        'automatic_login',
                                        __('Automatic Login After Registration', 'epic'),
                                        array(
                                                '1' => __('Enabled, log users in automatically after registration', 'epic'),
                                                '0' => __('Disabled, users must login normally after registration', 'epic')
                                        ),
                                        __('Enable or disable automatic login after registration.', 'epic'),
                                        __('If enabled, users will be logged automatically after registration and redirected to the page defined in Redirect After Registration setting. If disabled, users must login normally after registration.', 'epic')
                                );


                                $this->add_plugin_setting(
                                        'select',
                                        'profile_approval_status',
                                        __('User Profile Approvals', 'epic'),
                                        array(
                                                0 => __('Disabled, new users are not required to get the approval.', 'epic'),
                                                1 => __('Enabled, new users must get the approval', 'epic')),
                                        __('Enable or disable setting a user account approval at registration', 'epic'),
                                        __('If enabled, users must be approved by admin before login. If disabled, new users are not required to get the account approved before login.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'select',
                                        'set_email_confirmation',
                                        __('Email Confirmation', 'epic'),
                                        array(
                                                0 => __('Disabled, new users are not required to confirm email', 'epic'),
                                                1 => __('Enabled, new users must confirm email to activate', 'epic')),
                                        __('Enable or disable setting a user email confirmation at registration', 'epic'),
                                        __('If enabled, users must confirm email address to activate. If disabled, new users are not required to confirm email address.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'select',
                                        'accepting_terms_and_conditions',
                                        __('Terms and Conditions', 'epic'),
                                        array(
                                                0 => __('Disabled, terms and conditions are not shown in registration form', 'epic'),
                                                1 => __('Enabled, terms and conditions are shown in registration form', 'epic')),
                                        __('Enable or disable agreeing to terms and conditions at registration', 'epic'),
                                        __('If enabled, users must agree to terms and conditions for registration.', 'epic')
                                );


                                // Automatic Login Selection End
                                // Captcha Plugin Selection Start
                                $captcha_plugins = array(
                                                            'none' => __('None', 'epic'),
                                                            'funcaptcha' => __('FunCaptcha', 'epic'),
                                                            'recaptcha' => __('reCaptcha', 'epic'),
                                                            'captchabestwebsoft' => __('Captcha', 'epic'),
                                                    );
                                $captcha_plugins_params = array();
                                $captcha_plugins = apply_filters('epic_captcha_plugins_list',$captcha_plugins,$captcha_plugins_params);

                                $this->add_plugin_setting(
                                        'select',
                                        'captcha_plugin',
                                        __('Captcha Plugin', 'epic'),
                                        $captcha_plugins,
                                        __('Select which captcha plugin you want to use on the registration form. Funcaptcha requires the Funcaptcha plugin, however reCaptcha is built into epic and requires no additional plugin to be installed. <br /> You can enable or disable captcha with shortcode options: <code>[epic_registration captcha=yes]</code> or <code>[epic_registration captcha=no]</code>.', 'epic'),
                                        __('If you are using a captcha that requires a plugin, you must install and activate the selected captcha plugin. Some captcha plugins require you to register a free account with them, including FunCaptcha', 'epic')
                                );
        
                                // Captcha Plugin Selection End
                                $this->add_plugin_setting(
                                        'input',
                                        'captcha_label',
                                        __('CAPTCHA Field Label', 'epic'), array(),
                                        __('Enter text which you want to show in form in front of CAPTCHA.', 'epic'),
                                        __('Enter text which you want to show in form in front of CAPTCHA.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'recaptcha_public_key',
                                        __('reCaptcha Public Key', 'epic'), array(),
                                        __('Enter your reCaptcha Public Key. You can sign up for a free reCaptcha account <a href="https://www.google.com/recaptcha" title="Get a reCaptcha Key" target="_blank">here</a>.', 'epic'),
                                        __('Your reCaptcha kays are required to use reCaptcha. You can register your site for a free key on the Google reCaptcha page.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'recaptcha_private_key',
                                        __('reCaptcha Private Key', 'epic'), array(),
                                        __('Enter your reCaptcha Private Key.', 'epic'),
                                        __('Your reCaptcha kays are required to use reCaptcha. You can register your site for a free key on the Google reCaptcha page.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'textarea',
                                        'msg_register_success',
                                        __('Register success message', 'epic'),
                                        null,
                                        __('Show a text message when users complete the registration process.', 'epic'),
                                        __('This message will be shown to users after registration is complted.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_register_success_after',
                                        __('Text/HTML below the Register Success message.', 'epic'),
                                        null,
                                        __('Show a text/HTML content under success message when users complete the registration process.', 'epic'),
                                        __('This message will be shown to users under the success messsage after registration is completed.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'select_user_role_in_registration',
                                        __('Select User Role at Registration', 'epic'),
                                        '1',
                                        __('If checked, users will be able to select their user role at registration. If you do not understand what this means, leave this option unchecked.', 'epic'),
                                        __('Checking this option will enable users to select a user role at registration, based on available user roles defined in the choose roles setting.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'input',
                                        'label_for_registration_user_role',
                                        __('Select Role Label', 'epic'), array(),
                                        __('Enter text which you want to show as the label for User Role selection.', 'epic'),
                                        __('Enter text which you want to show as the label for User Role selection.', 'epic')
                                );

                                global $epic_roles;
                                $default_role = get_option("default_role");                        

                                $this->add_plugin_setting(
                                        'checkbox_list',
                                        'choose_roles_for_registration',
                                        __('Choose User Roles for Registration', 'epic'),
                                        $epic_roles->epic_available_user_roles_registration(),
                                        __('Selected user roles will be available for users to choose at registration. The default role for new users will be always available, you can change the default role in WordPress general settings.', 'epic'),
                                        __('User roles selected in this section will appear on the registration form. Be aware that some user roles will give posting and editing access to your site, so please be careful when using this option.', 'epic'),
                                        '',
                                        array('disabled' => array($default_role))
                                );

                                global $predefined;

                                $this->add_plugin_setting(
                                        'select',
                                        'default_predefined_country',
                                        __('Default Country', 'epic'),
                                        $predefined->get_array('countries'),
                                        __('List the countries to be used as default country.', 'epic'),
                                        __('Selected country will appear as the default value for country fields.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'select',
                                        'enforce_password_strength',
                                        __('Password Strength Level', 'epic'),
                                        array(
                                                '0' => __('Disabled', 'epic'),
                                                '1' => __('Weak', 'epic'),
                                                '2' => __('Medium', 'epic'),
                                                '3' => __('Strong', 'epic')
                                        ),
                                        __('Select the level of strength for user password.', 'epic'),
                                        __('User password should match the expected criteria required by strength level', 'epic')
                                );                               

                                $this->add_plugin_setting(
                                    'input',
                                    'register_form_title_text',
                                    __('Registration Form Title Message', 'epic'), array(),
                                    __('This message provides the title or message for registration form. By default it will say <strong>Your display name will appear here</strong>. Once user starts typing the username, this section will be updated to show the display name.', 'epic'),
                                    __('You can use custom title or custom instructions for this section.', 'epic')
                            );

                            $this->add_plugin_setting(
                                    'checkbox',
                                    'register_form_title_type_username',
                                    __('Update Registration Form Title with Display Name', 'epic'),
                                    '1',
                                    __('If checked, once user starts typing the username, this section will be updated to show the display name. By default, it will be checked to allow display name.', 'epic'),
                                    __('Unchecking this option will keep the custom title or message from being updated with display name.', 'epic')
                            );
                            ?>
                            
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-registration-option-tab', 'id'=>'save-epic-registration-option-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-registration-option-tab', 'id'=>'reset-epic-registration-option-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                    ?>
                                </td>
                            </tr>
                            
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-search-settings-content" style="display:none;">
                    <h3><?php _e('Search Settings','epic');?></h3>
                    <form id="epic-search-settings-form">
                        <table class="form-table">
                            <?php 
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'use_cron',
                                        __('Use WP Cron', 'epic'),
                                        '1',
                                        __('If checked, epic will use WP Cron Feature to update User Search Cache.<br /> When usign this option, make sure <code>DISABLE_WP_CRON</code> is not set to <code>TRUE</code> in <code>wp-config.php</code>', 'epic'),
                                        __('Using WP Cron will update your search cache automatically at regular intervals.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'require_search_input',
                                        __('Search Input Mandatory', 'epic'),
                                        '1',
                                        __('If checked, epic will not show search results when <code>hide_until_search=true</code> is used and no search input is given.', 'epic'),
                                        __('Checking this option is useful if you do not want to show all users when submitting the search with no search criteria.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'users_are_called',
                                        __('Users are Called', 'epic'), array(),
                                        __('What users are called in your system. Like Members, Doctors, etc.', 'epic'),
                                        __('This will update the text for the search form and search results. The default is Users.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'combined_search_text',
                                        __('Combined Search Text', 'epic'), array(),
                                        __('Name of Text search field which is used to perform combined search.', 'epic'),
                                        __('You may choose custom text for the main combined text search field. The default is Combined Search.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'search_button_text',
                                        __('Search Button Text', 'epic'), array(),
                                        __('Text to display on search button.', 'epic'),
                                        __('This is the text of the button to submit the member search form. The default is Filter.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'input',
                                        'reset_button_text',
                                        __('Reset Button Text', 'epic'), array(),
                                        __('Text to display on reset button.', 'epic'),
                                        __('This is the text of the button to reset the member search form. The default is Reset.', 'epic')
                                );
                                
                                
                            ?>
                            
                            
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-search-settings-tab', 'id'=>'save-epic-search-settings-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-search-settings-tab', 'id'=>'reset-epic-search-settings-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-privacy-option-content" style="display:none;">
                    <h3><?php _e('Privacy Options','epic');?></h3>
                    <form id="epic-privacy-option-form">
                        <table class="form-table">
                            <?php 
							    $this->add_plugin_setting(
                                        'select',
                                        'guests_can_view',
                                        __('Guest viewing of profiles', 'epic'),
                                        array(
                                                1 => __('Enabled, make profiles publicly visible', 'epic'),
                                                0 => __('Disabled, users must login to view profiles', 'epic')),
                                        __('Enable or disable guest and non-logged in user viewing of profiles.', 'epic'),
                                        __('If enabled, profiles will be publicly visible to non-logged in users. If disabled, guests must log in to view profiles.', 'epic')
                                );
								
                                $this->add_plugin_setting(
                                        'select',
                                        'users_can_view',
                                        __('Logged-in user viewing of other profiles', 'epic'),
                                        array(
                                                1 => __('Enabled, logged-in users may view other user profiles', 'epic'),
                                                0 => __('Disabled, users may only view their own profile', 'epic'),
                                                2 => __('Restricted by User Role', 'epic')),
                                        __('Enable or disable logged-in user viewing of other user profiles. Admin users can always view all profiles.', 'epic'),
                                        __('If enabled, logged-in users are allowed to view other user profiles. If disabled, logged-in users may only view theor own profile.', 'epic')
                                );

                                global $epic_roles;                        

                                $this->add_plugin_setting(
                                        'checkbox_list',
                                        'choose_roles_for_view_profile',
                                        __('Select User Roles', 'epic'),
                                        $epic_roles->epic_available_user_roles_view_profile(),
                                        __('Selected user roles can view other user profiles. Roles not selected here will only be able to view their own profile.', 'epic'),
                                        __('User roles selected in this section will have permission to view other profiles.', 'epic'),
                                        '',
                                        array('disabled' => array('administrator'))
                                );

                            ?>
                            
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-privacy-option-tab', 'id'=>'save-epic-privacy-option-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-privacy-option-tab', 'id'=>'reset-epic-privacy-option-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                    ?>
                                </td>
                            </tr>
                            
                            
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-misc-messages-content" style="display:none;">
                    <h3><?php _e('Messages for Insuficient Permissions','epic');?></h3>
                    <form id="epic-misc-messages-form">
                        <table class="form-table">
                            <?php 
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_login_to_view',
                                        __('Guests cannot view profiles', 'epic'),
                                        null,
                                        __('Show a text/HTML message when guests try to view profiles if they are not allowed, asking them to login or register to view the profile.', 'epic'),
                                        __('This message will eb shown to guests who try to view profiles if it is not allowed in the above settings.', 'epic')
                                );

                                $display_login_msg = __('Display login form below this message','epic');
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'html_login_to_view_form',
                                        '',
                                        '1',
                                        '',
                                        '',
                                        '',
                                        array('checkbox_type'=>'inline', 'message' => $display_login_msg)
                                );
                                
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_user_login_message',
                                        __('User must log-in to view/edit his profile', 'epic'),
                                        null,
                                        __('Show a text/HTML message asking the user to login to view or edit his own profile. Leave blank to show nothing.', 'epic'),
                                        __('This message is shown to users who try to view/edit their own profile but are not logged in.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'html_user_login_message_form',
                                        '',
                                        '1',
                                        '',
                                        '',
                                        '',
                                        array('checkbox_type'=>'inline', 'message' => $display_login_msg)
                                );
                                
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_private_content',
                                        __('User must log-in to view private content', 'epic'),
                                        null,
                                        __('Show a text/HTML message to guests and non-logged in users who try to view private member-only content. Leave blank to show nothing.', 'epic'),
                                        __('This message is shown to guests and non-logged in users who try to view private member-only content.', 'epic')
                                );
                                

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'html_private_content_form',
                                        '',
                                        '1',
                                        '',
                                        '',
                                        '',
                                        array('checkbox_type'=>'inline', 'message' => $display_login_msg)
                                );

                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_members_private_content',
                                        __('User must have permission to view private content', 'epic'),
                                        null,
                                        __('Show a text/HTML message to logged in users who try to view private content restricted for current user. Leave blank to show nothing.', 'epic'),
                                        __('This message is shown to logged in users who try to view private content restricted for current user.', 'epic')
                                );
								
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_other_profiles_restricted',
                                        __('User Role may not view other profiles', 'epic'),
                                        null,
                                        __('Show a text/HTML message in place of profiles when a User Role is not allowed to view other profiles.', 'epic'),
                                        __('This message is shown to users who try to view the epic Profile List while user role is blocked for viewing other profiles.', 'epic')
                                );
                                
                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_registration_disabled',
                                        __('Registration Closed Message', 'epic'),
                                        null,
                                        __('Show a text/HTML message in place of the registration form when registration is closed. Registeration can be opened or closed from the WordPress general settings using the checkbox <code>Anyone can register</code>.', 'epic'),
                                        __('This message is shown to users who try to view the epic registration form while you have registrations disabled.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_profile_status_msg',
                                        __('Profile Status Message', 'epic'),
                                        null,
                                        __('Show a text/HTML message in place of the profile when profile is set to INACTIVE by user.', 'epic'),
                                        __('This message is shown to users who try to view profiles set to INACTIVE by users.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_profile_approval_pending_msg',
                                        __('Profile Pending Approval Message', 'epic'),
                                        null,
                                        __('Show a text/HTML message on top of login form when user profile is pending approval of admin.', 'epic'),
                                        __('This message is shown to users who try to login when user profile is pending approval of admin.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'textarea',
                                        'html_terms_and_conditions',
                                        __('Terms and Conditions Message', 'epic'),
                                        null,
                                        __('Show a text/HTML message as the Terms and Conditions for user registration.', 'epic'),
                                        __('This message is shown to users in registration forms as terms and conditions for registration.', 'epic')
                                );

                            ?>
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-misc-messages-tab', 'id'=>'save-epic-misc-messages-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-misc-messages-tab', 'id'=>'reset-epic-misc-messages-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                        
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="epic-tab-content" id="epic-scripts-styles-content" style="display:none;">
                    <h3><?php _e('Loading Scripts and Styles','epic');?></h3>
                    <form id="epic-scripts-styles-form">
                        <table class="form-table">
                            <?php 
                                $this->add_plugin_setting(
                                        'checkbox',
                                        'disable_fancybox_script_styles',
                                        __('Disable Fancybox Scripts and Styles', 'epic'),
                                        '0',
                                        __('If checked, epic will disable the loading of script and style files for Fancybox library.', 'epic'),
                                        __('Use it when you have newer Fancybox version in your theme or other plugins.', 'epic')
                                );

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'disable_tipsy_script_styles',
                                        __('Disable Tipsy Scripts and Styles', 'epic'),
                                        '0',
                                        __('If checked, epic will disable the loading of script and style files for Tipsy library.', 'epic'),
                                        __('Use it when you have newer Tipsy version in your theme or other plugins.', 'epic')
                                ); 

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'disable_fitvids_script_styles',
                                        __('Disable FitVids Scripts and Styles', 'epic'),
                                        '0',
                                        __('If checked, epic will disable the loading of script and style files for FitVids library.', 'epic'),
                                        __('Use it when you have newer FitVids version in your theme or other plugins.', 'epic')
                                ); 

                                $this->add_plugin_setting(
                                        'checkbox',
                                        'disable_opensans_google_font',
                                        __('Disable Google Font Files for Open Sans', 'epic'),
                                        '0',
                                        __('If checked, epic will disable the loading of Open Sans from google fonts.', 'epic'),
                                        __('Use it when you want to avoid requests to google fonts.', 'epic')
                                );


                                
                            ?>
                            <tr valign="top">
                                <th scope="row"><label>&nbsp;</label></th>
                                <td>
                                    <?php 
                                        echo epic_Html::button('button', array('name'=>'save-epic-scripts-styles-tab', 'id'=>'save-epic-scripts-styles-tab', 'value'=>__('Save Changes','epic'), 'class'=>'button button-primary epic-save-options'));
                                        echo '&nbsp;&nbsp;';
                                        echo epic_Html::button('button', array('name'=>'reset-epic-scripts-styles-tab', 'id'=>'reset-epic-scripts-styles-tab', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-options'));
                                        
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
