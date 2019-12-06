var registered_list = $('#registered_list').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "lengthChange": false,
    "ordering": false,
    "info":     false,
    "ajax": {  
        type:"POST",
        url: 'Applications/registered_list',  // method  , by default get
        global: false,
        data: function (response){
            response.token = $('#token').val();
        }
    }
});

$('body').on('click','.btn-preview-info', function(){

    var form_data = $(this).serialize()+"&" +$.param({
        id: $(this).attr("data-id"),
        token: $('#token').val()
    });

    $.when(request('Applications/get_application_information', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        $('#applicant-image').attr("src", "");
        if(response.success){
            $('#full_name').html(`${response.first_name} ${response.middle_name} ${response.last_name}`);
            $('#birth_date').html(response.birth_date);
            $('#email').html(response.email);
            $('#applicant-image').attr("src", response.image_name);
            $('#mobile').html(response.mobile_no);
            $('#market_address').html(response.market_address);
            $('#membership_type').html(response.membership_type);
            $('#payment_mode').html(response.payment_mode);
            $('#subscription_amount').html(response.subscription_amount);
            $('#payment_per_mode').html(response.payment_per_mode);
            $('#image-upload').attr("data-id", response.id);
            $('#preview-info').modal('show');
        }else{
           swal("Warning", response.msg, "warning");
        }
    });
});

$('body').on('click','#btn-upload', function(){
    $('#image_file').click();
});

$(document).ready(function() {
    Dropzone.autoDiscover = false;
    $("div#image-upload").dropzone({ 
        url: "Applications/upload_image",
        dataType: "json",
        acceptedFiles: ".jpg,.jpeg,.JPG,.png",
        // addRemoveLinks: true,
        success: function(file, response){
            var obj = jQuery.parseJSON(response);
            $("#applicant-image").attr("src", obj.img);
            $('#token').val(obj.generated_token);
            if (obj.success) {
                $('#btn-upload').click();
                swal({
                  title: "Success!",
                  text: obj.msg,
                  type: "success",
                }, function(){
                    //$('#collapse_upload').click();
                    $('.dz-complete').remove();
                    $('.dz-message').attr('style','display:block');
                });

            } else {

                swal("Warning", obj.msg, "warning");
            }
        },
        // removedfile: function(file) {
        //     var name = file.name;

        //     $.ajax({
        //         type: "post",
        //         url: "Applications/image_remove",
        //         data: { file: name, token: $('#token').val()},
        //         dataType: 'html'
        //     });

        //     // remove the thumbnail
        //     var previewElement;
        //     return (previewElement = file.previewElement) != null ? (previewElement.parentNode.removeChild(file.previewElement)) : (void 0);
        // },
        sending:function(file, xhr, formData){
            formData.append('token', $('#token').val());
            formData.append('applicant-id', $('#image-upload').attr('data-id'));
            formData.append('file-name', file.name);
            formData.append('applicant-name',$('#full_name').html());
        }
    });
});

$('body').on('click','#btn-approve', function(){
     swal({
        title: "Are you sure?",
        text: "You will be able to approve this application!",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            var data = $(this).serialize()+"&" +$.param({
                'application-id': $('#image-upload').attr('data-id'),
                token: $('#token').val()
            });

            $.when(request('Applications/approve_application', data)).done(function(response){
                if(response.success){
                    swal("Success", response.msg, "success");
                    registered_list.ajax.reload();
                    $('#preview-info').modal('toggle');
                }else{
                    swal("Warning", response.msg, "warning");
                }
            });
        } else {
            swal("Cancelled", "This application is cancelled!", "error");
        }
    });
});