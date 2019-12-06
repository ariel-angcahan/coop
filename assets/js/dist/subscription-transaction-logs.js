//Bootstrap datepicker plugin
$('#bs_datepicker_container input').datepicker({
    autoclose: true,
    container: '#bs_datepicker_container',
    // format: 'yyyy-mm-dd'
});

$(document).on('click','#btn-filter-submit', function(){
    subscription_request.ajax.reload();
});

var subscription_request = $('#subscription-transaction-table').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "ajax": {  
        type:"POST",
        url: 'SubscriptionTransactionLogs/subscription_transaction_logs',  // method  , by default get
        global: false,
        data: function (response){
            response.token = $('#token').val();
            response.from = $('#date-from').val();
            response.to = $('#date-to').val();
        }
    }
});

$(document).on('click','.btn-transaction-details', function(){
    var id = $(this).attr('data-id');
    var data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });
    $('#subscription-transaction-detail-table > tbody').html('');
    $.when(request('SubscriptionTransactionLogs/get_transaction_details', data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            var html;
            
            response.data.forEach((data, index) => {
                html += `<tr>
                            <td>${++index}</td>
                            <td>${data.due_date}</td>
                            <td>${data.paid_amount}</td>
                            <td>${data.date_paid}</td>
                        </tr>`;
            });
                html += `<tr>
                            <td><br></td>
                            <td><br><label>TOTAL</label></td>
                            <td><br><label>${response.total_amount}</label></td>
                            <td><br><label>-------------------------------------</label></td>
                        </tr>`;

            $('#subscription-transaction-detail-table > tbody').html(html);
            $('#subscription-transaction-modal').modal('toggle');
        }
    });
});

$(document).on('click','#btn-print-preview', function(){
    var date_from = $('#date-from').val();
    var date_to = $('#date-to').val();
    var arrErr = [];
    var arrCtr = 0;

    if(date_from.length == 0){
        arrErr.push("Date from");
        arrCtr++;
    }

    if(date_to.length == 0){
        arrErr.push("Date to");
        arrCtr++;
    }

    if(arrCtr == 0){
        window.open(`SubscriptionTransactionLogs/transaction_report?date_from=${date_from}&date_to=${date_to}`);
    }else{
        swal("Warning", `"${arrErr.join(", ")} is empty!"`, "warning");
    }
});