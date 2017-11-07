/**
 * Created by hanke0726 on 2016/7/29.
 */
$(function(){
    $(".page").css("min-height",$(window).height()+"px");
    $(".btn-main").click(function(){
        qh($(this).index());
    });
    function qh(index){
        $(".btn-con a").removeClass("btn-a");
        $(".btn-main").eq(index).addClass("btn-a");
        $(".ll-com").css("display","none");
        $(".ll-com").eq(index).css("display","block");
        if(index==3){
            $(".jrll,.djll,.mfll").css("display","block");
        }
    }
});

