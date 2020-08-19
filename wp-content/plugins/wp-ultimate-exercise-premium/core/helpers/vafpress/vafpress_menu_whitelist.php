<?php
function wpuep_admin_users()
{
    $users = array();
    $blogusers = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );

    foreach( $blogusers as $bloguser ) {
        $users[] = array(
            'value' => $bloguser->ID,
            'label' => $bloguser->ID . ' - ' . $bloguser->display_name,
        );
    }

    return $users;
}

function wpuep_admin_exercise_slug_preview( $slug )
{
    return __( 'The exercise archive can be found at', 'wp-ultimate-exercise' ) . ' <a href="'.site_url('/'.$slug.'/').'" target="_blank">'.site_url('/'.$slug.'/').'</a>';
}


function wpuep_admin_user_emenus_slug_preview( $slug )
{
    return __( 'The user menus archive can be found at', 'wp-ultimate-exercise' ) . ' <a href="'.site_url('/'.$slug.'/').'" target="_blank">'.site_url('/'.$slug.'/').'</a>';
}


function wpuep_admin_premium_not_installed()
{
    return !WPUltimateExercise::is_premium_active();
}


function wpuep_admin_premium_installed()
{
    return WPUltimateExercise::is_premium_active();
}

function wpuep_admin_exercise_template_style($style)
{
    return $style == 'custom' ? true : false;
}

function wpuep_admin_chicory_terms( $button, $terms )
{
    return $button && !$terms;
}

function wpuep_admin_manage_fields()
{
    if( WPUltimateExercise::is_addon_active( 'template-editor' ) ) {
        $button = '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_custom_fields' ).'" class="button button-primary" target="_blank">'.__('Manage custom exercise fields', 'wp-ultimate-exercise').'</a>';
    } else {
        $button = '<a href="#" class="button button-primary button-disabled" disabled>'.__('Manage custom exercise fields', 'wp-ultimate-exercise').'</a>';
    }

    return $button;
}
function wpuep_admin_custom_fields()
{
    $fields = array();

    $custom_fields_addon = WPUltimateExercise::addon( 'custom-fields' );
    if( $custom_fields_addon )
    {
        $custom_fields = $custom_fields_addon->get_custom_fields();

        foreach( $custom_fields as $key => $custom_field ) {
            $fields[] = array(
                'value' => $key,
                'label' => $custom_field['name'],
            );
        }
    }

    return $fields;
}

function wpuep_admin_manage_tags()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_taxonomies' ).'" class="button button-primary" target="_blank">'.__('Manage custom exercise tags', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_template_editor_exercise()
{
    return WPUltimateExercise::get()->helper( 'cache' )->get( 'exercises_by_title' );
}

function wpuep_admin_template_editor()
{
    if( WPUltimateExercise::is_addon_active( 'template-editor' ) ) {
        $url = WPUltimateExercise::addon( 'template-editor' )->editor_url();
        $url .= '#?dir=' . urlencode( ABSPATH );
        $button = '<a href="' . $url . '" class="button button-primary" target="_blank">' . __('Open the Template Editor', 'wp-ultimate-exercise') . '</a>';
    } else {
        $button = '<a href="#" class="button button-primary button-disabled" disabled>' . __('Open the Template Editor', 'wp-ultimate-exercise') . '</a>';
    }

    return $button;
}

function wpuep_admin_templates()
{
    $template_list = array();
    $templates = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

    foreach ( $templates as $index => $template ) {

        $template_list[] = array(
            'value' => $index,
            'label' => $template,
        );

    }

    return $template_list;
}

