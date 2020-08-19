<?php

class WPUEP_Eassets {

    private $assets = array();

    public function __construct()
    {
        add_action( 'init', array( $this, 'add_defaults' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    public function add_defaults()
    {
        // Load core assets TODO Refactor this.
        $this->add(
            array(
                'file' => '/css/public.css',
                'public' => true,
            ),
            array(
                'file' => '/css/admin.css',
                'admin' => true,
            ),
            array(
                'name' => 'fraction',
                'file' => '/vendor/fraction-js/index.js',
                'public' => true,
                'admin' => true,
            ),
            array(
                'file' => '/js/adjustable_servings.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                    'fraction',
                ),
            ),
            array(
                'file' => '/vendor/jquery.tools.min.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'file' => '/js/print_button.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpuep_print',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'wpuep_print' ),
                    'custom_print_css' => WPUltimateExercise::option( 'custom_code_print_css', '' ),
                    'coreUrl' => WPUltimateExercise::get()->coreUrl,
                    'premiumUrl' => WPUltimateExercise::is_premium_active() ? WPUltimateExercisePremium::get()->premiumUrl : false,
                    'title' => WPUltimateExercise::option( 'print_template_title_text', get_bloginfo('name') ),
                ),
            ),
            array(
                'file' => '/js/tooltips.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'file' => '/js/responsive.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpuep_responsive_data',
                    'breakpoint' => WPUltimateExercise::option( 'exercise_template_responsive_breakpoint', '550' )
                ),
            ),
            array(
                'name' => 'sharrre',
                'setting' => array( 'exercise_sharing_enable', '1' ),
                'file' => '/vendor/sharrre/jquery.sharrre.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'setting' => array( 'exercise_sharing_enable', '1' ),
                'file' => '/js/sharing_buttons.js',
                'public' => true,
                'deps' => array(
                    'jquery',
                    'sharrre',
                ),
                'data' => array(
                    'name' => 'wpuep_sharing_buttons',
                    'facebook_lang' => WPUltimateExercise::option( 'exercise_sharing_language_facebook', 'en_US' ),
                    'twitter_lang' => WPUltimateExercise::option( 'exercise_sharing_language_twitter', 'en' ),
                    'google_lang' => WPUltimateExercise::option( 'exercise_sharing_language_google', 'en-US' ),
                ),
            ),
            array(
                'file' => '/js/partners.js',
                'public' => true,
            ),
            array(
                'setting' => array( 'exercise_template_font_awesome', '1' ),
                'file' => WPUltimateExercise::get()->coreUrl . '/vendor/font-awesome/css/font-awesome.min.css',
                'direct' => true,
                'public' => true,
            ),
            array(
                'name' => 'chicory',
                'setting' => array( 'partners_integrations_chicory_enable', '1' ),
                'file' => 'http://chicoryapp.com/widget_v2',
                'type' => 'js',
                'direct' => true,
                'public' => true,
            ),
            array(
                'name' => 'yummly',
                'setting_inverse' => array( 'partners_integrations_yummly_enable', '' ),
              'file' => 'https://www.yummly.com/js/widget.js?wordpress',
			   ## 'file' => 'http://members.epicwinpt.com.au/wp-content/plugins/wp-ultimate-exercise-premium/core/js/widget.js',
                'type' => 'js',
                'direct' => true,
                'public' => true,
            )
        );
    }

    public function add()
    {
        $assets = func_get_args();

        foreach( $assets as $asset )
        {
            if( isset( $asset['file'] ) ) {

                if( !isset( $asset['type'] ) ) {
                    $asset['type'] = pathinfo( $asset['file'], PATHINFO_EXTENSION );
                }

                if( !isset( $asset['priority'] ) ) {
                    $asset['priority'] = 10;
                }

                // Set a URL and DIR variable
                if( isset( $asset['direct'] ) && $asset['direct'] ) {
                    $asset['url'] = $asset['file'];
                    $asset['dir'] = $asset['file'];
                } else {
                    $base_url = WPUltimateExercise::get()->coreUrl;
                    $base_dir = WPUltimateExercise::get()->coreDir;

                    if( isset( $asset['premium'] ) && $asset['premium'] ) {
                        $base_url = WPUltimateExercisePremium::get()->premiumUrl;
                        $base_dir = WPUltimateExercisePremium::get()->premiumDir;
                    }

                    $asset['url'] = $base_url . $asset['file'];
                    $asset['dir'] = $base_dir . $asset['file'];
                }

                $this->assets[] = $asset;
            }
        }
    }

