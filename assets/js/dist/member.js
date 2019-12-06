// $('#calendar').fullCalendar({
//   defaultView: 'month',
//   events: 'https://fullcalendar.io/demo-events.json'
// });

$(document).ready(function(){
    $('li > a > span').css('margin-top', '6px');

    // $('#preview-ledger-details-slimscroll').slimScroll({
    //     height: '520px'
    // });
    $('#preview-break-down_slimscroll').slimScroll({
        height: '520px'
    });

    
});

$(document).on('blur','#payment-amount', function(){
    $(this).val(numeral($(this).val()).format('0,0.00'));
});

$(document).ready(function(){
    var member_list = $('#member-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax": {  
            type:"POST",
            url: '../Members/member_list',  // method  , by default get
            global: false,
            data: function (response){
                response.token = $('#token').val();
            }
        }
    });
});

$(document).on('click','.btn-preview-subscription', function(){

    var id = $(this).attr("data-id");
    var name = $(this).closest('tr').find("td").eq(1).html();
    var code = $(this).closest('tr').find("td").eq(2).find('label').html();

    // /if table exists destroy it
    if ($.fn.dataTable.isDataTable('#subscription-table')) {
      $('#subscription-table').DataTable().destroy();
    }

    var subscription_list = $('#subscription-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax": {  
            type:"POST",
            url: '../Members/subscription_list',  // method  , by default get
            data: function (response){
                response.token = $('#token').val();
                response.id = id;
            }
        }
    });

    $.when(subscription_list).done(function(response){
        $("#member-list-div-table").animateCss("fadeOutLeft"); 
        $(".fadeOutLeft").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
            $("#member-list-div-table").css({
                display: "none"
            });
            $("#subscription-div-table").show();
            $('#subscription-btn-name').html(name);
            $('#subscription-btn-code').html(code);
            $('#subscription-table').css('width', '100%'); // lol this is the fuken culprit
            $("#subscription-div-table").animateCss("fadeInRight");
        });

        $('#back-main-table-btn').attr('data-id', id);
    });
});

$(document).on('click','#back-main-table-btn', function(){
    $("#subscription-div-table").animateCss("slideOutRight"); 
    $(".slideOutRight").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
        $("#subscription-div-table").css({
            display: "none"
        });
        $("#member-list-div-table").show();
        $("#member-list-div-table").animateCss("slideInLeft");
    });
});


$(document).on('click','.btn-preview-info', function(){

    var form_data = $(this).serialize()+"&" +$.param({
        id: $(this).attr("preview-id"),
        token: $('#token').val()
    });

    $.when(request('../Applications/get_application_information', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        $('#applicant-image').attr("src", "");
        if(response.success){
            $('#full_name').html(`${response.first_name} ${response.middle_name} ${response.last_name}`);
            // $('#birth_date').html(response.birth_date);
            // $('#email').html(response.email);
            $('#applicant-image').attr("src", response.image_name);
            // $('#mobile').html(response.mobile_no);
            // $('#market_address').html(response.market_address);
            // $('#membership_type').html(response.membership_type);
            // $('#payment_mode').html(response.payment_mode);
            // $('#subscription_amount').html(response.subscription_amount);
            // $('#payment_per_mode').html(response.payment_per_mode);
            $('#image-upload').attr("data-id", response.id);

            var html = `<div class="body table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td style="text-align: right;">Birth Date: </td>
                                    <td>${response.birth_date}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Email: </td>
                                    <td>${response.email}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Mobile: </td>
                                    <td>${response.mobile_no}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Market Address: </td>
                                    <td>${response.market_address}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Membership: </td>
                                    <td>${response.membership_type}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>`;
            $('#profile-info').html(html);
            $('#preview-info').modal('show');
        }else{
            swal("Warning", response.msg, "warning");
        }
    });
});

$(document).on('click','#btn-upload', function(){
    $('#image_file').click();
});

