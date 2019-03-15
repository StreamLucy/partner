<?php
//代理首页
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");
include("./config.php");

try{
	session_start();

	$time = time();

	$uid 	= intval(trim($_GET['uid']));//用户ID
	$pid 		= intval(trim($_GET['pid']));//平台ID
	$token = mysql_escape_string(trim($_GET['token']));
	if(0==$uid || 0==$pid || ''==$token){
		throw new AppException('参数错误1', '', false);
	}

	$link = conn_pid($pid);
	if(!$link){
		throw new AppException('参数错误-pid', '', false);
	}

	//验证token
	$query = 'select 1 from yk_userlog where yk_uid='.$uid.' and yk_token="'.$token.'" order by yk_ulid desc limit 1';
	$res = mysql_query($query, $link);
	if(!$res){
		throw new AppException('400002', $query, true);
	}
	if(1>mysql_num_rows($res)){
		throw new AppException('token错误请重启登录', '', false);
	}

	//查询参数的真实性
	$query = 'select yk_pid, yk_nickname, yk_mobile, yk_headimgurl, yk_user_name from yk_user where yk_uid='.$uid.' order by yk_uid desc limit 1';
	$res = mysql_query($query, $link);
	if(!$res){
		throw new AppException('400002', $query, true);
	}
	$user = mysql_fetch_array($res, MYSQL_ASSOC);

	$agent = array();
	$yk_agent_id = 0;
	//查询代理表里用户id为$user_id的信息
	$query = 'select * from yk_agent where yk_user_id='.$uid.' order by yk_user_id desc limit 1';
	$res = mysql_query($query, $link);
	if(!$res){
		throw new AppException('400003', $query, true);
	}
	if(0<mysql_num_rows($res)){
		$agent = mysql_fetch_array($res, MYSQL_ASSOC);
		$yk_agent_id = $agent['yk_agent_id'];
		$_SESSION['yk_agent_id']  =$yk_agent_id;
	}
	
}catch(AppException $aex){
	mysql_query("FALLBACK");
	$aex->log();
	$msg=$aex->getMessage();
}catch(Exception $ex){
	mysql_query("FALLBACK");
	$aex = new AppException('40000',$ex->getMessage(),true);
	$aex->log();
	$msg=$aex->getMessage();
}
//$yk_agent_id = 0;
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
    <title id="pagetitle">合伙人限时加盟</title>
    <link rel="stylesheet" type="text/css" href="../css/style@fe108c767e.css"/>
    <link rel="stylesheet" type="text/css" href="../css/dialog@ee6fc1d32d.css"/>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../css/swiper.css" rel="stylesheet" type="text/css">
    <link href="../css/app.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/jquery-2.1.4.min@f9c7afd057.js"></script>
    <script type="text/javascript" src="../js/core@13264292f3.js"></script>
    <script type="text/javascript" src="../js/jweixin-1.0.0.js"></script>
    <script src="http://www.jq22.com/js/jquery.min.js"></script>
    <script src="http://www.jq22.com/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    var flag =zuche.uitls.getUrlParam("sharefrom");
    var promoteActivityIdZ;
    var origin = flag;
        // getWeixinShareData();
    </script>
		<style> 
