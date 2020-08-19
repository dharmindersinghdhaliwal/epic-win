<?php
global $epic_admin;

// $options = get_option('epic_options');
// echo "<pre>";print_r($options);exit;

?>

<form id="epic-download-export-settings-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="Export Type"><?php _e('Export Type', 'epic'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_settings' => __('All Settings', 'epic'),
                    //'selected_settings' => __('Selected Settings', 'epic')
                );
 
                echo epic_Html::drop_down(array('name' => 'site_export_settings_type', 'id' => 'site_export_settings_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'epic') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="Settings Section"><?php _e('Settings Section', 'epic'); ?></label></th>
            <td>
                <?php
                $settings_sections = array();
                echo epic_Html::drop_down(array('name' => 'site_export_settings_sections[]', 'id' => 'site_export_settings_sections', 'class' => 'chosen-admin_setting', 'multiple' => ''), $settings_sections, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help"
                     original-title="<?php _e('Select settings sections to be exported.', 'epic') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo epic_Html::button('button', array('name' => 'epic-download-export-settings', 'id' => 'epic-download-export-settings', 'value' => __('Download','epic'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>