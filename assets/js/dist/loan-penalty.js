$(document).ready(function(){
    var member_list = $('#loan-penalty-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "pageLength": 5,
        "lengthChange": false,
        "info":     false,
        "ajax": {  
            type:"POST",
            url: '../LoanPenalty/loan_penalty_list',  // method  , by default get
            global: false,
            data: function (response){
                response.token = $('#token').val();
            }
        }
    });
});

input[id="btn-name"]
#id
.class