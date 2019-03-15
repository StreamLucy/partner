<?php
//用户分享领取页面
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");

session_start();

	$link = conn();
	$time = time();

	$user_id = intval(trim($_GET['user_id']));
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

	//查询未使用的激活码
	$query = 'select yk_code from yk_cardlist where yk_uid='.$user_id.' and yk_use_id = 0 order by yk_cid desc limit 20';
	$card_res = mysql_query($query, $link);
	if(!$card_res){
		throw new AppException('400002', $query, true);
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
    <title id="pagetitle">全民代理——充值代理</title>
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
<article>
    <section class="pre-wrap">    
		<div class="pig" style="margin-top:20%">
            <ul class="ipt">
                <li><input id="count" name="count" type="text" class="verify_c" placeholder="激活" maxlength="" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/></li>
                <li><a onclick="addcard()" href="JavaScript:;"  class="sub" style="color:#dff0d8;background-color:red;">激活</a></li>
            </ul>
        </div>
    </section>
    <section class="pre-wrap">
    	<b>未使用的激活码</b>
        <ul>
        	<?php while($card = mysql_fetch_array($card_res, MYSQL_ASSOC)){ ?>
        	<li><?=$card['yk_code']?></li>
        	<?php } ?>
        </ul>
    </section>
    <section class="by-law">
        <h3><b>激活规则</b></h3>
        <ul>
			<li>激活规则</li>
        </ul>
        <br/><br/>
    </section>
</article>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
</body>
</html>