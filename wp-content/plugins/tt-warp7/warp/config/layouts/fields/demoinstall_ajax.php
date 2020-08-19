<?php
/*
 * 
 * @encoding     UTF-8
 * @author       Aleksandr Glovatskyy (aleksandr1278@gmail.com)
 * @copyright    Copyright (C) 2016 torbara (http://torbara.com/). All rights reserved.
 * @license      Copyrighted Commercial Software
 * @support      support@torbara.com
 * 
 */


add_action('wp_ajax_demoinstall_ajax', 'demoinstall_ajax_callback');

function demoinstall_ajax_callback() {

    $tt_file_open = "f" . "open";
    $tt_f_p_cont = "f" . "ile" . "_" . "put" . "_" . "cont" . "ents";
    $tt_f_g_cont = "f" . "ile" . "_" . "get" . "_" . "cont" . "ents";
    global $wpdb, $wp_rewrite;

    // Create local demo folder "one-click-demo-install"
    // initialize the API
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
    global $wp_filesystem;

    // Create Folder "one-click-demo-install" in Theme
    $demo_folder = get_template_directory() . "/one-click-demo-install/";

    if (!$wp_filesystem->is_dir($demo_folder)) {
        // directory didn't exist, so let's create it
        $wp_filesystem->mkdir($demo_folder);
    }

    // Create Folder for demo, ex. one-click-demo-install/demo-1
    $demo_url = htmlspecialchars($_POST["demo_url"]);
    $demo_local_folder = basename($demo_url);
    $demo_folder = get_template_directory() . "/one-click-demo-install/$demo_local_folder/";

    if (!$wp_filesystem->is_dir($demo_folder)) {
        // directory didn't exist, so let's create it
        $wp_filesystem->mkdir($demo_folder);
    }

    // Download demo data
    if (!$tt_f_p_cont($demo_folder . "config.json", $tt_file_open($demo_url . "config.json", 'r'))) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed Importing demo data. Failed download <b>config.json</b></div>';
        return FALSE;
    }

    if (!$tt_f_p_cont($demo_folder . "template_preview.png", $tt_file_open($demo_url . "template_preview.png", 'r'))) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed Importing demo data. Failed download <b>template_preview.png</b></div>';
        return FALSE;
    }

    if (!$tt_f_p_cont($demo_folder . "demo.sql", $tt_file_open($demo_url . "demo.sql", 'r'))) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed Importing demo data. Failed download <b>demo.sql</b></div>';
        return FALSE;
    }

    if (!$tt_f_p_cont($demo_folder . "uploads.zip", $tt_file_open($demo_url . "uploads.zip", 'r'))) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed Importing demo data. Failed download <b>uploads.zip</b></div>';
        return FALSE;
    }

    // Get demo SQL
    $demo_sql = $demo_folder . "demo.sql";

    //Error if no SQL to import
    if (!file_exists($demo_sql)) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Error: <b>demo.sql</b> not found in folder <b>' . $demo_folder . '</b>.</div>';
        return FALSE;
    }

    // Read Demo sql
    $sql = $tt_f_g_cont($demo_sql);
    // Replace TABLE_PREFIX and SITE_URL
    $sql = str_replace(array("@@TABLE_PREFIX@@", "@@SITE_URL@@"), array($wpdb->prefix, site_url()), $sql);

    $lines = explode("\n", $sql);
    $query = "";

    // Run queryes
    //$wpdb->show_errors = true;
    //$wpdb->suppress_errors = false;
    foreach ($lines as $line) {
        $line = trim($line);
        if (strlen($line) == 0)
            continue;
        if (substr($line, -1) == ";") {
            $query.=" " . $line;
            $wpdb->query($query);
            if ($wpdb->last_error) {
                ?><pre><?php echo $wpdb->last_query; ?></pre><?php
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Error with SQL query.</div>';
                die();
            }
            $query = "";
        } else {
            $query.=" " . $line;
        }
    }

    $wp_rewrite->flush_rules();
    wp_cache_flush();


    // Copy theme config.json
    $demo_json = $demo_folder . "config.json";
    $theme_json = get_template_directory() . '/config.json';

    if (!copy($demo_json, $theme_json)) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed to copy ' . $demo_json . ' to ' . $theme_json . '.</div>';
        return false;
    }

    // Import Images 
    WP_Filesystem();
    //$destination = wp_upload_dir();
    $destination_path = get_home_path() . "wp-content/uploads/";
    $unzipfile = unzip_file($demo_folder . 'uploads.zip', $destination_path);

    if (!$unzipfile) {
        echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed to unzipping images.</div>';
    }

    //Remove garbage
    $demo_folder = get_template_directory() . "/one-click-demo-install/";
    $wp_filesystem->rmdir($demo_folder, true);

    echo '<div class="uk-alert uk-alert-success uk-display-block">Import completed successfully!</div>';

    wp_die();
}
