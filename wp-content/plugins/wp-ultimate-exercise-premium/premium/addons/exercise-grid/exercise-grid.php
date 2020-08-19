<?php

class WPUEP_Exercise_Egrid extends WPUEP_Premium_Addon {

    public function __construct( $name = 'exercise-grid' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'admin_init', array( $this, 'check_terms_reset' ) );

        add_action( 'admin_init', array( $this, 'updated_terms_check' ) );
        add_action( 'edited_terms', array( $this, 'updated_terms' ), 10, 2 );
        add_action( 'save_post', array( $this, 'reset_saved_post_terms' ), 10, 2 );

        // Ajax
        add_action( 'wp_ajax_exercise_grid_get_exercises', array( $this, 'ajax_exercise_grid_get_exercises' ) );
        add_action( 'wp_ajax_nopriv_exercise_grid_get_exercises', array( $this, 'ajax_exercise_grid_get_exercises' ) );

        // Shortcode
        add_shortcode( 'ultimate-exercise-grid', array( $this, 'exercise_grid_shortcode' ));
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => WPUltimateExercise::get()->coreUrl . '/vendor/select2/select2.css',
                'direct' => true,
                'public' => true,
                'shortcode' => 'ultimate-exercise-grid',
            ),
            array(
                'file' => $this->addonPath . '/css/exercise-grid.css',
                'premium' => true,
                'public' => true,
                'shortcode' => 'ultimate-exercise-grid',
            ),
            array(
                'name' => 'select2',
                'file' => '/vendor/select2/select2.min.js',
                'public' => true,
                'shortcode' => 'ultimate-exercise-grid',
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'name' => 'exercise-grid',
                'file' => $this->addonPath . '/js/exercise-grid.js',
                'premium' => true,
                'public' => true,
                'shortcode' => 'ultimate-exercise-grid',
                'deps' => array(
                    'jquery',
                    'select2',
                ),
                'data' => array(
                    'name' => 'wpuep_exercise_grid',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'wpuep_exercise_grid' ),
                )
            )
        );
    }

    public function check_terms_reset()
    {
        if( isset( $_GET['wpuep_reset_exercise_grid_terms'] ) ) {
            $exercises = WPUltimateExercise::get()->query()->all();

            foreach ( $exercises as $exercise )
            {
                WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $exercise->ID() );
            }

            WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> The Exercise Egrid terms have been reset' );
        }
    }

    public function reset_saved_post_terms( $id, $post )
    {
        if( $post->post_type == 'exercise' )
        {
            // Other case gets handled by the exercise_save helper
            if ( !isset( $_POST['exercise_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['exercise_meta_box_nonce'], 'exercise' ) )
            {
                WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $id );
            }
        }
    }

    /*
     * Has to be done in two steps to make sure we update the latest version
     */
    public function updated_terms( $term_id, $taxonomy )
    {
        $term = get_term( $term_id, $taxonomy );
        $exercises = WPUltimateExercise::get()->query()->taxonomy( $taxonomy )->term( $term->slug )->get();
        $exercise_ids = array();

        foreach ( $exercises as $exercise )
        {
            $exercise_ids[] = $exercise->ID();
        }

        if( count( $exercise_ids ) != 0 ) {
            update_option( 'wpuep_update_exercise_grid_terms', $exercise_ids );
        }
    }

    public function updated_terms_check()
    {
        $exercise_ids = get_option( 'wpuep_update_exercise_grid_terms', false );
        if( $exercise_ids ) {
            foreach( $exercise_ids as $exercise_id ) {
                WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $exercise_id );
            }

            update_option( 'wpuep_update_exercise_grid_terms', false );
        }
    }

    public function ajax_exercise_grid_get_exercises()
    {
        if( check_ajax_referer( 'wpuep_exercise_grid', 'security', false ) )
        {
            $egrid = $_POST['grid'];
            $egrid['name'] = $_POST['grid_name'];

            // TODO Limit exercises
            $exercises = WPUltimateExercise::get()->query()->ids( $egrid['exercises'] )->order( $egrid['order'] )->order_by( $egrid['orderby'] )->get();

            // Filter Exercies
            foreach( $exercises as $index => $exercise )
            {
                if( $egrid['match_parents'] ) {
                    $exercise_terms = $exercise->terms_with_parents();
                } else {
                    $exercise_terms = $exercise->terms();
                }

                $exercise_in_grid = true;
                foreach( $egrid['filters'] as $taxonomy => $filters )
                {
                    if( !is_array( $filters ) ) {
                        unset( $egrid['filters'][$taxonomy] );
                    } else {
                        $match = false;

                        foreach( $filters as $filter ) {
                            $match = in_array( intval( $filter ), $exercise_terms[$taxonomy] );

                            if( $egrid['match_all'] && $match == false ) break;
                            if( !$egrid['match_all'] && $match == true ) break;
                        }

                        if( !$match ) {
                            unset( $exercises[$index] );
                            break;
                        }
                    }
                }
            }


            // Output Exercies
            // TODO Refactor
            $out = '';

            if( count( $exercises ) == 0 ) {
                $out .= '<div>' . __( 'No exercises found.', 'wp-ultimate-exercise' ) . '</div>';
            }
            else
            {
                foreach( $exercises as $exercise )
                {
                    $thumb = $exercise->image_url( 'thumbnail' );

                    if( !is_null( $thumb ) || !$egrid['images_only'] )
                    {
                        $out .= '<div class="exercise exercise-card" id="'.$egrid['name'].'-exercise-' . $exercise->ID() . '" data-link="' . $exercise->link() . '">';
                        $exercise_output = $exercise->output_string( 'grid', $egrid['template'] );
                        $out .= apply_filters( 'wpuep_output_exercise_grid', $exercise_output, $exercise );
                        $out .= '</div>';
                    }
                }
            }

            echo $out;
        }

        die();
    }

    public function exercise_grid_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'name' => 'default',
            'template' => 'default',
            'sort_by' => 'title',
            'sort_order' => 'ASC',
            'no_filter' => 'false',
            'filter' => 'all',
            'multiselect' => 'true',
            'match_all' => 'true',
            'match_parents' => 'true',
            'limit_author' => '',
            'limit_by_tag' => 'false',
            'limit_by_values' => '',
            'images_only' => 'false',
            'limit' => '999'
        ), $options );

        $name = preg_replace("/\W/", '', $options['name']);
        $template = strtolower( $options['template'] );
        $sort_by = strtolower( $options['sort_by'] );
        $sort_order = strtoupper( $options['sort_order'] );
        $no_filter = strtolower( $options['no_filter'] );
        $filter = strtolower( $options['filter'] );
        $multiselect = strtolower( $options['multiselect'] );
        $match_all = strtolower( $options['match_all'] );
        $match_parents = strtolower( $options['match_parents'] );
        $limit_author = $options['limit_author'];
        $limit_by_tag = $options['limit_by_tag'] == 'false' ? false : strtolower( $options['limit_by_tag'] );
        $limit_by_values = str_replace( ';', ',', strtolower( $options['limit_by_values'] ) );
        $images_only = strtolower( $options['images_only'] );
        $limit = intval( $options['limit'] );

        $sort_by = in_array( $sort_by, array( 'author', 'name', 'title', 'date', 'rating', 'rand' ) ) ? $sort_by : 'title';
        $sort_order = in_array( $sort_order, array( 'ASC', 'DESC' ) ) ? $sort_order : 'ASC';
        $no_filter = $no_filter == 'true' ? true : false;
        $multiselect = $multiselect == 'true' ? true : false;
        $match_all = $match_all == 'true' ? true : false;
        $match_parents = $match_parents == 'true' ? true : false;
        $filter_options = explode( ',', $filter );
        if ( $filter_options[0] == 'all' ) {
            $all = true;
        } else {
            $all = false;
        }
        $images_only = $images_only != 'false' ? true : false;

        // Get Exercise IDs
        $exercise_ids = array();

        if( $limit_by_tag && strlen( $limit_by_values ) > 0 ) {
            $tag_values = explode( ',', $limit_by_values );

            foreach( $tag_values as $term )
            {
                $exercise_ids = array_merge(
                    $exercise_ids,
                    WPUltimateExercise::get()->query()->author( $limit_author )->taxonomy( $limit_by_tag )->term( $term )->images_only( $images_only )->ids_only()->get()
                );
            }
        } else {
            $exercise_ids = WPUltimateExercise::get()->query()->author( $limit_author )->images_only( $images_only )->ids_only()->get();
        }

        $exercise_ids = apply_filters( 'wpuep_exercise_grid_exercise_ids', $exercise_ids, $name );

        // Output variables
        $filters_out = '';
        $out = '';

        // Exercise Egrid Filters
        $js_filters = array();
        if( !$no_filter )
        {
            // Filter Taxonomies
            $taxonomies = WPUltimateExercise::get()->tags();

            if( in_array( 'category', $filter_options ) || ( $all && WPUltimateExercise::option( 'exercise_tags_filter_categories', '0' ) == '1' ) ) {
                $taxonomies['category'] = array(
                    'labels' => array(
                        'name' => __( 'Categories', 'wp-ultimate-exercise' )
                    )
                );
            }

            if( in_array( 'post_tag', $filter_options ) || ( $all && WPUltimateExercise::option( 'exercise_tags_filter_tags', '0' ) == '1' ) ) {
                $taxonomies['post_tag'] = array (
                    'labels' => array(
                        'name' => __( 'Tags', 'wp-ultimate-exercise' )
                    )
                );
            }

            // Order taxonomies by order in shortcode
            $taxonomies = array_merge(array_flip($filter_options), $taxonomies);

            $used_terms = array();

            foreach( $taxonomies as $taxonomy => $options ) {
                $used_terms[$taxonomy] = wp_get_object_terms( $exercise_ids, $taxonomy, array( 'fields' => 'ids' ) );
            }

            $used_terms = apply_filters( 'wpuep_exercise_grid_filter_terms', $used_terms );


            $filters_out .= '<div class="wpuep-exercise-grid-filter-box">';

            foreach( $taxonomies as $taxonomy => $options ) {
                if( is_array( $options ) && ( $all || in_array( $taxonomy, $filter_options ) ) )
                {
                    $args = array(
                        'show_option_none' => 'none',
                        'taxonomy' => $taxonomy,
                        'echo' => 0,
                        'hide_empty' => 1,
                        'class' => 'wpuep-exercise-grid-filter',
                        'show_count' => 0,
                        'orderby' => 'name',
                        'hide_if_empty' => true
                    );
                    $placeholder = $options['labels']['name'];

                    $options = get_categories( $args );

                    if( $multiselect )
                    {
                        $empty_option = '';
                        $multiple = ' multiple';
                    } else {
                        $empty_option = '<option></option>';
                        $multiple = '';
                    }

                    $select = '<select name="exercise-'.$taxonomy.'" id="exercise-'.$taxonomy.'" class="wpuep-exercise-grid-filter" data-grid-name="'.$name.'" data-placeholder="'.$placeholder.'"'. $multiple .'>';
                    $select .= $empty_option;

                    $nbr_valid_options = 0;
                    foreach( $options as $option ) {
                        if( in_array( $option->term_id, $used_terms[$taxonomy] ) ) {
                            $select .= '<option value="'.$option->term_id.'">'.$option->name.'</option>';
                            $nbr_valid_options++;
                        }
                    }

                    $select .= '</select>';

                    if( $nbr_valid_options > 0 ) {
                        $filters_out .= $select;
                    }

                    $js_filters[] = array( $taxonomy => 0 );
                }
            }

            $filters_out .= '</div>';
        }

        // Exercise Egrid Cards
        $out .= '<div class="wpuep-exercise-grid-container" id="wpuep-exercise-grid-'.$name.'" data-grid-name="'.$name.'">';
        if( count( $exercise_ids ) == 0 ) {
            $out .= '<div>' . __( 'No exercises found.', 'wp-ultimate-exercise' ) . '</div>';
        }
        else
        {
            // Get actual exercise data
            $exercises = WPUltimateExercise::get()->query()->ids( $exercise_ids )->order( $sort_order )->order_by( $sort_by )->limit( $limit )->get();

            foreach( $exercises as $exercise )
            {
                $thumb = $exercise->image_url( 'thumbnail' );

                if( !is_null( $thumb ) || !$images_only)
                {
                    $out .= '<div class="exercise exercise-card" id="'.$name.'-exercise-' . $exercise->ID() . '" data-link="' . $exercise->link() . '">';
                    $exercise_output = $exercise->output_string( 'grid', $template );
                    $out .= apply_filters( 'wpuep_output_exercise_grid', $exercise_output, $exercise );
                    $out .= '</div>';
                }
            }

            // TODO Load more exercises
//            if( count( $exercises ) > $limit ) {
//                $out .= '<div class="exercise-grid-load-more"><a href="#">' . __( 'Load more exercises', 'wp-ultimate-exercise' ) . '</a></div>';
//            }
        }
        $out .= '</div>';

        $script_name = WPUltimateExercise::option( 'assets_use_minified', '1' ) == '1' ? 'wpuep_script_minified' : 'exercise-grid';

        wp_localize_script( $script_name, 'wpuep_exercise_grid_' . $name,
            array(
                'exercises' => $exercise_ids,
                'template' => $template,
                'orderby' => $sort_by,
                'order' => $sort_order,
                'limit' => $limit,
                'images_only' => $images_only,
                'filters' => $js_filters,
                'match_all' => $match_all,
                'match_parents' => $match_parents,
            )
        );

        return $filters_out . $out;
    }
}

WPUltimateExercise::loaded_addon( 'exercise-grid', new WPUEP_Exercise_Egrid() );