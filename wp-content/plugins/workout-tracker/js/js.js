$	=	jQuery;
var wt_tabs=function(uid){
	$(document).on('click','.wttabli',function(){
		cli	=	$(this).attr('wttab');
		$('.wttabul li').removeClass('wttabact');
		$('.wtttab').hide();
		$('.wtttab'+cli).show();
		$(this).addClass('wttabact');
	});

	$(document).on('click','.wttpst',function(){
		$('.wttlist li').removeClass('wttabact');	$(this).addClass('wttabact');
	});
};

	/*----	Toggle workout program list	----*/
var hideSHOW=function(){
	$(document).on('click','#hideSHOW',function(){
		$(".toggleli").toggle();
		if($(this).html()==='Expand'){
			$(this).html('Collapse');
		}
		else{
			$(this).html('Expand');
		}
	});
}
hideSHOW();

	/*--- WHEN SELECT A USER GET DEFAULT DATA	----*/

var wt_the_user_select=function(){
	$(document).on('click','.serch_user_notes',function(){
		var uid	=	$('#work_email').val();
		wt_the_user_data_load(uid);
	});
};
wt_the_user_select();

wt_the_user_data_load=function(uid){
	$('.workout-main').show();
	pids=[];
	$('.wttlist ul li.wttpst').each(function(){
		pids.push($(this).attr('pid'))
	});
	$('.wtttab1').show();pid	=	pids[0];
	$('.wttpst'+pid).addClass('wttabact');
	$('#wtuserid').val(uid);$('#loadingm').show();
	wt_sidebar_menu_user_posts(uid);
	wt_tabs(uid);
}

	/*---- 	Get User Post Siderbar Title	----*/
var wt_sidebar_menu_user_posts=function(uid){
	$.ajax({
		type:"POST",	url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'act':'sidebar_menu'},
		success:function(data){
			//if(data){
				$('.wt_ul').html(data);
				pids=[];
				$('.wttlist ul li.wttpst').each(function(){
					pids.push($(this).attr('pid'));
				});
				pid	=	pids[0];
				wt_program_data(uid,pid);
			//}
		}
	});
};

/*----	PROGRAM  TAB GET DATA AJAX		----*/
var wt_program_data=function(uid,pid){
	$.ajax({	type:"POST",	url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'get_data'},
		beforeSend: function() {
			$('#loadingm').show();
		},
		success:function(data){
			//if(data){
				$('.wtttab1').html(data);
				wt_strength_data(uid,pid);
			//}
		}
	});
};

/*----	STRENGTH TAB GET DATA AJAX		----*/
var wt_strength_data=function(uid,pid){
	$.ajax({
		type:"POST",
		url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'get_strength_data'},
		success:function(data){
			//if(data){
				$('.wtttab2').html(data);
				wt_volume_data(uid,pid);				
				wt_calculator_data(uid,pid);
			//}
		}
	});
};

/*----	VOLUME TAB GET DATA AJAX	----*/
var wt_volume_data=function(uid,pid){
	$.ajax({	type:"POST",url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'get_volume_data'},
		success:function(data){
			//if(data){
				$('.wtttab3').html(data);				
				wt_free_form_data(uid,pid);				
			//}
		}
	});
};

/*----	FREEFORM TAB GET DATA AJAX		----*/
var wt_free_form_data=function(uid,pid){
	$.ajax({	type:"POST",url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'get_free_form_data'},
		success:function(data){
			if(data){
				$('.wtttab4').html(data);
			}
		}
	});
};

/*----	CALCULATOR TAB GET DATA AJAX	----*/
var wt_calculator_data=function(uid,pid){
	$.ajax({	type:"POST",url:wp_ajax_url(),
		data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'get_calculator_data'},
		success:function(data){
			$('#loadingm h2').html('Success !');
			$('#loadingm .modal-body').html('<img src="http://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/success.gif" width="150" height="150">');
			$('#loadingm').delay(3000).hide(0, function(){
				$('#loadingm h2').html('Loading....');
				$('#loadingm .modal-body').html('<img src="http://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/x2_writing.gif" width="150" height="150">');
			});
			wt_get_workout_notes_ajax(pid,uid);
			retrive_payment_method(uid);
			if(data){
				$('.wtttab5').html(data);
			}
		}
	});
};

