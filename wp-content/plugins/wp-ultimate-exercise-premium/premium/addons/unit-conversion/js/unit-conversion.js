/**
 * ExerciseUnitConversion.js
 *
 * @author Bootstrapped Ventures
 * @version 0.6.4
 */

var ExerciseUnitConversion = ExerciseUnitConversion || {};

ExerciseUnitConversion.getUnitFromAlias = function(alias) {
    if(alias === null || alias === undefined) {
        return undefined;
    }
    var clean = alias.replace(/[^a-z]/gi, '');

    if(wpuep_unit_conversion.alias_to_unit[clean] !== undefined) {
        var unit = wpuep_unit_conversion.alias_to_unit[clean];
    } else {
        var unit = wpuep_unit_conversion.alias_to_unit[clean.toLowerCase()];
    }

    return unit;
}

ExerciseUnitConversion.getUnitType = function(unit) {
    return wpuep_unit_conversion.unit_to_type[unit];
}

ExerciseUnitConversion.getUnitSystems = function(unit) {
    var type = ExerciseUnitConversion.getUnitType(unit);

    if(!type) {
        return [];
    }

    var systems = wpuep_unit_conversion.systems;
    var unit_systems = [];

    for(var i = 0, l = systems.length; i < l; i++)
    {
        if(jQuery.inArray(unit, systems[i]['units_'+type]) != -1) {
            unit_systems.push(i);
        }
    }

    return unit_systems;
}

ExerciseUnitConversion.isUniversal = function(unit) {
    if(jQuery.inArray(unit, wpuep_unit_conversion.universal_units) != -1) {
        return true;
    }

    return false;
}

ExerciseUnitConversion.getAbbreviation = function(unit) {
    var abbr = wpuep_unit_conversion.unit_abbreviations[unit];
    if(abbr === undefined) {
        abbr = unit;
    }
    return abbr;
}

ExerciseUnitConversion.getUserAbbreviation = function(unit, amount) {
    var type = 'plural';
    if( amount == 1) {
        type = 'singular';
    }

    var abbr_unit = wpuep_unit_conversion.user_abbreviations[unit];

    if(abbr_unit !== undefined) {
        var abbr = wpuep_unit_conversion.user_abbreviations[unit][type];
        if(abbr === undefined) {
            abbr = unit;
        }
    } else {
        var abbr = unit
    }

    return abbr;
}

ExerciseUnitConversion.determineIngredientListSystem = function(list) {
    // Prepare counter
    var counter = [];
    var systems = wpuep_unit_conversion.systems;

    for(var i = 0, l = systems.length; i < l; i++) {
        counter[i] = 0;
    }

    list.find('.wpuep-exercise-ingredient').each(function(){
        var alias = jQuery(this).find('.wpuep-exercise-ingredient-unit').text();
        var unit = ExerciseUnitConversion.getUnitFromAlias(alias);
        var systems = ExerciseUnitConversion.getUnitSystems(unit);

        for(var i = 0, l = systems.length; i < l; i++) {
            counter[systems[i]]++;
        }
    });

    // Get index of largest number
    var max = counter[0];
    var maxIndex = 0;

    for(var i = 1, l = counter.length; i < l; i++) {
        if(counter[i] > max) {
            maxIndex = i;
            max = counter[i];
        }
    }

    return maxIndex;
};

