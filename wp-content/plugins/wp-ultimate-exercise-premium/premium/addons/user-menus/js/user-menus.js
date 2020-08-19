var ExerciseUserMenus = ExerciseUserMenus || {};
ExerciseUserMenus.exercises = [];
ExerciseUserMenus.order = [];
ExerciseUserMenus.exerciseIngredients = [];
ExerciseUserMenus.nbrExercies = 0;
ExerciseUserMenus.ajaxGettingIngredients = 0;
ExerciseUserMenus.generalServings = 4;
ExerciseUserMenus.menuId = 0;
ExerciseUserMenus.unitSystem = 0
ExerciseUserMenus.init = function() {
    ExerciseUserMenus.initSelect();
    // Set default servings and unit system
    ExerciseUserMenus.unitSystem = parseInt(wpuep_user_menus.default_system);
    ExerciseUserMenus.changeServings(jQuery('.user-menus-servings-general'), true);
    if(typeof wpuep_user_menu !== 'undefined') {
        ExerciseUserMenus.setSavedValues(wpuep_user_menu);
        ExerciseUserMenus.redrawExercies();
        ExerciseUserMenus.updateIngredients();
    }
    jQuery('.user-menus-group-by').on('click', function(e) {
        e.preventDefault();
        var link = jQuery(this);
        if(!link.hasClass('user-menus-group-by-selected')){
            ExerciseUserMenus.groupBy(jQuery(this).data('groupby'));
            link.siblings('.user-menus-group-by-selected').removeClass('user-menus-group-by-selected');
            link.addClass('user-menus-group-by-selected');
        }
    });
    jQuery('.user-menus-selected-exercises').sortable({
        opacity: 0.5,
        start: function(ev, ui) {
            jQuery('.user-menus-exercises-delete').slideDown(500);
        },
        stop: function(ev, ui) {
            jQuery('.user-menus-exercises-delete').slideUp(500);
        },
        update: function(ev, ui) {
            ExerciseUserMenus.updateExerciseOrder(jQuery(this));
            ExerciseUserMenus.updateCookies();
        }
    });
    jQuery('.user-menus-servings-general').on('keyup change', function() {
        ExerciseUserMenus.changeServings(jQuery(this), true);
    });
    jQuery('.user-menus-selected-exercises').on('keyup change', '.user-menus-servings-exercise', function() {
        ExerciseUserMenus.changeServings(jQuery(this), false);
    });
    jQuery('.user-menus-servings-general').on('blur', function() {
        var servings_input = jQuery(this);
        var servings_new = servings_input.val();
        if(isNaN(servings_new) || servings_new <= 0){
            servings_input.val(1);
        }
    });
    jQuery('.user-menus-selected-exercises').on('blur', '.user-menus-servings-exercise', function() {
        var servings_input = jQuery(this);
        var servings_new = servings_input.val();

        if(isNaN(servings_new) || servings_new <= 0){
            servings_input.val(1);
        }
    });
    jQuery('.wpuep-user-menus').on('click', '.delete-exercise-button', function() {
		alert( '' );	
        jQuery(this).parent('.user-menus-exercise').remove();
        ExerciseUserMenus.deleteExercise();
    });
    jQuery('.user-menus-ingredients').on('change', '.shopping-list-ingredient', function() {
        var checkbox = jQuery(this);
        if(checkbox.is(':checked')) {
            checkbox.closest('tr').addClass('ingredient-checked');
        } else {
            checkbox.closest('tr').removeClass('ingredient-checked');
        }
    });
};
ExerciseUserMenus.deleteExercise = function() {
    ExerciseUserMenus.nbrExercies -= 1;
    ExerciseUserMenus.checkIfEmpty();	
    ExerciseUserMenus.updateExerciseOrder(jQuery('.user-menus-selected-exercises'));
    ExerciseUserMenus.updateIngredients();
    ExerciseUserMenus.updateCookies();
}
ExerciseUserMenus.setSavedValues = function(val){
    if(val.exercises !== null) {
        ExerciseUserMenus.exercises = val.exercises;
        ExerciseUserMenus.order = val.order;
    }
    if(val.nbrExercies !== '') {
        ExerciseUserMenus.nbrExercies = parseInt(val.nbrExercies);
    }
    if(val.unitSystem !== '') {
        ExerciseUserMenus.unitSystem = parseInt(val.unitSystem);
    }
    if(val.menuId !== '') {
        ExerciseUserMenus.menuId = parseInt(val.menuId);
    }
}
ExerciseUserMenus.deleteMenu = function(){
    var data = {
        action: 'user_menus_delete',
        security: wpuep_user_menus.nonce,
        menuId: ExerciseUserMenus.menuId
    };
    jQuery.post(wpuep_user_menus.ajaxurl, data, function(url) {
        window.location.href = url;
    }, 'html');
}
ExerciseUserMenus.saveMenu = function(){
    var data = {
        action: 'user_menus_save',
        security: wpuep_user_menus.nonce,
        menuId: ExerciseUserMenus.menuId,
        title: jQuery('.user-menus-title').val(),
        exercises: ExerciseUserMenus.exercises,
        order: ExerciseUserMenus.order,
        nbrExercies: ExerciseUserMenus.nbrExercies,
        unitSystem: ExerciseUserMenus.unitSystem
    };
    jQuery.post(wpuep_user_menus.ajaxurl, data, function(url) {
        if(ExerciseUserMenus.menuId == 0) {
            window.location.href = url;
        }
    }, 'html');
}

