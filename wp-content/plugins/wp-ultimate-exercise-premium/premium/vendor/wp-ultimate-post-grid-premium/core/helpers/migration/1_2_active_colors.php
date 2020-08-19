<?php
/*
 * -> 1.2
 *
 * Set active colors to hover colors
 */

$args = array(
    'post_type' => WPUPG_EPOST_TYPE,
    'post_status' => 'any',
    'posts_per_page' => -1,
    'nopaging' => true,
);

$query = new WP_Query( $args );
$posts = $query->have_posts() ? $query->posts : array();

foreach ( $posts as $egrid_post )
{
    $egrid = new WPUPG_Egrid( $egrid_post );

    $filter_type = $egrid->filter_type();

    if( $filter_type == 'isotope' ) {
        $filter_style = $egrid->filter_style();

        $filter_style['isotope']['background_active_color'] = $filter_style['isotope']['background_hover_color'];
        $filter_style['isotope']['text_active_color'] = $filter_style['isotope']['text_hover_color'];
        $filter_style['isotope']['border_active_color'] = $filter_style['isotope']['border_hover_color'];

        update_post_meta( $egrid->ID(), 'wpupg_efilter_style', $filter_style );
    }
}

// Successfully migrated to 1.2
$migrate_version = '1.2';
update_option( 'wpupg_migrate_version', $migrate_version );
if( $notices ) WPUltimateEPostGrid::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Post Egrid</strong> Successfully migrated to 1.2+' );