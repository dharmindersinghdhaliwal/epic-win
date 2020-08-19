var $	=	jQuery;

var searchAttendance_by_date=function(){
	$(document).on('click','#searchAttendance',function(){
		var date	=	$('#attendanceDate').val();
		swal({ title:'Please Wait...', text:'Processing..',type:'info',showCancelButton: false, showConfirmButton: false});
		$.ajax({
			type:'POST',url:wp_ajax_url(),data:{'action':'epic_attendance_search_ajax','act':1,'date':date},
			success:function(data){
				swal.close();
				if(data){$('#attendanceHtml').html(data);}
				else{$('#attendanceHtml').html('Nothing Found');}
			}
		});
	});
}
searchAttendance_by_date();

/*Js for [attendance_achievements]*/
var attendance_achievements_Btn=function(){
	$(document).on('click','.attendance-achievements-right ul a',function(){
		var class_name	=	$(this).attr('class');
		var uid			=	$(this).parent('li').attr('uid');
		var tid			=	$(this).parent('li').attr('id');
		var current_status	=	$(this).attr('status');
		var num			=	$(this).attr('num');
		if(current_status==0){
			$(this).attr('status',1).text('Success').removeClass('not-active ').addClass('active');			
		}
		if(current_status==1){
			$(this).attr('status',0).text('Unlock').removeClass('active ').addClass('not-active');			
		}
		var status_arr		=	new Array();
		$(this).parent('li').children('a').each(function(index, element) {
			status_arr.push($(this).attr('status'));
		});
		swal({ title:'Please Wait...', text:'Processing..',type:'info',showCancelButton: false, showConfirmButton: false});
		$.ajax({
			type:'POST',url:wp_ajax_url(),data:{'action':'epic_attendance_achievements_ajax','status_arr':status_arr,'uid':uid,'act':current_status,'num':num,'tid':tid},
			success:function(data){
				console.log(data);
				var res	=	$.parseJSON(data);
				if(res.response == 1){
					console.log(res.message);					
					swal("", "Good job!", "success");					
					$('#'+res.uid).find('.info-count').text(res.unlocked);
				}
				else{
					swal("", res.message, "error");
				}
			}
		}); //ajax Ends
	});
}
attendance_achievements_Btn();
var epic_unlock_custom_achievements=function(){
	$(document).on('click','.info-remind span',function(){
		var id	=	$(this).attr('id');
		var uid	=	$(this).attr('uid');
			swal({ title:'Please Wait...', text:'Processing..',type:'info',showCancelButton: false, showConfirmButton: false});
		$.ajax({
			type:'POST',url:wp_ajax_url(),data:{'action':'epic_attendance_achievements_ajax','uid':uid,'act':3,'id':id},
			success:function(data){
				console.log(data);
				var res	=	$.parseJSON(data);
				if(res.response == 1){
					console.log(res.message);					
					swal("", "Good job!", "success");					
					//$('#'+res.uid).find('.info-count').text(res.unlocked);
				}
				else{
					swal("", res.message, "error");
				}
			}
		}); //ajax Ends
	});
}

epic_unlock_custom_achievements();