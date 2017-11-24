/**
 * Created by sooglejay on 17/11/16.
 */

function checkPhoneNumber(phoneNumber) {
    $.ajax({
        url: '../controller/CheckUserApp.php',
        type: 'POST',
        data: {
            'phoneNumber': phoneNumber
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            if (res.code == 200) {
                window.location.href = '' + phoneNumber;
            } else {
                $('#myModal').modal('show');
                $("#des").html(res.message);
            }
            console.log(res);
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
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