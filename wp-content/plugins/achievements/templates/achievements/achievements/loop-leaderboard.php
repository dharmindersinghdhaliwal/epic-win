<?php
/**
 * Leaderboard loop
 *
 * @package Achievements
 * @subpackage ThemeCompatibility
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php do_action( 'dpa_template_before_leaderboard_loop_block' ); ?>

<style>.upmeledprofi img{ width:32px;height:32px;float:left}</style>

<script type="text/javascript">jQuery(document).ready(function(e){(function($){$('.ledboardmem input').change(function(){$('#ledboardmem .genderMale').toggle($('.ledboardmem .chmale:checked').length>0);$('#ledboardmem .genderFemale').toggle($('.ledboardmem .chfemale:checked').length>0)})}(jQuery))});
</script>

<div class="ledboardmem">
	<input type="checkbox" value="male" checked class="chmale"> Male
	<input type="checkbox" value="female" checked class="chfemale"> Female
</div>

<table class="dpa-leaderboard-widget" id="ledboardmem">
	<caption class="screen-reader-text"><?php _e( 'A leaderboard of all users on the site who have earnt karma points. The table is sorted descendingly by the amount of karma points that the users have, and also shows the user&#8217;s name and karma points total.', 'dpa' ); ?></caption>
	<thead>
		<tr>
			<th id="dpa-leaderboard-position" scope="col" style="background-color:red;color:#fff !important;"><?php _ex( '#','user position column header for leaderboard table','dpa'); ?></th>
			<th id="dpa-leaderboard-name" scope="col" style="background-color:red;color:#fff !important;"><?php _ex( 'Name', 'user name column header for leaderboard table', 'dpa' ); ?></th>
			<th id="dpa-leaderboard-karma" scope="col" style="background-color:red;color:#fff !important;"><?php _ex( 'EPIC POINTS', 'column header for leaderboard table', 'dpa' ); ?></th>
		</tr>
	</thead>
	<tfoot class="screen-reader-text">
		<tr>
			<th scope="col"><?php _ex( '#', 'user position column header for leaderboard table', 'dpa' ); ?></th>
			<th scope="col"><?php _ex( 'Name', 'user name column header for leaderboard table', 'dpa' ); ?></th>
			<th scope="col"><?php _ex( 'Karma', 'column header for leaderboard table', 'dpa' ); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php do_action( 'dpa_template_before_leaderboard_loop' ); ?>
		
		<?php while(dpa_leaderboard_has_users()):dpa_the_leaderboard_user(); ?>
		<?php 
			$is_user_retired =	get_user_meta(dpa_get_leaderboard_user_id(),'retire_user',true);

			if(dpa_get_leaderboard_user_karma()>0 && empty( $is_user_retired )){ ?>
		<?php dpa_get_template_part( 'loop-single-leaderboard'); ?>
		<?php }?>
		<?php endwhile; ?>
		
		<?php do_action( 'dpa_template_after_leaderboard_loop'); ?>
	</tbody>
</table>
<?php do_action( 'dpa_template_after_leaderboard_loop_block'); ?>