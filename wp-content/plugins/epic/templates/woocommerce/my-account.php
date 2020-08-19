<?php
global $epic_template_loader;  

$current_user = wp_get_current_user();

wc_print_notices(); 

$account_tab_status     = apply_filters('epic_woo_account_tab_status',true,array( 'user_id' => $current_user->ID ));
$review_tab_status      = apply_filters('epic_woo_reviews_tab_status',true,array( 'user_id' => $current_user->ID ));
$downloads_tab_status   = apply_filters('epic_woo_downloads_tab_status',true,array( 'user_id' => $current_user->ID ));
$orders_tab_status      = apply_filters('epic_woo_orders_tab_status',true,array( 'user_id' => $current_user->ID ));

?>

<div id="epic-woo-account" class="woocommerce">
    
    <div id="epic-woo-account-navigation" >
        
        <?php if($account_tab_status) { ?>
        <div class="epic-woo-account-navigation-item" data-nav-ietm-id="epic-woo-account-info" >
            <?php echo __('Account Info','epic'); ?>
        </div>
        <?php } ?>
        
        <?php if($review_tab_status) { ?>
        <div class="epic-woo-account-navigation-item" data-nav-ietm-id="epic-woo-my-reviews" >
            <?php echo __('My Reviews','epic'); ?>
        </div>
        <?php } ?>
        
        <?php if($downloads_tab_status) { ?>
        <div class="epic-woo-account-navigation-item" data-nav-ietm-id="epic-woo-my-downloads" >
            <?php echo __('My Downloads','epic'); ?>
        </div>
        <?php } ?>
        
        <?php if($orders_tab_status) { ?>
        <div class="epic-woo-account-navigation-item" data-nav-ietm-id="epic-woo-my-orders" >
            <?php echo __('My Orders','epic'); ?>
        </div>
        <?php } ?>
        
        <div class="epic-woo-clear"></div>
    </div>
    
    <?php do_action( 'woocommerce_before_my_account' ); ?>
    
    
    <?php if($account_tab_status) { ?>
    <div id="epic-woo-account-info" class="myaccount_user epic-woo-account-navigation-content">
        <?php
        printf(
            __( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
            $current_user->display_name,
            wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
        );

        printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and edit your password and account details.', 'epic' ));
        ?>
        
        
        <?php $epic_template_loader->get_template_part('my-address'); ?>
    </div>
    <?php } ?>

    <?php if($downloads_tab_status) { ?>
    <div id="epic-woo-my-downloads" class="epic-woo-account-navigation-content" style="display:none" >
        <?php $epic_template_loader->get_template_part('my-downloads');  ?>
    </div>
    <?php } ?>

    <?php if($review_tab_status) { ?>
    <div id="epic-woo-my-reviews" class="epic-woo-account-navigation-content" style="display:none" >
        <?php $epic_template_loader->get_template_part('my-reviews');  ?>
    </div>
    <?php } ?>

    <?php if($orders_tab_status) { ?>
    <div id="epic-woo-my-orders" class="epic-woo-account-navigation-content" style="display:none"  >
        <?php $epic_template_loader->get_template_part('my-orders');  ?>
    </div>
    <?php } ?>

    <?php do_action( 'woocommerce_after_my_account' ); ?>
    
</div>
