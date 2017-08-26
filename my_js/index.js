/**
 * Created by sooglejay on 17/8/24.
 */
$(function () {
    loadData();
});
function loadData() {
    $.ajax({
        type: 'POST',
        url: '/ziyan/Search.php',
        data: {action: "INDEX"},
        dataType: 'json',
        beforeSend: function () {
//                box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            $('#county').text(res["county"]);
            $('#area_name').text(res["area_name"]);
            $('#grid_name').text(res["grid_name"]);
            $('#code').text(res["code"]);
            $('#selling_area_name').text(res["selling_area_name"]);
            $('#shop_num').text(res["shop_num"]);
        },
        error:function (e) {
            console.log(e);
        }
    });
}