ExerciseUserMenus.printShoppingList = function()
{
    var title = jQuery('.user-menus-title').val();
    if(title != undefined) {
        wpuep_user_menus.shoppingListTitle = '<h2 class="wpuep-shoppinglist-title">' + title + '</h2>';
    } else {
        wpuep_user_menus.shoppingListTitle = '<h2 class="wpuep-shoppinglist-title">Shopping List</h2>';
    }

    wpuep_user_menus.exerciseList = '';
    if(wpuep_user_menus.print_exercise_list) {
        //console.log(wpuep_user_menus.exercises);
        wpuep_user_menus.exerciseList = '<table class="wpuep-exerciselist">';

        wpuep_user_menus.exerciseList += wpuep_user_menus.print_exercise_list_header;

        jQuery('.user-menus-selected-exercises .user-menus-exercise').each(function() {
            wpuep_user_menus.exerciseList += '<tr>';
            wpuep_user_menus.exerciseList += '<td>' + jQuery(this).find('a').text() + '</td>';
            wpuep_user_menus.exerciseList += '<td>' + jQuery(this).find('input').val() + '</td>';
            wpuep_user_menus.exerciseList += '</tr>';
        });
        wpuep_user_menus.exerciseList += '</table>';
    }

    wpuep_user_menus.shoppingList = '<table class="wpuep-shoppinglist">' + jQuery('.user-menus-ingredients').html() + '</table>';

    window.open(wpuep_user_menus.addonUrl + '/templates/print-shopping-list.php');
}

ExerciseUserMenus.printUserMenu = function()
{
    // Exercies
    wpuep_user_menus.exercise_ids = [];
    wpuep_user_menus.exercises = [];
    wpuep_user_menus.print_servings_original = [];
    wpuep_user_menus.print_servings_wanted = [];
    wpuep_user_menus.print_unit_system = ExerciseUserMenus.unitSystem;

    for(var i = 0, l = ExerciseUserMenus.order.length; i < l; i++)
    {
        var order_id = ExerciseUserMenus.order[i];
        var exercise = ExerciseUserMenus.exercises[order_id];

        wpuep_user_menus.exercise_ids.push(exercise.id);
        wpuep_user_menus.print_servings_original.push(exercise.servings_original);
        wpuep_user_menus.print_servings_wanted.push(exercise.servings_wanted);
    }

    var data = {
        action: 'get_exercise_template',
        security: wpuep_user_menus.nonce,
        exercise_ids: wpuep_user_menus.exercise_ids
    };

    jQuery.post(wpuep_user_menus.ajaxurl, data, function(exercises) {
        wpuep_user_menus.exercises = exercises;
    }, 'json');

    // Shopping List
    var title = jQuery('.user-menus-title').val();
    if(title != undefined) {
        wpuep_user_menus.shoppingListTitle = '<h2>' + title + '</h2>';
    } else {
        wpuep_user_menus.shoppingListTitle = '<h2>Shopping List</h2>';
    }
    wpuep_user_menus.shoppingList = '<table class="wpuep-shoppinglist">' + jQuery('.user-menus-ingredients').html() + '</table>';

    window.open(wpuep_user_menus.addonUrl + '/templates/print-user-menu.php');
}

