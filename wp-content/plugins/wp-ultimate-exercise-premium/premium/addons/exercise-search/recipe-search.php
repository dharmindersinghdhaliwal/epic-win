<?php

class WPUEP_Exercise_Search extends WPUEP_Premium_Addon {

    public function __construct( $name = 'exercise-search' ) {
        parent::__construct( $name );

        require_once( $this->addonDir . '/widgets/exercise_search_widget.php' );

        //Actions
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts_search' ), 20 );
    }

    public function pre_get_posts_search( $query )
    {
        if( $query->is_main_query() && isset( $_GET['wpuep-search'] ) )
        {
            // Only search for exercises
            $query->set( 'post_type', 'exercise' );

            // Check taxonomy filters
            $tax_query = array();
            foreach( $_GET as $tag => $term )
            {
                if( substr( $tag, 0, 7 ) == 'exercise-' && $term != '0') {
                    $tax_query[] = array(
                        'taxonomy' => substr( $tag, 7 ),
                        'field' => 'id',
                        'terms' => array( intval( $term ) ),
                    );
                }
            }

            if( !empty( $tax_query ) ) {
                $query->tax_query->queries = $tax_query;
                $query->query_vars['tax_query'] = $query->tax_query->queries;
            }
        }

        return $query;
    }
}

WPUltimateExercise::loaded_addon( 'exercise-search', new WPUEP_Exercise_Search() );