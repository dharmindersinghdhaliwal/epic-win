<?php

class WPUEP_Ingredient_Units {

    protected $units;

    public function __construct()
    {
        $this->units = array(
            'weight' => array(
                'kilogram' => array(
                    'kg',
                    'kilogram',
                    'kilograms',
                ),
                'gram' => array(
                    'g',
                    'gram',
                    'grams',
                ),
                'milligram' => array(
                    'mg',
                    'milligram',
                    'milligrams',
                ),
                'pound' => array(
                    'lb',
                    'lbs',
                    'pound',
                    'pounds',
                ),
                'ounce' => array(
                    'oz',
                    'ounce',
                    'ounces',
                ),
            ),
            'volume' => array(
                'liter' => array(
                    'l',
                    'liter',
                    'liters',
                ),
                'deciliter' => array(
                    'dl',
                    'deciliter',
                    'deciliters',
                ),
                'centiliter' => array(
                    'cl',
                    'centiliter',
                    'centiliters',
                ),
                'milliliter' => array(
                    'ml',
                    'milliliter',
                    'milliliters',
                ),
                'gallon' => array(
                    'gal',
                    'gallon',
                    'gallons',
                ),
                'quart' => array(
                    'qt',
                    'quart',
                    'quarts',
                ),
                'pint' => array(
                    'pt',
                    'pint',
                    'pints',
                ),
                'cup' => array(
                    'cup',
                    'cups',
                    'cu',
                    'c',
                ),
                'fluid_ounce' => array(
                    'floz',
                    'fluid ounce',
                    'fluid ounces',
                    'fl ounce',
                    'fl ounces',
                ),
                'tablespoon' => array(
                    'tablespoon',
                    'tablespoons',
                    'tbsp',
                    'tbsps',
                    'tbls',
                    'tb',
                    'tbs',
                    'T'
                ),
                'teaspoon' => array(
                    'teaspoon',
                    'teaspoons',
                    'tsp',
                    'tsps',
                    'ts',
                    't',
                ),
            ),
            'length' => array(
                'meter' => array(
                    'm',
                    'meter',
                    'meters',
                ),
                'centimeter' => array(
                    'cm',
                    'centimeter',
                    'centimeters',
                ),
                'millimeter' => array(
                    'mm',
                    'millimeter',
                    'millimeters',
                ),
                'yard' => array(
                    'yd',
                    'yard',
                    'yards',
                ),
                'foot' => array(
                    'ft',
                    'foot',
                    'feet',
                ),
                'inch' => array(
                    'in',
                    'inch',
                    'inches',
                ),
            ),
        );
    }

