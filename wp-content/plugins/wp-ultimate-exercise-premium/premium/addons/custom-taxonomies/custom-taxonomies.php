<?php

class WPUEP_Custom_Taxonomies extends WPUEP_Premium_Addon {

    private $ignoreTaxonomies;

    public function __construct( $name = 'custom-taxonomies' ) {
        parent::__construct( $name );

        // Exercise taxonomies that users should not be able to delete
        $this->ignoreTaxonomies = array('rating', 'post_tag', 'category');
        $this->protectedTaxonomies = array('ingredient');

        //Actions
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'admin_init', array( $this, 'custom_taxonomies_settings' ) );
        add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
        add_action( 'admin_action_delete_taxonomy', array( $this, 'delete_taxonomy' ) );
        add_action( 'admin_action_add_taxonomy', array( $this, 'add_taxonomy' ) );
        add_action( 'admin_action_reset_taxonomies', array( $this, 'reset_taxonomies' ) );
    }

    public function eassets()
    {
        WPUltimateExercise::get()->helper('eassets')->add(
            array(
                'file' => $this->addonPath . '/css/custom-taxonomies.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_taxonomies',
            ),
            array(
                'file' => $this->addonPath . '/js/custom-taxonomies.js',
                'premium' => true,
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
        add_submenu_page( 'edit.php?post_type=exercise', __( 'Custom Taxonomies', 'wp-ultimate-exercise' ), __( 'Custom Tags', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_taxonomies', array( $this, 'custom_taxonomies_page' ) );
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

    // TODO - Not the nicest way of doing this.
    public function delete_taxonomy() {
        if ( !wp_verify_nonce( $_POST['delete_taxonomy_nonce'], 'delete_taxonomy' ) ) {
            die( 'Invalid nonce.');
        }

        foreach( $_POST as $name => $value ) {
            if( strpos($name, 'submit-delete-') === 0 ) { // Starts with submit-delete-
                $taxonomy =  substr( $name, 14 );

                global $wp_taxonomies;
                $taxonomies = WPUltimateExercise::get()->tags();

                if( taxonomy_exists( $taxonomy ) && array_key_exists( $taxonomy, $taxonomies ) ) {
                    unset( $wp_taxonomies[$taxonomy] );
                    unset( $taxonomies[$taxonomy] );

                    WPUltimateExercise::get()->helper( 'taxonomies' )->update( $taxonomies );
                }
            }
        }

        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();
    }

    public function reset_taxonomies() {
        if ( !wp_verify_nonce( $_POST['reset_taxonomies_nonce'], 'reset_taxonomies' ) ) {
            die( 'Invalid nonce.');
        }

        WPUltimateExercise::get()->helper( 'taxonomies' )->update( array() );

        WPUltimateExercise::get()->helper( 'taxonomies' )->check_exercise_taxonomies();
        WPUltimateExercise::get()->helper( 'permalinks_flusher' )->set_flush_needed();

        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();
    }

    public function add_taxonomy() {
        if ( !wp_verify_nonce( $_POST['add_taxonomy_nonce'], 'add_taxonomy' ) ) {
            die( 'Invalid nonce.' . var_export( $_POST, true ) );
        }

        $taxonomies = WPUltimateExercise::get()->tags();

        $name = $_POST['wpuep_custom_taxonomy_name'];
        $singular = $_POST['wpuep_custom_taxonomy_singular_name'];
        $slug = str_replace(' ', '-', strtolower($_POST['wpuep_custom_taxonomy_slug']));
        $tag_name = str_replace(' ', '-', strtolower($singular));
        $tag_name = preg_replace( '/[^a-z\-]/i', '', $tag_name );

        if( strlen($tag_name) == 0 ) {
            $tag_name = 'custom_tag' . (count( $taxonomies ) + 1);
        }

        $edit_tag_name = $_POST['wpuep_edit'];
        $editing = false;

        if( strlen($edit_tag_name) > 0 ) {
            $editing = true;
        }

        if( !$editing && taxonomy_exists( strtolower( $singular ) ) ) {
            die( 'This taxonomy already exists.' );
        }

        if( strlen($tag_name) > 1 && strlen($name) > 1 && strlen($singular) > 1 ) {
            $name_lower = strtolower( $name );

            if( $editing ) {
                $tag_name = $edit_tag_name;
            }

            $taxonomies[$tag_name] = apply_filters( 'wpuep_register_taxonomy',
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
                ),
                $tag_name
            );

            WPUltimateExercise::get()->helper( 'taxonomies' )->update( $taxonomies );
            WPUltimateExercise::get()->helper( 'taxonomies' )->check_exercise_taxonomies();
            WPUltimateExercise::get()->helper( 'permalinks_flusher' )->set_flush_needed();
        }

        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();
    }
}

WPUltimateExercise::loaded_addon( 'custom-taxonomies', new WPUEP_Custom_Taxonomies() );