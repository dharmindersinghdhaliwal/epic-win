<?php
define( 'WPUEP_VERSION', '2.4.2' );
class WPUltimateExercise {
    private static $instance;
    private static $instantiated_by_premium;
    private static $addons = array();
	/**
     * Return instance of self
     */	 	 
    public static function get( $instantiated_by_premium = false ){		
        // Instantiate self only once		
        if( is_null( self::$instance ) ){
            self::$instantiated_by_premium = $instantiated_by_premium;
            self::$instance = new self;
            self::$instance->init();
        }
        return self::$instance;
    }
	
    /**
     * Should we load the entire plugin or not?
     */
    public static function minimal_mode(){
        // No minimal mode in backend
        if( is_admin() ) return false;
        $minimal_mode = apply_filters( 'wpuep_minimal_mode', false, $_SERVER['REQUEST_URI'] );
        return $minimal_mode;
    }
    /**
     * Returns true if we are using the Premium version
     */
    public static function is_premium_active(){
        return self::$instantiated_by_premium;
    }
    /**
     * Add loaded addon to array of loaded addons
     */
    public static function loaded_addon( $addon, $instance ){
        if( !array_key_exists( $addon, self::$addons ) ){
            self::$addons[$addon] = $instance;
        }
    }
    /**
     * Returns true if the specified addon has been loaded
     */
    public static function is_addon_active( $addon ){
        return array_key_exists( $addon, self::$addons );
    }
    public static function addon( $addon ){
        if( isset( self::$addons[$addon] ) ){
            return self::$addons[$addon];
        }
        return false;
    }
    /**
     * Access a VafPress option with optional default value
     */
    public static function option( $name, $default = null ){			
        $option = vp_option( 'wpuep_option.' . $name );
        if( is_null( $default ) ){
            $default = self::get()->helper( 'vafpress_menu' )->defaults( $name );
        }
        // Chicory specific check
        if( 'partners_integrations_chicory_enable' == $name && '1' == $option ){
            $option = vp_option( 'wpuep_option.partners_integrations_chicory_terms' );
            if( count( $option ) == 0 ){
                $option = '';
            } else {
                $option = '1';
            }
        }
        return is_null( $option ) ? $default : $option;
    }
    public $pluginName = 'wp-ultimate-exercise';
    public $coreDir;
    public $corePath;
    public $coreUrl;
    public $pluginFile;
    protected $helper_dirs = array();
    protected $helpers = array();
    /**
     * Initialize
     */
    public function init(){
        // Load external libraries
        require_once( 'vendor/vafpress/bootstrap.php' );
        require_once( 'vendor/taxonomy-metadata/Taxonomy_MetaData.php' );
        // Update plugin version
        update_option( $this->pluginName . '_version', WPUEP_VERSION );
        // Set core directory, URL and main plugin file
        $this->corePath = str_replace( '/wp-ultimate-exercise.php', '', plugin_basename( __FILE__ ) );
        $this->coreDir = apply_filters( 'wpuep_core_dir', WP_PLUGIN_DIR . '/' . $this->corePath );
        $this->coreUrl = apply_filters( 'wpuep_core_url', plugins_url() . '/' . $this->corePath );
        $this->pluginFile = apply_filters( 'wpuep_plugin_file', __FILE__ );

        // Load textdomain
        if( !self::is_premium_active() ){
            $domain = 'wp-ultimate-exercise';
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
            load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
            load_plugin_textdomain( $domain, false, $this->corePath . '/lang/' );
        }

        // Add core helper directory
        $this->add_helper_directory( $this->coreDir . '/helpers' );
        // Migrate first if needed
        $this->helper( 'migration' );
        // Load required helpers
        $this->helper( 'eajax' );
        $this->helper( 'activate' );
        //$this->helper( 'admin_tour' );
        $this->helper( 'cache' );
        $this->helper( 'compatibility' );
        $this->helper( 'css' );
        $this->helper( 'faq' );
        $this->helper( 'notices' );
        $this->helper( 'permalinks_flusher' );
        $this->helper( 'plugin_action_link' );
        $this->helper( 'query_posts' );
        $this->helper( 'exercise_content' );
        $this->helper( 'exercise_demo' );
        $this->helper( 'exercise_meta_box' );
        $this->helper( 'exercise_post_type' );
        $this->helper( 'exercise_save' );
        $this->helper( 'search' );
        $this->helper( 'support_tab' );
        $this->helper( 'taxonomies' );
        $this->helper( 'thumbnails' );
        $this->helper( 'vafpress_menu' );
        $this->helper( 'vafpress_shortcode' );

        $this->helper( 'shortcodes/index_shortcode' );
        $this->helper( 'shortcodes/exercise_shortcode' );
        // Include required helpers but don't instantiate
        $this->include_helper( 'addons/addon' );
        $this->include_helper( 'addons/premium_addon' );
        $this->include_helper( 'models/exercise' );
        if( !WPUltimateExercise::minimal_mode() ){
            // Load core addons
            $this->helper( 'eaddon_loader' )->load_addons( $this->coreDir . '/addons' );
            // Load default assets
            $this->helper( 'eassets' );
        }
    }
    /**
     * Access a helper. Will instantiate if helper hasn't been loaded before.
     */
    public function helper( $helper ){
        // Lazy instantiate helper
        if( !isset( $this->helpers[$helper] ) ){
            $this->include_helper( $helper );
            // Get class name from filename
            $class_name = 'WPUEP';			
            $dirs = explode( '/', $helper );
            $file = end( $dirs );
            $name_parts = explode( '_', $file );			
            foreach( $name_parts as $name_part ){
                $class_name .= '_' . ucfirst( $name_part );
            }			
            // Instantiate class if exists
            if( class_exists( $class_name ) ){							
                $this->helpers[$helper] = new $class_name();
            }
        }		
        // Return helper instance
        return $this->helpers[$helper];
    }
    /**
     * Include a helper. Looks through all helper directories that have been added.
     */
    public function include_helper( $helper ){
        foreach( $this->helper_dirs as $dir ){
            $file = $dir . '/'.$helper.'.php';
            if( file_exists( $file ) ){
			    require_once( $file );
            }
        }
    }
    /**
     * Add a directory to look for helpers.
     */
    public function add_helper_directory( $dir ){
        if( is_dir( $dir ) ){
            $this->helper_dirs[] = $dir;
        }
    }
    /*
     * Quick access functions
     */
    public function tags(){
        return $this->helper( 'taxonomies' )->get();
    }	
    public function query(){		
        return $this->helper( 'query_exercises' );
    }	
    public function template( $type, $template ){		
        return $this->addon( 'custom-templates' )->get_template( $type, $template );
    }
}
// Premium version is responsible for instantiating if available
if( !class_exists( 'WPUltimateExercisePremium' ) ){
    WPUltimateExercise::get();
}