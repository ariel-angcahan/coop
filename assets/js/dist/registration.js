$(document).ready(function() {
    registration.init();
    // $('.registered-list-border').hover(
    //        function(){ $(this).addClass('active') },
    //        function(){ $(this).removeClass('active') }
    // )
});

var static_select = {
    'market_type':[
        {'value': 1, 'text': 'FIXED'},
        {'value': 2, 'text': 'OPEN ENTRY'}
    ]
    };

$(document).ready(function() {
    var option = '';
    static_select.market_type.forEach((data) => {
        option += `<option value="${data.value}">${data.text}</option>`;
    });
    $('#market_type').html(option).selectpicker('refresh');
});

$(document).ready(function() {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });
    $.when(request('Registrations/payment_mode', data)).done(function(response){
        var option = '<option value="">SELECT PAYMENT MODE</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.id}">${data.mode}</option>`;
        });
        $('#token').val(response.token);
        $('#payment_mode').html(option).selectpicker('refresh');
    });
});

$(document).ready(function() {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });
    $.when(request('Registrations/membership_type_list', data)).done(function(response){
        var option = '<option value="">SELECT MEMBERSHIP</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.id}">${data.type}</option>`;
        });
        $('#token').val(response.token);
        $('#membership_type').html(option).selectpicker('refresh');
    });
});

$(document).ready(function() {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });
    $.when(request('Registrations/market_list', data)).done(function(response){
        var option = '<option value="">SELECT MARKET</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.id}">${data.address}</option>`;
        });
        $('#token').val(response.token);
        $('#market').html(option).selectpicker('refresh');
    });
});

$(document).ready(function() {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });
    $.when(request('Registrations/stall_list', data)).done(function(response){
        var option = '<option value="">SELECT STALL NUMBER</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.id}">${data.stall_no}</option>`;
        });
        $('#token').val(response.token);
        $('#stall_no').html(option).selectpicker('refresh');
    });
});

$(document).ready(function() {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });
    $.when(request('Registrations/gender_list', data)).done(function(response){
        var option = '<option value="">SELECT GENDER</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.id}">${data.description}</option>`;
        });
        $('#token').val(response.token);
        $('#gender').html(option).selectpicker('refresh');
    });
});

$('body').on('change','#payment_mode', function(){
    $('#payment_per_mode_text').html(`${$('#payment_mode option:selected').html()} PAYMENT`);
    compute_subscription();
});

$('body').on('blur','#subscription_amount', function(){
    $('#subscription_amount').val(numeral($('#subscription_amount').val()).format('0,0.00'));
    compute_subscription();
});

$('body').on('blur','#payment_per_mode', function(){
    $('#payment_per_mode').val(numeral($('#payment_per_mode').val()).format('0,0.00'));
    compute_subscription();
});

function compute_subscription(){
    var amount = numeral($('#subscription_amount').val()).value();
    var payment_per_mode = numeral($('#payment_per_mode').val()).value();
    var payment_mode = $('#payment_mode option:selected').val();

    switch(payment_mode){
        case '1':
            //weekly 
            var value = `${amount} / ${payment_per_mode} = ${amount / payment_per_mode} <br>${payment_per_mode} pesos sulod sa ${amount / payment_per_mode} ka semana.`;
            $('#subscription_preview').html(value);
        break;

        case '2':
            //semi-monthly 
            var value = `${amount} / ${payment_per_mode} = ${amount / payment_per_mode} <br>${payment_per_mode} pesos sulod sa ${amount / payment_per_mode} ka kisenas.`;
            $('#subscription_preview').html(value);

        break;

        case '3':
            //monthly 
            var value = `${amount} / ${payment_per_mode} = ${amount / payment_per_mode} <br>${payment_per_mode} pesos sulod sa ${amount / payment_per_mode} ka buwan.`;
            $('#subscription_preview').html(value);
        break;
    }

}

$('body').on('change','#market_type', function(){
    if($('#market_type option:selected').val() == 1){
        $('#market_type_open_entry').hide();
        $('#market_type_open_entry').val('none');
        $('#market_type_fixed').show();
    }else{
        $('#market_type_fixed').hide();
        $('#market_type_open_entry').val('');
        $('#market_type_open_entry').show();
    }
});

