<?php
    global $epic_admin;

    $epic_admin->add_plugin_setting(
                        'input',
                        'email_from_name',
                        __('Email from name', 'epic'), array(),
                        sprintf(__('Provide the name to be displayed as the sender of notification emails.', 'epic')),
                        __('Users will receive notification emails from the name specificed here.', 'epic')
                                    );


    $epic_admin->add_plugin_setting(
                        'input',
                        'email_from_address',
                        __('Email from address', 'epic'), array(),
                        sprintf(__('Provide the email to be used as the sender for notification emails.', 'epic'), ini_get('upload_max_filesize')),
                        __('Users will receive notification emails from the email address specificed here.', 'epic')
                                    );

    $epic_admin->add_plugin_module_setting(
            'checkbox',
            'notifications_all_admins',
            'notifications_all_admins',
            __('Enable Notifications for All Admins', 'epic'),
            '1',
            __('If checked, all administrators of the site will receive email notifications.', 'epic'),
            __('Checking this option will allow all the admins to receive email notifications.', 'epic')
    );