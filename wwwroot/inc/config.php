<?php
//公共配置文件
$img_cdn_url = 'http://imgwx.3-ye.cn';//cdn图片域名
$apk_cdn_str_replace = 'http://apk.cdn.com/';
$apk_cdn_url = 'http://w2.3-ye.cn/';

$HTTP_HOST = $_SERVER['HTTP_HOST'];

//数据库连接参数
$DBHOST = 'localhost';
$DBUSER = 'ykxia';
$DBPWD = 'xxxxxx';
$DBNAME = 'ykxia';

$PRODUCT_NAME = '优看侠';//产品名称
$PLATFORM_ID = '1'; //用户注册来源于哪个产品
$WWW_DOMIN = 'ykxia.jump.tips';
$WWW_DOMIN_NEW = 'ykxia.jump.tips';
$WEIXIN = '优看侠';
$WEIXIN_IMG = $img_cdn_url.'/images/common/ykxia_weixin_img_dingye.jpg';
$DOWN_WWW_APP_URL = 'http://ykxia.jump.tips';
$SHARE_IMG = $img_cdn_url.'/img/ykxia/ykxia_logo_300_300.png';
$yk_platform_id = '1';
$activity_web_bgimg = $img_cdn_url.'/img/ykxia/www-down.png'; //网页活动背景图片
$ios_down_url = 'https://itunes.apple.com/cn/app/id1239021279'; //iOS下载地址
$invitation_code = ''; //yyBOX邀请码

//优看侠-母数据库连接--备用
function conn_ykxia_black(){
	$DBHOST_ykxia = 'localhost';
	$DBUSER_ykxia = 'ykxia';
	$DBPWD_ykxia = 'xxxxxx';
	$DBNAME_ykxia = 'ykxia';
	
	$link_ykxia = mysql_connect($DBHOST_ykxia,$DBUSER_ykxia,$DBPWD_ykxia) or die("could not connect:".mysql_error());
	mysql_select_db($DBNAME_ykxia) or die("could not select database");
	mysql_query("SET NAMES 'utf8'",$link_ykxia);
	return $link_ykxia;
}

