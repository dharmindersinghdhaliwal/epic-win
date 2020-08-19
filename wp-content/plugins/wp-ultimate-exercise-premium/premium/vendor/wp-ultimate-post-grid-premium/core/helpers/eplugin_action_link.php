<?php

class WPUPG_Eplugin_Action_Link {

    public function __construct()
    {
        add_filter( 'plugin_action_links_wp-eultimate-post-grid/wp-eultimate-post-grid.php', array( $this, 'action_links' ) );
    }

    public function action_links( $links )
    {
        //$links[] = '<a href="'. get_admin_url(null, 'edit.php?post_type=exercise&page=wpupg_eadmin') .'">'.__( 'Settings', 'wp-ultimate-exercise' ).'</a>';
        $links[] = '<a href="http://bootstrapped.ventures" target="_blank">'.__( 'More information', 'wp-eultimate-post-grid' ).'</a>';

        return $links;
    }
}