
$(document).ready(function() {
    loan_application.init();
    $('#loan-amount').number(true, 2);
    $('#interest-rate').number(true, 2);
    $('#service-fee-rate').number(true, 2);
    $('#no-of-payment-period').number(true);
    $('#interest-amount').number(true, 2);
    $('#service-charge-amount').number(true, 2);
    $('#taxes-amount').number(true, 2);
    $('#insurance-amount').number(true, 2);
    $('#docs-stamp-amount').number(true, 2);
    $('#processing-fee-amount').number(true, 2);
    $('#tax-amount').number(true, 2);

    //Bootstrap datepicker plugin
    $('#bs_datepicker_container input').datepicker({
        autoclose: true,
        container: '#bs_datepicker_container',
        // format: 'yyyy-mm-dd'
        orientation: 'top',
    });

    $('#loan-date').datepicker('update', new Date());

});

//Advanced form with validation
var form = $('#form-loan-application').show();
form.steps({
    labels:{
        finish: "Save"
    },
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
        return false;
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ':disabled';
        return form.valid();
    },
    onFinished: function (event, currentIndex) {

        var deductions = [];
        var table_body_row = $("table#deduction-table > tbody > tr");
        table_body_row.each(function(index, kani) {
            var amortized = $(kani).find("td:eq(2)").find("input[type='checkbox']").prop("checked");
            var net_proceed = $(kani).find("td:eq(3)").find("input[type='checkbox']").prop("checked");
            var rate = $(kani).find("td:eq(4)");
            var amount = $(kani).find("td:eq(5)");
            var include = $(kani).find("td:eq(6)").find("input[type='checkbox']").prop("checked");
            var id = $(kani).attr("id");

            if(include){
                deductions.push({
                    id: id,
                    amortized_flag : (amortized ? 1 : 0),
                    deduct_net_proceed_flag : (net_proceed ? 1 : 0),
                    rate : rate.html(),
                    amount : amount.html()
                });
            }
        });

        var form_data = $(this).serialize()+"&" +$.param({
            "borrower-id": $('#borrower-id').val(),
            "loan-amount": $('#loan-amount').val(),
            "frequency-of-payment": $('#frequency-of-payment option:selected').val(),
            "loan-term": $('#loan-term').val(),
            "no-of-payment-period": $('#no-of-payment-period').val(),
            "loan-date": $('#loan-date').val(),
            "maturity-date": $('#maturity-date').val(),
            "first-payment-date": $('#first-payment-date').val(),
            "net-proceeds": $('#net-proceeds').html(),
            "tax-amount": $("#tax-amount").val(),
            "monthly-interest": $("#interest-monthly").html(),
            "deductions": deductions,
            token: $('#token').val()
        });

        $.when(request('../LoanApplication/create_loan', form_data)).done(function(response){
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

$(document).on('change','#borrower-id', function(){
    var form_data = $(this).serialize()+"&" +$.param({
        "borrower-id": $('#borrower-id').val(),
        token: $('#token').val()
    });

    $.when(request('../LoanApplication/other_loan_status', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        $('#loan-amount').val(response.loanable_amount).trigger('blur');
        $('#max-loanable-amount').val(response.max_loanable_amount);
        if(response.success){
            swal({
                title: "Warning!", 
                text: response.msg, 
                type: "warning",
                showCancelButton: true,
            }, function(e){
                if(!e){
                    $('#borrower-id').val(1).selectpicker('refresh');
                }
            });
        }
    });
});

$(document).on('blur','.not-empty', function(){
    if(!$(this).val()){
        $(this).val('0.00');
        $(this).trigger('keyup');
    }
});

$(document).on('blur','#loan-term', function(){
    if(!$(this).val()){
        $(this).val('360');
        $(this).trigger('keyup');
    }
});

$(document).on('blur','#no-of-payment-period', function(){
    if(!$(this).val()){
        $(this).val('0');
        $(this).trigger('keyup');
    }
});

$(document).on('keyup focusout','#loan-amount', function(){

    $('#net-proceeds').html(numeral($('#loan-amount').val()).format('0.00'));
    calculate_net_proceed();
});

$(document).on('blur','#loan-term', function(){
    date_calculator();
    calculate_net_proceed();
});

$(document).on('change','#loan-date', function(){
    date_calculator();
});

$(document).on('change','#frequency-of-payment', function(){
    date_calculator();
});

$(document).on('keyup','.deduction-table-row', function(){
    calculate_net_proceed();
});

$(document).on('change', '.chck-box-change', function(){
    calculate_net_proceed();
});

$(document).on('change','.on-change-amortized', function(){
    var el = $(this);
    if(el.prop("checked")){
        el.closest("tr").find("td:eq(3)").find("input[type='checkbox']").prop("checked", false);
    }else{
        el.closest("tr").find("td:eq(3)").find("input[type='checkbox']").prop("checked", true);
    }
    calculate_net_proceed();
});

$(document).on('change','.on-change-net-proceed', function(){
    var el = $(this);
    if(el.prop("checked")){
        el.closest("tr").find("td:eq(2)").find("input[type='checkbox']").prop("checked", false);
    }else{
        el.closest("tr").find("td:eq(2)").find("input[type='checkbox']").prop("checked", true);
    }
    calculate_net_proceed();
});

$(document).on('change','.include', function(){
    calculate_net_proceed();
});

function date_calculator(){
    var fop = $('#frequency-of-payment option:selected');
    var lt = $('#loan-term');
    var nopp = $('#no-of-payment-period');
    var ld = $('#loan-date');
    var md = $('#maturity-date');
    var fpd = $('#first-payment-date');
    var days = fop.attr("data-days");
    var first_payment_date;
    var maturity_date;
    var no_of_payment_period;

    if(days == 15){
        no_of_payment_period = lt.val() * 2;
        if(moment(ld.val()).format("DD") < 16){
            first_payment_date = moment(ld.val()).endOf('month').format("MM/DD/YYYY");
        }else{
            first_payment_date = moment(ld.val()).add(1, 'months').format("MM/DD/YYYY");
            first_payment_date = moment(first_payment_date).format("MM") + "/15/" + moment(first_payment_date).format("YYYY");
        }

        for (var i = 1; i <= no_of_payment_period; i++) {
            if(i === 1){
                maturity_date = first_payment_date;
            }else{
                if(moment(maturity_date).format("DD") == 15){
                    maturity_date = moment(maturity_date).endOf('month').format("MM/DD/YYYY");
                }else{
                    maturity_date = moment(maturity_date).add(1, 'months').format("MM/DD/YYYY");
                    maturity_date = moment(maturity_date).format("MM") + "/15/" + moment(maturity_date).format("YYYY");
                }
            }
        }
    }else if(days == 30){
        no_of_payment_period = lt.val() * 1;
        for (var i = 1; no_of_payment_period >= i; i++) {
            if(i === 1){
                first_payment_date = moment(ld.val()).add(i, 'months').format("MM/DD/YYYY");
                maturity_date = moment(ld.val()).add(i, 'months').format("MM/DD/YYYY");
            }else{
                maturity_date = moment(ld.val()).add(i, 'months').format("MM/DD/YYYY");
            }
        }
    }

    md.val(moment(maturity_date).format("MM/DD/YYYY"));
    fpd.val(moment(first_payment_date).format("MM/DD/YYYY"));
    nopp.val(no_of_payment_period);

    amortization_calculator();
}

function calculate_net_proceed(){
    var la = $('#loan-amount');
    var lt = $('#loan-term');
    var net_proceed = $('#net-proceeds');
    var table_body_row = $("table#deduction-table > tbody > tr");
    var tax = $('#tax-amount');
    var deduction = 0;
    var grt = 0;

    table_body_row.each(function(index, kani) {
        var deduct_net_proceed = $(kani).find("td:eq(3)").find("input[type='checkbox']").prop("checked");
        var rate = $(kani).find("td:eq(4)");
        var amount = $(kani).find("td:eq(5)").html();
        var include = $(kani).find("td:eq(6)").find("input[type='checkbox']").prop("checked");
        var computed_amount = 0;

        if(include){
            if(parseInt(rate.html()) !== 0){
                computed_amount = ((rate.html() / 100) * lt.val()) * la.val();
                // $(kani).find("td:eq(5)").removeAttr("contenteditable");
                $(kani).find("td:eq(5)").html(numeral(computed_amount).format('0,0.00'));
            }else{
                computed_amount = 0;
                // $(kani).find("td:eq(5)").attr("contenteditable", true);
            }
            if(deduct_net_proceed){
                deduction += parseInt(numeral(amount).format('0.00'));
                grt += (parseInt(numeral(amount).format('0.00')) * ($("#deduction-tax-rate").val() / 100));
            }
            if(index == 0){
                $('#interest-monthly').html(numeral(la.val() * (rate.html() / 100)).format("0,0.00"));
            }
        }
    });

    var net_amount = la.val() - deduction;
    var net = numeral(net_amount).format('0,0.00');
    tax.val(numeral(grt).format('0,0.00'));
    net_proceed.html(net);
    amortization_calculator();
}

function format_table_amount(){
    var table_body_row = $("table#deduction-table > tbody > tr");
    table_body_row.each(function(index, kani) {
        var amortized = $(kani).find("td:eq(2)").find("input[type='checkbox']").prop("checked");
        var net_proceed = $(kani).find("td:eq(3)").find("input[type='checkbox']").prop("checked");
        var rate = $(kani).find("td:eq(4)");
        var amount = $(kani).find("td:eq(5)");
        var id = $(kani).attr("id");

        rate.html(numeral(rate.html()).format('0,0.00'));
        amount.html(numeral(amount.html()).format('0,0.00'));
    });
}

function amortization_calculator(){
    var fop = $('#frequency-of-payment option:selected');
    var lt = $('#loan-term');
    var nopp = $('#no-of-payment-period');
    var ld = $('#loan-date');
    var md = $('#maturity-date');
    var fpd = $('#first-payment-date');
    var la = $('#loan-amount');
    var days = fop.attr("data-days");
    var tax_rate = $('#deduction-tax-rate');
    var arrDueDate = [];

    if(days == 15){
        var duedate;
        for (var i = 1; nopp.val() >= i; i++) {
            if(i === 1){
                duedate = fpd.val();
            }else{
                if(moment(duedate).format("DD") == 15){
                    duedate = moment(duedate).endOf('month').format("MM/DD/YYYY");
                }else{
                    duedate = moment(duedate).add(1, 'months').format("MM/DD/YYYY");
                    duedate = moment(duedate).format("MM") + "/15/" + moment(duedate).format("YYYY");
                }
            }
            arrDueDate.push(duedate);
        }
    }else if(days == 30){
        var duedate;
        for (var i = 1; nopp.val() >= i; i++){
            if(i === 1){
                duedate = fpd.val();
            }else{
                duedate = moment(duedate).add(1, 'months').format("MM/DD/YYYY");
            }
            arrDueDate.push(duedate);
        }
    }

    var table_body_row = $("table#deduction-table > tbody > tr");
    var total_rate = 0;
    var total_amount = 0;
    table_body_row.each(function(index, kani) {
        var amortized = $(kani).find("td:eq(2)").find("input[type='checkbox']").prop("checked");
        var net_proceed = $(kani).find("td:eq(3)").find("input[type='checkbox']").prop("checked");
        var rate = $(kani).find("td:eq(4)");
        var amount = $(kani).find("td:eq(5)");
        if(amortized){
            total_rate += parseFloat(rate.html());
        }
        total_amount += parseFloat(amount.html());
    });

    var table_body;
    var seq;
    var duedate;
    var installment;
    var principal;
    var deduction;
    var grt;
    var balance = la.val();

    var grandInstallment = 0;
    var grandPrincipal = 0;
    var grandDeduction = 0;
    var grandGrt = 0;

    for (var i = 0; i <= arrDueDate.length - 1; i++) {
        seq = i;
        duedate = arrDueDate[i];
        principal = la.val() / nopp.val();
        principal = numeral(principal).format("0.00");
        deduction = la.val() * (total_rate/ 100);
        if(days == 15){
            deduction = deduction / 2;
        }else if(days == 30){
            deduction = deduction / 1;
        }
        deduction = numeral(deduction).format("0.00");

        if(i == arrDueDate.length - 1){
            principal = balance;
            principal = numeral(principal).format("0.00");
        }

        grt = deduction * (tax_rate.val() / 100);
        grt = numeral(grt).format("0.00");
        installment = parseFloat(principal) + parseFloat(deduction) + parseFloat(grt);
        balance -= principal;

        if(i == arrDueDate.length - 1){
            balance = 0;
        }
        
        grandInstallment += installment;
        grandPrincipal += parseFloat(principal);
        grandDeduction += parseFloat(deduction);
        grandGrt += parseFloat(grt);

        table_body += `<tr>
            <td>${++seq}</td>
            <td>${duedate}</td>
            <td>${numeral(installment).format("0,0.00")}</td>
            <td>${numeral(principal).format("0,0.00")}</td>
            <td>${numeral(deduction).format("0,0.00")}</td>
            <td>${numeral(grt).format("0,0.00")}</td>
            <td>${numeral(balance).format("0,0.00")}</td>
        </tr>`;

        if(i == arrDueDate.length - 1){
            table_body += `<tr>
                <td></td>
                <td><label>TOTAL<l/label></td>
                <td><label>${numeral(grandInstallment).format("0,0.00")}</label></td>
                <td><label>${numeral(grandPrincipal).format("0,0.00")}</label></td>
                <td><label>${numeral(grandDeduction).format("0,0.00")}</label></td>
                <td><label>${numeral(grandGrt).format("0,0.00")}</label></td>
                <td></td>
            </tr>`;
        }

        balance = balance;
    }

    $("table#preview-amortization-table > tbody").html(table_body);
}

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
                    required: true,
                    "not-zero": true,
                    "loanable-amount": true
                },
                "frequency-of-payment": {
                    required: true
                },
                "loan-term": {
                    required: true,
                    "not-zero": true
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