ExerciseUnitConversion.convertUnitToSystem = function(amount, unit, old_system, new_system) {

    var systems = wpuep_unit_conversion.systems;

    // Adjust for cup type
    if(unit == 'cup') {
        var old_cup = parseFloat(systems[old_system].cup_type);
        var qty_cup = new Qty('1 cup').to('ml').scalar;

        if(Math.abs(old_cup - qty_cup) > 0.1) { // 236.6 == 236.588238
            amount = amount * (old_cup / qty_cup);
        }
    }

    var unit_type = ExerciseUnitConversion.getUnitType(unit);
    var quantity = new Qty(amount + ' ' + ExerciseUnitConversion.getAbbreviation(unit));

    var possible_units = systems[new_system]['units_' + unit_type];

    var new_quantities = [];

    for(var i = 0, l = possible_units.length; i < l; i++) {
        try {
            var possible_amount = quantity.to(ExerciseUnitConversion.getAbbreviation(possible_units[i])).scalar;

            // Adjust for cup type
            if(possible_units[i] == 'cup') {
                var new_cup = parseFloat(systems[new_system].cup_type);
                var qty_cup = new Qty(new_cup + ' ml').to('cup').scalar;

                if(Math.abs(1 - qty_cup) > 0.01) { // 236.6 == 236.588238
                    possible_amount = possible_amount * (1 / qty_cup);
                }
            }

            new_quantities.push({
                unit: possible_units[i],
                amount: possible_amount
            });
        } catch (err) {
            console.log(err);
        }
    }

    if(new_quantities.length === 0) {
        // No conversion found, return same amount and unit
        return {
            unit: unit,
            amount: amount
        }
    }

    var sorted_quantities = new_quantities.sort(ExerciseUnitConversion.compareAmounts);

    var new_amount = sorted_quantities[0].amount;
    var new_unit = sorted_quantities[0].unit;

    dance:
    for(var i = 1, l = sorted_quantities.length; i < l; i++) {
        if(new_amount > 999 || ( new_amount > 5 && ( new_unit == 'teaspoon' || new_unit == 'tablespoon' ) ) ) {
            new_amount = sorted_quantities[i].amount;
            new_unit = sorted_quantities[i].unit;
        } else {
            break dance;
        }
    }

    return {
        unit: new_unit,
        amount: new_amount
    }
}

ExerciseUnitConversion.compareAmounts = function(a, b) {
    return b.amount - a.amount;
}

/**
 * Format final number
 *
 * @param string
 * @return {String}
 */
ExerciseUnitConversion.formatNumber = function(amount, fraction, precision) {
    if(fraction) {
        var fractioned_amount = Fraction(amount.toString()).snap();
        if(fractioned_amount.denominator < 100) {
            return fractioned_amount;
        }
    }

    if(amount == '' || amount == 0) {
        return '';
    }
    // reformat to fixed
    var precision = precision == undefined ? 2 : precision;
    var formated = amount.toFixed(precision);

    // increase the precision if reformated to 0.00, failsafe for endless loop
    while(parseFloat(formated) == 0) {
        precision++;
        formated = amount.toFixed(precision);

        if(precision > 10) {
            return '';
        }
    }

    // ends with .00, remove
    return formated.replace(/\.00$/,'');
};

/**
 * Update the ingredient list
 * @param ingredients
 * @param old_system
 * @param new_system
 */
ExerciseUnitConversion.updateIngredients = function(ingredientList, old_system, new_system)
{
    ingredientList.find('.wpuep-exercise-ingredient').each(function() {
        var ingredient_amount = jQuery(this).find('.wpuep-exercise-ingredient-quantity');
        var ingredient_unit = jQuery(this).find('.wpuep-exercise-ingredient-unit');

        var amount     = ingredient_amount.data('normalized');
        var fraction   = ingredient_amount.data('fraction');
        var alias      = ingredient_unit.data('original');

        var unit = ExerciseUnitConversion.getUnitFromAlias(alias);

        // Only continue if we have a non-universal unit and a non-zero quantity
        if(unit !== undefined && !ExerciseUnitConversion.isUniversal(unit) && amount)
        {
            var systems = ExerciseUnitConversion.getUnitSystems(unit);
            var cup_systems = ExerciseUnitConversion.getUnitSystems('cup');

            // Only continue if the current unit isn't used in the new system (unless it's a cup and the old system used cups)
            if(jQuery.inArray(new_system, systems) == -1 || ( unit == 'cup' && jQuery.inArray(old_system, cup_systems) != -1 ))
            {
                // TODO change amount to serving size
                var new_quantity = ExerciseUnitConversion.convertUnitToSystem(amount, unit, old_system, new_system);

                ingredient_amount.text(ExerciseUnitConversion.formatNumber(new_quantity.amount, fraction));
                ingredient_unit.text(ExerciseUnitConversion.getUserAbbreviation(new_quantity.unit, new_quantity.amount));

                ingredient_amount.data('normalized', new_quantity.amount);
                ingredient_unit.data('original', new_quantity.unit);

                // Ingredient name singular or plural
                var ingredient_name = jQuery(this).find('.wpuep-exercise-ingredient-name');
                ExerciseUnitConversion.checkIngredientPlural(ingredient_name, new_quantity.amount);
            }
        }
    });

    ingredientList.data('system', new_system);
}