    public function enqueue( $hook = '' )
    {
        $assets = $this->assets;

        // Check if we're generating assets on the fly
        $dir = WPUltimateExercise::option( 'assets_generate_minified_dir', '' );
        if( WPUltimateExercise::option( 'assets_generate_minified', '0' ) == '1' && $dir != '' && is_writable( $dir ) ) {
            $this->minify( $assets, $dir );
        }

        // Check which assets to enqueue
        $css_to_enqueue = array();
        $js_to_enqueue = array();
        $js_to_enqueue_data_only = array();
        $js_names = array();
        $js_dependencies = array();
        $use_minify = WPUltimateExercise::option( 'assets_use_minified', '1' ) == '1' && !is_admin() ? true : false;

        foreach( $assets as $asset )
        {
            if( $use_minify && ( !isset( $asset['direct'] ) || !$asset['direct'] ) ) {
                // These assets are minified so we don't need them again, except for public JS files with data
                if( strtolower( $asset['type'] ) == 'js' && isset( $asset['public'] ) && $asset['public'] ) {
                    if( isset( $asset['data'] ) && isset( $asset['data']['name'] ) ) $js_to_enqueue_data_only[] = $asset;
                    if( isset( $asset['name'] ) ) $js_names[] = $asset['name'];
                    if( isset( $asset['deps'] ) ) $js_dependencies = array_merge( $js_dependencies, $asset['deps'] );
                }

            } else {
                // Check if asset is intended for admin or public side
                if( !is_admin() && ( !isset( $asset['public'] ) || !$asset['public'] ) ) continue;
                if( is_admin() && ( !isset( $asset['admin'] ) || !$asset['admin'] ) ) continue;

                // Check if we're on a certain page
                if( isset( $asset['page'] ) ) {
                    switch ( strtolower( $asset['page'] ) ) {

                        case 'exercise_posts':
                            if( $hook != 'edit.php' || ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'exercise' ) ) continue 2; // Switch is consider a loop statement for continue
                            break;

                        case 'exercise_form':
                            if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'exercise' ) ) continue 2; // Switch is consider a loop statement for continue
                            break;

                        case 'exercise_settings':
                            if( $hook != 'exercise_page_wpuep_admin' ) continue 2;
                            break;

                        case 'exercise_page_wpuep_import_text':
                            if( substr( $hook, 0, 25 ) !== 'exercise_page_wpuep_import_' ) continue 2;
                            break;

                        default:
                            if( $hook != strtolower( $asset['page'] ) ) continue 2;
                            break;
                    }
                }

                // Check for shortcode
                if( isset( $asset['shortcode'] ) ) {
                    if( !$this->check_for_shortcode( $asset['shortcode'] ) ) continue;
                }

                // Check if setting equals value
                if( isset( $asset['setting'] ) && count( $asset['setting'] ) == 2 ) {
                    if( WPUltimateExercise::option( $asset['setting'][0], $asset['setting'][1] ) != $asset['setting'][1] ) continue;
                }

                // Check if setting does not equal value
                if( isset( $asset['setting_inverse'] ) && count( $asset['setting_inverse'] ) == 2 ) {
                    if( WPUltimateExercise::option( $asset['setting_inverse'][0], $asset['setting_inverse'][1] ) == $asset['setting_inverse'][1] ) continue;
                }

