<?php
    global $epic_admin;
?>

<div class="epic-tab-content" id="epic-custom-fields-settings-content" style="display:none;">
    <h3><?php _e('Manage Custom Field Settings','epic');?>
        </h3>
        
        
    
    <div id="epic-custom-fields-settings" class="epic-custom-fields-screens" style="display:block">

        <form id="epic-custom-fields-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'help_text_html',
                                'help_text_html',
                                __('HTML for Help Text', 'epic'),
                                array('0'=> __('HTML Disabled','epic'),'1' => __('HTML Enabled','epic'),'2' => __('HTML Selected Tags Enabled','epic')),
                                __('Enabele/Disable HTML content on help text. By default HTML content is not enabled for help text.', 'epic'),
                                __('HTML Enabled - Enables any type of HTML content. HTML Selected Tags Enabled - only enabled p,a,strong,b,i,span tags. Other tags are blocked. Its recommended to disable or use selected tags to prevent styling conflicts.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'profile_collapsible_tabs',
                                'profile_collapsible_tabs',
                                __('Collapsible Tabs in Profiles', 'epic'),
                                array('0'=> __('Enable Collapsible Tabs','epic'),'1' => __('Disable Collapsible Tabs','epic')),
                                __('Enabele/Disable collapsible tabs by spearator fields. By default collapsible tabs is not enabled.', 'epic'),
                                __('Used to display/hide profile fields based on separator fields.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $epic_admin->add_plugin_module_setting(
                                'select',
                                'profile_collapsible_tabs_display',
                                'profile_collapsible_tabs_display',
                                __('Display Fields in Collapsible Tabs', 'epic'),
                                array('0'=> __('Show by Default ','epic'),'1' => __('Hide by Default','epic')),
                                __('Display/hide fields in collapsible types on profile loading. By default, all fields in collapsible tabs are displayed', 'epic'),
                                __('Display/hide fields in collapsible types on profile loading. By default, all fields in collapsible tabs are displayed.', 'epic'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        
                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo epic_Html::button('button', array('name'=>'save-epic-custom-fields-settings', 'id'=>'save-epic-custom-fields-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo epic_Html::button('button', array('name'=>'reset-epic-custom-fields-settings', 'id'=>'reset-epic-custom-fields-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>