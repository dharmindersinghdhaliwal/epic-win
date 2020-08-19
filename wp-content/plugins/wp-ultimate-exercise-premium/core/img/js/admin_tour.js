jQuery(document).ready( function($) {
    wpuep_open_pointer(0);

    function wpuep_open_pointer(i) {
        pointer = wpuep_admin_tour.pointers[i];

        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });

        $(pointer.target).pointer( options ).pointer('open');
    }
});