/*----	GET WORKOUT NOTES AJAX	----*/
var wt_get_workout_notes_ajax=function(pid,uid){
	jQuery.ajax({
		type:'POST',url:wp_ajax_url(),
		data:{'action':'get_workout_notes','uid':uid,'pid':pid},
		success:function(data){

			if(data){$('.display_comment').html('');
			$('.display_comment').html(data);
		}
		else{
			$('.display_comment').html('');
			$('.display_comment').html('<h3 style="color:red">Nothing Found For this Client</h3>');
			}
		}
	})
};
	
/*----	When click on a tracker post get user data	----*/
var wt_get_data_using_tracker_post = function(){
	$(document).on('click','.wttpst',function(){
		pid =	$(this).attr('pid');
		uid = 	$('#wtuserid').val();
		wt_program_data(uid,pid);
	});
};
wt_get_data_using_tracker_post();

/*----	GET EXERCISE LIST ON KEY UP AJAX	----*/
var wt_get_exercise_list_key_up=function(){
	$(document).on('change','.inputsrch',function(){
		var me	=	$(this);
		var li_no	=	$(me).parents('li').attr('no');
		$('.inputsrch').removeClass("ipsAct");
		me.addClass("ipsAct");
		var clickedval	=	$('.ipsAct').val();
		$(me).parents('li').find('span').html('<i class="fa fa-info-circle exinfo my_popup_open "aria-hidden="true"></i>');
		$('.secodary-exercise li[no='+li_no+']').html(clickedval);
		$('.wtttab2 #ewtsdt .wt_lable li[no='+li_no+']').html(clickedval);
		$('.wtttab3 #ewtsdt .wt_lable li[no='+li_no+']').html(clickedval);
	});
};
wt_get_exercise_list_key_up();

	/*---------------------------------------------------------------------*/
	/*					GET EXERCISE DATA FOR POPUP AJAX			   */
	/*-------------------------------------------------------------------*/
var wt_get_exercise_data_by_id=function(){
	$(document).on('click', '.exinfo',function(){
		var program_id	=	jQuery(this).parents('li').find('.inputsrch').attr('exeid');
		var url 			=	wp_ajax_url();
		jQuery.ajax({	type: 'POST',url: url,data:{
			action: 'exercise_detail_by_id_ajax',program_id: program_id
		},
		success: function(data) {
			if(data){$('#myModal').html(data);
			var modal	= document.getElementById('myModal');
			var span 	= document.getElementsByClassName("close")[0];modal.style.display = "block";
			span.onclick 	= function() {
			modal.style.display = "none";
			}
			window.onclick 	= function(event) {
				if (event.target == modal) {modal.style.display = "none";}
			}
		}
		else{
			swal("", "Program has not fetched .Please try again", "error");
		}
	},
	error: function(xhr) {alert("Error occured.please try again");}
	});
	});
};
wt_get_exercise_data_by_id();

/*-------------------------------------------------------------------*/
/*		CALCULATOR TAB GET WARM UP SET ACCORDING TO WEIGHT		*/
/*---------------------------------------------------------------*/
var wt_get_warm_up_set_weight=function(){
		$(document).on('change','.cal-weight li input',function(){
			var input_val	=	$(this).val();
			var li_no		=	$(this).parents('li').attr('no');
			var result	=	Math.round(input_val*0.6);$('.warmup li[no='+li_no+']').html(result);
		});
};
wt_get_warm_up_set_weight();

/*---------------------------------------------------------*/
/*		SEE FREE FORM DATA CALCULATIONS BY WEIGHT		*/
/*------------------------------------------------------*/
var wt_see_free_form_data_calculation=function(){
	$(document).on('change','.see-from-data .from-data-weight',function(){
		var weight	=	$(this).val();
		$('.reps12').html(Math.round(weight*.2));
		$('.reps10').html(Math.round(weight*.4));
		$('.reps6').html(Math.round(weight*.6));
		$('.reps3').html(Math.round(weight*.8));
		$('.reps1').html(Math.round(weight*1));
	});
};
wt_see_free_form_data_calculation();

