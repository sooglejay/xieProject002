/**
 * Created by sooglejay on 17/11/11.
 */
/**
 * Created by hanke0726 on 2016/7/29.
 */
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}
function downUpList(clockNode, selectBox, selectNode) {
    function stopPropagation(e) {
        var e1 = e || event;
        if (e1.stopPropagation) {
            e1.stopPropagation()
        } else {
            e1.cancelBubble = true
        }
    }

    clockNode.click(function (e) {
        stopPropagation(e);
        $(".select_bj").css("display", "block");
        $("body").css("overflow", "hidden");
        selectBox.css("display", "block");
        $("body").bind('touchmove', function (event) {
            event.preventDefault()
        })
    });
    selectNode.click(function (e) {
        stopPropagation(e);
        clockNode.val($(this).text());
        select_after()
    });
    $(document).click(function () {
        select_after()
    });
    function select_after() {
        $(".select_bj").css("display", "none");
        $("body").css("overflow", "visible");
        selectBox.css("display", "none");
        $("body").unbind("touchmove")
    }
}
function doViewShop(id) {
    $.ajax({
        type: 'post',
        url: 'shop/controller/EditOrReviewApp.php',
        data: {
            id: id,
            openId: getURLParameter("openId")
        },
        beforeSend: function () {
            box.loadding('正在获取信息,请稍后...');
        },
        success: function (res) {
            var data = "";
            layer.closeAll();
            try {
                data = JSON.parse(res);
            } catch (e) {
                console.log("ERROR:" + e);
            }
            if (data != "") {
                fillForm(data);
            }
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}
function fillForm(obj) {
    $('#shop_name').val(obj['shop_name']);
    $('#shop_addr').val(obj['shop_addr']);
    $('#shop_street').val(obj['shop_street']);
    $('#shop_contact1').val(obj['shop_contact1']);
    $('#shop_contact2').val(obj['shop_contact2']);
    $('#shop_type').val(obj['shop_type']);
    $('#shop_280').val(obj['shop_280']);
    $('#shop_209').val(obj['shop_209']);
    $('#shop_group_net').val(obj['shop_group_net']);
    $('#shop_broadband_cover').val(obj['shop_broadband_cover']);
    $('#shop_operator').val(obj['shop_operator']);
    $('#shop_landline').val(obj['shop_landline']);
    $('#shop_mem_num').val(obj['shop_mem_num']);
    $('#shop_name,' +
        '#shop_addr,' +
        '#shop_street,' +
        '#shop_contact1,' +
        '#shop_contact2,' +
        '#shop_type,' +
        '#shop_280,' +
        '#shop_209,' +
        '#shop_group_net,' +
        '#shop_broadband_cover,' +
        '#shop_landline,' +
        '#shop_mem_num,' +
        '#shop_operator' +
        '').attr("disabled", "disabled");
    $("#btn_submit").html("返回");
    var searchWord = getURLParameter("search");
    $("#btn_submit").bind('click', function () {
        window.location.href = "search.html?openId=" + getURLParameter('openId') + "&search=" + searchWord;
    });
}
$(function () {
    $(".page").css("min-height", $(window).height() + "px");
    doViewShop(getURLParameter("id"));
    downUpList($(".se-qylb"), $(".select-hangye"), $(".select-hangye ul li"));
    downUpList($(".se-yunying"), $(".select_yunying"), $(".select_yunying ul li"));
    downUpList($(".se-ztzw"), $(".select_ztzw"), $(".select_ztzw ul li"));
    downUpList($(".se-kdfg"), $(".select_kdfg"), $(".select_kdfg ul li"));
    $(".select_magistrate").css("margin-top", -($(".select_magistrate").height() / 2) + "px");
});