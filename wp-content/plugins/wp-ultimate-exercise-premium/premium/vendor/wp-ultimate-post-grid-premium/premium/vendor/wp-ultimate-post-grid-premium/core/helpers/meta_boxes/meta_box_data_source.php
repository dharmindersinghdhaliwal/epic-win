<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Egrid(0);
$premium_only = WPUltimateEPostEgrid::is_premium_active() ? '' : ' (' . __( 'Premium only', 'wp-eultimate-post-grid' ) . ')';
?>

<input type="hidden" name="wpupg_nonce" value="<?php echo wp_create_nonce( 'grid' ); ?>" />
<table id="wpupg_form_data_source" class="wpupg_form">
    <tr>
        <td><label for="wpupg_post_types"><?php _e( 'Post Types', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_post_types" id="wpupg_post_types" class="wpupg-select2">
                <?php
                $post_types = get_post_types( '', 'objects' );

                unset( $post_types[WPUPG_EPOST_TYPE] );
                unset( $post_types['revision'] );
                unset( $post_types['nav_menu_item'] );

                foreach( $post_types as $post_type => $options ) {
                    $selected = in_array( $post_type, $grid->post_types() ) ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $post_type ) . '"' . $selected . '>' . $options->labels->name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Post types to be displayed in the grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_order_by"><?php _e( 'Order By', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_order_by" id="wpupg_order_by" class="wpupg-select2">
                <?php
                $order_by_options = array(
                    'title' => __( 'Title', 'wp-eultimate-post-grid' ),
                    'date' => __( 'Date', 'wp-eultimate-post-grid' ),
                    'rand' => __( 'Random', 'wp-eultimate-post-grid' ),
                );

                foreach( $order_by_options as $order_by => $order_by_name ) {
                    $selected = $order_by == $grid->order_by() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $order_by ) . '"' . $selected . '>' . $order_by_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'How to order the posts in the grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_order"><?php _e( 'Order', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_order" id="wpupg_order" class="wpupg-select2">
                <?php
                $order_options = array(
                    'asc' => __( 'Ascending', 'wp-eultimate-post-grid' ),
                    'desc' => __( 'Descending', 'wp-eultimate-post-grid' ),
                );

                foreach( $order_options as $order => $order_name ) {
                    $selected = $order == $grid->order() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $order ) . '"' . $selected . '>' . $order_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_images_only"><?php _e( 'Images Only', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="checkbox" name="wpupg_images_only" id="wpupg_images_only" <?php if( $grid->images_only() ) echo 'checked="true" '?>/>
        </td>
        <td><?php _e( 'Only display posts with a featured image.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_limit_posts"><?php _e( 'Limit Posts', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="checkbox" name="wpupg_limit_posts" id="wpupg_limit_posts" <?php if( $grid->limit_posts() ) echo 'checked="true" '?>/>
            <?php echo $premium_only; ?>
        </td>
        <td><?php _e( 'Limit the posts that will be shown in the grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
</table>