/*---------------------------------------*/
/*		 SAVE  TRAINER NOTES  AJAX		*/
/*-------------------------------------*/
var wt_save_trainer_notes_ajax=function(metaid,comment,postid,num){
	jQuery.ajax({
		type:'POST',url:wp_ajax_url(),
		data:{'action':'save_workout_notes','metaid':metaid,'comment':comment,'postid':postid,'num':num,'act':'wt_save_trainer_notes'},
		success:function(data){
			wt_save_program_data_exercise_ajax(postid);
			if(data==1){
				$('.cmt').hide();
				$('.cmt').val('');
				$('.record_note').show();
				console.log('Notes Updated');
			}
			else{
				console.log(data);
				console.log('Unable to update Comment');
			}
		}
	})
};

/*---------------------------------------------------------------------------*/
/*			 SAVE  PROGRAM  TAB EXERCISE LIST  AJAX						*/
/*------------------------------------------------------------------------*/
var wt_save_program_data_exercise_ajax=function(postid){
	var exercises	=	new Array();
		$('.wt_lable .inputsrch').each(function(){
			var exeid	=	$(this).attr('exeid');exercises.push(exeid);
		});
	jQuery.ajax({	type:'POST',url:wp_ajax_url(),
		data:{'action':'save_workout_notes','exercises':exercises,'postid':postid,'act':'wt_save_program_data_exercise'},
		success:function(data){
			wt_save_free_form_data_ajax(postid);
			if(data==1){
				console.log('Exercise list updtaed');
			}
			else{
				console.log('Unable to update Exercise list');
			}
		}
	})
};

/*---------------------------------------------------------------------------*/
/*					 SAVE  FREE FORM DATA AJAX				 			*/
/*-------------------------------------------------------------------------*/
var wt_save_free_form_data_ajax=function(postid){
	var freefrom	=	$('.free-form-data').val();
	jQuery.ajax({
		type:'POST',url:wp_ajax_url(),
		data:{'action':'save_workout_notes','freefrom':freefrom,'postid':postid,'act':'wt_save_free_form_data'},
		success:function(data){
			wt_save_calculator_data_ajax(postid);
			if(data==1){
				console.log(data);
			}
			else{
				console.log('Unable to update form');
			}
		}
	})
};

/*---------------------------------------------------------------------------*/
/*				CALCULATOR TAB SAVE DATA AJAX							*/
/*-------------------------------------------------------------------------*/
var wt_save_calculator_data_ajax=function(postid){
	var inbetween		=	$('.pinn-exercise input').val();
	var txtarea		=	$('.pinn-exercise textarea').val();
	var rmwght		=	$('.from-data-weight').val();
	var reps	=	new Array();
	$('.reps').each(function(){
		var val	=	$(this).html();
		reps.push(val);
	});
	var calweight	=	new Array();
	$('.cal-weight li input').each(function(){
		var val	=	$(this).val();
		calweight.push(val);
	});
	var warmup	= new Array();
	var counter 	= 0;
	$('.warmup li').each(function(){
		if( counter == 0){
			counter  = 1;
		}
		else{
			var val = $(this).html();
			warmup.push(val);
			}
		});
	$.ajax({	type:"POST",url:wp_ajax_url(),
		data:{'action':'save_workout_notes','postid':postid,'inbetween':inbetween,'txtarea':txtarea,'rmwght':rmwght,'reps':reps,'calweight':calweight,'warmup':warmup,'act':'save_calulator_tab_data'},
		success:function(data){
			if(data){
				$('#loadingm h2').html('Success !');$('#loadingm .modal-body').html('<img src="http://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/success.gif" width="150" height="150">');
				$('#loadingm').delay(3000).hide(0, function(){
					$('#loadingm').hide();	$('#loadingm h2').html('Loading....');$('#loadingm .modal-body').html('<img src="http://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/x2_writing.gif" width="150" height="150">');
				});
				console.log(data);
			}
			else{
				console.log('error for calculate data');
			}
		}
	});
};

