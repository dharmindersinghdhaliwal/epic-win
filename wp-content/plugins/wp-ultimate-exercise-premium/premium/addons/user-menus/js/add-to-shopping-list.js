jQuery(document).ready(function(e){(function($){	
$(document).on('click','.wpuep-exercise-add-to-shopping-list',function(e){
	console.log('Clicked');
	e.preventDefault();
	e.stopPropagation();
	
	var button = jQuery(this);
	if(!button.hasClass('in-shopping-list')) {
		var exerciseId = button.data('exercise-id');
		var exercise = button.parents('.wpuep-container');
		var servings = 0;
		// Check if there is a servings changer (both free and Premium)
		var servings_input = exercise.find('input.adjust-exercise-servings');
		if(servings_input.length == 0) {
			servings_input = exercise.find('input.advanced-adjust-exercise-servings');
		}
		// Take servings from serving changer if available
		if(servings_input.length != 0) {
			servings = parseInt(servings_input.val());
		}
		var data = {
			action:'add_to_shopping_list',
			security:wpuep_add_to_shopping_list.nonce,
			exercise_id: exerciseId,
			servings_wanted: servings
			};
		jQuery.post(wpuep_add_to_shopping_list.ajaxurl,data,function(html){
			button.addClass('in-shopping-list');
			if(button.next().hasClass('exercise-tooltip-content')){
				var tooltip = button.next().find('.tooltip-shown').first();
				var tooltip_alt = button.next().find('.tooltip-alt').first();
				var tooltip_text = tooltip.html();
				var tooltip_alt_text = tooltip_alt.html();
				tooltip.html(tooltip_alt_text);
				tooltip_alt.html(tooltip_text);
				
			}
		});
	}
});

}(jQuery))});