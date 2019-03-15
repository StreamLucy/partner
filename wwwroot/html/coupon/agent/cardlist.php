<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 8:53
 */
//用户已生成激活码记录
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");

session_start();

$uid   = intval(trim($_GET['uid']));//用户ID
$pid   = intval(trim($_GET['pid']));//平台ID
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

//查询用户全部激活码
$query = 'select yk_code from yk_cardlist where yk_adminid = '.$uid;
$res = mysql_query($query, $link);
if(!$res){
    echo "<script>alert('请生成激活码以后再来查看！')</script>";
}

$cardlist = mysql_fetch_all($res, MYSQL_ASSOC);

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
    <title id="pagetitle">激活码记录</title>
    <link rel="stylesheet" type="text/css" href="../css/style@fe108c767e.css"/>
    <link rel="stylesheet" type="text/css" href="../css/dialog@ee6fc1d32d.css"/>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../css/swiper.css" rel="stylesheet" type="text/css">
    <link href="../css/app.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/jquery-2.1.4.min@f9c7afd057.js"></script>
    <script type="text/javascript" src="../js/core@13264292f3.js"></script>
    <script type="text/javascript" src="../js/jweixin-1.0.0.js"></script>

</head>
<body style="padding-bottom: 0px !important;">
<article>
    <section class="by-law">
        <div class="add_card">
            <ul class="ipt">
                <textarea id="cardlist" style="" rows="10" cols="40"><?php foreach($cardlist as $key => $val){echo $val[0]; ?>&#13;<?php }?>
                </textarea>
            </ul>
        </div>
    </section>
    <section class="by-law">
        <h3><b>激活码生成说明</b></h3>
        <ul>
            <li>一次最多可以生成1000个激活码；</li>
            <li>激活码生成后自己保存好（重要）；</li>
            <li>生成的激活码都是1年 VIP 的激活码；</li>
            <li>激活码有效期为1年；</li>
        </ul>
        <br/><br/>
    </section>
</article>
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script type="text/javascript">
</script>
</body>
</html>
