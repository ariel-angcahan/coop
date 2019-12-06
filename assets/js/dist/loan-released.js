$(document).ready(()=>{
    form_validation.init();
});

$(document).ready(function(){
    var member_list = $('#loan-borrower-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax": {  
            type:"POST",
            url: '../LoanReleased/loan_borrower_list',  // method  , by default get
            global: false,
            data: function (response){
                response.token = $('#token').val();
            }
        }
    });
});

$(document).on('click','.btn-preview-info', function(){
    let id = $(this).attr("data-id");

    var form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_borrower_information', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#name').html(response.name);
            $('#amount').html(response.loan_amount);
            $('#interest').html(response.interest_rate);
            $('#service').html(response.service_fee_rate);
            $('#payment').html(response.frequency_of_payment);
            $('#term').html(response.loan_term);
            $('#payment-periond').html(response.no_of_payment_period);
            $('#loan-date').html(response.loan_date);
            $('#maturity-date').html(response.maturity_date);
            $('#first-payment-date').html(response.first_payment_date);
            $('#tax-amount').html(response.tax_amount);
            $('#net-proceed-amount').html(response.net_proceed);

            var table_body;
            response.deductions.data.forEach((data, index) => {
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
            $('#preview-info-modal').modal('toggle');
        }else{
           swal("Warning", response.msg, "warning");
        }
    });
});

$(document).on('click','.btn-preview-amortization', function(){
    var token = $(this).attr("data-plain");
    window.open(`${location.origin}/coop.ezwareservices.ph/loans/released/loan_amortization_schedule?p=${token}`);
});

$(document).on('click','.btn-preview-loan-ledger', function(){
    var token = $(this).attr("data-plain");
    window.open(`${location.origin}/coop.ezwareservices.ph/loans/released/loan_ledger_report?p=${token}`);
});

$(document).on('click','.btn-show-loan-list', function(){

    var id = $(this).attr("data-id");
    // /if table exists destroy it
    if ($.fn.dataTable.isDataTable('#borrower-loan-list')) {
        $('#borrower-loan-list').DataTable().destroy();
    }

    var borrower_loan_list = $('#borrower-loan-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax": {  
            type:"POST",
            url: '../LoanReleased/borrower_loan_list',  // method  , by default get
            data: function (response){
                response.token = $('#token').val();
                response.id = id;
            }
        }
    });

    $.when(borrower_loan_list).done(function(response){
        $("#loan-borrower-table").animateCss("fadeOutLeft"); 
        $(".fadeOutLeft").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
            $("#loan-borrower-table").css({
                display: "none"
            });
            $("#loan-released-table").show();
            $('#borrower-loan-list').css('width', '100%'); // lol this is the fuken culprit
            $("#loan-released-table").animateCss("fadeInRight");
        });

        $('#back-main-table-btn').attr('data-id', id);
    });
});

$(document).on('click','#back-main-table-btn', function(){
    $("#loan-released-table").animateCss("slideOutRight"); 
    $(".slideOutRight").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
        $("#loan-released-table").css({
            display: "none"
        });
        $("#loan-borrower-table").show();
        $("#member-list-div-table").animateCss("slideInLeft");
    });
});


$(document).on('click','.btn-loan-payment', function(){
    let id = $(this).attr('data-id');
    let borrower_name = $(this).closest("tr").find("td:eq(1)").text();

    $('#loan-payment-table > tbody').html('');

    get_loan_payment_list(id);

    $('#loan-payment-borrower-name').html(`${borrower_name.toUpperCase()} - LOAN PAYMENT DETAILS`);
});

