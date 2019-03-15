/*! Copyright©2008-2016 ykxia.com All Rights Reserved.2017-05-09 */
function getFormatDate(date, pattern) {
    return void 0 == date && (date = new Date),
    void 0 == pattern && (pattern = "yyyy-MM-dd hh:mm:ss"),
    date.format(pattern)
}
function ref() {
   /*  var url = "/liebian/getRecord.do",
    param = zuche.uitls.getUrlParam("param");
    $.ajax({
        url: url,
        type: "post",
        data: {
            param: param
        },
        dataType: "json",
        cache: !1,
        success: function(data, textStatus) {
            var result = data.result;
            if (result && result.length > 0) {
                $(".result").show(),
                $("#rst-success").html("");
                for (var i = 0; i <= result.length - 1; i++) {
                    var date = new Date(result[i].gotTime),
                    createTime = getFormatDate(date, "MM.dd hh:mm");
                    $("#rst-success").append('<ul><li class="portrait"><img src="' + result[i].picture + '" /><li><li class="center"><span class="name">' + result[i].nick + '</span><span class="time">' + createTime + '</span><span class="language">' + result[i].shareContent + '</span></li><li class="sum">' + result[i].couponValue + "元</li>")
                }
            } else $(".friends").hide()
        }
    }) */
}
function checkTel() {
	$(".white_content").hide();
	$(".black_overlay").hide();
    var tel = $.trim($("#mobileId").val());
    return null === tel || "" === tel || 0 === tel.length || "请输入手机号" === tel ? (zuche.uitls.show("请输入手机号<span class='face face-3'></span>"), !1) : !!mobileValidate(tel) || (zuche.uitls.show("手机号填写错误<span class='face face-3'></span>"), !1)
}
Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        S: this.getMilliseconds()
    };
    /(y+)/.test(format) && (format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length)));
    for (var k in o) new RegExp("(" + k + ")").test(format) && (format = format.replace(RegExp.$1, 1 == RegExp.$1.length ? o[k] : ("00" + o[k]).substr(("" + o[k]).length)));
    return format
},
$("#capture-btn").click(function() {
	var mobile = $("#mobileId").val(),code_v = $("#code_v").val(),
	sign = $("#sign").val(),
	pid = $("#uid").val(),
	share_id = $("#share_id").val(),
    url = "";
    //param = zuche.uitls.getUrlParam("param"),
   // openId = zuche.uitls.getUrlParam("openId");
    checkTel() && (zuche.uitls.setStorage("zc_mobile", mobile), $.ajax({
        url: url,
        async: !1,
        type: "post",
        data: {
            mobile: mobile,
			code_v: code_v,
            sign: sign,
            share_id: share_id,
            pid: pid,
            promoteActivityIdZ: promoteActivityIdZ
        },
        dataType: "json",
        cache: !1,
        success: function(data, textStatus) {
			
			if('200'==data.msg){
				$("#mobilebox").hide();
				$(".succeed-wrap").show();
				$("#youkan_val").html(data.val);
				$("#youkan_type").html(data.type);
				$("#phone").html(mobile);
			}else if('40002'==data.msg){
				zuche.uitls.show("自己不能领取哦<span class='face face-3'></span>");
			}else if('40003'==data.msg){
				zuche.uitls.show("别贪心哦，明天再来领吧<span class='face face-3'></span>");
			}else if('40004'==data.msg){
				zuche.uitls.show("验证码错误<span class='face face-3'></span>");
			}else{
				alert(data.msg);
			}
            //var result = data;
            //result && (result = data.status, mobile && (mobile = mobile.substr(0, 3) + "****" + mobile.substr(7, 4)), 1 == result ? ($("#mobilebox").hide(), data.result && ($(".succeed-wrap").show(), $(".res-money").html(data.result)), $("#phone").html(mobile), ref()) : 8 == result ? (data.result && ($(".fta-wrap").show(), $(".new-res-money").html(data.result)), $("#newphone").html(mobile), ref()) : 3 == result ? ($("#mobilebox").hide(), data.result && ($(".succeed-wrap").show(), $(".res-money").html(data.result)), $("#phone").html(mobile), $("#already").show()) : 2 == result ? zuche.uitls.show("手机号不正确！<span class='face face-4'></span><br>") : 5 == result ? ($("#mobilebox").hide(), $(".late-wrap").show()) : 4 == result ? zuche.uitls.show("纳尼，领券人太多<br>请稍后或重试！<span class='face face-4'></span><br>") : 6 == result ? ($("#mobilebox").hide(), $(".over-wrap").show()) : 7 == result ? location.href = data.result: zuche.uitls.show("纳尼，领券人太多<br>请稍后或重试！<span class='face face-4'></span><br>"))
        }
    }))
}),
$(function() {
    ref()
});