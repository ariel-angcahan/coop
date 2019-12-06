$(document).ready(function() {
    login.init();
});

var login = function () {

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

    var authenticate = function () {
        var form = $('#sign_in', function(e){
            e.preventDefault();
        });

        form.validate({
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            submitHandler: function (form) {

                var form_data = $(this).serialize()+"&"+$.param({
                    username: $('#username').val(),
                    password: $('#password').val(),
                    token: $('#token').val()
                });
                $.when(request('Logins/authentication', form_data)).done(function(response){
                   $('#token').val(response.generated_token);
                    if (response.success) {
                        location.href = response.redirect;
                    } else {
                        swal("Warning", ""+response.msg+"", "warning");
                    }
                });
            }
        });
    };

    return {
        //main function to initiate template pages
        init: function () {
            runSetDefaultValidation();
            authenticate();
        }
    };
    
}();