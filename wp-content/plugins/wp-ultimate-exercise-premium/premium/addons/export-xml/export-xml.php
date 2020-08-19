<?php

class WPUEP_Export_Xml extends WPUEP_Premium_Addon {

    public function __construct( $name = 'export-xml' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'export_menu' ) );
        add_action( 'admin_menu', array( $this, 'export_manual_menu' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/export_xml.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_export_xml',
            ),
            array(
                'file' => $this->addonPath . '/js/export_xml.js',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_export_xml',
                'deps' => array(
                    'jquery',
                ),
            )
        );
    }

    public function export_menu() {
        add_submenu_page( null, __( 'Export XML', 'wp-ultimate-exercise' ), __( 'Export XML', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_export_xml', array( $this, 'export_page' ) );
    }

    public function export_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function export_manual_menu() {
        add_submenu_page( null, __( 'Export XML', 'wp-ultimate-exercise' ), __( 'Export XML', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_export_xml_manual', array( $this, 'export_manual_page' ) );
    }

    public function export_manual_page() {
        if ( !wp_verify_nonce( $_POST['submitexercise'], 'exercise_submit' ) ) {
            die( 'Invalid nonce.' );
        }
        echo '<h2>XML Export</h2>';

        $exercises = $_POST['exercises'];
        if( count( $exercises ) == 0 ) {
            _e( "You haven't selected any exercises to export.", 'wp-ultimate-exercise' );
        } else {
            $xml_data = array(
                'name' => 'exercises',
            );

            foreach( $exercises as $exercise ) {
                $xml_data[] = $this->export_xml_exercise( intval( $exercise ) );
            }

            $doc = new DOMDocument();
            $child = $this->generate_xml_element( $doc, $xml_data );
            if ( $child ) {
                $doc->appendChild( $child );
            }
            $doc->formatOutput = true; // Add whitespace to make easier to read XML
            $xml = $doc->saveXML();

            echo '<form id="exportExercies" action="' . $this->addonUrl . '/templates/download.php" method="post">';
            echo '<input type="hidden" name="exportExercies" value="' . base64_encode( $xml ) . '"/>';
            submit_button( __( 'Download XML', 'wp-ultimate-exercise' ) );
            echo '</form>';
        }
    }

    public function export_xml_exercise( $exercise_id ) {
        $exercise = new WPUEP_Exercise( $exercise_id );

        $xml = array(
            'name' => 'exercise',
            array(
                'name' => 'title',
                'value' => $exercise->title(),
            ),
        );

        if( $exercise->is_present( 'exercise_description' ) ) {
            $xml[] = array(
                'name' => 'description',
                'value' => $exercise->description(),
            );
        }

        if( $exercise->is_present( 'exercise_image' ) ) {
            $xml[] = array(
                'name' => 'imageUrl',
                'value' => $exercise->image_url( 'full' ),
            );
        }

        if( $exercise->is_present( 'exercise_rating_author' ) ) {
            $xml[] = array(
                'name' => 'rating',
                'attributes' => array(
                    'stars' => $exercise->rating_author(),
                ),
            );
        }

        if( $exercise->is_present( 'exercise_servings' ) || $exercise->is_present( 'exercise_servings_type' ) ) {
            $xml[] = array(
                'name' => 'servings',
                'attributes' => array(
                    'quantity' => $exercise->servings(),
                    'unit' => $exercise->servings_type(),
                ),
            );
        }
        if( $exercise->is_present( 'exercise_prep_time' ) || $exercise->is_present( 'exercise_prep_time_text' ) ) {
            $xml[] = array(
                'name' => 'prepTime',
                'attributes' => array(
                    'quantity' => $exercise->prep_time(),
                    'unit' => $exercise->prep_time_text(),
                ),
            );
        }
        if( $exercise->is_present( 'exercise_cook_time' ) || $exercise->is_present( 'exercise_cook_time_text' ) ) {
            $xml[] = array(
                'name' => 'cookTime',
                'attributes' => array(
                    'quantity' => $exercise->cook_time(),
                    'unit' => $exercise->cook_time_text(),
                ),
            );
        }
        if( $exercise->is_present( 'exercise_passive_time' ) || $exercise->is_present( 'exercise_passive_time_text' ) ) {
            $xml[] = array(
                'name' => 'passiveTime',
                'attributes' => array(
                    'quantity' => $exercise->passive_time(),
                    'unit' => $exercise->passive_time_text(),
                ),
            );
        }

        if( $exercise->is_present( 'exercise_ingredients' ) ) {
            $previous_group = null;
            $group_ingredients = array();

            foreach( $exercise->ingredients() as $ingredient ) {
                $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

                if( $group !== $previous_group ) {
                    if( count( $group_ingredients ) > 0 ) {
                        $group_xml = array(
                            'name' => 'ingredients',
                            'attributes' => array(
                                'group' => $previous_group,
                            ),
                            $group_ingredients,
                        );

                        $xml[] = array_merge( $group_xml, $group_ingredients );
                    }

                    $previous_group = $group;
                    $group_ingredients = array();
                }

                $group_ingredients[] = array(
                    'name' => 'ingredient',
                    'attributes' => array(
                        'name' => $ingredient['ingredient'],
                        'quantity' => $ingredient['amount'],
                        'unit' => $ingredient['unit'],
                        'notes' => $ingredient['notes'],
                    ),
                );
            }

            if( count( $group_ingredients ) > 0 ) {
                $group_xml = array(
                    'name' => 'ingredients',
                    'attributes' => array(
                        'group' => $group,
                    ),
                    $group_ingredients,
                );

                $xml[] = array_merge( $group_xml, $group_ingredients );
            }
        }

        if( $exercise->is_present( 'exercise_instructions' ) ) {
            $previous_group = null;
            $group_instructions = array();

            foreach( $exercise->instructions() as $instruction ) {
                $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

                if( $group !== $previous_group ) {
                    if( count( $group_instructions ) > 0 ) {
                        $group_xml = array(
                            'name' => 'instructions',
                            'attributes' => array(
                                'group' => $previous_group,
                            ),
                            $group_instructions,
                        );

                        $xml[] = array_merge( $group_xml, $group_instructions );
                    }

                    $previous_group = $group;
                    $group_instructions = array();
                }

                $group_instructions[] = array(
                    'name' => 'instruction',
                    'value' => $instruction['description'],
                );
            }

            if( count( $group_instructions ) > 0 ) {
                $group_xml = array(
                    'name' => 'instructions',
                    'attributes' => array(
                        'group' => $group,
                    ),
                    $group_instructions,
                );

                $xml[] = array_merge( $group_xml, $group_instructions );
            }
        }

        if( $exercise->is_present( 'exercise_notes' ) ) {
            $xml[] = array(
                'name' => 'notes',
                'value' => $exercise->notes(),
            );
        }

        $exercise_nutritional = $exercise->nutritional();
        if( $exercise_nutritional ) {
            $xml[] = array(
                'name' => 'nutrition',
                'attributes' => $exercise_nutritional,
            );
        }

        $taxonomies = WPUltimateExercise::get()->tags();
        unset( $taxonomies['ingredient'] );
        $taxonomies['category'] = true;
        $taxonomies['post_tag'] = true;

        foreach( $taxonomies as $taxonomy => $options ) {
            $terms = get_the_terms($exercise_id, $taxonomy);
            $terms_xml = array();

            if (!is_wp_error($terms) && $terms) {
                foreach ($terms as $term) {
                    $terms_xml[] = array(
                        'name' => 'term',
                        'value' => $term->name,
                    );
                }
            }

            if (count($terms_xml) > 0) {
                $taxonomy_xml = array(
                    'name' => 'taxonomy',
                    'attributes' => array(
                        'name' => $taxonomy,
                    ),
                );

                $xml[] = array_merge($taxonomy_xml, $terms_xml);
            }
        }

        $custom_fields_addon = WPUltimateExercise::addon( 'custom-fields' );
        if( $custom_fields_addon ) {
            $custom_fields = $custom_fields_addon->get_custom_fields();

            foreach( $custom_fields as $key => $custom_field ) {
                $value = $exercise->custom_field( $key );

                if( $value ) {
                    $xml[] = array(
                        'name' => 'customField',
                        'attributes' => array(
                            'key' => $key,
                            'value' => $exercise->custom_field( $key ),
                        ),
                    );
                }
            }
        }

        return $xml;
    }

    /**
     * Helper functions
     */
    // Source: http://www.viper007bond.com/2011/06/29/easily-create-xml-in-php-using-a-data-array/
    private function generate_xml_element( $dom, $data ) {
        if ( empty( $data['name'] ) )
            return false;

        // Create the element
        $element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
        $element = $dom->createElement( $data['name'], $element_value );

        // Add any attributes
        if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
            foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
                $element->setAttribute( $attribute_key, $attribute_value );
            }
        }

        // Any other items in the data array should be child elements
        foreach ( $data as $data_key => $child_data ) {
            if ( ! is_numeric( $data_key ) )
                continue;

            $child = $this->generate_xml_element( $dom, $child_data );
            if ( $child )
                $element->appendChild( $child );
        }

        return $element;
    }
}

WPUltimateExercise::loaded_addon( 'export-xml', new WPUEP_Export_Xml() );