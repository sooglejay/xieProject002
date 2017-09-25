/**
 * Created by sooglejay on 17/8/24.
 */
var openId = "";
$(function () {
    box.loadding('加载中...');
    checkLogin();
    $("#submit").click(function () {

    });
});

function checkLogin() {
    $.ajax({
        type: 'POST',
        url: 'Login.php',
        data: {
            action: "checkUserIsLogin"
        },
        dataType: 'json',
        success: function (res) {
            openId = res["openId"];
            if (openId.hasOwnProperty("0")) {
                openId = openId["0"];
            }
            if (res.code == 200) {
            }
            layer.closeAll();
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });
}
function submit() {
    var mobilePhone = $("#mobile_phone").val();
    if (!mobilePhone || mobilePhone.length < 1) {
        box.msg("请输入手机号码");
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'testQiandao.php',
        data: {
            mobilePhone: mobilePhone,  openId: openId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
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
