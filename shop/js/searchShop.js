/**
 * Created by sooglejay on 17/11/11.
 */
/**
 * Created by hanke0726 on 2016/7/29.
 */
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

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}

function doSearch(search) {
    if (search == undefined || search.length < 1) {
        box.msg('请输入需要搜索的商铺名称！');
        return false;
    }
    $.ajax({
        type: 'post',
        url: 'shop/controller/SearchApp.php',
        dataType: 'json',
        data: {
            search: search,
            openId: getURLParameter('openId')
        },
        beforeSend: function () {
            box.loadding('正在搜索,请稍等...');
        },
        success: function (res) {
            layer.closeAll();
            if (res["code"] != 200) {
                box.msg(res["message"]);
                return;
            }
            var data = res["data"];
            if (data == undefined || data.length < 1) {
                box.msg("没有搜索到任何有关" + search + "的结果");
                return;
            }

            var list = '';
            $.each(data, function (i, item) {
                var editText = '<a href="javascript:void (0)" data-id="' + item.id + '"  data-search="' + search + '" class="btn-xgzl">修改资料</a>';
                var classSee = "btn-ckxq";
                if (item.hasOwnProperty("owner")) {
                    var owner = item["owner"];
                    if (owner == "0") {
                        editText = '';
                        classSee = "btn-ckxq-without";
                    }
                }
                var content = '<div class="search-list">';
                content += '<h2>' + item.shop_name + '</h2>';
                content += '<ul>';
                content += '<li>企业类别：<span>' + item.shop_type + '</span></li>';
                content += '<li>商铺所在地址：<span>' + item.shop_addr + item.shop_street + '</span></li>';
                content += '</ul>';
                content += '<div class="clearfix" align="center">';
                content += '<a href="javascript:void (0)" data-id="' + item.id + '"  data-search="' + search + '" class="' + classSee + '">查看详情</a>';
                content += editText;
                content += '</div></div>';
                list += content;

            });
            $('#result').show();
            $('#result').html(list);
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}

$(function () {
    $(".page").css("min-height", $(window).height() + "px");
    var searchKeyWord = getURLParameter("search");
    if (searchKeyWord != undefined) {
        doSearch(searchKeyWord);
    }
    $('#btn_search').click(function () {
        var shop_name = $("#shop_name").val();
        doSearch(shop_name);
    });
    downUpList($(".se-qylb"), $(".select-hangye"), $(".select-hangye ul li"));
    downUpList($(".se-yunying"), $(".select_yunying"), $(".select_yunying ul li"));
    downUpList($(".se-ztzw"), $(".select_ztzw"), $(".select_ztzw ul li"));
    downUpList($(".se-kdfg"), $(".select_kdfg"), $(".select_kdfg ul li"));
    $(".select_magistrate").css("margin-top", -($(".select_magistrate").height() / 2) + "px");

    // 查看详情
    $('#result').delegate('.btn-ckxq,.btn-ckxq-without', 'click', function () {
        var id = $(this).data('id');
        var searchWord = $(this).data('search');
        window.location.href = 'viewShop.html?openId=' + getURLParameter('openId') + '&id=' + id + "&search=" + searchWord;
    });

    // 修改资料
    $('#result').delegate('.btn-xgzl', 'click', function () {
        var id = $(this).data('id');
        var searchWord = $(this).data('search');
        window.location.href = 'editShop.html?openId=' + getURLParameter('openId') + '&id=' + id + "&search=" + searchWord;
    });

});