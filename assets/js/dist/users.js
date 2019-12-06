$(document).ready(function() {
    users.init();
});

$(document).on('click','#btnAddUser', function(){
    $('#addUsers').modal('toggle');
});

var tbl_user = $('#tblUsersList').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "lengthChange": false,
    "ajax": {  
        type:"POST",
        url: 'Users/users_list',
        data: {token: $('#token').val()},
        error: function() {
            $(".tblUsersList-grid-error").html(""); 
            $("#tblUsersList-grid").append('<tbody class="tblUsersList-grid-error"><tr><th class="text-center" colspan="4">No data found in the server</th></tr></tbody>'); 
            $("#tblUsersList-grid_processing").css("display", "none");
        },
        dataSrc: function(r) {
            $('#token').val(r.generated_token);
            return r.data;
        }
    }
});

$(document).on('focusout','#add-username', function(){
    var data = $(this).serialize() + "&" + $.param({
        username: $('#add-username').val(),
        token: $('#token').val()
    });

    $.when(request('Users/check_username2', data, false)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            swal("Warning", response.msg, "warning");
            $('#add-username').val('');
        }
    });
});

$(document).on('click','.btn-delete-user', function(){
    var data = $(this).serialize() + "&" + $.param({
        UAId: $(this).attr('data-id'),
        token: $('#token').val()
    });

    $.when(request('Users/delete_user', data, false)).done(function(response){
        $('#token').val(response.generated_token);
        tbl_user.ajax.reload();
        swal("Success", "User Remove successfully!", "success");
    });
});

$(document).on('click','.btn_edit_user', function() {
    var UAId = $(this).attr("data-id");
    var token = $('#token').val();

    $.ajax({
        type: "POST",
        url: "Users/get_user",
        data: {UAId:UAId,token:token},
        dataType: 'json',
        success: function(r) {
            $('#token').val(r.generated_token);

            if (r.success) {
                var user = r.user;

                $('#select-edit-role').html(r.role).selectpicker('refresh');
                $('#image-date').html(`Last uploaded date: ${user.image_date}`);
                $('#edit-first-name').val(user.fname);
                $('#edit-last-name').val(user.lname);
                $('#preview-edit-image-user').attr('src', user.avatar);
                $('#preview-edit-image-user-name').html(`${user.lname}, ${user.fname}`);
                $('#selected-edit-user-id').val(UAId);
                $('#edit-old-password').val('');
                $('#edit-new-password').val('');
                $('#edit-new-password-confirm').val('');
                $('#editUsers').modal('toggle');
            } else {
                Swal.fire({
                    title: "System Error",
                    text: "Unable to fetch user data!",
                    type: "warning", 
                    timer: 2000
                });
            }
        },
        error: function(r) {
            $('#token').val(r.generated_token);

            Swal.fire({
                title: "System Error",
                text: "Unable to fetch user data!",
                type: "warning", 
                timer: 2000
            });
        }
    });
});