//返回完整的访问URL
function curPageURL(){
	$pageURL = 'http';

	if ($_SERVER["HTTPS"] == "on"){
		$pageURL .= "s";
	}
	$pageURL .= "://";

	if($_SERVER["SERVER_PORT"] != "80"){
		$pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	}else{
		$pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//官网关闭后记录
function www_close_jilu(){
	$link = conn_ykxia_black();

	$yk_domain = $_SERVER['HTTP_HOST'];
	$yk_yk_refere = $_SERVER['HTTP_REFERER'];
	$yk_url = curPageURL();
	$yk_time = time();
	$query = 'INSERT into yk_close_record(yk_domain, yk_url, yk_yk_refere, yk_time) VALUES("'.$yk_domain.'", "'.$yk_url.'", "'.$yk_yk_refere.'", "'.$yk_time.'")';
	mysql_query($query, $link);
}

if(strpos($HTTP_HOST, 'ykxia.com')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);
}

//UU伴侣
if(strpos($HTTP_HOST, 'floworth.com')!==false || strpos($HTTP_HOST, 'www.floworth.com')!==false || strpos($HTTP_HOST, 'a.floworth.com')!==false || strpos($HTTP_HOST, 'www.floworth.cn')!==false || strpos($HTTP_HOST, 'src.www.floworth.com')!==false){
	//edit by 200180331
	//if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
	www_close_jilu();
		echo "Error. <br />This website is close.";
		exit(0);
		//header("Location: http://d.uubanlv8.com/");
	//}
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'uubanlv';

	$PRODUCT_NAME = 'UU伴侣';//产品名称
	$PLATFORM_ID = '2';
	$CONFIG_ID = '3';
	$WWW_DOMIN = 'www.floworth.com';
	$WEIXIN = 'UU伴侣';
	$WEIXIN_IMG = $img_cdn_url.'/img/uubanlv/uuliulanqi_code.png';
	$DOWN_WWW_APP_URL = 'http://d.uubanlv8.com/';
	$SHARE_IMG = $img_cdn_url.'/img/uubanlv/uu_logo_300.png';
	$yk_platform_id = '2'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/uubanlv/uu-bg-img_1.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1258666458'; //ios 下载地址
	$app_down_img_logo = $img_cdn_url.'/img/uubanlv/logo2.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/uubanlv/1_1.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3
}

//看易看
if(strpos($HTTP_HOST, 'cn2mc.com')!==false || strpos($HTTP_HOST, 'kyk.cn2mc.com')!==false || strpos($HTTP_HOST, 'kyka.cn2mc.com')!==false){
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'kanyikan';

	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://kyk.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}

	$PRODUCT_NAME = '看易看';//产品名称
	$PLATFORM_ID = '3';
	$CONFIG_ID = '2';
	$WWW_DOMIN = 'www.cn2mc.com';
	$WEIXIN = '看易看';
	$WEIXIN_IMG = $img_cdn_url.'/img/kanyikan/kyk_weixin_img.jpg';
	$DOWN_WWW_APP_URL = 'http://kyk.atlink.vip/';
	$SHARE_IMG = $img_cdn_url.'/img/kanyikan/kyk_logo_300_300.png';
	$yk_platform_id = '3'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/kanyikan/kyk-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
	$app_down_img_logo = $img_cdn_url.'/img/kanyikan/logo_kyk.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/kanyikan/1_kyk.png'; //APP下载轮播图片1
	$app_down_img_2 = $img_cdn_url.'/img/kanyikan/2.png'; //APP下载轮播图片2
	$app_down_img_3 = $img_cdn_url.'/img/kanyikan/3_kyk.png'; //APP下载轮播图片3
	$invitation_code = '38383939';
	$ios_down_name = 'yyBox';//IOS下载的APP名称
}

//爱视TV
if(strpos($HTTP_HOST, 'gangwan1314.com')!==false || strpos($HTTP_HOST, 'as.wifidt.cn')!==false || strpos($HTTP_HOST, 'asa.wifidt.cn')!==false || strpos($HTTP_HOST, 'assrc0315.wifidt.cn')!==false){
	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=爱视TV&p2=as.atlink.vip");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	//edit by 200180331
	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://as.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://as.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=爱视TV&p2=as.atlink.vip");
		exit(0);
	}
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'aishitv';

	$PRODUCT_NAME = '爱视TV';//产品名称
	$PLATFORM_ID = '4';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'as.wifidt.cn';
	$WEIXIN = '爱视TV';
	$WEIXIN_IMG =  $img_cdn_url.'/img/aishi/aishitv_weixin_img.jpg';
	$DOWN_WWW_APP_URL = 'http://as.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/aishi/aishitv_logo_300_300.png';
	$yk_platform_id = '4'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/aishi/aishitv-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
	$app_down_img_logo = $img_cdn_url.'/img/aishi/logo_aishitv.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/aishi/1_aishitv.png'; //APP下载轮播图片1
	$app_down_img_2 = $img_cdn_url.'/img/aishi/2.png'; //APP下载轮播图片2
	$app_down_img_3 = $img_cdn_url.'/img/aishi/3_aishitv.png'; //APP下载轮播图片3
	$invitation_code = '38383939';
	$ios_down_name = 'yyBox';//IOS下载的APP名称
}


//百亿看
if(strpos($HTTP_HOST, 'byka.wifidt.cn')!==false || strpos($HTTP_HOST, 'byk.wifidt.cn')!==false || strpos($HTTP_HOST, 'src.byk.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://byk.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://byk.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=百亿看&p2=byk.atlink.vip");
		exit(0);
	}

	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'baiyikan';

	$PRODUCT_NAME = '百亿看';//产品名称
	$PLATFORM_ID = '5';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'byk.wifidt.cn';
	$WEIXIN = '百亿看';
	$WEIXIN_IMG = $img_cdn_url.'/img/baiyikan/weixingongzhonghao-baiyikan.jpg';
	$DOWN_WWW_APP_URL = 'http://byk.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/baiyikan/baiyikan_logo_120.png';
	$yk_platform_id = '5'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/baiyikan/baiyikan-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1276484008'; //iOS下载地址
	$app_down_img_logo = $img_cdn_url.'/img/baiyikan/logo_baiyikan.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/baiyikan/1_1_baiyikan.png'; //APP下载轮播图片1
	$app_down_img_2 = $img_cdn_url.'/img/baiyikan/2.png'; //APP下载轮播图片2
	$app_down_img_3 = $img_cdn_url.'/img/baiyikan/3_1_baiyikan.png'; //APP下载轮播图片3
}