ExerciseUserMenus.updateExerciseOrder = function(list)
{
    ExerciseUserMenus.order = list.sortable('toArray', { attribute: 'data-index'} );
	//console.log( 'Update ORder' );
	//console.log( ExerciseUserMenus.order ); 
}


ExerciseUserMenus.changeServings = function(input, general) {
    var servings_new = input.val();

    if(isNaN(servings_new) || servings_new <= 0) {
        servings_new = 1;
    }

    if(general) {
        ExerciseUserMenus.generalServings = servings_new;
    } else {
        var index = input.parent('.user-menus-exercise').data('index');
        ExerciseUserMenus.exercises[index].servings_wanted = servings_new;

        ExerciseUserMenus.updateIngredientsTable();
        ExerciseUserMenus.updateCookies();
    }
}

ExerciseUserMenus.initSelect = function() {
    jQuery('.user-menus-select').select2({
        width: 'off'
    }).on('change', function() {
			 jQuery(this).select2('data' );		
            // Add the selected exercise
            ExerciseUserMenus.addExercise(jQuery(this).select2('data'));

            // Clear the selection
            jQuery(this).select2('val', '');
        });
}

ExerciseUserMenus.addExercise = function(exercise) {
		
	
    if(exercise.id !== '')
    {
        ExerciseUserMenus.exercises.push({
            id: exercise.id,
            name: exercise.text,
            link: exercise.element[0].dataset.link,
            servings_original: exercise.element[0].dataset.servings,
            servings_wanted: ExerciseUserMenus.generalServings
        });

        ExerciseUserMenus.order.push((ExerciseUserMenus.exercises.length - 1).toString());
        ExerciseUserMenus.redrawExercies();
        ExerciseUserMenus.updateCookies();
        ExerciseUserMenus.updateIngredients();
    }
}

ExerciseUserMenus.redrawExercies = function() {		
    var container = jQuery('.user-menus-selected-exercises');
    container.empty();	
    var exercises	=	ExerciseUserMenus.exercises;
    var order 		=	ExerciseUserMenus.order;	
    ExerciseUserMenus.nbrExercies = 0;	
    for(var i = 0, l = order.length; i < l; i++){
        var exercise = exercises[order[i]];
        container.append(
            '<div class="user-menus-exercise" data-exercise="'+exercise.id+'" data-index="'+order[i]+'">' +
                '<i class="fa fa-trash delete-exercise-button"></i> ' +
                '<a href="'+exercise.link+'" target="_blank">'+exercise.name+'</a>' +
                '<input type="number" class="user-menus-servings-exercise" value="'+exercise.servings_wanted+'">' +
            '</div>');
        ExerciseUserMenus.nbrExercies += 1;
    }

    ExerciseUserMenus.checkIfEmpty();
}

ExerciseUserMenus.updateCookies = function() {
    if(ExerciseUserMenus.menuId == 0) {
        var data = {
            action: 'update_shopping_list',
            security: wpuep_user_menus.nonce,
            exercises: ExerciseUserMenus.exercises,
            order: ExerciseUserMenus.order
        };

        jQuery.post(wpuep_user_menus.ajaxurl, data);
    }
}

ExerciseUserMenus.checkIfEmpty = function() {
	alert( ExerciseUserMenus.nbrExercies );
    if(ExerciseUserMenus.nbrExercies === 0) {
        jQuery('.user-menus-no-exercises').show();
    } else {
        jQuery('.user-menus-no-exercises').hide();
    }
}

