
//REALTIME START MAH NIGGA 
Pusher.log = function(message) {
    if (window.console && window.console.log) {
        window.console.log(message);
    }
};

var pusher = new Pusher('191dbef1780135573650');
var channel = pusher.subscribe('ECMS');

channel.bind('add_card', function(data) {
    if($('#department_id').val() == data.department_id){
        card_list.ajax.reload();
    }
});

// channel.bind('card_transaction', function(data) {
//     if($('#department_id').val() == data.department_id){
//         notification_list();
//     }
// });

channel.bind('pusher:subscription_succeeded', function(members) {
    // alert();
});
//REALTIME END MAH NIGGA

notification_list();
channel.bind('notification', function(data) {
    if($('#department_id').val() == data.department_id){
        notification_list();
    }
});

function notification_list(){

    $('#notification_menu').html('');
    var form_data = $(this).serialize()+"&" +$.param({
        token: $('#token').val()
    });

    $.ajax({
        type: "POST",
        url:  "/Utilities/notification_list",
        dataType: "json",
        data: form_data,
        success: function(response) {
            $('#token').val(response.generated_token);
            $('.label-count').html(response.count);
            $('#notification_menu').append(response.data);
        }
    });
}