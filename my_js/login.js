/**
 * Created by sooglejay on 17/8/24.
 */
$(function () {
    // login();
});
function login() {
    var userName = $("#userName");
    var password = $("#password");
    $.ajax({
        type: 'POST',
        url: '/ziyan/Login.php',
        data: {userName: userName, password: password},
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            window.href.location = '/ziyan/index.html';
        },
        error: function (e) {
            console.log(e);
            window.href.location = '/ziyan/index.html';

        }
    });
}