ExerciseUserMenus.groupBy = function(groupby) {
    var data = {
        action: 'user_menus_groupby',
        security: wpuep_user_menus.nonce,
        groupby: groupby
    };

    jQuery.post(wpuep_user_menus.ajaxurl, data, function(html) {
        jQuery('.user-menus-select').select2('destroy').off().html(html);
        ExerciseUserMenus.initSelect();
    });
}

ExerciseUserMenus.updateIngredients = function()
{
    var order = ExerciseUserMenus.order;
    var exercises = ExerciseUserMenus.exercises;
    var ajaxCalls = 0;

    for(var i = 0, l = order.length; i < l; i++)
    {
        var exercise_id = exercises[order[i]].id;

        if(ExerciseUserMenus.exerciseIngredients[exercise_id] === undefined) {
            ajaxCalls++;

            ExerciseUserMenus.exerciseIngredients[exercise_id] = [];
            ExerciseUserMenus.getIngredients(exercise_id);
        }
    }

    // No need to wait for non-existent ajax calls
    if(ajaxCalls === 0) {
        ExerciseUserMenus.updateIngredientsTable();
    }
}

/**
 * Get exercise ingredients through ajax and put in cache
 *
 * @param exercise_id
 */
ExerciseUserMenus.getIngredients = function(exercise_id){	
    var data = {
        action: 'user_menus_get_ingredients',
        security: wpuep_user_menus.nonce,
        exercise_id: exercise_id
    };
    ExerciseUserMenus.ajaxGettingIngredients++;
    jQuery.post(wpuep_user_menus.ajaxurl, data, function(ingredients) {
        ExerciseUserMenus.ajaxGettingIngredients--;
        ExerciseUserMenus.exerciseIngredients[exercise_id] = ingredients;
        if(ExerciseUserMenus.ajaxGettingIngredients === 0) {
            ExerciseUserMenus.updateIngredientsTable();
        }

    }, 'json');	
}

