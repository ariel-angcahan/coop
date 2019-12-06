
var member_list = $('#member-list').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "ajax": {  
        type:"POST",
        url: 'Analytics/member_list',
        data: function (response){
            response.token = $('#token').val();
        }
    }
});

$(document).on('click','.show-subscription-modal', function(){
    var id = $(this).attr('data-id');
    $("#div-subscription-list-table").css({
        display: "block"
    });

    $("#div-subscription-detail-list-table").css({
        display: "none"
    });

    var form_data = $(this).serialize()+"&"+$.param({
        id: id,
        token: $('#token').val()
    });
    
    $.when(request('Analytics/get_subscription_list', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            var html;
            response.data.forEach((data, index) => {
                html += `<tr>
                            <td>${++index}</td>
                            <td>${data.subscription_amount}</td>
                            <td>${data.current_subscription}</td>
                            <td>${data.parentage}</td>
                            <td style="text-align: center;">${data.button}</td>
                        </tr>`;
            });

            if ($.fn.dataTable.isDataTable('#subscription-list-table')) {
                $('#subscription-list-table').DataTable().destroy();
            }

            $('#subscription-list-table > tbody').html(html);


            $('#subscription-list-table').DataTable({
                "searching": false,
                "pageLength": 5,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });

            $('#subscription-list-modal').modal('toggle');
       }
    });
});
$(document).on('click','.show-subscription-details', function(){

    var id = $(this).attr('data-id');

    var form_data = $(this).serialize()+"&"+$.param({
        id: id,
        token: $('#token').val()
    });

    $.when(request('Analytics/get_subscription_detail_list', form_data)).done(function(response){
        $('#token').val(response.generated_token);
        if(response.success){
            var html;
            var total_payment_count;
            response.data.forEach((data, index) => {
                var labelActive = 0;
                if(data.on_time_count != 0){
                    labelActive = 1;
                }
                var color = '';
                if(data.payment_delay_rating.includes('-')){
                    color = 'col-black';
                }

                total_payment_count = parseInt(data.on_time_count) + parseInt(data.delay_count);

                var tr_bg_color = "";
                if(parseInt(total_payment_count) != 0){
                    tr_bg_color = "bg-light-blue";
                }


                html += `<tr class="${tr_bg_color}">
                            <td>${++index}</td>
                            <td>${data.due_date}</td>
                            <td>${total_payment_count}</td>
                            <td>${data.on_time_count}</td>
                            <td>${data.payment_on_time_rating}</td>
                            <td>${data.delay_count}</td>
                            <td class="${color}">${data.payment_delay_rating}</td>
                        </tr>`;
            });

            if ($.fn.dataTable.isDataTable('#subscription-detail-list-table')) {
                $('#subscription-detail-list-table').DataTable().destroy();
            }

            $('#subscription-detail-list-table > tbody').html(html);

            $('#subscription-detail-list-table').DataTable({
                "searching": false,
                "pageLength": 5,
                "lengthChange": false,
                "ordering": false,
                "info":     false,
            });

            $("#div-subscription-list-table").animateCss("fadeOutLeft"); 
            $(".fadeOutLeft").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                $("#div-subscription-list-table").css({
                    display: "none"
                });
                $("#div-subscription-detail-list-table").show();
                $('#subscription-detail-list-table').css('width', '100%'); // lol this is the fuken culprit
                $("#div-subscription-detail-list-table").animateCss("fadeInRight");
            });
        }
    });
});

$(document).on('click','#btn-subscription-list-table', function(){
    $("#div-subscription-detail-list-table").animateCss("fadeOutRight"); 
    $(".fadeOutRight").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
        $("#div-subscription-detail-list-table").css({
            display: "none"
        });
        $("#div-subscription-list-table").show();
        $('#subscription-list-table').css('width', '100%'); // lol this is the fuken culprit
        $("#div-subscription-list-table").animateCss("fadeInLeft");
    });
});