/*-----------------------------------------------------------------------------------*/
/*			INSTANT RESULT FOR BOTH STRENGTH DATA AND VOLUME DATA (TEMPORARY)		*/
/*---------------------------------------------------------------------------------*/
var temporary_display_data=function(uid,pid){
	$(document).on('change','.wt_data_li .wt_rep2',function(){
		var lino	=	$(this).parents('li').attr('lino');
		var str	=	$(this).parents('li').find('.wt_wet').val();
		var val1	=	$(this).parents('li').find('.wt_rep0').val();
		var val2	=	$(this).parents('li').find('.wt_rep1').val();
		var val3	=	$(this).parents('li').find('.wt_rep2').val();
		var weight_arr=	str.split("/"); // REMOVES SLASHS
		var	weight1	=	(weight_arr[0]);
		var	weight2	=	(weight_arr[1]);
		var	weight3	=	(weight_arr[2]);
		if(weight_arr.length==1){
			var	volume1	=	Volume_formula(weight1,val1);
			var	volume2	=	Volume_formula(weight1,val2);
			var	volume3	=	Volume_formula(weight1,val3);
			var strength1	=	Strenght_Data_formula(weight1,val1);
			var strength2	=	Strenght_Data_formula(weight1,val2);
			var strength3	=	Strenght_Data_formula(weight1,val3);
			var	volume	=	parseInt(volume1) + parseInt(volume2) + parseInt(volume3);
			var strength	=	strength1+'|'+strength2+'|'+strength3;
		}
		else{
			var strength1	=	Strenght_Data_formula(weight1,val1);
			var strength2	=	Strenght_Data_formula(weight2,val2);
			var strength3	=	Strenght_Data_formula(weight3,val3);
			var	volume1	=	Volume_formula(weight1,val1);
			var	volume2	=	Volume_formula(weight2,val2);
			var	volume3	=	Volume_formula(weight3,val3);
			var strength	=	strength1+'|'+strength2+'|'+strength3;
			var	volume	=	parseInt(volume1) + parseInt(volume2) + parseInt(volume3);
		}
		$('.wtttab2 .wt_data1 li[lino='+lino+']').html(strength);$('.wtttab3 .wt_data1 li[lino='+lino+']').html(volume);
	})
}
temporary_display_data();

var Strenght_Data_formula=function(weight,reps){
	var weight	=	parseInt(weight);return ((weight * 0.03) * parseInt(reps)) + weight;
}

var Volume_formula=function(weight,reps){
	var weight	=	parseInt(weight);
	var reps=parseInt(reps);
	return (weight*reps);
}

/**Update payment method**/
var update_payment_method=function(){
	$(document).on('click','#updateMethod',function(){
		var uid		= 	$('#work_email').val();
		var method	=	$('#paymentStyle').val();
		$.ajax({
			type:"POST",url:wp_ajax_url(),
			data:{'action':'update_payment_method','uid':uid,'method':method,'act':'update'},
			success:function(data){
				if(data){
					var res	=	$.parseJSON(data);
					if(res.response == 1){console.log(res);}
					else{console.log(res);}
				}
			}
		});
	});
}
update_payment_method();

/*----	Retrive payment method	----*/
var retrive_payment_method=function(uid){
	$.ajax({
		type:"POST",url:wp_ajax_url(),
		data:{'action':'update_payment_method','uid':uid,'act':'retrive'},
		success:function(data){
			if(data){
				var res	=	$.parseJSON(data);
				$('#paymentStyle').val(res.value);
			}
		}
	});
}

