<?php
//用户授权
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");
include("./config.php");

session_start();

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

$yk_agent = mysql_fetch_array($res, MYSQL_ASSOC);


	if (isset($_POST['weixin'])) {
        if(''!=$yk_agent['yk_weixin']){
            //$data['msg'] = '未防止欺骗行为，修改微信号请加微信：'.$SHENHE_WEIXIN;
            $data['msg'] = '您的微信号已设定，无需更改';
            echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
        }
		$weixin = mysql_escape_string(trim($_POST['weixin']));
		if (strlen($weixin)<1) {
			$data['msg'] = '请输入微信号';
			echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
		}

		$query = 'update yk_agent set yk_weixin="'.$weixin.'" where yk_agent_id='.$yk_agent['yk_agent_id'].' order by yk_agent_id desc limit 1';
		$res = mysql_query($query, $link);

		$data['msg'] = '200';
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
    <title id="pagetitle">设置微信号</title>
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
                <li><input id="weixin" name="weixin" type="text" class="verify_c" placeholder="请输入微信号" value="<?=$yk_agent['yk_weixin']?>" /></li>
                <li><a onclick="addcard()" href="JavaScript:;"  class="sub" style="color:#dff0d8;background-color:red;">确定修改</a></li>
            </ul>
        </div>
    </section>
    <section class="by-law">    
		<div class="add_card">
        </div>
    </section>
    <section class="by-law">
        <h3><b>设置微信号说明</b></h3>
        <ul>
			<li>合伙人都有权限留自己的微信号；</li>
            <li>使用你的 VIP 激活码的用户都能看到你的微信号；</li>
            <li>该微信号谨慎填写（一经提交再次修改需要平台审核），该微信号是用来收下级合伙人加盟费用的（非常重要）；</li>
        </ul>
        <br/><br/>
    </section>
</article>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script type="text/javascript">
	function addcard() {
		var weixin=$("#weixin").val();

		if (''==weixin) {
			alert("请输入微信号或手机号码");return false;
		}

		var pay_url = './weixin.php?uid=<?=$_GET['uid']?>&token=<?=$_GET['token']?>&pid=<?=$_GET['pid']?>';
		
		$.ajax({
			type: 'post', 
			url:pay_url,
			dataType: 'json',
			data:'weixin='+weixin,
			cache : false,
			async : false,
			success: function(data){
				if(data.msg=="200"){
					alert('修改成功');
				}else{
					alert(data.msg);
				}
			},
			error:function(data){
				alert('错误');
			}
		});
	    
	}
</script>
</body>
</html>