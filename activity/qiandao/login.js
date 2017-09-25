/**
 * Created by sooglejay on 17/8/24.
 */
var openId = "";
$(function () {
    checkLogin();
    $("#submit").click(function () {
        doQianDao();
    });
});
function checkIsQianDaoed() {
    $.ajax({
        type: 'POST',
        url: 'QianDao.php',
        data: {
            check: "checkUserIsQianDaoed",
            openId: openId
        },
        dataType: 'json',

        success: function (res) {
            if (res.code == 200) {
                doQianDao(true);
            }
            layer.closeAll();
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });

}
function checkLogin() {
    $.ajax({
        type: 'POST',
        url: '/ziyan/Login.php',
        data: {
            action: "checkUserIsLogin"
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('正在验证用户合法性...');
        },
        success: function (res) {
            openId = res["openId"];
            if (openId.hasOwnProperty("0")) {
                openId = openId["0"];
            }
            if (res.code == 200) {
                checkIsQianDaoed();
            }
            layer.closeAll();
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });
}
function doQianDao(isQianDaoed) {
    var mobilePhone = $("#mobile_phone").val();
    if ((!mobilePhone || mobilePhone.length < 1) && !isQianDaoed) {
        box.msg("请输入手机号码");
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'QianDao.php',
        data: {
            mobilePhone: mobilePhone, openId: openId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('正在签到...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.error) {
                box.msg(res.message);
            }
        },
        error: function (e) {
            layer.closeAll();
            box.msg('签到失败！请联系系统管理员！');
            console.log(e);
        }
    });
}
