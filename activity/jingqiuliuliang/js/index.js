/**
 * Created by sooglejay on 17/11/5.
 */
function sendSMS(phoneNumber, obj, code, to) {
    // 发送短信给指定号码
    var u = navigator.userAgent, mobile = '';
    if (u.indexOf('iPhone') > -1) mobile = 'iphone';
    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) mobile = 'Android';
    if (mobile == 'Android') {
        $(obj).attr('href', 'sms:' + to + '?body=' + code);
    }
    if (mobile == 'iphone') {
        $(obj).attr('href', 'sms:' + to + '&body=' + code);
    }
    submit(phoneNumber);
}

function submit(phoneNumber) {
    $.ajax({
        type: 'POST',
        url: 'Home.php',
        data: {
            doBuy: 'doBuy',
            mobileNumber: phoneNumber
        },
        dataType: 'json',
        success: function (res) {
            console.log(res);
        },
        error: function (e) {
            console.log(e);
            layer.closeAll();
        }
    });
}

function doSearch() {
    var mobileNumber = $("#phoneNumber").val();
    if (mobileNumber == undefined || mobileNumber.length < 11) {
        $('#myModal').modal('show');
        $('.modal-body').html('请输入11位手机号码');
    } else {
        mobileNumber = mobileNumber.trim();
        $.ajax({
            type: 'POST',
            url: 'Home.php',
            data: {
                checkUser: 'checkUser',
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
                    $("#footer").hide();
                } else {
                    $('#btnClose').hide();
                    $('#footer').removeClass("modal-footer").addClass("new-modal-footer").show();
                    var text = -1;
                    var code = 'KTSF' + res;
                    switch (res) {
                        case 28:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”28元档活动，您本月参加可享受话费赠送56元（办理当天送费28元，2017年11月30日送费28元），赠送话费今年内有效。承诺每月消费28元至2018年6月30日。';
                            break;
                        case 38:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”38元档活动，您本月参加可享受话费赠送76元（办理当天送费38元，2017年11月30日送费38元），赠送话费今年内有效。承诺保底每月38元至2018年6月30日。';
                            break;
                        case 48:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”48元档活动，您本月参加可享受话费赠送96元（办理当天送费48元，2017年11月30日送费48元），赠送话费今年内有效。承诺每月消费48元至2018年6月30日。';
                            break;
                        case 58:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”58元档活动，您本月参加可享受话费赠送116元（办理当天送费58元，2017年11月30日送费58元），赠送话费今年内有效。承诺每月消费58元至2018年6月30日。';
                            break;
                        case 88:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”88元档活动，您本月参加可享受话费赠送176元（办理当天送费88元，2017年11月30日送费88元），赠送话费今年内有效。承诺每月消费88元至2018年6月30日。';
                            break;
                        case 138:
                            text = '凉山移动诚邀您参加“金秋流量敞开用”138元档活动，您本月参加可享受话费赠送276元（办理当天送费138元，2017年11月30日送费138元），赠送话费今年内有效。承诺每月消费138元至2018年6月30日。';
                            break;
                    }
                    $('.modal-body').html(text);
                    $('#doBuy').click(function () {
                        sendSMS(mobileNumber, this, code, '10086');
                    }).show();
                }
                console.log(res);
            },
            error: function (e) {
                console.log(e);
                layer.closeAll();
            }
        });
    }
}
$(function () {
    $('#doBuy').hide();
    $("#search").click(doSearch);
    $('#myModal').on('hide.bs.modal', function () {
        $('#footer').removeClass("new-modal-footer").addClass("modal-footer").show();
        $('#doBuy').hide();
        $('#btnClose').show();
    });
});