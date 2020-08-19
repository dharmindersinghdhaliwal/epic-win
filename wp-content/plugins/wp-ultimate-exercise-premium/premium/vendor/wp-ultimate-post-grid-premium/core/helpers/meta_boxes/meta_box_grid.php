<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $egrid ) ) $egrid = new WPUPG_Egrid(0);
?>

<table id="wpupg_form_grid" class="wpupg_form">
    <tr>
        <td><label for="wpupg_link_type"><?php _e( 'Links', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_link_type" id="wpupg_link_type" class="wpupg-select2">
                <?php
                $link_type_options = array(
                    '_self' => __( 'Open in same tab', 'wp-eultimate-post-grid' ),
                    '_blank' => __( 'Open in new tab', 'wp-eultimate-post-grid' ),
                    'none' => __( "Don't use links", 'wp-eultimate-post-grid' ),
                );

                foreach( $link_type_options as $link_type => $link_type_name ) {
                    $selected = $link_type == $egrid->link_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $link_type ) . '"' . $selected . '>' . $link_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Options for links surrounding the grid items.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_link_target"><?php _e( 'Link to', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_link_target" id="wpupg_link_target" class="wpupg-select2">
                <?php
                $link_target_options = array(
                    'post' => __( 'Post', 'wp-eultimate-post-grid' ),
                    'image' => __( 'Featured Image', 'wp-eultimate-post-grid' ),
                );

                foreach( $link_target_options as $link_target => $link_target_name ) {
                    $selected = $link_target == $egrid->link_target() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $link_target ) . '"' . $selected . '>' . $link_target_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Options for links surrounding the grid items.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_etemplate"><?php _e( 'Template', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_etemplate" id="wpupg_etemplate" class="wpupg-select2">
                <?php
                $templates = WPUltimateEPostGrid::addon( 'custom-templates' )->get_mapping();
                $templates = apply_filters( 'wpupg_meta_box_grid_templates', $templates );

                foreach ( $templates as $index => $template ) {
                    $selected = $index == $egrid->template_id() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $index ) . '"' . $selected . '>' . $template . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Template to be used for grid items.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_layout_mode"><?php _e( 'Layout Mode', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_layout_mode" id="wpupg_layout_mode" class="wpupg-select2">
                <?php
                $layout_mode_options = array(
                    'masonry' => __( 'Masonry (Pinterest like)', 'wp-eultimate-post-grid' ),
                    'fitRows' => __( 'Items in rows', 'wp-eultimate-post-grid' ),
                );

                foreach( $layout_mode_options as $layout_mode => $layout_mode_name ) {
                    $selected = $layout_mode == $egrid->layout_mode() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $layout_mode ) . '"' . $selected . '>' . $layout_mode_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Options for links surrounding the grid items.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_masonry">
        <td><label for="wpupg_centered"><?php _e( 'Center Egrid', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="checkbox" name="wpupg_centered" id="wpupg_centered" <?php if( $egrid->centered() ) echo 'checked="true" '?>/>
        </td>
        <td><?php _e( 'Center the entire grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
</table>