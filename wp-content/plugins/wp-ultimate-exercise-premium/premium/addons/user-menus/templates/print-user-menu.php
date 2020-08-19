<?php 
ob_start();
require_once('../../../../../../../wp-blog-header.php');

?><!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>WP Ultimate Exercise Plugin</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>		
    <script>		
        var wpuep = window.opener.wpuep_print;
        var user_menu = window.opener.wpuep_user_menus;
		
        document.title = wpuep.title;
		
        jQuery(document).ready(function() {

            // Set RTL if opener was in RTL
            if(wpuep.rtl) {
                jQuery('html').attr('dir', 'rtl')
                    .find('body').addClass('rtl');
            }

            var wpuep_printed = false;

            function startChecking()
            {
                checkForAjax()
                setTimeout(function(){
                    checkForAjax();
                }, 50);
            }

            function checkForAjax() {
                user_menu		=	 window.opener.wpuep_user_menus;				
				
                if(user_menu.print_exercises.length != 0) {
                    var html = '';
					jQuery( '#programtitle' ).val( user_menu.programtitle );
					
                    // Exercise Templates
                    if(user_menu.exercises.fonts) {
                        //	html += '<link rel="stylesheet" type="text/css" href="' + user_menu.exercises.fonts + '">';
                    }
					
                    for(var i = 0; i < user_menu.print_exercises.length; i++) {											
						var temp_exercise	=	user_menu.print_exercises[i];					
						
						html +=	'<div style="position:relative !important;height: 310px !important; border: 2px solid rgb(0, 0, 0) !important; vertical-align: inherit !important;left: 0px; top: 0px; background-color: rgb(255, 255, 255) !important;" class="wpupg-type-exercise wpuep-container">';
						html +=	'<table class="wpuep-table" style="width:100%;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><tbody>';
						html +=	'<tr>';
						html +=	'<td valign="top" style="width:50% !important;text-align:inherit !important;height:auto !important;">';
						html +=	'<a href="'+temp_exercise.link+'" class="thickbox">';
						html +=	'<span class="wpuep-exercise-title" style="margin-left:5px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:30px !important;color:rgba(0,0,0,1) !important;">'+temp_exercise.name+'</span>';
						html +=	'<div><img src="'+temp_exercise.image_link+'" alt="'+temp_exercise.image_link+'" title="'+temp_exercise.image_link+'" class="wpuep-exercise-image" style="width:100% !important;height: 230px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"></div></a><table class="wpuep-table" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;"><tbody><tr><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Weight</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'+temp_exercise.weight+'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Sets</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'+temp_exercise.sets+'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px !important;color:rgba(0,0,0,1) !important;">Reps/Time</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:80px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'+temp_exercise.reps+'" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"></span></td></tr></tbody></table></td>';						
						html +=	'<td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="margin-left:10px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:18px !important;color:rgba(0,0,0,1) !important;">INSTRUCTIONS</span>';						
						html +=	'<ol class="wpuep-exercise-instructions" style="font-size:14px !important;height:350px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;">';
						
						if( temp_exercise.instructions != '' )
						{
							var instructions	=	temp_exercise.instructions.split( "||" );
							
							for( k=0; k<instructions.length; k++ )
							{
								html+= '<li>'+instructions[k]+'</li>';
							}
						}

						
						html +=	'</ol></td></tr></tbody></table>';
						html +=	'</tr></tbody></table>';
						html +=	'</div>';						
                    }					
					
                    jQuery( '#exercises_div' ).html(html);
                    //	adjustServings();

                    if( !wpuep_printed ) {
                        setTimeout(function() {
                            window.print();
                        }, 1000); // TODO Check if everything is actually loaded
                        wpuep_printed = true;
                    }
                } else {
                    setTimeout(function() {
                        checkForAjax();
                    }, 50);
                }
            }

            function adjustServings()
            {
                for(var i = 0; i < user_menu.exercises.templates.length; i++) {
                    var exercise_id = user_menu.exercise_ids[i]
                    var ingredientList = jQuery('#wpuep-container-exercise-' + exercise_id + ' .wpuep-exercise-ingredients');

                    // Adjust Servings
                    var old_servings = user_menu.print_servings_original[i];
                    var new_servings = user_menu.print_servings_wanted[i];

                    if(old_servings != new_servings) {
                        window.opener.ExerciseUnitConversion.adjustServings(ingredientList, old_servings, new_servings)
                        jQuery('#wpuep-container-exercise-' + exercise_id + ' .wpuep-exercise-servings').text(new_servings);
                    }

                    // Adjust System
                    var new_system = user_menu.print_unit_system;
                    var old_system = window.opener.ExerciseUnitConversion.determineIngredientListSystem(ingredientList);

                    if(old_system != new_system) {
                        window.opener.ExerciseUnitConversion.updateIngredients(ingredientList, old_system, new_system);
                    }
                }
            }

            startChecking();
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
			<td style="font-size:20px;" width="50%" valign="bottom"><span></span id="exercise_date"><?php echo $_REQUEST[ 'programtitle' ] ?></span></td>			
			<td width="50%" align="right"><img style="width:30% !important;" src="<?php echo $upload_dir['baseurl']; ?>/2015/07/logo.png" alt="" title=""></td>			
		</tr>
	</table>
	
	<hr style="border: 3px outset #595955;">
	
	<div id="exercises_div">
	
	</div>
</div>
</body>
</html>