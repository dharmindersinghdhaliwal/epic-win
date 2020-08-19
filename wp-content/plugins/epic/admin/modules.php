<?php
   global $epic_template_loader;
?>

<div class="wrap">
    <h2><?php _e('epic - Modules','epic')?></h2>
    <div class="updated" id="epic-modules-settings-saved" style="display:none;">
        <p><?php _e('Settings Saved','epic');?></p>
    </div>
    
    <div class="updated" id="epic-modules-settings-reset" style="display:none;">
        <p><?php _e('Settings Reset Completed.','epic');?></p>
    </div>
    
    <div class="updated" id="epic-modules-import-success" style="display:none;">
        <p><?php _e('Record Updated.', 'epic'); ?></p>
    </div>

    <div class="error" id="epic-modules-import-error" style="display:none;">
        <p><?php _e('No File Selected.', 'epic'); ?></p>
    </div>
    
    <div id="epic-tab-group" class="epic-tab-group vertical_tabs">
        <ul id="epic-tabs" class="epic-tabs">
            <li class="epic-tab active" id="epic-site-lockdown-settings-tab"><?php _e('Site Lockdown','epic');?></li>
            <li class="epic-tab " id="epic-site-restrictions-settings-tab"><?php _e('Content Restriction Rules','epic');?></li>
            <li class="epic-tab " id="epic-email-settings-tab"><?php _e('Email Templates','epic');?></li>
            <li class="epic-tab " id="epic-email-general-settings-tab"><?php _e('Email Settings','epic');?></li>
            <li class="epic-tab " id="epic-site-export-import-settings-tab"><?php _e('Export / Import', 'epic'); ?></li>
            <?php do_action('epic_addon_module_tabs'); ?>
        </ul>
        <div id="epic-tab-container" class="epic-tab-container" style="min-height: 325px;">
            <div class="epic-tab-content-holder">
                <div class="epic-tab-content" id="epic-site-lockdown-settings-content">
                    <h3><?php _e('Site Lockdown','epic');?></h3>
                    <div class="updated" id="epic-site-lockdown-settings-msg" style="display:none;">
                        
                    </div>
                    <form id="epic-site-lockdown-settings-form">
                        <table class="form-table" cellspacing="0" cellpadding="0">
                            <tbody>
                                <?php
                                    ob_start();
                                    $epic_template_loader->get_template_part('site_lockdown_view');
                                    echo ob_get_clean();
                                ?>
                                <tr valign="top">
                                    <th scope="row"><label>&nbsp;</label></th>
                                    <td>
                                        <?php 
                                            echo epic_Html::button('button', array('name'=>'save-epic-site-lockdown-settings', 'id'=>'save-epic-site-lockdown-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                            echo '&nbsp;&nbsp;';
                                            echo epic_Html::button('button', array('name'=>'reset-epic-site-lockdown-settings', 'id'=>'reset-epic-site-lockdown-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));
                                        ?>
                                    </td>
                                </tr>                                
                                
                            </tbody>
                        </table>
                    </form>
                </div> 
                <div class="epic-tab-content" id="epic-site-restrictions-settings-content" style="display:none;">
                    <div id="epic-site-restrictions-create" style="display:none">
                        <h3><?php _e('Content Restriction Rules','epic');?>
                        <?php echo epic_Html::button('button', array('name'=>'epic-display-list-res-rule', 'id'=>'epic-display-list-res-rule'
                            , 'value'=> __('Back To Restriction Rules','epic'), 'class'=>'button button-primary')); ?></h3>


                        <div class="updated" id="epic-add-site-restrictions-settings-msg" style="display:none;">
                            
                        </div>
                        <form id="epic-site-restrictions-create-form">
                            <table class="form-table" cellspacing="0" cellpadding="0">
                                <tbody>
                                    
                                    <?php
                                        ob_start();
                                        $epic_template_loader->get_template_part('site_content_restriction_rules_view');
                                        echo ob_get_clean();
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><label>&nbsp;</label></th>
                                        <td>
                                            <?php 

                                                echo epic_Html::button('button', array('name'=>'add-epic-site-restriction-rule', 'id'=>'add-epic-site-restriction-rule', 'value'=>'Add Restriction Rule', 'class'=>'button button-primary '));
                                                echo '&nbsp;&nbsp;';
                                            ?>
                                        </td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="epic-site-restrictions-list">
                        <h3><?php _e('Content Restriction Rules','epic');?>
                        <?php echo epic_Html::button('button', array('name'=>'epic-display-create-res-rule', 'id'=>'epic-display-create-res-rule'
                            , 'value'=> __('Create New Restriction Rule','epic'), 'class'=>'button button-primary')); ?></h3>

                        <div class="updated" id="epic-site-restrictions-settings-msg" style="display:none;">
                            
                        </div>
                        <form id="epic-site-restrictions-settings-form">
                            <table class="form-table" cellspacing="0" cellpadding="0">
                                <tbody>                                

                                    <tr valign="top">
                                        <td colspan="2">
                                            <table id="epic_site_restriction_rules">
                                                <tr id="epic_site_restriction_rules_titles">
                                                    <th><?php echo __('Allowed for','epic'); ?></th>
                                                    <th><?php echo __('Allowed Conditions','epic'); ?></th>
                                                    <th><?php echo __('Restrictions','epic'); ?></th>
                                                    <th><?php echo __('Redirection','epic'); ?></th>
                                                    <th><?php echo __('Delete','epic'); ?></th>
                                                    <th><?php echo __('Enable/Disable','epic'); ?></th>
                                                </tr>
                                                <?php
                                                    global $epic_site_restrictions;
                                                    echo $epic_site_restrictions->epic_restriction_rules_list();
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>



                <div class="epic-tab-content" id="epic-email-settings-content" style="display:none;">
                    <h3><?php _e('Email Templates','epic');?></h3>
                    <div class="updated" id="epic-email-settings-msg" style="display:none;">
                        
                    </div>
                    <form id="epic-email-settings-form">
                        <table class="form-table" cellspacing="0" cellpadding="0">
                            <tbody>
                            
                                <?php
                                    ob_start();
                                    $epic_template_loader->get_template_part('email_templates_view');
                                    echo ob_get_clean();
                                ?>
                                <tr valign="top">
                                    <th scope="row"><label>&nbsp;</label></th>
                                    <td>
                                        <?php 
                                            echo epic_Html::button('button', array('name'=>'save-epic-email-template', 'id'=>'save-epic-email-template', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary '));
                                            echo '&nbsp;&nbsp;';
                                            echo epic_Html::button('button', array('name'=>'reset-epic-email-template', 'id'=>'reset-epic-email-template', 'value'=>__('Reset Templates','epic'), 'class'=>'button button-secondary '));
                                        ?>
                                    </td>
                                </tr>                                
                                
                            </tbody>
                        </table>
                    </form>
                </div>
                
                <div class="epic-tab-content" id="epic-email-general-settings-content" style="display:none;">
                    <h3><?php _e('Email Settings','epic');?></h3>
                    <div class="updated" id="epic-email-general-settings-msg" style="display:none;">
                        
                    </div>
                    <form id="epic-email-general-settings-form">
                        <table class="form-table" cellspacing="0" cellpadding="0">
                            <tbody>
                                <?php
                
                                    ob_start();
                                    $epic_template_loader->get_template_part('email_general_settings_view');
                                    echo ob_get_clean();
                                ?>
                                
                                <tr valign="top">
                                    <th scope="row"><label>&nbsp;</label></th>
                                    <td>
                                        <?php 
                                             echo epic_Html::button('button', array('name'=>'save-epic-email-general-settings', 'id'=>'save-epic-email-general-settings', 'value'=> __('Save Changes','epic'), 'class'=>'button button-primary epic-save-module-options'));
                                            echo '&nbsp;&nbsp;';
                                            echo epic_Html::button('button', array('name'=>'reset-epic-email-general-settings', 'id'=>'reset-epic-email-general-settings', 'value'=>__('Reset Options','epic'), 'class'=>'button button-secondary epic-reset-module-options'));


                                           
                                        ?>
                                    </td>
                                </tr>                                
                                
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="epic-tab-content" id="epic-site-export-import-settings-content" style="display:none;">
                    
                    <div id="epic-site-export-import-list">
                        <h3>
                            <?php _e('Export / Import', 'epic'); ?> 
                        </h3>
                        <div class='epic-module-settings-button-bar' >
                        <?php
                            echo epic_Html::button('button',
                                array(
                                    'name' => 'epic-export-import-fields-btn',
                                    'id' => 'epic-export-import-fields-btn',
                                    'value' => __('Profile Fields', 'epic'),
                                    'class' => 'button-import-export button button-primary'));
                        ?>
                        <?php
                        echo epic_Html::button('button',
                            array(
                                'name' => 'epic-export-import-settings-btn',
                                'id' => 'epic-export-import-settings-btn',
                                'value' => __('epic Settings', 'epic'),
                                'class' => 'button-import-export button button-primary'));
                        ?>
                            
                        <?php
                        echo epic_Html::button('button',
                            array(
                                'name' => 'epic-export-import-users-btn',
                                'id' => 'epic-export-import-users-btn',
                                'value' => __('epic Users', 'epic'),
                                'class' => 'button-import-export button button-primary'));
                        ?>
                        
                        </div>
                    </div>
                    <div id="epic-export-import-fields-panel" class='panel-import-export' style="display:block">

                        <div class="updated" id="epic-add-site-export-import-msg" style="display:none;"></div>

                        <!--Import Form-->
                        <div class='epic-module-settings-sub-title'><?php _e('Import Fields', 'epic'); ?></div>
                        <?php
                            ob_start();
                            $epic_template_loader->get_template_part('site_field_import_view');
                            echo ob_get_clean();
                        ?>


                        <!--Export Form-->
                        <div class='epic-module-settings-sub-title'><?php _e('Export Fields', 'epic'); ?></div>
                        <?php
                            ob_start();
                            $epic_template_loader->get_template_part('site_field_export_view');
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div id="epic-export-import-settings-panel" class='panel-import-export' style="display:none">

                        <div class="updated" id="epic-add-site-settings-msg" style="display:none;"></div>

                        <!--Import Form-->
                        <div class='epic-module-settings-sub-title'><?php _e('Import Settings', 'epic'); ?></div>
                        <?php
                            ob_start();
                            $epic_template_loader->get_template_part('site_settings_import_view');
                            echo ob_get_clean();
                        ?>


                        <!--Export Form-->
                        <div class='epic-module-settings-sub-title'><?php _e('Export Settings', 'epic'); ?></div>
                        <?php
                            ob_start();
                            $epic_template_loader->get_template_part('site_settings_export_view');
                            echo ob_get_clean();
                        ?>
                    </div>
                    
                    <div id="epic-export-import-users-panel" class='panel-import-export' style="display:none">

                        <div class="updated" id="epic-add-site-settings-msg" style="display:none;"></div>

                        <!--Export Form-->
                        <div class='epic-module-settings-sub-title'><?php _e('Export Users', 'epic'); ?></div>
                        <?php
                            ob_start();
                            $epic_template_loader->get_template_part('site_users_export_view');
                            echo ob_get_clean();
                        ?>
                    </div>
                    
                </div>

                <?php do_action('epic_addon_module_settings'); ?>

            </div>
        </div>
        
    </div>
    
</div>
