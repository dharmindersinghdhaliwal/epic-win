<?php
global $epic_admin;

// $options = get_option('epic_options');
// echo "<pre>";print_r($options);exit;

?>

<form id="epic-download-export-users-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="<?php _e('Export Users','epic'); ?>"><?php _e('Export Users', 'epic'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_users' => __('All Users', 'epic'),
                    //'selected_settings' => __('Selected Settings', 'epic')
                );

                $users_query = new WP_User_Query( array( 
                    'orderby' => 'registered',
                    'order'   => 'desc'
                ) );
                $result_users = $users_query->get_results();
                foreach($result_users as $user){
                    $name = trim(get_user_meta($user->ID,'first_name',true). ' ' . get_user_meta($user->ID,'last_name',true));
                    $name = ($name == '') ? $user->display_name : $name;
                    $id = $user->ID;
                    $export_types[$id] = $name;
                }
 
                echo epic_Html::drop_down(array('name' => 'site_export_users_type', 'id' => 'site_export_users_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="epic-icon-question-sign epic-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'epic') ?>"></i>
            </td>
        </tr>

        

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo epic_Html::button('button', array('name' => 'epic-download-export-users', 'id' => 'epic-download-export-users', 'value' => __('Download','epic'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>