jQuery(document).ready(function(){(function($){
	/**
	* Function: play_audio
	*
	* Description: plays audio on click
	* param: audioid1 = id of starting
	* param: audioid2 = id of end audio
	* param: btnid = id of Button which is Responsible to paly audio
	* param: time = counter time
	**/
	play_audio=function(audioid1,audioid2,btnid,time){
		var audio1 = $(audioid1);
		var audio2 = $(audioid2);
		audio2[0].load();
		audio1[0].play();
		$(btnid).addClass("disabled");
		var number = parseInt($(btnid).text(),0) || time;
		var interval = setInterval(function () {
			$(btnid).text(number--);	$(btnid).prepend('<i class="fa fa-sticky-note fa-6" aria-hidden="true"></i>');
			if (number < 0) {audio2[0].play();clearInterval(interval);$(btnid).removeClass("disabled");}
		}, 1000);
	};
	
	/*----	Pop up	----*/
		$('#slide').popup({ focusdelay: 400,outline: true,vertical: 'top'});
		$('.add-table').click(function(){$('.add-table').hide();$('.post_title').show();$('.add-title').show();});

	/*---------------------------------------------------------------------------*/
	/*						ADD TITLE AJAX								*/
	/*--------------------------------------------------------------------------*/
	$('.add-title').click(function(){
		var title	=	$('.post_title').val();
		var uid	=	$('#work_email').val();
		if(title==''){
			alert('Please Add Title');
			return false;
		}
		if(uid==''){
			alert('Please Select User');
			return false;
		}
		else{
			jQuery.ajax({type:'POST',url:wp_ajax_url(),
			data:{'action':'add_post','title':title,'uid':uid},
			beforeSend: function(){
				$(".post_title").addClass('loading');
			},
			success:function(data){
				if(data){
					$(".post_title").removeClass('loading');
					$('.wt_ul').prepend('<li pid="'+data+'" class="wttpst wttpst'+data+'"><a pid="'+data+'" href="javascript:void(0)">'+title+'</a></li>');
					console.log('Created Post id :',data);
					$('#ewtwtt input').val('');	$('.wt-pid').val(data);
					$('.add-table').show();		$('.add-title').hide();
						$('.post_title').hide();	$('.post_title').val('');
					}
					else{
						alert('Unable to Add Title');
					}
				}
			})
		}
	});

	$('.record_note').click(function(){ $('.cmt').show(); $('.record_note').hide();});

	/*---------------------------------------------------------------------------*/
	/*					 SAVE TRAINSER NOTES								*/
	/*-------------------------------------------------------------------------*/
	$('.submit_note').click(function(){
		var metaid	= 	$('#work_email').val();
		var comment	=	$('.cmt').val();
		var num		=	$('.cnotes').length;
		var postid	=	$('.wttabact').attr('pid');
		if(metaid==''){
			alert('Please Select Client');
			return false;
		}
		wt_save_trainer_notes_ajax(metaid,comment,postid,num);	 // SAVE TRAINER NOTES
		var pid	=	postid;
		var uid	=	metaid;
		jQuery.ajax({	
			type:'POST',url:wp_ajax_url(),
			data:{'action':'get_workout_notes','uid':uid,'pid':pid},
			beforeSend: function(){$('#loadingm').show();},
			success:function(data){
				if(data){$('.display_comment').html('');$('.display_comment').html(data);}
				else{$('.display_comment').html('');$('.display_comment').html('<h3 style="color:red">Nothing Found For this Client</h3>');}
			}
		})
	});

	/*---------------------------------------------------------------------------*/
	/*				UPDATE USER TABLE USING POST ID						*/
	/*-------------------------------------------------------------------------*/
	var wt_update_user_table_data=function(uid,pid){
		$(document).on('change','.wt_wet,.wt_rep,.wt_dat',function(){
			var uid	=	$('#wtuid').val();
			var pid	=	$('#wtpid').val();
			var inp 	=	$(this);
			var val	=	inp.val();
			var dty 	=	inp.attr('dt');
			var li	=	inp.parents('li');
			var uln 	=	li.parents('ul').attr('ulno');
			var lin 	= 	li.attr('lino');
			var key 	=	'wt_'+pid+'_'+dty+'_'+uln+'_'+lin;
			$.ajax({
				type:"POST",	url:wp_ajax_url(),
				data:{'action':'wt_tab_get_user_data_ajax','uid':uid,'pid':pid,'act':'update_data','key':key,'val':val},
				success:function(data){
					if(data){
						console.log('Data added');
						console.log(key+':'+val);
					}
				}
			});
		});
	};
	wt_update_user_table_data();

}(jQuery))});