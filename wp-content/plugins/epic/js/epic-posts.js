jQuery(document).ready(function($) {
    /* Nice file upload */
    // Calling hidden and native element's action
//    $('.epic-fileupload').click(function(){
//        if($('#file_'+$(this).attr('id')).length > 0)
//            $('#file_'+$(this).attr('id')).click();
//    });
    
//    $( ".epic-post-features-active" ).hover(
//      function() {
//        var type = $(this).attr('epic-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(epicPosts.Messages.markAsUnRead);
//                break;
//            case 'favorite':
//                $( this ).html(epicPosts.Messages.markAsNotFavorite);
//                break;
//            case 'recommend':
//                $( this ).html(epicPosts.Messages.markAsNotRecommended);
//                break;
//        }
//        
//      }, function() {
//        var type = $(this).attr('epic-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(epicPosts.Messages.read);
//                break;
//            case 'favorite':
//                $( this ).html(epicPosts.Messages.favorite);
//                break;
//            case 'recommend':
//                $( this ).html(epicPosts.Messages.recommended);
//                break;
//        }
//      }
//    );
//    
//    $( ".epic-post-features-inactive" ).hover(
//      function() {
//        var type = $(this).attr('epic-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(epicPosts.Messages.markAsRead);
//                break;
//            case 'favorite':
//                $( this ).html(epicPosts.Messages.markAsFavorite);
//                break;
//            case 'recommend':
//                $( this ).html(epicPosts.Messages.markAsRecommended);
//                break;
//        }
//        
//      }, function() {
//        var type = $(this).attr('epic-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(epicPosts.Messages.unread);
//                break;
//            case 'favorite':
//                $( this ).html(epicPosts.Messages.notfavorite);
//                break;
//            case 'recommend':
//                $( this ).html(epicPosts.Messages.notrecommended);
//                break;
//        }
//      }
//    );
    
    
//    $( ".epic-post-features-reading-active" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsUnRead);
//      }, function() {
//        $( this ).html(epicPosts.Messages.read);
//      }
//    );
//    
//    $( ".epic-post-features-reading-inactive" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsRead);
//      }, function() {
//        $( this ).html(epicPosts.Messages.unread);
//      }
//    );
//    
//    $( ".epic-post-features-recommend-active" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsNotRecommended);
//      }, function() {
//        $( this ).html(epicPosts.Messages.recommended);
//      }
//    );
//    
//    $( ".epic-post-features-recommend-inactive" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsRecommended);
//      }, function() {
//        $( this ).html(epicPosts.Messages.notrecommended);
//      }
//    );
//    
//    $( ".epic-post-features-favorite-active" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsNotFavorite);
//      }, function() {
//        $( this ).html(epicPosts.Messages.favorite);
//      }
//    );
//    
//    $( ".epic-post-features-favorite-inactive" ).hover(
//      function() {
//        $( this ).html(epicPosts.Messages.markAsFavorite);
//      }, function() {
//        $( this ).html(epicPosts.Messages.notfavorite);
//      }
//    );
    
    
    $('.epic-post-features-panel').on('click','.epic-post-features-btn',function(){
        var type = $(this).attr('epic-data-type');
        var status = $(this).attr('epic-data-status');
        var btn = $(this);
        var btn_html = $(this).html();
        
        $(this).html(epicPosts.Messages.processing);
        
        jQuery.post(
                epicPosts.AdminAjax,
                {
                    'action': 'epic_change_post_feature_status',
                    'type'  :   type,
                    'status' : status,
                    'post_id' : epicPosts.Post_ID,
                },
                function(response){
 
                    if(response.status == 'success'){
       
                        var active_status = '';
                        var inactive_status = '';
                        if(response.type == 'read'){
                           active_status = epicPosts.Messages.read;
                           inactive_status = epicPosts.Messages.unread;
                        }else if(response.type == 'favorite'){
                           inactive_status = epicPosts.Messages.notfavorite;
                           active_status = epicPosts.Messages.favorite;
                        }else if(response.type == 'recommend'){
                           inactive_status = epicPosts.Messages.notrecommended;
                           active_status = epicPosts.Messages.recommended;
                        }
                        
                        if(response.post_status == '1'){
                            btn.attr('epic-data-status','active');
                            btn.removeClass('epic-post-features-inactive').addClass('epic-post-features-active');
                            btn.html(active_status);
                        }else{
                            btn.attr('epic-data-status','inactive');
                            btn.removeClass('epic-post-features-active').addClass('epic-post-features-inactive');
                            btn.html(inactive_status);
                        }
                        
                    }

                

        },"json");
    });
    
    
    
    
});



    