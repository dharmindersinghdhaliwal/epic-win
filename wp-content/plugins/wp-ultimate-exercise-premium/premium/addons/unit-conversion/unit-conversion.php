<?php

class WPUEP_Unit_Conversion extends WPUEP_Premium_Addon {

    public function __construct( $name = 'unit-conversion' ) {
        parent::__construct( $name );

        add_action( 'init', array( $this, 'eassets' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'name' => 'js-quantities',
                'file' => $this->addonPath . '/vendor/js-quantities.js',
                'premium' => true,
                'public' => true,
                'admin' => true,
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'name' => 'wpuep-unit-conversion',
                'file' => $this->addonPath . '/js/unit-conversion.js',
                'premium' => true,
                'public' => true,
                'admin' => true,
                'deps' => array(
                    'jquery',
                    'fraction',
                    'js-quantities',
                ),
                'data' => array(
                    'name' => 'wpuep_unit_conversion',
                    'alias_to_unit'         => WPUltimateExercise::get()->helper( 'ingredient_units')->get_alias_to_unit(),
                    'unit_to_type'          => WPUltimateExercise::get()->helper( 'ingredient_units')->get_unit_to_type(),
                    'universal_units'       => WPUltimateExercise::get()->helper( 'ingredient_units')->get_universal_units(),
                    'systems'               => WPUltimateExercise::get()->helper( 'ingredient_units')->get_active_systems(),
                    'unit_abbreviations'    => WPUltimateExercise::get()->helper( 'ingredient_units')->get_unit_abbreviations(),
                    'user_abbreviations'    => WPUltimateExercise::get()->helper( 'ingredient_units')->get_unit_user_abbreviations(),
                )
            )
        );
    }
}

WPUltimateExercise::loaded_addon( 'unit-conversion', new WPUEP_Unit_Conversion() );