$(document).ready(function() {
    Dropzone.autoDiscover = false;
    $("div#image-upload").dropzone({ 
        url: "../Applications/upload_image",
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

// $('#ledger-details').DataTable({
//     "searching": false,
//     "pageLength": 10,
//     "lengthChange": false,
//     "paging":   false,
//     "ordering": false,
//     "info":     false,
// });

$(document).on('click','.btn-preview-ledger', function(){

    var ledger_header_id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: ledger_header_id,
        token: $('#token').val()
    });
    $.when(request('../Members/get_ledger_details', form_data)).done(function(response){
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
                    html += `<tr>
                                <td>${++index}</td>
                                <td>${data.transaction_log_id}</td>
                                <td>${data.paid_amount}</td>
                                <td>${data.balance}</td>
                                <td>${data.due_date}</td>
                                <td>${data.date_paid}</td>
                            </tr>`; 
                if (response.ledger.ledger_data.length === index) {
                    html += `<tr>
                                <td><br></td>
                                <td><br><label>TOTAL</label></td>
                                <td><br><label>${response.total_amount_paid}</label></td>
                                <td><br><label>${response.balance}</label></td>
                                <td><br><label>-------------</label></td>
                                <td><br><label>---------------</label></td>
                            </tr>`;
                        }
                });
                    
                if ($.fn.dataTable.isDataTable('#ledger-details')) {
                  $('#ledger-details').DataTable().destroy();
                }

                $('#ledger-details > tbody').html(html);

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

$(document).on('click','#payment-btn', function(){
    $('#payment-modal').modal('toggle');
});

$(document).on('click','.btn-preview-break-down', function(){

    var ledger_header_id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: ledger_header_id,
        token: $('#token').val()
    });

    $.when(request('../Members/get_break_down_details', form_data)).done(function(response){
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

                if ($.fn.dataTable.isDataTable('#break-down')) {
                  $('#break-down').DataTable().destroy();
                }
                
                $('#break-down > tbody').html(html);

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

$(document).on('focusout','#payment-tmc-code', function(){
    if($('#payment-tmc-code').val().length != 0){
        var form_data = $(this).serialize()+"&" +$.param({
            tmc_code: $('#payment-tmc-code').val(),
            // ledger_header_id: $('#payment_variance_date option:selected').val(),
            // amount: $('#payment_amount').val(),
            token: $('#token').val()
        });

        $.when(request('../Members/get_payment_account_details', form_data)).done(function(response){
            $('#token').val(response.generated_token);
            if(response.success){
                $('#payment-account-holder').val(response.name);
            }else{
               swal("Warning", response.msg, "warning");
                $('#payment-tmc-code').val('');
                $('#payment-amount').val('');
            }
        });
    }
});

$(document).on('click','#btn-payment-proceed', function(){
    var form_data = $(this).serialize()+"&" +$.param({
        tmc_code: $('#payment-tmc-code').val(),
        amount: $('#payment-amount').val(),
        token: $('#token').val()
    });

    $.when(request('../Members/payment_proceed', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            swal({title: "Success", text: response.msg, type: "success"}, function(){
                $('#payment-tmc-code').val('');
                $('#payment-account-holder').val('');
                $('#payment-amount').val('');
                $('#payment-variance-date').html('').selectpicker('refresh');
                $('#payment-modal').modal('toggle');
            });
        }else{
           swal("Warning", response.msg, "warning");
            $('#payment-amount').val('');
        }
    });
});

$(document).on('click','#btn-print-break-down', function(){
    var id = $('.btn-preview-break-down').attr('data-id');
    window.open("break_down_print_layout?id="+id);
});

$(document).on('click','#btn-print-ledger', function(){
    var id = $('.btn-preview-break-down').attr('data-id');
    window.open("ledger_print_layout?id="+id);
});

$(document).on('click','.btn-preview-transaction', function(){
    var id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&" +$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('../Members/get_subscription_transaction', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            var html;
            var total_amount = 0;
            response.data.forEach((data, index) => {
                html += ` <tr>
                            <td>${++index}</td>
                            <td>${data.id}</td>
                            <td>${numeral(data.amount).format('0,0.00')}</td>
                            <td>${data.date_transact}</td>
                        </tr>`;
                total_amount += parseFloat(data.amount);
                if(response.data.length === index){
                    html += `<tr>
                                <td><br></td>
                                <td><br><label>TOTAL</label></td>
                                <td><br><label>${numeral(total_amount).format('0,0.00')}</label></td>
                                <td><label><br>------------------------</label></td>
                            </tr>`;
                }
            });
            
            if ($.fn.dataTable.isDataTable('#table-subscription-transaction-logs')) {
              $('#table-subscription-transaction-logs').DataTable().destroy();
            }
            
            $('#table-subscription-transaction-logs > tbody').html(html);

            $('#table-subscription-transaction-logs').DataTable({
                "searching": false,
                "pageLength": 5,
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