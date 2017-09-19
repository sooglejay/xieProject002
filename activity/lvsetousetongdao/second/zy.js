/**
 * Created by Administrator on 13-12-31.
 */
$(function () {
    $("#sub").click(function () {

        var khxm = $("#khxm").val();
        if (khxm == "") {
            setboxM("请输入客户姓名");
            $("#khxm").focus();
            return false;
        }

        var khdh = $("#khdh").val();
        if (khdh == "") {
            setboxM("请输入客户电话");
            $("#khdh").focus();
            return false;
        }
        var regu = /^(134|135|136|137|138|139|147|150|151|152|157|158|159|182|183|184|187|188)[0-9]{8}$/;
        //var regu = /^[0-9]{11}$/;
        var re = new RegExp(regu);
        if (!re.test(khdh)) {
            setboxM("请输入正确的四川移动手机号码");
            $("#khdh").focus();
            return false;
        }

        var lxdh = $("#lxdh").val();
        if (lxdh == "") {
            setboxM("请输入联系电话");
            $("#lxdh").focus();
            return false;
        }
        var regu = /^(134|135|136|137|138|139|147|150|151|152|157|158|159|182|183|184|187|188)[0-9]{8}$/;
        //var regu = /^[0-9]{11}$/;
        var re = new RegExp(regu);
        if (!re.test(lxdh)) {
            setboxM("请输入正确的四川移动手机号码");
            $("#lxdh").focus();
            return false;
        }

        var tsnr = $("#tsnr").val();
        if (tsnr == "") {
            setboxM("请输入投诉内容");
            $("#tsnr").focus();
            return false;
        }

        $.ajax({
            type: "post",
            url: "tousu.php",
            dataType: 'json',
            data: {"khxm": khxm, "khdh": khdh, "lxdh": lxdh, "tsnr": tsnr},
            success: function (data) {
                if (data.code == 200) {
                    box.msg("已收到您的投诉建议，请等待处理！");
                } else if (data.code == 201) {
                    box.msg(data.error);
                }
            }
        });

    });
});