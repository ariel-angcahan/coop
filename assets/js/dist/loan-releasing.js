$(document).ready(function(){
    loan_application.init();
    $('input').attr('readonly', 'true');
});

$(document).on('change','#borrower-id', function(){
    $("#loan-amount").val("");
    $("#interest-rate").val("");
    $("#service-fee-rate").val("");
    $("#loan-term").val("");
    $("#frequency-of-payment").val("");
    $("#no-of-payment-period").val("");
    $("#loan-date").val("");
    $("#maturity-date").val("");
    $("#first-payment-date").val("");

    var form_data = $(this).serialize()+"&" +$.param({
        "lbid": $('#borrower-id').val(),
        token: $('#token').val()
    });

    $.when(request('../LoanReleasing/get_borrower_info', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $("#loan-amount").val(response.loan_amount);
            $("#interest-rate").val(response.interest_rate);
            $("#service-fee-rate").val(response.service_fee_rate);
            $("#loan-term").val(response.loan_term);
            $("#frequency-of-payment").val(response.frequency_of_payment);
            $("#no-of-payment-period").val(response.no_of_payment_period);
            $("#loan-date").val(moment(response.loan_date).format("MM/DD/YYYY"));
            $("#maturity-date").val(moment(response.maturity_date).format("MM/DD/YYYY"));
            $("#first-payment-date").val(moment(response.first_payment_date).format("MM/DD/YYYY"));
            $("#net-proceeds").html(response.net_proceed);
            $("#tax-amount").val(response.tax_amount);

            $("table#deduction-table > tbody").html("");
            if(response.deductions.deduction_flag){
                var table_body;
                response.deductions.data.forEach((data, index) => {
                // console.log(response.deductions.data);
                // response.deductions.data.each(function(index, data){
                    var amortized_flag = "";
                    var net_proceed_flag = "";

                    if(data.amortized_flag){
                        amortized_flag = "checked";
                    }
                    if(data.deduct_net_proceed_flag){
                        net_proceed_flag = "checked";
                    }

                    table_body += 
                    `<tr>
                        <td>${++index}</td>
                        <td>${data.deduction}</td>
                        <td>
                            <input type="checkbox" id="chk-box-amortized${index}" ${amortized_flag} onclick="return false;" readonly/>
                            <label for="chk-box-amortized${index}"></label>
                        </td>
                        <td>
                            <input class="chck-box-change" type="checkbox" id="chk-box-deduct-net-proceed${index}" ${net_proceed_flag}  onclick="return false;" readonly/>
                            <label for="chk-box-deduct-net-proceed${index}"></label></td>
                        <td>${data.rate}</td>
                        <td>${data.amount}</td>
                    </tr>`;
                });

                $("table#deduction-table > tbody").html(table_body);
            }
        }else{
            swal("System Error!", response.msg, "warning");
        }
    });
});

//Advanced form with validation
var form = $('#form-loan-application').show();
form.steps({
    headerTag: 'h3',
    bodyTag: 'fieldset',
    transitionEffect: 'slideLeft',
    stepsOrientation: 'vertical',
    onInit: function (event, currentIndex) {
        $.AdminBSB.input.activate();
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        if (currentIndex > newIndex) { return true; }

        if (currentIndex < newIndex) {
            form.find('.body:eq(' + newIndex + ') label.error').remove();
            form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
        }
        form.validate().settings.ignore = ':disabled,:hidden';
        return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
        // setButtonWavesEffect(event);
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ':disabled';
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        var form_data = $(this).serialize()+"&" +$.param({
            "lbid": $('#borrower-id').val(),
            token: $('#token').val()
        });

        $.when(request('../LoanReleasing/release', form_data)).done(function(response){
            $('#token').val(response.generated_token);
            if(response.success){
                swal({
                    title: "Success!", 
                    text: response.msg, 
                    type: "success"
                }, function(){
                    location.reload();
                });
            }else{
                swal("System Error!", response.msg, "warning");
            }
        });
    }
});

var loan_application = function () {
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

    var form_validation = function () {
        var form = $('#form-loan-application', function(e){
            e.preventDefault();
        });
        form.validate({
            rules: {
                "borrower-id": {
                    required: true
                },
                "loan-amount": {
                    required: true
                },
                "interest-rate": {
                    required: true
                },
                "service-fee-rate": {
                    required: true
                },
                "frequency-of-payment": {
                    required: true
                },
                "loan-term": {
                    required: true
                },
                "no-of-payment-period": {
                    required: true
                },
                "loan-date": {
                    required: true
                },
                "maturity-date": {
                    required: true
                },
                "first-payment-date": {
                    required: true
                },
                "name": {
                    required: true
                }
            }
        });
    };
    return {
        //main function to initiate template pages
        init: function () {
            runSetDefaultValidation();
            form_validation();
        }
    };
}();