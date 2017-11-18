/**
 * Created by sooglejay on 17/11/16.
 */
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}
function submit(activityCode) {
    var phoneNumber = getURLParameter('phoneNumber');
    if (phoneNumber == undefined || phoneNumber.length != 11) {
        $('#myModal').modal('show');
        $(".modal-body").html('请输入正确的手机号码！');
        return;
    }
    $.ajax({
        url: './../../controller/OrderApp.php',
        type: 'GET',
        data: {
            type: 3,
            phoneNumber: phoneNumber,
            activityCode: activityCode
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在办理,请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            if (res.code == 200) {
                $('#myModal').modal('show');
                $(".modal-body").html('办理成功！');
            } else if (res.message) {
                $('#myModal').modal('show');
                $(".modal-body").html(res.message);
            }
            console.log(res);
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
        }
    });
}
function sendSMS(obj, code, to) { // 发送短信给指定号码
    var u = navigator.userAgent, mobile = '';
    if (u.indexOf('iPhone') > -1) mobile = 'iphone';
    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) mobile = 'Android';
    if (mobile == 'Android') {
        $(obj).attr('href', 'sms:' + to + '?body=' + code);
    }
    if (mobile == 'iphone') {
        $(obj).attr('href', 'sms:' + to + '&body=' + code);
    }
    submit(code);
}

$(function () {
    $(".dismissImg").click(function () {
        $('#myModal').modal('hide');
    });
});