//VV视界
if(strpos($HTTP_HOST, 'vsja.wifidt.cn')!==false || strpos($HTTP_HOST, 'vsj.wifidt.cn')!==false || strpos($HTTP_HOST, 'vsj.wifidt.cn')!==false || strpos($HTTP_HOST, 'src.vsj.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://vsj.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	//if(strpos($_SERVER['PHP_SELF'], 'share')==false && strpos($_SERVER['PHP_SELF'], 'tools')==false){
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://vsj.atlink.vip/");
		header("Location: /html/show/notice/index.php?p1=VV视界&p2=vsj.atlink.vip");
		exit(0);
	}
	
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'vvshijie';

	$PRODUCT_NAME = 'VV视界';//产品名称
	$PLATFORM_ID = '6';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'vsj.wifidt.cn';
	$WEIXIN = 'VV视界';
	$WEIXIN_IMG = $img_cdn_url.'/img/vvshijie/qrcode_for_vshijie.jpg';
	$DOWN_WWW_APP_URL = 'http://vsj.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/vvshijie/vvsj-bg-img.png';
	$yk_platform_id = '6'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/vvshijie/vvsj-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1280930979'; //iOS下载地址
	$app_down_img_logo = $img_cdn_url.'/img/vvshijie/logo2.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/vvshijie/1.png'; //APP下载轮播图片1
	$app_down_img_2 = $img_cdn_url.'/img/vvshijie/2.png'; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3
}

//咕咕侠--作废了
if(strpos($HTTP_HOST, 'ggxa.wifidt.cn')!==false || strpos($HTTP_HOST, 'ggx.wifidt.cn')!==false){
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'guguxia';

	$PRODUCT_NAME = '咕咕侠';//产品名称
	$PLATFORM_ID = '7';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'ggx.wifidt.cn';
	$WEIXIN = '咕咕侠';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://ggxa.wifidt.cn/html/d/uu/?id=1';
	$SHARE_IMG = 'http://ggxa.wifidt.cn/html/a/images/baiyikan-1024.png';
	$yk_platform_id = '7'; //产品ID
	$activity_web_bgimg = 'http://ggxa.wifidt.cn/html/a/images/baiyikan-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1276484008'; //iOS下载地址
	$app_down_img_logo = 'http://ggxa.wifidt.cn/html/d/uu/images/logo_baiyikan.png';//下载页面logo
	$app_down_img_1 = 'http://ggxa.wifidt.cn/html/d/uu/images/1_1_baiyikan.png'; //APP下载轮播图片1
	$app_down_img_2 = 'http://ggxa.wifidt.cn/html/d/uu/images/2.png'; //APP下载轮播图片2
	$app_down_img_3 = 'http://ggxa.wifidt.cn/html/d/uu/images/3_1_baiyikan.png'; //APP下载轮播图片3
}

//随便看
if(strpos($HTTP_HOST, 'sbka.wifidt.cn')!==false || strpos($HTTP_HOST, 'sbk.wifidt.cn')!==false || strpos($HTTP_HOST, 'src.sbk.wifidt.cn')!==false){
	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=随便看&p2=sbk.atlink.vip");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://sbk.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://sbk.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=随便播&p2=sbk.atlink.vip");
		exit(0);
	}
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'suibiankan';

	$PRODUCT_NAME = '随便看';//产品名称
	$PLATFORM_ID = '8';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'sbk.wifidt.cn';
	$WEIXIN = '随便看';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://sbk.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/suibianbo/suibianbo-bg-img.png';
	$yk_platform_id = '8'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/suibianbo/suibianbo-bg-img.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
	$app_down_img_logo = $img_cdn_url.'/img/suibianbo/logo-sbk.png';//下载页面logo
	$app_down_img_1 = $img_cdn_url.'/img/suibianbo/img_1_sbk.png'; //APP下载轮播图片1
	$app_down_img_2 = $img_cdn_url.'/img/suibianbo/2.png'; //APP下载轮播图片2
	$app_down_img_3 = $img_cdn_url.'/img/suibianbo/img_2_sbk.png'; //APP下载轮播图片3
	$invitation_code = '1380818';
	$ios_down_name = 'yyBox';//IOS下载的APP名称
}

