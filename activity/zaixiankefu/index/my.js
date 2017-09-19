$(function(){

    $("#tj").click(function(){
        var name = $("#name").val();
        var phone = $("#phone").val();
		if (name == "") {
            setboxM("请输入您的姓名！");
            $("#name").focus();
            return false;
        }
        if (phone == "") {
            setboxM("请输入您的手机号！");
            $("#phone").focus();
            return false;
        }

        $.ajax({
            type: "post",
            url: "index.php",
            dataType: 'json',
            data: {"part": 'index', "tel": phone, "cname":name, "appid":request("appid"), "openid":request("openid")},
            success: function (data) {
                if(data.errcode==0){
                    setboxUrlM(data.result,data.url);
                } else {
                    setboxM(data.result);
                }
            }
        });
    });

});