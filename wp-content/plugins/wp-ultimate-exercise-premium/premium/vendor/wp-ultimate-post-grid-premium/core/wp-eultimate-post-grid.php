<?php
define( 'WPUPG_EVERSION', '1.6' );
define( 'WPUPG_EPOST_TYPE', 'wpupg_egrid' );

class WPUltimateEPostEgrid {

    private static $instance;
    private static $instantiated_by_premium;
    private static $addons = array();

    /**
     * Return instance of self
     */
    public static function get( $instantiated_by_premium = false )
    {
        // Instantiate self only once
        if( is_null( self::$instance ) ) {
            self::$instantiated_by_premium = $instantiated_by_premium;
            self::$instance = new self;
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Returns true if we are using the Premium version
     */
    public static function is_premium_active()
    {
        return self::$instantiated_by_premium;
    }

    /**
     * Add loaded addon to array of loaded addons
     */
	 
    public static function loaded_addon( $addon, $instance )
    {
        if( !array_key_exists( $addon, self::$addons ) ) {
            self::$addons[$addon] = $instance;
        }
    }
	
    /**
     * Returns true if the specified addon has been loaded
     */
	 
    public static function is_addon_active( $addon )
    {
        return array_key_exists( $addon, self::$addons );
    }

    public static function addon( $addon )
    {
        if( isset( self::$addons[$addon] ) ) {
            return self::$addons[$addon];
        }

        return false;
    }

    /**
     * Access a VafPress option with optional default value
     */
    public static function option( $name, $default = null )
    {
        $option = vp_option( 'wpupg_option.' . $name );

        return is_null( $option ) ? $default : $option;
    }


    public $pluginName = 'wp-eultimate-post-grid';
    public $coreDir;
    public $corePath;
    public $coreUrl;
    public $pluginFile;

    protected $helper_dirs = array();
    protected $helpers = array();

    /**
     * Initialize
     */
	 
    public function init()
    {		
        // Load external libraries
        if( !class_exists( 'VP_AutoLoader' ) ) {
            require_once( 'vendor/vafpress/bootstrap.php' );
        }
		
        // Update plugin version		
        update_option( $this->pluginName . '_version', WPUPG_EVERSION );
		
        // Set core directory, URL and main plugin file				
        $this->corePath 	=	str_replace( '/wp-eultimate-post-grid.php', '', plugin_basename( __FILE__ ) );
        $this->coreDir 		=	apply_filters( 'wpupg_core_dir', WP_PLUGIN_DIR . '/' . $this->corePath );
        $this->coreUrl 		=	apply_filters( 'wpupg_core_url', plugins_url() . '/' . $this->corePath );
        $this->pluginFile	=	apply_filters( 'wpupg_plugin_file', __FILE__ );
		
        // Load textdomain
        if( !self::is_premium_active() ) {
            $domain = 'wp-eultimate-post-grid';
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

            load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
            load_plugin_textdomain( $domain, false, $this->corePath . '/lang/' );
        }

        // Add core helper directory
        $this->add_helper_directory( $this->coreDir . '/helpers' );

        // Migrate first if needed
        $this->helper( 'emigration' );

        // Load required helpers
        $this->helper( 'eactivate' );
        $this->helper( 'eajax' );
        $this->helper( 'econtent' );
        $this->helper( 'ecss' );
        $this->helper( 'efaq' );
        $this->helper( 'egrid_cache' );
        $this->helper( 'egrid_save' );
        $this->helper( 'emeta_box' );
        $this->helper( 'enotices' );
        $this->helper( 'epagination' );
        $this->helper( 'eplugin_action_link' );
        $this->helper( 'epost_type' );
        $this->helper( 'esupport_tab' );
        $this->helper( 'evafpress_menu' );
        $this->helper( 'evafpress_shortcode' );
        $this->helper( 'shortcodes/efilter_shortcode' );
        $this->helper( 'shortcodes/egrid_shortcode' );

        // Include required helpers but don't instantiate
        $this->include_helper( 'addons/eaddon' );
        $this->include_helper( 'addons/epremium_addon' );
        $this->include_helper( 'models/egrid' );

        // Load core addons
        $this->helper( 'eaddon_loader' )->load_addons( $this->coreDir . '/addons' );

        // Load default assets
        $this->helper( 'eassets' );
    }

    /**
     * Access a helper. Will instantiate if helper hasn't been loaded before.
     */
    public function helper( $helper )
    {
        // Lazy instantiate helper
        if( !isset( $this->helpers[$helper] ) ) {
            $this->include_helper( $helper );

            // Get class name from filename
            $class_name = 'WPUPG';

            $dirs = explode( '/', $helper );
            $file = end( $dirs );
            $name_parts = explode( '_', $file );
            foreach( $name_parts as $name_part ) {
                $class_name .= '_' . ucfirst( $name_part );
            }
			
            // Instantiate class if exists
            if( class_exists( $class_name ) ) {
                $this->helpers[$helper] = new $class_name();
            }
        }

        // Return helper instance
        return $this->helpers[$helper];
    }

    /**
     * Include a helper. Looks through all helper directories that have been added.
     */
    public function include_helper( $helper )
    {
        foreach( $this->helper_dirs as $dir )
        {
			$file = $dir . '/'.$helper.'.php';			
		
            if( file_exists( $file ) ) {
                require_once( $file );
            }
        }
    }

    /**
     * Add a directory to look for helpers.
     */
    public function add_helper_directory( $dir )
    {
        if( is_dir( $dir ) ) {
            $this->helper_dirs[] = $dir;
        }
    }

    /*
     * Quick access functions
     */

    public function template( $template )
    {
        return $this->addon( 'custom-templates' )->get_template( $template );
    }
}

// Premium version is responsible for instantiating if available
if( !class_exists( 'WPUltimateEPostEgridPremium' ) ) {
    WPUltimateEPostEgrid::get();
}