/**
 * Created by sooglejay on 17/8/24.
 */
$(function () {
    var code = getURLParameter("code");
    if (!code) {
        getCode();
    } else {
        getOpenId(code);
    }
    $("#btnLogin").click(function () {
        login();
    });
});

function getCode() {
    var appId = 'wxb76c5258ffa59386';
    var callback = 'http://test.sighub.com/';
    var scope = 'snsapi_base';
//$scope='snsapi_userinfo';//需要授权
    window.location.href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + appId + '&redirect_uri=' + callback + '&response_type=code&scope=' + scope + '&state=1#wechat_redirect';
}
function getOpenId(code) {
    $.ajax({
        type: 'POST',
        url: 'signature.php',
        data: {
            code: code
        },
        dataType: 'json',
        success: function (res) {

        },
        error: function (e) {

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
            userName: userName, password: password,
            openid: request('openid'),
            appid: request('appid')
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
                window.location.href = '/ziyan/index.html';
            }
        },
        error: function (e) {
            layer.closeAll();
            box.msg('登录失败！请联系系统管理员！');
            console.log(e);
        }
    });
}
