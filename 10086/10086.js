/**
 * Created by sooglejay on 17/9/5.
 */


$(function () {
    $.ajax({
        type: 'POST',
        url: '10086.php',
        data: {action: "index"},
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            window.location.href = res;
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
});