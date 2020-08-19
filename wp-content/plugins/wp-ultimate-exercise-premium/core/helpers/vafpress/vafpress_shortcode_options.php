<?php
$shortcode_generator = array(
//=-=-=-=-=-=-= RECIPES =-=-=-=-=-=-=
    __( 'Exercies', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'random' => array(
                'title'   => __('Display a random exercise', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise id="random"]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
            'by_date' => array(
                'title'   => __('Select a exercise to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by date added', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Exercise', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_exercises_by_date',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a exercise to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by title', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Exercise', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_exercises_by_title',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE INDEX =-=-=-=-=-=-=
    __( 'Exercise Index', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'basic_index' => array(
                'title'   => __('Basic Exercise Index', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-index]',
                'attributes' => array(
                    array(
                        'type' => 'checkbox',
                        'name' => 'headers',
                        'label' => __('Show headers', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => '',
                            ),
                        ),
                    ),
                ),
            ),
            'extended_index' => array(
                'title'   => __('Extended Exercise Index', 'wp-ultimate-exercise') . ' (' . __('WP Ultimate Exercise Premium only', 'wp-ultimate-exercise'). ')',
                'code'    => '[ultimate-exercise-index]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'group_by',
                        'label' => __('Group by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'name',
                                'label' => __('Name', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'category',
                                'label' => __('Category', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'post_tag',
                                'label' => __('Tag', 'wp-ultimate-exercise'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'limit_author',
                        'label' => __('Limit Author', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'current_user',
                                'label' => __('Currently logged in user', 'wp-ultimate-exercise'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_authors',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'limit_by_tag',
                        'label' => __('Limit by', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_by_values',
                        'label' => __('Limit by values', 'wp-ultimate-exercise') . ' ' . __('(separate slugs with ;)', 'wp-ultimate-exercise'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'title',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'ASC',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_exercises',
                        'label' => __('Max number of exercises', 'wp-ultimate-exercise'),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= PREMIUM ONLY =-=-=-=-=-=-=
    '| ' . __( 'Premium only', 'wp-ultimate-exercise' ) . ':' => array(
        'elements' => array(
        ),
    ),
//=-=-=-=-=-=-= NUTRITION LABEL =-=-=-=-=-=-=
    __( 'Nutrition Label', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'by_date' => array(
                'title'   => __('Select a exercise to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by date added', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-nutrition-label]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Exercise', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_exercises_by_date',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'align',
                        'label' => __('Alignment', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'left',
                                'label' => __('Left', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'center',
                                'label' => __('Center', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'inline',
                                'label' => __('Inline', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'left',
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a exercise to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by title', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-nutrition-label]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Exercise', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_exercises_by_title',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'align',
                        'label' => __('Alignment', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'left',
                                'label' => __('Left', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'center',
                                'label' => __('Center', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'inline',
                                'label' => __('Inline', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'left',
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= USER SUBMISSION =-=-=-=-=-=-=
    __( 'User Submission', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'submission' => array(
                'title'   => __('User Submissions form', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-submissions]',
            ),
            'submissions_current_user' => array(
                'title'   => __('List of submissions by currently logged in user with edit ability', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-submissions-current-user-edit]',
            ),
        ),
    ),
//=-=-=-=-=-=-= USER MENUS =-=-=-=-=-=-=
    __( 'User Menus', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'menus' => array(
                'title'   => __('User Menus form', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-user-menus]',
            ),
            'my_menus' => array(
                'title'   => __('List Menus by user', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-user-menus-by]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'author',
                        'label' => __('Author', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'current_user',
                                'label' => __('Currently logged in user', 'wp-ultimate-exercise'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_user_menu_authors',
                                ),
                            ),
                        ),
                        'default' => array(
                            'current_user',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'title',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'ASC',
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= FAVORITE RECIPES =-=-=-=-=-=-=
    __( 'Favorite Exercies', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'menus' => array(
                'title'   => __( "List logged in user's Favorite Exercies", 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-favorites]',
            ),
        ),
    ),
//=-=-=-=-=-=-= SHOPPING LIST =-=-=-=-=-=-=
    __( 'Shopping List', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'random' => array(
                'title'   => __('Display a random menu', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-menu id="random"]'
            ),
            'by_date' => array(
                'title'   => __('Select a menu to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by date added', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-menu]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Menu', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_menus_by_date',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a menu to display', 'wp-ultimate-exercise') . ' - ' . __('Ordered by title', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-menu]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Menu', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_menus_by_title',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
    __( 'Exercise Egrid', 'wp-ultimate-exercise' ) => array(
        'elements' => array(
            'exercise_grid' => array(
                'title'   => __('Exercise Egrid', 'wp-ultimate-exercise') . ' - ' . __('Old version, use Egrid shortcode button instead', 'wp-ultimate-exercise'),
                'code'    => '[ultimate-exercise-grid]',
                'attributes' => array(
                    array(
                        'type' => 'textbox',
                        'name' => 'name',
                        'label' => __('Name', 'wp-ultimate-exercise'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'images_only',
                        'label' => __('Exclude exercises without a photo', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => '',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'filter',
                        'label' => __('Allow filtering by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'category',
                                'label' => __('Category', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'post_tag',
                                'label' => __('Tag', 'wp-ultimate-exercise'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                        'default' => array(
                            '{{all}}',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'multiselect',
                        'label' => __('Multi-Select', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Allow visitors to select multiple values for a tag or category.', 'wp-ultimate-exercise' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-exercise' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'match_all',
                        'label' => __('Match All', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Exercies will only match if they match all selections.', 'wp-ultimate-exercise' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-exercise' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'match_parents',
                        'label' => __('Parents match Children', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Selecting a parent will also match exercises with a child category or tag of that parent.', 'wp-ultimate-exercise' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-exercise' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'limit_author',
                        'label' => __('Limit Author', 'wp-ultimate-exercise'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_authors',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'limit_by_tag',
                        'label' => __('Limit by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'category',
                                'label' => __('Category', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'post_tag',
                                'label' => __('Tag', 'wp-ultimate-exercise'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpuep_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_by_values',
                        'label' => __('Limit by values', 'wp-ultimate-exercise') . ' ' . __('(separate slugs with ;)', 'wp-ultimate-exercise'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'rating',
                                'label' => __('Rating', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'date',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', 'wp-ultimate-exercise'),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', 'wp-ultimate-exercise'),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', 'wp-ultimate-exercise'),
                            ),
                        ),
                        'default' => array(
                            'DESC',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit',
                        'label' => __('Limit exercises at start', 'wp-ultimate-exercise'),
                        'default' => '30',
                    ),
                ),
            ),
        ),
    ),
);