$('body').on('change','#switch_mother', function(){
    if($(this).prop('checked') == true){
        $('#mother_last_name').val('').attr('readonly', false);
        $('#mother_first_name').val('').attr('readonly', false);
        $('#mother_maiden').val('').attr('readonly', false);
    }else{
        $('#mother_last_name').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
        $('#mother_first_name').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
        $('#mother_maiden').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
    }
});

$('body').on('change','#switch_father', function(){
    if($(this).prop('checked') == true){
        $('#father_last_name').val('').attr('readonly', false);
        $('#father_first_name').val('').attr('readonly', false);
        $('#father_middle_name').val('').attr('readonly', false);
    }else{
        $('#father_last_name').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
        $('#father_first_name').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
        $('#father_middle_name').val('None').attr('readonly', true).closest('.form-line').removeClass('focused');
    }
});

$("#mobile_no").inputmask("(63)-999-9999999");
// $("#zip_code").attr("readonly", true);
$("#email").inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
      pastedValue = pastedValue.toLowerCase();
      return pastedValue.replace("mailto:", "");
    },
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
        casing: "lower"
      }
    }
  });

// jQuery.validator.addMethod("notDecimalSubscriptionAmount", function(value, element) {
//     return (Math.floor(numeral($('#payment_per_mode').val()).value()) / Math.floor(numeral(value).value())) % 1 == 0;
// }, "Quotient of the subscription amount and payment per mode must not be decimal.");

// $('body').on('focusout','#subscription_amount', function(){
//     $('#payment_per_mode').focusout();
// });

// $('body').on('focusout','#payment_per_mode', function(){
//     $('#subscription_amount').focusout();
// });

var FromEndDate = new Date();
$('#birth_date input').datepicker({
    autoclose: true,
    container: '#birth_date',
    endDate: FromEndDate, 
    autoclose: true
});

$('body').on('dblclick','.registered_list', function(){
    var registered_id = $(this).attr('data-id');
    swal({
        title: "Are you sure?",
        text: "You will be able to delete "+$(this).html()+"!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel this!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            var form_data = $(this).serialize()+"&" +$.param({
                registered_id: registered_id,
                token: $('#token').val()
            });
            $.ajax({
                type: "POST",
                url:  "Registrations/remove_registered_info",
                dataType: "json",
                data: form_data,
                error: function(jqXHR, lsStatus, ex){
                     swal("System Error!", "Please contact your administrator!", "warning");
                },
                success: function(response) {
                    $('#token').val(response.token);
                    if (response.success) {
                        $('#registered_list').html('');
                        registered_list();
                        swal({title: "Success", text: response.msg, type: "success"});
                    } else {
                        swal({title: "Warning", text: response.msg, type: "warning"});
                    }
                }
            });
        }
    });
});
$("#spinner")
  .spinner('delay', 200) //delay in ms
  .spinner('changed', function(e, newVal, oldVal) {
    // trigger lazed, depend on delay option.
  })
  .spinner('changing', function(e, newVal, oldVal) {
    // trigger immediately
  });
