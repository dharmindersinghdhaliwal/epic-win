jQuery(document).ready(function() {
    // Only keep first exercise
    jQuery('.wpuep-container').first().addClass('wpuep-container-keep');

    // Indicate which elements are parent to the exercise
    jQuery('.wpuep-container-keep').parents().each(function() {
        jQuery(this).addClass('wpuep-container-keep-parent');
    });

    // Remove all elements except for the exercise and its parents
    jQuery('body *').not('.wpuep-container-keep-parent, .wpuep-container-keep, .wpuep-container-keep *').remove();

    jQuery('body').prepend('<div style="text-align: center; margin-bottom: 5px;"><select onchange="wpuep_preview_type(this.value)"><option value="desktop">Desktop Preview</option><option value="mobile">Mobile Preview</option></select></div>');

    wpuep_preview_type('desktop');
});

var wpuep_preview_type = function(type) {
    if(type == 'desktop') {
        jQuery('.wpuep-container').removeClass('wpuep-mobile-preview');
        jQuery('.wpuep-responsive-mobile').css('display', 'none');
        jQuery('.wpuep-responsive-desktop').css('display', 'block');
    } else {
        jQuery('.wpuep-container').addClass('wpuep-mobile-preview');
        jQuery('.wpuep-responsive-mobile').css('display', 'block');
        jQuery('.wpuep-responsive-desktop').css('display', 'none');
    }
}