    public function get_unit_admin_settings()
    {
        $admin = array(
            array(
                'type' => 'notebox',
                'name' => 'unit_conversion_unit_aliases_notebox',
                'label' => __('Unit Aliases', 'wp-ultimate-exercise'),
                'description' => __('Use a semicolon to separate unit aliases. For example: ', 'wp-ultimate-exercise') . ' ounce;ounces;oz',
                'status' => 'info',
            ),
        );

        // Unit type aliases
        foreach( $this->units as $unit_type => $units ) {

            $units_admin = array();

            foreach( $units as $unit => $aliases ) {
                $units_admin[] = array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_alias_' . $unit,
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), 'wp-ultimate-exercise' ),
                    'default' => implode( ';', $aliases ),
                );
            }

            $admin[] = array (
                'type' => 'section',
                'title' => __(ucfirst($unit_type) . ' Units', 'wp-ultimate-exercise'),
                'name' => 'section_unit_conversion_unit_aliases_' . $unit_type,
                'fields' => $units_admin,
            );
        }

        // Alias to convert to
        $admin[] = array(
            'type' => 'notebox',
            'name' => 'unit_conversion_unit_aliases_translate_notebox',
            'label' => __('Unit Aliases', 'wp-ultimate-exercise'),
            'description' => __('When converting to a unit the alias defined below will be shown. The singular form will be shown when the amount is 1, the plural otherwise.', 'wp-ultimate-exercise'),
            'status' => 'info',
        );

        $units_admin = array();

        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                // Singular
                $units_admin[] = array(
                    'type' => 'select',
                    'name' => 'unit_conversion_alias_' . $unit . '_singular',
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), 'wp-ultimate-exercise' ),
                    'description' => __( 'Singular', 'wp-ultimate-exercise' ),
                    'items' => array(
                        'data' => array(
                            array(
                                'source' => 'binding',
                                'field' => 'unit_conversion_alias_' . $unit,
                                'value' => 'wpuep_alias_options',
                            ),
                        ),
                    ),
                    'validation' => 'required',
                    'default' => array(
                        '{{first}}',
                    ),
                );

                // Plural
                $units_admin[] = array(
                    'type' => 'select',
                    'name' => 'unit_conversion_alias_' . $unit . '_plural',
                    'label' => '',
                    'description' => __( 'Plural', 'wp-ultimate-exercise' ),
                    'items' => array(
                        'data' => array(
                            array(
                                'source' => 'binding',
                                'field' => 'unit_conversion_alias_' . $unit,
                                'value' => 'wpuep_alias_options',
                            ),
                        ),
                    ),
                    'validation' => 'required',
                    'default' => array(
                        '{{first}}',
                    ),
                );
            }
        }

        $admin[] = array (
            'type' => 'section',
            'title' => __( 'Alias to convert to', 'wp-ultimate-exercise'),
            'name' => 'section_unit_conversion_unit_aliases_to_convert_to',
            'fields' => $units_admin,
        );

        return $admin;
    }

    public function get_unit_system_admin_settings()
    {
        $admin = array();
        $nbr_of_systems = 5;

        // Unit system names
        $admin[] = array(
            'type' => 'section',
            'title' => __('Unit Systems', 'wp-ultimate-exercise'),
            'name' => 'section_unit_conversion_unit_systems',
            'fields' => array(
                array(
                    'type' => 'slider',
                    'name' => 'unit_conversion_number_systems',
                    'label' => __('Number of Systems', 'wp-ultimate-exercise'),
                    'description' => __('Number of unit systems for your visitors to choose from.', 'wp-ultimate-exercise'),
                    'min' => '2',
                    'max' => '5',
                    'step' => '1',
                    'default' => '2',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_1',
                    'label' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' 1',
                    'default' => $this->get_default( 'system_name', 1 ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_2',
                    'label' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' 2',
                    'default' => $this->get_default( 'system_name', 2 ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_3',
                    'label' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' 3',
                    'default' => __( 'Custom', 'wp-ultimate-exercise' ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpuep_admin_system_3',
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_4',
                    'label' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' 4',
                    'default' => __( 'Custom', 'wp-ultimate-exercise' ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpuep_admin_system_4',
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_5',
                    'label' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' 5',
                    'default' => __( 'Custom', 'wp-ultimate-exercise' ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpuep_admin_system_5',
                    ),
                ),
            ),
        );

        // Universal units
        $items = array();
        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                $items[] = array(
                    'value' => $unit,
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), 'wp-ultimate-exercise' ),
                );
            }
        }

        $admin[] = array(
            'type' => 'section',
            'title' => __('Universal Units', 'wp-ultimate-exercise'),
            'name' => 'section_unit_conversion_universal_units',
            'fields' => array(
                array(
                    'type' => 'multiselect',
                    'name' => 'unit_conversion_universal_units',
                    'label' => __('Universal Units', 'wp-ultimate-exercise'),
                    'description' => __('These units are considered universal to all systems and will not be converted.', 'wp-ultimate-exercise'),
                    'items' => $items,
                    'default' => $this->get_default( 'universal_units', 1 ),
                ),
            ),
        );

        // Unit system units
        for( $i = 1; $i <= $nbr_of_systems; $i++ )
        {
            // Section dependency
            $dependency = null;
            if( $i >= 3 ) {
                $dependency = array(
                    'field' => 'unit_conversion_number_systems',
                    'function' => 'wpuep_admin_system_' . $i,
                );
            }

            // Multiselect Fields
            $fields = array();
            foreach( $this->units as $unit_type => $units ) {

                // Items
                $items = array();
                foreach( $units as $unit => $aliases ) {
                    $items[] = array(
                        'value' => $unit,
                        'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), 'wp-ultimate-exercise' ),
                    );
                }

                // Defaults
                $default = $this->get_default( 'system_units_' . $unit_type, $i );

                // Field
                $fields[] = array(
                    'type' => 'multiselect',
                    'name' => 'unit_conversion_system_'.$i.'_'.$unit_type,
                    'label' => __( ucfirst( $unit_type ) . ' units', 'wp-ultimate-exercise'),
                    'validation' => 'minselected[1]',
                    'items' => $items,
                    'default' => $default,
                );
            }

            // Cup type field
            $fields[] = array(
                'type' => 'select',
                'name' => 'unit_conversion_system_'.$i.'_cups',
                'label' => __('Cup Type', 'wp-ultimate-exercise'),
                'items' => array(
                    array(
                        'value' => '250',
                        'label' => __('Metric', 'wp-ultimate-exercise') . ' (250 ml)',
                    ),
                    array(
                        'value' => '236.6',
                        'label' => __('US Customary', 'wp-ultimate-exercise') . ' (236.6 ml)',
                    ),
                    array(
                        'value' => '240',
                        'label' => __('US Legal', 'wp-ultimate-exercise') . ' (240 ml)',
                    ),
                    array(
                        'value' => '200',
                        'label' => __('Japanese', 'wp-ultimate-exercise') . ' (200 ml)',
                    ),
                ),
                'default' => array(
                    $this->get_default( 'system_cup_type', 1 )
                ),
                'validation' => 'required',
                'dependency' => array(
                    'field' => 'unit_conversion_system_'.$i.'_volume',
                    'function' => 'wpuep_admin_system_cups',
                ),
            );

            // Section
            $admin[] = array(
                'type' => 'section',
                'title' => __( 'Unit System', 'wp-ultimate-exercise' ) . ' ' . $i,
                'name' => 'section_unit_conversion_unit_system_' . $i,
                'fields' => $fields,
                'dependency' => $dependency
            );
        }

        return $admin;
    }

    public function get_default( $field, $system = 1 )
    {
        if( $system < 1 || $system > 2 ) {
            return null;
        }

        switch( $field ) {
            case 'system_name':
                if( $system == 1 ) {
                    return __( 'Metric', 'wp-ultimate-exercise' );
                } else {
                    return __( 'US Imperial', 'wp-ultimate-exercise' );
                }
                break;
            case 'system_units_weight':
                if( $system == 1 ) {
                    return array( 'kilogram', 'gram', 'milligram' );
                } else {
                    return array( 'pound', 'ounce' );
                }
                break;
            case 'system_units_volume':
                if( $system == 1 ) {
                    return array( 'liter', 'deciliter', 'centiliter', 'milliliter' );
                } else {
                    return array( 'gallon', 'quart', 'pint', 'cup', 'fluid_ounce' );
                }
                break;
            case 'system_units_length':
                if( $system == 1 ) {
                    return array( 'meter', 'centimeter', 'millimeter' );
                } else {
                    return array( 'yard', 'foot', 'inch' );
                }
                break;
            case 'system_cup_type':
                return '236.6';
                break;
            case 'universal_units':
                return array( 'teaspoon', 'tablespoon', );
                break;
        }

        return null;
    }

    public function get_active_systems()
    {
        $nbr_systems = intval( WPUltimateExercise::option( 'unit_conversion_number_systems', 2) );

        // Get all active systems
        $systems = array();

        for( $i = 1; $i <= $nbr_systems; $i++ )
        {
            $name = WPUltimateExercise::option( 'unit_conversion_system_'.$i, $this->get_default( 'system_name', $i ));

            $weight = WPUltimateExercise::option( 'unit_conversion_system_'.$i.'_weight', $this->get_default( 'system_units_weight', $i ));
            $volume = WPUltimateExercise::option( 'unit_conversion_system_'.$i.'_volume', $this->get_default( 'system_units_volume', $i ));
            $length = WPUltimateExercise::option( 'unit_conversion_system_'.$i.'_length', $this->get_default( 'system_units_length', $i ));

            $cup_type = WPUltimateExercise::option( 'unit_conversion_system_'.$i.'_cups', $this->get_default( 'system_cup_type' ));

            $systems[] = array(
                'name' => $name,
                'units_weight' => $weight,
                'units_volume' => $volume,
                'units_length' => $length,
                'cup_type' => $cup_type,
            );
        }

        return $systems;
    }

    public function get_universal_units()
    {
        return WPUltimateExercise::option( 'unit_conversion_universal_units', $this->get_default( 'universal_units' ) );
    }

    public function get_alias_to_unit()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $default_aliases ) {
                $user_aliases = WPUltimateExercise::option( 'unit_conversion_alias_' . $unit, false );

                if($user_aliases) {
                    $aliases = explode( ';', $user_aliases );
                } else {
                    $aliases = $default_aliases;
                }

                $aliases[] = $unit;

                foreach( $aliases as $alias ) {
                    $clean = preg_replace( "/[^a-z]/i", "", $alias );
                    $lower = strtolower( $clean );

                    if( $clean != '' ) {
                        // Both case sensitive and lower version in output, will be the same for most cases
                        $out[$clean] = $unit;

                        if( !array_key_exists( $lower, $out ) ) {
                            $out[$lower] = $unit;
                        }
                    }
                }
            }
        }

        return $out;
    }

    public function get_unit_to_type()
    {
        $out = array();
        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                $out[$unit] = $unit_type;
            }
        }

        return $out;
    }

    public function get_unit_user_abbreviations()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $default_aliases ) {
                $user_aliases = WPUltimateExercise::option( 'unit_conversion_alias_' . $unit, false );

                if($user_aliases) {
                    $aliases = explode( ';', $user_aliases );
                } else {
                    $aliases = $default_aliases;
                }

                $singular = intval( WPUltimateExercise::option( 'unit_conversion_alias_' . $unit . '_singular', '0' ) );
                $plural = intval( WPUltimateExercise::option( 'unit_conversion_alias_' . $unit . '_plural', '0' ) );

                $out[$unit] = array(
                    'singular' => $aliases[$singular],
                    'plural' => $aliases[$plural]
                );
            }
        }

        return $out;
    }

    public function get_unit_abbreviations()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $aliases ) {
                $out[$unit] = $aliases[0];
            }
        }

        return $out;
    }
}