/**
 * Created by sooglejay on 17/8/24.
 */
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

$(function () {
    var openId = getURLParameter("openId");
    if (openId == undefined || openId.length < 5) {
        box.msg("请在资阳微信公众号中点击图文消息进入!");
    } else {
        loadData(openId);
        $("#searchBtn").click(function () {
            window.location.href = "search.html?openId=" + openId;
        });
        $("#addBtn").click(function () {
            window.location.href = "addShop.html?openId=" + openId;
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
            if (res.code != 200) {
                box.msg(res.message);
                window.location.href = "index.html?openId=" + openId;
                return;
            }
            var result = res["data"];
            $('#county').text(result["county"]);
            $('#area_name').text(result["area_name"]);
            $('#grid_name').text(result["grid_name"]);
            $('#code').text(result["code"]);
            $('#selling_area_name').text(result["selling_area_name"]);
            $('#shop_num').text(result["shop_num"]);
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}