$(document).ready(function() {
    Dropzone.autoDiscover = false;
    $("div#image-upload").dropzone({ 
        url: "Users/update_image",
        dataType: "json",
        acceptedFiles: ".jpg,.jpeg,.JPG,.png",
        // addRemoveLinks: true,
        success: function(file, response){
            var obj = jQuery.parseJSON(response);
            $("#preview-edit-image-user").attr("src", obj.img);
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
        sending:function(file, xhr, formData){
            formData.append('token', $('#token').val());
            formData.append('user-access-id', $('#selected-edit-user-id').val());
        }
    });
});

$(document).on('focusout','#edit-new-password', function(){
    $('#edit-new-password-confirm').val('');
});

$(document).on('focusout','#add-password', function(){
    $('#add-cpassword').val('');
});

$(document).on('click','#btnClearForm', function(){
     $('#add-form-profile')[0].reset();
});

$(document).on('click','#avatar', function(){
    $('#add-file').click();
});

$(document).on('change','#add-file', function(){
    imageToBase64(this, function(response){
        $('#avatar').attr('src', response);
    });
});

function imageToBase64(input, callback, outputFormat){
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      callback(e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}














jQuery.validator.addMethod("noSpace", function(value, element) { 
    value = value.trimLeft();
    return value != ""; 
}, "Space is not allowed.");

jQuery.validator.addMethod("confirm-password", function(value, element) { 
    return value == $('#edit-new-password').val(); 
}, "Confirm password is not match!");

jQuery.validator.addMethod("add-confirm-password", function(value, element) { 
    return value == $('#add-password').val(); 
}, "Confirm password is not match!");

var users = function () {

    var runSetDefaultValidation = function () {
        $.validator.setDefaults({
            highlight: function (input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
                $(element).parents('.input-group').append(error);
            }
        });
    };

    var editPassword = function(){
        var form = $('#form-password-settings', function(e){
            e.preventDefault();
        });

        form.validate({
            rules: {
                'edit-old-password': {
                    required: true,
                    noSpace: true
                },
                'edit-new-password': {
                    required: true,
                    noSpace: true
                },
                'edit-new-password-confirm': {
                    required: true,
                    noSpace: true,
                    'confirm-password': true
                }
            },
            submitHandler: function () {
                var data = $(this).serialize() + "&" + $.param({
                    'edit-user-id': $('#selected-edit-user-id').val(),
                    'edit-old-password': $('#edit-old-password').val(),
                    'edit-new-password': $('#edit-new-password').val(),
                    token: $('#token').val()
                });

                $.when(request('Users/update_password', data)).done(function(response){
                    $('#token').val(response.generated_token);
                    if(response.success){
                        swal("Success", response.msg, "success");
                        $('#edit-old-password').val('');
                        $('#edit-new-password').val('');
                        $('#edit-new-password-confirm').val('');
                    }else{
                        swal("Warning", response.msg, "warning");
                    }
                });
            }
        });
    }

    var editProfile = function () {
        var form = $('#edit-form-profile', function(e){
            e.preventDefault();
        });

        form.validate({
            rules: {
                'edit-first-name': {
                    required: true,
                    noSpace: true
                },
                'edit-last-name': {
                    required: true,
                    noSpace: true
                },
                'select-edit-role': {
                    required: true,
                    noSpace: true
                }
            },
            submitHandler: function () {
                var data = $(this).serialize() + "&" + $.param({
                    'edit-user-id': $('#selected-edit-user-id').val(),
                    'edit-first-name': $('#edit-first-name').val(),
                    'edit-last-name': $('#edit-last-name').val(),
                    'select-edit-role': $('#select-edit-role option:selected').val(),
                    token: $('#token').val()
                });

                $.when(request('Users/update_profile', data)).done(function(response){
                    $('#token').val(response.generated_token);
                    tbl_user.ajax.reload();
                    if(response.success){
                        swal("Success", response.msg, "success");
                    }else{
                        swal("Warning", response.msg, "warning");
                    }
                });
            }
        });
    };

    var addUser = function(){
        var form = $('#add-form-profile', function(e){
            e.preventDefault();
        });

        form.validate({
            rules: {
                'add-fname': {
                    required: true,
                    noSpace: true
                },
                'add-lname': {
                    required: true,
                    noSpace: true
                },
                'add-role': {
                    required: true,
                    noSpace: true
                },
                'add-username': {
                    required: true,
                    noSpace: true
                },
                'add-password': {
                    required: true,
                    noSpace: true
                },
                'add-cpassword': {
                    required: true,
                    noSpace: true,
                    'add-confirm-password': true
                }
            },
            submitHandler: function () {
                var data = new FormData();
                data.append('username', $('#add-username').val());
                data.append('password', $('#add-password').val());
                data.append('lname', $('#add-lname').val());
                data.append('fname', $('#add-fname').val());
                data.append('role', $('#add-role').val());
                data.append('token', $('#token').val())
                if($('#add-file')[0].files[0] != undefined){
                    data.append('file', $('#add-file')[0].files[0]);
                }

                $.when(request_with_file('Users/do_upload', data)).done(function(response){
                    var response = jQuery.parseJSON(response);
                    $('#token').val(response.generated_token);
                    if(response.success){
                        swal("Success", response.msg, "success");
                        tbl_user.ajax.reload();
                        $('#addUsers').modal('toggle');
                        $('#add-form-profile')[0].reset();
                    }else{
                        swal("Warning", response.msg, "warning");
                    }
                });
            }
        });
    }

    return {
        //main function to initiate template pages
        init: function () {
            runSetDefaultValidation();
            addUser();
            editProfile();
            editPassword();
        }
    };
    
}();