$(document).ready(function() {
    function e(e) {
        if (e.files && e.files[0]) {
            var t = new FileReader;
            t.onload = function(e) {
                $("#avatar").attr("src", e.target.result)
            }, t.readAsDataURL(e.files[0])
        }
    }

    function e(e) {
        if (e.files && e.files[0]) {
            var t = new FileReader;
            t.onload = function(e) {
                $("#avatarEmp").attr("src", e.target.result)
            }, t.readAsDataURL(e.files[0])
        }
    }
    core.init(), $("#ifActive").attr("checked", !1), $("#btnAddDependent").on("click", function() {
        $("#addDependent").modal("show")
    }), $(".menu-header").siblings().addClass("root"), $("#btnSaveForm").on("click", function() {
        $("#addEmp").submit()
    }), $(".menu-header").siblings().addClass("root"), $(".root").find("li.active").parentsUntil(".root").last().parent().find(".menu-toggle").trigger("click"), $("#btnDeleteForm").on("click", function() {
        swal({
            title: "Are you sure?",
            text: "You want to delete this record!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            closeOnConfirm: !1
        }, function() {
            var e = $(location).attr("href").split("/"),
                a = "view" == e[4] ? "../delete_emp" : "delete_emp",
                n = $(this).serialize() + "&" + $.param({
                    EmpId: e[5]
                }),
                o = core.request(a, n);
            $.when(o).done(function(e) {
                core.set_token(e.generated_token.hash), $("#addEmp").trigger("reset"), $("#dependentList").DataTable().destroy(), t = $("#dependentList").DataTable().draw(), swal("Record", "Successfully deleted", "success")
            })
        })
    });
    var t = $("#dependentList").DataTable({
            select: !0
        }),
        a = [4];
    $("#employeeList").DataTable({
        processing: !0,
        serverSide: !0,
        order: [],
        ajax: {
            url: "employeeList",
            type: "post",
            data: {
                token: $("#token").val(),
                csrf_token: $("#token").val()
            },
            error: function() {
                $("." + employeeList + "-grid-error").html(""), $("#" + employeeList + "-grid").append('<tbody class="' + employeeList + '-grid-error"><tr><th colspan="4">No data found in the server</th></tr></tbody>'), $("#" + employeeList + "-grid_processing").css("display", "none")
            },
            dataSrc: function(e) {
                return core.set_token(e.generated_token.hash), e.data
            }
        },
        columnDefs: [{
            targets: a,
            orderable: !1
        }]
    }), $("#birthDate").on("change", function() {
        $("#bdate").val($(this).val())
    }), $("#ifActive").on("change", function() {
        this.checked ? $("#ActiveFlag").val("1") : $("#ActiveFlag").val("0")
    }), $("#addEmp").on("submit", function(e) {
        e.preventDefault(), $(this).ajaxSubmit({
            beforeSend: function() {
                swal({
                    title: "Saving <span class='loadingPercent'>1%</span>...",
                    text: '<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" id="progressBar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>',
                    html: !0,
                    showConfirmButton: !1
                }), $("#progressBar").css("width", "0%"), $(".loadingPercent").html("0%")
            },
            uploadProgress: function(e, t, a, n) {
                $("#progressBar").css("width", n + "%"), $(".loadingPercent").html(n + "%")
            },
            success: function(e) {
                "view" == $(location).attr("href").split("/")[4] ? location.reload() : $("#addEmp").trigger("reset"), $("#avatar").attr("src", "http://hris.twmc.com/assets/images/user_img/avatar.png"), swal("Employee Record", "Successfully Saved", "success")
            }
        })
    }), $("#avatar").on("click", function() {
        $("#file").trigger("click")
    }), $("#file").change(function() {
        e(this)
    }), $("#btn-add-depend").on("click", function() {
        var e = "view" == $(location).attr("href").split("/")[4] ? "../addDependent" : "addDependent";
        swal({
            title: "Are sure you want to save ?",
            text: "",
            type: "info",
            showCancelButton: !0,
            closeOnConfirm: !1,
            showLoaderOnConfirm: !0
        }, function() {
            var a = $(this).serialize() + "&" + $.param({
                    DepId: $("#depId").val(),
                    EmpId: $("#empId").val(),
                    DepLastName: $("#depLname").val(),
                    DepFirstName: $("#depFname").val(),
                    DepMiddleName: $("#depMname").val(),
                    DepBloodTypeId: $("#depBloodType").val(),
                    DedRelationshipId: $("#depRelation").val(),
                    DepDateOfBirth: $("#depBirthDate").val()
                }),
                n = core.request(e, a);
            $.when(n).done(function(e) {
                core.set_token(e.generated_token.hash), $("#dependentList").DataTable().destroy(), $("#dependentList").find("tbody").html(e.data), t = $("#dependentList").DataTable().draw(), $("#addDependent").modal("hide"), swal("New Dependent", "Successfully Added", "success")
            })
        })
    });
    var n = $(location).attr("href").split("/");
    if ("view" == n[4]) {
        var o = $(this).serialize() + "&" + $.param({
                EmpId: n[5]
            }),
            r = core.request("../info", o);
        $.when(r).done(function(e) {
            core.set_token(e.generated_token.hash), $("#dependentList").DataTable().destroy(), $("#dependentList").find("tbody").html(e.dependents), t = $("#dependentList").DataTable().draw(), $("#empId").val(n[5]), $("#empCode").val(e.employee_info.empCode), $("#status").val(e.employee_info.status).selectpicker("render"), $("#dayOff").val(e.employee_info.dayOff).selectpicker("render"), $("#jobDesc").val(e.employee_info.jobDesc), $("#coordinator").val(e.employee_info.coordinator).selectpicker("render"), $("#branch").val(e.employee_info.branch).selectpicker("render"), $("#section").val(e.employee_info.section).selectpicker("render"), $("#position").val(e.employee_info.position).selectpicker("render"), $("#ActiveFlag").val(e.employee_info.ActiveFlag), 1 == e.employee_info.ActiveFlag ? $("#ifActive").prop("checked", !0) : $("#ifActive").prop("checked", !1), $("#fname").val(e.employee_info.fname), $("#mname").val(e.employee_info.mname), $("#lname").val(e.employee_info.lname), $("#gender").val(e.employee_info.gender).selectpicker("render"), $("#age").val(e.employee_info.age), $("#birthDate").bootstrapMaterialDatePicker("setDate", moment(e.employee_info.bdate)), $("#placeOfBirth").val(e.employee_info.placeOfBirth), $("#bloodType").val(e.employee_info.bloodType).selectpicker("render"), $("#civilStatus").val(e.employee_info.civilStatus).selectpicker("render"), $("#religion").val(e.employee_info.religion).selectpicker("render"), $("#height").val(e.employee_info.height), $("#weight").val(e.employee_info.weight), $("#mobNum").val(e.employee_info.mobNum), $("#phoneNum").val(e.employee_info.phoneNum), $("#preAddr").val(e.employee_info.preAddr).selectpicker("render"), $("#comPreAddr").val(e.employee_info.comPreAddr), $("#proAddr").val(e.employee_info.proAddr).selectpicker("render"), $("#comProAddr").val(e.employee_info.comProAddr), $("#secondary").val(e.employee_info.secondary).selectpicker("render"), $("#ternary").val(e.employee_info.ternary).selectpicker("render"), $("#contactFname").val(e.employee_info.contactFname), $("#contactMname").val(e.employee_info.contactMname), $("#contactLname").val(e.employee_info.contactLname), $("#contactPreAddr").val(e.employee_info.contactPreAddr).selectpicker("render"), $("#comContactPreAddr").val(e.employee_info.comContactPreAddr), $("#contactProAddr").val(e.employee_info.contactProAddr).selectpicker("render"), $("#comContactProAddr").val(e.employee_info.comContactProAddr), $("#contactRelation").val(e.employee_info.contactRelation).selectpicker("render"), $("#contactMobNum").val(e.employee_info.contactMobNum), $("#contactPhoneNum").val(e.employee_info.contactPhoneNum), $("#spFname").val(e.employee_info.spFname), $("#spMname").val(e.employee_info.spMname), $("#spLname").val(e.employee_info.spLname), $("#spComName").val(e.employee_info.spComName), $("#spComAddr").val(e.employee_info.spComAddr), $("#spPosition").val(e.employee_info.spPosition), $("#spMobNum").val(e.employee_info.spMobNum), $("#spPhoneNum").val(e.employee_info.spPhoneNum), $("#avatar").attr("src", e.employee_info.avatar)
        })
    }
    $("#dependentList tbody").on("click", "tr", function() {
        $(this).toggleClass("selected")
    }), $("#btnEditDependent").on("click", function() {
        var e = null;
        if (t.rows(".selected").data().length > 1) swal("Info", "Please select one person only", "info");
        else if (t.rows(".selected").data().length < 1) swal("Info", "Please atleast one person", "info");
        else {
            var a = "view" == $(location).attr("href").split("/")[4] ? "../dependentInfo" : "dependentInfo";
            $("#dependentList tbody").find("tr.selected").each(function() {
                e = $(this).attr("data-id")
            });
            var n = $(this).serialize() + "&" + $.param({
                    depID: e
                }),
                o = core.request(a, n);
            $.when(o).done(function(e) {
                $("#depId").val(e.data.DepId), $("#depFname").val(e.data.DepFirstName), $("#depMname").val(e.data.DepMiddleName), $("#depLname").val(e.data.DepLastName), $("#depBloodType").val(e.data.DepBloodTypeId).selectpicker("render"), $("#depRelation").val(e.data.DedRelationshipId).selectpicker("render"), $("#depBirthDate").bootstrapMaterialDatePicker("setDate", moment(e.data.DepDateOfBirth)), core.set_token(e.generated_token.hash), $("#addDependent").modal("show")
            })
        }
    }), $("#btnDeleteDependent").on("click", function() {
        var e = [];
        t.rows(".selected").data().length < 1 ? swal("Info", "Please atleast one", "info") : swal({
            title: "Are you sure?",
            text: "You want to delete this dependent!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            closeOnConfirm: !1
        }, function() {
            var a = "view" == $(location).attr("href").split("/")[4] ? "../delete" : "delete";
            $("#dependentList tbody").find("tr.selected").each(function() {
                e.push($(this).attr("data-id"))
            });
            var n = $(this).serialize() + "&" + $.param({
                    DepId: e,
                    EmpId: $("#empId").val()
                }),
                o = core.request(a, n);
            $.when(o).done(function(e) {
                core.set_token(e.generated_token.hash), $("#dependentList").DataTable().destroy(), $("#dependentList").find("tbody").html(e.dependents), t = $("#dependentList").DataTable().draw(), swal("Dependent", "Successfully deleted", "success")
            })
        })
    }), $("body").on("click", "#loadMore", function(e) {
        e.preventDefault()
    }), $("#avatarEmp").on("click", function() {
        $("#fileEmp").trigger("click")
    }), $("#changeProfile").on("submit", function(e) {
        e.preventDefault(), $(this).ajaxSubmit({
            beforeSend: function() {
                swal({
                    title: "Saving <span class='loadingPercent'>1%</span>...",
                    text: '<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" id="progressBar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>',
                    html: !0,
                    showConfirmButton: !1
                }), $("#progressBar").css("width", "0%"), $(".loadingPercent").html("0%")
            },
            uploadProgress: function(e, t, a, n) {
                $("#progressBar").css("width", n + "%"), $(".loadingPercent").html(n + "%")
            },
            success: function(e) {
                swal("Profile Picture", "Successfully Updated", "success"), swal("Profile Picture", "Successfully Updated", "success")
            }
        })
    }), $("#fileEmp").change(function() {
        e(this), $("#changeProfile").trigger("submit")
    }), $("body").on("click", "#btnCpass", function(e) {
        e.preventDefault();
        var t = $(this).serialize() + "&" + $.param({
                password: $("#newPass").val(),
                cpassword: $("#cpass").val()
            }),
            a = core.request("Profile/changePassword", t);
        $.when(a).done(function(e) {
            e.success ? (swal("Password", "Successfully change.", "success"), $("#newPass").val(""), $("#cpass").val("")) : swal("Warning", e.msg, "warning"), core.set_token(e.generated_token.hash)
        }), $("#divWrapper").append('<button class="btnWrap" id="submitBtt">submit</button>')
    })
});