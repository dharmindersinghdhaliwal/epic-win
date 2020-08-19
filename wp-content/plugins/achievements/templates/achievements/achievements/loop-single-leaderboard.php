<?php
if(!defined('ABSPATH')) exit;
$gender=get_user_meta(dpa_get_leaderboard_user_id(),'gender',true);
?>
<style>.current_login_user { color: #43bc9c;}</style>
<tr  id="dpa-leaderboard-user-<?php dpa_leaderboard_user_id(); ?>" class="gender<?php echo $gender; ?>">
	<?php do_action( 'dpa_template_in_leaderboard_loop_early'); ?>
    <th scope="row" headers="dpa-leaderboard-position" class="position">
		<?php do_action( 'dpa_template_before_leaderboard_position' );
        dpa_leaderboard_user_position();
        do_action( 'dpa_template_after_leaderboard_position' ); ?>
	</th>
	<td headers="dpa-leaderboard-name">
    	<?php do_action( 'dpa_template_before_leaderboard_name' );        
		if(get_current_user_id()==dpa_get_leaderboard_user_id()){
			$profile_url= site_url('/profile');
			?><font>
            <a class="current_login_user" href="<?php echo $profile_url; ?>"><?php
			global $epic;
			echo'<div class="upmeledprofi">'.$epic->pic(dpa_get_leaderboard_user_id(),75).'</div>';
			dpa_leaderboard_user_display_name(); ?></a></font><?php
        }
		else{
			?>
             <a href="<?php echo get_site_url(); ?>/member/?member=<?php dpa_leaderboard_user_id(); ?>">
			<?php
            global $epic;
			echo '<div class="upmeledprofi">'.$epic->pic(dpa_get_leaderboard_user_id(),75).'</div>';?>
			<?php dpa_leaderboard_user_display_name(); ?><!--</a>-->
			<?php
		}
		do_action( 'dpa_template_after_leaderboard_name' ); ?>
	</td>
	<td headers="dpa-leaderboard-karma" class="k_points" <?php echo $user_points; ?>>
		<?php do_action( 'dpa_template_before_leaderboard_karma' );
		 dpa_leaderboard_user_karma();
		 do_action( 'dpa_template_after_leaderboard_karma' ); ?>
	</td>
	<?php do_action( 'dpa_template_in_leaderboard_loop_late' ); ?>
</tr>