//悟空精灵--66视界
if(strpos($HTTP_HOST, 'wukonga.wifidt.cn')!==false || strpos($HTTP_HOST, '66a.wlan8.cn')!==false || strpos($HTTP_HOST, '66.wlan8.cn')!==false || strpos($HTTP_HOST, '66src0315.wlan8.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://66.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://66.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=66视界&p2=66.atlink.vip");
		exit(0);
	}
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'wukongjingling';

	$PRODUCT_NAME = '66视界';//产品名称
	$PLATFORM_ID = '9';
	$CONFIG_ID = '1';
	$WWW_DOMIN = '66.wlan8.cn';
	$WEIXIN = '66视界';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://66.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/66shijie/bg-66shijie.png';
	$yk_platform_id = '9'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/66shijie/bg-66shijie.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1331475104'; //iOS下载地址
	$app_down_img_logo =  $img_cdn_url.'/img/66shijie/icon_300.png';//下载页面logo
	$app_down_img_1 =  $img_cdn_url.'/img/66shijie/66shijie_1.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3

	$invitation_code = '668668';
	$ios_down_name = 'Qbird';//APP名称
}

//动感地带--看视界
if(strpos($HTTP_HOST, 'src.ksja.ggxia.cn')!==false || strpos($HTTP_HOST, 'ksja.ggxia.cn')!==false || strpos($HTTP_HOST, 'src.dgdda.ggxia.cn')!==false || strpos($HTTP_HOST, 'dgdda.ggxia.cn')!==false || strpos($HTTP_HOST, 'ksj.wifidt.cn')!==false || strpos($HTTP_HOST, 'ksj.ggxia.cn')!==false || strpos($HTTP_HOST, 'ksjsrc0315.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://ksj.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://ksj.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=看视界&p2=ksj.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'donggandidai';

    $PRODUCT_NAME = '看视界';//产品名称
    $PLATFORM_ID = '10';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'ksj.ggxia.cn';
    $WEIXIN = '看视界';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://ksj.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/kanshijie/kanshijie-bg-1.png';
    $yk_platform_id = '10'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/kanshijie/kanshijie-bg-1.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/kanshijie/logo-kanshijie.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/kanshijie/img_1_dgdd.jpg'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '168888';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
}

//富家百视
if(strpos($HTTP_HOST, 'src.fjbsa.wifidt.cn')!==false || strpos($HTTP_HOST, 'fjbsa.wifidt.cn')!==false || strpos($HTTP_HOST, 'fjbs.wifidt.cn')!==false || strpos($HTTP_HOST, 'fjbssrc0315.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://fjbs.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://fjbs.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=富家百视&p2=fjbs.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'fujiabaishi';

    $PRODUCT_NAME = '富家百视';//产品名称
    $PLATFORM_ID = '11';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'fjbs.wifidt.cn';
    $WEIXIN = '富家百视';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://fjbs.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/fujiabaishi/fjbs-bg-img.png';
    $yk_platform_id = '11'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/fujiabaishi/fjbs-bg-img.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/fujiabaishi/logo-fjbs.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/fujiabaishi/img_1_fjbs.png'; //APP下载轮播图片1
    $app_down_img_2 = $img_cdn_url.'/img/fujiabaishi/img_2_fjbs.png'; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '1680018';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
}

//新视界-15
if(strpos($HTTP_HOST, 'src.xsja.wifidt.cn')!==false || strpos($HTTP_HOST, 'xsja.wifidt.cn')!==false || strpos($HTTP_HOST, 'xsj.wifidt.cn')!==false){

	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=新视界&p2=xsj.atlink.vip");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://xsj.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://xsj.atlink.vip");
		www_close_jilu();
		header("Location: /html/show/notice/index.php?p1=新视界&p2=xsj.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'xinshijie';

    $PRODUCT_NAME = '新视界';//产品名称
    $PLATFORM_ID = '15';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'xsj.wifidt.cn';
    $WEIXIN = '新视界';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://xsj.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/xinshijie/xinshijie-bg-1.png';
    $yk_platform_id = '15'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/xinshijie/xinshijie-bg-1.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/xinshijie/xsj-logo-300.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/xinshijie/img_1_xsj.png'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '1236869';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
}

//小青果-16
if(strpos($HTTP_HOST, 'src.xqga.wifidt.cn')!==false || strpos($HTTP_HOST, 'xqga.wifidt.cn')!==false || strpos($HTTP_HOST, 'xqg.wifidt.cn')!==false){
	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=小青果&p2=xqg.atlink.vip");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://xqg.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://xqg.atlink.vip/");
		header("Location: /html/show/notice/index.php?p1=小青果&p2=xqg.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'xiaoqingguo';

    $PRODUCT_NAME = '小青果';//产品名称
    $PLATFORM_ID = '16';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'xqg.wifidt.cn';
    $WEIXIN = '小青果';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://xqg.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/xiaoqingguo/bg.png';
    $yk_platform_id = '16'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/xiaoqingguo/bg.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/xiaoqingguo/logo-xqg.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/xiaoqingguo/img_1_xqg.jpg'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '138168';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
    //$www_weixin_img_url = 'http://xqg.wifidt.cn/images/xiaoqingguo_weixin.png';
    $www_weixin_img_url = '';
}

//微影-17
if(strpos($HTTP_HOST, 'src.wya.wifidt.cn')!==false || strpos($HTTP_HOST, 'wya.wifidt.cn')!==false || strpos($HTTP_HOST, 'wy.wifidt.cn')!==false || strpos($HTTP_HOST, 'wysrc0315.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://wy.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://wy.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=微影&p2=wy.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'weiying';

    $PRODUCT_NAME = '微影';//产品名称
    $PLATFORM_ID = '17';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'wy.wifidt.cn';
    $WEIXIN = '微影';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://wy.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/weiying/bg-weiying-1.png';
    $yk_platform_id = '17'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/weiying/bg-weiying-1.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/weiying/logo-weiyin.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/weiying/img_1_weiying.jpg'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '1301300';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
    //$www_weixin_img_url = 'http://xqg.wifidt.cn/images/xiaoqingguo_weixin.png';
    $www_weixin_img_url = '';
}


//趣看-18
if(strpos($HTTP_HOST, 'src.qukana.wifidt.cn')!==false || strpos($HTTP_HOST, 'qukana.wifidt.cn')!==false || strpos($HTTP_HOST, 'qukan.wifidt.cn')!==false || strpos($HTTP_HOST, 'qksrc0315.wifidt.cn')!==false){
	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://qukan.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://qukan.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=趣看&p2=qukan.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'qukan';

    $PRODUCT_NAME = '趣看';//产品名称
    $PLATFORM_ID = '18';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'qukan.wifidt.cn';
    $WEIXIN = '趣看';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://qukan.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/qukan/qukan-bg-img.png';
    $yk_platform_id = '19'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/qukan/qukan-bg-img.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/qukan/iconqk_300.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/qukan/img_1_qukan.jpg'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '18186868';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
    //$www_weixin_img_url = 'http://xqg.wifidt.cn/images/xiaoqingguo_weixin.png';
    $www_weixin_img_url = '';
}

//看荐-19
if(strpos($HTTP_HOST, 'src.kja.wifidt.cn')!==false || strpos($HTTP_HOST, 'kja.wifidt.cn')!==false || strpos($HTTP_HOST, 'kj.wifidt.cn')!==false || strpos($HTTP_HOST, 'kjsrc0315.wifidt.cn')!==false){
	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=看荐&p2=kj.atlink.vip");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://kj.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://kj.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=看荐&p2=kj.atlink.vip");
		exit(0);
	}
    //数据库连接参数
    $DBHOST = 'localhost';
    $DBUSER = 'ykxia';
    $DBPWD = 'xxxxxx';
    $DBNAME = 'kanjian';

    $PRODUCT_NAME = '看荐';//产品名称
    $PLATFORM_ID = '19';
    $CONFIG_ID = '1';
    $WWW_DOMIN = 'kj.wifidt.cn';
    $WEIXIN = '看荐';
    $WEIXIN_IMG = '';
    $DOWN_WWW_APP_URL = 'http://kj.atlink.vip';
    $SHARE_IMG = $img_cdn_url.'/img/kanjian/kanjian-bg-img.png';
    $yk_platform_id = '19'; //产品ID
    $activity_web_bgimg = $img_cdn_url.'/img/kanjian/kanjian-bg-img.png'; //网页活动背景图片
    $ios_down_url = 'https://itunes.apple.com/cn/app/id1313680793'; //iOS下载地址
    $app_down_img_logo = $img_cdn_url.'/img/kanjian/icon_300.png';//下载页面logo
    $app_down_img_1 = $img_cdn_url.'/img/kanjian/img_1_kanjian.jpg'; //APP下载轮播图片1
    $app_down_img_2 = ''; //APP下载轮播图片2
    $app_down_img_3 = ''; //APP下载轮播图片3
    $invitation_code = '08081818';
    $ios_down_name = 'yyBox';//IOS下载的APP名称
    //$www_weixin_img_url = 'http://xqg.wifidt.cn/images/xiaoqingguo_weixin.png';
    $www_weixin_img_url = '';
}

//嘿卡
if(strpos($HTTP_HOST, 'heika.lanrenqi.com')!==false || strpos($HTTP_HOST, 'heicardsrc.pwlink.cn')!==false){
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'heika';

	www_close_jilu();
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!=false){
		header("Location: /html/show/notice/index.php?p1=嘿卡&p2=heika.jump.tips");
		exit(0);
	}else{
		header("Location: /html/show/error/index.html");exit(0);
	}
	exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://heika.jump.tips/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://kj.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=嘿卡&p2=heika.jump.tips");
		exit(0);
	}

	$PRODUCT_NAME = '嘿卡';//产品名称
	$PLATFORM_ID = '20';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'heika.lanrenqi.com';
	$WEIXIN = '嘿卡';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://heika.lanrenqi.com/html/d/uu/?id=1';
	$SHARE_IMG = $img_cdn_url.'/img/heika/heica_300.png';
	$yk_platform_id = '9'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/heika/heica_bg.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1331475104'; //iOS下载地址
	$app_down_img_logo =  $img_cdn_url.'/img/heika/heica_300.png';//下载页面logo
	$app_down_img_1 =  $img_cdn_url.'/img/heika/heika_1.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3

	$invitation_code = '1819';
	$ios_down_name = 'Qbird';//APP名称
}

