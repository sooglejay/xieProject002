/**
 * Created by sooglejay on 17/8/30.
 */

$(function () {
    getWxInfo();
});
function getWxInfo() {
    var verifyUrl = window.location.href.replace(window.location.hash, '');
    $.ajax({
        type: 'GET',
        url: 'signature.php',
        data: {url: encodeURIComponent(verifyUrl)},
        dataType: 'json',
        success: function (res) {
            wxConfig(res);
        },
        error: function (e) {
        }
    });
}
function wxConfig(res) {
    wx.config({
        debug: false,
        appId: res.appId,
        timestamp: res.timestamp,
        nonceStr: res.nonceStr,
        signature: res.signature,
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'hideAllNonBaseMenuItem',
            'hideMenuItems',
            'showMenuItems',
            'hideOptionMenu',
            'showOptionMenu'
        ]
    });
    /**
     * =============================================================================================================
     * 修改以下内容即可
     * =============================================================================================================
     */
    var wxData = {
        title: '资阳移动微生活',
        desc: '',
        link: "",
        imgUrl: ""
    };
    /**
     * =============================================================================================================
     * 修改以上内容即可
     * =============================================================================================================
     */
    wx.ready(function () {
        wx.hideAllNonBaseMenuItem();    //隐藏所有非基础按钮接口
        wx.showMenuItems({
            menuList: []    //要显示的菜单项
        })
    });

}