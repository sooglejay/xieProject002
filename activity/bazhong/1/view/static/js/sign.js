/**
 * Created by sooglejay on 17/11/16.
 */

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

function submit(originCount) {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/addsign.do',
        type: 'GET',
        data: {
            sessionId: sessionId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在签到,请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            $('#myModal').modal('show');
            $("#des").html(res.msg);
            console.log(res);
            if(res.status==200){
                check();
                changeImage(originCount+1);
            }
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
        }
    });
}

function changeImage(count) {
    var i;
    for (i = 1; i <= count; i++) {
        $("#img_" + i).attr('src', '../image/p2s_1.png')
    }
    while (i <= 5) {
        $("#img_" + i).attr('src', '../image/p2s_2.png');
        i++;
    }
}
function check() {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/getInfo.do',
        type: 'GET',
        data: {
            sessionId: sessionId
        },
        dataType: 'json',
        success: function (res) {
            layer.closeAll();
            if (res.status == 200) {
                var data = res.data;
                var count = data.count;
                var status = data.status;
                changeImage(count);
                if (count >= 5) {
                    if (status == 2) {
                        $('#myModal').modal('show');
                        $("#des").html("您已累计签到5次，可点击下方按钮，领取流量哟！");
                        $('#btnSign').click(getLiuLiang).html('领取流量');
                    } else if (status == 1) {
                        $('#btnSign').attr('disabled', 'disabled').html('您已领取');
                    }
                } else {
                    $('#btnSign').click(function () {
                        submit(count);
                    });
                }
                console.log(res);
            } else {
                $('#myModal').modal('show');
                $("#des").html(res.msg);
            }
        },
        error: function (res) {
            layer.closeAll();
            console.log(res);
        }
    });
}

function getLiuLiang() {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/exchange.do',
        type: 'POST',
        data: {
            sessionId: sessionId
        },
        dataType: 'json',
        success: function (res) {
            layer.closeAll();
            $('#myModal').modal('show');
            $("#des").html(res.msg);
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
    check();
});