//大视界
if(strpos($HTTP_HOST, 'dashijie.lanrenqi.com')!==false || strpos($HTTP_HOST, 'dsjiesrc.pwlink.cn')!==false){
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'dashijie';

	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://dashijie.jump.tips/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://kj.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=大视界&p2=dashijie.jump.tips");
		exit(0);
	}

	$PRODUCT_NAME = '大视界';//产品名称
	$PLATFORM_ID = '21';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'dashijie.lanrenqi.com';
	$WEIXIN = '大视界';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://dashijie.lanrenqi.com/html/d/uu/?id=1';
	$SHARE_IMG = $img_cdn_url.'/img/dashijie/logo-108.png';
	$yk_platform_id = '9'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/dashijie/dashijie-bg.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1331475104'; //iOS下载地址
	$app_down_img_logo =  $img_cdn_url.'/img/dashijie/logo-108.png';//下载页面logo
	$app_down_img_1 =  $img_cdn_url.'/img/dashijie/dashijie-bg.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3

	$invitation_code = '2829';
	$ios_down_name = 'Qbird';//APP名称
}

//任视优
if(strpos($HTTP_HOST, 'rsy.4003771.com')!==false){
	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'renshiyou';

	//2018-05-17 停止访问
	www_close_jilu();
	header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')!=false){
		echo '<br/><br/><h1 align="center">查码地址已经更换为：<br/><br/>
		http://rsy.atlink.vip/tools/check/
		</h1>';
		exit(0);
	}
	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://kj.atlink.vip");
		header("Location: /html/show/notice/index.php?p1=任视优&p2=rsy.atlink.vip");
		exit(0);
	}

	$PRODUCT_NAME = '任视优';//产品名称
	$PLATFORM_ID = '22';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'rsy.4003771.com';
	$WEIXIN = '任视优';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://rsy.4003771.com/html/d/uu/?id=1';
	$SHARE_IMG = $img_cdn_url.'/img/renshiyou/logo-108.png';
	$yk_platform_id = '9'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/renshiyou/renshiyou-bg.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1331475104'; //iOS下载地址
	$app_down_img_logo =  $img_cdn_url.'/img/renshiyou/logo-108.png';//下载页面logo
	$app_down_img_1 =  $img_cdn_url.'/img/renshiyou/renshiyou-bg.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3

	$invitation_code = '3038';
	$ios_down_name = 'Qbird';//APP名称
}

