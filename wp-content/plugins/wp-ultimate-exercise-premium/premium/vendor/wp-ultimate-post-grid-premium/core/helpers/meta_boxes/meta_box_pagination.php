<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $egrid ) ) $egrid = new WPUPG_Egrid(0);
$premium_only = WPUltimateEPostGrid::is_premium_active() ? '' : ' (' . __( 'Premium only', 'wp-eultimate-post-grid' ) . ')';

$pagination = $egrid->pagination();
?>
<table id="wpupg_form_pagination" class="wpupg_form">
    <tbody class="wpupg_epagination_none">
    <tr>
        <td><label for="wpupg_epagination_type"><?php _e( 'Type', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_epagination_type" id="wpupg_epagination_type" class="wpupg-select2">
                <?php
                $pagination_type_options = array(
                    'none' => __( 'No pagination (all posts visible at once)', 'wp-eultimate-post-grid' ),
                    'pages' => __( 'Divide posts in pages', 'wp-eultimate-post-grid' ),
                    'load_more' => __( 'Use a "Load More" button', 'wp-eultimate-post-grid' ) . $premium_only,
                );

                foreach( $pagination_type_options as $pagination_type => $pagination_type_name ) {
                    $selected = $pagination_type == $egrid->pagination_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $pagination_type ) . '"' . $selected . '>' . $pagination_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Type of pagination to be used for this grid.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    </tbody>
    <tbody class="wpupg_epagination_pages">
    <tr class="wpupg_divider">
        <td><label for="wpupg_epagination_pages_posts_per_page"><?php _e( 'Posts per page', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_epagination_pages_posts_per_page_slider"></div>
        </td>
        <td><input type="text" name="wpupg_epagination_pages_posts_per_page" id="wpupg_epagination_pages_posts_per_page" value="<?php echo $pagination['pages']['posts_per_page']; ?>" /><?php _e( 'posts', 'wp-ultimate-posts-grid' ); ?></td>
    </tr>
    </tbody>
    <tbody class="wpupg_epagination_load_more">
    <tr class="wpupg_divider">
        <td><label for="wpupg_epagination_load_more_initial_posts"><?php _e( 'Initial posts', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_epagination_load_more_initial_posts_slider"></div>
        </td>
        <td><input type="text" name="wpupg_epagination_load_more_initial_posts" id="wpupg_epagination_load_more_initial_posts" value="<?php echo $pagination['load_more']['initial_posts']; ?>" /><?php _e( 'posts', 'wp-ultimate-posts-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_epagination_load_more_posts_on_click"><?php _e( 'Posts loaded on click', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_epagination_load_more_posts_on_click_slider"></div>
        </td>
        <td><input type="text" name="wpupg_epagination_load_more_posts_on_click" id="wpupg_epagination_load_more_posts_on_click" value="<?php echo $pagination['load_more']['posts_on_click']; ?>" /><?php _e( 'posts', 'wp-ultimate-posts-grid' ); ?></td>
    </tr>
    <tr>
        <td><label for="wpupg_epagination_load_more_button_text"><?php _e( 'Button text', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="text" name="wpupg_epagination_load_more_button_text" id="wpupg_epagination_load_more_button_text" value="<?php echo $pagination['load_more']['button_text']; ?>" />
        </td>
        <td><?php _e( 'Text shown on the "Load More" button.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    </tbody>
</table>