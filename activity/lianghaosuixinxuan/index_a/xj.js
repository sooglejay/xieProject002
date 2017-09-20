/**
 * Created by hanke0726 on 2016/7/29.
 */
$(function(){
    $(".page").css("min-height",$(window).height()+"px");

    //弹框
    $(".btn-gz").click(function(){
        $(".pop-bj,.pop-con").css("display","block");

        if($(".pop-con ul").height()>$(window).height()*0.56){
            $(".pop-con ul").css({"height":$(window).height()*0.56+"px","overflow-y":"auto","overflow-x":"hidden"});
        }else {
        }
        $(".pop-con").css("top",parseInt(($(window).height()-$(".pop-con").height())/2)+"px");
    });

    $(".pop-btn").click(function(){
        $(".pop-bj,.pop-con").css("display","none");
    });

    downUpList($(".quxian"),$(".select_magistrate"),$(".select_magistrate ul li"));
    $(".select_magistrate").css("margin-top",-($(".select_magistrate").height()/2)+"px");

    $("#tj").click(function(){
        var name = $("#name").val();
        var num = $("#num").val();
        var phone = $("#phone").val();
        var qx = $("#qx").attr("value");
        if (name == "") {
            alert("请填写本人身份证姓名！");
            $("#name").focus();
            return false;
        }
        if (num == "") {
            alert("请填写本人身份证号码！");
            $("#num").focus();
            return false;
        }
        if (phone == "") {
            alert("请填写您的联系电话！");
            $("#phone").focus();
            return false;
        }
		if (qx == 0) {
            alert("请选择您的入网区县！");
            return false;
        }

        $.ajax({
            type: "post",
            url: "index.php",
            dataType: 'json',
            data: {"part": 'clxh',"openid": request('openid'), "appid":request('appid'),"phone":phone, "username":name, "usercard":num, "qx":qx, 'aphone': request('hm')},
            success: function (data) {
                if(data.errcode==0){
                    //document.location.href="?part=cx&openid="+request('openid')+"&appid="+request('appid');
                    setboxUrlM(data.result,data.url);
                } else {
                    setboxM(data.result);
                }
            }
        });

		//alert("号码预约成功！");
        //document.location.href="index.html";
    });

    $("#choujiang").click(function(){
        $.ajax({
            type: "post",
            url: "choujiang.php",
            dataType: 'json',
            ansy: false,
            data: {"part": 'choujiang'},
            success: function (data) {
               alert(data.result);
            }
        });
    })

	$("#fhzy").click(function(){
        document.location.href="index.php?part=index&openid="+request('openid')+"&appid="+request('appid');
    });

    $("#dtw_gz").click(function(){
        document.location.href="index.php?part=index&openid="+request('openid')+"&appid="+request('appid');
    })

});

function downUpList(clockNode,selectBox,selectNode){
    function stopPropagation(e){
        var e1=e||event;
        if(e1.stopPropagation){
            e1.stopPropagation()
        }else{
            e1.cancelBubble=true
        }
    }
    clockNode.click(function(e){
        stopPropagation(e);
        $(".select_bj").css("display","block");
        $("body").css("overflow","hidden");
        selectBox.css("display","block");
        $("body").bind('touchmove',function(event){
            event.preventDefault()
        })
    });
    selectNode.click(function(e){
        stopPropagation(e);
        clockNode.html($(this).text());
        clockNode.attr("value",$(this).attr("qx"));
        select_after()
    });
    $(document).click(function(){select_after()});
    function select_after(){
        $(".select_bj").css("display","none");
        $("body").css("overflow","visible");
        selectBox.css("display","none");
        $("body").unbind("touchmove")
    }}




