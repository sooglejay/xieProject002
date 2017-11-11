/**
 * Created by sooglejay on 17/8/24.
 */
$(function () {
    var openId = getURLParameter("openId");
    if (openId == undefined || openId.length < 5) {
        box.msg("请在资阳微信公众号中点击图文消息进入!");
    } else {
        loadData(openId);
        $("#searchBtn").click(function () {
            window.location.href = "search.html?openId="+openId;
        });
        $("#addBtn").click(function () {
            window.location.href = "fill_info.html?openId="+openId;
        });
    }
});
function loadData(openId) {
    $.ajax({
        type: 'POST',
        url: 'shop/controller/IndexApp.php',
        data: {
            'openId': openId
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            if (res.error) {
                window.location.href = "index.html";
                return;
            }
            $('#county').text(res["county"]);
            $('#area_name').text(res["area_name"]);
            $('#grid_name').text(res["grid_name"]);
            $('#code').text(res["code"]);
            $('#selling_area_name').text(res["selling_area_name"]);
            $('#shop_num').text(res["shop_num"]);
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}
