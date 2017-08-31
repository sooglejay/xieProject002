/**
 * Created by sooglejay on 17/8/30.
 */

$(function () {
    getWxInfo();
});
function getWxInfo() {
    var verifyUrl = $(location).attr('href');
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
    // wxConfig({
    //     appId:"wxb76c5258ffa59386",
    //     timestamp:1504180036,
    //     nonceStr:"e2f6e861a7364492",
    //     signature:"7ed8f338d5638b86f643a982487dbc3632328cba"
    // });
}
function wxConfig(res) {
    wx.config({
        debug: true,
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