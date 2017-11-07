;var box = {
    'loadding'    : function () { // 加载层
        var loadding = layer.load(1, {
            shade: [0.3, '#ccc']
        })
        return loadding
    },
    'alert'       : function (message, func) { // 提示框
        layer.closeAll()
        func = (typeof func == 'function') ? func : function(){layer.close(alert)}
        var anims = [0, 1, 2, 3, 4, 5, 6]
        var rand  = Math.floor((Math.random() * anims.length))
        var alert = layer.alert(message, {
            skin    : 'layui-layer-molv',
            closeBtn: false,
            anim    : anims[rand],
            title   : ' '
        }, func)
    },
    'msg'         : function (message, icon, func) { // 消息框
        icon = !isNaN(icon) ? icon : 1
        func = (typeof func == 'function') ? func : function () {
        }
        layer.closeAll()
        layer.msg(message, {
            time : 1500,
            icon : icon,
            shade: [0.3, '#ccc',],
        }, func)
    },
    'confirm'     : function (message, btns, func1, func2) { // 询问框
        btns  = (typeof btns == 'object') ? btns : ['确认', '取消']
        func1 = (typeof func1 == 'function') ? func1 : function () {
            layer.close(confirm)
        }
        func2 = (typeof func2 == 'function') ? func2 : function () {
            layer.close(confirm)
        }
        layer.closeAll();
        var confirm = layer.confirm(message, {
            btn     : btns,
            closeBtn: false,
            icon    : 3,
            title   : '提示'
        }, func1, func2)
    },
    'request'     : function (key) { // 获取get参数值
        var args  = new Object();
        var query = location.search.substring(1);

        var pairs = query.split("&"); // Break at ampersand
        for (var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            if (pos == -1) continue;
            var argname   = pairs[i].substring(0, pos);
            var value     = pairs[i].substring(pos + 1);
            value         = decodeURIComponent(value);
            args[argname] = value;
        }
        return args[key];
    },
    'getTimestamp': function () { // 获取当前时间戳
        return Math.round(new Date().getTime() / 1000);
    },
    'getRand'     : function () { // 获取随机数
        return Math.random();
    },
    'jump'        : function (url) { // 跳转
        window.location.href = url;
    },
    'reload'      : function () { // 刷新，重新加载
        //layer.closeAll()
        window.location.reload()
    },
    'isPhone'     : function (str) { // 验证是否为手机号码
        if (str == 'empty' || !(/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}$/).test(str)) {
            return false;
        } else {
            return true;
        }
    },
    'back' : function(){ // 返回上一级页面
        window.history.back();
    },
    'send_sms' : function(obj, code, to) { // 发送短信给指定号码
        var u = navigator.userAgent,mobile = '';
        if(u.indexOf('iPhone') > -1) mobile = 'iphone';
        if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) mobile = 'Android';
        if(mobile == 'Android'){
            $(obj).attr('href','sms:'+to+'?body=' + code);
        }
        if(mobile == 'iphone'){
            $(obj).attr('href','sms:'+to+'&body=' + code);
        }
    },
    'getUrl' : function(part, params){
        var part = part ? 'part='+part+'&' : '',
            params = params ? '&'+params : '',
            url  = '?type='+box.request('type')+'&'+part+'openid='+box.request('openid')+'&appid='+box.request('appid')+'&t='+box.getRand()+params;
        return url;
    },
    'go' : function(url){
        history.replaceState = url;
        document.location.replace(url);
    },
}


