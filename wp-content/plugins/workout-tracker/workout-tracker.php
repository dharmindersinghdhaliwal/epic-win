<?php
/*
Plugin Name: Epic Win PT Workout Tracker
Plugin URI: #
Description: Epic Win PT Workout Tracker.
Version: 0.0.1
Tested up to: 4.7.5
Author: Manoj Singh
Author URI: http://www.codeflox.com
Text Domain: cf
Domain Path: lang/
Network:true
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if(!defined('ABSPATH')){
	exit;
}
define("WT_PLUGIN_DIR",plugin_dir_url(__FILE__));
require_once('includes/init.php');
require_once('includes/tab.php');
require_once('includes/tracker-data.php');
require_once('includes/workout-notes.php');