.touxiang{
	display: flex;
	overflow: hidden; 
	border-radius: 25px; 
	margin-left: 5%; 
	margin-top: 28px; 
	width: 49px; 
	height: 49px; 
	left: 0px; 
	top: 0px; 
	position: absolute;
}
.touname{
	display: flex; 
	overflow: hidden; 
	font-size: 14px; 
	color: rgb(255, 255, 255); 
	width: 200px; 
	height: auto; 
	margin-top: 25px; 
	margin-left: 20%; 
	/*font-weight: bold;*/ 
	position: absolute;
}
.touname2{
	display: flex; 
	overflow: hidden; 
	font-size: 14px; 
	color: rgb(255, 255, 255); 
	width: 200px; 
	height: auto; 
	margin-top: 45px; 
	margin-left: 20%; 
	/*font-weight: bold; */
	position: absolute;
}
.touname3{
	display: flex; 
	overflow: hidden;
	font-size: 16px; 
	color: rgb(255, 255, 255); 
	width: 30%; 
	height: auto;
	margin-top: 23px;
	margin-left: 65%; 
	position: absolute;
}
.sub{
    display: inline-block;
    width: 100%;
    line-height: 1.5rem;
    border: none;
    text-align: center;
    border-radius: .7rem;
    font-size: 1.2rem
}
.common-copy-clip {
    position: relative;
textarea {
    position: absolute;
    top: 0;
    opacity: 0;
}
</style> 
</head>
<body style="padding-bottom: 0px !important;">
<article>
	<section class="pre-wrap" style="min-height: 10rem !important;">   
    <img src="https://ws3.sinaimg.cn/large/005BYqpggy1fzaun04wkrj30ku082gnu.jpg" alt="">
    </section>
    <section class="pre-wrap" style="background-color: red;min-height: 7rem !important;">   
   <!--  <section class="pre-wrap" style="background-color: #efb336;min-height: 14rem !important;">   -->  
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-12 hy-main-content">          
		        <div class="hy-video-details clearfix">
		            <div class="item clearfix">
		                <div class="touxiang"><img src="<?=$user['yk_headimgurl']?$user['yk_headimgurl']:'../images/dlogo.png';?>" alt=""></div>
		                <div view-name="DTextView"  class="touname">
		                	<span style="text-overflow: ellipsis; overflow: hidden; line-height: 16px; white-space: nowrap;">
		                	<?php
		                	if(0==$yk_agent_id){
		                		$query = 'select yk_weixin from yk_agent where yk_user_id='.$user['yk_pid'].' limit 1';
		                		$res = mysql_query($query, $link);
		                		$p_agent = mysql_fetch_array($res, MYSQL_ASSOC);
		                		$weixin = ''!=$p_agent['yk_weixin']?$p_agent['yk_weixin']:'Wx_ChuXin';
		                		echo "<script>alert('您还不是合伙人，请加微信：".$weixin." 授权合伙人身份')</script>";
		                	}else{
		                		echo '欢迎您，合伙人';
		                		if(''==$agent['yk_weixin']){
		                			echo "<script>alert('请先设置微信号')</script>";
		                		}
		                	}
		                	?>
		                </span></div>
		                <div view-name="DTextView"  class="touname2">
                            <li>
                                <span style="line-height: 16px; white-space: nowrap;" id="uid" name="uid" >UID：80338<?=$uid?><br/></span>
                                <a onclick="copyuid()" href="JavaScript:;" class="sub" style="color:red;background-color:#f3f3f3;">复制UID</a>
                                <input id='uid1' v-model='product_url' style='opacity: 0;position: absolute;' type="text" value="80338<?php echo $uid?>">
                            </li>
                        </div>
		                <?php
		                if(0==$yk_agent_id){
		                	?>
		                <?php
		                }else{
		                	?>
		                <div view-name="DTextView" class="touname3">
		                	<span>累计收益(元)<br/><h style="font-size: 20px;margin-left:20%;"><font style="font-size: 30px;"><?=$agent['yk_all_money']?$agent['yk_all_money']:'0'?></font></h><br/><!--本月收益:+<font style="font-size: 20px;">0</font>--></span><br/>
		                </div>
		                	<?php
		                }
		                ?>
		                
		            </div>
		        </div> 
            </div>
        </div>
    </div>
    <?=$msg?>
    </section>
    <section class="pre-wrap" style="min-height: 3rem !important;">    
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-12 hy-main-content">          
		        <div class="hy-layout active clearfix">
                   
                    <div class="item clearfix" style="margin-top:10px">
                    	<dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px"><span><font style="font-size: 30px;"><?=$agent['yk_user_num']?$agent['yk_user_num']:0;?></font><br />推广用户数</span> </div>
		                    </dd>
		                </dl>
                    	<dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px"><span><font style="font-size: 30px;"><?=$agent['yk_agent_num']?$agent['yk_agent_num']:0;?></font><br />下级合伙人</span> </div>
		                    </dd>
		                </dl>
                    	<dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px"><span><font style="font-size: 30px;"><?=$agent['yk_card_num']?$agent['yk_card_num']:0;?></font><br />总提码数</span> </div>
		                    </dd>
		                </dl>
		            </div>
                </div> 
            </div>
        </div>
    </div>
    </section>
    <section class="pre-wrap" style="min-height: 14rem !important;">    
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-12 hy-main-content">          
		        <div class="hy-layout active clearfix">
                    <div class="item clearfix" style="margin-top:10px">     
                    	<dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dt><a style="position: relative;display: block;background: url('../images/cz.png')  no-repeat; background-size: cover;width: 50px;height: 50px;margin:auto;" onclick="location_url('./auth.php?uid=<?=$uid?>&token=<?=$token?>&pid=<?=$pid?>')"><span class="play hidden-xs"></span></a></dt>
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px">授权合伙人</div>
		                    </dd>
		                </dl>
		                <dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dt><a style="position: relative;display: block;background: url('../images/jhm.png')  no-repeat; background-size: cover;width: 50px;height: 50px;margin:auto;" onclick="location_url('./card.php?uid=<?=$uid?>&token=<?=$token?>&pid=<?=$pid?>')"><span class="play hidden-xs"></span></a></dt>
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px">快速提码</div>
		                    </dd>
		                </dl>
		                <dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dt><a style="position: relative;display: block;background: url('https://ws3.sinaimg.cn/large/005BYqpgly1fzalf5y235j303o03k0sm.jpg')  no-repeat; background-size: cover;width: 50px;height: 50px;margin:auto;" onclick="location_url('./weixin.php?uid=<?=$uid?>&token=<?=$token?>&pid=<?=$pid?>')"><span class="play hidden-xs"></span></a></dt>
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px">设置微信号</div>
		                    </dd>
		                </dl>
		                <dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dt><a style="position: relative;display: block;background: url('https://ws3.sinaimg.cn/large/005BYqpggy1fzaltp1oguj303k03kwec.jpg')  no-repeat; background-size: cover;width: 50px;height: 50px;margin:auto;" onclick="location_url('./advert.php?uid=<?=$uid?>&token=<?=$token?>&pid=<?=$pid?>')"><span class="play hidden-xs"></span></a></dt>
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px">广告投放</div>
		                    </dd>
		                </dl>
		                
		                <dl class="content" style="width: 33%;float: left;margin-left:0.33%;">
		                    <dt><a style="position: relative;display: block;background: url('https://ws3.sinaimg.cn/large/005BYqpggy1fzalut0emwj303k03kglg.jpg')  no-repeat; background-size: cover;width: 50px;height: 50px;margin:auto;" id="fenxiang" onclick="location_url('fenxiang')"><span class="play hidden-xs"></span></a></dt>
		                    <dd class="clearfix">
		                        <div class="head" style="text-align: center;font-size: 14px">专属海报</div>
		                    </dd>
		                </dl>
		            </div>
                </div> 
            </div>
        </div>
    </div>
    </section>
    <section class="by-law" <?='16'==$pid?'style="display:none;"':''?> <?='24'==$pid?'style="display:none;"':''?>>
    	<h3><b>合伙人权益</b></h3>
    	<ul>
    		<?php
    		if(0==$yk_agent_id){
    			echo '<li><font color="red">加微信：'.$weixin.' 加盟合伙人</font></li>';
    		}
    		?>
    		<li>影视合伙人，拥有APP专属后台；</li>
            <li>可以无限生成 VIP 激活码；</li>
            <li>修改广告、轮播图、联系方式等等所有的资料信息都可以修改；</li>
            <li>拥有发展下级合伙人的权力，享受下级合伙人加盟费75%的高额返佣；</li>
            <li>一次性费用，后续不需要任何的费用；</li>
    	</ul>
    	<h3><b>合伙人加盟条件</b></h3>
    	<ul>
    		<li>使用了1年 VIP 激活码的用户；</li>
    		<li>支付加盟费399元（原价：799元）。</li>
    	</ul>
    	<h3><b>合伙人三大收入</b></h3>
    	<ul>
    		<li>一、会员收入<br/>
    		1. 影视合伙人拥有独立后台，可以无限生成 VIP 激活码<br/>
    		2. 一张年 VIP 激活卡零售价29.9元，零售15张即可回本，因为是无限生成激活码，后续不需要任何成本，卖多少都是自己的<br/>
    		<font color="red">例如：一天卖10张VIP 激活码纯收入就是299元，一年365天纯收入就是109135元，自己数数一年的纯收入是多少。</font>
    		</li>
    		<li>二、下级合伙人返佣<br/>
    		你发展的影视 VIP 用户，花费399加盟合伙人，你可以享受75%的返佣。<br/>
    		<font color="red">例如：你的 VIP激活码用户花费399元（原价799）加盟合伙人，你可以获得300元（原价利润600元）的返佣，10个用户中只要1个用户加盟合伙人你就纯赚300元，1年365个用户加盟合伙人你就纯赚109500元。</font>
    		</li>
    		<li>三、广告收入<br/>
    		影视合伙人，可以在后台随意添加任何广告，如：淘宝客、变现猫、广告联盟、老榕树等等！<br/>
    		在这里小编脑补一下一年的保底收入：<br/>
    		<font color="red">VIP 会员卡收入109135元+109500元=218635元。这仅仅是最保守的年利润了，用户越多，合伙人就越多，收入就越多，而且都是翻倍的</font><br/>
    		还是那句话，世界上最痛苦的事情就是自己的老公，闺蜜，同事，朋友，扫了别人的二维码……做了别人的代理！
    		</li>
    	</ul>

        
        <br/><br/>
    </section>
</article>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script type="text/javascript">
	function location_url(url){
		<?php
		if('16'==$pid || '24'==$pid){
			$query = 'select yk_weixin from yk_agent where yk_user_id='.$user['yk_pid'].' limit 1';
    		$res = mysql_query($query, $link);
    		$p_agent = mysql_fetch_array($res, MYSQL_ASSOC);
    		$weixin = ''!=$p_agent['yk_weixin']?$p_agent['yk_weixin']:'Wx_ChuXin';
			echo 'if(url.indexOf("auth.php") != -1){alert("请添加微信：'.$weixin.' 授权合伙人");exit();}';
		}
		?>
		var yk_agent_id = <?=$yk_agent_id?>;
		if('fenxiang'==url){
            $("#fenxiang").bind("click", function() {
                $.MsgBox.Alert("提示", "请在APP内分享即可");
            });
            return;
		}
		if(0==yk_agent_id){
			alert("请先成为合伙人");
            return;
		}else{
			window.location.href=url;
		}
	}
	//jQuery弹出框
    (function() {
        $.MsgBox = {
            Alert: function(title, msg) {
                GenerateHtml("alert", title, msg);
                btnOk(); //alert只是弹出消息，因此没必要用到回调函数callback
                btnNo();
            },
            Confirm: function(title, msg, callback) {
                GenerateHtml("confirm", title, msg);
                btnOk(callback);
                btnNo();
            }
        }
        //生成Html
        var GenerateHtml = function(type, title, msg) {
            var _html = "";
            _html += '<div id="mb_box"></div><div id="mb_con"><span id="mb_tit">' + title + '</span>';
            _html += '<a id="mb_ico">x</a><div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';
            if (type == "alert") {
                _html += '<input id="mb_btn_ok" type="button" value="确定" />';
            }
            if (type == "confirm") {
                _html += '<input id="mb_btn_ok" type="button" value="确定" />';
                _html += '<input id="mb_btn_no" type="button" value="取消" />';
            }
            _html += '</div></div>';
            //必须先将_html添加到body，再设置Css样式
            $("body").append(_html);
            //生成Css
            GenerateCss();
        }

        //生成Css
        var GenerateCss = function() {
            $("#mb_box").css({
                width: '100%',
                height: '100%',
                zIndex: '99999',
                position: 'fixed',
                filter: 'Alpha(opacity=60)',
                backgroundColor: 'black',
                top: '0',
                left: '0',
                opacity: '0.6'
            });
            $("#mb_con").css({
                zIndex: '999999',
                width: '300px',
                position: 'fixed',
                backgroundColor: 'White',
                borderRadius: '15px'
            });
            $("#mb_tit").css({
                display: 'block',
                fontSize: '14px',
                color: '#444',
                padding: '10px 15px',
                backgroundColor: '#DDD',
                borderRadius: '15px 15px 0 0',
                borderBottom: '3px solid red',
                fontWeight: 'bold'
            });
            $("#mb_msg").css({
                padding: '20px',
                lineHeight: '20px',
                borderBottom: '1px dashed #DDD',
                fontSize: '13px'
            });
            $("#mb_ico").css({
                display: 'block',
                position: 'absolute',
                right: '10px',
                top: '9px',
                border: '1px solid Gray',
                width: '18px',
                height: '18px',
                textAlign: 'center',
                lineHeight: '16px',
                cursor: 'pointer',
                borderRadius: '12px',
                fontFamily: '微软雅黑'
            });
            $("#mb_btnbox").css({
                margin: '15px 0 10px 0',
                textAlign: 'center'
            });
            $("#mb_btn_ok,#mb_btn_no").css({
                width: '85px',
                height: '30px',
                color: 'white',
                border: 'none'
            });
            $("#mb_btn_ok").css({
                backgroundColor: 'red'
            });
            $("#mb_btn_no").css({
                backgroundColor: 'gray',
                marginLeft: '20px'
            });
            //右上角关闭按钮hover样式
            $("#mb_ico").hover(function() {
                $(this).css({
                    backgroundColor: 'Red',
                    color: 'White'
                });
            }, function() {
                $(this).css({
                    backgroundColor: '#DDD',
                    color: 'black'
                });
            });
            var _widht = document.documentElement.clientWidth; //屏幕宽
            var _height = document.documentElement.clientHeight; //屏幕高
            var boxWidth = $("#mb_con").width();
            var boxHeight = $("#mb_con").height();
            //让提示框居中
            $("#mb_con").css({
                top: (_height - boxHeight) / 2 + "px",
                left: (_widht - boxWidth) / 2 + "px"
            });
        }
        //确定按钮事件
        var btnOk = function(callback) {
            $("#mb_btn_ok").click(function() {
                $("#mb_box,#mb_con").remove();
                if (typeof(callback) == 'function') {
                    callback();
                }
            });
        }
        //取消按钮事件
/*        var btnNo = function() {
            $("#mb_btn_no,#mb_ico").click(function() {
                $("#mb_box,#mb_con").remove();
            });
        }*/
    })();

	function showDetails(animal) {
	    alert('请到app播放！');
	}
	function play_vidoe(url) {
		var id=$(".yk_share_id").val();
	    $.ajax({
			type: 'post', 
			url:"",
			dataType: 'json',
			data:'url='+url+'&id='+id,
			cache : false,
			async : false,
			success: function(data){
				if(data.msg=="-1"){
					alert('该视频的分享时间已经超过24小时，请到app观看！');
				}else if(data.msg=="200"){
					window.open(data.url);
				}
			},
			error:function(data){
				alert('错误');
			}
		});
	}
    function copyuid() {
        var input = $('#uid1');
        input.select();
        document.execCommand("Copy");
        alert("已成功地将内容复制到粘贴板!");
        /*
        var aux= document.createElement("input");
        // 获得需要复制的内容
        aux.setAttribute("value", document.getElementById("uid2").innerHTML);
        // 添加到 DOM 元素中
        document.body.appendChild(aux);
        // 执行选中
        // 注意: 只有 input 和 textarea 可以执行 select() 方法.
        aux.select();
        // 获得选中的内容
        var content = window.getSelection().string();
        //var content = content.substring(4,13);
        //alert(content);
        // 执行复制命令
        document.execCommand("copy");
        // 将 input 元素移除
        document.body.removeChild(aux);

        alert("已成功地将内容复制到粘贴板!");*//*

        var obj=document.getElementById("uid");
        //console.log('innerText cont= '+ cont.innerText);
        //console.log('innerHtml cont= '+ cont.innerHTML);
        //var obj = document.getElementById("uid").innerText;
        //var obj = document.getElementById("uid");
        obj.select();
        console.log(obj);///选择对象
        document.execCommand("Copy");// 执行浏览器复制命令
        alert("已成功地将内容复制到粘贴板!");*/
    }
</script>
<script type="text/javascript" src="https://s96.cnzz.com/z_stat.php?id=1276017891&web_id=1276017891"></script>
</body>
</html>