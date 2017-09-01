/**
 * Created by sooglejay on 17/9/1.
 */
var _LOCATION_APP_ = function () {
    this.lng = 0;
    this.lat = 0;
    this.getLocation = function () {
        if (navigator.geolocation) {
            //浏览器支持geolocation
            navigator.geolocation.getCurrentPosition(LocationApp.onSuccess, LocationApp.onError);
        } else {
            //浏览器不支持geolocation
            alert('您的浏览器不支持地理位置定位');
        }
    };

    //成功时
    this.onSuccess = function (position) {
        //返回用户位置
        //经度
        var longitude = position.coords.longitude;
        //纬度
        var latitude = position.coords.latitude;

        // 这里后面可以写你的后续操作了
        LocationApp.lng = longitude;
        LocationApp.lat = latitude;
    };
//失败时
    this.onError = function (error) {
        switch (error.code) {
            case 1:
                alert("位置服务被拒绝");
                break;
            case 2:
                alert("暂时获取不到位置信息");
                break;
            case 3:
                alert("获取信息超时");
                break;
            case 4:
                alert("未知错误");
                break;
        }
        // 这里后面可以写你的后续操作了
        //经度
        LocationApp.lng = 23.1823780000;
        //纬度
        LocationApp.lat = 113.4233310000;
    }
};
var LocationApp = new _LOCATION_APP_();

// 页面载入时请求获取当前地理位置
window.onload = function () {
    // html5获取地理位置
    LocationApp.getLocation();

};