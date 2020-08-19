<?php

class WPUEP_Support_Tab {

    public function __construct()
    {
        add_action( 'admin_footer-exercise_page_wpuep_admin', array( $this, 'add_support_tab' ) );
        add_action( 'admin_footer-exercise_page_wpuep_faq', array( $this, 'add_support_tab' ) );
    }

    public function add_support_tab()
    {
        include(WPUltimateExercise::get()->coreDir . '/static/support_tab.html');
    }
}