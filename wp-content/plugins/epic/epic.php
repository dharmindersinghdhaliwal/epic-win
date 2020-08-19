<?php
/*
  Plugin Name: Epic Profile
  Plugin URI: http://codeflox.com
  Description: To Manage user profiles
  Version: 1.2
  Author: Manoj Singh
  Author URI: http://codeflox.com
*/ 
define('epic_url', plugin_dir_url(__FILE__));
define('epic_path', plugin_dir_path(__FILE__));
define('epic_php_version_status', version_compare(phpversion(), '5.3'));
if (class_exists('FUNCAPTCHA'))
    define('FUNCAPTCHA_LOADED', true);
else
    define('FUNCAPTCHA_LOADED', false);
// Get plugin version from header
function epic_get_plugin_version() {
    $default_headers = array('Version' => 'Version');
    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');
    return $plugin_data['Version'];
}
// Add settings link on plugin page
function epic_settings_link($links) {
    $settings_link = '<a href="admin.php?page=epic-settings">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'epic_settings_link');
// load the text domain
load_plugin_textdomain('epic', false, '/epic/l10n');
/* Loading Function */
require_once epic_path . 'function/functions.php';
require_once epic_path . 'function/compatible_plugins_filters.php';
require_once epic_path . 'function/compatible_plugins_actions.php';
// New Actions for epic Search to Update Search Cache for User
add_action('epic_profile_update','epic_update_user_cache');
add_action('epic_user_register','epic_update_user_cache');
// Adding Action Hook to use for WP Cron
add_action('epic_process_cache_cron', 'epic_cron_user_cache');
register_activation_hook( __FILE__, 'epic_activation' );
register_deactivation_hook( __FILE__, 'epic_deactivation' );
/* Init */
require_once epic_path . 'init/init.php';
// Module related files
require_once epic_path . 'modules/class-epic-modules.php';
require_once epic_path . 'modules/class-epic-verify-site-restrictions.php';
require_once epic_path . 'modules/class-epic-email-templates.php';
require_once epic_path . 'modules/class-epic-woocommerce.php';
require_once epic_path . 'modules/class-epic-custom-fields.php';
require_once epic_path . 'modules/class-epic-import-export.php';
require_once epic_path . 'modules/social/epic-social.php';
require_once epic_path . 'modules/class-epic-seo.php';
require_once epic_path . 'modules/class-epic-posts-pages.php';
/* Classes */
require_once epic_path . 'classes/class-epic-options.php';
require_once epic_path . 'classes/class-epic-template-loader.php';
require_once epic_path . 'classes/class-epic-html.php';
require_once epic_path . 'classes/class-epic-captcha-loader.php';
require_once epic_path . 'classes/class-epic-predefined.php';
require_once epic_path . 'classes/class-epic-roles.php';
require_once epic_path . 'classes/class-epic.php';
require_once epic_path . 'classes/class-epic-save.php';
require_once epic_path . 'classes/class-epic-register.php';
require_once epic_path . 'classes/class-epic-login.php';
require_once epic_path . 'classes/class-epic-reset-password.php';
require_once epic_path . 'classes/class-epic-profile-fields.php';
require_once epic_path . 'classes/class-epic-profile.php';
require_once epic_path . 'classes/class-epic-private-content.php';
require_once epic_path . 'classes/class-epic-cards.php';
require_once epic_path . 'classes/class-epic-scripts-styles.php';
require_once epic_path . 'classes/class-epic-api.php';
/* Shortcodes */
require_once epic_path . 'shortcodes/shortcode-init.php';
require_once epic_path . 'shortcodes/shortcodes.php';
/* Registration customizer */
require_once epic_path . 'registration/epic-register.php';
/* Profile customizer */
require_once epic_path . 'profile/epic-profile.php';
/* Function for Forgot Password Feature */
require_once epic_path . 'forgotpass/epic-forgot-pass.php';
/* Widgets */
require_once epic_path . 'widgets/epic-widgets.php';
require_once epic_path . 'widgets/epic-login-widget.php';
/* Scripts - dynamic css */
add_action('wp_footer', 'epic_custom_scripts');
function epic_custom_scripts() {
    $epic_options = get_option('epic_options');
    $reg_form_title_username = isset($epic_options['register_form_title_type_username']) ? $epic_options['register_form_title_type_username'] : '1';
    wp_register_script('epic_custom', epic_url . 'js/epic-custom.js', array('jquery'));
    wp_enqueue_script('epic_custom');
    $custom_js_strings = array(
        'ViewProfile' => __('View Profile', 'epic'),
        'EditProfile' => __('Edit Profile', 'epic'),
        'epicUrl' => epic_url,
        'ForgotPass' => __('Forgot Password', 'epic'),
        'Login' => __('Login', 'epic'),
        'Messages' => array(
            'EnterDetails' => __('Please enter your username or email to reset password.', 'epic'),
            'EnterEmail' => __('Please enter your email address.', 'epic'),
            'ValidEmail' => __('Please enter valid username or email address.', 'epic'),
            'NotAllowed' => __('Password changes are not allowed for this user.', 'epic'),
            'EmailError' => __('We are unable to deliver email to your email address. Please contact site admin.', 'epic'),
            'PasswordSent' => __('We have sent a password reset link to your email address.', 'epic'),
            'WentWrong' => __('Something went wrong, please try again', 'epic'),
            'RegExistEmail' => __('Email is already registered.', 'epic'),
            'RegValidEmail' => __('Email is available', 'epic'),
            'RegInvalidEmail' => __('Invalid email.', 'epic'),
            'RegEmptyEmail' => __('Email is empty.', 'epic'),
            'RegExistUsername' => __('Username is already registered.', 'epic'),
            'RegValidUsername' => __('Username is available.', 'epic'),
            'RegEmptyUsername' => __('Username is empty.', 'epic'),
            'RegInValidUsername' => __('Invalid username.', 'epic'),
            'DelPromptMessage' => __('Are you sure you want to delete this image?', 'epic'),
        ),
        'AdminAjax' => admin_url('admin-ajax.php'),
        'RegFormTitleUsername' => $reg_form_title_username,
        'confirmDeleteProfile' => __('Do you want to delete the profile','epic'),
    );
    /* epic Filter for modifying custom js messgaes */
    $custom_js_strings = apply_filters('epic_custom_js_strings',$custom_js_strings);
    // End Filter
    wp_localize_script('epic_custom', 'epicCustom', $custom_js_strings);
}
add_filter( 'epic_profile_edit_bar', array($epic, 'profile_edit_bar_buttons'), 10,3);
/* Admin panel *//*
if (is_admin ()) {
    // Module related files
    require_once epic_path . 'modules/class-epic-site-restrictions.php';
    require_once epic_path . 'classes/class-epic-updater.php';
    require_once(epic_path . 'classes/class-epic-admin.php');
    require_once(epic_path . 'classes/class-epic-sync-woocommerce.php');
    require_once(epic_path . 'admin/admin-icons.php');
}*/
if ( defined( 'WPB_VC_VERSION' ) ) {
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_extend.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_login.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_registration.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_search.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_member.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_non_member.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_logout.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_reset_password.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_profile.php');
    require_once(epic_path . 'integrated_plugins/visual_composer/vc_epic_member_list.php');
}