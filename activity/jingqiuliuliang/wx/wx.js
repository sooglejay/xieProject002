/**
 * Created by sooglejay on 17/8/30.
 */

var _LOCATION_APP_ = function () {
    this.lng = 0;
    this.lat = 0;
};
var LocationApp = new _LOCATION_APP_();

$(function () {
    getWxInfo();
});

function getWxInfo() {
    var verifyUrl = $(location).attr('href');
    $.ajax({
        type: 'GET',
        url: 'http://test.sighub.com/ziyan/activity/jingqiuliuliang/wx/signature.php',
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
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });
    /**
     * =============================================================================================================
     * 修改以下内容即可
     * =============================================================================================================
     */
    var wxData = {
        title: '金秋流量敞开用',
        desc: '今年套餐免费送',
        link: 'http://test.sighub.com/ziyan/activity/jingqiuliuliang/',
        imgUrl: 'http://test.sighub.com/ziyan/activity/jingqiuliuliang/img/txt.png'
    };
    /**
     * =============================================================================================================
     * 修改以上内容即可
     * =============================================================================================================
     */
    wx.ready(function () {
        // 获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
        wx.onMenuShareTimeline(wxData);
        // 获取“分享给朋友”按钮点击状态及自定义分享内容接口
        wx.onMenuShareAppMessage(wxData);
    });
}