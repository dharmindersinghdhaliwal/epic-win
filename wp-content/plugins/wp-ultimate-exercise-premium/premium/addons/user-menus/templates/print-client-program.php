<?php 
ob_start();
require_once('../../../../../../../wp-blog-header.php');
$program_id		=	$_REQUEST['program_id'];			
$exercises		=	get_post_meta( $program_id, 'user-menus-exercises', TRUE );						

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>WP Ultimate Exercise Plugin</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>		
    <script>		
        jQuery(document).ready(function() {
			window.print();
        });		
    </script>
</head>
<body>
<?php
$upload_dir 	=	wp_upload_dir();

?>
<div style="margin: 0 auto;width:95% !important;">
	<table width="100% !important;">
		<tr>		
			<td style="font-size:20px;" width="50%" valign="bottom"><span></span id="exercise_date"><?php echo get_the_title( $program_id ); ?></span></td>			
			<td width="50%" align="right"><img style="width:30% !important;" src="<?php echo $upload_dir['baseurl']; ?>/2015/07/logo.png" alt="" title=""></td>			
		</tr>
	</table>
	
	<hr style="border: 3px outset #595955;">
	
	<div id="exercises_div">
<?php
		foreach( $exercises as $exercise )
		{
			echo '<div style="position:relative !important;height: 310px !important; border: 2px solid rgb(0, 0, 0) !important; vertical-align: inherit !important;left: 0px; top: 0px; background-color: rgb(255, 255, 255) !important;" class="wpupg-type-exercise wpuep-container">';
			echo '<table class="wpuep-table" style="width:100%;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><tbody>';
			echo '<tr>';
			echo '<td valign="top" style="width:50% !important;text-align:inherit !important;height:auto !important;">';
			echo '<a href="'.$exercise['link'].'">';
			echo '<span class="wpuep-exercise-title" style="margin-left:5px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:30px !important;color:rgba(0,0,0,1) !important;">'.$exercise['name'].'</span>';
			echo '<div><img src="'.$exercise['image_link'].'" alt="'.$exercise['image_link'].'" title="'.$exercise['name'].'" class="wpuep-exercise-image" style="width:100% !important;height: 230px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"></div></a>';
			echo '<table class="wpuep-table" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;"><tbody>';
			echo '<tr><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Weight</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['weight'].'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Sets</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['sets'].'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Reps/Time</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:80px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['reps'].'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"></span></td></tr></tbody></table></td>';						
			echo '<td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="margin-left:10px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:18px !important;color:rgba(0,0,0,1) !important;">INSTRUCTIONS</span>';						
			echo '<ol class="wpuep-exercise-instructions" style="font-size:14px !important;height:350px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;">';	
			
			if( $exercise['instructions'] != '' )
			{
				$instructions	=	explode( '||', $exercise['instructions'] );		
				
				for( $k=0; $k<sizeof($instructions); $k++ )
				{
					echo '<li>'.$instructions[$k].'</li>';
				}
			}	

			echo '</ol></td></tr></tbody></table>';
			echo '</tr></tbody></table>';
			echo '</div>';
		}

?>
	</div>
</div>
</body>
</html>