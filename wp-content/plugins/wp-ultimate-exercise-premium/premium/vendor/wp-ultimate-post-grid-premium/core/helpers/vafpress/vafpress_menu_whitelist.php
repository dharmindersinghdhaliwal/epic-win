<?php
function wpupg_eadmin_premium_not_installed()
{
    return !WPUltimateEPostGrid::is_premium_active();
}

function wpupg_eadmin_premium_installed()
{
    return WPUltimateEPostGrid::is_premium_active();
}

function wpupg_eadmin_grids()
{
    $args = array(
        'post_type' => WPUPG_EPOST_TYPE,
        'post_status' => array( 'publish', 'private' ),
        'posts_per_page' => -1,
        'nopaging' => true,
        'orderby' => 'date',
        'order' => 'ASC',
    );

    $query = new WP_Query( $args );
    $posts = $query->have_posts() ? $query->posts : array();

    $egrids = array();
    foreach( $posts as $post ) {
        $egrids[] = array(
            'value' => $post->ID,
            'label' => $post->post_title,
        );
    }

    return $egrids;
}

function wpupg_eadmin_template_editor()
{
    if( WPUltimateEPostGrid::is_addon_active( 'template-editor' ) ) {
        $url = WPUltimateEPostGrid::addon( 'template-editor' )->editor_url();
        $button = '<a href="' . $url . '" class="button button-primary" target="_blank">' . __('Open the Template Editor', 'wp-eultimate-post-grid') . '</a>';
    } else {
        $button = '<a href="#" class="button button-primary button-disabled" disabled>' . __('Open the Template Editor', 'wp-eultimate-post-grid') . '</a>';
    }

    return $button;
}

function wpupg_eadmin_post_types()
{
    $post_types = get_post_types( '', 'names' );
    $types = array();

    foreach( $post_types as $post_type ) {
        $types[] = array(
            'value' => $post_type,
            'label' => ucfirst( $post_type )
        );
    }

    return $types;
}

VP_Security::instance()->whitelist_function( 'wpupg_eadmin_premium_not_installed' );
VP_Security::instance()->whitelist_function( 'wpupg_eadmin_premium_installed' );
VP_Security::instance()->whitelist_function( 'wpupg_eadmin_grids' );
VP_Security::instance()->whitelist_function( 'wpupg_eadmin_template_editor' );
VP_Security::instance()->whitelist_function( 'wpupg_eadmin_post_types' );