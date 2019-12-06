$(document).on('click','#btn-upload-photo', function(){
	$('#fileEmp').trigger('click');
});

$(document).on('change','#fileEmp', function(){
	var fd = new FormData();
    var files = $('#fileEmp')[0].files[0];
    fd.append('file',files);
    fd.append('token',$('#token').val());

    $.ajax({
        url: 'Profiles/upload_image',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
        	var response = jQuery.parseJSON(response);
        	$('#token').val(response.generated_token);
            $("#avatarUsr").attr("src", response.img); 
            $("#user-img").attr("src", response.img); 
        },
    });
});

$(document).on('click','#btn-change-password', function(){
    var form_data = $(this).serialize()+"&"+$.param({
        'old-password': $('#old-password').val(),
        'new-password': $('#new-password').val(),
        'confirm-password': $('#confirm-password').val(),
        token: $('#token').val()
    });
    $.when(request('Profiles/change_password', form_data)).done(function(response){
       $('#token').val(response.generated_token);
        if (response.success) {
            $('#old-password').val('');
            $('#new-password').val('');
            $('#confirm-password').val('');
            swal("Success", ""+response.msg+"", "success");
        } else {
            swal("Warning", ""+response.msg+"", "warning");
        }
    });
});