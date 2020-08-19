jQuery(document).ready(function() {
    if(jQuery('.exercise-tooltip').length) {
        jQuery('.exercise-tooltip').jt_tooltip({
            offset: [-10, 0],
            effect: 'fade',
            delay: 250,
            relative: true
        });
    }
});