function get_loan_payment_list(id){
    
    let form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_loan_payment_list', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#loan-payment-borrower-id').val(id);
            let html;
            response.list.forEach((data, index) => {
                index++;
                html += `<tr>
                            <td>${index}</td>
                            <td>${data.due_date}</td>
                            <td>${data.installment_amount}</td>
                            <td>${data.penalty}</td>
                            <td>${numeral(data.balance).format('0,0.00')}</td>
                            <td>${data.option}</td>
                         </tr>`;
            });

            html += `<tr>
                        <td><br></td>
                        <td><br><label>TOTAL</label></td>
                        <td><br><label>${response.total_installment}</label></td>
                        <td><br><label>${response.total_penalty}</label></td>
                        <td><br><label>${response.total_balance}</label></td>
                        <td><br></td>
                    </tr>`;

            if ($.fn.dataTable.isDataTable('#loan-payment-table')) {
              $('#loan-payment-table').DataTable().destroy();
            }

            $('#loan-payment-table > tbody').html(html);

            $('#loan-payment-table').DataTable({
                "searching": false,
                "pageLength": 5,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });
            $('#loan-payment-modal').modal('toggle');
        }
    });
}

$(document).on('click','.btn-preview-penalty-details', function(){
    var id = $(this).attr('data-id');
    let borrower_name = $(this).closest("tr").find("td:eq(1)").text();

    $('#loan-penalty-table > tbody').html('');
    $('#loan-payment-modal').modal('toggle');

    let form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_penalty_list', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        $('#loan-penalty-borrower-name').html(`${borrower_name.toUpperCase()} - LOAN PENALTY DETAILS`);
        if(response.success){
            let html;
            response.list.forEach((data, index) => {
                index++;
                html += `<tr>
                            <td>${index}</td>
                            <td>${data.due_date}</td>
                            <td>${data.amount}</td>
                        </tr>`;
            });

            if ($.fn.dataTable.isDataTable('#loan-penalty-table')) {
                $('#loan-penalty-table').DataTable().destroy();
            }

            $('#loan-penalty-table > tbody').html(html);

            $('#loan-penalty-table').DataTable({
                "searching": false,
                "pageLength": 5,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });
        }

        $('#loan-penalty-modal').modal({
            backdrop: 'static', 
            keyboard: false
        }); 
    }); 
});

$(document).on('click','#btn-close-loan-penalty-modal', function(){
    $('#loan-payment-modal').modal('toggle');
});

$(document).on('click','#btn-payment-modal', function(){
    var id = $('#loan-payment-borrower-id').val();
    $('#loan-payment-modal').modal('toggle');

    $('#payment-modal').modal({
        backdrop: 'static', 
        keyboard: false
    }); 
});

$(document).on('click','.btn-preview-due-date-transaction', function(){
    var id = $(this).attr('data-id');
    let borrower_name = $(this).closest("tr").find("td:eq(1)").text();

    $('#payment-transaction-details-table > tbody').html('');
    $('#loan-payment-modal').modal('toggle');

    let form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_payment_transaction_details', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        $('#payment-transaction-details-borrower-name').html(`${borrower_name.toUpperCase()} - LOAN PAYMENT TRANSACTION DETAILS`);

        if(response.list_flag){
            let html;
            response.list.forEach((data, index) => {
                index++;
                html += `<tr>
                            <td>${index}</td>
                            <td>${data.penalty_paid_amount}</td>
                            <td>${data.loan_paid_amount}</td>
                            <td>${data.total_paid_amount}</td>
                            <td>${data.paid_date}</td>
                        </tr>`;
            });
                html += `<tr>
                            <td><br><label>TOTAL</label></td>
                            <td><br><label>${response.total_penalty_paid}</label></td>
                            <td><br><label>${response.total_loan_paid}</label></td>
                            <td><br><label>${response.total_paid}</label></td>
                            <td><br><label>----------------</td>
                        </tr>`;

            if ($.fn.dataTable.isDataTable('#payment-transaction-details-table')) {
                $('#payment-transaction-details-table').DataTable().destroy();
            }

            $('#payment-transaction-details-table > tbody').html(html);

            $('#payment-transaction-details-table').DataTable({
                "searching": false,
                "pageLength": 5,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });
        }

        $('#payment-transaction-details-modal').modal({
            backdrop: 'static', 
            keyboard: false
        }); 
    }); 
});

