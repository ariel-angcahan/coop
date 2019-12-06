
$(document).ready(function() {

    $(".menu-header").siblings().addClass("root");
    $(".root").find("li.active").parentsUntil(".root").last().parent().find(".menu-toggle").trigger("click");

    $("body").on("click", ".accessRightOpen", function() {

        btn = $(this);
        btn.addClass("hideBtn"); 
        btn.next().addClass("showBtn");

        var form_data = btn.serialize() + "&" + $.param({
            EmpId: btn.attr("id"),
            token: $('#token').val()
        });

        $.ajax({
            type: "POST",
            url:  "user/menu",
            dataType: "json",
            data: form_data,
            error: function(jqXHR, lsStatus, ex) {
                Swal.fire({title: "System Error!", text: "Please contact your administrator!", type: "warning", timer: 2000});
            },
            success: function(response) {

                $('#token').val(response.generated_token);
                $("#empTable").animateCss("fadeOut");
                $(".fadeOut").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                    $("#empTable").css({
                        display: "none"
                    });
                    $("#empAccessRight").attr("style", "display:block");
                    $("#empAccessRight").animateCss("slideInLeft");
                });

                if (response.success) {
                    $("#accessRights").html(response.data);
                    $("table").treetable();
                    btn.addClass("showBtn"); 
                    btn.next().addClass("hideBtn"); 
                    btn.removeClass("hideBtn");
                    btn.next().removeClass("showBtn"); 
                    btn.removeClass("showBtn");

                } else {
                    Swal.fire({title: "System Error!", text: response.msg, type: "warning", timer: 2000});
                }
        
            }
        });
    });

    $('body').on('click', '#accessRightClose',  function() {

        $("#empAccessRight").animateCss("slideOutLeft");
        $(".slideOutLeft").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
            $("#empAccessRight").css({
                display: "none"
            }), $("#empTable").attr("style", "display:block"), $("#empTable").animateCss("fadeIn")
        })
    });

    $("body").on("click", ".fa-trig", function() {

        var btn = $(this);

        btn.addClass("hideBtn");
        btn.next().addClass("showBtn");
        btn.addClass("fa-refresh fa-spin");
        status = $(this).hasClass("fa-times") ? 1 : 0;

        if(status == 1){
            $(this).removeClass("fa-times");
            $(this).removeClass("no-rights");
        }else{
            $(this).removeClass("fa-check");
            $(this).removeClass("has-rights");
        }

        form_data = $(this).serialize() + "&" + $.param({
            res_id: btn.attr("data-menu-id"),
            status: status,
            infa: $(this).attr("data-infa"),
            rid: $(this).attr("data-rid"),
            token: $('#token').val()
        });

        $.ajax({
            type: "POST",
            url:  "user/stat_update",
            dataType: "json",
            data: form_data,
            error: function(jqXHR, lsStatus, ex) {
                Swal.fire({title: "System Error!", text: "Please contact your administrator!", type: "warning", timer: 2000});
            },
            success: function(response) {

                $('#token').val(response.generated_token);

                if (response.success) {
                    if (status == 1) {
                        btn.removeClass("col-red");
                        btn.addClass("col-light-blue");
                        btn.addClass("fa-check");
                        btn.addClass("has-rights");
                    } else {
                        btn.removeClass("col-light-blue");
                        btn.addClass("col-red");
                        btn.addClass("fa-times");
                        btn.addClass("no-rights");
                    }
                    
                    btn.removeClass("hideBtn");
                    btn.next().removeClass("showBtn");
                    btn.addClass("showBtn");
                    btn.next().addClass("hideBtn");
                    btn.removeClass("showBtn");
                } else {
                    Swal.fire({title: "System Error!", text: response.msg, type: "warning", timer: 2000});
                }
        
            }
        });
    });

});

var user_list = $('#user_list').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "lengthChange": false,
    "ajax": {  
        type:"POST",
        url: 'user/getEmpList',  // method  , by default get
        data: function (response){
            response.token = $('#token').val();
        }
    },
    "order": [0, 'asc'],
    columnDefs: [
        {orderable: false, targets: [3]},
        {searchable: false, targets: [3]}
    ]
});
