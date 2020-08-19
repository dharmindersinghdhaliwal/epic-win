jQuery(document).ready(function(){
    jQuery(document).on('click', '.wpuep-exercise-favorite', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var button = jQuery(this);
        var icon = button.find('i');

        var exerciseId = button.data('exercise-id');

        var data = {
            action: 'favorite_exercise',
            security: wpuep_favorite_exercise.nonce,
            exercise_id: exerciseId
        };

        jQuery.post(wpuep_favorite_exercise.ajaxurl, data, function(html) {
            var icon1 = icon.data('icon');
            var icon2 = icon.data('icon-alt');

            if(icon.hasClass(icon1)) {
                icon.removeClass(icon1);
                icon.addClass(icon2);
            } else {
                icon.removeClass(icon2);
                icon.addClass(icon1);
            }

            if(button.next().hasClass('exercise-tooltip-content')) {
                var tooltip = button.next().find('.tooltip-shown').first();
                var tooltip_alt = button.next().find('.tooltip-alt').first();

                var tooltip_text = tooltip.html();
                var tooltip_alt_text = tooltip_alt.html();

                tooltip.html(tooltip_alt_text);
                tooltip_alt.html(tooltip_text);
            }
        });
    });
});