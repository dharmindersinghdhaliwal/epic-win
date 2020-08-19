<?php
    global $epic_admin;
?>

<div class="epic-tab-content" id="epic-social-login-settings-content" style="display:none;">
    <h3><?php _e('Manage Social Login','epic');?>
        </h3>
        
        
    
    <div id="epic-social-login-settings" class="epic-woo-screens" style="display:block">

        <form id="epic-social-login-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $epic_admin->add_plugin_setting(
                                'checkbox_list',
                                'social_login_allowed_networks',
                                __('Enabled Social Networks', 'epic'),
                                array(  'linkedin' => __('LinkedIn','epic'),
                                        'facebook' => __('Facebook','epic'),
                                        'twitter' => __('Twitter','epic'),
                                        'google' => __('Google Plus','epic')
                                     ),
                                __('Selected social network buttons will be available in login and registration forms to login using social networks.', 'epic'),
                                __('Please select only the necessary networks and fill in API details before using them.', 'epic'),
                                '',
                                array()
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_display_message',
                            __('Social Login Message', 'epic'), array(),
                            sprintf(__('Message displayed before the social login buttons.', 'epic')),
                            __('Message displayed before the social login buttons.', 'epic')
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_facebook_app_id',
                            __('Facebook App ID', 'epic'), array(),
                            sprintf(__('ID of your Facebook application.', 'epic')),
                            __('Get the ID of your Facebook application and use it here.', 'epic')
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_facebook_app_secret',
                            __('Facebook App Secret', 'epic'), array(),
                            sprintf(__('Secret key of your Facebook application.', 'epic')),
                            __('Get the app secret of your Facebook application and use it here.', 'epic')
                        );


                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_google_client_id',
                            __('Google Client ID', 'epic'), array(),
                            sprintf(__('Client ID of your Google application.', 'epic')),
                            __('Get the Client ID of your Google application and use it here.', 'epic')
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_google_client_secret',
                            __('Google Client Secret', 'epic'), array(),
                            sprintf(__('Secret key of your Google application.', 'epic')),
                            __('Get the app secret of your Google application and use it here.', 'epic')
                        );


                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_twitter_app_key',
                            __('Twitter App Key', 'epic'), array(),
                            sprintf(__('Application key of your Twitter application', 'epic')),
                            __('Get the key of your Twitter application and use it here.', 'epic')
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_twitter_app_secret',
                            __('Twitter App Secret', 'epic'), array(),
                            sprintf(__('Secret key of your Twitter application.', 'epic')),
                            __('Get the app secret of your Twitter application and use it here.', 'epic')
                        );


                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_linkedin_app_key',
                            __('LinkedIn App Key', 'epic'), array(),
                            sprintf(__('Application key of your LinkedIn application.', 'epic')),
                            __('Get the key of your LinkedIn application and use it here.', 'epic')
                        );

                        $epic_admin->add_plugin_setting(
                            'input',
                            'social_login_linkedin_app_secret',
                            __('LinkedIn App Secret', 'epic'), array(),
                            sprintf(__('Secret key of your LinkedIn application.', 'epic')),
                            __('Get the app secret of your LinkedIn application and use it here.', 'epic')
                        );



                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo epic_Html::button('button', array('name'=>'save-epic-social-login-settings', 'id'=>'save-epic-social-login-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo epic_Html::button('button', array('name'=>'reset-epic-social-login-settings', 'id'=>'reset-epic-social-login-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>