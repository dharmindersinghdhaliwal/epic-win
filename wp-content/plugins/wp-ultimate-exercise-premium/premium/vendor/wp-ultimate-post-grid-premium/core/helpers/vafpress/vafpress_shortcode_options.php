<?php

$shortcode_generator = array(
//=-=-=-=-=-=-= GRID =-=-=-=-=-=-=
    __( 'Egrid', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'by_date' => array(
                'title'   => __('Select a grid to display', 'wp-eultimate-post-grid') . ' - ' . __('Ordered by date added', 'wp-eultimate-post-grid'),
                'code'    => '[wpupg-egrid]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Egrid', 'wp-eultimate-post-grid'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpupg_eshortcode_generator_grids_by_date',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= FILTER =-=-=-=-=-=-=
    __( 'Filter', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'by_date' => array(
                'title'   => __('Select a filter to display', 'wp-eultimate-post-grid') . ' - ' . __('Ordered by date added', 'wp-eultimate-post-grid'),
                'code'    => '[wpupg-efilter]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Egrid', 'wp-eultimate-post-grid'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpupg_eshortcode_generator_grids_by_date',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);