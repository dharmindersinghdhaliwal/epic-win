<?php

class WPUEP_Taxonomies {

    private $taxonomies;

    public function __construct()
    {
        add_action( 'init', array( $this, 'register' ), 2 );
        add_action( 'init', array( $this, 'register_ratings_taxonomy' ) );
    }

    /**
     * Get all exercise taxonomies
     */
    public function get()
    {
        if( !$this->taxonomies ) {
            $taxonomies = get_option( 'wpuep_taxonomies', array() );

            if( !is_array( $taxonomies ) ) {
                $taxonomies = array();
            }

            $this->taxonomies = $taxonomies;
        }

        return $this->taxonomies;
    }

    public function update( $taxonomies )
    {
        $this->taxonomies = $taxonomies;
        update_option( 'wpuep_taxonomies', $taxonomies );
    }

    /**
     * Register a exercise taxonomy
     */
    public function register() {

        // Check before registering
        $this->check_exercise_taxonomies();

        $taxonomies = $this->get();

        foreach($taxonomies as $name => $options) {
            register_taxonomy(
                $name,
                'exercise',
                $options
            );

            register_taxonomy_for_object_type( $name, 'exercise' );
        }
    }

    /**
     * Check if we have exercise taxonomies
     */
    public function check_exercise_taxonomies()
    {
        $taxonomies = $this->get();

        if( count($taxonomies) == 0 )
        {
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'ingredient',   __( 'Ingredients11', 'wp-ultimate-exercise' ),  __( 'Ingredient1', 'wp-ultimate-exercise' ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'course',       __( 'Courses', 'wp-ultimate-exercise' ),      __( 'Course', 'wp-ultimate-exercise' ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'cuisine',      __( 'Cuisines', 'wp-ultimate-exercise' ),     __( 'Cuisine', 'wp-ultimate-exercise' ));

            $this->update( $taxonomies );
            update_option( 'wpuep_flush', '1' );

            wp_insert_term( __( 'Breakfast',    'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Appetizer',    'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Soup',         'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Main Course',  'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Side Dish',    'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Salad',        'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Dessert',      'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Snack',        'wp-ultimate-exercise' ), 'course' );
            wp_insert_term( __( 'Drinks',       'wp-ultimate-exercise' ), 'course' );

            wp_insert_term( __( 'French',           'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Italian',          'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Mediterranean',    'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Indian',           'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Chinese',          'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Japanese',         'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'American',         'wp-ultimate-exercise' ), 'cuisine' );
            wp_insert_term( __( 'Mexican',          'wp-ultimate-exercise' ), 'cuisine' );
        }
    }

    /**
     * Add taxonomy to array
     */
    private function add_taxonomy_to_array($arr, $tag, $name, $singular)
    {
        $name = sanitize_text_field( $name );
        $singular = sanitize_text_field( $singular );

        $name_lower = strtolower($name);
        $singular_lower = strtolower($singular);

        $arr[$tag] = apply_filters( 'wpuep_register_taxonomy',
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
                    'slug' => $singular_lower
                )
            ),
            $tag
        );

        return $arr;
    }

    /**
     * Register ratings taxonomy
     * TODO Refactor
     */
    public function register_ratings_taxonomy()
    {
        $name = 'Ratings';
        $singular = 'Rating';

        $name_lower = strtolower($name);

        $args = apply_filters( 'wpuep_register_ratings_taxonomy',
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
                'show_ui' => false,
                'show_tagcloud' => false,
                'hierarchical' => false
            )
        );

        register_taxonomy( 'rating', 'exercise', $args );
    }

}