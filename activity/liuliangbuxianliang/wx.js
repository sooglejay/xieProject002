/**
 * Created by sooglejay on 17/8/30.
 */

var _LOCATION_APP_ = function () {
    this.lng = 0;
    this.lat = 0;
};
var LocationApp = new _LOCATION_APP_();

function WinXinInit() {
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false)
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady)
        }
    } else {
        onBridgeReady()
    }
    function onBridgeReady() {
        WeixinJSBridge.call('hideOptionMenu')
    }
}
$(function () {
    WinXinInit();
    getWxInfo();
});

function getWxInfo() {
    var verifyUrl = $(location).attr('href');
    $.ajax({
        type: 'GET',
        url: '/ziyan/signature.php',
        data: {url: verifyUrl},
        async: false,
        dataType: 'json',
        success: function (res) {
            var obj = -1;
            try {
                obj = JSON.parse(res);
            }
            catch (e) {
                console.log(e);
            }
            if (obj != -1) {
                wxConfig(obj);
            }
        },
        error: function (e) {
        }
    });
}

function wxConfig(res) {
    wx.config({
        debug: false,
        appId: "wxb76c5258ffa59386",
        timestamp: res.timestamp,
        nonceStr: res.noncestr,
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
            menuList: ["menuItem:share:appMessage", "menuItem:share:timeline"]    //要显示的菜单项
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: "流量不限量预约",
            desc: "资阳移动微生活",
            imgUrl: "http://wx.hankedata.com/wx_case/22/hd20170713/images/pic02.png",
            link: "http://test.sighub.com/ziyan/activity/first.html",
            success: function (res) {
                /*分享成功*/
            }
        });
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: "流量不限量预约",
            desc: "资阳移动微生活",
            imgUrl: "http://wx.hankedata.com/wx_case/22/hd20170713/images/pic02.png",
            link: "http://test.sighub.com/ziyan/activity/first.html",
            success: function (res) {
                /*分享成功*/
            }
        });
    })

}