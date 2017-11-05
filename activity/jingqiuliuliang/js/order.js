/**
 * Created by sooglejay on 17/11/5.
 */
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}
function exportExcel() {
    $.ajax({
        type: 'POST',
        url: 'export/doExcelHandler.php',
        data: {
            "n": 'd'
        }
    });
}
function doBuy() {
    var mobileNumber = $("#phoneNumber").val();
    var address = $("#address").val();
    if (mobileNumber == undefined || mobileNumber.length < 11) {
        $('#myModal').modal('show');
        $('.modal-body').html('请输入11位手机号码');
        return;
    }
    if (address == undefined || address.length < 1) {
        $('#myModal').modal('show');
        $('.modal-body').html('请输入区县');
        return;
    }

    mobileNumber = mobileNumber.trim();
    address = address.trim();
    $.ajax({
        type: 'POST',
        url: 'Home.php',
        data: {
            doBuy: 'doBuy',
            address: address,
            mobileNumber: mobileNumber
        },
        beforeSend: function () {
            box.loadding('加载中,请稍后...');
        },
        dataType: 'json',
        success: function (res) {
            layer.closeAll();
            $('#myModal').modal('show');
            if (!res) {
                $('.modal-body').html('抱歉，您不是本次活动的目标客户');
            } else {
                $('.modal-body').html('恭喜您预约成功');
                exportExcel();
            }

            console.log(res);
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });

}
$(function () {
    $("#phoneNumber").val(getQueryString("mobileNumber"));
    $('#doBuy').click(doBuy);
});