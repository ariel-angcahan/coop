$(document).on('mousedown','.widgets', function(){
	$(this).find('.number').attr('contenteditable', true);
});
$(document).on('blur','.widgets', function(){
	$(this).find('.number').removeAttr('contenteditable');
	var rate = $(this).find('.number');
	rate.html(numeral(rate.html()).format("0,0.00"));
});


// $(document).on('blur','.widgets', function(){
// 	var rate = $(this).find('.number').html();
// 	var para = $(this).find('.number').attr("data-for");

//     var form_data = $(this).serialize()+"&" +$.param({
//         "rate": rate,
//         "para": para,
//         token: $('#token').val()
//     });

//     $.when(request('LoanSettings/update', form_data)).done(function(response){
//         $('#token').val(response.generated_token);
//         if(response.success){
//             swal({
//                 title: "Success!", 
//                 text: response.msg, 
//                 type: "success"
//             }, function(){
//                 location.reload();
//             });
//         }else{
//             swal("System Error!", response.msg, "warning");
//         }
//     });
// });