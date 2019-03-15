<?php
//用户分享领取页面
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");

session_start();

	$link = conn();
	$time = time();

	$user_id = intval(trim($_GET['user_id']));
	$type = intval(trim($_GET['type']));
	$user_id = 1;
	if(0==$user_id){
		throw new AppException('40000', '', false);
	}
	//查询参数的真实性
	$query = 'select yk_nickname,yk_mobile,yk_headimgurl from yk_user where yk_uid='.$user_id.' order by yk_uid desc limit 1';
	$res = mysql_query($query, $link);
	if(!$res){
		throw new AppException('400002', $query, true);
	}
	$user = mysql_fetch_array($res, MYSQL_ASSOC);

	include("../../../phpqrcode/phpqrcode.php");	
	if ($type == 1 || $type == 2 || $type == 3) {
		$code_url = 'https://www.baidu.com/';
		$file = '../images/'.time().'.png';
		if(!file_exists($file)){
			$url = urldecode($code_url);
			QRcode::png($url, $file, 'H',5,2);
		}
		$data = array();
		$data['bigImgPath'] = "../images/share_img.png";
		$data['qCodePath'] = $file;
		// $data['filename'] = time().".png"; //图片不保存，直接输出到屏幕上
		$data['left'] = "280";
		$data['top'] = "1085";
		$data['percent'] = "2";
		createPromotion($data);
		@unlink($file);
	}else{
		echo "<script>alert('非法进入');window.history.back(-1);</script>";
	}

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <meta http-equiv="pragma" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="telephone=no,email=no" name="format-detection">
    <meta http-equiv="X-UA-Compatible" content="edge">
    <meta http-equiv="Expires" content="0">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="fullscreen=yes,preventMove=no" name="ML-Config">
    <title id="pagetitle">全民代理——生成宣传图</title>
    <link rel="stylesheet" type="text/css" href="../css/style@fe108c767e.css"/>
    <link rel="stylesheet" type="text/css" href="../css/dialog@ee6fc1d32d.css"/>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../css/swiper.css" rel="stylesheet" type="text/css">
    <link href="../css/app.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/jquery-2.1.4.min@f9c7afd057.js"></script>
    <script type="text/javascript" src="../js/core@13264292f3.js"></script>
    <script type="text/javascript" src="../js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
    var flag =zuche.uitls.getUrlParam("sharefrom");
    var promoteActivityIdZ;
    var origin = flag;
        // getWeixinShareData();
    </script>
</head>
<body style="padding-bottom: 0px !important;">
	
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
</body>
</html>