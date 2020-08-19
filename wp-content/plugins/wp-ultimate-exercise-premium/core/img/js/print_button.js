jQuery(document).ready(function() {

    jQuery(document).on('click', '.wpuep-exercise-print-button', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var exerciseId = jQuery(this).data('exercise-id');
        var exercise = jQuery(this).parents('.wpuep-container');

        wpuep_print.servings_original = parseInt(exercise.data('servings-original'));
        wpuep_print.old_system = parseInt(exercise.data('system-original'))
        wpuep_print.new_system = exercise.find('select.adjust-exercise-unit option:selected').val();

        // Check if the page was in RTL
        wpuep_print.rtl = jQuery('body').hasClass('rtl');

        // Check if there is a servings changer (both free and Premium)
        var servings_input = exercise.find('input.adjust-exercise-servings');
        if(servings_input.length == 0) {
            servings_input = exercise.find('input.advanced-adjust-exercise-servings');
        }

        // Take servings from serving changer if available or just use original
        if(servings_input.length == 0) {
            wpuep_print.servings_new = wpuep_print.servings_original;
        } else {
            wpuep_print.servings_new = parseInt(servings_input.val());
        }

        // Get print template via AJAX
        wpuep_print.template = '';

        var data = {
            action: 'get_exercise_template',
            security: wpuep_print.nonce,
            exercise_id: exerciseId
        };

        jQuery.post(wpuep_print.ajaxurl, data, function(template) {
            wpuep_print.template = template.output;
            wpuep_print.fonts = template.fonts;
        }, 'json');

        // Open print version of exercise in blank page
        window.open(wpuep_print.coreUrl + '/templates/print.php', '_blank');
    });
});