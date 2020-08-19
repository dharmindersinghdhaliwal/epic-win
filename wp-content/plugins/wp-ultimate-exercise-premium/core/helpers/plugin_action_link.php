<?php

class WPUEP_Plugin_Action_Link {

    public function __construct()
    {
        add_filter( 'plugin_action_links_wp-ultimate-exercise/wp-ultimate-exercise.php', array( $this, 'action_links' ) );
    }

    public function action_links( $links )
    {
        $links[] = '<a href="'. get_admin_url(null, 'edit.php?post_type=exercise&page=wpuep_admin') .'">'.__( 'Settings', 'wp-ultimate-exercise' ).'</a>';
        $links[] = '<a href="http://www.wpultimateexercise.com" target="_blank">'.__( 'More information', 'wp-ultimate-exercise' ).'</a>';

        return $links;
    }
}