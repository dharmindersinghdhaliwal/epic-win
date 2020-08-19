<div class="wrap">
<h2><?php _e('epic - Sync / Tools','epic');?></h2>

<h3><?php _e('Auto Sync with WooCommerce','epic'); ?></h3>

<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>

<p><?php _e('Syncing with WooCommerce will automatically add WooCommerce customer profile fields to your epic. A quick way to have a WooCommerce account page integrated with epic. Just click the following button and let epic do the work for you.','epic'); ?></p>

<p><a href="<?php echo esc_url(add_query_arg( array('sync' => 'woocommerce') )); ?>" class="button button-secondary"><?php _e('Sync and keep existing fields','epic'); ?></a> 
<a href="<?php echo esc_url(add_query_arg( array('sync' => 'woocommerce_clean') )); ?>" class="button button-secondary"><?php _e('Sync and delete existing fields','epic'); ?></a></p>

<?php } else { ?>

<p><?php _e('Please install WooCommerce plugin first.','epic'); ?></p>

<?php } ?>
</div>