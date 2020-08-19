<?php
/**
 * Achievements loop
 *
 * @package Achievements
 * @subpackage ThemeCompatibility
 */
// Exit if accessed directly
if(!defined('ABSPATH')) exit;
do_action('dpa_template_before_achievements_loop_block'); ?>
<table class="dpa-archive-achievements">
	<caption class="screen-reader-text"><?php _e( 'All of the available achievements with the name, avatar, and karma points for each.','dpa'); ?></caption>
	<thead>
		<tr>
        	<th id="dpa-archive-achievements-name" scope="col"></th>
			<th id="dpa-archive-achievements-name" scope="col"><?php _ex('Achievement Name','column header for list of achievements','dpa'); ?></th>
			<th id="dpa-archive-achievements-karma" scope="col"><?php _ex('EPIC Points','column header for list of achievements','dpa'); ?></th>
			<th id="dpa-archive-achievements-excerpt" scope="col"><?php _ex('Description','column header for list of achievements','dpa'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
        	<th scope="col"></th>
			<th scope="col"><?php _ex('Achievement Name','column header for list of achievements','dpa'); ?></th>
			<th scope="col"><?php _ex('EPIC Points','column header for list of achievements','dpa'); ?></th>
			<th scope="col"><?php _ex('Description','column header for list of achievements','dpa'); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php
		do_action('dpa_template_before_achievements_loop');
		while(dpa_achievements()):dpa_the_achievement();
			dpa_get_template_part('loop-single-achievement');
		endwhile;
		do_action('dpa_template_after_achievements_loop'); ?>
	</tbody>
</table>
<?php do_action('dpa_template_after_achievements_loop_block'); ?>