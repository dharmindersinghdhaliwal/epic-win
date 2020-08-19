<?php
    global $epic_admin,$epic_options;

    $profile_fields = $epic_options->epic_profile_fields;
    $profile_meta_fields = array('0'=> __('Select Field','epic'));
    foreach($profile_fields as $profile_field){
        if(isset($profile_field['type']) && $profile_field['type'] == 'usermeta'){
            $profile_meta_fields[$profile_field['meta']] = $profile_field['name'];
        }
    }

    
?>

<div class="epic-tab-content" id="epic-seo-settings-content" style="display:none;">
    <h3><?php _e('Manage Profile SEO Settings','epic');?>
        </h3>
        
        
    
    <div id="epic-seo-settings" class="epic-seo-screens" style="display:block">

        <form id="epic-seo-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        $epic_admin->add_plugin_setting(
                                            'input',
                                            'seo_profile_title_prefix',
                                            __('Prefix for Profile Page Title Bar', 'epic'), array(),
                                            __('Provide prefix to be included in title bar of the profile page and meta tags', 'epic'),
                                            __('This will be used to specify a SEO optimized page title.', 'epic')
                                    );
    
                        $epic_admin->add_plugin_setting(
                                            'input',
                                            'seo_profile_title_suffix',
                                            __('Suffix for Profile Page Title Bar', 'epic'), array(),
                                            __('Provide suffix to be included in title bar of the profile page and meta tags', 'epic'),
                                            __('This will be used to specify a SEO optimized page title.', 'epic')
                                    );



                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_title_field',
                                'seo_profile_title_field',
                                __('Field for Title Meta Tag', 'epic'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the title meta tag. This will be placed bewtween the prefix and suffix specified above.', 'epic'),
                                __('Display name or first name is the ideal field for this setting.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_description_field',
                                'seo_profile_description_field',
                                __('Field for Description Meta Tag', 'epic'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the description meta tag.', 'epic'),
                                __('Description/About field us the ideal field for this setting.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_image_field',
                                'seo_profile_image_field',
                                __('Field for Image Meta Tag', 'epic'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the image meta tag.', 'epic'),
                                __('Profile picture is the ideal field for this setting.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        
                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo epic_Html::button('button', array('name'=>'save-epic-seo-settings', 'id'=>'save-epic-seo-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo epic_Html::button('button', array('name'=>'reset-epic-seo-settings', 'id'=>'reset-epic-seo-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>