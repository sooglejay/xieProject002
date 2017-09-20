/**
 * Created by sooglejay on 17/8/24.
 */

$(function () {
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
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.code == 200) {
                window.location.href = '/ziyan/home.html?openId='+res["openId"];
            }
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
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
    $.ajax({
        type: 'POST',
        url: 'Login.php',
        data: {
            userName: userName, password: password
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        }

        ,
        success: function (res) {
            layer.closeAll();
            if (res.error) {
                box.msg(res.message);
            } else {
                window.location.href = '/ziyan/home.html';
            }
        }
        ,
        error: function (e) {
            layer.closeAll();
            box.msg('登录失败！请联系系统管理员！');
            console.log(e);
        }
    });
}
