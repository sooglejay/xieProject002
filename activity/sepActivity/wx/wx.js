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
    var verifyUrl = 'http://test.sighub.com/ziyan';
    $.ajax({
        type: 'GET',
        url: 'signature.php',
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
            'showOptionMenu',
            'openLocation',
            'getLocation'
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
        });
        wx.checkJsApi({
            jsApiList: [
                'getLocation'
            ],
            success: function (res) {
                // alert(JSON.stringify(res));
                // alert(JSON.stringify(res.checkResult.getLocation));
                if (res.checkResult.getLocation == false) {
                    alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                    return;
                }else{
                    wx.getLocation({
                        success: function (res) {
                            var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                            var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                            var speed = res.speed; // 速度，以米/每秒计
                            var accuracy = res.accuracy; // 位置精度
                            LocationApp.lng = longitude;
                            LocationApp.lat = latitude;
                        },
                        cancel: function (res) {
                            alert('用户拒绝授权获取地理位置');
                        }
                    });
                }
            }
        });

    });

}