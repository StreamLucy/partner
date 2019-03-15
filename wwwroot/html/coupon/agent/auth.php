<?php
//用户授权
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");
include("./config.php");

session_start();

$card_num = 100;//一次性最大生成数量

$uid    = intval(trim($_GET['uid']));//用户ID
$pid        = intval(trim($_GET['pid']));//平台ID
$token = mysql_escape_string(trim($_GET['token']));
if(0==$uid || 0==$pid || ''==$token){
    //throw new AppException('参数错误1', '', false);
    echo '参数错误1';exit(0);
}

$link = conn_pid($pid);
if(!$link){
    //throw new AppException('参数错误-pid', '', false);
    echo '参数错误-pid';exit(0);
}

//验证token
$query = 'select 1 from yk_userlog where yk_uid='.$uid.' and yk_token="'.$token.'" order by yk_ulid desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
    echo '查询出错';exit(0);
}
if(1>mysql_num_rows($res)){
    //throw new AppException('token错误请重启登录', '', false);
    echo 'token错误请重启登录';exit(0);
}

//查询代理表里用户id为$user_id的信息
$query = 'select * from yk_agent where yk_user_id='.$uid.' order by yk_user_id desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
	throw new AppException('400003', $query, true);
}
if(1>mysql_num_rows($res)){
	echo '请先成为代理';exit(0);
}


	if (isset($_POST['num'])) {
		$count = intval(trim($_POST['num']));
		if ($card_num<$count || $count < 1) {
			$data['msg'] = '-1';
			echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
		}

		$ctid = 8;
		$code = 1;
		$expirationtime = strtotime(date('Y-m-d H:i:s',strtotime('+1year')));
		$time = time();
	
		mysql_query("BEGIN");
		$i = 0;
		$code_str = array();
		while($i<$count){
			$yk_code = createRandomStr(12, 1);
			$query = 'select 1 from yk_cardlist where yk_code="'.$yk_code.'" limit 1';
			$res = mysql_query($query, $link);
			if(!$res){
				mysql_query("FALLBACK");
				echo json_encode(array('msg'=>'发送错误，请联系管理员'), JSON_UNESCAPED_UNICODE);exit(0);
			}
			if(mysql_num_rows($res)){
				continue;
			}
		
			//备注一下：使用  yk_adminid  这个字段记录 生成的用户ID
			$query = 'INSERT INTO yk_cardlist (yk_ctid, yk_addtime, yk_expirationtime, yk_code, yk_title, yk_adminid) VALUES('.$ctid.','.$time.', '.$expirationtime.', "'.$yk_code.'", "激活码", "'.$uid.'")';
			$res = mysql_query($query, $link);
			if(!$res){
				mysql_query("FALLBACK");
				echo json_encode(array('msg'=>'发送错误，请联系管理员'), JSON_UNESCAPED_UNICODE);exit(0);
			}
			if(!mysql_affected_rows()){
				mysql_query("FALLBACK");
				echo json_encode(array('msg'=>'发送错误，请联系管理员'), JSON_UNESCAPED_UNICODE);exit(0);
			}
			
			$code_str[$i]= $yk_code;
			$i++;
		}
		
		//代理表提码数+n
		$query = 'update yk_agent set yk_card_num=yk_card_num+'.$count.' where yk_user_id='.$uid.' order by yk_agent_id limit 1';
		$res = mysql_query($query, $link);
		if(!$res){
			mysql_query("FALLBACK");
			echo json_encode(array('msg'=>'发送错误，请联系管理员'), JSON_UNESCAPED_UNICODE);exit(0);
		}
		
		mysql_query("COMMIT");
		$data['msg'] = '200';
		$data['card'] = $code_str;
		echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
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
    <title id="pagetitle">合伙人授权</title>
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
                <li><input id="auth_uid" name="auth_uid" type="text" class="verify_c" placeholder="请输入下级用户的UID" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/></li>
                <li><a onclick="addcard()" href="JavaScript:;"  class="sub" style="color:#dff0d8;background-color:red;">合伙人授权（￥<?=$PAY_PRICE?>）</a></li>
            </ul>
        </div>
    </section>
    <section class="by-law">    
		<div class="add_card">
        </div>
    </section>
    <section class="by-law">
        <h3><b>合伙人介绍</b></h3>
        <ul>
            <li>合伙人都有权限发展下级合伙人。</li>
            <li>申请你的下级合伙人必须是使用你的 VIP 激活码的用户，没使用激活码的用户是没有权限申请下级合伙人的。</li>
            <li>你的 VIP 激活用户申请合伙人，该用户的合伙人加盟费用直接通过微信或者支付宝的方式付款给你，并把影视会员账号的UID给你。（该用户的页面能看到你的联系方式，通过线下给你转账。）</li>
            <li>你收到该 VIP 用户的399元（原价799元）加盟费用后，把该用户的UID填入上方，点击合伙人授权并支付99元（原价199元）给平台方，支付成功后你的线下合伙人即开通成功。</li>
            <li><font color="red">注：颠覆传统的钱先到平台达到一定额度再提现的分佣机制，所有的钱都是先到合伙人手上再充值给后台，再也不用担心平台各种借口不能体现了。<font></li>
        </ul>
        <br/><br/>
    </section>
</article>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script type="text/javascript">
	function addcard() {
		var auth_uid=$("#auth_uid").val();

		if (''==auth_uid) {
			alert("请输入需要授权的用户的UID");return false;
		}

		var pay_url = './pay/pay.php?uid=<?=$_GET['uid']?>&token=<?=$_GET['token']?>&pid=<?=$_GET['pid']?>&auth_uid='+auth_uid;
		window.location.href=pay_url;
	    
	}
</script>
</body>
</html>