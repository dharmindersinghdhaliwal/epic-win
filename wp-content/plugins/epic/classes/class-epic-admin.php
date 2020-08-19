<?php

class epic_Admin {

    var $options;
    public $field_validate_errors;
    /* constructor for admin panel */

    function __construct() {
        $this->wp_all_pages = false;
        $this->wp_all_posts = false;
        $this->wp_all_categories = false;

        $this->slug = 'wp-epic';
        $this->tabs = array('general' => __('epic Settings', 'epic'), 'customizer' => __('Custom Fields', 'epic'), 'sync' => __('Sync / Tools', 'epic'), 'user_cache' => __('Update Search Cache', 'epic'));
        $this->default_tab = 'general';
        add_action('admin_menu', array(&$this, 'add_menu'), 9);
        add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);

        $current_option = get_option('epic_options');

        $this->defaults = array(
            'html_user_login_message' => __('Please log in to view / edit your profile.', 'epic'),
            'html_login_to_view' => __('Please log in to view user profiles.', 'epic'),
            'html_private_content' => __('This content is for members only. You must log in to view this content.', 'epic'),
            'clickable_profile' => 1,
            'set_password' => 1,
            'guests_can_view' => 1,
            'users_can_view' => 1,
            'style' => 'default',
            'profile_page_id' => '0',
            'login_page_id' => '0',
            'registration_page_id' => '0',
            'redirect_backend_profile' => '0',
            'redirect_backend_registration' => '0',
            'redirect_backend_login' => '0',
            'html_registration_disabled' => __('User registration is currently not allowed.', 'epic'),
            'link_author_posts_page' => '1',
            'msg_register_success' => __('Registration successful. Please check your email.', 'epic'),
            'automatic_login' => 0,
            'login_redirect_page_id' => '0',
            'date_format' => 'mm/dd/yy',
            'show_empty_field_on_profile' => '0',
            'show_empty_field_on_profile' => '0',
            'hide_frontend_admin_bar' => 'enabled',
            'profile_title_field' => 'display_name',
            'select_user_role_in_registration' => '0',
            'choose_roles_for_registration' => get_option('default_role'),
            'label_for_registration_user_role' => __('Select Role', 'epic'),
            'reset_password_page_id' => '0',
            'set_email_confirmation' => '0',
            'lightbox_avatar_cropping' => '1',
            'show_recent_user_posts' => '0',
            'maximum_allowed_posts' => '3',
            'show_feature_image_posts' => '0',
            'website_link_on_profile' => '0',
            'default_predefined_country' => 'US',
            'enforce_password_strength' => '0',
            'choose_roles_for_view_profile' => 'administrator',
            'profile_modal_window_shortcode' => '[epic]',
            'disable_fancybox_script_styles' => '0',
            'html_login_to_view_form' => '1',
            'html_user_login_message_form' => '1',
            'html_private_content_form' => '1',
            'html_other_profiles_restricted' => __('Viewing of other profiles restricted for your user role.', 'epic'),
            'profile_view_status' => '1',
            'html_profile_status_msg' => __('This is a private profile. You are not allowed to view this profile.', 'epic'),
            'display_profile_status' => '0',
            'html_profile_approval_pending_msg' => __('This profile is pending approval. You will get a notification once the profile is approved.', 'epic'),
            'profile_approval_status' => '0',
            'ajax_profile_field_save' => '0',
            'html_terms_and_conditions' => __('I agree to Terms and Conditions.', 'epic'),
            'accepting_terms_and_conditions' => '0',
            'site_lockdown_status' => '0',
            'site_lockdown_allowed_pages' => '',
            'site_lockdown_allowed_posts' => '',
            'site_lockdown_allowed_urls' => '',
            'site_lockdown_redirect_url' => $current_option['login_page_id'],
            'site_lockdown_rss_feed' => '0',
            'html_members_private_content' => __('This content is restricted for your user account.', 'epic'),
            'link_post_author_to_epic' => '0',
            'display_profile_after_post' => '0',
            'author_post_profile_template' => '0',
            'disable_fitvids_script_styles' => '0',
            'disable_tipsy_script_styles' => '0',
            'disable_opensans_google_font' => '0',
            'email_two_factor_verification_status' => '0',
            'profile_tabs_display_status' => 'disabled',
            'profile_tabs_initial_display_status' => 'enabled',
            'email_from_name' => __('WordPress','epic'),
            'email_from_address' => epic_get_default_email_address(),
            'notifications_all_admins' => '0',
            'help_text_html' => '0',
            'register_form_title_text' => __('Your display name will appear here.','epic'),
            'register_form_title_type_username' => '1',
            'delete_user_profiles' => 'disabled',
            'profile_collapsible_tabs' => '0',
            'profile_collapsible_tabs_display' => '0',
            'profile_cover_image_status' => '0',
        );

        /* epic Filters for customizing defualt options */
        $this->defaults = apply_filters('epic_init_options',$this->defaults);
        // End Filter

        $this->default_settings = array(
                    'epic-general-settings' => array(
                                'style' => 'default',
                                'date_format' => 'mm/dd/yy',
                                'hide_frontend_admin_bar' => 'enabled',
                                'lightbox_avatar_cropping' => '1',
                                'ajax_profile_field_save' => '0',
                            ),
                    'epic-profile-settings' => array(
                                'clickable_profile' => '1',
                                'link_author_posts_page' => '1',
                                'avatar_max_size' => '2',
                                'show_separator_on_profile' => '0',
                                'show_empty_field_on_profile' => '0',
                                'profile_title_field' => 'display_name',
                                'show_recent_user_posts' => '0',
                                'maximum_allowed_posts' => '3',
                                'show_feature_image_posts' => '0',
                                'website_link_on_profile' => '0',
                                'profile_modal_window_shortcode' => '[epic]',
                                'profile_view_status' => '1',
                                'display_profile_status' => '0',
                                'link_post_author_to_epic' => '0',
                                'display_profile_after_post' => '0',
                                'author_post_profile_template' => '0',
                                'email_two_factor_verification_status' => '0',
                                'profile_tabs_display_status' => 'disabled',
                                'profile_tabs_initial_display_status' => 'enabled',
                                'delete_user_profiles' => 'disabled',
                                'profile_cover_image_status' => '0',

                            ),
                    'epic-system-pages' => array(
                                'profile_page_id' => '0',
                                'login_page_id' => '0',
                                'registration_page_id' => '0',
                                'reset_password_page_id' => '0'
                            ),
                    'epic-redirect-setting' => array(
                                'redirect_backend_profile' => '0',
                                'redirect_backend_login' => '0',
                                'redirect_backend_registration' => '0',
                                'login_redirect_page_id' => '0',
                                'register_redirect_page_id' => '0'
                            ),
                    'epic-registration-option' => array(
                                'set_password' => '1',
                                'automatic_login' => '0',
                                'captcha_plugin' => 'none',
                                'captcha_label' => 'Captcha',
                                'recaptcha_public_key' => '',
                                'recaptcha_private_key' => '',
                                'msg_register_success' => __('Registration successful. Please check your email.','epic'),
                                'html_register_success_after' => '',
                                'select_user_role_in_registration' => '0',
                                'choose_roles_for_registration' => get_option('default_role'),
                                'label_for_registration_user_role' => __('Select Role', 'epic'),
                                'set_email_confirmation' => '0',
                                'default_predefined_country' => 'US',
                                'enforce_password_strength' => '0',
                                'profile_approval_status' => '0',
                                'accepting_terms_and_conditions' => '0',
                                'register_form_title_text' => __('Your display name will appear here.','epic'),
                                'register_form_title_type_username' => '1',
                            ),
                    'epic-search-settings' =>array(
                                'use_cron' => '1',
                                'require_search_input' => '0',
                                'users_are_called' => __('User','epic'),
                                'combined_search_text' => __('Combined Search','epic'),
                                'search_button_text' => __('Filter','epic')
                            ),
                    'epic-privacy-option' => array(
                                'users_can_view' => '1',
                                'guests_can_view' => '1',
                                'choose_roles_for_view_profile' => 'administrator',
                            ),
                    'epic-misc-messages' => array(
                                'html_login_to_view' => __('Please log in to view user profiles.','epic'),
                                'html_user_login_message' => __('Please log in to view / edit your profile.','epic'),
                                'html_private_content' => __('This content is for members only. You must log in to view this content.','epic'),
                                'html_registration_disabled' => __('User registration is currently not allowed.','epic'),
                                'html_login_to_view_form' => '1',
                                'html_user_login_message_form' => '1',
                                'html_private_content_form' => '1',
                                'html_other_profiles_restricted' => __('Viewing of other profiles restricted for your user role.', 'epic'),
                                'html_profile_status_msg' => __('This is a private profile. You are not allowed to view this profile.', 'epic'),
                                'html_profile_approval_pending_msg' => __('This profile is pending approval. You will get a notification once the profile is approved.', 'epic'),
                                'html_terms_and_conditions' => __('I agree to Terms and Conditions.', 'epic'),
                                'html_members_private_content' => __('This content is restricted for your user account.', 'epic'),

                            ),
                    'epic-scripts-styles' => array(
                                'disable_fancybox_script_styles' => '0',
                                'disable_fitvids_script_styles' => '0',
                                'disable_tipsy_script_styles' => '0',
                                'disable_opensans_google_font' => '0',
                            ),
                );

        $this->default_module_settings = array(
                    'epic-site-lockdown-settings' => array(
                                'site_lockdown_status' => '0',
                                'site_lockdown_allowed_pages' => '',
                                'site_lockdown_allowed_posts' => '',
                                'site_lockdown_allowed_urls' => '',
                                'site_lockdown_redirect_url' => $current_option['login_page_id'],
                                'site_lockdown_rss_feed' => '0',
                            ),
                    'epic-email-general-settings' => array(
                                'email_from_name' => __('WordPress','epic'),
                                'email_from_address' => epic_get_default_email_address(),
                                'notifications_all_admins' => '0',
                            ),
                    'epic-custom-fields-settings' => array(
                                'help_text_html' => '0',
                            ),
                    );

        /* epic Filters for customizing defualt module options */
        $this->default_module_settings = apply_filters('epic_default_module_settings',$this->default_module_settings);
        // End Filter


        $this->colorsdefault = array();

        $this->option_with_checkbox = array('redirect_backend_profile', 'redirect_backend_registration', 'redirect_backend_login', 'link_author_posts_page', 'show_separator_on_profile', 'show_empty_field_on_profile'
            , 'use_cron','select_user_role_in_registration','lightbox_avatar_cropping','show_recent_user_posts','disable_fancybox_script_styles',
            'html_login_to_view_form','html_user_login_message_form','html_private_content_form','ajax_profile_field_save',
            'site_lockdown_status','link_post_author_to_epic','display_profile_after_post','disable_fitvids_script_styles',
            'disable_tipsy_script_styles','disable_opensans_google_font','notifications_all_admins');

