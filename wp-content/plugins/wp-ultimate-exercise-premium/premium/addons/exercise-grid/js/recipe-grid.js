jQuery(document).ready(function() {
    // Select2
    jQuery('.wpuep-exercise-grid-filter').select2({
        allowClear: true,
        width: 'off',
        dropdownAutoWidth: false
    });

    // Get and prepare all Exercise Egrids on the page
    var ExerciseEgrids = {};
    jQuery('.wpuep-exercise-grid-container').each(function() {
        var grid_name = jQuery(this).data('grid-name');

        var exercises = window['wpuep_exercise_grid_'+grid_name]['exercises'];
        var template = window['wpuep_exercise_grid_'+grid_name]['template'];
        var orderby = window['wpuep_exercise_grid_'+grid_name]['orderby'];
        var order = window['wpuep_exercise_grid_'+grid_name]['order'];
        var limit = window['wpuep_exercise_grid_'+grid_name]['limit'];
        var images_only = window['wpuep_exercise_grid_'+grid_name]['images_only'];
        var filters_arr = window['wpuep_exercise_grid_'+grid_name]['filters'];
        var match_all = window['wpuep_exercise_grid_'+grid_name]['match_all'];
        var match_parents = window['wpuep_exercise_grid_'+grid_name]['match_parents'];

        var filters = {};
        for(var i = 0, l = filters_arr.length; i < l; i++) {
            jQuery.extend(filters, filters_arr[i]);
        }

        ExerciseEgrids[grid_name] = {
            exercises: exercises,
            template: template,
            orderby: orderby,
            order: order,
            limit: limit,
            images_only: images_only,
            offset: 0,
            filters: filters,
            match_all: match_all,
            match_parents: match_parents
        };
    });

    // Go to exercise when clicking on card in Egrid
    jQuery(document).on('click', '.exercise-card', function() {
        var link = jQuery(this).data('link');
        window.location.href = link;
    });

    // Remove links in Egrid (But load Socialite first if sharing buttons are present)
    if(jQuery('.exercise-card .socialite').length > 0) {
        Socialite.load();
    }
    jQuery('.exercise-card a:not(.wpuep-exercise-favorite, .wpuep-exercise-add-to-shopping-list, .wpuep-exercise-print-button, .wpuep-exercise-grid-link)').replaceWith(function() { return jQuery(this).contents(); });

    // Handle a filter selection
    jQuery('.wpuep-exercise-grid-filter').on('change', function() {
        var taxonomy = jQuery(this).attr('id').substr(7);
        var value = jQuery(this).val();
        var grid_name = jQuery(this).data('grid-name');

        if(value !== null && !jQuery.isArray(value)) {
            value = [value];
        }

        ExerciseEgrids[grid_name]['filters'][taxonomy] = value;

        jQuery('#wpuep-exercise-grid-'+grid_name).empty();
        getAndAddExerciesTo(grid_name);
    });

    function getAndAddExerciesTo(grid_name) {
        var data = {
            action: 'exercise_grid_get_exercises',
            security: wpuep_exercise_grid.nonce,
            grid: ExerciseEgrids[grid_name],
            grid_name: grid_name
        };

        var grid = jQuery('#wpuep-exercise-grid-'+grid_name);

        // Add spinner
        grid.append('<div id="floatingCirclesG"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>');

        // Get exercises through AJAX
        jQuery.post(wpuep_exercise_grid.ajaxurl, data, function(html) {
            grid.append(html).find('#floatingCirclesG').remove();
            jQuery('.exercise-card a').replaceWith(function() { return jQuery(this).contents(); });
            grid.trigger('exerciseEgridChanged');
        });
    }
});