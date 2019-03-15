<?php
//用户分享领取页面
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");

session_start();

$uid    = intval(trim($_GET['uid']));//用户ID
$pid        = intval(trim($_GET['pid']));//平台ID
$token = mysql_escape_string(trim($_GET['token']));
if(0==$uid || 0==$pid || ''==$token){
    //throw new AppException('参数错误1', '', false);
    echo '参数错误1';
}

$link = conn_pid($pid);
if(!$link){
    //throw new AppException('参数错误-pid', '', false);
    echo '参数错误-pid';
}

//验证token
$query = 'select 1 from yk_userlog where yk_uid='.$uid.' and yk_token="'.$token.'" order by yk_ulid desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
    throw new AppException('400002', $query, true);
}
if(1>mysql_num_rows($res)){
    //throw new AppException('token错误请重启登录', '', false);
    echo 'token错误请重启登录';
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
    </script><style type="text/css">
    .siema-wrapper{
        height:100%;
        width:100%;
        overflow: hidden;
        text-align: center; 
    }
    .siema{
        height:100%;
        width:100%;
        padding:0;
        position:relative;
        left:0;
    }
    .siema li{
        height:100%;
        float:left;
        list-style:none;
        font-size:30px;
        color:#fff;
    }
    .siema li img {
      width: auto;
      height: 100%;
      position: relative; 
      margin:0 auto
    }
    .current-slide {
    position: absolute;
    display: inline-block;
    right: 50%;
    bottom: 0.16rem;
    font-size: 0.1rem;
    height: 0.2rem;
    line-height: 0.2rem;
    color: #fff;
    padding: 0 0.1rem;
    border-radius: 0.2rem;
    z-index: 10;
}

</style>
<style type="text/css">
.hide {
  display: none !important;
}
.pic-gallery-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  -webkit-transition: height .2s;
  transition: height .2s;
}
</style>
    <script>
        window.onload = function(){

            var moveX,      //手指滑动距离
                endX,       //手指停止滑动时X轴坐标
                cout = 0,   //滑动计数器
                moveDir;    //滑动方向
            var movebox = document.querySelector(".siema");   //滑动对象
            var Li = movebox.querySelectorAll("li");    //滑动对象item
            var width = parseInt(window.getComputedStyle(movebox.parentNode).width);    //滑动对象item的宽度
            movebox.style.width = (width*Li.length) + "px"; //设置滑动盒子width
            for(var i = 0; i < Li.length; i++){
                Li[i].style.width = width + "px";   //设置滑动item的width，适应屏幕宽度
            }
            //触摸开始
            function boxTouchStart(e){
                var touch = e.touches[0];   //获取触摸对象
                startX = touch.pageX;   //获取触摸坐标
                endX = parseInt(movebox.style.webkitTransform.replace("translateX(", ""));  //获取每次触摸时滑动对象X轴的偏移值
            }

            function boxTouchMove(e){
                var touch = e.touches[0];
                moveX = touch.pageX - startX;   //手指水平方向移动的距离
               
                if(cout == 0 && moveX > 0){     //刚开始第一次向左滑动时
                    return false;
                }

                if(cout == (Li.length-1) && moveX < 0){     //滑动到最后继续向右滑动时
                    return false;
                }

                movebox.style.webkitTransform = "translateX(" + (endX + moveX) + "px)"; //手指滑动时滑动对象随之滑动
            }

            function boxTouchEnd(e){
                moveDir = moveX < 0 ? true : false;     //滑动方向大于0表示向左滑动，小于0表示向右滑动
                //手指向左滑动
                if(moveDir){
                    var slide_cout =cout+2;
                    if (slide_cout > Li.length) slide_cout=Li.length;
                    document.getElementById("current-slide").innerHTML="第"+slide_cout+"种";
                    if(cout<(Li.length-1)){
                        movebox.style.webkitTransform = "translateX(" + (endX-width) + "px)";
                        cout++;
                    }
                //手指向右滑动
                }else{
                    var slide_cout =cout;
                    if (slide_cout < 1) slide_cout=1;
                    document.getElementById("current-slide").innerHTML="第"+slide_cout+"种";
                    //滑动到初始状态时返回false
                    if(cout == 0){
                        return false;
                    }else{
                        movebox.style.webkitTransform = "translateX(" + (endX+width) + "px)";
                        cout--;
                    }
                }
            }

            //滑动对象事件绑定
            movebox.addEventListener("touchstart", boxTouchStart, false);
            movebox.addEventListener("touchmove", boxTouchMove, false);
            movebox.addEventListener("touchend", boxTouchEnd, false);
            document.getElementById("current-slide").innerHTML="第1种";
        }
    </script>
</head>
<body style="padding-bottom: 0px !important;">    
    <section class="pre-wrap" style="min-height: 5rem;">    
        <div class="pig">
            <ul class="ipt">
                <li>
                    <a style="width: 30%;color:#dff0d8;background-color:red;" href="./image1.php?user_id=<?=$user_id?>&type=1"  class="sub">生成第一种</a>
                    <a style="width: 30%;color:#dff0d8;background-color:red;" href="./image1.php?user_id=<?=$user_id?>&type=2"  class="sub">生成第二种</a>
                    <a style="width: 30%;color:#dff0d8;background-color:red;" href="./image1.php?user_id=<?=$user_id?>&type=3"  class="sub">生成第三种</a>
                </li>
            </ul>
        </div>
    </section>
    <section class="pre-wrap">
        <div class="pic-gallery-wrapper" style="height: 495px;max-height: 100%">
			<div class="siema-wrapper">
                <ul class="siema" style="transition-duration:0.2s;transform: translateX(-0px);">
                    <li class="img-wrapper"><img src="../images/tip1.png"></li>
                    <li class="img-wrapper"><img src="../images/tip1.png"></li>
                    <li class="img-wrapper"><img src="../images/tip1.png"></li>
                </ul>
                <div class="current-slide" id="current-slide" style="color:red;"></div>
            </div>
        </div>
    </section>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
</body>
</html>