                // If we've made it here, this asset should be included
                switch( strtolower( $asset['type'] ) ) {

                    case 'css':
                        $css_to_enqueue[] = $asset;
                        break;
                    case 'js':
                        $js_to_enqueue[] = $asset;
                        break;
                }
            }
        }

        // We've got the assets we need, enqueue them
        if( count( $css_to_enqueue ) > 0 || $use_minify )   $this->enqueue_css( $css_to_enqueue, $use_minify );
        if( count( $js_to_enqueue ) > 0 || $use_minify )    $this->enqueue_js( $js_to_enqueue, $use_minify, $js_to_enqueue_data_only, $js_names, $js_dependencies );
    }

    private function enqueue_css( $assets, $use_minify )
    {
        if( !$use_minify ) {
            // Include Base CSS
            if( WPUltimateExercise::option( 'exercise_template_base_css', '1' ) == '1' ) {
                $base_layout = WPUltimateExercise::option( 'exercise_template_force_style', '1' ) == '1' ? 'layout_base_forced.css' : 'layout_base.css';

                array_unshift( $assets, array( 'url' => WPUltimateExercise::get()->coreUrl . '/css/' . $base_layout ) );
            }
        } else {
            // Add correct minified file
            $minified_css = 'wpuep-public-without-base';
            if( WPUltimateExercise::option( 'exercise_template_base_css', '1' ) == '1' ) {
                $minified_css = WPUltimateExercise::option( 'exercise_template_force_style', '1' ) == '1' ? 'wpuep-public-forced' : 'wpuep-public';
            }

            $minified_url = WPUltimateExercise::get()->coreUrl . '/assets/' . $minified_css . '.css';

            wp_enqueue_style( 'wpuep_style_minified', $minified_url, false, WPUEP_VERSION, 'all' );
        }

        $included_urls = array();
        $i = 1;
        foreach( $assets as $asset ) {
            if( !in_array( $asset['url'], $included_urls ) ) {
                wp_enqueue_style( 'wpuep_style' . $i, $asset['url'], false, WPUEP_VERSION, 'all' );
                $included_urls[] = $asset['url'];
                $i++;
            }
        }
    }

    private function enqueue_js( $assets, $use_minify, $js_to_enqueue_data_only, $js_names, $js_dependencies )
    {
        if( $use_minify ) {
            if( WPUltimateExercise::is_premium_active() ) {
                $external_deps = array_unique( array_diff( $js_dependencies, $js_names ) );
                //var_dump( $external_deps );
            } else {
                $external_deps = array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-droppable', 'suggest' );
            }

            wp_enqueue_script( 'wpuep_script_minified', WPUltimateExercise::get()->coreUrl . '/assets/wpuep-public.js', $external_deps, WPUEP_VERSION, true );

            foreach( $js_to_enqueue_data_only as $asset ) {
                $data_name = $asset['data']['name'];
                unset( $asset['data']['name'] );

                wp_localize_script( 'wpuep_script_minified', $data_name, $asset['data'] );
            }
        }

        $i = 1;
        foreach( $assets as $asset ) {
            $name = isset( $asset['name'] ) ? $asset['name'] : 'wpuep_script' . $i;
            $deps = isset( $asset['deps'] ) ? $asset['deps'] : '';

            wp_enqueue_script( $name, $asset['url'], $deps, WPUEP_VERSION, true );

            if( isset( $asset['data'] ) && isset( $asset['data']['name'] ) ) {
                $data_name = $asset['data']['name'];
                unset( $asset['data']['name'] );

                wp_localize_script( $name, $data_name, $asset['data'] );
            }

            $i++;
        }
    }

    private function minify( $assets, $dir )
    {
        $css_to_minify = array();
        $js_to_minify = array();

        foreach( $assets as $asset ) {
            // Don't minify direct assets
            if( isset( $asset['direct'] ) && $asset['direct'] ) continue;

            // Only minify public assets
            if( !isset( $asset['public'] ) || !$asset['public'] ) continue;

            switch( strtolower( $asset['type'] ) ) {
                case 'css':
                    $css_to_minify[] = $asset['dir'];
                    break;
                case 'js':
                    $js_to_minify[] = $asset;
                    break;
            }
        }

        $minify_files = array();

        /**
         * CSS minification
         */
        // CSS without base
        $minify_files[] = array(
            'name' => 'wpuep-public-without-base.css',
            'files' => array_unique( $css_to_minify ),
        );

        // CSS with normal base
        array_unshift( $css_to_minify, WPUltimateExercise::get()->coreDir . '/css/layout_base.css' );
        $minify_files[] = array(
            'name' => 'wpuep-public.css',
            'files' => array_unique( $css_to_minify ),
        );

        // CSS with forced base
        $css_to_minify[0] = WPUltimateExercise::get()->coreDir . '/css/layout_base_forced.css';
        $minify_files[] = array(
            'name' => 'wpuep-public-forced.css',
            'files' => array_unique( $css_to_minify ),
        );

        /**
         * JS minification
         */
        // Get all the named JS files
        $js_names = array();

        foreach( $js_to_minify as $js ) {
            if( isset( $js['name'] ) ) {
                $js_names[] = $js['name'];
            }
        }

        // Order JS files (max 20 loops)
        $js_minify_order = array();
        $js_ordered_names = array();

        for( $i = 0; $i < 20; $i++ ) {
            foreach( $js_to_minify as $index => $js ) {
                // Check which dependencies we need to actually resolve right now
                $actual_deps = array();
                if( isset( $js['deps'] ) ) {
                    foreach( $js['deps'] as $dep ) {
                        if( in_array( $dep, $js_names ) && !in_array( $dep, $js_ordered_names ) ) {
                            $actual_deps[] = $dep;
                        }
                    }
                }

                if( count( $actual_deps ) == 0 ) {
                    $js_minify_order[] = $js['dir'];
                    if( isset( $js['name'] ) ) {
                        $js_ordered_names[] = $js['name'];
                    }
                    unset( $js_to_minify[$index] );
                }
            }
        }

        if( count( $js_to_minify ) > 0 ) {
            var_dump( 'WP Ultimate Exercise: JS minification problem' );
        }

        $minify_files[] = array(
            'name' => 'wpuep-public.js',
            'files' => array_unique( $js_minify_order ),
        );

        /**
         * Performing the minification
         */
        require_once( WPUltimateExercise::get()->coreDir . '/vendor/magic-min/class.magic-min.php' );

        $minified = new Minifier( array(
            'echo' => false,
            'gzip' => false,
        ) );

        foreach( $minify_files as $minify_file ) {
            // Remove current file (easier while developing)
            if( is_file( $dir . $minify_file['name'] ) ) unlink( $dir . $minify_file['name'] );

            // Minify
            $minified->merge( $dir . $minify_file['name'], '', $minify_file['files'] );
        }
    }

    /**
     * Check if any of the shortcodes is used in post
     */
    public function check_for_shortcode( $shortcodes ) {
        if( !is_single() ) return apply_filters( 'wpuep_check_for_shortcode', true, $shortcodes ); // TODO Needs better solution

        global $post;

        if( function_exists( 'has_shortcode' ) ) {

            // Multiple shortcodes passed, if one shortcode is in the post, return true
            if( is_array( $shortcodes ) ) {
                $shortcode_used = false;

                foreach( $shortcodes as $shortcode ) {
                    if( isset( $post->post_content ) && has_shortcode( $post->post_content, $shortcode ) ) {
                        $shortcode_used = true;
                    }
                }

                return apply_filters( 'wpuep_check_for_shortcode', $shortcode_used, $shortcodes );
            }

            // Only one shortcode passed, true if that one is in the post
            if( isset( $post->post_content ) && has_shortcode( $post->post_content, $shortcodes ) ) {
                return apply_filters( 'wpuep_check_for_shortcode', true, $shortcodes );
            }

            return apply_filters( 'wpuep_check_for_shortcode', false, $shortcodes );
        }

        return apply_filters( 'wpuep_check_for_shortcode', true, $shortcodes ); // In older versions of WP just enqueue everything
    }
}