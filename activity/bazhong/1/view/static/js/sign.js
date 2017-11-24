/**
 * Created by sooglejay on 17/11/16.
 */

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

function submit(code, phoneNumber) {
    $.ajax({
        url: './../../controller/OrderApp.php',
        type: 'GET',
        data: {
            type: 1,
            phoneNumber: phoneNumber,
            activityCode: code
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在办理,请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            // if (res.code == 200) {
            //     order();
            //     $('#myModal').modal('show');
            //     $(".modal-body").html('办理成功！');
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
});