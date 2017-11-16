/**
 * Created by sooglejay on 17/11/16.
 */

function checkPhoneNumber(phoneNumber) {
    $.ajax({
        url: './../controller/MainApp.php',
        method: 'POST',
        data: {
            phoneNumber: phoneNumber
        },
        success: function (res) {
            console.log(res);
        },
        error: function (res) {
            console.log(res);
        }
    });
}
$(function () {
    $("#myModal").show();
    $("#btnReceive").click(function () {
        var phoneNumber = $("#phoneNumber").val();
        if (phoneNumber == undefined || phoneNumber.length != 11) {
            alert("请输入正确的手机号码！");
            return;
        }
        checkPhoneNumber(phoneNumber);
    });
});