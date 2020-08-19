<?php 
require_once( '../../../../mpdf/mpdf.php' );
//	Create new mPDF Document
$mpdf = new mPDF( '', 'A4');

//	Get the current date
$exercise_date	=	date( 'd' ).'.'.date( 'm' ).'.'.date( 'y' );

$array1[ 'id' ]				=	'1262';
$array1[ 'image_link' ]		=	"http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/12/barbellsquatfull.jpg";
$array1[ 'instructions' ]	=	"Squat down by bending hips back while allowing knees to bend forward slightly||Toes should track inline with 2nd and 3rd toes||Keep back straight and chest up||Descend until thighs are just past parallel to floor. ||Extend hips and knees until legs are straight. Return and repeat.||Breathe in as your descend and breathe out as you stand back up";
$array1[ 'link' ]			=	"http://rohitwebguru.com/epicwinptmembers/exercise/barbell-squat/";
$array1[ 'name' ]			=	"Barbell Squat";

$array2[ 'id' ]				=	'1338';
$array2[ 'image_link' ]		=	"http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/12/sumosquatfull.jpg";
$array2[ 'instructions' ]	=	"Stand with your feet slightly outside shoulder width with your feet pointed out at 45 degrees||Ensure the bar is resting on your traps and not too high on your neck.||Bend at the knees and hips and lower your hips down so that your thighs are parallel to the ground. ||Keep your knees wide and do not let them collapse inwards||Stand up and squeeze glutes||Breathe in as you squats down and breathe out as you stand up. ";
$array2[ 'link' ]			=	"http://rohitwebguru.com/epicwinptmembers/exercise/sumo-squat/";
$array2[ 'name' ]			=	"Sumo Squat";


$array3[ 'id' ]				=	'1339';
$array3[ 'image_link' ]		=	"http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/12/sumosquatfull.jpg";
$array3[ 'instructions' ]	=	"Stand with your feet slightly outside shoulder width with your feet pointed out at 45 degrees||Ensure the bar is resting on your traps and not too high on your neck.||Bend at the knees and hips and lower your hips down so that your thighs are parallel to the ground. ||Keep your knees wide and do not let them collapse inwards||Stand up and squeeze glutes||Breathe in as you squats down and breathe out as you stand up. ";
$array3[ 'link' ]			=	"http://rohitwebguru.com/epicwinptmembers/exercise/sumo-squat/";
$array3[ 'name' ]			=	"Sumo Squats";

$exercises[]				=	(object)$array1;
$exercises[]				=	(object)$array2;
$exercises[]				=	(object)$array3;

$header	=	'<style type="text/css">.wpuep-container{ border:2px solid black;}</style>';
//	Get the header
$header .=	'<div style="margin: 0 auto;width:95% !important;">';
$header .= 		'<table width="100% !important;"><tr>';
$header .= 		'<td style="font-size:20px;" width="50%" valign="bottom"><span></span id="exercise_date">'.$exercise_date.' <span id="program_name">Jatinder Rehab Exercises</span></td>';
$header .= 		'<td width="50%" align="right"><img style="width:30% !important;" src="http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/07/logo.png" alt="" title=""></td>';
$header .= 		'</tr></table>';	
$header .= 		'<hr style="border: 3px outset #595955;">';

//	Get the content	
$content .= 	'<div id="exercises_div">';	

foreach( $exercises as $exercise )
{
	$content .= 	'<div class="wpuep-container" style="position:relative !important;height: 250px !important; vertical-align: inherit !important;left: 0px; top: 0px; background-color: rgb(255, 255, 255) !important;">';
	$content .= 	'<table style="width:100%;position:static !important;text-align:inherit !important;vertical-align:inherit !important"><tbody>';
	$content .= 	'<tr>';
	$content .= 	'<td valign="top" style="width:50% !important;text-align:inherit !important;height:auto !important;">';
	$content .= 	'<a href="'.$exercise->link.'" class="thickbox">';
	$content .= 	'<span class="wpuep-exercise-title" style="margin-left:5px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:30px !important;color:rgba(0,0,0,1) !important;">'.$exercise->name.'</span>';
	$content .= 	'<div><img src="'.$exercise->image_link.'" alt="'.$exercise->image_link.'" title="'.$exercise->image_link.'" class="wpuep-exercise-image" style="height:50% !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"></div></a><table class="wpuep-table" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;"><tbody><tr><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Weight</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="4" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Sets</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="4" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Reps/Time</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:80px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="4" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"></span></td></tr></tbody></table></td>';						
	$content .= 	'<td align="top" width="50%" style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="margin-left:10px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:18px !important;color:rgba(0,0,0,1) !important;">INSTRUCTIONS</span>';						
	$content .= 	'<ol class="wpuep-exercise-instructions" style="font-size:14px !important;height:350px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;">';

	if( $exercise->instructions != '' )
	{
		$instructions	=	explode( '||', $exercise->instructions );		
		
		for( $k=0; $k<sizeof($instructions); $k++ )
		{
			$content.=	'<li>'.$instructions[$k].'</li>';
		}
	}
	
	$content .= 	'</ol></td></tr></tbody></table>';
	$content .= 	'</tr></tbody></table>';
	$content .= 	'</div>';	
}

$content .= 	'</div>';

// Merge it all into the HTML
$html = $header . $content;

// Write HTML
$mpdf->WriteHTML( $html );
$pdf	=	$mpdf->Output( 'exercisesample.pdf', 'I' );

?>