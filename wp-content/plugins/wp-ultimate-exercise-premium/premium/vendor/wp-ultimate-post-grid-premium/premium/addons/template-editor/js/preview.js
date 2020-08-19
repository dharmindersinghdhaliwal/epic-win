var WPUltimateEPostGrid = WPUltimateEPostGrid || {};

jQuery(document).ready(function() {
    // Only keep first exercise
    jQuery('.wpupg-egrid').first().addClass('wpupg-keep');

    // Indicate which elements are parent to the exercise
    jQuery('.wpupg-keep').parents().each(function() {
        jQuery(this).addClass('wpupg-keep-parent');
    });

    // Remove all elements except for the exercise and its parents
    jQuery('body *').not('.wpupg-keep-parent, .wpupg-keep, .wpupg-keep *').remove();
});