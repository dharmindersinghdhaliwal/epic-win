<?php
/*
Plugin Name: Client Progress Images	- CodeFlox
Plugin URI: #
Description: To show client progress image
Version: 0.0.2
Tested up to: 4.7.2
Author:CodeFlox
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
//add_action('plugins_loaded',array('CF_Cpi','init'));
$cf_cpi=new CF_Cpi();
class CF_Cpi{
	/**
     * The ID of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string    $name    The ID of this plugin.
     */
    private $name; 
    /**
     * The current version of the plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string    $version    The version of the plugin
     */
    private $version;
    /**
     * Initializes the plugin by defining the properties.
     *
     * @since 0.0.1
     */
	function init(){
        $class = __CLASS__;
        new $class;
    }
	function __construct(){
		 $this->name = 'cf-cpi';
       	 $this->version = '0.0.2';
		//add_action('plugins_loaded', array($this,'wan_load_textdomain'));
	}
	public static function wan_load_textdomain() {
		load_plugin_textdomain( 'cf', false,dirname(plugin_basename(__FILE__)).'/lang/' );
	}
}
include(plugin_dir_path(__FILE__).'cf-api.php');
include(plugin_dir_path(__FILE__).'admin/cpi-post-type.php');
include(plugin_dir_path(__FILE__).'includes/add-cpi.php');
include(plugin_dir_path(__FILE__).'includes/client-progress-output.php');