/**
 * Created by sooglejay on 17/11/16.
 */

function checkPhoneNumber(phoneNumber) {
    $.ajax({
        url: './../controller/MainApp.php',
        type: 'POST',
        data: {
            phoneNumber: phoneNumber
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.code == 200) {
                window.location.href = 'html/type_' + res.type + '.html';
            } else {
                box.msg(res.message);
            }
            console.log(res);
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
        }
    });
}
$(function () {
    $("#btnReceive").click(function () {
        var phoneNumber = $("#phoneNumber").val();
        if (phoneNumber == undefined || phoneNumber.length != 11) {
            alert("请输入正确的手机号码！");
            return;
        }
        checkPhoneNumber(phoneNumber);
    });
});