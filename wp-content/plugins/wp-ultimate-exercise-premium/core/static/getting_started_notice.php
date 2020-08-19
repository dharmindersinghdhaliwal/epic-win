<div class="updated wpuep_notice">
    <div class="wpuep_notice_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('wpuep_hide_new_notice' => wp_create_nonce( 'wpuep_hide_new_notice' ) ) ) ); ?>"> <?php _e( 'Hide this message', 'wp-ultimate-exercise' ); ?></a>
    </div>
    <h3>Hi there!</h3>
    <p>It looks like you're new to <strong>WP Ultimate Exercise</strong>. Please check out our <a href="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_faq&sub=getting_started' ); ?>"><strong>Getting Started page</strong>!</a></p>
</div>