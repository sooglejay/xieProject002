/**
 * Created by hanke0726 on 2016/7/29.
 */
$(function () {
    $(".page").css("min-height", $(window).height() + "px");

    $(".btn-tijiao").click(function () {
        var phone   = $.trim($("#phone").val()),
            name    = $.trim($("#name").val()),
            area    = $.trim($("#area").val()),
            addr    = $.trim($("#addr").val()),
            loading = {};

        if (!box.isPhone(phone)) {
            weui.alert("请输入资阳移动手机号码!");
            return false;
        }

        if (!name) {
            weui.alert("请输入用户姓名!");
            return false;
        }

        if (!area) {
            weui.alert("请输入所在区县!");
            return false;
        }
        if (!addr) {
            weui.alert("请输入小区地址!");
            return false;
        }

        $.ajax({
            type      : "POST",
            url       : box.getUrl('luru'),
            data      : {
                "phone": phone,
                "name" : name,
                "area" : area,
                "addr" : addr
            },
            dataType  : "json",
            beforeSend: function () {
                loading = weui.loading('loading...');
            },
            complete  : function () {
                loading.hide();
            }
        }).done(function (res) {
            if (res.errcode == 0) {
                weui.alert(res.message, function () {
                    box.reload();
                });
            } else {
                weui.alert(res.message);
            }
        });

    })
});

