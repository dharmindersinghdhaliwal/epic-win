<?php

class WPUPG_Ecss {

    public function __construct()
    {
        add_action( 'wp_head', array( $this, 'dropdown_filters_css' ), 19 );
        add_action( 'wp_head', array( $this, 'custom_css' ), 20 );
    }

    public function dropdown_filters_css()
    {
        if( WPUltimateEPostGrid::is_premium_active() ) {
            $border_color               = WPUltimateEPostGrid::option( 'filters_dropdown_border_color', '#AAAAAA' );
            $text_color                 = WPUltimateEPostGrid::option( 'filters_dropdown_text_color', '#444444' );
            $background_highlight_color = WPUltimateEPostGrid::option( 'filters_dropdown_highlight_background_color', '#5897FB' );
            $text_highlight_color       = WPUltimateEPostGrid::option( 'filters_dropdown_highlight_text_color', '#FFFFFF' );

            echo '<style type="text/css">';
            // Border Color
            echo '.select2wpupg-selection, .select2wpupg-dropdown { border-color: '.$border_color.'!important; }';
            echo '.select2wpupg-selection__arrow b { border-top-color: '.$border_color.'!important; }';
            echo '.select2wpupg-container--open .select2wpupg-selection__arrow b { border-bottom-color: '.$border_color.'!important; }';

            // Text color
            echo '.select2wpupg-selection__placeholder, .select2wpupg-search__field, .select2wpupg-selection__rendered, .select2wpupg-results__option { color: '.$text_color.'!important; }';
            echo '.select2wpupg-search__field::-webkit-input-placeholder { color: '.$text_color.'!important; }';
            echo '.select2wpupg-search__field:-moz-placeholder { color: '.$text_color.'!important; }';
            echo '.select2wpupg-search__field::-moz-placeholder { color: '.$text_color.'!important; }';
            echo '.select2wpupg-search__field:-ms-input-placeholder { color: '.$text_color.'!important; }';

            // Highlight colors
            echo '.select2wpupg-results__option--highlighted { color: '.$text_highlight_color.'!important; background-color: '.$background_highlight_color.'!important; }';

            echo '</style>';
        }
    }

    public function custom_css()
    {
        if( WPUltimateEPostGrid::option( 'custom_code_public_css', '' ) !== '' ) {
            echo '<style type="text/css">';
            echo WPUltimateEPostGrid::option( 'custom_code_public_css', '' );
            echo '</style>';
        }
    }
}