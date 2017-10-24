function validatePhoneNumber(phoneNum) {
    var pattern = /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/;
    return pattern.test(phoneNum);
}
function validateIDCard(val) {
    var CheckNum = function (c) {
        return ((c >= '0' && c <= '9') || c == 'x' || c == 'X') ? true : false;
    };
    var len = val.length;
    if (len == 18 || len == 15) {
        for (var i = 0; i < len; i++) {
            if (!CheckNum(val.charAt(i))) {
                alert("输入的身份证号码错误，请检查！");
                return false;
            }
        }
        return true;
    }
    alert("输入的身份证号码长度应该是18位或15位，请检查！");
    return false;
}

function onSubmit() {
    var userName = $("#userName").val();
    var idCard = $("#idCard").val();
    var phoneNum = $("#phoneNumber").val();
    var address = $("#address").val();
    var area = $("input[name='optionsRadios']").val();

    if (!validatePhoneNumber(phoneNum)) {
        alert("手机号码不合法！请输入11位手机号码！");
        return;
    }
    if (!validateIDCard(idCard)) {
        return;
    }
    if (userName == null || userName.length < 1) {
        alert("请输入姓名");
        return;
    }

    if (address == null || address.length < 1) {
        alert("请输入详细联系地址");
        return;
    }
    if (area == null || area.length < 1) {
        alert("请选择区县");
        return;
    }
    console.log(area);
    $.ajax({
        type: 'POST',
        url: 'StoreAndGive.php',
        data: {
            area: area,
            userName: userName,
            address: address,
            phoneNumber: phoneNum,
            idCard: idCard
        },
        dataType: 'json',
        beforeSend: function () {
            box.loadding('加载中...');
        },
        success: function (res) {
            layer.closeAll();
            box.msg('预约成功');
        },
        error: function (e) {
            layer.closeAll();
            box.msg('预约失败！请联系系统管理员！');
            console.log(e);
        }
    });
}