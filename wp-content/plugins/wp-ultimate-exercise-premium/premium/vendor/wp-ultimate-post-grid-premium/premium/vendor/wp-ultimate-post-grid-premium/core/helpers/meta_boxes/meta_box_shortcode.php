<?php
// Egrid should never be null. Construct just allows easy access to WPUPG_Egrid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Egrid(0);
?>

<?php
if( $grid->post()->post_status !== 'publish' ) {
    _e( 'You have to publish the grid first.', 'wp-eultimate-post-grid' );
} else {
?>
    <strong>Egrid</strong><br/>
    [wpupg-grid id="<?php echo $grid->post()->post_name; ?>"]
    <?php
    if( $grid->filter_type() !== 'none' ) {
    ?>
    <br/><br/>
    <strong>Filter</strong><br/>
    [wpupg-filter id="<?php echo $grid->post()->post_name; ?>"]
    <?php } ?>
<?php } ?>