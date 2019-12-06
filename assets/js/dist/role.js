$(document).ready(function() {
    // init(), 

    $(".selPreLoading").hide(), 

    $("#get_role_list").on("change", function() {
        var e = $("option:selected", this).attr("RoleId");
        $(".selPreLoading").show();

        var s = $(this).serialize() + "&" + $.param({
            RoleId: e,
            token: $('#token').val()
        });

        $(this).attr("disabled", !0);
        var t = request("role/getRoleAccessRights", s);
        $.when(t).done(function(e) {
            // set_token(e.generated_token),
            e.success && ($("#tblAccessRights").html(e.data),
            $("table").treetable()),
            $(".selPreLoading").hide(), 
            $("#get_role_list").removeAttr("disabled")
        })
    }), 

    $(".menu-header").siblings().addClass("root"), 

    $(".root").find("li.active").parentsUntil(".root").last().parent().find(".menu-toggle").trigger("click"), 

    $("body").on("click", ".fa-trig", function() {
        var e = $(this);
        e.addClass("hideBtn"), e.next().addClass("showBtn");

        var s = $(this).attr("data-infa"),
            t = $(this).attr("data-rid"),
            a = $(this).attr("data-menu-id"),
            i = $(this).hasClass("fa-times") ? 1 : 0,

            r = $(this).serialize() + "&" + $.param({
                status: i,
                infa: s,
                rid: t,
                menuId: a,
                token: $('#token').val()
            }),
        n = request("role/ar_update", r);

        i ? ($(this).removeClass("fa-times"), $(this).removeClass("no-rights")) : ($(this).removeClass("fa-check"), $(this).removeClass("has-rights")), 
        $(this).addClass("fa-refresh fa-spin"),

        $.when(n).done(function(s) {
            // set_token(s.generated_token.hash),
             s.success ? (e.removeClass("fa-refresh fa-spin"), i ? (e.addClass("fa-check"), e.addClass("has-rights")) : (e.addClass("fa-times"), e.addClass("no-rights")), e.removeClass("hideBtn"), e.next().removeClass("showBtn"), e.addClass("showBtn"), e.next().addClass("hideBtn"), e.removeClass("showBtn")) : alertify.alert("Error Message", s.msg, function() {})
        })
    })
});