<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $egrid ) ) $egrid = new WPUPG_Egrid(0);

$filter_style = $egrid->filter_style();
$isotope_style = $filter_style['isotope'];
?>

<table id="wpupg_form_isotope_filter_style" class="wpupg_form">
    <tr>
        <td>&nbsp;</td>
        <td><span class="wpupg_label_prefix"><?php _e( 'Background Color', 'wp-eultimate-post-grid' ); ?></span></td>
        <td><span class="wpupg_label_prefix"><?php _e( 'Text Color', 'wp-eultimate-post-grid' ); ?></span></td>
        <td><span class="wpupg_label_prefix"><?php _e( 'Border Color', 'wp-eultimate-post-grid' ); ?></span></td>
    </tr>
    <tr>
        <td><span class="wpupg_label_prefix"><?php _e( 'Default', 'wp-eultimate-post-grid' ); ?></span></td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_background_color" name="wpupg_isotope_filter_style_background_color" value="<?php echo $isotope_style['background_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_text_color" name="wpupg_isotope_filter_style_text_color" value="<?php echo $isotope_style['text_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_border_color" name="wpupg_isotope_filter_style_border_color" value="<?php echo $isotope_style['border_color']; ?>">
        </td>
    </tr>
    <tr>
        <td><span class="wpupg_label_prefix"><?php _e( 'Active', 'wp-eultimate-post-grid' ); ?></span></td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_background_active_color" name="wpupg_isotope_filter_style_background_active_color" value="<?php echo $isotope_style['background_active_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_text_active_color" name="wpupg_isotope_filter_style_text_active_color" value="<?php echo $isotope_style['text_active_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_border_active_color" name="wpupg_isotope_filter_style_border_active_color" value="<?php echo $isotope_style['border_active_color']; ?>">
        </td>
    </tr>
    <tr>
        <td><span class="wpupg_label_prefix"><?php _e( 'Hover', 'wp-eultimate-post-grid' ); ?></span></td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_background_hover_color" name="wpupg_isotope_filter_style_background_hover_color" value="<?php echo $isotope_style['background_hover_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_text_hover_color" name="wpupg_isotope_filter_style_text_hover_color" value="<?php echo $isotope_style['text_hover_color']; ?>">
        </td>
        <td>
            <input type="color" id="wpupg_isotope_filter_style_border_hover_color" name="wpupg_isotope_filter_style_border_hover_color" value="<?php echo $isotope_style['border_hover_color']; ?>">
        </td>
    </tr>
</table>
<table id="wpupg_form_isotope_filter_style_2" class="wpupg_form">
    <tr class="wpupg_divider">
        <td><span class="wpupg_label_prefix"><?php _e( 'Border', 'wp-eultimate-post-grid' ); ?></span><label for="wpupg_isotope_filter_style_border_width"><?php _e( 'Width', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_isotope_filter_style_border_width_slider"></div>
        </td>
        <td><input type="text" name="wpupg_isotope_filter_style_border_width" id="wpupg_isotope_filter_style_border_width" value="<?php echo $isotope_style['border_width']; ?>" />px</td>
    </tr>
    <tr class="wpupg_divider">
        <td><span class="wpupg_label_prefix"><?php _e( 'Margin', 'wp-eultimate-post-grid' ); ?></span><label for="wpupg_isotope_filter_style_margin_vertical"><?php _e( 'Vertical', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_isotope_filter_style_margin_vertical_slider"></div>
        </td>
        <td><input type="text" name="wpupg_isotope_filter_style_margin_vertical" id="wpupg_isotope_filter_style_margin_vertical" value="<?php echo $isotope_style['margin_vertical']; ?>" />px</td>
    </tr>
    <tr>
        <td><label for="wpupg_isotope_filter_style_margin_horizontal"><?php _e( 'Horizontal', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_isotope_filter_style_margin_horizontal_slider"></div>
        </td>
        <td><input type="text" name="wpupg_isotope_filter_style_margin_horizontal" id="wpupg_isotope_filter_style_margin_horizontal" value="<?php echo $isotope_style['margin_horizontal']; ?>" />px</td>
    </tr>
    <tr>
        <td><span class="wpupg_label_prefix"><?php _e( 'Padding', 'wp-eultimate-post-grid' ); ?></span><label for="wpupg_isotope_filter_style_padding_vertical"><?php _e( 'Vertical', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_isotope_filter_style_padding_vertical_slider"></div>
        </td>
        <td><input type="text" name="wpupg_isotope_filter_style_padding_vertical" id="wpupg_isotope_filter_style_padding_vertical" value="<?php echo $isotope_style['padding_vertical']; ?>" />px</td>
    </tr>
    <tr>
        <td><label for="wpupg_isotope_filter_style_padding_horizontal"><?php _e( 'Horizontal', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_isotope_filter_style_padding_horizontal_slider"></div>
        </td>
        <td><input type="text" name="wpupg_isotope_filter_style_padding_horizontal" id="wpupg_isotope_filter_style_padding_horizontal" value="<?php echo $isotope_style['padding_horizontal']; ?>" />px</td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_isotope_filter_style_alignment"><?php _e( 'Alignment', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_isotope_filter_style_alignment" id="wpupg_isotope_filter_style_alignment" class="wpupg-select2">
                <?php
                $alignment_options = array(
                    'left' => __( 'Left', 'wp-eultimate-post-grid' ),
                    'center' => __( 'Center', 'wp-eultimate-post-grid' ),
                    'right' => __( 'Right', 'wp-eultimate-post-grid' ),
                );

                foreach( $alignment_options as $alignment_option => $alignment_option_name ) {
                    $selected = $alignment_option == $isotope_style['alignment'] ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $alignment_option ) . '"' . $selected . '>' . $alignment_option_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'How to align the filters.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_isotope_filter_style_all_button_text"><?php _e( 'Button text', 'wp-eultimate-post-grid' ); ?></label></td>
        <td>
            <input type="text" name="wpupg_isotope_filter_style_all_button_text" id="wpupg_isotope_filter_style_all_button_text" value="<?php echo $isotope_style['all_button_text']; ?>" />
        </td>
        <td><?php _e( 'Text shown on the "All" button.', 'wp-eultimate-post-grid' ); ?></td>
    </tr>
</table>

<div id="wpupg_efilter_preview_isotope_filter_style" class="wpupg_efilter_preview">
    <div class="wpupg-efilter-isotope-term" id="wpupg_isotope_filter_style_all_button_text_preview"><?php _e( 'All', 'wp-eultimate-post-grid' ); ?></div>
    <div class="wpupg-efilter-isotope-term"><?php _e( 'A Tag', 'wp-eultimate-post-grid' ); ?></div>
    <div class="wpupg-efilter-isotope-term active"><?php _e( 'This is Active', 'wp-eultimate-post-grid' ); ?></div>
    <div class="wpupg-efilter-isotope-term"><?php _e( 'Example', 'wp-eultimate-post-grid' ); ?></div>
    <div class="wpupg-efilter-isotope-term"><?php _e( 'Preview', 'wp-eultimate-post-grid' ); ?></div>
</div>