// $('#birth_date').bootstrapMaterialDatePicker({ format : 'MMMM DD, YYYY', time: false});
// $('#birth_date').moment('01-01-1990', 'MM-DD-YYYY');
$('body').on('change','#student', function(){
    if($(this).prop('checked') == true){
        $('#chk_student').removeAttr('disabled');
        $('#selected_school_name').removeAttr('disabled').selectpicker('refresh');
        $('#employee').attr('checked', false);
        $('#entry_company').attr('disabled', true).val('').closest('.form-line').removeClass('focused');
        $('#chk_company').attr('disabled', true).removeAttr('checked');
        $('#selected_company_name').attr('disabled', true).val(0).selectpicker('refresh');
    }else{
        $('#entry_school').val('').attr('disabled', true).closest('.form-line').removeClass('focused');
        $('#chk_student').attr('disabled', true).removeAttr('checked');
        $('#selected_school_name').attr('disabled', true).val('').selectpicker('refresh');
    }
});
$('body').on('change','#employee', function(){
    if($(this).prop('checked') == true){
        $('#chk_company').removeAttr('disabled');
        $('#selected_company_name').removeAttr('disabled').selectpicker('refresh');
        $('#student').attr('checked', false);
        $('#entry_school').attr('disabled', true).val('').closest('.form-line').removeClass('focused');
        $('#chk_student').attr('disabled', true).removeAttr('checked');
        $('#selected_school_name').attr('disabled', true).val(0).selectpicker('refresh');
    }else{
        $('#entry_company').val('').attr('disabled', true).closest('.form-line').removeClass('focused');
        $('#chk_company').attr('disabled', true).removeAttr('checked');
        $('#selected_company_name').attr('disabled', true).val('').selectpicker('refresh');
    }
});
$('body').on('change','#chk_student', function(){
    if($(this).prop('checked') == true){
        $('#selected_school_name').attr('disabled', true).val(0).selectpicker('refresh');
        $('#entry_school').removeAttr('disabled').closest('.form-line').addClass('focused');
    }else{
        $('#entry_school').attr('disabled', true).val('').closest('.form-line').removeClass('focused');
        $('#selected_school_name').removeAttr('disabled').selectpicker('refresh');
    }
});
$('body').on('change','#chk_company', function(){
    if($(this).prop('checked') == true){
        $('#selected_company_name').attr('disabled', true).val(0).selectpicker('refresh');
        $('#entry_company').removeAttr('disabled').closest('.form-line').addClass('focused');
    }else{
        $('#entry_company').attr('disabled', true).val('').closest('.form-line').removeClass('focused');
        $('#selected_company_name').removeAttr('disabled').selectpicker('refresh');
    }
});
$('body').on('keyup','#first_name', function(){
    auto_name();
});
$('body').on('keyup','#last_name', function(){
    auto_name();
});
$('body').on('keyup','#middle_name', function(){
    auto_name();
});
$('body').on('keyup','#email', function(){
    $('.email').html($('#email').val());
});
$('body').on('change','#selected_school_name', function(){
    submit_ready();
});
$('body').on('change','#selected_company_name', function(){
    submit_ready();
});
$('body').on('focusout','#entry_school', function(){
    submit_ready();
});
$('body').on('focusout','#entry_company', function(){
    submit_ready();
});
function auto_name(){
    var first_name = $('#first_name').val() ? $('#first_name').val() : '';
    var last_name = $('#last_name').val() ? $('#last_name').val() : '';
    var middle_name = $('#middle_name').val() ? $('#middle_name').val() : '';
    $('.name').html(`${cap(last_name)}, ${cap(first_name)} ${cap(middle_name)}`);
}
function cap(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function submit_ready(){
    if($('#selected_school_name option:selected').val() != 0 || $('#selected_company_name option:selected').val() != 0 || $('#entry_school').val().length != 0 || $('#entry_company').val().length != 0){
        $('#btn_register').removeAttr('disabled');
    }else{
        $('#btn_register').attr('disabled', true);
    }
}
//registered_list();
//function registered_list(){
    // var form_data = $(this).serialize()+"&" +$.param({
    //     token: $('#token').val()
    // });
    // $.ajax({
    //     type: "POST",
    //     url:  "Registrations/registered_list",
    //     dataType: "json",
    //     data: form_data,
    //     error: function(jqXHR, lsStatus, ex){
    //          swal("System Error!", "Please contact your administrator!", "warning");
    //     },
    //     success: function(response) {
    //         $('#token').val(response.token);
    //         // alert();
    //         for (var i = 0; i < response.name.length; i++) {
    //             $('#registered_list').append('<li class="header registered_list" data-id="'+response.id[i]+'">'+response.name[i]+'</li>');
    //         }
    //     }
    // });
//}
region_list();
function region_list(country_code = 0){
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val(),
        country_code: country_code
    });
    $.when(request('Registrations/region_list', data)).done(function(response){
        var option = '<option value="">SELECT REGION</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.regCode}">${data.regDesc}</option>`;
        });
        $('#token').val(response.token);
        $('#region').html(option).selectpicker('refresh');
    });
}
$('body').on('change','#region', function(){
    province_list($('#region option:selected').val());
});
function province_list(region_code = 0){
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val(),
        region_code: region_code
    });
    $.when(request('Registrations/province_list', data)).done(function(response){
        var option = '<option value="">SELECT PROVINCE</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.provCode}">${data.provDesc}</option>`;
        });
        $('#token').val(response.token);
        $('#province').html(option).selectpicker('refresh');
    });
}
$('body').on('change','#province', function(){
    city_list($('#province option:selected').val());
});
function city_list(province_code = 0){
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val(),
        province_code: province_code
    });
    $.when(request('Registrations/city_list', data)).done(function(response){
        var option = '<option value="">SELECT CITY</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.citymunCode}">${data.citymunDesc}</option>`;
        });
        $('#token').val(response.token);
        $('#city').html(option).selectpicker('refresh');
    });
}
$('body').on('change','#city', function(){
    barangay_list($('#city option:selected').val());
});
function barangay_list(city_code = 0){
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val(),
        city_code: city_code
    });
    $.when(request('Registrations/barangay_list', data)).done(function(response){
        var option = '<option value="">SELECT BARANGAY</option>';
        response.data.forEach((data) => {
            option += `<option value="${data.brgyCode}">${data.brgyDesc}</option>`;
        });
        $('#zip_code').val(response.zip_code);
        $('#token').val(response.token);
        $('#barangay').html(option).selectpicker('refresh');
    });
}
$('body').on('focusout','#first_name', function(){
    check_if_registered();
});
$('body').on('focusout','#last_name', function(){
    check_if_registered();
});
function check_if_registered(argument) {
    var data = $(this).serialize()+"&" +$.param({
        token: $('#token').val(),
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val()
    });
    $.when(request('Registrations/check_if_registered', data, false)).done(function(response){
        if(response.success){
            swal({title: "Warning", text: response.msg, type: "warning"});
            $('#first_name').val('');
            $('#last_name').val('');
        }
        $('#token').val(response.token);
    });
}

