<?php
$menu_display_only = isset( $menu_display_only ) ? $menu_display_only : false;
if( !$menu_display_only ) {
    $exercises = WPUltimateExercise::get()->query()->order_by( 'title' )->order( 'ASC' )->get();
    if( WPUltimateExercise::option( 'exercise_tags_filter_categories', '0' ) == '1' ) {
        $groupby_options['category'] =  __( 'Category', 'wp-ultimate-exercise' );
    }
    if( WPUltimateExercise::option( 'exercise_tags_filter_tags', '0' ) == '1' ) {
        $groupby_options['post_tag'] = __( 'Tag', 'wp-ultimate-exercise' );
    }
    $taxonomies = WPUltimateExercise::get()->tags();
    unset($taxonomies['ingredient']);
    foreach( $taxonomies as $taxonomy => $options ) {
        if ( count( get_terms( $taxonomy ) ) > 0 ) {
            $groupby_options[$taxonomy] = $options['labels']['singular_name'];
        }
    }
}
$adjust_servings = '';
if( WPUltimateExercise::option('user_menus_dynamic_unit_system', '1') == '1' ) {
    $adjust_servings .= '<div>' . __( 'Units', 'wp-ultimate-exercise' );
    $adjust_servings .= '<select onchange="ExerciseUserMenus.changeUnits(this)">';
    $systems = WPUltimateExercise::get()->helper( 'ingredient_units' )->get_active_systems();
    $unit_system = isset( $menu ) ? get_post_meta( $menu->ID, 'user-menus-unitSystem', true ) : WPUltimateExercise::option( 'user_menus_default_unit_system', '0' );
    foreach($systems as $i => $system) {
        if($i == $unit_system) {
            $selected = ' selected';
        } else {
            $selected = '';
        }
        $adjust_servings .= '<option value="'.$i.'"'.$selected.'>'.$system['name'].'</option>';
    }
    $adjust_servings .= '</select></div>';
}
?><div class="user-menus-buttons-container">
        <?php   // Menu author or admin can delete menus if enabled
        if( !$menu_display_only && isset( $menu ) && WPUltimateExercise::option( 'user_menus_enable_delete', '0' ) == '1' && ( get_current_user_id() != 0 && ( current_user_can( 'manage_options' ) || $menu->post_author == get_current_user_id() ) ) ) {
            ?><button onclick="ExerciseUserMenus.deleteMenu()"><?php _e( 'Delete Menu', 'wp-ultimate-exercise' ); ?></button>
        <?php }// Menu author or admin can save menus if enabled
        $allow_save = false;
        // If not saved menu OR ( logged in user is admin OR menu author )
        if( ( !isset($menu) ) || ( get_current_user_id() != 0 && ( current_user_can( 'manage_options' ) || $menu->post_author == get_current_user_id() ) ) ) {
            switch( WPUltimateExercise::option( 'user_menus_enable_save', 'guests' ) ) {
                case 'off':
                    $allow_save = current_user_can( 'manage_options' ) ? true : false;
                    break;
                case 'registered':
                    $allow_save = is_user_logged_in() ? true : false;
                    break;
                case 'guests':
                    $allow_save = true;
                    break;
            }
        }		/*
        if( $allow_save && !$menu_display_only ) {
        ?>
        <button onclick="ExerciseUserMenus.saveMenu()"><?php _e( 'Save Menu', 'wp-ultimate-exercise' ); ?></button>
        <?php } ?>
        <?php if( WPUltimateExercise::option( 'user_menus_enable_print_list', '1' ) == '1' ) { ?>
        <button onclick="ExerciseUserMenus.printShoppingList()"><?php _e( 'Print Shopping List', 'wp-ultimate-exercise' ); ?></button>
        <?php } ?>
        <?php if( WPUltimateExercise::option( 'user_menus_enable_print_menu', '0' ) == '1' ) { ?>
        <button onclick="ExerciseUserMenus.printUserMenu()"><?php _e( 'Print Menu', 'wp-ultimate-exercise' ); ?></button>  <?php } ?>*/?>		
		<span><button onclick="ExerciseUserMenus.saveProgram()"><?php _e( 'Save Program', 'wp-ultimate-exercise' ); ?></button></span>		
		<span style="margin-left:15px !important;"><button onclick="ExerciseUserMenus.emailProgram()"><?php _e( 'Email Program', 'wp-ultimate-exercise' ); ?></button></span>		
		<span style="margin-left:15px !important;"><button onclick="ExerciseUserMenus.printUserMenu()"><?php _e( 'Print Program', 'wp-ultimate-exercise' ); ?></button></span>		
    </div>
