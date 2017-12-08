/**
 * Created by sooglejay on 17/11/16.
 */


function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

function checkPhoneNumber(phoneNumber) {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/register.do',
        type: 'POST',
        data: {
            phone: phoneNumber,
            sessionId:sessionId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            var status = res.status;
            switch (status){
                case 200:
                    window.location.href = 'http://test.sighub.com/ziyan/activity/bazhong/1/view/html/page.html?sessionId=' + sessionId;
                    break;
                default:
                    $('#myModal').modal('show');
                    $("#des").html(res.msg);
                    break;
            }
            console.log(res);
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
            $('#myModal').modal('show');
            $("#des").html(JSON.stringify(res.responseText));
        }
    });
}
function removeHeaderAndTailSpace(s) {
    return s.replace(/^\s+|\s+$/g, '');
}

$(function () {
    $("#btnLogin").click(function () {
        var phoneNumber = removeHeaderAndTailSpace($("#phoneNumber").val());
        if (phoneNumber == undefined || phoneNumber.length != 11) {
            $('#myModal').modal('show');
            $("#des").html("请输入正确的手机号码！");
            return;
        }
        checkPhoneNumber(phoneNumber);
    });
    $(".dismissImg").click(function () {
        $('#myModal').modal('hide');
    })
});