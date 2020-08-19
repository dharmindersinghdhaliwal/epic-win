'use strict';
function OpenMediaFrame (inp){
    
    // Create WP media gallery window
    var mediaFrame = wp.media({ 
        title: 'Upload Images',
        // mutiple: true if you want to upload multiple files at once
        multiple: 'add'
    });
    
    // Open WP media gallery window
    mediaFrame.on('open',function() {
        var selection = mediaFrame.state().get('selection');
        if(jQuery(inp).val().length > 0){
            var ids = jQuery(inp).val().split(',');
            console.log(ids);
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        }
    });
    
    // On select images in WP media gallery window
    mediaFrame.on('select', function(e){ 
        var selection = mediaFrame.state().get('selection');
        var imagesIDs = [];
        selection.map( function( attachment ) {
            // Get selected images IDs
            attachment = attachment.toJSON();
            for(var k in attachment) {
                if(k == "id"){
                    imagesIDs.push(attachment[k]);
                }
            }
        });
        jQuery(inp).val(imagesIDs);
    });
    
    mediaFrame.open();
}








//jQuery(document).ready(function($) {
//    $(document).on("click", ".upload_image_button", function() {
//
//        jQuery.data(document.body, 'prevElement', $(this).prev());
//
//        window.send_to_editor = function(html) {
//            var imgurl = jQuery('img',html).attr('src');
//            var inputText = jQuery.data(document.body, 'prevElement');
//
//            if(inputText != undefined && inputText != '')
//            {
//                inputText.val(imgurl);
//            }
//
//            tb_remove();
//        };
//
//        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
//        return false;
//    });
//});