<?php
global $epic_admin;

?>

<form id="epic-download-export-fields-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="Export Type"><?php _e('Export Type', 'epic'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_fields' => __('All Fields', 'epic'),
                    'selected_fields' => __('Selected Fields', 'epic')
                );

                echo epic_Html::drop_down(array('name' => 'site_export_field_type', 'id' => 'site_export_field_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'epic') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="Fields"><?php _e('Fields', 'epic'); ?></label></th>
            <td>
                <?php
                $profile_fields = $epic_admin->epic_get_custom_field();
                echo epic_Html::drop_down(array('name' => 'site_export_fields[]', 'id' => 'site_export_fields', 'class' => 'chosen-admin_setting', 'multiple' => ''), $profile_fields, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help"
                     original-title="<?php _e('Select pages to be exported.', 'epic') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo epic_Html::button('button', array('name' => 'epic-download-export-fields', 'id' => 'epic-download-export-fields', 'value' => __('Download','epic'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>