        /* epic Filters for customizing checkbox options */
        $this->option_with_checkbox = apply_filters('epic_option_with_checkbox',$this->option_with_checkbox);
        // End Filter

        $this->options = get_option('epic_options');

        if (!get_option('epic_options')) {
            update_option('epic_options', $this->defaults);
        }

        /* Store icons in array */
        $this->fontawesome = $this->list_font_awesome_icons();

        asort($this->fontawesome);

        // Adding Action hook to show additional profile fields
        add_action('show_user_profile', array($this, 'epic_user_extra_fields'));
        add_action('edit_user_profile', array($this, 'epic_user_extra_fields'));
        //add_action( 'load-profile.php', array($this,'epic_user_extra_fields') );
        // Adding Action hook to save additional profile fields
        add_action('personal_options_update', array($this, 'epic_save_user_extra_fields'), 9999);
        add_action('edit_user_profile_update', array($this, 'epic_save_user_extra_fields'));

        if (is_admin ()) {
            add_action('wp_ajax_update_user_cache', array($this, 'epic_update_user_cache'));
            add_action('wp_ajax_save_epic_settings', array($this, 'epic_save_settings'));
            add_action('wp_ajax_reset_epic_settings', array($this, 'epic_reset_settings'));

            add_action('wp_ajax_epic_update_custom_field', array($this, 'epic_ajax_update_custom_field'));
            add_action('wp_ajax_epic_reset_custom_fields', array($this, 'reset_all'));
            add_action('wp_ajax_epic_create_custom_field', array($this, 'epic_ajax_create_custom_field'));
            add_filter('user_profile_update_errors', array($this,'epic_profile_update_errors'), 10, 3);

            add_action ('user_register',array($this, 'epic_register_backend_user'));
        }

        define('PROFILE_HELP', __('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.', 'epic'));
        define('SEPARATOR_HELP', __('A Meta Key may be added to your separator in order to reference it with the [epic view=x,x,x] shortcode option. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.', 'epic'));

        add_action('admin_footer', array($this,'epic_user_action_buttons'));
        add_action('load-users.php', array($this,'epic_users_page_loaded'));
        add_action('admin_notices', array($this,'epic_bulk_admin_notices'));

        add_filter('manage_users_columns', array($this,'epic_manage_user_custom_columns'));
        add_action('manage_users_custom_column', array($this,'epic_manage_user_custom_column_values'), 10, 3);
        add_filter('manage_users_sortable_columns', array($this,'epic_users_sortable_columns'));
        add_action('pre_user_query', array($this,'epic_users_orderby_filters'));


        $custom_file_field_types_params = array();
        $this->custom_file_field_types = apply_filters('epic_custom_file_field_types',array(), $custom_file_field_types_params );

