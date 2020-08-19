jQuery( document ).ready(function(){
	function getDate( element ) {
   	var date;
    	try {
      	date = $.datepicker.parseDate( dateFormat, element.value );
    	} catch( error ) {
      	date = null;
    	}
 
    	return date;
  }
  
  var dateFormat = "dd/mm/yy";
  var from = $( "#trainer_start_date" ).datepicker({defaultDate: "+1w",changeMonth: true,numberOfMonths: 2,maxDate: '0' }).on( "change", function() {
        to.datepicker( "option", "minDate", getDate( this ) ); }),to = $( "#trainer_end_date" ).datepicker({ defaultDate: "+1w",changeMonth: true,numberOfMonths: 2,maxDate: '0' })
    .on( "change", function() { from.datepicker( "option", "maxDate", getDate( this ) ); });      
  jQuery( '#search_trainer_data' ).click(function(){
    //  Get Start and End Date
    var trainer_start_date  = jQuery.trim( jQuery( '#trainer_start_date' ).val() );
    var trainer_end_date    = jQuery.trim( jQuery( '#trainer_end_date' ).val() );

    if( trainer_start_date == '' ){
      swal("", "Please select start date", "error");
    }else if( trainer_start_date == '' ){
      swal("", "Please select end date", "error");
    }else{
      var data  = {
                    action: 'search_trainer_report',                
                    start_date:  trainer_start_date,
                    end_date:    trainer_end_date
                  };

      jQuery.ajax({
        type: 'POST',
        url: jQuery( '#admin_ajax_url' ).val(),
        data: data,
        beforeSend: function() {
          swal({
            title:"",
            text:"Please wait...",
            imageUrl: 'http://members.epicwinpt.com.au/wp-content/plugins/wp-ultimate-exercise-premium/core/img/write.gif'
          });
        },
        success: function(data) {
          console.log(data);
          //  return false; 
          var result  = jQuery.parseJSON( data );
          if( result.response == 1 ){
            swal(
              {   
                title: "Trainer Report",
                text: "Trainer Report has been successfully found",
                imageUrl: 'http://members.epicwinpt.com.au/wp-content/plugins/wp-ultimate-exercise-premium/core/img/x2_jump.gif',
                //type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok", 
                closeOnConfirm: false 
              },
              function(){
                jQuery( '#trainer_information_block' ).empty();
                jQuery( '#trainer_information_block' ).html( result.html);
                swal.close();
              }
            );
          }
          else{swal("", "Trainer Report has not found.Please try again", "error");}
        },
        error: function(xhr) { // if error occured
          alert("Error occured.please try again");  
        }
      });
    }
  });
});