<div class="wpuep-user-menus">
    <?php if( !$menu_display_only ) : ?>
    <!--<div class="user-menus-input-container">-->
	<div> <?php
        if ( is_user_logged_in() ) {
            global $current_user;
            get_currentuserinfo();
            $user_name = $current_user->display_name;
        } else {
            $user_name = __( 'Visitor', 'wp-ultimate-exercise' );
        } //	$default_menu_name = date('Y-m-d') . ' | ' . $user_name;
		$default_menu_name = date('d-m-Y');		
        //$default_menu_name = _e( 'My New Menu', 'wp-ultimate-exercise' );		
		add_thickbox();		
		$plugin_dir		=	plugin_dir_path( __FILE__ );?>
		<p>
			<input type="hidden" name="ajax_loader" id="ajax_loader" value="<?php echo get_template_directory_uri(); ?>/images/ajax.gif"/>
			<input type="hidden" name="physical_path" id="physical_path" value="<?php echo $plugin_dir; ?>index.php" />
			<input class="uid" type="hidden" name="client_email" id="client_email">
			<span><input type="text" class="user-menus-title" name="program_title" id="program_title" value="<?php if( isset( $menu ) ) { echo get_the_title( $menu->ID ); } else { echo $default_menu_name; } ?>"/></span>
			<span><input placeholder="Search User" class="user-menus-title" type="text" name="search_user" id="search_user" autocomplete="off" /></span>
			<span>			
				<select class="user-menus-select" data-placeholder="<?php _e( 'Add Exercies', 'wp-ultimate-exercise' ); ?>">
<?php
					$groups = $this->get_exercises_grouped_by('a-z');
					echo $this->get_select_options($groups);?>
				</select>
			</span>
		</p>
    </div>
    <div class="user-menus-servings-container" style="display:none;">
        <?php // echo $adjust_servings; ?>
        <div>
            <?php
            $general_servings = intval( WPUltimateExercise::option('user_menus_default_servings', '4') );
            if( $general_servings < 1 ) {
                $general_servings = 4;
            }
             _e( 'Servings', 'wp-ultimate-exercise' ); ?>
            <input type="number" class="user-menus-servings-general" value="<?php echo $general_servings; ?>">
        </div>
    </div>
    <div class="user-menus-group-by-container">
        <?php _e( 'Group by', 'wp-ultimate-exercise' ); ?>: <a href="#" class="user-menus-group-by user-menus-group-by-selected" data-groupby="a-z"><?php _e( 'alphabet', 'wp-ultimate-exercise' ); ?></a><?php
        if( is_array( $groupby_options ) ) {
            foreach( $groupby_options as $id => $name ) {
                echo ', <a href="#" class="user-menus-group-by" data-groupby="'.$id.'">'.strtolower($name).'</a>';
            };
        }
        ?></div>
    <?php endif; // !$menu_display_only ?>
    <div class="user-menus-selected-exercises"></div>
    <div class="user-menus-no-exercises"><?php _e( 'No exercises in your menu yet', 'wp-ultimate-exercise' ); ?></div>
    <?php if( $menu_display_only ) : ?>
    <div class="user-menus-servings-container">
        <?php echo $adjust_servings; ?>
    </div>
    <div style="clear: both;"></div>
    <?php endif; // $menu_display_only ?>    
    <table class="user-menus-ingredients">
        <thead>		
        <tr>
            <?php
            if( WPUltimateExercise::option('user_menus_dynamic_unit_system', '1') == '1' ) {
                // Dynamic Unit System
                //echo '<th>' . __( 'Ingredient', 'wp-ultimate-exercise' ) . '</th>';
                //	echo '<th>' . __( 'Amount', 'wp-ultimate-exercise' ) . '</th>';				
				echo '<th style="background:red;"><a href="#program_title"><b style="color:#fff;">BACK TO TOP</b></a></th><th style="background:red;">&nbsp;</th>';
            } else {
                // Static Unit Systems
                $systems = WPUltimateExercise::get()->helper( 'ingredient_units' )->get_active_systems();
                $systems_to_show = $this->get_static_unit_systems();
                $nbr_of_columns = count( $systems_to_show ) + 1;
                $inline_style = '';
                if( $nbr_of_columns > 2 ) {
                    $width = round( 100.0 / $nbr_of_columns, 2 );
                    $inline_style = ' style="width: ' . $width . '%;"';
                }
                echo '<th' . $inline_style . '>' . __( 'Ingredient', 'wp-ultimate-exercise' ) . '</th>';
                foreach( $systems_to_show as $system ) {
                    echo '<th' . $inline_style . '>' . $systems[$system]['name'] . '</th>';
                }
            }
            ?>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.min.js"></script>
<script>
jQuery(document).ready(function(e){(function($){
	var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
	jQuery('#search_user').autocomplete({
		source:availableTags,select:function(event,ui){
			event.preventDefault();
			$('#client_email').val(ui.item.email);
			$(this).val(ui.item.label);
		},
	});
}(jQuery))});
</script>