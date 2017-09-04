/**
 * Created by sooglejay on 17/9/2.
 */


$(function () {

    var type_88 = getURLParameter("type_88");
    if (type_88 != null) {
        var type_138 = getURLParameter("type_138");
        var type_158 = getURLParameter("type_158");
        var type_238 = getURLParameter("type_238");
        var data = [];
        var i = 0;
        if (type_88 == "1") {
            data[i++] = {id: "88", text: "飞享88套餐"};
        }
        if (type_138 == "1") {
            data[i++] = {id: "138", text: "飞享138套餐"};
        }

        if (type_158 == "1") {
            data[i++] = {id: "158", text: "飞享158套餐"};
        }
        if (type_238 == "1") {
            data[i] = {id: "238", text: "飞享238套餐"};
        }
        $("#type-select").select2({
            data: data
        });
    }

    $("#a-submit").click(function () {
        var mobilePhone = $("#mobile-phone").val();
        if (!mobilePhone || mobilePhone.length < 11) {
            box.msg("请输入合法的手机号码");
            return;
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'PHPActivity.php',
            data: {actionName: 'check', mobile: mobilePhone},
            success: function (e) {
                if (e.hasOwnProperty("error")) {
                    box.msg(e.message);
                    return;
                } else {
                    var type_88 = e.type_88;
                    var type_138 = e.type_138;
                    var type_158 = e.type_158;
                    var type_238 = e.type_238;
                    window.location.href = "activity.html?mobile=" + mobilePhone +
                        "&type_88=" + type_88 +
                        "&type_138=" + type_138 +
                        "&type_158=" + type_158 +
                        "&type_238=" + type_238
                    ;
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });


    $("#type-submit").click(function () {
        var gender = $("#gender-select").find(':selected').val();
        var type = $("#type-select").find(':selected').val();
        var address = $("#address").val();
        var userName = $("#userName").val();
        var mobile = getURLParameter("mobile");
        if (gender == undefined || gender.length < 1) {
            box.msg("请选择性别！");
            return;
        }
        if (address == undefined || address.length < 1) {
            box.msg("请输入地址！");
            return;
        }
        if (userName == undefined || userName.length < 1) {
            box.msg("请输入姓名！");
            return;
        }
        if (mobile == undefined || mobile.length < 1) {
            box.msg("请输入手机号码！");
            return;
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "PHPActivity.php",
            data: {
                mobile: mobile,
                gender: gender,
                type: type,
                userName: userName,
                address: address,
                actionName: "saveType"
            },
            success: function (e) {
                box.msg(e.message);
            },
            error: function (e) {
                box.msg(e);
            }
        });
    });

});