//created by darnel------start this part :)

$(document).on('click','.btn-preview-transaction-history', function(){
    let id = $(this).attr('data-id');
    let borrower_name = $(this).closest("tr").find("td:eq(1)").text();
    get_loan_history_list(id);
    $('#loan-history-borrower-name').html(`${borrower_name.toUpperCase()} - LOAN PAYMENT DETAILS`);
});

function get_loan_history_list(id){
    $('#loan-history-table > tbody').html('');

    let form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_loan_history_list', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        
        if(response.success){
            let html;
            response.list.forEach((data, index) => {
                index++;
                html += `<tr>
                            <td>${index}</td>
                            <td>${data.payment_amount}</td>
                            <td>${data.paid_date}</td>
                            <td>${data.option}</td>
                        </tr>`;
            });
                html += `<tr>
                            <td><br><label>TOTAL</label></td>
                            <td><br><label>${response.total_payment}</label></td>
                            <td><br><label>-------------</label></td>
                            <td><br></td>
                        </tr>`;

            if ($.fn.dataTable.isDataTable('#loan-history-table')) {
                $('#loan-history-table').DataTable().destroy();
            }

                $('#loan-history-table > tbody').html(html);

                $('#loan-history-table').DataTable({
                    "searching": false,
                    "pageLength": 5,
                    "lengthChange": false,
                    "ordering": false,
                    "info":     false,
                });
        }
        
        $('#loan-history-modal').modal('toggle');
    });
}

$(document).on('click','.btn-loan-history-details', function(){
    let id = $(this).attr('data-id');

    $('#loan_history_details-table > tbody').html('');
    $('#loan-history-modal').modal('toggle');

    let form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../LoanReleased/get_loan_history_details', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        
        if(response.success){
            let html;
            response.list.forEach((data, index) => {
                index++;
                html += `<tr>
                            <td>${index}</td> 
                            <td>${data.loan_paid_amount}</td>
                            <td>${data.penalty_paid_amount}</td>
                            <td>${data.total_paid_amount}</td>
                            <td>${data.paid_date}</td>
                            <td>${data.due_date}</td>
                        </tr>`;
            });
                html += `<tr> 
                            <td><br><label>TOTAL</label></td>
                            <td><br><label>${response.total_loan}</label></td>
                            <td><br><label>${response.total_penalty}</label></td>
                            <td><br><label>${response.total_paid}</label></td>
                            <td><br><label>-------------</label></td>
                            <td><br><label>-------------</label></td>
                        </tr>`;
           if ($.fn.dataTable.isDataTable('#loan-history-details-table')) {
                $('#loan-history-details-table').DataTable().destroy();
            }

                $('#loan-history-details-table > tbody').html(html);
        }

                $('#loan-history-details-modal').modal({
                    backdrop: 'static', 
                    keyboard: false
        }); 
    }); 

});

$(document).on('click','#loan-history-details-modal-close', function(){
    $('#loan-history-modal').modal('toggle');

});

var form_validation = function () {

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

    var payment = function () {
        var form = $('#payment-form', function(e){
            e.preventDefault();
        });
        form.validate({
            rules: {
                'payment-amount': {
                    required: true,
                    'not-zero': true
                }
            },
            submitHandler: function (form) {
                var id = $('#loan-payment-borrower-id').val();

                var form_data = $(this).serialize()+"&"+$.param({
                    'loan-borrower-id': id,
                    'payment-amount': $('#payment-amount').val(),
                    token: $('#token').val()
                });

                $.when(request('../LoanReleased/payment_proceed', form_data)).done(function(response){
                   $('#token').val(response.generated_token);
                    if (response.success) {
                        swal({
                          title: "Success!",
                          text: response.msg,
                          type: "success",
                        }, function(){
                            $('#payment-modal').modal('toggle');
                            get_loan_payment_list(id);
                        });
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
            payment();
        }
    };
    
}();