function wpuep_admin_import_easyexercise()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_easyexercise' ).'" class="button button-primary" target="_blank">'.__('Import EasyExercise exercises', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_import_exercisecard()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_exercisecard' ).'" class="button button-primary" target="_blank">'.__('Import ExerciseCard exercises', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_import_exerciseress()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_recipress' ).'" class="button button-primary" target="_blank">'.__('Import ReciPress exercises', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_import_ziplist()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_ziplist' ).'" class="button button-primary" target="_blank">'.__('Import Ziplist exercises', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_import_xml()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_xml' ).'" class="button button-primary" target="_blank">'.__('Import XML', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_import_fdx()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_import_fdx' ).'" class="button button-primary" target="_blank">'.__('Import FDX', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_export_xml()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_export_xml' ).'" class="button button-primary" target="_blank">'.__('Export XML', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_system_3( $nbr )
{
    return $nbr >= 3 ? true : false;
}

function wpuep_admin_system_4( $nbr )
{
    return $nbr >= 4 ? true : false;
}

function wpuep_admin_system_5( $nbr )
{
    return $nbr >= 5 ? true : false;
}

function wpuep_admin_system_cups( $units )
{
    return in_array('cup', $units);
}

function wpuep_get_unit_systems()
{
    $unit_systems = array();

    $nbr_systems = WPUltimateExercise::option( 'unit_conversion_number_systems', 2 );

    for( $i = 0; $i < $nbr_systems; $i++ ) {
        $system = WPUltimateExercise::option( 'unit_conversion_system_' . ($i+1), 'System ' . ($i+1) );

        $unit_systems[] = array(
            'value' => $i,
            'label' => $system,
        );
    }

    return $unit_systems;
}

function wpuep_alias_options($param = '')
{
    $options = array();

    $aliases = explode(';', $param);

    foreach($aliases as $index => $alias) {
        $options[] = array(
            'value' => $index,
            'label' => $alias
        );
    }

    return $options;
}

function evp_dep_boolean_inverse($value)
{
    $args   = func_get_args();
    $result = true;
    foreach ($args as $val)
    {
        $result = ($result and !empty($val));
    }
    return !$result;
}

function wpuep_font_preview_with_text($text, $face, $style, $weight, $size)
{
    VP_Site_GoogleWebFont::instance()->add($face, $weight, $style);
    $fonts = VP_Site_GoogleWebFont::instance()->get_font_links();

    $out = '';
    foreach($fonts as $font)
    {
        $out .= '<link href="'.$font.'" rel="stylesheet" type="text/css">';
    }

    $out .= '<div style="font-family: '.$face.'; font-style: '.$style.'; font-weight: '.$weight.'; font-size: '.$size.'px; height: '.($size + 5).'px; margin-top: 15px;">' . $text . '</div>';
    return $out;
}

function wpuep_font_preview($face, $style, $weight, $size)
{
    return wpuep_font_preview_with_text('The quick brown fox jumps over the lazy dog', $face, $style, $weight, $size);
}

function wpuep_reset_demo_exercise()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&wpuep_reset_demo_exercise=true' ).'" class="button button-primary" target="_blank">'.__('Reset Demo Exercise', 'wp-ultimate-exercise').'</a>';
}

function wpuep_manage_exercise_grid()
{
    return '<a href="'.admin_url( 'edit.php?post_type=' . WPUPG_EPOST_TYPE ).'" class="button button-primary" target="_blank">'.__('Manage Exercise Egrids', 'wp-ultimate-exercise').'</a>';
}

function wpuep_reset_exercise_grid_terms()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&wpuep_reset_exercise_grid_terms=true' ).'" class="button button-primary" target="_blank">'.__('Recalculate Exercise Egrid Terms', 'wp-ultimate-exercise').'</a>';
}

function wpuep_reset_cache()
{
    return '<a href="'.admin_url( 'edit.php?post_type=exercise&wpuep_reset_cache=true' ).'" class="button button-primary" target="_blank">'.__('Reset Cache', 'wp-ultimate-exercise').'</a>';
}

function wpuep_admin_user_submission_required_fields()
{
    $fields = array();

    $default_fields = array(
        'title' => __( 'Exercise title', 'wp-ultimate-exercise' ),
        'exercise-author' => __( 'Your name', 'wp-ultimate-exercise' ) . ' (' . __( 'guests', 'wp-ultimate-exercise' ) . ')',
        'exercise_description' => __( 'Description', 'wp-ultimate-exercise' ),
        'exercise_servings' => __( 'Servings', 'wp-ultimate-exercise' ),
        'exercise_prep_time' => __( 'Prep Time', 'wp-ultimate-exercise' ),
        'exercise_cook_time' => __( 'Cook Time', 'wp-ultimate-exercise' ),
        'exercise_passive_time' => __( 'Passive Time', 'wp-ultimate-exercise' ),
    );

    foreach( $default_fields as $value => $label ) {
        $fields[] = array(
            'value' => $value,
            'label' => $label,
        );
    }

    if( WPUltimateExercise::option( 'exercise_fields_in_user_submission', '1' ) == '1' ) {
        foreach( wpuep_admin_custom_fields() as $custom_field ) {
            $custom_field['label'] = __( 'Custom Fields', 'wp-ultimate-exercise' ) . ': ' . $custom_field['label'];
            $fields[] = $custom_field;
        }
    }

    return $fields;
}

