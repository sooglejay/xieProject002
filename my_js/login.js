/**
 * Created by sooglejay on 17/8/24.
 */
var openId = "";
$(function () {
    box.loadding('加载中...');
    checkLogin();
    $("#btnLogin").click(function () {
        login();
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
            console.log("jiangwei says:"+res);
            if (res.code == 200) {
                window.location.href = '/ziyan/home.html?openId=' + openId;
            }
            layer.closeAll();
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });
}
function login() {
    var userName = $("#userName").val();
    var password = $("#password").val();
    if (!userName || userName.length < 1) {
        box.msg("请输入用户名");
        return;
    }
    if (!password || password.length < 1) {
        box.msg("请输入密码");
        return;
    }
    if (!openId || openId.length < 1) {
        box.msg("微信openId为空，请在微信中使用！");
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'Login.php',
        data: {
            'userName': userName, 'password': password, 'openId': openId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.error) {
                box.msg(res.message);
            } else {
                window.location.href = '/ziyan/home.html?openId=' + res["openId"];
            }
        },
        error: function (e) {
            layer.closeAll();
            box.msg('登录失败！请联系系统管理员！');
            console.log(e);
        }
    });
}
