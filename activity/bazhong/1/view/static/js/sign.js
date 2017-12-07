/**
 * Created by sooglejay on 17/11/16.
 */

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

function submit() {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/addsign.do',
        type: 'GET',
        data: {
            sessionId: sessionId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在办理,请稍后...");
        },
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

function check() {
    var sessionId = getURLParameter('sessionId');
    $.ajax({
        url: 'http://wx.xj169.com/KASHI/bzyd/signIn/getInfo.do',
        type: 'GET',
        data: {
            sessionId: sessionId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding("正在办理,请稍后...");
        },
        success: function (res) {
            layer.closeAll();
            var count = res.count;
            var status = res.status;
            var i;
            for(i=1;i<=count;i++){
                $("#img_"+i).attr('src','../image/p2s_1.png')
            }
            while(i<=5){
                $("#img_"+i).attr('src','../image/p2s_2.png')
            }
            if(count>=1){
                if(status==2){

                }
            }

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
});