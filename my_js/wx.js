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
            menuList: []    //要显示的菜单项
        })
    });

    //调用登录接口
    wx.login({
        success: function (response) {
            var code = response.code;
            wx.getUserInfo({
                success: function (resp) {
                    wx.request({
                        url: 'test.sighub.com',
                        data: {
                            code: code,
                            iv: resp.iv,
                            encryptedData: resp.encryptedData
                        },
                        success: function (res) {
                            console.log(res.data)
                        }
                    })
                }
            })
        },
        fail:function(){
        }
    })

}