var registration = function () {
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
    var register_information = function () {
        var form = $('#register_information', function(e){
            e.preventDefault();
        });
        form.validate({
            rules: {
                last_name: {
                    required: true,
                    noSpace: true
                    
                },
                first_name: {
                    required: true,
                    noSpace: true
                    
                },
                middle_name: {
                    required: true,
                    noSpace: true
                    
                },
                gender: {
                    required: true,
                    noSpace: true
                    
                },
                birth_date: {
                    required: true,
                    noSpace: true
                    
                },
                birth_place: {
                    required: true,
                    noSpace: true
                    
                },
                email: {
                    required: true,
                    noSpace: true,
                    ayieee_email: true
                    
                },
                mobile_no: {
                    required: true,
                    noSpace: true,
                    ayieee_mobile: true
                    
                },
                country: {
                    required: true,
                    noSpace: true
                },
                region: {
                    required: true,
                    noSpace: true
                },
                province: {
                    required: true,
                    noSpace: true
                },
                city: {
                    required: true,
                    noSpace: true
                },
                barangay: {
                    required: true,
                    noSpace: true
                    
                },
                zip_code: {
                    required: true,
                    noSpace: true
                    
                },
                street: {
                    required: true,
                    noSpace: true
                    
                },
                mother_maiden: {
                    required: true,
                    noSpace: true
                    
                },
                mother_first_name: {
                    required: true,
                    noSpace: true
                    
                },
                mother_last_name: {
                    required: true,
                    noSpace: true
                    
                },
                father_last_name: {
                    required: true,
                    noSpace: true
                    
                },
                father_first_name: {
                    required: true,
                    noSpace: true
                    
                },
                father_middle_name: {
                    required: true,
                    noSpace: true
                    
                },
                market:{
                    required: true
                },
                market_type:{
                    required: true
                },
                membership_type:{
                    required: true
                },
                subscription_amount:{
                    required: true,
                    notZero: true
                },
                payment_mode:{
                    required: true
                },
                payment_per_mode:{
                    required: true,
                    notZero: true,
                    notHigherToSubscriptionAmount: true,
                    notDecimalPaymentPerMode: true
                }
            },
            submitHandler: function (form) {
                var market_type = $('#market_type option:selected').val();
                var stall_no = $('#stall_no option:selected').val();
                var stall_description = $('#stall_description').val();
                var errorCount = 0;
                switch(market_type){
                    case "1":
                        if(stall_no == 0){
                            swal("System Checked", "Please select stall number!", "warning");
                            errorCount++;
                        }
                    break;
                    case "2":
                        if(stall_description.length == 0){
                            swal("System Checked", "Please do not leave stall location description as blank!", "warning");
                            errorCount++;
                        }
                    break;
                }
                if(errorCount == 0){
                    swal({
                        title: "Are you sure?",
                        text: "You will be able to register this information!",
                        type: "warning",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            var form_data = $(this).serialize()+"&" +$.param({
                                last_name: $('#last_name').val(),
                                first_name: $('#first_name').val(),
                                middle_name: $('#middle_name').val(),
                                gender: $('#gender').val(),
                                birth_date: $('#birth_date_val').val(),
                                birth_place: $('#birth_place').val(),
                                email: $('#email').val(),
                                mobile_no: $('#mobile_no').val(),
                                country: $('#country').val(),
                                region: $('#region').val(),
                                province: $('#province').val(),
                                city: $('#city').val(),
                                barangay: $('#barangay').val(),
                                zip_code: $('#zip_code').val(),
                                street: $('#street').val(),

                                market_id: $('#market option:selected').val(),
                                stall_no_id: $('#stall_no option:selected').val(),
                                stall_description: $('#stall_description').val(),
                                membership_type: $('#membership_type option:selected').val(),
                                subscription_amount: numeral($('#subscription_amount').val()).value(),
                                payment_mode: $('#payment_mode option:selected').val(),
                                payment_per_mode: numeral($('#payment_per_mode').val()).value(),

                                mother_last_name: $('#mother_last_name').val(),
                                mother_first_name: $('#mother_first_name').val(),
                                mother_maiden: $('#mother_maiden').val(),
                                
                                father_last_name: $('#father_last_name').val(),
                                father_first_name: $('#father_first_name').val(),
                                father_middle_name: $('#father_middle_name').val(),
                                selected_school_id: $('#selected_school_name option:selected').val(),
                                entry_school: $('#entry_school').val(),
                                selected_company_id: $('#selected_company_name option:selected').val(),
                                entry_company: $('#entry_company').val(),
                                token: $('#token').val()
                            });
                            $.ajax({
                                type: "POST",
                                url:  "Registrations/register_information",
                                dataType: "json",
                                data: form_data,
                                error: function(jqXHR, lsStatus, ex){
                                     swal("System Error!", "Please contact your administrator!", "warning");
                                },
                                success: function(response) {
                                    $('#token').val(response.token);
                                    if (response.success) {
                                        swal({title: "Success", text: response.msg, type: "success"}, function(){
                                            window.location.href = response.redirect;
                                            // $('#register_information')[0].reset();
                                            // $('#country').val(1).trigger('change');
                                            // $('#region').val(0).trigger('change');
                                            // $('#province').val(0).trigger('change');
                                            // $('#city').val(0).trigger('change');
                                            // $('#barangay').val(0).trigger('change');
                                            // $('#selected_school_name').val(0).trigger('change');
                                            // $('#selected_company_name').val(0).trigger('change');
                                            //registered_list(); 
                                            // location.reload();
                                        });
                                    } else {
                                        swal({title: "Warning", text: response.msg, type: "warning"});
                                    }
                                }
                            });
                        } else {
                            swal("Cancelled", "Your registration is cancelled!", "error");
                        }
                    });
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                // errorHandler.show();
            }
        });
    };
    return {
        //main function to initiate template pages
        init: function () {
            runSetDefaultValidation();
            register_information();
        }
    };
}();