/**
 * Handles onchange dropdown list
 *
 * @param ulList
 */
ExerciseUnitConversion.recalculate = function(dropdown) {

    var ingredientList = jQuery(dropdown).parents('.wpuep-container').find('.wpuep-exercise-ingredients');
    var old_system = parseInt(ingredientList.data('system'));
    var new_system = parseInt(jQuery(dropdown).val());

    if(old_system != new_system)
    {
        ExerciseUnitConversion.updateIngredients(ingredientList, old_system, new_system);
    }
}

/**
 * Exercise unit conversion init
 */
ExerciseUnitConversion.init = function(){
    jQuery('.wpuep-exercise-ingredients').each(function(i) {
        var ingredientList = jQuery(this);

        // Set current system as selected in dropdown
        var system = ExerciseUnitConversion.determineIngredientListSystem(ingredientList);
        var dropdown = ingredientList.parents('.wpuep-container').find('.adjust-exercise-unit');
        dropdown.val(system);

        ingredientList.parents('.wpuep-container').data('system-original', system);
        ingredientList.data('system', system);
    });
};

/**
 * Jquery document ready (init)
 */

jQuery(document).ready(function(){
    if(window.wpuep_unit_conversion !== undefined) {
        ExerciseUnitConversion.init();
    }
});

ExerciseUnitConversion.adjustServings = function(ingredientList, servings_original, servings_new)
{
    ingredientList.find('.wpuep-exercise-ingredient').each(function() {
        var ingredient_amount = jQuery(this).find('.wpuep-exercise-ingredient-quantity');
        var ingredient_unit = jQuery(this).find('.wpuep-exercise-ingredient-unit');

        var amount = ingredient_amount.data('normalized');
        var fraction = ingredient_amount.data('fraction');

        if(!isFinite(amount)) {
            ingredient_amount.addClass('exercise-ingredient-nan');
        } else {
            // Calculate and set new amount
            var new_amount = servings_new * amount/servings_original;
            var new_amount_text = ExerciseUnitConversion.formatNumber(new_amount, fraction);

            ingredient_amount
                .text(new_amount_text)
                .data('normalized', new_amount);

            // Get alias again as we might have to switch from plural to singular or back
            var alias = ingredient_unit.data('original');
            var unit = ExerciseUnitConversion.getUnitFromAlias(alias);
            ingredient_unit.text(ExerciseUnitConversion.getUserAbbreviation(unit, new_amount));

            // Ingredient name singular or plural
            var ingredient_name = jQuery(this).find('.wpuep-exercise-ingredient-name');
            ExerciseUnitConversion.checkIngredientPlural(ingredient_name, new_amount);
        }

    });
};

ExerciseUnitConversion.checkIngredientPlural = function(ingredient_name, amount) {
    if(amount.toFixed(2) == 1) {
        var ingredient_version = ingredient_name.data('singular');
    } else {
        var ingredient_version = ingredient_name.data('plural');
    }

    if(ingredient_version !== undefined) {
        if(ingredient_name.find('a').length > 0) {
            ingredient_name.find('a').text(ingredient_version);
        } else {
            ingredient_name.text(ingredient_version);
        }
    }
};

/**
 * Addition to temporarily solve servings adjust
 * TODO: Better (integrated) solution
 */
jQuery(document).on('keyup change', '.advanced-adjust-exercise-servings', function(e) {
    var servings_input = jQuery(this);

    var ingredientList = servings_input.parents('.wpuep-container').find('.wpuep-exercise-ingredients');

    var servings_original = parseFloat(servings_input.data('original'));
    var servings_new = servings_input.val();

    if(isNaN(servings_new) || servings_new <= 0){
        servings_new = 1;
    }

    ExerciseUnitConversion.adjustServings(ingredientList, servings_original, servings_new);

    // Update current servings
    servings_input.data('original', servings_new);
});

jQuery(document).on('blur', '.advanced-adjust-exercise-servings', function(e) {
    var servings_input = jQuery(this);

    var servings_new = servings_input.data('original');
    servings_input.val(servings_new);
});