<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $egrid ) ) $egrid = new WPUPG_Egrid(0);
?>

<?php
if( $egrid->post()->post_status !== 'publish' ) {
    _e( 'You have to publish the grid first.', 'wp-eultimate-post-grid' );
} else {
?>
    <strong>Egrid</strong><br/>
    [wpupg-egrid id="<?php echo $egrid->post()->post_name; ?>"]
    <?php
    if( $egrid->filter_type() !== 'none' ) {
    ?>
    <br/><br/>
    <strong>Filter</strong><br/>
    [wpupg-efilter id="<?php echo $egrid->post()->post_name; ?>"]
    <?php } ?>
<?php } ?>