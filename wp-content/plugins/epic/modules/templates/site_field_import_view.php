<?php
    global $epic_admin;

?>

    <form id="epic-upload-import-fields-form" name="epic-upload-import-fields-form" method="post" enctype="multipart/form-data"  action="upload.php">

    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="Import Type"><?php _e('Import Type', 'epic'); ?></label></th>
            <td>
                <?php
                $import_types = array(
                    'all_fields'  => __('All Fields', 'epic'),
                    'selected_fields'   => __('Selected Fields', 'epic')
                );

                echo epic_Html::drop_down(array('name'=>'site_import_field_type','id'=>'site_import_field_type', 'class' =>'chosen-admin_setting'), $import_types, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help" original-title="<?php _e('Select import type.', 'epic') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
    <th scope="row"><label for="Fields"><?php _e('Fields', 'epic'); ?></label></th>
    <td>
        <?php
        $profile_fields = $epic_admin->epic_get_custom_field();
        echo epic_Html::drop_down(array('name'=>'site_import_fields[]','id'=>'site_import_fields','class'=> 'chosen-admin_setting','multiple'=>''), $profile_fields, '');

        ?><i class="epic-icon-question-sign epic-tooltip2 option-help" original-title="<?php _e('Select pages to be restricted.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="File"><?php _e('File', 'epic'); ?></label></th>
    <td>
        <input type="file" name="fields_file" id="fields_file" multiple />

    </td>
</tr>

<tr valign="top">
    <th scope="row"><label>&nbsp;</label></th>
    <td>
        <?php
        echo epic_Html::button('submit', array('name' => 'epic-upload-import-fields', 'id' => 'epic-upload-import-fields', 'value' => __('Upload','epic'), 'class' => 'button button-primary'));
        ?>
    </td>
</tr>

<tr>
    <div id="response"></div>
    <ul id="image-list">
</tr>
        </tbody>
    </table>
</form>


<div id="errfrmMsg"></div>
