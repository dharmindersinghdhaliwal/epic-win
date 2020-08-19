jQuery(document).ready(function() {
    // Select2
    jQuery('#wpuep_user_submission_form select[multiple]').select2({
        allowClear: true,
        width: 'off',
        dropdownAutoWidth: false
    });
});