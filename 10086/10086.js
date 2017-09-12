/**
 * Created by sooglejay on 17/9/5.
 */


$(function () {

   $("#btnLogin").click(function () {
       do10086();
   });
});
function do10086() {
    var userPhone = $("#userPhone").val();
    $.ajax({
        type: 'POST',
        url: '10086.php',
        data: {action: "index", userPhone: userPhone},
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
}