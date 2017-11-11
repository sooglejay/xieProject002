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

function doViewShop(id, isForView) {
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
                fillForm(data, isForView);
            }
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}
function fillForm(obj, isForView) {
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

    if (isForView) {
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
        $("#btn_submit").click(function () {
            window.location.href = "search.html?search=" + searchWord;
        });
    } else {
        $("#btn_submit").html("保存修改");
    }

}

$(function () {
    $(".page").css("min-height", $(window).height() + "px");
    var id = getURLParameter("id");
    if (id) {
        var flag = getURLParameter("action");
        doViewShop(id, (flag == "view"));
    } else {
        var searchWord = getURLParameter("search");
        if (searchWord) {
            $('#shop_name').val(searchWord);
            doSearch();
        }
    }
});

function doSearch() {

    var search = $.trim($('#shop_name').val());
    if (search == '') {
        box.msg('请输入需要搜索的商铺名称！');
        return false;
    }
    $.ajax({
        type: 'post',
        url: 'shop/controller/SearchApp.php',
        dataType: 'json',
        data: {
            search: search,
            openId:getURLParameter('openId')
        },
        beforeSend: function () {
            box.loadding('正在搜索,请稍等...');
        },
        success: function (res) {
            layer.closeAll();
            var result = JSON.parse(res);
            if (result["code"] != 200) {
                box.msg(result["message"]);
                return;
            }
            var data = result["data"];
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
                    if (!owner) {
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

            // } else {
            //     box.msg('搜索失败');
            // }
        },
        error: function (e) {
            layer.closeAll();
            console.log(e);
        }
    });
}

$(function () {
    downUpList($(".se-qylb"), $(".select-hangye"), $(".select-hangye ul li"));
    downUpList($(".se-yunying"), $(".select_yunying"), $(".select_yunying ul li"));
    downUpList($(".se-ztzw"), $(".select_ztzw"), $(".select_ztzw ul li"));
    downUpList($(".se-kdfg"), $(".select_kdfg"), $(".select_kdfg ul li"));
    $(".select_magistrate").css("margin-top", -($(".select_magistrate").height() / 2) + "px");

    // 商铺信息录入
    $('#btn_submit').on('click', function () {
            var shop_name = $.trim($('#shop_name').val());
            var shop_addr = $.trim($('#shop_addr').val());
            var shop_street = $.trim($('#shop_street').val());
            var shop_contact1 = $.trim($('#shop_contact1').val());
            var shop_contact2 = $.trim($('#shop_contact2').val());
            var shop_type = $.trim($('#shop_type').val());
            var shop_280 = $.trim($('#shop_280').val());
            var shop_group_net = $.trim($('#shop_group_net').val());
            var shop_mem_num = Number($.trim($('#shop_mem_num').val()));
            var shop_209 = $.trim($('#shop_209').val());
            var shop_broadband_cover = $.trim($('#shop_broadband_cover').val());
            var shop_landline = $.trim($('#shop_landline').val());
            var shop_operator = $.trim($('#shop_operator').val());

            // 验证商铺名称不能为空
            if (shop_name == '') {
                box.msg('请输入商铺名称！');
                return false;
            }

            // 验证商铺地址不能为空
            if (shop_addr == '') {
                box.msg('请输入商铺地址！');
                return false;
            }

            // 验证商铺所在街道不能为空
            if (shop_street == '') {
                box.msg('请输入商铺所在街道名称！');
                return false;
            }

            // 验证商铺联系方式1不能为空
            if (shop_contact1 == '') {
                box.msg('请输入商铺联系方式1！');
                return false;
            }

            // 必须输入手机号码
            /*if (!/^1\d{10}$/.test(shop_contact1)) {
             box.msg('请输入正确手机号码！');
             return false;
             }*/
            if (shop_contact1.length != 11 || isNaN(shop_contact1)) {
                box.msg('商铺联系方式1的长度为11位！');
                return false;
            }

            // 如果联系方式二不为空，验证格式
            if (shop_contact2 != '') {
                if (isNaN(shop_contact2)) {
                    box.msg('请输入数字！');
                    return false;
                }
            }

            // 验证商铺类型必选
            if (shop_type == '') {
                box.msg('请选择商铺行业类别！');
                return false;
            }

            // 判断280代码为10位
            if (shop_280 != '') {
                if (shop_280.length != 10 || isNaN(shop_280)) {
                    box.msg('请输入10位数的280代码！');
                    return false;
                }
            }

            // 验证是否完成组网
            if (shop_group_net == '') {
                box.msg('请选择是否完成集团组网！');
                return false;
            }

            // 集团成员数量
            if (shop_mem_num == '') {
                box.msg('请输入集团成员数量！');
                return false;
            }

            // 判断集团数量是否为数字或正数
            if (isNaN(shop_mem_num) || shop_mem_num < 0) {
                box.msg('请输入大于或等于0的数字！');
                return false;
            }

            // 判断209代码为11位
            if (shop_209 != '') {
                if (shop_209.length != 11 || isNaN(shop_209)) {
                    box.msg('请输入11位数的209代码！');
                    return false;
                }
            }

            // 验证宽带是否覆盖
            if (shop_broadband_cover == '') {
                box.msg('请选择移动宽带资源是否覆盖！');
                return false;
            }

            // 验证是否选择运行商
            if (shop_operator == '') {
                box.msg('请选择宽带使用运营商！');
                return false;
            }

            var id = getURLParameter("id");
            var openId = getURLParameter("openId");

            $.ajax({
                type: 'POST',
                url: 'shop/controller/SaveApp.php',
                dataType: 'json',
                data: {
                    id: id ? id : "",
                    openId: openId,
                    action: (id == null ? 'save_shop' : 'edit_save_shop'),
                    shop_name: shop_name,
                    shop_addr: shop_addr,
                    shop_street: shop_street,
                    shop_contact1: shop_contact1,
                    shop_contact2: shop_contact2,
                    shop_type: shop_type,
                    shop_280: shop_280,
                    shop_group_net: shop_group_net,
                    shop_mem_num: shop_mem_num,
                    shop_209: shop_209,
                    shop_broadband_cover: shop_broadband_cover,
                    shop_landline: shop_landline,
                    shop_operator: shop_operator,
                    shop_lng: LocationApp.lng,
                    shop_lat: LocationApp.lat
                },
                beforeSend: function () {
                    box.loadding('正在添加,请稍后...');
                },
                success: function (res) {
                    layer.closeAll();
                    var isError = (res.hasOwnProperty("error"));
                    if (isError) {
                        box.msg(res.message);
                        return;
                    }

                    var openId = res['openId'];
                    var searchWord = getURLParameter("search");
                    $("#btn_submit").click(function () {
                    });

                    var textLeft = (searchWord != null ? "返回上一级" : "继续添加");
                    var textRight = "返回首页";
                    layer.open({
                        content: res.message
                        , btn: [textLeft, textRight]
                        , yes: function (index) {
                            if (searchWord != null) {
                                window.location.href = "search.html?search=" + searchWord;
                            } else {
                                window.location.reload();
                            }
                            layer.close(index);
                        },
                        no: function (index) {
                            window.location.href = "home.html?openId=" + openId;
                        }
                    });
                },
                error: function (e) {
                    layer.closeAll();
                    box.msg("添加失败，请确认输入的信息是否合法！");
                    console.log(e);
                }
            });
        }
    );


// 搜索
    $('#btn_search').on('click', doSearch);

// 查看详情
    $('#result').delegate('.btn-ckxq,.btn-ckxq-without', 'click', function () {
        var id = $(this).data('id');
        var searchWord = $(this).data('search');
        window.location.href = 'fill_info.html?id=' + id + "&action=view&search=" + searchWord;
    });

// 修改资料
    $('#result').delegate('.btn-xgzl', 'click', function () {
        var id = $(this).data('id');
        var searchWord = $(this).data('search');
        window.location.href = 'fill_info.html?id=' + id + "&action=edit&search=" + searchWord;
    });

});