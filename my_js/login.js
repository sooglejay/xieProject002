/**
 * Created by sooglejay on 17/8/24.
 */
$(function () {
    var openId = getURLParameter('openId');
    if (openId == undefined || openId.length < 5) {
        $("body").html("未能获取用户信息，请在资阳微信公众号中点击图文消息进入!");
    } else {
        checkLogin(openId);
    }
});

function checkLogin(openId) {
    // pre-config
    box.loadding('加载中...');
    $("#btnLogin").click(function () {
        login(openId);
    });

    $.ajax({
        type: 'POST',
        url: 'shop/controller/Login.php',
        data: {
            checkLogin: "checkUserIsLogin",
            openId: openId
        },
        dataType: 'json',
        success: function (res) {
            console.log("jiangwei says:" + res + openId);
            var code = res.code;
            if (code == 200 && openId.length == 28) {
                window.location.href = '/ziyan/home.html?openId=' + openId;
            }
            else if (res.error) {
                alert("error!" + res["error"]);
            }
            layer.closeAll();
        },
        error: function (e) {
            console.log("失败：" + e);
            layer.closeAll();
        }
    });
}
function login(openId) {
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
        url: 'shop/controller/Login.php',
        data: {
            'userName': userName, 'password': password, 'openId': openId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.code == 200) {
                window.location.href = '/ziyan/home.html?openId=' + openId;
            } else {
                box.msg(res.message);
            }
        },
        error: function (e) {
            layer.closeAll();
            box.msg('登录失败！请联系系统管理员！');
            console.log(e);
        }
    });
}
