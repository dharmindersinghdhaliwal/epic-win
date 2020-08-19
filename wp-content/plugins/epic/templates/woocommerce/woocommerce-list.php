<?php
    global $epic_admin;
?>

<div class="epic-tab-content" id="epic-woocommerce-settings-content" style="display:none;">
    <h3><?php _e('Manage Woocommerce','epic');?>
        </h3>
        
        
    
    <div id="epic-woocommerce-settings" class="epic-woo-screens" style="display:block">

        <form id="epic-woocommerce-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $epic_admin->add_plugin_module_setting(
                            'checkbox',
                            'woocommerce_profile_tab_status',
                            'woocommerce_profile_tab_status',
                            __('Display Woocoommerce Tab in Profile', 'epic'),
                            '1',
                            __('If checked, Woocommerce my account details will be available in epic profile as a separate tab.', 'epic'),
                            __('Checking this option will enable Woocommerce tab for logged-in user profiles.', 'epic')
                        );

                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo epic_Html::button('button', array('name'=>'save-epic-woocommerce-settings', 'id'=>'save-epic-woocommerce-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo epic_Html::button('button', array('name'=>'reset-epic-woocommerce-settings', 'id'=>'reset-epic-woocommerce-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>