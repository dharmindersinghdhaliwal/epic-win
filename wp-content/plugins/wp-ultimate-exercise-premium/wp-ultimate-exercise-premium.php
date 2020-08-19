<?php
/*
Plugin Name: WP Ultimate Exercise Premium
Plugin URI: http://www.wpultimateexercise.com
Description: Everything a Food Blog needs. Beautiful SEO friendly exercises, print versions, visitor interaction, ...
Version: 2.4.2
Author: Bootstrapped Ventures
Author URI: http://bootstrapped.ventures
License: GPLv2
*/

define( 'WPUEP_PREMIUM_VERSION', '2.4.2' );

class WPUltimateExercisePremium {

    private static $instance;

    /**
     * Return instance of self
     */
    public static function get()
    {
        // Instantiate self only once
        if( is_null( self::$instance ) ) {
            self::$instance = new self;
            self::$instance->init();
        }

        return self::$instance;
    }

    public $premiumName = 'wp-ultimate-exercise-premium';
    public $premiumDir;
    public $premiumPath;
    public $premiumUrl;

    private $wpuep;

    /**
     * Our only task is to correctly set up WP Ultimate Exercise and load the premium helpers and addons
     */
    public function init()
    {
		$this->premiumPath = str_replace( '/wp-ultimate-exercise-premium.php', '', plugin_basename( __FILE__ ) );
        $this->premiumDir = apply_filters( 'wpuep_premium_dir', WP_PLUGIN_DIR . '/' . $this->premiumPath . '/premium' );
        $this->premiumUrl = apply_filters( 'wpuep_premium_url', plugins_url() . '/' . $this->premiumPath . '/premium' );

        add_filter( 'wpuep_core_dir', array( $this, 'filter_wpuep_core_dir' ) );
        add_filter( 'wpuep_core_url', array( $this, 'filter_wpuep_core_url' ) );
        add_filter( 'wpuep_plugin_file', array( $this, 'filter_wpuep_plugin_file' ) );

        // Include and instantiate WP Ultimate Exercise
        require_once( WP_PLUGIN_DIR . '/' . $this->premiumPath . '/core/wp-ultimate-exercise.php' );
        $this->wpuep = WPUltimateExercise::get( true );

        if( !WPUltimateExercise::minimal_mode() ) {			
            // Load WP Ultimate Post Egrid Premium
            require_once( WP_PLUGIN_DIR . '/' . $this->premiumPath . '/premium/vendor/wp-ultimate-post-grid-premium/wp-ultimate-post-grid-premium.php' );

            // Load textdomain
            $domain = 'wp-ultimate-exercise';
            $locale = apply_filters('plugin_locale', get_locale(), $domain);

            load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
            load_plugin_textdomain($domain, false, $this->premiumPath . '/core/lang/');

            // Add Premium helper directory
            $this->wpuep->add_helper_directory($this->premiumDir . '/helpers');

            // Load Premium helpers
            $this->wpuep->helper('ingredient_metadata');
            $this->wpuep->helper('license');
            $this->wpuep->helper('exercise_cloner');
            $this->wpuep->helper('exercise_columns');

            $this->wpuep->helper('shortcodes/extended_index_shortcode');

            $this->wpuep->helper('widgets/exercise_list_widget');

            // Load Premium addons			
            $this->wpuep->helper('eaddon_loader')->load_addons($this->premiumDir . '/addons');

            // Add plugin action links
            add_filter('plugin_action_links_wp-ultimate-exercise-premium/wp-ultimate-exercise-premium.php', array($this->wpuep->helper('plugin_action_link'), 'action_links'));
        }
    }

    public function filter_wpuep_core_dir()
    {
        return WP_PLUGIN_DIR . '/' . $this->premiumPath . '/core';
    }

    public function filter_wpuep_core_url()
    {
        return plugins_url() . '/' . $this->premiumPath . '/core';
    }

    public function filter_wpuep_plugin_file()
    {
        return __FILE__;
    }
}

// Check if WP Ultimate Exercise isn't activated
if( class_exists( 'WPUltimateExercise' ) ) {
    wp_die( __( "You need to deactivate the free WP Ultimate Exercise plugin before activating the Premium version. WP Ultimate Exercise Premium is a stand-alone plugin since version 2. You won't lose any settings or exercises when deactivating.", 'wp-ultimate-exercise' ), 'WP Ultimate Exercise Premium', array( 'back_link' => true ) );
} else {
    // Instantiate WP Ultimate Exercise Premium
    WPUltimateExercisePremium::get();
}