        $this->errors = null;
    }

    /* add styles */

    function add_styles($current_page_hook) {
        $current_option = get_option('epic_options');

        /* admin panel css */
        wp_register_style('epic_admin', epic_url . 'admin/css/epic-admin.css');
        wp_enqueue_style('epic_admin');

        if (!wp_script_is('epic_admin_tipsy_js') && '0' == $current_option['disable_tipsy_script_styles'] ) {
            wp_register_script('epic_admin_tipsy_js', epic_url . 'js/jquery.tipsy.js', array('jquery'));
            wp_enqueue_script('epic_admin_tipsy_js');
        }

        if (!wp_script_is('epic_admin_tipsy') && '0' == $current_option['disable_tipsy_script_styles'] ) {
            wp_register_style('epic_admin_tipsy', epic_url . 'css/tipsy.css');
            wp_enqueue_style('epic_admin_tipsy');
        }

        wp_register_script('epic_admin', epic_url . 'admin/js/epic-admin.js');
        wp_enqueue_script('epic_admin');

        // Add scripts for various modules
        wp_register_script('epic_admin_modules', epic_url . 'admin/js/epic-admin-modules.js');
        wp_enqueue_script('epic_admin_modules');

        $admin_options_array = array(
            'profileKey' => __('New Custom Meta Key', 'epic'),
            'separatorKey' => __('Meta Key', 'epic'),
            'profileLabel' => __('Label', 'epic'),
            'separatorLabel' => __('Separator Text', 'epic'),
            'profileHelp' => PROFILE_HELP,
            'separatorHelp' => SEPARATOR_HELP,
            'AdminAjax' => admin_url('admin-ajax.php'),
            'savingSetting' => __('Saving...','epic'),
            'saveSetting' => __('Save Changes','epic'),
            'resettingSetting' => __('Resetting...','epic'),
            'resetSetting' => __('Reset Options','epic'),
            'adminURL' => get_admin_url('', 'admin.php?page=epic-settings'),
            'fieldDeleteConfirm' => __('Are you sure you want to delete this field?', 'epic'),
            'cacheCompletedUsers' => __('users Completed', 'epic'),
            'fieldUpdateProcessing' => __(' Processing.....', 'epic'),
            'fieldUpdateCompleted' => __(' Update Completed', 'epic'),
            'customFileFieldTypes' => $this->custom_file_field_types,
        );
        wp_localize_script('epic_admin', 'epicAdmin', $admin_options_array);

        $admin_modules_options_array = array(
            'AdminAjax' => admin_url('admin-ajax.php'),
            'adminURL' => get_admin_url('', 'admin.php?page=epic-modules'),
            'userRoleRequired' => __('User role is required.', 'epic'),
            'redirectURLRequired' => __('Redirect URL is required.', 'epic'),
            'pageRequired' => __('Restricted Pages are required.', 'epic'),
            'postRequired' => __('Restricted Posts are required.', 'epic'),
            'categoriesRequired' => __('Restricted Categories are required.', 'epic'),
            'savingResRule' => __('Adding Restriction Rule', 'epic'),
            'saveResRule' => __('Add Restriction Rule','epic'),
            'savingSetting' => __('Saving...','epic'),
            'saveSetting' => __('Save Changes','epic'),
            'resettingSetting' => __('Resetting...','epic'),
            'resetSetting' => __('Reset Options','epic'),
            'emailTitleRequired' => __('Email Template Title is required','epic'),
            'emailSubjectRequired' => __('Email Subject is required','epic'),
            'downloadFields' => __('Downloading Fields','epic'),
            'downloadSettings' => __('Downloading Settings','epic'),
        );
        wp_localize_script('epic_admin_modules', 'epicAdminModules', $admin_modules_options_array);

        wp_register_style('epic_font_awesome', epic_url . 'css/font-awesome.min.css');
        wp_enqueue_style('epic_font_awesome');

        /* google fonts */
        if ('0' == $current_option['disable_opensans_google_font']) {
            wp_register_style('epic_google_fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&subset=latin,latin-ext');
            wp_enqueue_style('epic_google_fonts');
        }

        /* Drag & Drop */
        //wp_register_script('epic_drag_drop', epic_url . 'admin/js/drag-drop.js');
        //wp_enqueue_script('epic_drag_drop');
        wp_enqueue_script('jquery-ui-sortable');


        /* Tabify */
        wp_register_script('epic_tabify', epic_url . 'admin/js/epic-tabify.js', array('jquery', 'epic_admin'));
        wp_enqueue_script('epic_tabify');


        if ('profile.php' == $current_page_hook || 'user-edit.php' == $current_page_hook) {
            wp_register_style('epic_date_picker', epic_url . 'css/epic-datepicker.css');
            wp_enqueue_style('epic_date_picker');

            wp_register_script('epic_date_picker_js', epic_url . 'js/epic-datepicker.js', array('jquery'));
            wp_enqueue_script('epic_date_picker_js');

            wp_localize_script('epic_date_picker_js', 'epicDatePicker', epic_date_picker_setting());
        }

        $lang_strings = epic_tinymce_language_setting();

        wp_register_script('epic_tmce', epic_url . 'admin/js/tinymce_language_strings.js');
        wp_enqueue_script('epic_tmce');

        wp_localize_script('epic_tmce', 'epicTmce', $lang_strings);

        // Add scripts for chosen library
        //wp_register_script('epic_chosen_js', epic_url . 'admin/js/chosen/chosen.jquery.js');
        //wp_enqueue_script('epic_chosen_js');

        wp_register_style('epic_chosen_css', epic_url . 'admin/js/chosen/chosen.css');
        wp_enqueue_style('epic_chosen_css');

        do_action('epic_admin_add_styles');
    }

    // add menu
    function add_menu() {

        // Adding epic Menu
        add_menu_page(__('epic Settings', "epic"), __("epic Settings", "epic"),'manage_options','epic-settings',array(&$this,'epic_settings'));

        // Adding epic Sub Menus
        add_submenu_page('epic-settings', __("Custom Fields", "epic"), __("Custom Fields", "epic"),'manage_options','epic-field-customizer',array(&$this,'epic_customizer'));

        add_submenu_page('epic-settings', __("Modules", "epic"), __("Modules", "epic"),'manage_options','epic-modules',array(&$this,'epic_manage_modules'));

        add_submenu_page('epic-settings', __("Sync / Tools", "epic"), __("Sync / Tools", "epic"),'manage_options','epic-sync-tools',array(&$this,'epic_sync_tools'));

        add_submenu_page('epic-settings', __("Update Search Cache", "epic"), __("Update Search Cache", "epic"),'manage_options','epic-search-cache',array(&$this,'epic_update_search_cache'));

        add_submenu_page('epic-settings', __("Add-ons", "epic"), __("Add-ons", "epic"),'manage_options','epic-add-ons',array(&$this,'epic_addons_page'));

    }

    public function epic_settings()
    {
        include_once(epic_path.'admin/settings.php');
    }

    public function epic_customizer()
    {
        /**
         * @submit settings page
         */
        if (epic_is_in_post('submit'))
        {
            $this->epic_update_custom_field($_POST,'normal');
            //$this->update();
        }

        /* Create a new field */
        if (epic_is_in_post('epic-add'))
        {
            $this->epic_add_custom_field($_POST,'normal');
        }

        /* Trash field */

        if (epic_is_get()) {
            if (isset($_GET['trash_field']) && !isset($_POST['submit']) && !isset($_POST['reset-options']) && !isset($_POST['reset-options-fields'])) {
                $fields = get_option('epic_profile_fields');
                $trash = $_GET['trash_field'];
                if (isset($fields[$trash])) {

                    /* epic Action for before deleteing custom field */
                    do_action('epic_before_delete_field',$fields[$trash]);
                    // End Action

                    if(isset($this->delete_error)){
                        if('' != $this->delete_error)
                            echo '<div class="updated"><p><strong>' . $this->delete_error . '</strong></p></div>';

                    }else{

                        $trash_field = $fields[$trash];
                        unset($fields[$trash]);
                        update_option('epic_profile_fields', $fields);

                        /* epic Action for after deleting custom field */
                        do_action('epic_after_delete_field',$trash_field);
                        // End Action


                        $update_cache_link = ' <a href="' . get_admin_url('', 'admin.php?page=epic-search-cache') . '">' . __('Update Now', 'epic') . '</a>';

                        echo '<div class="updated"><p><strong>' . __('Profile field was sent to Trash. It is recommended to update your user search cache.', 'epic') . $update_cache_link . '</strong></p></div>';

                    }
                }
            }
        }

        if (epic_is_in_post('reset-options-fields'))
        {
            $this->reset_all('normal');
        }

        include_once(epic_path.'admin/field-builder.php');
    }

    public function epic_manage_modules()
    {
        include_once(epic_path.'admin/modules.php');
    }

    public function epic_sync_tools()
    {
        include_once(epic_path.'admin/sync-tool.php');
    }

    public function epic_update_search_cache()
    {
        include_once(epic_path.'admin/user-cache.php');
    }

    public function epic_addons_page()
    {
        global $epic_template_loader;

        ob_start();
        $epic_template_loader->get_template_part('addons','list');
        $display = ob_get_clean();
        echo $display;
    }

    // get value in admin option
    function get_value($option_id) {

        if (isset($this->options[$option_id]) && $this->options[$option_id] != '') {
            return $this->options[$option_id];
        } elseif (isset($this->defaults[$option_id]) && $this->defaults[$option_id] != '') {
            return $this->defaults[$option_id];
        } else {
            return null;
        }
    }

    // add normal info
    function add_plugin_info($label, $content) {
        print "<tr valign=\"top\">
        <th scope=\"row\"><label>$label</label></th>
        <td class=\"epic-label\">$content</td>
        </tr>";
    }

    // add setting field
    function add_plugin_setting($type, $id, $label, $pairs, $help, $inline_help = '', $extra=null,$custom_attrs=null) {

        $td_class = '';
        if(isset($custom_attrs['checkbox_type'])){
            $td_class = 'class="epic-admin-inline-checkbox"';
        }

        $field_holder_id = $id . '_holder';
        print "<tr valign=\"top\" id=\"$field_holder_id\">
        <th scope=\"row\"><label for=\"$id\">$label</label></th>
        <td ".$td_class." >";
        $input_html = '';

        // Added hack for edit profile URL.

        $value = '';
        $value = $this->get_value($id);


        switch ($type) {

            case 'textarea':
                echo epic_Html::text_area(array('name' => $id, 'id' => $id, 'class' => 'large-text code text-area', 'value' => $value, 'rows' => '3'));
                break;

            case 'input':
                echo epic_Html::text_box(array('name' => $id, 'id' => $id, 'value' => htmlspecialchars($value), 'class' => 'regular-text'));
                break;

            case 'select':
                echo epic_Html::drop_down(array('name' => $id, 'id' => $id), $pairs, $this->options[$id]);
                break;

            case 'checkbox':
                echo epic_Html::check_box(array('name' => $id, 'id' => $id, 'value' => '1'), $value);
                break;

            case 'color':
                $default_color = $this->defaults[$id];
                echo epic_Html::text_box(array('name' => $id, 'id' => $id, 'value' => $value, 'class' => 'my-color-field', 'data-default-color' => $default_color));
                break;

            case 'checkbox_list':
                $selected_roles = $value;


                $disabled_roles = array();
                if(isset($custom_attrs['disabled'])){
                    $disabled_roles = $custom_attrs['disabled'];
                }

                foreach ($pairs as $role_key => $role) {
                    if(in_array($role_key, $disabled_roles)){
                        echo epic_Html::check_box(array('name' => $id.'[]', 'id' => $id, 'value' => $role_key,'checked'=>'checked','disabled'=>'disabled')).$role.'<br/>';
                    }else{
                        $checked_value = '';
                        if(is_array($selected_roles) && in_array($role_key,$selected_roles)){
                            $checked_value = $role_key;
                        }
                        echo epic_Html::check_box(array('name' => $id.'[]', 'id' => $id, 'value' => $role_key),$checked_value).$role.'<br/>';
                    }

                }
                break;
        }

        // Add description for inline checkboxes of parent field
        if(isset($custom_attrs['checkbox_type']) && $custom_attrs['checkbox_type'] == 'inline'){
            print '<span>'.$custom_attrs['message'].'</span>';
        }

        if ($inline_help != '') {
            print '<i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" title="' . $inline_help . '"></i>';
        }


        if ($help)
            print "<p class=\"description\">$help</p>";

        if (is_array($extra)) {
            echo "<div class=\"helper-wrap\">";
            foreach ($extra as $a) {
                echo $a;
            }
            echo "</div>";
        }

        print "</td></tr>";
    }

    // save form
    // function saveform() {
    //     //echo "<pre>";print_r($_POST);exit;
    //     foreach ($_POST as $key => $value) {
    //         if ($key != 'submit') {

    //             if (strpos($key, 'epic') !== false) {

    //                 /* Save new fields */
    //                 $array_key = filter_var($key, FILTER_SANITIZE_NUMBER_INT);

    //                 $new_pos = filter_var($_POST['epic_' . $array_key . '_position'], FILTER_SANITIZE_NUMBER_INT);

    //                 $plain_key = str_replace('epic_' . $array_key . '_', '', $key);

    //                 if (!is_array($value)) {
    //                     $form_fields[$new_pos][$plain_key] = stripslashes($value);
    //                 } else {
    //                     $form_fields[$new_pos][$plain_key] = $value;
    //                 }

    //                 if ($plain_key == 'name' && $value != '') {
    //                     $form_fields[$new_pos][$plain_key] = esc_html($value);
    //                 }

    //                 if ($plain_key == 'meta_custom' && $value != '') {
    //                     $form_fields[$new_pos]['meta'] = esc_html($value);
    //                 }

    //                 if ($plain_key == 'icon' && $value != '') {
    //                     $form_fields[$new_pos]['icon'] = $value;
    //                 } else {
    //                     $form_fields[$new_pos]['icon'] = 0;
    //                 }

    //                 if ($plain_key == 'private' && $value == 1) {
    //                     $form_fields[$new_pos]['can_hide'] = 0;
    //                 }

    //                 if ($plain_key == 'show_to_user_role_list') {
    //                     $form_fields[$new_pos]['show_to_user_role_list'] = implode(',', $value);
    //                 }

    //                 if ($plain_key == 'edit_by_user_role_list') {
    //                     $form_fields[$new_pos]['edit_by_user_role_list'] = implode(',', $value);
    //                 }
    //             } else {

    //                 if (strpos($key, 'html_') !== false) {
    //                     $this->options[$key] = stripslashes($value);
    //                 } else {
    //                     $this->options[$key] = esc_attr($value);
    //                 }
    //             }
    //         }
    //     }

    //     if (isset($form_fields) && is_array($form_fields)) {
    //         ksort($form_fields);
    //         update_option('epic_profile_fields', $form_fields);
    //     }
    // }



    // save default colors
    function save_default_colors() {
        $alloptions = get_option('epic_options');
        foreach ($this->colorsdefault as $k => $v) {
            $alloptions[$k] = $v;
            $this->options[$k] = $v;
        }
    }

    // update settings
    function update() {

        foreach ($this->option_with_checkbox as $key => $value) {
            if (isset($_GET['tab'])) {
                $current = $_GET['tab'];
            } else {
                $current = $this->default_tab;
            }

            if ($current == 'general') {

                if (!isset($_POST[$value]))
                    $this->options[$value] = '0';
            }
        }

        update_option('epic_options', $this->options);

        $update_cache_link = ' <a href="' . get_admin_url('', 'admin.php?page=epic-search-cache') . '">' . __('Update Now', 'epic') . '</a>';

        echo '<div class="updated"><p><strong>' . __('Settings saved. It is recommended to update your user search cache.', 'epic') . $update_cache_link . '</strong></p></div>';
    }

    // reset settings
    function reset() {
        update_option('epic_options', $this->defaults);
        $this->options = array_merge($this->options, $this->defaults);
        echo '<div class="updated"><p><strong>' . __('Settings are reset to default.', 'epic') . '</strong></p></div>';
    }

    function reset_all($type= '') {
        global $epic;
        update_option('epic_profile_fields', $epic->fields);

        $update_cache_link = ' <a href="' . get_admin_url('', 'admin.php?page=epic-search-cache') . '">' . __('Update Now', 'epic') . '</a>';

        if('normal' == $type){
            echo '<div class="updated"><p><strong>' . __('Settings are reset to default. It is recommended to update your user search cache.', 'epic') . $update_cache_link . '</strong></p></div>';
        }else{
            echo json_encode(array('status'=> 'success','redirect_to' => admin_url( 'admin.php?page=epic-field-customizer') ));exit;
        }

    }

    /* Get admin tabs */

    function admin_tabs($current = null) {
        $tabs = $this->tabs;
        $links = array();
        if (isset($_GET['tab'])) {
            $current = $_GET['tab'];
        } else {
            $current = $this->default_tab;
        }
        foreach ($tabs as $tab => $name) :
            if ($tab == $current) :
                $links[] = "<a class='nav-tab nav-tab-active' href='?page=" . $this->slug . "&tab=$tab'>$name</a>";
            else :
                $links[] = "<a class='nav-tab' href='?page=" . $this->slug . "&tab=$tab'>$name</a>";
            endif;
        endforeach;
        foreach ($links as $link)
            echo $link;
    }

    /* get tab ID and load its content */

    function get_tab_content() {
        $screen = get_current_screen();
        if (strstr($screen->id, $this->slug)) {
            if (isset($_GET['tab'])) {
                $tab = $_GET['tab'];
            } else {
                $tab = $this->default_tab;
            }
            $this->load_tab($tab);
        }
    }

    /* load tab */

    function load_tab($tab) {
        require_once epic_path . 'admin/' . $tab . '.php';
    }

    // add settings
    function settings_page() {

        /**
         * @submit settings page
         */
        if (isset($_POST['submit'])) {
            $this->epic_update_custom_field($_POST,'normal');
            $this->update();
        }

        /* Create a new field */
        if (isset($_POST['epic-add'])) {
            $this->epic_add_custom_field($_POST,'normal');
        }

        /* Trash field */

        if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
            if (isset($_GET['trash_field']) && !isset($_POST['submit']) && !isset($_POST['reset-options']) && !isset($_POST['reset-options-fields'])) {
                $fields = get_option('epic_profile_fields');
                $trash = $_GET['trash_field'];
                if (isset($fields[$trash])) {
                    unset($fields[$trash]);
                    update_option('epic_profile_fields', $fields);
                    echo '<div class="updated"><p><strong>' . __('Profile field was sent to Trash.', 'epic') . '</strong></p></div>';
                }
            }
        }


        /**
         * @submit theme reset button
         */
        if (isset($_POST['reset-custom-theme'])) {
            $this->epic_update_custom_field($_POST,'normal');
            $this->save_default_colors();
            $this->update();
        }

        /**
         * @callback to restore all options
         */
        if (isset($_POST['reset-options'])) {
            $this->reset();
        }

        if (isset($_POST['reset-options-fields'])) {
            $this->reset_all('normal');
        }