//G视界
if(strpos($HTTP_HOST, 'gsj.wifidt.cn')!==false || strpos($HTTP_HOST, 'gsja.xiaouhuiju.com')!==false){
	//edit by 200180331

	//2018-05-17 停止访问
	www_close_jilu();
	if(strpos($_GET['sign'], 'ebdb1edb3c7d1ef27dd96a596a8ee910')!==false){
		header("Location: http://gsj.atlink.vip/html/a/www/index.php?sign=ebdb1edb3c7d1ef27dd96a596a8ee910");exit(0);
	}
	header("Location: /html/show/error/index.html");exit(0);
	//header("Location: /html/show/error/index.html");exit(0);

	if(strpos($_SERVER['PHP_SELF'], 'tools')==false){
		//header("Location: http://gsj.atlink.vip");exit(0);
		header("Location: /html/show/notice/index.php?p1=G视界&p2=gsj.atlink.vip");
	}

	//数据库连接参数
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD = 'xxxxxx';
	$DBNAME = 'gshijie';

	$PRODUCT_NAME = 'G视界';//产品名称
	$PLATFORM_ID = '23';
	$CONFIG_ID = '1';
	$WWW_DOMIN = 'gsj.atlink.vip';
	$WEIXIN = 'G视界';
	$WEIXIN_IMG = '';
	$DOWN_WWW_APP_URL = 'http://gsj.atlink.vip';
	$SHARE_IMG = $img_cdn_url.'/img/gshijie/logo.png';
	$yk_platform_id = '9'; //产品ID
	$activity_web_bgimg = $img_cdn_url.'/img/gshijie/gshijie-bg.png'; //网页活动背景图片
	$ios_down_url = 'https://itunes.apple.com/cn/app/id1331475104'; //iOS下载地址
	$app_down_img_logo =  $img_cdn_url.'/img/gshijie/logo.png';//下载页面logo
	$app_down_img_1 =  $img_cdn_url.'/img/gshijie/gshijie-bg.png'; //APP下载轮播图片1
	$app_down_img_2 = ''; //APP下载轮播图片2
	$app_down_img_3 = ''; //APP下载轮播图片3

	$invitation_code = '5868';
	$ios_down_name = 'Qbird';//APP名称
}

if(strpos($HTTP_HOST, 'yybox.wifidt.cn')!==false){
	echo 'yyBox';exit(0);
}

if(strpos($HTTP_HOST, 'qb.wifidt.cn')!==false){
	echo 'Qbird';exit(0);
}

$AppID = 'wx6a4016bbdffd82cc'; //微信公众号接口
$AppSecret = 'f226a4d6293251cc907cd585fc0db4af';
$get_access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppID.'&secret='.$AppSecret;

$DONGLE = 'yoyo'; //加密狗字符

//分享领取
//分享者--
$SHARE_FROM = '86400'; //分享者奖励1天

$SHARE_TO_ID = '1';//领取者奖励1天/记录的是卡类型
$SHARE_TO_TYPE = '1';//类型
$SHARE_TO_VAL = '1';//值

//新用专享卡券
$NEWUSER_GIVE = '3'; //赠送卡券类型


?>
