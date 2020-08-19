<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $egrid ) ) $egrid = new WPUPG_Egrid(0);
$premium_only = WPUltimateEPostGrid::is_premium_active() ? '' : ' (' . __( 'Premium only', 'wp-eultimate-post-grid' ) . ')';
?>
<div id="wpupg_no_taxonomies"><?php _e( 'There are no taxonomies associated with this post type', 'wp-eultimate-post-grid' ); ?></div>
<table id="wpupg_form_filter" class="wpupg_form">
    <tr class="wpupg_no_filter">
        <td><label for="wpupg_efilter_type"><?php _e( 'Type', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_efilter_type" id="wpupg_efilter_type" class="wpupg-select2">
                <?php
                $filter_type_options = array(
                    'none' => __( 'No Filter', 'wp-eultimate-post-grid' ),
                    'isotope' => __( 'Isotope', 'wp-eultimate-post-grid' ),
                    'dropdown' => __( 'Dropdown', 'wp-eultimate-post-grid' ) . $premium_only,
                );

                foreach( $filter_type_options as $filter_type => $filter_type_name ) {
                    $selected = $filter_type == $egrid->filter_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $filter_type ) . '"' . $selected . '>' . $filter_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Type of filter to be used for this grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_efilter_taxonomy_post"><?php _e( 'Taxonomy', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <?php
            $post_types = get_post_types( '', 'objects' );

            unset( $post_types[WPUPG_EPOST_TYPE] );
            unset( $post_types['revision'] );
            unset( $post_types['nav_menu_item'] );

            foreach( $post_types as $post_type => $options ) {
                $taxonomies = get_object_taxonomies( $post_type, 'objects' );

                if( count( $taxonomies ) > 0 ) {
                    $multiple = WPUltimateEPostGrid::is_premium_active() ? ' multiple' : '';
                    echo '<div id="wpupg_efilter_taxonomy_' . $post_type . '_container" class="wpupg_efilter_taxonomy_container">';
                    echo '<select name="wpupg_efilter_taxonomy_' . $post_type . '[]" id="wpupg_efilter_taxonomy_' . $post_type . '" class="wpupg-select2"' . $multiple . '>';

                    foreach( $taxonomies as $taxonomy => $tax_options ) {
                        $selected = in_array( $taxonomy, $egrid->filter_taxonomies() ) ? ' selected="selected"' : '';
                        echo '<option value="' . esc_attr( $taxonomy ) . '"' . $selected . '>' . $tax_options->labels->name . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }
            }
            ?>
        </td>
        <td><?php _e( 'Taxonomy to be used for filtering the grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_efilter_match_parents"><?php _e( 'Selecting parent terms matches children', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="checkbox" name="wpupg_efilter_match_parents" id="wpupg_efilter_match_parents" <?php if( $egrid->filter_match_parents() ) echo 'checked="true" '?>/>
        </td>
        <td><?php _e( 'Selecting a parent term will also match posts with one of its child terms.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_efilter_multiselect"><?php _e( 'Multi-select', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="checkbox" name="wpupg_efilter_multiselect" id="wpupg_efilter_multiselect" <?php if( $egrid->filter_multiselect() ) echo 'checked="true" '?>/>
            <?php echo $premium_only; ?>
        </td>
        <td><?php _e( 'Allow users to select multiple terms.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tbody class="wpupg_multiselect">
    <tr>
        <td><label for="wpupg_efilter_multiselect_type"><?php _e( 'Multi-select Behaviour', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_efilter_multiselect_type" id="wpupg_efilter_multiselect_type" class="wpupg-select2">
                <?php
                $filter_multiselect_type_options = array(
                    'match_all' => __( 'Only posts that match all selected terms', 'wp-eultimate-post-grid' ),
                    'match_one' => __( 'All posts that match any of the selected terms', 'wp-eultimate-post-grid' ),
                );

                foreach( $filter_multiselect_type_options as $filter_multiselect_type => $filter_multiselect_type_name ) {
                    $selected = $filter_multiselect_type == $egrid->filter_multiselect_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $filter_multiselect_type ) . '"' . $selected . '>' . $filter_multiselect_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Type of filtering when selecting multiple terms.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    </tbody>
</table>