<?php
/**
 * 
 * Torbara for Warp Framework, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      1.0.0
 * @copyright    Copyright (C) 2016 Torbara (http://torbara.com). All rights reserved.
 * @license      See http://themeforest.net/licenses/standard?ref=torbara
 * @author       Alexander Khmelnitskiy (info@alexander.khmelnitskiy.ua)
 * @support      support@torbara.com
 * 
 */

// To Debug
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if(!class_exists("TTDemo")) {
    
    /**
     * TTDemo - It performs all the magic with demo.
     * 
     * Sorry guys, it is best that I was able to organize in terms of architecture. 
     * The field must be in single file, But for import need to do a lot of things. 
     * So we have what we have.
     */
    Class TTDemo {
        
        /** @var string|null Current Theme Name */
        public $theme_name;
        
        /** @var string|null Current Theme version */
        public $theme_version;
        
        /** @var string URL of demo.xml */
        public $demo_xml_url = "http://update.torbara.com/wordpress/demo.xml";
        
        /**
         * Constructor. Initialize the initial state.
         */
        function __construct() {    
            
            // Start WP
            $this->loadWordPressCore();
            
            // get warp
            $warp = require(WP_PLUGIN_DIR.'/tt-warp7/warp.php');
            
            // Get Theme Name and version
            $xml = $warp['dom']->create($warp['path']->path('theme:theme.xml'), 'xml');
            if ($xml) {
                $this->theme_name = $xml->first("name")->text(); // Current theme name
                $this->theme_version = $xml->first("version")->text(); // Current theme version
            } else {
                echo '<div class="uk-alert uk-display-block uk-alert-danger">Error. Something strange. Contact with support team via <strong>support@torbara.com</strong></div>';
            }
        }
        
        /**
         * Try to set biger PHP limits, to make sure the script can handle large folders/files
         */
        function setLimits () {
            // Make sure the script can handle large folders/files
            ini_set('max_execution_time', 600);
            ini_set('memory_limit', '512M');
        }
        
        /**
         * Generate Demo package
         */
        function generateDemo () {
            $this->setLimits();
            
            $this->createDBDump ();// Create DB dump in demo.sql
            $this->packAllFilesToZIP();// Pack to ZIP
            
            $this->cleanTrash();
            echo '<div class="uk-alert uk-display-block uk-alert-success">The demo package is ready! This page will automatically reloaded. </div>';
        }
        
        /**
         * Backup all WP files to zip
         * Here the magic happens :)
         */
        function packAllFilesToZIP () {
            $d_path = ABSPATH . 'tt-demo-packages/';

            //Create folder if not exist tt-demo-packages in WP root 
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem(); // initialize the API
            global $wp_filesystem;

            // Create "/tt-demo-packages/" folder
            if( !$wp_filesystem->is_dir($d_path) ) {
                $wp_filesystem->mkdir($d_path);
            }

            // Pack all files to zip
            $d_zip = new ZipArchive();
            $res = $d_zip->open($d_path . 'demo-' . time() . '.zip', ZIPARCHIVE::CREATE);
            if ( $res !== TRUE ) {
                echo '<div class="uk-alert uk-display-block uk-alert-danger">ZipArchive Error: '.$res.'</div>';
                return ;
            }

            $source = realpath(ABSPATH);
            if (is_dir($source)) {
                $iterator = new RecursiveDirectoryIterator($source);
                // skip dot files while iterating 
                $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                foreach ($files as $file) {

                    if ($file."/" == $d_path) { continue; } // Skip tt-demo-packages foder
                    if (strpos($file, $d_path) !== FALSE) { continue; } // Skip tt-demo-packages subfiles
                    if ($file == ABSPATH . 'wp-config.php') { continue; } // Skip wp-config.php

                    $file = realpath($file);
                    if (is_dir($file)) {
                        $d_zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                    } else if (is_file($file)) {
                        $d_zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                    }
                }
            } else if (is_file($source)) {
                $d_zip->addFromString(basename($source), file_get_contents($source));
            }

            $d_zip->close();
        }
        
        
        /**
         * Clean up after yourself. Remove demo.sql in root
         */
        function cleanTrash () {
            if (file_exists(ABSPATH . "demo.sql")) { unlink (ABSPATH . "demo.sql"); }
        }
        
        /**
         * Create DB dump in demo.sql
         */
        function createDBDump () {
            //Some Constants
            define("TT_DEMO_GENERATOR_DB_MAX_TIME",     5000);
            define("TT_DEMO_GENERATOR_DB_EOF_MARKER",   'TT_DEMO_GENERATOR_MYSQLDUMP_EOF');

            //Generates MySQL Dump to file
            global $wpdb;

            $wpdb->query("SET session wait_timeout = " . TT_DEMO_GENERATOR_DB_MAX_TIME);

            $handle = fopen(ABSPATH."demo.sql", 'w+');
            $tables = $wpdb->get_col('SHOW TABLES');

            $qryLimit = '100';

            $sql_header = "/* TT_DEMO_GENERATOR MYSQL SCRIPT CREATED ON : " . @date("Y-m-d H:i:s") . " */\n\n";
            $sql_header .= "/* Old Table Prefix: {" . $wpdb->prefix . "} */".PHP_EOL;
            $sql_header .= "/* Old Site URL: {" . get_option("siteurl") . "} */".PHP_EOL;
            $user = wp_get_current_user();
            $sql_header .= "/* Old User Login: {" . $user->user_login . "} */".PHP_EOL.PHP_EOL;
            $sql_header .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
            fwrite($handle, $sql_header);

            //BUILD CREATES:
            //All creates must be created before inserts do to foreign key constraints
            foreach ($tables as $table) {
                // Clear old tables
                $sql_del = "DROP TABLE IF EXISTS {$table};\n\n";
                @fwrite($handle, $sql_del);

                $create = $wpdb->get_row("SHOW CREATE TABLE `{$table}`", ARRAY_N);
                @fwrite($handle, "{$create[1]};\n\n");
            }

            //BUILD INSERTS: 
            //Create Insert in 100 row increments to better handle memory
            foreach ($tables as $table) {

                $row_count = $wpdb->get_var("SELECT Count(*) FROM `{$table}`");

                if ($row_count > $qryLimit) {
                    $row_count = ceil($row_count / $qryLimit);
                } else if ($row_count > 0) {
                    $row_count = 1;
                }

                if ($row_count >= 1) {
                    fwrite($handle, "\n/* INSERT TABLE DATA: {$table} */\n");
                }

                for ($i = 0; $i < $row_count; $i++) {
                    $sql = "";
                    $limit = $i * $qryLimit;
                    $query = "SELECT * FROM `{$table}` LIMIT {$limit}, {$qryLimit}";
                    $rows = $wpdb->get_results($query, ARRAY_A);
                    if (is_array($rows)) {
                        foreach ($rows as $row) {
                            $sql .= "INSERT INTO `{$table}` VALUES(";
                            $num_values = count($row);
                            $num_counter = 1;
                            foreach ($row as $value) {
                                if (is_null($value) || !isset($value)) {
                                    ($num_values == $num_counter) ? $sql .= 'NULL' : $sql .= 'NULL, ';
                                } else {
                                    ($num_values == $num_counter) ? $sql .= '"' . @esc_sql($value) . '"' : $sql .= '"' . @esc_sql($value) . '", ';
                                }
                                $num_counter++;
                            }
                            $sql .= ");\n";
                        }
                        fwrite($handle, $sql);
                    }
                }

                $sql = null;
                $rows = null;
            }

            $sql_footer = "\nSET FOREIGN_KEY_CHECKS = 1; \n\n";
            $sql_footer .= "/* TT_DEMO_GENERATOR WordPress Timestamp: " . date("Y-m-d H:i:s") . "*/\n";
            $sql_footer .= "/* " . TT_DEMO_GENERATOR_DB_EOF_MARKER . " */\n";
            fwrite($handle, $sql_footer);
            $wpdb->flush();
            fclose($handle);
        }
        
        /**
         * Load WordPress Core
         */
        function loadWordPressCore () {
            // WP init
            if(file_exists(realpath("../wp-load.php"))){
                require_once realpath("../wp-load.php");
            }elseif (file_exists(realpath("../../../../../../../wp-load.php"))) {
                require_once realpath("../../../../../../../wp-load.php");
            }
            
        }
        
        /**
         * Remove Demo package file
         */
        function generateDemo_FileRemove () {
            // TODO: Maybe some security checks?
            $file =  htmlspecialchars($_POST["file"]);
            if (file_exists($file)) { unlink ($file); }
        }
        
        /**
         * Install Demo from Torbara
         */
        function installFromTorbara () {
            if($this->installFromTorbara_run()){
                $this->installFromTorbara_cleanTrash();
                echo '<div class="uk-alert uk-alert-success uk-display-block">Import completed successfully!</div>';
            }else{
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Import completed with error!</div>';
            }
        }
        
        /**
         *  Remove garbage after Install Demo from Torbara
         */
        function installFromTorbara_cleanTrash () {
            $demo_zip = ABSPATH . "/demo.zip";
            $demo_sql = ABSPATH . "/demo.sql";
            if (file_exists($demo_zip)) { unlink ($demo_zip); }
            if (file_exists($demo_sql)) { unlink ($demo_sql); }
        }
        
        /**
         * Install Demo from Torbara Process
         */
        function installFromTorbara_run () {
            global $wpdb, $wp_rewrite;

            // Demo URL
            $demo_url = htmlspecialchars($_POST["demo_url"]) . "demo.zip";

            // Download demo data
            if( !file_put_contents(ABSPATH . "/demo.zip", fopen($demo_url, 'r')) ){
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed Importing demo data. Failed download <b>'.$demo_url.'</b></div>';
                return FALSE;
            }

            // Unzip all files
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
            $unzipfile = unzip_file( get_home_path() . 'demo.zip', get_home_path());

            if(!$unzipfile) {
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed to unzipping demo.zip.</div>';
                return FALSE;
            }

            // Get demo SQL
            $demo_sql = get_home_path() . "demo.sql";

            //Error if no SQL to import
            if (!file_exists($demo_sql)){
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Error: <b>'.$demo_sql.'</b> not found.</div>';
                return FALSE;
            }

            // Read Demo sql
            $sql = file_get_contents($demo_sql);

            // Search next strings:
            // Old Table Prefix: {e4rfs3_}
            // Old Site URL: {http://w-gb.torbara.com}
            // Old User Login: {alexander.khmelnitskiy}
            preg_match_all('/\{(.+)\}/U', $sql, $matches);

            $old_table_prefix = $matches[1][0];
            $old_siteurl = $matches[1][1];
            $old_user = $matches[1][2];

            // Get Current user login and password
            $new_user = wp_get_current_user();

            // Replace TABLE_PREFIX and SITE_URL
            $sql = str_replace(array($old_table_prefix, $old_siteurl), array($wpdb->prefix, site_url()), $sql);
            
            $lines = explode("\n", $sql);
            
            $query = "";

            
            // Uncomment for debug
            // $wpdb->show_errors = true; // Debug
            // $wpdb->suppress_errors = false; // Debug
            
            // Run queryes
            foreach ($lines as $line) {
                $line = trim($line);
                if (strlen($line)==0) continue;
                if (substr($line,-1)==";") {
                    $query.=" ".$line;
                    $wpdb->query($query);
                    if ($wpdb->last_error) { // Show SQL error message ?>
                        <div class="uk-alert uk-alert-danger uk-display-block">
                            <strong>WordPress database error:</strong> <?php echo $wpdb->print_error(); ?><hr>
                            Error with SQL query: 
                            <pre><?php echo $wpdb->last_query; ?></pre>
                        </div><?php
                    }
                    $query = "";
                } else {
                    $query.=" ".$line;
                }
            }

            $wp_rewrite->flush_rules();
            wp_cache_flush();

            //Update admin user pass and login
            $wpdb->update( 
                    $wpdb->users,
                    array( 'user_login' => $new_user->user_login, 'user_pass' => $new_user->user_pass ), 
                    array( 'user_login' => $old_user));
            
            return TRUE;
        }
        
        /**
         * Process Ajax requests
         */
        function processAjaxRequests ($action) {
            if ($action == "tt_generate_demo") { //Generating Demo process
                $this->generateDemo();
            } elseif ($action == "tt_generate_demo_remove_file") { // Remove Demo file
                $this->generateDemo_FileRemove();
            } elseif ($action == "tt_install_torbara") { // Install Demo process
                $this->installFromTorbara();
            } else { // Action is wrong
                echo '<div class="uk-alert uk-alert-danger uk-display-block">Failed ajax request. Unknown action.</div>';
            }
        }
        
        /**
         * Check allow_url_fopen
         * 
         */
        public function check_Allow_url_fopen () {
                
            if (!ini_get('allow_url_fopen')) {
                ?>
                <div class="uk-alert uk-display-block uk-alert-danger">
                    The <strong>allow_url_fopen</strong> directive is disabled. To use <strong>Install from Torbara</strong>, you need to enable this option.<br><br>

                    You can try three things:<br><br>

                    1. Contact with your hosting support team and ask them to <strong>enable allow_url_fopen</strong>, they know what to do.<br><br>

                    2. Create an <em>.htaccess</em> file and keep it in root folder (sometimes it may need to place it one step back folder of the root) 
                    and paste this code there: <code>php_flag allow_url_fopen On</code><br><br>

                    3. Create a <em>php.ini</em> file (for update server php5.ini) 
                    and keep it in root folder (sometimes it may need to place it one step back folder of the root) and paste the following code there:
                    <code>allow_url_fopen = On;</code>

                </div><?php
                return FALSE;
            }

            return TRUE;
    
        }
        
        /**
         * Render List of avalible demos
         */
        public function renderDemosList ($demos) {
            
            echo '<ul class="uk-grid" id="ttdemo-install-torbara" data-uk-grid-margin="">';
            
            // List of avalible demos
            foreach ($demos as $demo) {
                $d_name = $demo->getAttribute("name");
                $d_url = $demo->getAttribute("url");
                //$d_creationDate = $demo->getAttribute("creationDate");
                //$d_author = $demo->getAttribute("author");
                //$d_size = $demo->getAttribute("size");
                //$d_akeebaBackup = $demo->getAttribute("akeebaBackup");
                $d_img = $d_url . "template_preview.png";
                $d_desc = $demo->nodeValue;

                if (@fopen($d_img, 'r')) { // If image exists ?>
                    <li class="uk-width-medium-1-3">
                        <a class="uk-thumbnail" style="text-decoration: none;" href="#" data-demo-url="<?php echo $d_url; ?>">
                            <img src="<?php echo $d_img; ?>" width="250" height="150" alt="<?php echo $d_name; ?>">
                            <span class="uk-thumbnail-caption uk-display-block" title="<?php echo $d_desc; ?>"><?php echo $d_name; ?></span>
                        </a>
                    </li><?php
                } else { // If image does not exist, skip current demo
                    continue;
                }
            }
            
            echo '</ul>';
        }
        
        //TODO: Check for big files
        /**
         * Convert bytes to MB|GB
         */
        public function formatSizeUnits($bytes) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }
            return $bytes;
        }
        
        /**
         * Check default table prefix "wp_"
         */
        function DBPrefixCheck() {
            global $wpdb;
            if ($wpdb->prefix == "wp_") {
                add_thickbox();
            ?><div class="uk-alert uk-display-block uk-alert-danger">
                You have not changed the database default prefix <strong>wp_</strong> at installation time. It need to be changed. 
                Use this <a href="<?php echo get_admin_url(); ?>plugin-install.php?tab=plugin-information&plugin=db-prefix-change&TB_iframe=true&width=772&height=895" class="thickbox open-plugin-details-modal">plugin</a>.
            </div><?php
            return FALSE;
            }
            return TRUE;
        }

        
        /**
         * Render Tabs header
         */
        public function renderTabs_head () {
            // Tabs header
            ?><ul class="uk-tab" data-uk-tab="{connect:'#tt-demo-tabs'}">
                <li><a style="cursor: pointer;">Install from Torbara</a></li>
                <li><a style="cursor: pointer;">Download Installer</a></li>
                <li><a style="cursor: pointer; display: none;">Generate Demo</a></li>
            </ul><?php
        }
        
        /**
         * Render Tab message: Install Demo from Torbara
         */
        public function renderTabs_tobara_msg () {
            ?><div class="uk-alert uk-display-block">
                <div>Importing demo data (post, pages, images, theme settings, ...) is the easiest way to setup your theme. It will
                    allow you to quickly edit everything instead of creating content from scratch. When you import the data following things will happen:</div>

                <ul class="uk-list-line">
                    <li>All existing posts, pages, categories, images, custom post types or any other data may be deleted or modified.</li>
                    <li>Some WordPress settings will be modified.</li>
                    <li>Posts, pages, some images, some widgets and menus will get imported.</li>
                    <li>Images will be downloaded from our server, these images are copyrighted and are for demo use only.</li>
                    <li>Please click import only once and wait, it can take a couple of minutes.</li>
                </ul>
            </div><?php
        }
        
        /**
         * Install Demo from Torbara: Download demos list
         */
        public function renderTabs_tobara_demos_list () {
            
            $dom = new DOMDocument;
    
            if (!@$dom->load($this->demo_xml_url)) {
                echo '<div class="uk-alert uk-display-block uk-alert-danger">Error. Could not load file: ' . $this->demo_xml_url . '</div>';
                return;
            }

            // Get demos for current theme version
            $xpath = new DOMXPath($dom);
            $demos = $xpath->query('//theme[@name="' . $this->theme_name . '"]/version[@vnumber="' . $this->theme_version . '"]/demo');

            if (($demos == NULL) || ($demos->length == 0)) {
                echo '<div class="uk-alert uk-display-block uk-alert-danger">Demos for <strong>' . $this->theme_name . ' ' . $this->theme_version . '</strong> not found.</div>';
                return FALSE;
            }
            
            return $demos;
        }
        
        /**
         * Generate Tabs for interface
         */
        function renderTabs () {
            
            // Tabs header
            $this->renderTabs_head(); ?>
            
            <ul id="tt-demo-tabs" class="uk-switcher uk-margin">
                <li>
                    <h3>Install Demo from Torbara</h3><?php
                    // Initial checks
                    
                    // TODO: Add condition checks
                    // Check allow_url_fopen
                    $this->check_Allow_url_fopen();
                    
                    $this->renderTabs_tobara_msg(); // Message
    
                    $demos = $this->renderTabs_tobara_demos_list();
                    
                    if($demos){
                        $this->renderDemosList($demos);
                    }
                            
                    ?>

                    <div class="tt-torbara-mesage-area" style="margin-top: 20px;"></div>
                </li>
                
                <li>
                    <h3>Download Installation Package</h3>
                    <div class="tt-demo-download-list"></div>
                    <div class="tt-upload-mesage-area" style="margin-top: 20px;"></div>
                </li>
                
                
                
                <li>
                    <h3>Generate Demo Package</h3><?php


                    // TODO: WTF?
                    if (!$this->DBPrefixCheck()) {
                        return;
                    }

                    // If zip extension missed
                    if (!extension_loaded('zip') || !class_exists('ZipArchive')) {?>
                        <div class="uk-alert uk-display-block uk-alert-danger">
                            In order to work with ZIP files the PHP ZipArchive module must be installed. Please contact with your hosting provider.        
                        </div><?php
                        return;
                    }
                    ?>

                    <div class="uk-alert uk-display-block uk-alert-warning">
                        This operation may not work on a shared hosting. It is demanding to resources.
                    </div>

                    <button class="uk-button uk-button-primary" id="tt-generate-demo">Generate Demo</button>

                    <div class="tt-generator-mesage-area" style="margin-top: 20px;"></div>


                    <?php
                    // Is there ready demo Packages?
                    $d_path = ABSPATH . 'tt-demo-packages';
                    if (!is_dir($d_path)) { return; }

                    $files = glob($d_path . "/*.zip"); // Select only zip packadges
                    if (!count($files) > 0) { return; }
                    ?>

                    <h3>Available for download</h3>
                    <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed tt-demo-list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Timestamp</th>
                                <th>Size</th>
                                <th>Path</th>
                                <th class="uk-text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file) : ?>
                                <tr>
                                    <td><?php echo basename($file); ?></td>
                                    <td><?php echo date("F d Y H:i:s", filemtime($file)); ?></td>
                                    <td><?php echo $this->formatSizeUnits(filesize($file)); ?></td>
                                    <td><?php echo $file; ?></td>
                                    <td class="uk-text-center">
                                        <a href="<?php echo get_home_url() . "/tt-demo-packages/" . basename($file); ?>" title="Download Package" target="_blank"><i class="uk-icon-download"></i></a>&nbsp;&nbsp;&nbsp;
                                        <a href="#" class="tt-remove-demo" data-file-path="<?php echo $file; ?>" title="Remove Package" target="_blank" style="color: red;"><i class="uk-icon-trash"></i></a>
                                    </td>    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </li>

            </ul><?php
    
        }
        
        /**
         * Generate all javascripts
         */
        public function generateJS () {
            ?>
            <script type="text/javascript">
            //<![CDATA[

                (function ($) {
                    "use strict";
                    var progressBarWidth = 0;

                    // Click on install demo
                    $(document).on("click", "#ttdemo-install-torbara li a", function (e) {
                        e.preventDefault();
                        if (confirm('Are you sure want to import dummy content?') == false) {
                            return false;
                        }
                        var $demo_url = $(this).attr("data-demo-url");
                        var $mesageArea = $(this).parent().parent().next(); // Mesage Area

                        progressBarWidth = 0;
                        jQuery($mesageArea).html('<div class="uk-progress uk-progress-small uk-progress-success uk-progress-striped uk-active"><div class="uk-progress-bar" id="tt-demo-progress" style=""></div></div><span class="uk-float-left uk-margin-top-remove"></span> <span>Data is being imported, please be patient, while the awesomeness is being created. (Typically, about 1-2 minutes. Rarely 5 minutes.)</span>');
                        var ProgressInterval = setInterval(ProgressBarStep, 400);

                        //Post request
                        var data = {'action': 'tt_install_torbara', 'demo_url': $demo_url};
                        var post_url = "<?php echo WP_PLUGIN_URL; ?>/tt-warp7/warp/config/layouts/fields/ttdemo.php";
                        $.post(post_url, data, function (response) {
                            $($mesageArea).html(response);
                        }).fail(function () { // Error
                            $($mesageArea).html('<div class="uk-alert uk-display-block uk-alert-danger">Error while installing demo data. Something went wrong. Please try again later. Or contact our support team: support@torbara.com</div>');
                        }).always(function () {
                            clearInterval(ProgressInterval);//Stop ProgressBar
                            jQuery("#tt-demo-progress").width('100%');
                            setTimeout(function () {
                                $($mesageArea).append('<div class="uk-alert uk-display-block">The import process is complete.</div>');
                            }, 1500);
                        });
                    });

                    // Click on Generate Demo button
                    $("#tt-generate-demo").click(function (e) {
                        e.preventDefault();

                        if (confirm('Are you sure want to generate Demo Package?') == false) {
                            return false;
                        }

                        var $mesageArea = $(this).next(); // Mesage Area
                        progressBarWidth = 0;
                        jQuery($mesageArea).html('<div class="uk-progress uk-progress-small uk-progress-success uk-progress-striped uk-active"><div class="uk-progress-bar" id="tt-demo-progress" style=""></div></div><span class="uk-float-left uk-margin-top-remove"></span> <span>Demo package is being generated, please be patient, while the awesomeness is being created. (Typically, about 1-2 minutes. Rarely 5 minutes.)</span>');
                        var ProgressInterval = setInterval(ProgressBarStep, 300);

                        var btn = $(this);
                        btn.prop("disabled", true);// Disable button

                        //Post request
                        var data = {'action': 'tt_generate_demo'};
                        var post_url = "<?php echo WP_PLUGIN_URL; ?>/tt-warp7/warp/config/layouts/fields/ttdemo.php";
                        $.post(post_url, data, function (response) {
                            $($mesageArea).html(response);
                            setTimeout(function () {
                                location.reload(); // TODO: Make ajax update
                            }, 1500);
                        }).fail(function () { // Error
                            $($mesageArea).html('<div class="uk-alert uk-display-block uk-alert-danger">Error while generating the demo data. Something went wrong. Please try again later. Or contact our support team: support@torbara.com</div>');
                        }).always(function () {
                            clearInterval(ProgressInterval);//Stop ProgressBar
                            jQuery("#tt-demo-progress").width('100%');
                            setTimeout(function () {
                                $($mesageArea).append('<div class="uk-alert uk-display-block">The demo generation process is complete.</div>');
                                btn.prop("disabled", false);// Enable button
                            }, 1500);
                        });
                    });

                    // Click on delete Demo button from Generate Demo tab
                    jQuery(".tt-demo-list .tt-remove-demo").click(function (e) {
                        e.preventDefault();
                        var filePath = jQuery(this).data("file-path");

                        if (confirm('Are you sure want to delete this file: ' + filePath + '?') == false) {
                            return false;
                        }


                        var data = {'action': 'tt_generate_demo_remove_file', 'file': filePath};
                        var post_url = "<?php echo WP_PLUGIN_URL; ?>/tt-warp7/warp/config/layouts/fields/ttdemo.php";
                        $.post(post_url, data, function (response) {
                            location.reload();
                        }).fail(function () { // Error
                            alert("Error");
                        });
                    });

                    // Step for progressBar ~ 30 sec
                    function ProgressBarStep() {
                        progressBarWidth = progressBarWidth + 1;
                        jQuery("#tt-demo-progress").width(progressBarWidth + '%');
                        if (progressBarWidth >= 100) {
                            progressBarWidth = 0;
                        }
                    }

                    // Downloading content for "Download Installation Package" tab
                    // We use crossdomain ajax in order not to depend from allow_url_fopen
                    jQuery.support.cors = true;
                    jQuery.ajax({
                        url: "//update.torbara.com/wordpress/tab_download_installer.php",
                        data: {
                            "url": window.location.href,
                            "theme_name": "<?php echo $this->theme_name; ?>",
                            "theme_version": "<?php echo $this->theme_version; ?>"
                        },
                        type: "GET",
                        timeout: 10000,
                        dataType: "text",
                        success: function (data) {
                            jQuery(".tt-demo-download-list").html(data);
                        },
                        error: function (jqXHR, textStatus, ex) {
                            alert(textStatus + "," + ex + "," + jqXHR.responseText);
                        }
                    });

                })(jQuery);
            //]]>
            </script>
            <?php
        }
        
        /**
         * 
         * Generate horizontal rule
         * 
         * @return string
         */
        public function articleDivider () {
            return '<hr class="uk-article-divider">';
        }
        
        
    }
}

// Create instance
$TTDemo = new TTDemo();
if ( !is_super_admin() ) { return ; } // Only for super admins

// Main logic
$action = filter_input(INPUT_POST, 'action');
if(isset($action)) { // Firstly process Ajax requests
    $TTDemo->processAjaxRequests($action);
} else { // Generate tabs if this is not Ajax Request
    $TTDemo->renderTabs(); // Generate UI tabs
    echo $TTDemo->articleDivider(); // Add line
    $TTDemo->generateJS(); // Generate all JS    
}