$('#tmc-modal').modal('toggle');
$(document).ready(function(){
    $('li > a > span').css('margin-top', '6px');

    $('#preview-ledger-details-slimscroll').slimScroll({
        height: '520px'
    });
    $('#preview-break-down_slimscroll').slimScroll({
        height: '520px'
    });
});

$(document).on('click','#btn-request-inquery', function(e){
    var tmc = $('#subscription-tmc-code').val();
    var form_data = $(this).serialize()+"&" +$.param({
        tmc: tmc,
        token: $('#token').val()
    });
    $('#subscription-tmc-code').val('');
    $('#subscription-btn-name').html('');
    $('#subscription-btn-code').html('');
    $('#subscription-table > tbody').html('');

    $.when(request('Inquery/get_subscription_list', form_data)).done(function(response){
        var html;
        response.data.forEach((data) => {
            html += `<tr>
                        <td>${data.Lno}</td>
                        <td>${data.subscription_amount}</td>
                        <td>${data.mode}</td>
                        <td>${data.date_approved}</td>
                        <td>${data.status}</td>
                        <td>${data.button}</td>
                    </tr>`;
        });
        $('#subscription-table > tbody').html(html);
        $('#subscription-btn-name').html(response.name);
        $('#subscription-btn-code').html(response.tmc);
    });
});

$(document).on('click','#tmc-modal-toggle', function(){
    $('#tmc-modal').modal('toggle');
});

$(document).on('click','.copy', function(){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(this).html()).select();
    document.execCommand("copy");
    $temp.remove();

    var placementFrom = $(this).data('placement-from');
    var placementAlign = $(this).data('placement-align');
    var animateEnter = $(this).data('animate-enter');
    var animateExit = $(this).data('animate-exit');
    var colorName = $(this).data('color-name');

    showNotification(colorName, null, placementFrom, placementAlign, animateEnter, animateExit);
});

function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (colorName === null || colorName === '') { colorName = 'bg-black'; }
    if (text === null || text === '') { text = 'Copied to the clipboard!'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            timer: 500,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bg-black bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
}


$(document).on('click','.btn-preview-ledger', function(){

    var ledger_header_id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: ledger_header_id,
        token: $('#token').val()
    });
    $.when(request('Inquery/get_ledger_details', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#ledger-details > tbody').html('');
            $('#ledger-name').html(`<b>NAME: </b>${response.name}`);
            $('#ledger-code').html(`<b>CODE: </b>${response.tmc_code}`);
            $('#ledger-approved-date').html(`<b>APPROVED DATE: </b>${response.start_date}`);
            $('#ledger-balance').html(`<b>BALANCE: </b>${response.balance}`);
            $('#ledger-due-date').html(`<b>DUE DATE: </b>${response.due_date}`);
            $('#ledger-total-amout-paid').html(`<b>TOTAL AMOUNT PAID: </b>${response.total_amount_paid}`);
            $('#ledger-subscription-amount').html(`<b>SUBSCRIPTION AMOUNT: </b>${response.subscription_amount}`);

            if(response.ledger.success){
                var html;
                response.ledger.ledger_data.forEach((data, index) => {
                    html += ` <tr>
                                <td>${++index}</td>
                                <td>${data.transaction_log_id}</td>
                                <td>${data.paid_amount}</td>
                                <td>${data.balance}</td>
                                <td>${data.due_date}</td>
                                <td>${data.date_paid}</td>
                            </tr>`;
                });

                $('#ledger-details > tbody').html(html);

                if ($.fn.dataTable.isDataTable('#ledger-details')) {
                  $('#ledger-details').DataTable().destroy();
                }

                $('#ledger-details').DataTable({
                    "searching": false,
                    "pageLength": 5,
                    "lengthChange": false,
                    "ordering": false,
                    "info":     false,
                });
            }
            $('#preview-ledger-details').modal('toggle');
        }
    });
});

$(document).on('click','.btn-preview-break-down', function(){

    var ledger_header_id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: ledger_header_id,
        token: $('#token').val()
    });

    $.when(request('Inquery/get_break_down_details', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            $('#break-down-name').html(`<b>NAME: </b>${response.name}`);
            $('#break-down-code').html(`<b>CODE: </b>${response.tmc_code}`);
            $('#break-down-approved-date').html(`<b>APPROVED DATE: </b>${response.start_date}`);
            $('#break-down-balance').html(`<b>BALANCE: </b>${response.balance}`);
            $('#break-down-due-date').html(`<b>DUE DATE: </b>${response.due_date}`);
            $('#break-down-total-amout-paid').html(`<b>TOTAL AMOUNT PAID: </b>${response.total_amount_paid}`);
            $('#break-down-subscription-amount').html(`<b>SUBSCRIPTION AMOUNT: </b>${response.subscription_amount}`);

            if(response.break_down.success){
                var html;
                response.break_down.break_down_data.forEach((data) => {
                    var font_weight;
                    if(data.balance > 0){
                        font_weight = 'font-weight: bold;';
                    }

                    html += ` <tr style="${font_weight}">
                                <td>${data.Lno}</td>
                                <td>${data.due_date}</td>
                                <td>${data.amount}</td>
                                <td>${data.balance}</td>
                            </tr>`;
                });
                $('#break-down > tbody').html(html);

                if ($.fn.dataTable.isDataTable('#break-down')) {
                  $('#break-down').DataTable().destroy();
                }

                $('#break-down').DataTable({
                    "searching": false,
                    "pageLength": 5,
                    "lengthChange": false,
                    "ordering": false,
                    "info":     false,
                });
            }
            $('#preview-break-down').modal('toggle');
        }
    });
});

$(document).on('click','.btn-preview-transaction', function(){
    var id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('Inquery/get_subscription_transaction', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            var html;
            var total_amount = 0;
            response.data.forEach((data, index) => {
                html += ` <tr>
                            <td>${++index}</td>
                            <td>${data.id}</td>
                            <td>${data.amount}</td>
                            <td>${data.date_transact}</td>
                        </tr>`;
                total_amount += parseInt(data.amount);
                if(response.data.length === index){
                    html += ` <tr>
                        <td></td>
                        <td><label>TOTAL</label></td>
                        <td><label>${numeral(total_amount).format('0,0.00')}</label></td>
                        <td></td>
                    </tr>`;
                }
            });

            $('#table-subscription-transaction-logs > tbody').html(html);
            
            if ($.fn.dataTable.isDataTable('#table-subscription-transaction-logs')) {
              $('#table-subscription-transaction-logs').DataTable().destroy();
            }

            $('#table-subscription-transaction-logs').DataTable({
                "searching": false,
                "pageLength": 10,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });

            $('#preview-transaction-logs').modal('toggle');
        }else{
           swal("Warning", response.msg, "warning");
        }
    });
});