?>

        <div class="wrap">
            <div id="epic-icon epic-icon-<?php echo $this->slug; ?>" class="icon32">
                <br />
            </div>
            <h2 class="nav-tab-wrapper">
<?php $this->admin_tabs(); ?>
            </h2>
            <form method="post" action="" id="epic-custom-field-add">
<?php $this->get_tab_content(); ?>
        <p class="submit">
        <?php
        if (epic_get_value('tab') != 'user_cache') {
            echo epic_Html::button('submit', array(
                'name' => 'submit',
                'id' => 'submit',
                'value' => __('Save Changes', 'epic'),
                'class' => 'button button-primary'
            ));
        }
        ?>
            <?php
            if (isset($_GET['tab'])) {
                $tab = $_GET['tab'];
            } else {
                $tab = $this->default_tab;
            }


            if ($tab == 'customizer') {
                echo epic_Html::button('submit', array(
                    'name' => 'reset-options-fields',
                    'value' => __('Reset to Default Fields', 'epic'),
                    'class' => 'button button-secondary'
                ));
            }


            if ($tab == 'user_cache') {
                echo epic_Html::button('button', array(
                    'name' => 'reset-options-fields',
                    'id' => 'epic-update-user-cache',
                    'value' => __('Update User Cache', 'epic'),
                    'class' => 'button button-primary'
                ));
            }
            ?>




        </p>
    </form>
</div>

