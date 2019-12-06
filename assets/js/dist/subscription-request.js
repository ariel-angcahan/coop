
var subscription_request = $('#subscription-request-list').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "ajax": {  
        type:"POST",
        url: 'Members/subscription_request',  // method  , by default get
        global: false,
        data: function (response){
            response.token = $('#token').val();
        }
    }
});

$(document).on('click', '.btn-preview-subscription-request-info', function(){
    var form_data = $(this).serialize()+"&" +$.param({
        id: $(this).attr('data-id'),
        token: $('#token').val()
    });

    $.when(request('../Members/get_subscription_request_info', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#name').html(`<strong>NAME:</strong> ${response.name}`);
            $('#tmc_code').html(`<strong>CODE:</strong> ${response.tmc_code}`);
            $('#mode').html(`<strong>MODE:</strong> ${response.mode}`);
            $('#subscription_amount').html(`<strong>AMOUNT:</strong> ${response.subscription_amount}`);
            $('#payment_per_mode').html(`<strong>PAYMENT/MODE:</strong> ${response.payment_per_mode}`);
            $('#date_requested').html(`<strong>DATE:</strong> ${response.date_requested}`);
            $('#subscription-request-info-modal').modal('toggle');
        }
    });
});

$(document).on('click','.btn-preview-subscription-approved', function(){
    var id = $(this).attr('data-id');
     swal({
        title: "Are you sure?",
        text: "You will be able to register this information!",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: true,
        // showLoaderOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            var form_data = $(this).serialize()+"&" +$.param({
                'subscription-id': id,
                token: $('#token').val()
            });

            $.when(request('../Members/approve_application', form_data)).done(function(response){
                $('#token').val(response.generated_token);
                if(response.success){
                    // swal({title: "Success", text: response.msg, type: "success"});
                    subscription_request.ajax.reload();
                } else {
                    swal({title: "Warning", text: response.msg, type: "warning"});
                }
            });
        }
    });
});

$(document).on('click','#create-new-request-btn', function(){
    $('#new-subscription-request-modal').modal('toggle');
});

$(document).on('focusout','#request-tmc-code', function(){
    if($('#request-tmc-code').val().length != 0){
        var form_data = $(this).serialize()+"&" +$.param({
            tmc_code: $('#request-tmc-code').val(),
            token: $('#token').val()
        });

        $.when(request('../Members/get_account_holder', form_data)).done(function(response){
            $('#token').val(response.generated_token);
            if(response.success){
                $('#request-account-holder').val(response.name);
            }else{
               swal("Warning", response.msg, "warning");
            }
        });
    }
});


$(document).on('click','#btn-request-proceed', function(){
    var form_data = $(this).serialize()+"&" +$.param({
        'request-tmc-code': $('#request-tmc-code').val(),
        'request-subscriptiom-amount': $('#request-subscriptiom-amount').val(),
        'subscription-payment-mode': $('#subscription-payment-mode option:selected').val(),
        'request-payment-per-mode-amount': $('#request-payment-per-mode-amount').val(),
        token: $('#token').val()
    });

    $.when(request('../Members/request_new_subscription', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#payment-account-holder').val(response.name);
            subscription_request.ajax.reload();
            $('#new-subscription-request-modal').modal('toggle');
        }else{
           swal("Warning", response.msg, "warning");
        }
    });
})

$(document).on('blur','#request-subscriptiom-amount', function(){
    money_format(this);
});

$(document).on('blur','#request-payment-per-mode-amount', function(){
    money_format(this);
});

function money_format(e){
    $(e).val(numeral($(e).val()).format('0,0.00'));
}