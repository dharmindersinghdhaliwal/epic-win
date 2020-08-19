<?php

$unit_helper = WPUltimateExercise::get()->helper('ingredient_units');
$conversion_units_admin = $unit_helper->get_unit_admin_settings();
$unit_systems_admin = $unit_helper->get_unit_system_admin_settings();

// Include part of site URL hash in HTML settings to update when site URL changes
$sitehash = substr( md5( WPUltimateExercise::get()->coreUrl ), 0, 8 );

$template_editor_button = WPUltimateExercise::is_addon_active( 'template-editor' ) ? 'exercise_template_open_editor_active' . $sitehash : 'exercise_template_open_editor_disabled';
$custom_fields_button = WPUltimateExercise::is_addon_active( 'custom-fields' ) ? 'exercise_fields_manage_custom_active' . $sitehash : 'exercise_fields_manage_custom_disabled';

$admin_menu = array(
    'title' => 'WP Ultimate Exercise ' . __('Settings', 'wp-ultimate-exercise'),
    'logo'  => WPUltimateExercise::get()->coreUrl . '/img/icon_100.png',
    'menus' => array(
//=-=-=-=-=-=-= RECIPE TEMPLATE =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Template', 'wp-ultimate-exercise'),
            'name' => 'exercise_template',
            'icon' => 'font-awesome:fa-picture-o',
            'menus' => array(
                array(
                    'title' => __('Template Editor', 'wp-ultimate-exercise'),
                    'name' => 'exercise_template_template_editor_menu',
                    'controls' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_template_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Template Editor', 'wp-ultimate-exercise'),
                            'name' => 'exercise_template_editor',
                            'fields' => array(
                                array(
                                    'type' => 'html',
                                    'name' => $template_editor_button,
                                    'binding' => array(
                                        'field'    => '',
                                        'function' => 'wpuep_admin_template_editor',
                                    ),
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_template_editor_exercise',
                                    'label' => __('Preview Exercise', 'wp-ultimate-exercise'),
                                    'description' => __( 'This exercise will be used for the preview in the editor.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpuep_admin_template_editor_exercise',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '{{first}}',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Default Templates', 'wp-ultimate-exercise'),
                            'name' => 'exercise_templates',
                            'fields' => array(
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_template_exercise_template',
                                    'label' => __('Exercise Template', 'wp-ultimate-exercise'),
                                    'description' => __( 'The default template to use for exercises.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpuep_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '0',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_template_print_template',
                                    'label' => __('Print Template', 'wp-ultimate-exercise'),
                                    'description' => __( 'The default template to use for printed exercises.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpuep_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '1',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_template_exercisegrid_template',
                                    'label' => __('Exercise Egrid Template', 'wp-ultimate-exercise'),
                                    'description' => __( 'The default template to use for exercises in the Exercise Egrid.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpuep_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '2',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_template_feed_template',
                                    'label' => __('RSS Feed Template', 'wp-ultimate-exercise'),
                                    'description' => __( 'The default template to use for RSS feeds.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpuep_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '99',
                                    ),
                                    'validation' => 'required',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Exercise Box', 'wp-ultimate-exercise'),
                    'name' => 'exercise_template_exercise_box_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Functionality', 'wp-ultimate-exercise'),
                            'name' => 'section_functionality',
                            'fields' => array(
                                array(
                                    'type' => 'textbox',
                                    'name' => 'print_tooltip_text',
                                    'label' => __('Print Button Tooltip', 'wp-ultimate-exercise'),
                                    'description' => __('Text to show when someone hovers over the button.', 'wp-ultimate-exercise'),
                                    'default' => __('Print Exercise', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_adjustable_servings',
                                    'label' => __('Adjustable Servings', 'wp-ultimate-exercise'),
                                    'description' => __( 'Allow users to dynamically adjust the servings of exercises.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_adjustable_servings_fractions',
                                    'label' => __('Use Fractions', 'wp-ultimate-exercise'),
                                    'description' => __( "Use fractions after adjusting, even if the original quantity wasn't one.", 'wp-ultimate-exercise' ),
                                    'default' => '0',
                                ),
                                array(
                                    'type' => 'slider',
                                    'name' => 'exercise_default_servings',
                                    'label' => __('Default Servings', 'wp-ultimate-exercise'),
                                    'description' => __('Default number of servings to use when none specified.', 'wp-ultimate-exercise'),
                                    'min' => '1',
                                    'max' => '10',
                                    'step' => '1',
                                    'default' => '4',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_linkback',
                                    'label' => __('Link to plugin', 'wp-ultimate-exercise'),
                                    'description' => __( 'Show a link to the plugin website as a little thank you.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Images', 'wp-ultimate-exercise'),
                            'name' => 'section_exercise_images',
                            'fields' => array(
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_images_clickable',
                                    'label' => __('Clickable Images', 'wp-ultimate-exercise'),
                                    'description' => __( 'Best used in combination with a lightbox plugin.', 'wp-ultimate-exercise' ),
                                    'default' => '',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_image_title',
                                    'label' => __('Exercise Image Title', 'wp-ultimate-exercise'),
                                    'description' => __( 'Title tag to be used for the exercise image.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        array(
                                            'value' => 'attachment',
                                            'label' => __('Use media attachment title', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'exercise_title',
                                            'label' => __('Use exercise title', 'wp-ultimate-exercise'),
                                        ),
                                    ),
                                    'default' => array(
                                        'attachment',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_instruction_images_title',
                                    'label' => __('Instruction Images Title', 'wp-ultimate-exercise'),
                                    'description' => __( 'Title tag to be used for instruction images.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        array(
                                            'value' => 'attachment',
                                            'label' => __('Use media attachment title', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'instruction',
                                            'label' => __('Use instruction text', 'wp-ultimate-exercise'),
                                        ),
                                    ),
                                    'default' => array(
                                        'attachment',
                                    ),
                                    'validation' => 'required',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Ingredients1', 'wp-ultimate-exercise'),
                            'name' => 'section_ingredients',
                            'fields' => array(
                                array(
                                    'type' => 'notebox',
                                    'name' => 'exercise_ingredient_links_premium_not_installed',
                                    'label' => 'WP Ultimate Exercise Premium',
                                    'description' => __('Custom links are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                                    'status' => 'warning',
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpuep_admin_premium_not_installed',
                                    ),
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_ingredient_links',
                                    'label' => __('Ingredient Links', 'wp-ultimate-exercise'),
                                    'description' => __( 'Links to be used in the ingredient list.', 'wp-ultimate-exercise' ),
                                    'items' => array(
                                        array(
                                            'value' => 'disabled',
                                            'label' => __('No ingredient links', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'archive',
                                            'label' => __('Only link to ingredient archive page', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'archive_custom',
                                            'label' => __('Custom link if provided, otherwise archive page', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'custom',
                                            'label' => __('Custom links if provided, otherwise no link', 'wp-ultimate-exercise'),
                                        ),
                                    ),
                                    'default' => array(
                                        'archive_custom',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_ingredient_custom_links_target',
                                    'label' => __('Custom Links', 'wp-ultimate-exercise'),
                                    'description' => __( 'Custom links can be added on the ', 'wp-ultimate-exercise' ) . ' <a href="'.admin_url('edit-tags.php?taxonomy=ingredient&post_type=exercise').'" target="_blank">' . __( 'ingredients page', 'wp-ultimate-exercise' ) . '</a>.',
                                    'items' => array(
                                        array(
                                            'value' => '_self',
                                            'label' => __('Open in the current tab/window', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => '_blank',
                                            'label' => __('Open in a new tab/window', 'wp-ultimate-exercise'),
                                        ),
                                    ),
                                    'default' => array(
                                        '_blank',
                                    ),
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpuep_admin_premium_installed',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_ingredient_custom_links_nofollow',
                                    'label' => __('Use Nofollow', 'wp-ultimate-exercise'),
                                    'description' => __( 'Add the nofollow attribute to custom ingredient links.', 'wp-ultimate-exercise' ),
                                    'default' => '0',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Print Version', 'wp-ultimate-exercise'),
                    'name' => 'exercise_template_print_template_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Title', 'wp-ultimate-exercise'),
                            'name' => 'print_template_section_title',
                            'fields' => array(
                                array(
                                    'type' => 'textbox',
                                    'name' => 'print_template_title_text',
                                    'label' => __('Title Text', 'wp-ultimate-exercise'),
                                    'description' => __('Title of the new webpage that opens.', 'wp-ultimate-exercise'),
                                    'default' => get_bloginfo('name'),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('RSS Feed', 'wp-ultimate-exercise'),
                    'name' => 'exercise_template_rss_feed_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Exercise Posts', 'wp-ultimate-exercise'),
                            'name' => 'exercise_template_rss_feed_exercise_posts',
                            'fields' => array(
                                array(
                                    'type' => 'select',
                                    'name' => 'exercise_rss_feed_display',
                                    'label' => __('Display', 'wp-ultimate-exercise'),
                                    'items' => array(
                                        array(
                                            'value' => 'excerpt',
                                            'label' => __('Only the excerpt', 'wp-ultimate-exercise'),
                                        ),
                                        array(
                                            'value' => 'full',
                                            'label' => __('The entire exercise', 'wp-ultimate-exercise'),
                                        ),
                                    ),
                                    'default' => array(
                                        'full',
                                    ),
                                    'validation' => 'required',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Exercise Shortcode', 'wp-ultimate-exercise'),
                            'name' => 'exercise_template_rss_feed_shortcode',
                            'fields' => array(
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_rss_feed_shortcode',
                                    'label' => __('Display', 'wp-ultimate-exercise'),
                                    'description' => __( 'Output the exercise shortcode in RSS feeds?', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Advanced', 'wp-ultimate-exercise'),
                    'name' => 'exercise_template_advanced_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Mobile', 'wp-ultimate-exercise'),
                            'name' => 'exercise_template_advanced',
                            'fields' => array(
                                array(
                                    'type' => 'slider',
                                    'name' => 'exercise_template_responsive_breakpoint',
                                    'label' => __('Responsive Breakpoint', 'wp-ultimate-exercise'),
                                    'description' => __( 'The width of the exercise box at which will be switched to the mobile version.', 'wp-ultimate-exercise' ),
                                    'min' => '10',
                                    'max' => '1000',
                                    'step' => '1',
                                    'default' => '550',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => 'CSS',
                            'name' => 'exercise_template_advanced_styling',
                            'fields' => array(
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_template_force_style',
                                    'label' => __('Force CSS style', 'wp-ultimate-exercise'),
                                    'description' => __( 'This ensures maximum compatibility with most themes. Can be disabled for advanced usage.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_template_font_awesome',
                                    'label' => __('Include Font Awesome', 'wp-ultimate-exercise'),
                                    'description' => __( 'You can disable this if your theme already includes Font Awesome.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_template_inline_css',
                                    'label' => __('Output Inline CSS', 'wp-ultimate-exercise'),
                                    'description' => __( 'When disabled the Template Editor will not output any inline CSS.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_template_base_css',
                                    'label' => __('Use base CSS', 'wp-ultimate-exercise'),
                                    'description' => __( 'When disabled the base CSS file will not be included.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE ARCHIVE =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Archive', 'wp-ultimate-exercise'),
            'name' => 'exercise_archive',
            'icon' => 'font-awesome:fa-archive',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Exercise Archive Pages', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_archive_pages',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'exercise_archive_display',
                            'label' => __('Display', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'excerpt',
                                    'label' => __('Only the excerpt', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'full',
                                    'label' => __('The entire exercise', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'full',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'exercise_theme_thumbnail',
                            'label' => __('Display Thumbnail', 'wp-ultimate-exercise'),
                            'description' => __( 'Thumbnail position depends on the theme you use', 'wp-ultimate-exercise' ) . '.',
                            'items' => array(
                                array(
                                    'value' => 'never',
                                    'label' => __('Never', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'archive',
                                    'label' => __('Only on archive pages', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'exercise',
                                    'label' => __('Only on exercise pages', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'always',
                                    'label' => __('Always', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'archive',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_slug',
                            'label' => __('Slug', 'wp-ultimate-exercise'),
                            'default' => 'exercise',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'exercise_slug_preview' . $sitehash,
                            'binding' => array(
                                'field'    => 'exercise_slug',
                                'function' => 'wpuep_admin_exercise_slug_preview',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_slug_notebox',
                            'label' => __('404 error/page not found?', 'wp-ultimate-exercise'),
                            'description' => __('Try', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexercise.com/docs/404-page-found/" target="_blank">'.__('flushing your permalinks', 'wp-ultimate-exercise').'</a>.',
                            'status' => 'info',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_archive_advanced',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_archive_disabled',
                            'label' => __('Disable Exercise Archive', 'wp-ultimate-exercise'),
                            'description' => __( 'Make sure to flush your permalinks after changing this setting.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= PARTNER INTEGRATIONS =-=-=-=-=-=-=
        array(
            'title' => __('Partner Integrations', 'wp-ultimate-exercise'),
            'name' => 'partners_integrations',
            'icon' => 'font-awesome:fa-link',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => 'Chicory',
                    'name' => 'section_integrations_chicory',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'partners_integrations_chicory_enable',
                            'label' => 'Chicory',
                            'description' => __( 'Connect your exercises to leading online grocers with the Chicory "get ingredients" button.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'checkbox',
                            'name' => 'partners_integrations_chicory_terms',
                            'label' => '',
                            'items' => array(
                                array(
                                    'value' => '1',
                                    'label' => __( "I agree to Chicory's", 'wp-ultimate-exercise') . '</label> <a href="http://chicoryapp.com/terms/" target="_blank">' . __( 'terms of use', 'wp-ultimate-exercise') . '</a><label>',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'partners_integrations_chicory_agree',
                            'label' => 'Chicory',
                            'description' => __( "You need to agree to Chicory's terms of use if you want the button to show up.", 'wp-ultimate-exercise'),
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => 'partners_integrations_chicory_enable,partners_integrations_chicory_terms',
                                'function' => 'wpuep_admin_chicory_terms',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_integrations_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'partners_integrations_bigoven_enable',
                            'label' => 'BigOven',
                            'description' => __( 'Show save exercise to BigOven button.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'partners_integrations_foodfanatic_enable',
                            'label' => 'Food Fanatic',
                            'description' => __( 'Show save exercise to Food Fanatic button.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'partners_integrations_yummly_enable',
                            'label' => 'Yummly',
                            'description' => __( 'Show the Yum button.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE SHARING =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Sharing', 'wp-ultimate-exercise'),
            'name' => 'exercise_sharing',
            'icon' => 'font-awesome:fa-thumbs-o-up',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_sharing_enable',
                            'label' => __('Enable Sharing', 'wp-ultimate-exercise'),
                            'description' => __( 'Show sharing buttons.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Language', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_sharing_language',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_sharing_language_options',
                            'description' => __('Available languages can be found over here:', 'wp-ultimate-exercise') . ' <a href="https://developers.facebook.com/docs/internationalization/" target="_blank">Facebook</a>, <a href="https://dev.twitter.com/overview/general/adding-international-support-to-your-apps" target="_blank">Twitter</a>, <a href="https://developers.google.com/+/web/+1button/#available-languages" target="_blank">Google+</a>',
                            'status' => 'info',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_sharing_language_facebook',
                            'label' => 'Facebook',
                            'default' => 'en_US',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_sharing_language_twitter',
                            'label' => 'Twitter',
                            'default' => 'en',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_sharing_language_google',
                            'label' => 'Google+',
                            'default' => 'en-US',
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Default text to share', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_sharing_default_text',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_sharing_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_sharing_codes',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Use %title% as a placeholder for the exercise title.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_sharing_twitter',
                            'label' => 'Twitter',
                            'default' => '%title% - Powered by @WPUltimExercise',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'exercise_sharing_pinterest',
                            'label' => 'Pinterest',
                            'default' => '%title% - Powered by @ultimateexercise',
                            'validation' => 'required',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE FIELDS =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Fields', 'wp-ultimate-exercise'),
            'name' => 'exercise_fields',
            'icon' => 'font-awesome:fa-edit',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'exercise_tags_premium_not_installed',
                    'label' => 'WP Ultimate Exercise Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpuep_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Custom Exercise Fields', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_fields_custom',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => $custom_fields_button,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_manage_fields',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_fields_advanced',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_fields_in_user_submission',
                            'label' => __('Show Custom Fields in User Submission form', 'wp-ultimate-exercise'),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'exercise_fields_user_submission',
                            'label' => __( 'Fields', 'wp-ultimate-exercise' ),
                            'description' => __( 'Fields to show in the User Submission form.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_custom_fields',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '{{all}}',
                            ),
                            'dependency' => array(
                                'field' => 'exercise_fields_in_user_submission',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE TAGS =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Tags', 'wp-ultimate-exercise'),
            'name' => 'exercise_tags',
            'icon' => 'font-awesome:fa-tags',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Custom Exercise Tags', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_tags_custom',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'exercise_tags_manage_custom' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_manage_tags',
                            ),
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'exercise_tags_hide_in_exercise',
                            'label' => __('Hide Custom Tags', 'wp-ultimate-exercise'),
                            'description' => __( 'Do not show these tags in the Exercise Box.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_exercise_tags',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('WordPress Categories & Tags', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_tags_wordpress',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_use_wp_categories',
                            'label' => __('Use Categories and Tags', 'wp-ultimate-exercise'),
                            'description' => __( 'Use the default WP Categories and Tags to organize your exercises.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_show_in_archives',
                            'label' => __('Show Exercies in Archives', 'wp-ultimate-exercise'),
                            'description' => __( 'Show exercises in the WP Categories and Tags archives.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_tags_advanced',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_tags_cu_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_tags_show_in_exercise_info',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Categories will only show up as tags in the exercise if they have a parent category. For example: a "Courses" parent category with "Main Dish" and "Dessert" as child categories assigned to your exercises.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_show_in_exercise',
                            'label' => __('Show Categories in Exercise', 'wp-ultimate-exercise'),
                            'description' => __( 'Use WP categories as if they are tags for their parent category.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_filter_categories',
                            'label' => __('Show Categories Filter', 'wp-ultimate-exercise'),
                            'description' => __( 'Users can see the categories when filtering.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_filter_tags',
                            'label' => __('Show Tags Filter', 'wp-ultimate-exercise'),
                            'description' => __( 'Users can see the tags when filtering.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER RATINGS =-=-=-=-=-=-=
        array(
            'title' => __('User Ratings', 'wp-ultimate-exercise'),
            'name' => 'user_ratings',
            'icon' => 'font-awesome:fa-star-half-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_user_ratings_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'user_ratings_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_ratings_enable',
                            'label' => __('User Ratings', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('Disabled', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'users_only',
                                    'label' => __('Only logged in users can rate exercises', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'everyone',
                                    'label' => __('Everyone can rate exercises', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'everyone',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_ratings_vote_attention',
                            'label' => __('Show indicator', 'wp-ultimate-exercise'),
                            'description' => __( 'Attract attention to the possibility to vote.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'user_ratings_minimum_votes',
                            'label' => __('Minimum # Votes', 'wp-ultimate-exercise'),
                            'description' => __('Minimum number of votes needed before sharing the rating as metadata used by Google and other search engines.', 'wp-ultimate-exercise'),
                            'min' => '1',
                            'max' => '50',
                            'step' => '1',
                            'default' => '1',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_ratings_rounding',
                            'label' => __('Rounding Ratings', 'wp-ultimate-exercise'),
                            'description' => __( 'Round the ratings presented in the metadata.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('Disabled', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'half',
                                    'label' => __('Round up to nearest half', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'integer',
                                    'label' => __('Round up to nearest integer', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'disabled',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= UNIT CONVERSION =-=-=-=-=-=-=
        array(
            'title' => __('Unit Conversion', 'wp-ultimate-exercise'),
            'name' => 'unit_conversion',
            'icon' => 'font-awesome:fa-exchange',
            'menus' => array(
                array(
                    'title' => __('General Settings', 'wp-ultimate-exercise'),
                    'name' => 'unit_conversion_general_settings',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('General', 'wp-ultimate-exercise'),
                            'name' => 'section_unit_conversion_general',
                            'fields' => array(
                                array(
                                    'type' => 'notebox',
                                    'name' => 'unit_conversion_premium_not_installed',
                                    'label' => 'WP Ultimate Exercise Premium',
                                    'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                                    'status' => 'warning',
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpuep_admin_premium_not_installed',
                                    ),
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'exercise_adjustable_units',
                                    'label' => __('Allow Conversion', 'wp-ultimate-exercise'),
                                    'description' => __( 'Allow your visitors to switch between Imperial and Metric units.', 'wp-ultimate-exercise' ),
                                    'default' => '1',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Unit Systems', 'wp-ultimate-exercise'),
                    'name' => 'unit_conversion_unit_systems',
                    'controls' => $unit_systems_admin, //TODO Universal units
                ),
                array(
                    'title' => __('Unit Aliases', 'wp-ultimate-exercise'),
                    'name' => 'unit_conversion_unit_aliases',
                    'controls' => $conversion_units_admin
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
        array(
            'title' => __('Exercise Egrid', 'wp-ultimate-exercise'),
            'name' => 'exercise_grid',
            'icon' => 'font-awesome:fa-th',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_grid_general',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'exercise_grid_manage' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_manage_exercise_grid',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Backwards Compatibility', 'wp-ultimate-exercise'),
                    'name' => 'section_exercise_grid_backwards_compatibility',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_grid_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_grid_shortcode',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Use the [ultimate-exercise-grid] shortcode to display the Exercise Egrid.', 'wp-ultimate-exercise') . ' '. __('The shortcode can be added to any page or post.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'exercise_grid_reset_terms' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_reset_exercise_grid_terms',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'exercise_grid_shortcode',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('The Multi-Select, Match All and Parents match Children setting has been moved to the shortcode and can now be changed per Exercise Egrid.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER SUBMISSION =-=-=-=-=-=-=
        array(
            'title' => __('User Submission', 'wp-ultimate-exercise'),
            'name' => 'user_submission',
            'icon' => 'font-awesome:fa-user',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_user_submission_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'user_submission_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'user_submission_shortcode',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Use the following shortcode to display the front-end form:', 'wp-ultimate-exercise') . ' [ultimate-exercise-submissions]. '. __('The shortcode can be added to any page or post.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_submission_enable',
                            'label' => __('Allow submissions from', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'off',
                                    'label' => __('Nobody', 'wp-ultimate-exercise') . ' (' . __('disabled', 'wp-ultimate-exercise') . ')',
                                ),
                                array(
                                    'value' => 'guests',
                                    'label' => __('Guests and registered users', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'registered',
                                    'label' => __('Registered users only', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'guests',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submission_preview_button',
                            'label' => __('Allow visitors to preview their submission', 'wp-ultimate-exercise'),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'codeeditor',
                            'name' => 'user_submission_submitted_text',
                            'label' => __('After-submission text', 'wp-ultimate-exercise'),
                            'description' => __('Text to be shown after a user has submitted a exercise.', 'wp-ultimate-exercise') . ' ' . __( 'HTML can be used.', 'wp-ultimate-exercise' ),
                            'theme' => 'github',
                            'mode' => 'html',
                            'default' => __( 'Exercise submitted! Thank you, your exercise is now awaiting moderation.', 'wp-ultimate-exercise' ),
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'user_submission_required_fields',
                            'label' => __('Required Fields', 'wp-ultimate-exercise'),
                            'description' => __( 'Fields that are required to fill in.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_user_submission_required_fields',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'title',
                                'exercise-author',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Security Question', 'wp-ultimate-exercise'),
                    'name' => 'section_user_submission_security_question',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submissions_use_security_question',
                            'label' => __('Security Question', 'wp-ultimate-exercise'),
                            'description' => __( 'Use a security question to prevent spam.', 'wp-ultimate-exercise' ),
                            'default' => '',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'user_submissions_security_question',
                            'label' => __('Question', 'wp-ultimate-exercise'),
                            'description' => __( 'The question your visitors have to answer.', 'wp-ultimate-exercise' ),
                            'default' => '4 + 7 =',
                            'dependency' => array(
                                'field' => 'user_submissions_use_security_question',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'user_submissions_security_answer',
                            'label' => __('Answer', 'wp-ultimate-exercise'),
                            'description' => __( 'The correct answer to that question.', 'wp-ultimate-exercise' ),
                            'default' => '11',
                            'dependency' => array(
                                'field' => 'user_submissions_use_security_question',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Exercise Tags', 'wp-ultimate-exercise'),
                    'name' => 'section_user_submission_exercise_tags',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_user_submissions_multiselect',
                            'label' => __('Allow Multiselect', 'wp-ultimate-exercise'),
                            'description' => __( 'Allow users to select multiple terms per category.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_user_submissions_categories',
                            'label' => __('User Submitted Categories', 'wp-ultimate-exercise'),
                            'description' => __( 'Allow users to assign categories when submitting exercises.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_tags_user_submissions_tags',
                            'label' => __('User Submitted Tags', 'wp-ultimate-exercise'),
                            'description' => __( 'Allow users to assign tags when submitting exercises.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'user_submission_hide_tags',
                            'label' => __('Hide Custom Tags', 'wp-ultimate-exercise'),
                            'description' => __( 'Hide these tags on the user submission page.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_exercise_tags',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'user_submission_hide_category_terms',
                            'label' => __('Hide Category Terms', 'wp-ultimate-exercise'),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_category_terms',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'user_submission_hide_tag_terms',
                            'label' => __('Hide Tag Terms', 'wp-ultimate-exercise'),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_tag_terms',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Approval Rules', 'wp-ultimate-exercise'),
                    'name' => 'section_user_submission_approval_rules',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'user_submission_approve',
                            'label' => __('Auto approve', 'wp-ultimate-exercise'),
                            'description' => __('Publish exercise immediately on submission.', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'off',
                                    'label' => __('Nobody', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'guests',
                                    'label' => __('Guests and registered users', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'registered',
                                    'label' => __('Registered users only', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'off',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'user_submission_approve_users',
                            'label' => __('Auto approve specific users', 'wp-ultimate-exercise'),
                            'description' => __('Publish exercises from these users immediately on submission.', 'wp-ultimate-exercise'),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_users',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'user_submissions_approve_role',
                            'label' => __('Auto approve users with role or capability', 'wp-ultimate-exercise'),
                            'description' => __( 'Publish exercises from these users immediately on submission.', 'wp-ultimate-exercise' ),
                            'default' => '',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-exercise'),
                    'name' => 'section_user_submission_advanced',
                    'fields' => array(
//                        array(
//                            'type' => 'toggle',
//                            'name' => 'user_submission_css',
//                            'label' => __('Submission form CSS', 'wp-ultimate-exercise'),
//                            'description' => __( 'Add basic CSS styles to the frontend form.', 'wp-ultimate-exercise' ),
//                            'default' => '1',
//                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submission_ingredient_list',
                            'label' => __('Can only select existing ingredients', 'wp-ultimate-exercise'),
                            'description' => __( 'When enabled visitors will only be able to select from a list of existing ingredients.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submission_email_admin',
                            'label' => __('Email administrator', 'wp-ultimate-exercise'),
                            'description' => __( 'Send an email notification when a new exercise is submitted.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submission_restrict_media_access',
                            'label' => __('Restrict Media Library Access', 'wp-ultimate-exercise'),
                            'description' => __( 'Only show media library for editors and up', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_submission_use_media_manager',
                            'label' => __('Use Media Manager', 'wp-ultimate-exercise'),
                            'description' => __( 'Let logged in users use the Media Manager to upload images', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER MENUS =-=-=-=-=-=-=
        array(
            'title' => __('User Menus', 'wp-ultimate-exercise'),
            'name' => 'user_menus',
            'icon' => 'font-awesome:fa-list-alt',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_user_menus_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'user_menus_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'user_menus_shortcode',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Use the following shortcode to display the front-end form:', 'wp-ultimate-exercise') . ' [ultimate-exercise-user-menus]. '. __('The shortcode can be added to any page or post.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_enable',
                            'label' => __('Enable user menus for', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'off',
                                    'label' => __('Nobody', 'wp-ultimate-exercise') . ' (' . __('disabled', 'wp-ultimate-exercise') . ')',
                                ),
                                array(
                                    'value' => 'guests',
                                    'label' => __('Guests and registered users', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'registered',
                                    'label' => __('Registered users only', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'guests',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_enable_save',
                            'label' => __('Enable user menus save function for', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'off',
                                    'label' => __('Nobody', 'wp-ultimate-exercise') . ' (' . __('disabled', 'wp-ultimate-exercise') . ')',
                                ),
                                array(
                                    'value' => 'guests',
                                    'label' => __('Guests and registered users', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'registered',
                                    'label' => __('Registered users only', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'guests',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_enable_delete',
                            'label' => __('Enable Delete Button', 'wp-ultimate-exercise'),
                            'description' => __( 'Users can delete their own saved menus.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'user_menus_default_servings',
                            'label' => __('Default Servings', 'wp-ultimate-exercise'),
                            'min' => '1',
                            'max' => '10',
                            'step' => '1',
                            'default' => '4',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_ingredient_notes',
                            'label' => __('Show Ingredient Notes', 'wp-ultimate-exercise'),
                            'description' => __( 'Ingredients with different notes will be handled as different ingredients.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_checkboxes',
                            'label' => __('Show Checkboxes', 'wp-ultimate-exercise'),
                            'description' => __( 'Show checkboxes in the shopping list to cross items of the list.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Add to Shopping List Button', 'wp-ultimate-exercise'),
                    'name' => 'section_user_menus_add_to_shopping_list',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_add_to_shopping_list',
                            'label' => __('Show add to shopping list button for', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'off',
                                    'label' => __('Nobody', 'wp-ultimate-exercise') . ' (' . __('disabled', 'wp-ultimate-exercise') . ')',
                                ),
                                array(
                                    'value' => 'guests',
                                    'label' => __('Guests and registered users', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'registered',
                                    'label' => __('Registered users only', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'off',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'add_to_shopping_list_tooltip_text',
                            'label' => __('Add to Shopping List Button Tooltip', 'wp-ultimate-exercise'),
                            'description' => __('Text to show when someone hovers over the button.', 'wp-ultimate-exercise'),
                            'default' => __('Add to Shopping List', 'wp-ultimate-exercise'),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'added_to_shopping_list_tooltip_text',
                            'label' => __('Tooltip after adding', 'wp-ultimate-exercise'),
                            'description' => __('Text to show when someone hovers over the button.', 'wp-ultimate-exercise'),
                            'default' => __('This exercise is in your Shopping List', 'wp-ultimate-exercise'),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'add_to_shopping_list_tooltip_html',
                            'description' => __('You can use HTML to link to the relevant page in the tooltips:', 'wp-ultimate-exercise') . '<br/>' . htmlspecialchars( '<a href="/shopping-list/">Shopping List</a>' ),
                            'status' => 'info',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Unit Systems', 'wp-ultimate-exercise'),
                    'name' => 'section_user_menus_unit_systems',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_consolidate_ingredients',
                            'label' => __('Consolidate Ingredients', 'wp-ultimate-exercise'),
                            'description' => __( 'Convert units to be able to consolidate ingredients into 1 line.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_dynamic_unit_system',
                            'label' => __('Unit System Dropdown', 'wp-ultimate-exercise'),
                            'description' => __( 'Users can use a dropdown to select the unit system they want.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        // Dynamic Unit System Selection
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_default_unit_system',
                            'label' => __('Default Unit System', 'wp-ultimate-exercise'),
                            'description' => __( 'Unit system to use for the shopping list feature.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_get_unit_systems',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '0',
                            ),
                            'validation' => 'required',
                            'dependency' => array(
                                'field' => 'user_menus_dynamic_unit_system',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                        // Fixed Unit Systems in table
                        array(
                            'type' => 'slider',
                            'name' => 'user_menus_static_nbr_systems',
                            'label' => __('Number of Systems', 'wp-ultimate-exercise'),
                            'description' => __('Number of unit systems displayed in the shopping list.', 'wp-ultimate-exercise'),
                            'min' => '1',
                            'max' => '3',
                            'step' => '1',
                            'default' => '1',
                            'dependency' => array(
                                'field' => 'user_menus_dynamic_unit_system',
                                'function' => 'evp_dep_boolean_inverse',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_static_system_1',
                            'label' => __('Unit System', 'wp-ultimate-exercise') . ' 1',
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_get_unit_systems',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '0',
                            ),
                            'validation' => 'required',
                            'dependency' => array(
                                'field' => 'user_menus_dynamic_unit_system',
                                'function' => 'evp_dep_boolean_inverse',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_static_system_2',
                            'label' => __('Unit System', 'wp-ultimate-exercise') . ' 2',
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_get_unit_systems',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '0',
                            ),
                            'validation' => 'required',
                            'dependency' => array(
                                'field' => 'user_menus_dynamic_unit_system',
                                'function' => 'evp_dep_boolean_inverse',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_static_system_3',
                            'label' => __('Unit System', 'wp-ultimate-exercise') . ' 3',
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_get_unit_systems',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '0',
                            ),
                            'validation' => 'required',
                            'dependency' => array(
                                'field' => 'user_menus_dynamic_unit_system',
                                'function' => 'evp_dep_boolean_inverse',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Print Options', 'wp-ultimate-exercise'),
                    'name' => 'section_user_menus_print',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_enable_print_list',
                            'label' => __('Print Shopping List Button', 'wp-ultimate-exercise'),
                            'description' => __( 'Show a button to print the shopping list.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_print_with_menu',
                            'label' => __('Include Exercise List', 'wp-ultimate-exercise'),
                            'description' => __( 'Include of list of the exercises in the menu when printing the shopping list.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_menus_enable_print_menu',
                            'label' => __('Print Menu Button', 'wp-ultimate-exercise'),
                            'description' => __( 'Show a button to print the entire menu (Shopping list and exercises).', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_menus_exercise_print_template',
                            'label' => __('Print Template', 'wp-ultimate-exercise'),
                            'description' => __( 'The default template to use for printed exercises.', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_templates',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '1',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Slug', 'wp-ultimate-exercise'),
                    'name' => 'section_user_emenus_slug',
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'user_emenus_slug',
                            'label' => __('Slug', 'wp-ultimate-exercise'),
                            'default' => 'menu',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'user_emenus_slug_preview' . $sitehash,
                            'binding' => array(
                                'field'    => 'user_emenus_slug',
                                'function' => 'wpuep_admin_user_emenus_slug_preview',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'user_emenus_slug_notebox',
                            'label' => __('404 error/page not found?', 'wp-ultimate-exercise'),
                            'description' => __('Try', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexercise.com/docs/404-page-found/" target="_blank">'.__('flushing your permalinks', 'wp-ultimate-exercise').'</a>.',
                            'status' => 'info',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= FAVORITE RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Favorite Exercies', 'wp-ultimate-exercise'),
            'name' => 'favorite_exercises',
            'icon' => 'font-awesome:fa-heart',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_favorite_exercises_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'favorite_exercises_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'favorite_exercises_shortcode',
                            'label' => __('Important', 'wp-ultimate-exercise'),
                            'description' => __('Use the following shortcode to display the list of favorite exercises:', 'wp-ultimate-exercise') . ' [ultimate-exercise-favorites]. '. __('The shortcode can be added to any page or post.', 'wp-ultimate-exercise'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'favorite_exercises_enabled',
                            'label' => __('Enable Button', 'wp-ultimate-exercise'),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'favorite_exercises_tooltip_text',
                            'label' => __('Favorite Exercies Button Tooltip', 'wp-ultimate-exercise'),
                            'description' => __('Text to show when someone hovers over the button.', 'wp-ultimate-exercise'),
                            'default' => __('Add to your Favorite Exercies', 'wp-ultimate-exercise'),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'favorited_exercises_tooltip_text',
                            'label' => __('Tooltip after adding', 'wp-ultimate-exercise'),
                            'description' => __('Text to show when someone hovers over the button.', 'wp-ultimate-exercise'),
                            'default' => __('This exercise is in your Favorite Exercies', 'wp-ultimate-exercise'),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'favorite_exercises_tooltip_html',
                            'description' => __('You can use HTML to link to the relevant page in the tooltips:', 'wp-ultimate-exercise') . '<br/>' . htmlspecialchars( '<a href="/favorite-exercises/">Favorite Exercies</a>' ),
                            'status' => 'info',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= NUTRITIONAL INFORMATION =-=-=-=-=-=-=
        array(
            'title' => __('Nutritional Information', 'wp-ultimate-exercise'),
            'name' => 'nutritional_information',
            'icon' => 'font-awesome:fa-tasks',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-exercise'),
                    'name' => 'section_nutritional_information_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'nutritional_information_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'nutritional_information_notice',
                            'label' => __('Show Notice', 'wp-ultimate-exercise'),
                            'description' => __( 'Show notice to update Nutritional Information after updating a exercise.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'nutritional_information_capability',
                            'label' => __('Required role or capability', 'wp-ultimate-exercise'),
                            'description' => __( 'Only users with this role or capability can edit the Nutritional Information.', 'wp-ultimate-exercise' ),
                            'default' => 'manage_options',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= IMPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Import Exercies', 'wp-ultimate-exercise'),
            'name' => 'import_exercises',
            'icon' => 'font-awesome:fa-upload',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Import Options', 'wp-ultimate-exercise'),
                    'name' => 'secion_import_exercises_options',
                    'fields' => array(
                        array(
                            'type' => 'textarea',
                            'name' => 'import_exercises_generic_units',
                            'label' => __('Generic ingredient units', 'wp-ultimate-exercise'),
                            'description' => __('Generic ingredient units to recognize while importing. Separate with a ;', 'wp-ultimate-exercise'),
                            'default' => $defaults['import_exercises_generic_units'],
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'import_exercises_order',
                            'label' => __('Import order', 'wp-ultimate-exercise'),
                            'items' => array(
                                array(
                                    'value' => 'ASC',
                                    'label' => __('Import oldest exercises first', 'wp-ultimate-exercise'),
                                ),
                                array(
                                    'value' => 'DESC',
                                    'label' => __('Import latest exercises first', 'wp-ultimate-exercise'),
                                ),
                            ),
                            'default' => array(
                                'ASC',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Import From', 'wp-ultimate-exercise'),
                    'name' => 'secion_import_exercises_plugins',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'import_exercises_premium_not_installed',
                            'label' => 'WP Ultimate Exercise Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpuep_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_easyexercise' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_easyexercise',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_exercisecard' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_exercisecard',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_recipress' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_exerciseress',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_ziplist' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_ziplist',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_xml' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_xml',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'import_exercises_fdx' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_admin_import_fdx',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= EXPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Export Exercies', 'wp-ultimate-exercise'),
            'name' => 'export_exercises',
            'icon' => 'font-awesome:fa-download',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'export_exercises_premium_not_installed',
                    'label' => 'WP Ultimate Exercise Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-exercise') . ' <a href="http://www.wpultimateexerciseplugin.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpuep_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => 'export_exercises_xml' . $sitehash,
                    'binding' => array(
                        'field'    => '',
                        'function' => 'wpuep_admin_export_xml',
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= ADVANCED =-=-=-=-=-=-=
        array(
            'title' => __('Advanced', 'wp-ultimate-exercise'),
            'name' => 'advanced',
            'icon' => 'font-awesome:fa-wrench',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Exercise', 'wp-ultimate-exercise'),
                    'name' => 'advanced_section_exercise',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'advanced_reset_demo_exercise' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_reset_demo_exercise',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'exercise_as_posts',
                            'label' => __('Exercies act as posts', 'wp-ultimate-exercise'),
                            'description' => __( 'Exercies act like normal posts. For example: they show up on your front page.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'show_exercises_in_posts',
                            'label' => __('Exercies in admin posts', 'wp-ultimate-exercise'),
                            'description' => __( 'Show exercises in admin posts overview when acting as posts.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'remove_exercise_slug',
                            'label' => __('Remove exercise slug', 'wp-ultimate-exercise'),
                            'description' => __( 'Make sure your slugs are unique across posts, pages and exercises! Your archive page will still be available.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'output_yandex_metadata',
                            'label' => __('Use Yandex metadata', 'wp-ultimate-exercise'),
                            'description' => __( 'Add a resultPhoto meta field for Yandex.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Shortcode Editor', 'wp-ultimate-exercise'),
                    'name' => 'advanced_section_shortcode',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'advanced_reset_cache' . $sitehash,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpuep_reset_cache',
                            ),
                        ),
                        array(
                            'type' => 'multiselect',
                            'name' => 'shortcode_editor_post_types',
                            'label' => __('Show shortcode editor for', 'wp-ultimate-exercise'),
                            'description' => __( 'Where do you want to be able to insert exercises with the shortcode editor?', 'wp-ultimate-exercise' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpuep_admin_post_types',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '{{all}}',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Assets', 'wp-ultimate-exercise'),
                    'name' => 'advanced_section_assets',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'assets_use_minified',
                            'label' => __('Use minified assets', 'wp-ultimate-exercise'),
                            'description' => __( 'Use minified assets to improve page load speed.', 'wp-ultimate-exercise' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'assets_generate_minified',
                            'label' => __('Generate minified assets', 'wp-ultimate-exercise'),
                            'description' => __( 'Generate minified assets on the fly.', 'wp-ultimate-exercise' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'assets_generate_minified_dir',
                            'label' => __('Minified assets directory', 'wp-ultimate-exercise'),
                            'description' => __('Directory to generate the minified assets to. Should be writable.', 'wp-ultimate-exercise'),
                            'default' => '',
                            'dependency' => array(
                                'field' => 'assets_generate_minified',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= CUSTOM CODE =-=-=-=-=-=-=
        array(
            'title' => __('Custom Code', 'wp-ultimate-exercise'),
            'name' => 'custom_code',
            'icon' => 'font-awesome:fa-code',
            'controls' => array(
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_public_css',
                    'label' => __('Public CSS', 'wp-ultimate-exercise'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_print_css',
                    'label' => __('Print Exercise CSS', 'wp-ultimate-exercise'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_print_shoppinglist_css',
                    'label' => __('Print Shopping List CSS', 'wp-ultimate-exercise'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
            ),
        ),
    ),
);