<?php

class WPUPG_Esupport_Tab {

    public function __construct()
    {
        add_action( 'admin_footer-' . WPUPG_EPOST_TYPE . '_page_wpupg_admin', array( $this, 'add_support_tab' ) );
        add_action( 'admin_footer-' . WPUPG_EPOST_TYPE . '_page_wpupg_faq', array( $this, 'add_support_tab' ) );
    }

    public function add_support_tab()
    {
        include(WPUltimateEPostEgrid::get()->coreDir . '/static/support_tab.html');
    }
}