function wpuep_admin_exercise_tags()
{
    $taxonomy_list = array();

    $args = array(
        'object_type' => array( 'exercise' )
    );

    $taxonomies = get_taxonomies( $args, 'objects' );

    foreach ( $taxonomies  as $taxonomy ) {

        if( !in_array( $taxonomy->name, array( 'rating', 'ingredient' ) ) ) {
            $taxonomy_list[] = array(
                'value' => $taxonomy->name,
                'label' => $taxonomy->labels->name,
            );
        }
    }

    return $taxonomy_list;
}

function wpuep_admin_category_terms()
{
    return wpuep_admin_get_terms( 'category' );
}

function wpuep_admin_tag_terms()
{
    return wpuep_admin_get_terms( 'post_tag' );
}

function wpuep_admin_get_terms( $taxonomy )
{
    $args = array(
        'hide_empty' => false
    );

    $terms = get_terms( $taxonomy, $args );

    $result = array();
    foreach( $terms as $term ) {
        $result[] = array(
            'value' => $term->term_id,
            'label' => $term->name,
        );
    }

    return $result;
}

function wpuep_admin_post_types()
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


VP_Security::instance()->whitelist_function('wpuep_admin_users');
VP_Security::instance()->whitelist_function('wpuep_admin_exercise_slug_preview');
VP_Security::instance()->whitelist_function('wpuep_admin_user_emenus_slug_preview');
VP_Security::instance()->whitelist_function('wpuep_admin_premium_not_installed');
VP_Security::instance()->whitelist_function('wpuep_admin_premium_installed');
VP_Security::instance()->whitelist_function('wpuep_admin_exercise_template_style');
VP_Security::instance()->whitelist_function('wpuep_admin_chicory_terms');
VP_Security::instance()->whitelist_function('wpuep_admin_manage_fields');
VP_Security::instance()->whitelist_function('wpuep_admin_custom_fields');
VP_Security::instance()->whitelist_function('wpuep_admin_manage_tags');
VP_Security::instance()->whitelist_function('wpuep_admin_template_editor_exercise');
VP_Security::instance()->whitelist_function('wpuep_admin_template_editor');
VP_Security::instance()->whitelist_function('wpuep_admin_templates');
VP_Security::instance()->whitelist_function('wpuep_admin_import_easyexercise');
VP_Security::instance()->whitelist_function('wpuep_admin_import_exercisecard');
VP_Security::instance()->whitelist_function('wpuep_admin_import_exerciseress');
VP_Security::instance()->whitelist_function('wpuep_admin_import_ziplist');
VP_Security::instance()->whitelist_function('wpuep_admin_import_xml');
VP_Security::instance()->whitelist_function('wpuep_admin_import_fdx');
VP_Security::instance()->whitelist_function('wpuep_admin_export_xml');
VP_Security::instance()->whitelist_function('wpuep_admin_system_3');
VP_Security::instance()->whitelist_function('wpuep_admin_system_4');
VP_Security::instance()->whitelist_function('wpuep_admin_system_5');
VP_Security::instance()->whitelist_function('wpuep_admin_system_cups');
VP_Security::instance()->whitelist_function('wpuep_get_unit_systems');
VP_Security::instance()->whitelist_function('wpuep_alias_options');
VP_Security::instance()->whitelist_function('evp_dep_boolean_inverse');
VP_Security::instance()->whitelist_function('wpuep_font_preview');
VP_Security::instance()->whitelist_function('wpuep_font_preview_with_text');
VP_Security::instance()->whitelist_function('wpuep_reset_demo_exercise');
VP_Security::instance()->whitelist_function('wpuep_manage_exercise_grid');
VP_Security::instance()->whitelist_function('wpuep_reset_exercise_grid_terms');
VP_Security::instance()->whitelist_function('wpuep_reset_cache');
VP_Security::instance()->whitelist_function('wpuep_admin_user_submission_required_fields');
VP_Security::instance()->whitelist_function('wpuep_admin_exercise_tags');
VP_Security::instance()->whitelist_function('wpuep_admin_category_terms');
VP_Security::instance()->whitelist_function('wpuep_admin_tag_terms');
VP_Security::instance()->whitelist_function('wpuep_admin_post_types');