ExerciseUserMenus.updateIngredientsTable = function(){
    var ingredient_table = jQuery('table.user-menus-ingredients');
    ingredient_table.find('tbody').empty();
    var exercise_ingredients = ExerciseUserMenus.exerciseIngredients;
    var order = ExerciseUserMenus.order;
    var exercises = ExerciseUserMenus.exercises;
    var ingredients = [];
    var ingredients_plural = [];
    // Choose last cup type in systems. Not a perfect system.
    var cup_type = 236.6;
    for(var i = 0, l = wpuep_unit_conversion.systems.length; i < l; i++){
        var system = wpuep_unit_conversion.systems[i];
        if(jQuery.inArray('cup', system.units_volume) != -1) {
            cup_type = parseFloat(system.cup_type);
        }
    }

    for(var i = 0, l = order.length; i < l; i++){
        var exercise_id = exercises[order[i]].id;
        var servings = exercises[order[i]].servings_wanted / parseFloat(exercises[order[i]].servings_original);
        for(var j = 0, m = exercise_ingredients[exercise_id].length; j < m; j++){
            var ingredient = exercise_ingredients[exercise_id][j];
            var name = wpuep_user_menus.ingredient_notes && ingredient.notes ? ingredient.ingredient + ' (' + ingredient.notes + ')' : ingredient.ingredient;
            var group = ingredient.group;
            var plural = ingredient.plural || name;
            if(ingredients_plural[name] === undefined) {
                ingredients_plural[name] = plural;
            }
            if(ingredients[group] === undefined) {
                ingredients[group] = [];
            }
            if(ingredients[group][name] === undefined) {
                ingredients[group][name] = [];
            }
            var amount = ingredient.amount_normalized * servings;
            var unit = ingredient.unit;
            var type = ExerciseUnitConversion.getUnitFromAlias(unit);
            if(type !== undefined && wpuep_user_menus.consolidate_ingredients == '1') {
                var abbreviation = ExerciseUnitConversion.getAbbreviation(type);
                // Adjust for cup type
                if(abbreviation == 'cup') {
                    var qty_cup = new Qty('1 cup').to('ml').scalar;
                    if(Math.abs(cup_type - qty_cup) > 0.1) { // 236.6 == 236.588238
                        amount = amount * (cup_type / qty_cup);
                    }
                }
                var quantity = new Qty('' + amount + ' ' + abbreviation);
                var base_quantity = quantity.toBase();
                if(base_quantity.units() == 'm3') {
                    base_quantity = base_quantity.to('l');
                }
                unit = base_quantity.units();
                amount = base_quantity.scalar;
            }
            if(unit == '') {
                unit = 'wpuep_nounit';
            }

            if(ingredients[group][name][unit] === undefined) {
                ingredients[group][name][unit] = 0.0;
            }

            ingredients[group][name][unit] += parseFloat(amount);
        }
    }

    // Sort ingredients by name
    var group_keys = Object.keys(ingredients);
    group_keys.sort(function (a, b) { // Case insensitive sort
        return a.toLowerCase().localeCompare(b.toLowerCase());
    });

    for(i = 0, l = group_keys.length; i < l; i++)
    {
        var group_key = group_keys[i];
        var group = ingredients[group_key];

        var group_row = jQuery('<tr><td colspan="2"><strong>'+group_key+'</strong></td></tr>');
        ingredient_table.append(group_row);

        var ingredient_keys = Object.keys(group);
        ingredient_keys.sort(function (a, b) { // Case insensitive sort
            return a.toLowerCase().localeCompare(b.toLowerCase());
        });

        for(j = 0, m = ingredient_keys.length; j < m; j++)
        {
            var ingredient = ingredient_keys[j];

            var units = group[ingredient];
            for(var unit in units)
            {
                var amount = units[unit];

                if(isFinite(amount))
                {
                    var ingredient_row = jQuery('<tr></tr>');

                    var actual_unit = ExerciseUnitConversion.getUnitFromAlias(unit);

                    // Get the Unit Systems we need to generate
                    if(wpuep_user_menus.adjustable_system == '1') {
                        var systems = [ExerciseUserMenus.unitSystem];
                    } else {
                        var systems = wpuep_user_menus.static_systems;
                    }

                    var plural = false;

                    for(var s = 0; s < systems.length; s++) {
                        var converted_amount = amount;
                        var converted_unit = unit;

                        if(unit == 'wpuep_nounit') {
                            converted_unit = '';
                        } else if(actual_unit !== undefined) {
                            if(wpuep_user_menus.consolidate_ingredients == '1' || (!ExerciseUnitConversion.isUniversal(actual_unit) && jQuery.inArray(systems[s], ExerciseUnitConversion.getUnitSystems(actual_unit)) == -1)) {
                                var quantity = ExerciseUnitConversion.convertUnitToSystem(amount, actual_unit, 0, systems[s]);
                                var converted_amount = quantity.amount;
                                converted_unit = ExerciseUnitConversion.getUserAbbreviation(quantity.unit, converted_amount);
                            }
                        }

                        converted_amount = ExerciseUnitConversion.formatNumber(converted_amount, wpuep_user_menus.fractions);
                        if(converted_amount !== '1') {
                            plural = true;
                        }

                        ingredient_row.append('<td>'+converted_amount+' '+ converted_unit +'</td>');
                    }

                    var ingredient_name = plural ? ingredients_plural[ingredient] : ingredient;
                    var ingredient_name = ingredient_name.charAt(0).toUpperCase() + ingredient_name.slice(1);
                    var checkbox = '';

                    if(wpuep_user_menus.checkboxes == '1') {
                        checkbox = '<input type="checkbox" class="shopping-list-ingredient"> ';
                    }
                    ingredient_row.prepend('<td>'+checkbox+ingredient_name+'</td>');
                    
                    ingredient_table.append(ingredient_row);
                }
            }
        }
    }
}

ExerciseUserMenus.changeUnits = function(dropdown)
{
    ExerciseUserMenus.unitSystem = parseInt(jQuery(dropdown).val());
    ExerciseUserMenus.updateIngredientsTable();
}

jQuery(document).ready(function(){
    if(jQuery('.wpuep-user-menus').length > 0) {
        ExerciseUserMenus.init();
    }
});