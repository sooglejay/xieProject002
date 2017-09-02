/**
 * Created by sooglejay on 17/9/2.
 */


$(function () {
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
                    window.location.href = "activity.html?mobile=" + mobilePhone;
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