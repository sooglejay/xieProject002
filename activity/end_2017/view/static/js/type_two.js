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
            type: 2,
            phoneNumber: phoneNumber,
            activityCode: activityCode
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在办理,请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            // if (res.code == 200) {
            //     $('#myModal').modal('show');
            //     $(".modal-body").html('请根据网页提示发送对应短信代码');
            // } else {
            //     $('#myModal').modal('show');
            //     $(".modal-body").html(res.message);
            // }
            console.log(res);
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
        }
    });
}

$(function () {
    $(".dismissImg").click(function () {
        $('#myModal').modal('hide');
    });

    $('#btnSubmitTypeTwo_a').click(function () {
        submit('LLCX5');
    });

    $('#btnSubmitTypeTwo_b').click(function () {
        submit('LLBN20');
    });

});