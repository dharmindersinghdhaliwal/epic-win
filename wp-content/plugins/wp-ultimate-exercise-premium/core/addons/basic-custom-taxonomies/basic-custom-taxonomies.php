<?php

if( !WPUltimateExercise::is_premium_active() )
{
    class WPUEP_Basic_Custom_Taxonomies extends WPUEP_Addon {
        private $ignoreTaxonomies;
		
        public function __construct( $name = 'basic-custom-taxonomies' ) {			
            parent::__construct( $name );
			
            //	Exercise taxonomies that users should not be able to delete
            $this->ignoreTaxonomies = array('rating', 'post_tag', 'category');
			
            //	Actions			
            add_action( 'init', array( $this, 'eassets' ) );			
            add_action( 'admin_init', array( $this, 'custom_taxonomies_settings' ) );			
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );			
            add_action( 'admin_action_add_taxonomy', array( $this, 'add_taxonomy' ) );						
        }
		
        public function eassets()
        {	
            WPUltimateExercise::get()->helper('eassets')->add(
                array(
                    'file' => $this->addonPath . '/css/custom-taxonomies.css',
                    'admin' => true,
                    'page' => 'exercise_page_wpuep_taxonomies',
                ),
                array(
                    'file' => $this->addonPath . '/js/custom-taxonomies.js',
                    'admin' => true,
                    'page' => 'exercise_page_wpuep_taxonomies',
                    'deps' => array(
                        'jquery',
                    ),
                )
            );
        }
		
        /*
         * Generate settings & addons pages
         */
        public function add_submenu_page() {
            add_submenu_page( null, __( 'Custom Taxonomies', 'wp-ultimate-exercise' ), __( 'Manage Tags', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_taxonomies', array( $this, 'custom_taxonomies_page' ) );
        }

        public function custom_taxonomies_page() {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( 'You do not have sufficient permissions to access this page.' );
            }

            include( $this->addonDir . '/templates/page.php' );
        }

        public function custom_taxonomies_settings() {
            add_settings_section( 'wpuep_taxonomies_list_section', __('Current Exercise Tags', 'wp-ultimate-exercise' ), array( $this, 'page_list_taxonomies' ), 'wpuep_taxonomies_settings' );
            add_settings_section( 'wpuep_taxonomies_settings_section', __('Add new Exercise Tag', 'wp-ultimate-exercise' ), array( $this, 'page_taxonomy_form' ), 'wpuep_taxonomies_settings' );
        }

        public function page_list_taxonomies() {
            include( $this->addonDir . '/templates/page_list.php' );
        }

        public function page_taxonomy_form() {
            include( $this->addonDir . '/templates/page_form.php' );
        }

        public function add_taxonomy() {
            if ( !wp_verify_nonce( $_POST['add_taxonomy_nonce'], 'add_taxonomy' ) ) {
                die( 'Invalid nonce.' . var_export( $_POST, true ) );
            }

            $name = $_POST['wpuep_custom_taxonomy_name'];
            $singular = $_POST['wpuep_custom_taxonomy_singular_name'];
            $slug = str_replace(' ', '-', strtolower($_POST['wpuep_custom_taxonomy_slug']));

            $edit_tag_name = $_POST['wpuep_edit'];
            $editing = false;

            if( strlen($edit_tag_name) > 0 ) {
                $editing = true;
            }

            if( !$editing ) {
                die( 'There was an unexpected error. Please try again.' );
            }

            if( !$editing && taxonomy_exists( strtolower( $singular ) ) ) {
                die( 'This taxonomy already exists.' );
            }

            if( strlen($name) > 1 && strlen($singular) > 1 ) {

                $taxonomies = WPUltimateExercise::get()->tags();

                $name_lower = strtolower( $name );

                // Cannot add tags in the basic version
                $tag_name = $edit_tag_name;

                // TODO Filter this to allow customizing
                $taxonomies[$tag_name] =
                    array(
                        'labels' => array(
                            'name'                       => $name,
                            'singular_name'              => $singular,
                            'search_items'               => __( 'Search', 'wp-ultimate-exercise' ) . ' ' . $name,
                            'popular_items'              => __( 'Popular', 'wp-ultimate-exercise' ) . ' ' . $name,
                            'all_items'                  => __( 'All', 'wp-ultimate-exercise' ) . ' ' . $name,
                            'edit_item'                  => __( 'Edit', 'wp-ultimate-exercise' ) . ' ' . $singular,
                            'update_item'                => __( 'Update', 'wp-ultimate-exercise' ) . ' ' . $singular,
                            'add_new_item'               => __( 'Add New', 'wp-ultimate-exercise' ) . ' ' . $singular,
                            'new_item_name'              => __( 'New', 'wp-ultimate-exercise' ) . ' ' . $singular . ' ' . __( 'Name', 'wp-ultimate-exercise' ),
                            'separate_items_with_commas' => __( 'Separate', 'wp-ultimate-exercise' ) . ' ' . $name_lower . ' ' . __( 'with commas', 'wp-ultimate-exercise' ),
                            'add_or_remove_items'        => __( 'Add or remove', 'wp-ultimate-exercise' ) . ' ' . $name_lower,
                            'choose_from_most_used'      => __( 'Choose from the most used', 'wp-ultimate-exercise' ) . ' ' . $name_lower,
                            'not_found'                  => __( 'No', 'wp-ultimate-exercise' ) . ' ' . $name_lower . ' ' . __( 'found.', 'wp-ultimate-exercise' ),
                            'menu_name'                  => $name
                        ),
                        'show_ui' => true,
                        'show_tagcloud' => true,
                        'hierarchical' => true,
                        'rewrite' => array(
                            'slug' => $slug,
                            'hierarchical' => true
                        )
                    );

                WPUltimateExercise::get()->helper( 'taxonomies' )->update( $taxonomies );
                WPUltimateExercise::get()->helper( 'taxonomies' )->check_exercise_taxonomies();
                WPUltimateExercise::get()->helper( 'permalinks_flusher' )->set_flush_needed();
            }

            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }
    }

    WPUltimateExercise::loaded_addon( 'basic-custom-taxonomies', new WPUEP_Basic_Custom_Taxonomies() );

} // !WPUltimateExercise::is_premium_active()