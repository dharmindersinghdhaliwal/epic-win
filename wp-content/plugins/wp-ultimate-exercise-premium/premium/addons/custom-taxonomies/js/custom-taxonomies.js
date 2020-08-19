jQuery(document).ready(function() {
    jQuery('.wpuep-edit-tag').on('click', function() {
        var tag = jQuery(this).data('tag');

        var singular = jQuery(this).parents('tr').find('.singular-name').text();
        var name = jQuery(this).parents('tr').find('.name').text();
        var slug = jQuery(this).parents('tr').find('.slug').text();

        jQuery('input#wpuep_edit_tag_name').val(tag);
        jQuery('input#wpuep_custom_taxonomy_singular_name').val(singular);
        jQuery('input#wpuep_custom_taxonomy_name').val(name);
        jQuery('input#wpuep_custom_taxonomy_slug').val(slug);

        jQuery('#wpuep_editing_tag').text(tag);

        jQuery('.wpuep_adding').hide();
        jQuery('.wpuep_editing').show();
    });

    jQuery('#wpuep_cancel_editing').on('click', function() {
        jQuery('input#wpuep_edit_tag_name').val('');
        jQuery('input#wpuep_custom_taxonomy_singular_name').val('');
        jQuery('input#wpuep_custom_taxonomy_name').val('');
        jQuery('input#wpuep_custom_taxonomy_slug').val('');

        jQuery('.wpuep_adding').show();
        jQuery('.wpuep_editing').hide();
    });
});