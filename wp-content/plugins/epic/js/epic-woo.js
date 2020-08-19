jQuery(document).ready(function($) {
    
    $(".epic-woo-account-navigation-item").click(function(){
        $('.epic-woo-account-navigation-item').removeClass("epic-woo-active");
        $(this).addClass("epic-woo-active");
        var panel_id = $(this).attr("data-nav-ietm-id");
        $(".epic-woo-account-navigation-content").hide();
        $("#"+panel_id).show();
    });
    
    $(".epic-field-edit .epic-fire-editor").click(function(){
        $(".epic-woo-account-navigation-content").hide();
        $('.epic-woo-account-navigation-item').hide();
    });
    

});