<?php
        }

        function get_all_pages() {
            if ($this->wp_all_pages === false) {
                $this->wp_all_pages[0] = __('Select Page','epic');
                foreach (get_pages () as $key => $value) {
                    $this->wp_all_pages[$value->ID] = $value->post_title;
                }
            }

            return $this->wp_all_pages;
        }

        function get_all_posts() {
            if ($this->wp_all_posts === false) {
                $this->wp_all_posts[0] = __('Select Post','epic');
                $args = array('posts_per_page'   => -1, 'post_status' => 'publish');
                foreach (get_posts ($args) as $key => $value) {
                    $this->wp_all_posts[$value->ID] = $value->post_title;
                }
            }

            return $this->wp_all_posts;
        }

        function get_all_categories() {
            if ($this->wp_all_categories === false) {
                $this->wp_all_categories[0] = __('Select Category','epic');
                $args = array( 'type'  => 'post');
                foreach (get_categories ($args) as $key => $value) {
                    $this->wp_all_categories[$value->term_id] = $value->name;
                }
            }

            return $this->wp_all_categories;
        }

        // Additional user fields

        function epic_user_extra_fields($user) {
            global $predefined, $epic_roles, $epic_profile_fields,$epic;

            // Set date format from admin settings
            $epic_settings = get_option('epic_options');
            $epic_date_format = (string) isset($epic_settings['date_format']) ? $epic_settings['date_format'] : 'mm/dd/yy';

            $array = get_option('epic_profile_fields');

            if ( current_user_can('edit_user', $user->ID) || current_user_can('manage_epic_options', $user->ID) ) {
                $fields = get_option('epic_profile_fields');

                // These are default fields from WP Team
                $exclude_fields = array('rich_editing', 'admin_color', 'comment_shortcuts', 'admin_bar_front', 'user_login', 'first_name', 'last_name', 'nickname', 'display_name', 'email', 'url', 'aim', 'yim', 'jabber', 'description', 'pass1', 'pass2', 'user_pass_confirm', 'user_pass', 'user_email', 'user_url');

                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                    $exclude_woocommerce_fields = array(
                        'billing_first_name','billing_last_name','billing_company','billing_address_1',
                        'billing_address_2','billing_city', 'billing_postcode','billing_state',
                        'billing_country', 'billing_phone' ,  'billing_email','shipping_first_name',
                        'shipping_last_name', 'shipping_company','shipping_address_1', 'shipping_address_2',
                        'shipping_city', 'shipping_postcode','shipping_state', 'shipping_country');

                    $exclude_fields =  array_merge($exclude_fields, $exclude_woocommerce_fields);
                }

                if (count($fields) > 0) {
                    echo "<h3>".__('epic Fields','epic')."</h3>";

                    echo '<table class="form-table">';
                    echo '<tbody>';

                    $logged_in_user = 0;
                    if (is_user_logged_in ()) {
                        $current_user = wp_get_current_user();
                        if (($current_user instanceof WP_User)) {
                            $logged_in_user = $current_user->ID;
                        }
                    }

                    $epic_roles->epic_get_user_roles_by_id($user->ID);

                    /* Add mandatory profile fields, which are not avaiable in custom fields section */
                    $display = $epic_profile_fields->epic_backend_mandatory_fields($epic_settings,$user);
                    echo $display;


                    /* epic Filters for customizing profile display fields */
                    $backend_profile_fields_params = array('user_id' => $user->ID, 'form_name' => get_user_meta($user->ID,'epic-register-form-name',true));
                    $fields = apply_filters( 'epic_backend_profile_display_fields', $fields, $backend_profile_fields_params);
                    // End Filters

                    foreach ($fields as $key => $value) {
                        //echo "<pre>";
                        //print_r($fields);
                        //exit;

                        $show_to_user_role = isset($value['show_to_user_role']) ? $value['show_to_user_role'] : '0';
                        $show_to_user_role_list = isset($value['show_to_user_role_list']) ? $value['show_to_user_role_list'] : '';

                        $epic_roles->epic_get_user_roles_by_id($user->ID);
                        $show_field_status = $epic_roles->epic_empty_fields_by_user_role($show_to_user_role, $show_to_user_role_list);

                        $edit_by_user_role = isset($value['edit_by_user_role']) ? $value['edit_by_user_role'] : '0';
                        $edit_by_user_role_list = isset($value['edit_by_user_role_list']) ? $value['edit_by_user_role_list'] : '';

                        $epic_roles->epic_get_user_roles_by_id($user->ID);
                        $edit_field_status = $epic_roles->epic_fields_by_user_role($edit_by_user_role, $edit_by_user_role_list);

                        // Hiding fields based on show by user role setting
                        if ( (current_user_can('manage_options') || current_user_can('manage_epic_options') ) && $show_field_status) {
                            $display_show_status = true;
                        } else if (!$show_field_status) {
                            $display_show_status = false;
                        } else {
                            $display_show_status = true;
                        }


                        // Disabling fields based on editable by user role setting
                        if ( (current_user_can('manage_options') || current_user_can('manage_epic_options') ) && $edit_field_status) {
                            $display_edit_status = true;
                        } else if (!$edit_field_status) {
                            $display_edit_status = false;
                        } else {
                            $display_edit_status = true;
                        }

                        /* Do not show private fields */
                        $private = isset($value['private']) ? $value['private'] : 0;


                        if($display_show_status && ($private == 0 || ($private == 1 && (current_user_can('manage_options') || current_user_can('manage_epic_options')) ) )){

                            $required = isset($value['required']) ? $value['required'] : 0;

                            $required_class = '';
                            $required_sign  = '';

                            if ($required == 1 && in_array($value['field'], $epic->include_for_validation)) {
                                $required_class = ' required';
                                $required_sign  = '<span class="epic-required">&nbsp;*</span>';
                            }


                            // field should not be separator and should be from exclude field
                            if ($value['type'] == 'usermeta' && isset($value['meta']) && !in_array($value['meta'], $exclude_fields) && ( !in_array( $value['field'] , $this->custom_file_field_types )) ) {
                                echo '<tr>';
                                echo '<th scope="row"><label for="' . $value['meta'] . '">' . $value['name'] . '</label>&nbsp;' . $required_sign . '</th>';

                                // Checking if field should be editable or not
                                // For admin always allow
                                if (current_user_can('manage_options') || current_user_can('manage_epic_options') ) {
                                    $disabled = null;
                                }
                                else if(!$display_edit_status){
                                    $disabled = 'disabled="disabled"';
                                }
                                else {
                                    $disabled = null;
                                }

                                $backend_profile_value_params = array('user_id' => $user->ID, 'meta' => $value['meta']);

                                switch ($value['field']) {
                                    case 'textarea':
                                        $params = array('meta'=>$value['meta'],'id' => $user->ID);
                                        $custom_editor_styles = apply_filters('epic_text_editor_styles','',$params);

                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);

                                        echo '<td><textarea ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '" rows="5" cols="30" class="'.$custom_editor_styles.'">' . $display_field_val . '</textarea></td>';
                                        break;

                                    case 'text':

                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);

                                        echo '<td><input type="text" ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '" value="' . esc_attr($display_field_val) . '" class="regular-text"></td>';
                                        break;

                                    case 'datetime':
                                        $formatted_date_value = '';
                                        $date_values = esc_attr(get_the_author_meta($value['meta'],$user->ID));
                                        if('' != $date_values){
                                            $formatted_date_value = epic_date_format_to_custom($date_values, $epic_date_format);
                                        }

                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], $formatted_date_value, $backend_profile_value_params );

                                        echo '<td><input readonly="readonly" type="text" ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '" value="' . $formatted_date_value . '" class="regular-text epic-datepicker">';
                                        echo '<input type="button" class="epic-button-alt epic-datepicker-reset" value="'.__('Clear Date','epic').'" /></td>';

                                        break;

                                    case 'select':
                                        $loop = array();
                                        if (isset($value['predefined_loop']) && $value['predefined_loop'] != '' && $value['predefined_loop'] != '0') {
                                            $loop = $predefined->get_array($value['predefined_loop']);
                                            if('countries' == $array[$key]['predefined_loop']){
                                                array_shift($loop);
                                            }

                                        } else if (isset($value['choices']) && $value['choices'] != '') {
                                            $loop = explode(PHP_EOL, $value['choices']);
                                        }

                                        /* epic filter for customizing select field values */
                                        $backend_select_field_custom_values_params = array('meta' => $value['meta'], 'name' => $value['name'], 'id' => $user->ID );
                                        $loop = apply_filters('epic_backend_select_field_custom_values',$loop,$backend_select_field_custom_values_params);
                                        /* End filter */

                                        // Check for country loop
                                        $country_loop_status = isset($value['predefined_loop']) ? $value['predefined_loop'] : '';
                                        $profile_user_meta = get_the_author_meta($value['meta'], $user->ID);
                                        if('' == $profile_user_meta && '' != $country_loop_status && 'countries' == $country_loop_status){

                                            $profile_user_meta = isset($loop[$epic_settings['default_predefined_country']] ) ? $loop[$epic_settings['default_predefined_country']] : '0';
                                        }

                                        $display = '';
                                        if (count($loop) > 0) {
                                            $display .= '<td><select ' . $disabled . ' class="input" name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '">';
                                            $display .= '<option value="" ' . selected($profile_user_meta, "", 0) . '>' . __('Please Select', 'epic') . '</option>';
                                            foreach ($loop as $option) {
                                                $option = epic_stripslashes_deep(trim($option));

                                                $display .= '<option value="' . $option . '" ' . selected($profile_user_meta, $option, 0) . '>' . $option . '</option>';
                                            }
                                            $display .= '</select></td>';
                                        }
                                        echo $display;

                                        break;

                                    case 'radio':
                                        $display = '';
                                        if (isset($value['choices'])) {
                                            $loop = explode(PHP_EOL, $value['choices']);
                                        }

                                        /* epic filter for customizing radio field values */
                                        $backend_radio_field_custom_values_params = array('meta' => $value['meta'], 'name' => $value['name'], 'id' => $user->ID );
                                        $loop = apply_filters('epic_backend_radio_field_custom_values',$loop,$backend_radio_field_custom_values_params);
                                        /* End filter */

                                        if (isset($loop) ) {
                                            $counter = 0;
                                            $display.='<td>';
                                            foreach ($loop as $option) {

                                                if ($counter > 0)
                                                    $required_class = '';

                                                // Added as per http://codecanyon.net/item/user-profiles-made-easy-wordpress-plugin/discussion/4109874?filter=All+Discussion&page=27#comment_4352415
                                                $option = epic_stripslashes_deep(trim($option));

                                                $display.='<label for="' . $value['meta'] . '_' . $counter . '">';

                                                $display.='<input ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '_' . $counter . '" type="radio" value="' . $option . '"';

                                                $values = explode(', ', get_the_author_meta($value['meta'], $user->ID));

                                                if ($option == get_the_author_meta($value['meta'], $user->ID)) {
                                                    $display .= ' checked="checked"';
                                                }
                                                $display.='>&nbsp;&nbsp;';

                                                $display.=$option;
                                                $display.='</label>';

                                                $display.='<br />';

                                                $counter++;
                                            }
                                            $display.='</td>';
                                            unset($loop);
                                        }

                                        echo $display;


                                        break;

                                    case 'checkbox':
                                        $loop = array();
                                        $display = '';
                                        if (isset($value['choices'])) {
                                            $loop = explode(PHP_EOL, $value['choices']);
                                        }

                                        /* epic filter for customizing checkbox field values */
                                        $backend_checkbox_field_custom_values_params = array('meta' => $value['meta'], 'name' => $value['name'], 'id' => $user->ID );
                                        $loop = apply_filters('epic_backend_checkbox_field_custom_values',$loop,$backend_checkbox_field_custom_values_params);
                                        /* End filter */

                                        if (isset($loop) ) {
                                            $counter = 0;
                                            $display.='<td>';
                                            foreach ($loop as $option) {

                                                if ($counter > 0)
                                                    $required_class = '';

                                                // Added as per http://codecanyon.net/item/user-profiles-made-easy-wordpress-plugin/discussion/4109874?filter=All+Discussion&page=27#comment_4352415
                                                $option = epic_stripslashes_deep(trim($option));

                                                $display.='<label for="' . $value['meta'] . '_' . $counter . '">';

                                                $display.='<input ' . $disabled . ' name="epic[' . $value['meta'] . '][]" id="' . $value['meta'] . '_' . $counter . '" type="checkbox" value="' . $option . '"';

                                                $values = explode(', ', get_the_author_meta($value['meta'], $user->ID));
                                                if (in_array($option, $values)) {
                                                    $display .= ' checked="checked"';
                                                }
                                                $display.='>&nbsp;&nbsp;';

                                                $display.=$option;
                                                $display.='</label>';

                                                $display.='<br />';

                                                $counter++;
                                            }
                                            $display.='</td>';
                                            unset($loop);
                                        }

                                        echo $display;

                                        break;

                                    case 'video':

                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);
                                        echo '<td><input type="text" ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '" value="' . esc_attr($display_field_val) . '" class="regular-text"></td>';
                                        break;


                                    case 'soundcloud':
                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);
                                        echo '<td><input type="text" ' . $disabled . ' name="epic[' . $value['meta'] . ']" id="' . $value['meta'] . '" value="' . esc_attr($display_field_val) . '" class="regular-text"></td>';
                                        break;

                                    case 'fileupload':
                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);
                                        if( '' != esc_attr($display_field_val)){
                                            echo '<td><img src="' . esc_attr($display_field_val) . '" class="epic-admin-profile-image" /></td>';
                                        }else{
                                            echo '<td>-</td>';
                                        }
                                        break;



                                    default:

                                        /* epic Filter for showing custom field types in backend edit mode */
                                        $display_field_val =  apply_filters('epic_backend_edit_profile_value_' . $value['meta'], get_the_author_meta($value['meta'], $user->ID), $backend_profile_value_params);

                                        $edit_backend_custom_field_type_input_params = array('name' => "epic[" . $value['meta'] . "]", 'user_id' => $user->ID, 'value' => $display_field_val, 'disabled' => $disabled,  'meta' => $value['meta'], 'field' => $value['field'], 'array' => $value);
                                        $display = apply_filters('epic_backend_edit_custom_field_type_input','', $edit_backend_custom_field_type_input_params);

                                        if($display != ''){
                                            echo '<td>'.$display.'</td>';
                                        }
                                        // End filter
                                        break;
                                }

                                echo '</tr>';
                            }
                        }
                    }

                    echo '</tbody>';
                    echo '</table>';
                }
            }
        }

        function epic_save_user_extra_fields($user_id) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['epic']) && is_array($_POST['epic']) && count($_POST['epic']) > 0) {
                    // Set date format from admin settings
                    $epic_settings = get_option('epic_options');
                    $epic_date_format = (string) isset($epic_settings['date_format']) ? $epic_settings['date_format'] : 'mm/dd/yy';


                    // Get profile fields
                    $profile_fields = get_option('epic_profile_fields');

                    // Get list of dattime fields
                    $date_time_fields = array();
                    // Get list of required fields
                    $required_fields = array();

                    foreach ($profile_fields as $key => $field) {
                        $field_settings = $field;
                        extract($field);

                        // Set checkbox values to null, for preventing checkboxes saving issue when all are deselected
                        if (isset($profile_fields[$key]['field']) && $profile_fields[$key]['field'] == 'checkbox') {
                            update_user_meta($user_id, $meta, null);
                        }

                        // Filter date/time custom fields
                        if (isset($profile_fields[$key]['field']) && $profile_fields[$key]['field'] == 'datetime') {
                            array_push($date_time_fields, $profile_fields[$key]['meta']);
                        }
                        if(isset($profile_fields[$key]['required']) && $profile_fields[$key]['required'] == '1'){
                            $required_fields[$profile_fields[$key]['meta']] = $field_settings;
                        }

                        /* epic filter for adding restrictions before custom field type saving */
                        $backend_custom_field_type_restrictions_params = array('field_settings' => $field_settings);
                        $this->errors = apply_filters('epic_backend_custom_field_type_restrictions', $this->errors, $_POST['epic'] , $backend_custom_field_type_restrictions_params);
                        /* END filter */
                    }

                    foreach ($_POST['epic'] as $key => $value) {

                        // Validation for required fields
                        if (array_key_exists($key, $required_fields)) {

                            switch ($required_fields[$key]['field']) {
                                case 'text':
                                case 'textarea':
                                case 'datetime':
                                case 'select':
                                case 'fileupload':
                                case 'password':
                                case 'video':
                                case 'soundcloud':

                                    if('' == trim($value)){
                                        $this->errors[] = __($required_fields[$key]['name'],'epic'). __(' is required.','epic');
                                    }
                                    break;

                                case 'radio':
                                case 'checkbox':

                                    break;

                                default:
                                    if('' == trim($value)){
                                        $this->errors[] = __($required_fields[$key]['name'],'epic'). __(' is required.','epic');
                                    }
                                    break;
                            }

                        }
                        //$this->errors[]

                        if(!(isset($this->errors) && count($this->errors) > 0)){


                            if (is_array($value))
                                $value = implode(', ', $value);

                            if (in_array($key, $date_time_fields)) {
                                if('' != $value){
                                    $formatted_date = epic_date_format_to_standerd($value, $epic_date_format);
                                    $value = $formatted_date;
                                }
                            }

                            /* epic action for executing custom functionality before saving a field */
                            do_action('epic_before_save_backend_field_'.$key,$key,$value,$user_id);
                            // End Action

                            // To Do Need to check for adding new meta when it was not same as old
                            update_user_meta($user_id, $key, $value);

                            /* epic action for executing custom functionality after saving a field */
                            do_action('epic_after_save_backend_field_'.$key,$key,$value,$user_id);
                            // End Action

                            if($key == 'epic_user_profile_status'){
                                /* epic action for executing custom functionality after saving a field */
                                do_action('epic_user_profile_status_update',$key,$value,$user_id);
                                // End Action
                            }

                        }
                    }


                    if(!(isset($this->errors) && count($this->errors) > 0)){
                        // Save core WordPress fields to user meta table
                        update_user_meta($user_id, 'first_name', isset($_POST['first_name']) ? $_POST['first_name'] : '');
                        update_user_meta($user_id, 'last_name', isset($_POST['last_name']) ? $_POST['last_name'] : '');
                        if(isset($_POST['user_email']) && '' != $_POST['user_email']){
                            update_user_meta($user_id, 'user_email', $_POST['user_email']);
                        }

                        epic_update_user_cache($user_id);
                    }
                }
            }
        }

        // Get the icon names of font awesome from stylesheet
        function list_font_awesome_icons() {

            $icons = array("glass", "music", "search", "envelope-o", "heart", "star", "star-o", "user", "film", "th-large",
                "th", "th-list", "check", "times", "search-plus", "search-minus", "power-off", "signal", "cog", "trash-o",
                "home", "file-o", "clock-o", "road", "download", "arrow-circle-o-down", "arrow-circle-o-up", "inbox",
                "play-circle-o", "repeat", "refresh", "list-alt", "lock", "flag", "headphones", "volume-off", "volume-down",
                "volume-up", "qrcode", "barcode", "tag", "tags", "book", "bookmark", "print", "camera", "font", "bold", "italic",
                "text-height", "text-width", "align-left", "align-center", "align-right", "align-justify", "list", "outdent",
                "indent", "video-camera", "picture-o", "pencil", "map-marker", "adjust", "tint", "pencil-square-o", "share-square-o",
                "check-square-o", "arrows", "step-backward", "fast-backward", "backward", "play", "pause", "stop", "forward",
                "fast-forward", "step-forward", "eject", "chevron-left", "chevron-right", "plus-circle", "minus-circle", "times-circle",
                "check-circle", "question-circle", "info-circle", "crosshairs", "times-circle-o", "check-circle-o", "ban", "arrow-left",
                "arrow-right", "arrow-up", "arrow-down", "share", "expand", "compress", "plus", "minus", "asterisk", "exclamation-circle",
                "gift", "leaf", "fire", "eye", "eye-slash", "exclamation-triangle", "plane", "calendar", "random", "comment", "magnet",
                "chevron-up", "chevron-down", "retweet", "shopping-cart", "folder", "folder-open", "arrows-v", "arrows-h", "bar-chart-o",
                "twitter-square", "facebook-square", "camera-retro", "key", "cogs", "comments", "thumbs-o-up", "thumbs-o-down",
                "star-half", "heart-o", "sign-out", "linkedin-square", "thumb-tack", "external-link", "sign-in", "trophy", "github-square",
                "upload", "lemon-o", "phone", "square-o", "bookmark-o", "phone-square", "twitter", "facebook", "github", "unlock",
                "credit-card", "rss", "hdd-o", "bullhorn", "bell", "certificate", "hand-o-right", "hand-o-left", "hand-o-up", "hand-o-down",
                "arrow-circle-left", "arrow-circle-right", "arrow-circle-up", "arrow-circle-down", "globe", "wrench", "tasks", "filter",
                "briefcase", "arrows-alt", "users", "link", "cloud", "flask", "scissors", "files-o", "paperclip", "floppy-o", "square",
                "bars", "list-ul", "list-ol", "strikethrough", "underline", "table", "magic", "truck", "pinterest", "pinterest-square",
                "google-plus-square", "google-plus", "money", "caret-down", "caret-up", "caret-left", "caret-right", "columns", "sort",
                "sort-desc", "sort-asc", "envelope", "linkedin", "undo", "gavel", "tachometer", "comment-o", "comments-o", "bolt",
                "sitemap", "umbrella", "clipboard", "lightbulb-o", "exchange", "cloud-download", "cloud-upload", "user-md", "stethoscope",
                "suitcase", "bell-o", "coffee", "cutlery", "file-text-o", "building-o", "hospital-o", "ambulance", "medkit", "fighter-jet",
                "beer", "h-square", "plus-square", "angle-double-left", "angle-double-right", "angle-double-up", "angle-double-down",
                "angle-left", "angle-right", "angle-up", "angle-down", "desktop", "laptop", "tablet", "mobile", "circle-o", "quote-left",
                "quote-right", "spinner", "circle", "reply", "github-alt", "folder-o", "folder-open-o", "smile-o", "frown-o", "meh-o",
                "gamepad", "keyboard-o", "flag-o", "flag-checkered", "terminal", "code", "reply-all", "star-half-o", "location-arrow",
                "crop", "code-fork", "chain-broken", "question", "info", "exclamation", "superscript", "subscript", "eraser", "puzzle-piece",
                "microphone", "microphone-slash", "shield", "calendar-o", "fire-extinguisher", "rocket", "maxcdn", "chevron-circle-left",
                "chevron-circle-right", "chevron-circle-up", "chevron-circle-down", "html5", "css3", "anchor", "unlock-alt", "bullseye",
                "ellipsis-h", "ellipsis-v", "rss-square", "play-circle", "ticket", "minus-square", "minus-square-o", "level-up", "level-down",
                "check-square", "pencil-square", "external-link-square", "share-square", "compass", "caret-square-o-down", "caret-square-o-up",
                "caret-square-o-right", "eur", "gbp", "usd", "inr", "jpy", "rub", "krw", "btc", "file", "file-text", "sort-alpha-asc",
                "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-numeric-asc", "sort-numeric-desc", "thumbs-up", "thumbs-down",
                "youtube-square", "youtube", "xing", "xing-square", "youtube-play", "dropbox", "stack-overflow", "instagram", "flickr", "adn",
                "bitbucket", "bitbucket-square", "tumblr", "tumblr-square", "long-arrow-down", "long-arrow-up", "long-arrow-left",
                "long-arrow-right", "apple", "windows", "android", "linux", "dribbble", "skype", "foursquare", "trello", "female", "male",
                "gittip", "sun-o", "moon-o", "archive", "bug", "vk", "weibo", "renren", "pagelines", "stack-exchange", "arrow-circle-o-right",
                "arrow-circle-o-left", "caret-square-o-left", "dot-circle-o", "wheelchair", "vimeo-square", "try", "plus-square-o",
                "space-shuttle", "slack", "envelope-square", "wordpress", "openid", "university", "graduation-cap", "yahoo", "google", "reddit",
                "reddit-square", "stumbleupon-circle", "stumbleupon", "delicious", "digg", "pied-piper", "pied-piper-alt", "drupal", "joomla",
                "language", "fax", "building", "child", "paw", "spoon", "cube", "cubes", "behance", "behance-square", "steam", "steam-square",
                "recycle", "car", "taxi", "tree", "spotify", "deviantart", "soundcloud", "database", "file-pdf-o", "file-word-o", "file-excel-o",
                "file-powerpoint-o", "file-image-o", "file-archive-o", "file-audio-o", "file-video-o", "file-code-o", "vine", "codepen",
                "jsfiddle", "life-ring", "circle-o-notch", "rebel", "empire", "git-square", "git", "hacker-news", "tencent-weibo", "qq", "weixin",
                "paper-plane", "paper-plane-o", "history", "circle-thin", "header", "paragraph", "sliders", "share-alt", "share-alt-square", "bomb","tty",
                "plug","newspaper-o","lastfm","futbol-o","cc-stripe","cc-amex","birthday-cake","bell-slash","at","binoculars",
                "cc","cc-paypal","eyedropper","ioxhost","meanpath","pie-chart","slideshare","trash","yelp","wifi","toggle-on","ils","paypal",
                "line-chart","copyright","cc-mastercard","calculator","bicycle","area-chart","angellist","bell-slash-o","bus","cc-discover","cc-visa"
                ,"google-wallet","lastfm-square","paint-brush","toggle-off","twitch");

            return $icons;
        }

        function epic_update_user_cache() {
            global $wpdb;

            $limit = 10;

            $user_query = "SELECT ID FROM " . $wpdb->users . " ORDER BY ID ASC LIMIT " . esc_sql(epic_post_value('last_user')) . ',' . $limit;

            $users = $wpdb->get_results($user_query, 'ARRAY_A');

            $count = 0;
            $last_processed_id = epic_post_value('last_user');

            foreach ($users as $key => $value) {
                epic_update_user_cache($value['ID']);
                $last_processed_id++;
                $count++;
            }

            if ($count < $limit) {
                echo "completed";
            } else {
                echo $last_processed_id;
            }

            die;
        }

                function epic_save_settings()
        {
            $this->checkbox_options = array(
                    'epic-general-settings-form' => array('lightbox_avatar_cropping','ajax_profile_field_save'),
                    'epic-profile-settings-form' => array('link_author_posts_page', 'show_separator_on_profile',
                            'show_empty_field_on_profile','show_recent_user_posts','show_feature_image_posts',                 'profile_view_status','display_profile_status','link_post_author_to_epic','display_profile_after_post',
                                                          'email_two_factor_verification_status','profile_cover_image_status'),
                    'epic-system-pages-form' => array(),
                    'epic-redirect-setting-form' => array('redirect_backend_profile', 'redirect_backend_login', 'redirect_backend_registration'),
                    'epic-registration-option-form' => array('select_user_role_in_registration','choose_roles_for_registration','register_form_title_type_username'),
                    'epic-search-settings-form' => array('use_cron','require_search_input'),
                    'epic-privacy-option-form' => array('choose_roles_for_view_profile'),
                    'epic-misc-messages-form' => array('html_login_to_view_form','html_user_login_message_form','html_private_content_form'),
                    'epic-scripts-styles-form' => array('disable_fancybox_script_styles','disable_fitvids_script_styles','disable_tipsy_script_styles',
                                                       'disable_opensans_google_font')
            );

            $current_options = get_option('epic_options');

            parse_str($_POST['data'], $setting_data);

            foreach($setting_data as $key=>$value){
                $current_options[$key]=$value;

                /* Filter values of specific settings */
                $filter_settings = array('register_form_title_text', 'users_are_called', 'combined_search_text', 'search_button_text', 'reset_button_text', 'html_login_to_view', 'html_user_login_message', 'html_private_content', 'html_members_private_content', 'html_other_profiles_restricted', 'html_registration_disabled', 'html_profile_status_msg','html_profile_approval_pending_msg','html_terms_and_conditions');
                if(in_array($key,$filter_settings)){
                    $current_options[$key] = stripslashes_deep($current_options[$key]);
                }
            }


            if(count($this->checkbox_options[epic_post_value('current_tab')]) > 0)
            {

                foreach($this->checkbox_options[epic_post_value('current_tab')] as $key=>$value)
                {
                    if(!array_key_exists($value, $setting_data))
                        $current_options[$value]='0';
                }
            }

            flush_rewrite_rules();

            if($current_options['choose_roles_for_registration'] == 0){
                $current_options['choose_roles_for_registration'] = '';
            }

            if($current_options['choose_roles_for_view_profile'] == 0){
                $current_options['choose_roles_for_view_profile'] = '';
            }

            update_option('epic_options', $current_options);
            echo "success"; die;
        }


        function epic_reset_settings()
        {
            if(epic_is_post() && epic_is_in_post('current_tab'))
            {
                if(isset($this->default_settings[epic_post_value('current_tab')]))
                {
                    $current_options = get_option('epic_options');

                    foreach($this->default_settings[epic_post_value('current_tab')] as $key=>$value)
                        $current_options[$key] = $value;

                    update_option('epic_options', $current_options);
                    echo "success"; die;
                }
            }
        }

        // List the fields allowed to be set as the display title of the profile
        function epic_profile_title_fields(){
            $fields = get_option('epic_profile_fields');
            $text_fields = array();

            $combined_name_options = 0;
            $label_first_name = '';
            $label_last_name = '';


            foreach ($fields as $key => $field_data) {
                if(isset($field_data['field']) && 'text' == $field_data['field']){
                    $text_fields[$field_data['meta']] = $field_data['name'];
                }

                if( 'first_name' == $field_data['meta'] ){
                    $label_first_name = $field_data['name'];
                    $combined_name_options++;
                }
                if( 'last_name' == $field_data['meta']){
                    $label_last_name = $field_data['name'];
                    $combined_name_options++;
                }
            }

            if(2 == $combined_name_options){
                $text_fields['combined_fname_lname'] = $label_first_name . " " . $label_last_name;
                $text_fields['combined_lname_fname'] = $label_last_name . ", " . $label_first_name;
            }

            $text_fields['username'] = __('Username','epic');

            return $text_fields;
        }


        /* ==  Customizing the user list with new columns and actions ==  */

        function epic_user_action_buttons() {
            $screen = get_current_screen();
            if ( $screen->id != "users" )   // Only add to users.php page
                return;

            $activate_profile               = __('Activate Profile','epic');
            $deactivate_profile             = __('Deactivate Profile','epic');
            $approve_profile                = __('Approve Profile','epic');
            $deapprove_profile              = __('Disapprove Profile','epic');
            $activate_profile_status        = __('Set Profile Status to Acitve','epic');
            $deactivate_profile_status      = __('Set Profile Status to Inacitve','epic');
            $activate_profile_permission    = __('Add Profile Manage Permission','epic');
            $deactivate_profile_permission  = __('Remove Profile Manage Permission','epic');

            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('<option>').val('epic_act_user').text("<?php echo $activate_profile; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_apr_user').text("<?php echo $approve_profile; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_deact_user').text("<?php echo $deactivate_profile; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_deapr_user').text("<?php echo $deapprove_profile; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_activate_profile_status_user').text("<?php echo $activate_profile_status; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_deactivate_profile_status_user').text("<?php echo $deactivate_profile_status; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_activate_profile_permission').text("<?php echo $activate_profile_permission; ?>").appendTo("select[name='action']");
                    $('<option>').val('epic_deactivate_profile_permission').text("<?php echo $deactivate_profile_permission; ?>").appendTo("select[name='action']");


                });
            </script>
            <?php
        }

        function epic_users_page_loaded() {
            if( (isset($_GET['action']) && $_GET['action'] === 'epic_act_user') ||
                (isset($_GET['action2']) && $_GET['action2'] === 'epic_act_user')) {
                $act_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $act_users) {
                    foreach ($act_users as $act_user) {
                        $meta = get_user_meta($act_user, 'epic_approval_status', true);
                        if('ACTIVE' != $meta){
                            update_user_meta($act_user, 'epic_activation_status', 'ACTIVE');
                            epic_update_user_cache($act_user);
                        }
                    }

                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_apr_user') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_apr_user')){
                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {

                    $current_option = get_option('epic_options');
                    $link = get_permalink($current_option['login_page_id']);

                    foreach ($apr_users as $apr_user) {
                        $meta = get_user_meta($apr_user, 'epic_approval_status', true);
                        if('ACTIVE' != $meta){
                                    update_user_meta($apr_user, 'epic_approval_status', 'ACTIVE');
                                    epic_update_user_cache($apr_user);
                                    epic_admin_approval_notification($apr_user,$link);
                        }
                    }
                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_deact_user') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_deact_user')  ){
                $act_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $act_users) {
                    foreach ($act_users as $act_user) {
                        update_user_meta($act_user, 'epic_activation_status', 'INACTIVE');
                        epic_update_user_cache($act_user);
                    }

                }

            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_deapr_user') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_deapr_user')  ){
                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {

                    foreach ($apr_users as $apr_user) {
                        update_user_meta($apr_user, 'epic_approval_status', 'INACTIVE');
                        epic_update_user_cache($apr_user);
                    }
                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_activate_profile_status_user') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_activate_profile_status_user')  ){
                $users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $users) {

                    foreach ($users as $user) {
                        update_user_meta($user, 'epic_user_profile_status', 'ACTIVE');
                        epic_update_user_cache($user);
                    }
                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_deactivate_profile_status_user') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_deactivate_profile_status_user')  ){
                $users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $users) {

                    foreach ($users as $user) {
                        update_user_meta($user, 'epic_user_profile_status', 'INACTIVE');
                        epic_update_user_cache($user);
                    }
                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_activate_profile_permission') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_activate_profile_permission')  ){
                $users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $users) {

                    foreach ($users as $user) {
                        $user = new WP_User( $user );
                        $user->add_cap( 'manage_epic_options' );

                    }
                }
            }else if( (isset($_GET['action']) && $_GET['action'] === 'epic_deactivate_profile_permission') ||
                      (isset($_GET['action2']) && $_GET['action2'] === 'epic_deactivate_profile_permission')  ){
                $users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $users) {

                    foreach ($users as $user) {
                        $user = new WP_User( $user );
                        $user->remove_cap( 'manage_epic_options' );

                    }
                }
            }
        }

        function epic_bulk_admin_notices(){
            $screen = get_current_screen();
            if ( $screen->id != "users" )   // Only add to users.php page
                return;

            $message = '';

            if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_act_user') {
                $act_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $act_users) {
                    $message = __( 'Users activated successfully.','epic');

                }else{
                    $message = __( 'Select users for activation.','epic');
                }

            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_apr_user') {
                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile approved successfully.','epic');
                }else{
                    $message = __( 'Select users for approval.','epic');
                }

            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_deact_user') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile deactivated successfully.','epic');
                }else{
                    $message = __( 'Select users for deactivation.','epic');
                }

            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_deapr_user') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile disapproved successfully.','epic');
                }else{
                    $message = __( 'Select users for disapproval.','epic');
                }
            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_activate_profile_status_user') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile status changed to ACTIVE.','epic');
                }else{
                    $message = __( 'Select users for disapproval.','epic');
                }
            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_deactivate_profile_status_user') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile status changed to INACTIVE.','epic');
                }else{
                    $message = __( 'Select users for disapproval.','epic');
                }
            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_activate_profile_permission') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile permission changed.','epic');
                }else{
                    $message = __( 'Select users for adding permission.','epic');
                }
            }else if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'epic_deactivate_profile_permission') {

                $apr_users = isset($_GET['users']) ? $_GET['users'] : '';
                if ('' != $apr_users) {
                    $message = __( 'Users profile permission changed.','epic');
                }else{
                    $message = __( 'Select users for removing permission.','epic');
                }
            }

            if('' != $message){
                $html = '<div class="updated">
                            <p>'.$message.'</p>
                        </div>';
                echo $html;
            }

        }

        /* Adding approval and activation status columns to user list */
        function epic_manage_user_custom_columns( $column ) {
            $column['epic_apr_status'] = __('Approval Status','epic');
            $column['epic_act_status'] = __('Activation Status','epic');
            $column['epic_view_user']  = __('View User','epic');

            return $column;
        }

        function epic_manage_user_custom_column_values( $val, $column_name, $user_id ) {
            global $epic;
            $user_activation_status = get_user_meta( $user_id , 'epic_activation_status', TRUE);
            $user_approval_status = get_user_meta( $user_id , 'epic_approval_status', TRUE);

            switch ($column_name) {
                case 'epic_apr_status' :
                    return $user_approval_status;
                    break;

                case 'epic_act_status' :
                    return $user_activation_status;
                    break;

                case 'epic_view_user' :
                    return "<a class='epic-admin-user-view-link' target='_blank' href='". $epic->profile_link($user_id) ."'>".__('View Profile','epic')."</a>";
                    break;

                default:
                    //Fix Bug with other plugins using same hooks
                    return $val;
                    break;

            }
        }

        function epic_users_sortable_columns( $columns ) {
            $columns['epic_apr_status'] = 'epic_apr_status';
            $columns['epic_act_status'] = 'epic_act_status';
            return $columns;
        }

        function epic_users_orderby_filters($userquery){
            global $wpdb;
            if('epic_act_status'==$userquery->query_vars['orderby']) {

                $userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS wpusermeta ON ($wpdb->users.ID = wpusermeta.user_id) ";
                $userquery->query_where .= " AND wpusermeta.meta_key = 'epic_activation_status' ";
                $userquery->query_orderby = " ORDER BY wpusermeta.meta_value ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");

            }else if('epic_apr_status'==$userquery->query_vars['orderby']){

                $userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS wpusermeta ON ($wpdb->users.ID = wpusermeta.user_id) ";
                $userquery->query_where .= " AND wpusermeta.meta_key = 'epic_approval_status' ";
                $userquery->query_orderby = " ORDER BY wpusermeta.meta_value  ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");

            }
        }

        function epic_ajax_update_custom_field(){

            $field_options = wp_unslash($_POST['field_options']) ;
            parse_str($field_options,$field_options);
            $this->epic_update_custom_field($field_options,'ajax');
        }

        /* Update settings for custom fields */
        function epic_update_custom_field($field_options,$type){

            foreach ($field_options as $key=>$value) {
                $field_options[$key] = wp_unslash($field_options[$key]);
                if ($key != 'submit') {

                    if (strpos($key, 'epic') !== false) {

                        /* Save new fields */
                        $array_key = filter_var($key, FILTER_SANITIZE_NUMBER_INT);

                        $new_pos = filter_var($field_options['epic_' . $array_key . '_position'], FILTER_SANITIZE_NUMBER_INT);

                        $plain_key = str_replace('epic_' . $array_key . '_', '', $key);

                        if (!is_array($value)) {
                            $form_fields[$new_pos][$plain_key] = epic_stripslashes_deep($value);
                        } else {
                            $form_fields[$new_pos][$plain_key] = $value;
                        }

                        if ($plain_key == 'name' && $value != '') {
                            $form_fields[$new_pos][$plain_key] = wp_unslash(esc_html($value));
                        }

                        if ($plain_key == 'meta_custom' && $value != '') {
                            $form_fields[$new_pos]['meta'] = esc_html($value);
                        }

                        if ($plain_key == 'icon' && $value != '') {
                            $form_fields[$new_pos]['icon'] = $value;
                        } else {
                            $form_fields[$new_pos]['icon'] = 0;
                        }

                        if ($plain_key == 'private' && $value == 1) {
                            $form_fields[$new_pos]['can_hide'] = 0;
                        }

                        if ($plain_key == 'show_to_user_role_list') {
                            $form_fields[$new_pos]['show_to_user_role_list'] = implode(',', $value);
                        }

                        if ($plain_key == 'edit_by_user_role_list') {
                            $form_fields[$new_pos]['edit_by_user_role_list'] = implode(',', $value);
                        }

                        if ($plain_key == 'can_hide_role_list') {
                            $form_fields[$new_pos]['can_hide_role_list'] = implode(',', $value);
                        }
                    } else {

                        if (strpos($key, 'html_') !== false) {
                            $this->options[$key] = stripslashes($value);
                        } else {
                            $this->options[$key] = esc_attr($value);
                        }
                    }
                }
            }

            if (isset($form_fields) && is_array($form_fields)) {
                ksort($form_fields);
                update_option('epic_profile_fields', $form_fields);
            }

            if('ajax' == $type){
                echo json_encode(array('status'=>'success'));exit;
            }
        }

        function epic_ajax_create_custom_field(){

            $field_options = (wp_unslash($_POST['field_options'])) ;

            parse_str($field_options,$field_options);
            $this->epic_add_custom_field($field_options,'ajax');
        }

        function epic_add_custom_field($field_options,$type) {
            $current = get_option('epic_profile_fields');

            foreach ($field_options as $key => $value) {
                $field_options[$key] = wp_unslash($field_options[$key]);
                if ($key != 'epic-add') {
                    if (strpos($key, 'up_') !== false) {

                        $plain_key = str_replace('up_', '', $key);

                        //Error handling
                        if ($plain_key == 'position') {
                            if ($field_options[$key] != '' && !is_numeric($field_options[$key])) {
                                $this->errors[] = __('Position must be a number.', 'epic');
                            } /* elseif (isset($current[$_POST[$key]])) {
                              $this->errors[] = __('A field that has the same position already exists.','epic');
                              } */
                        }

                        if ($plain_key == 'name') {
                            if (esc_attr($field_options[$key]) == '') {
                                $this->errors[] = __('Please enter a label/name for your field.', 'epic');
                            }
                        }

                        if ($plain_key == 'meta') {
                            if ($field_options[$key] == '' && $field_options['up_meta_custom'] == '' && $field_options['up_type'] == 'usermeta') {
                                $this->errors[] = __('You must specify a usermeta / custom field.', 'epic');
                            }
                            else if($field_options[$key] != '') {
                                foreach($current as $k=>$v) {
                                    if(isset($v['meta'])){
                                        if($v['meta'] == $field_options[$key]) {
                                            $this->errors[] = __('A profile field with this meta key already exists, please use a unique meta key.', 'epic');
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($plain_key == 'meta_custom') {
                            if (esc_attr($field_options[$key]) == '' && $field_options['up_meta'] == '' && $field_options['up_type'] == 'usermeta') {
                                $this->errors[] = __('You must specify a usermeta / custom field.', 'epic');
                            } elseif (strpos($field_options[$key], ' ')) {
                                $this->errors[] = __('Invalid usermeta / custom field.', 'epic');
                            } elseif($field_options[$key] != '') {
                                foreach($current as $k=>$v) {
                                    if(isset($v['meta'])){
                                        if($v['meta'] == $field_options[$key]) {
                                            $this->errors[] = __('A profile field with this meta key already exists, please use a unique meta key.', 'epic');
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /* Show any errors */
            if (isset($this->errors) && count($this->errors) > 0) {
                $message = '<div class="error epic_field_add_msg"><p>' . $this->errors[0] . '</p></div>';
            } else {

                /* Force a position */
                if (!$field_options['up_position']) {
                    $field_options['up_position'] = max(array_keys($current)) + 10;
                }

                $current[$field_options['up_position']]['position'] = $field_options['up_position'];

                /* Update fields */
                if ($field_options['up_type'] == 'separator') {

                    $current[$field_options['up_position']]['type'] = $field_options['up_type'];
                    $current[$field_options['up_position']]['name'] = esc_html($field_options['up_name']);
                    //$current[$_POST['up_position']]['private'] = $_POST['up_private'];
                    $current[$field_options['up_position']]['show_in_register'] = $field_options['up_show_in_register'];
                    $current[$field_options['up_position']]['deleted'] = 0;

                    $current[$field_options['up_position']]['show_to_user_role'] = $field_options['up_show_to_user_role'];
                    $current[$field_options['up_position']]['edit_by_user_role'] = $field_options['up_edit_by_user_role'];

                    // Save user roles which has permission for view and edit the field
                    if (isset($field_options['up_show_to_user_role_list']) && is_array($field_options['up_show_to_user_role_list'])) {
                        $current[$field_options['up_position']]['show_to_user_role_list'] = implode(',', $field_options['up_show_to_user_role_list']);
                    }
                    if (isset($field_options['up_edit_by_user_role_list']) && is_array($field_options['up_edit_by_user_role_list'])) {
                        $current[$field_options['up_position']]['edit_by_user_role_list'] = implode(',', $field_options['up_edit_by_user_role_list']);
                    }
                    if (isset($field_options['up_can_hide_role_list']) && is_array($field_options['up_can_hide_role_list'])) {
                        $current[$field_options['up_position']]['can_hide_role_list'] = implode(',', $field_options['up_can_hide_role_list']);
                    }
                    if ($field_options['up_meta_custom'] != '') {
                        $current[$field_options['up_position']]['meta'] = esc_html($field_options['up_meta_custom']);
                    } else {
                        $current[$field_options['up_position']]['meta'] = $field_options['up_meta'];
                    }


                    $current[$field_options['up_position']]['help_text'] = esc_html($field_options['up_help_text']);
                } else {

                    $current[$field_options['up_position']]['type'] = $field_options['up_type'];
                    $current[$field_options['up_position']]['name'] = esc_html($field_options['up_name']);
                    $current[$field_options['up_position']]['social'] = $field_options['up_social'];
                    $current[$field_options['up_position']]['can_hide'] = $field_options['up_can_hide'];
                    $current[$field_options['up_position']]['field'] = $field_options['up_field'];
                    $current[$field_options['up_position']]['can_edit'] = $field_options['up_can_edit'];
                    if ($field_options['up_meta_custom'] != '') {
                        $current[$field_options['up_position']]['meta'] = esc_html($field_options['up_meta_custom']);
                    } else {
                        $current[$field_options['up_position']]['meta'] = $field_options['up_meta'];
                    }
                    $current[$field_options['up_position']]['private'] = $field_options['up_private'];
                    $current[$field_options['up_position']]['required'] = $field_options['up_required'];
                    $current[$field_options['up_position']]['icon'] = isset($field_options['up_icon']) ? $field_options['up_icon'] : '0' ;
                    $current[$field_options['up_position']]['allow_html'] = $field_options['up_allow_html'];
                    $current[$field_options['up_position']]['deleted'] = 0;
                    $current[$field_options['up_position']]['show_to_user_role'] = $field_options['up_show_to_user_role'];
                    $current[$field_options['up_position']]['edit_by_user_role'] = $field_options['up_edit_by_user_role'];

                    // Save user roles which has permission for view and edit the field
                    if (isset($field_options['up_show_to_user_role_list']) && is_array($field_options['up_show_to_user_role_list'])) {
                        $current[$field_options['up_position']]['show_to_user_role_list'] = implode(',', $field_options['up_show_to_user_role_list']);
                    }
                    if (isset($field_options['up_edit_by_user_role_list']) && is_array($field_options['up_edit_by_user_role_list'])) {
                        $current[$field_options['up_position']]['edit_by_user_role_list'] = implode(',', $field_options['up_edit_by_user_role_list']);
                    }
                    if (isset($field_options['up_can_hide_role_list']) && is_array($field_options['up_can_hide_role_list'])) {
                        $current[$field_options['up_position']]['can_hide_role_list'] = implode(',', $field_options['up_can_hide_role_list']);
                    }

                    if ($field_options['up_private'] == 1) {
                        $current[$field_options['up_position']]['can_hide'] = 0;
                    }

                    if ( ! in_array($field_options['up_field'], $this->custom_file_field_types) ) {
                        $current[$field_options['up_position']]['show_in_register'] = $field_options['up_show_in_register'];
                    }

                    $current[$field_options['up_position']]['help_text'] = esc_html($field_options['up_help_text']);
                }

                /* Done */
                ksort($current);

                update_option('epic_profile_fields', $current);

                $current_user = wp_get_current_user();
                // Updating User Meta for Admin User
                add_user_meta($current_user->ID, $current[$field_options['up_position']]['meta'], '', false);

                $update_cache_link = ' <a href="' . get_admin_url('', 'admin.php?page=epic-search-cache') . '">' . __('Update Now', 'epic') . '</a>';


                $ajax_additional_msg = '';
                if('ajax' == $type){
                    $ajax_additional_msg = __('.Click <a href="'.get_admin_url('', 'admin.php?page=epic-field-customizer').'"><strong>Here</strong></a> to refresh the fields list after creating all fields.','epic');
                }
                $message = '<div class="updated epic_field_add_msg"><p><strong>' . __('Profile field added. It is recommended to update your user search cache.', 'epic') . $update_cache_link . '</strong>'.$ajax_additional_msg.'</p></div>';

            }



            if('ajax' == $type){
                echo json_encode(array('status'=>'success','msg'=>$message));exit;
            }else{
                echo $message;
            }
        }


        function epic_profile_update_errors($errors, $update, $user){
            foreach ($this->errors as $key=>$value) {
                $errors->add($key,$value);
            }
        }

        function epic_get_custom_field(){
            $options = get_option('epic_profile_fields');
            $customFields = array();
            foreach ($options as $key=>$value) {
                $customFields[$key] = $value['name'];
            }

            return $customFields;
        }

        function add_plugin_module_setting($type,$name,$id,$label,$pairs,$help, $inline_help = '',$custom_attrs=array()) {

            $td_class = '';


            $field_holder_id = $id . '_holder';
            print "<tr valign=\"top\" id=\"$field_holder_id\">
            <th scope=\"row\"><label for=\"$id\">$label</label></th>
            <td ".$td_class." >";
            $input_html = '';

            // Added hack for edit profile URL.

            $value = '';
            $value = $this->get_value($id);
            $class = isset($custom_attrs['class']) ? $custom_attrs['class'] : '';

            switch ($type) {

                case 'textarea':
                    echo epic_Html::text_area(array('name' => $name, 'id' => $id, 'class' => 'large-text code text-area '.$class, 'value' => $value, 'rows' => '3'));
                    break;

                case 'input':
                    echo epic_Html::text_box(array('name' => $name, 'id' => $id, 'value' => $value, 'class' => 'regular-text '.$class));
                    break;

                case 'select':
                    if(isset($custom_attrs['multiple'])){
                        echo epic_Html::drop_down(array('name' => $name, 'id' => $id, 'class' => $class, 'multiple' => '','data-placeholder' => __('Please Select','epic')), $pairs, $this->options[$id]);
                    }else{
                        echo epic_Html::drop_down(array('name' => $name, 'id' => $id, 'class' => $class), $pairs, $this->options[$id]);
                    }
                    break;

                case 'checkbox':
                    echo epic_Html::check_box(array('name' => $name, 'id' => $id, 'class' => $class, 'value' => '1'), $value);
                    break;

                case 'color':
                    $default_color = $this->defaults[$id];
                    echo epic_Html::text_box(array('name' => $name, 'id' => $id, 'value' => $value, 'class' => 'my-color-field '.$class, 'data-default-color' => $default_color));
                    break;


            }

            if ($inline_help != '') {
                print '<i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" title="' . $inline_help . '"></i>';
            }


            if ($help)
                print "<p class=\"description\">$help</p>";

            if (isset($custom_attrs['extra']) && is_array($custom_attrs['extra'])) {
                echo "<div class=\"helper-wrap\">";
                foreach ($custom_attrs['extra'] as $a) {
                    echo $a;
                }
                echo "</div>";
            }

            print "</td></tr>";
        }

        function epic_register_backend_user($user_id){
            $user_info = get_userdata($user_id);
            update_user_meta($user_id, 'first_name', $user_info->first_name);
            update_user_meta($user_id, 'last_name', $user_info->last_name);
            update_user_meta($user_id, 'display_name', $user_info->first_name.' '.$user_info->last_name);

            update_user_meta($user_id, 'epic_activation_status', "ACTIVE");
            update_user_meta( $user_id, 'epic_approval_status', 'ACTIVE' );
            update_user_meta( $user_id, 'epic_user_profile_status', 'ACTIVE' );
            epic_update_user_cache($user_id);
        }


    }

    $epic_admin = new epic_Admin();
