<?php
//春节活动-使用过激活码的用户可以领取5个1年激活码
header("Content-Type: text/html; charset=UTF-8");
include("../../../../inc/config.php");
include("../../../../inc/function.php");

try{
	session_start();

	$time = time();

	$uid 	= intval(trim($_GET['uid']));//用户ID
	$pid 		= intval(trim($_GET['pid']));//平台ID
	$token = mysql_escape_string(trim($_GET['token']));
	if(0==$uid || 0==$pid || ''==$token){
		exit('请先登录');
	}

	//允许开放的产品
	if(1==$pid || 11==$pid || 6==$pid || 2==$pid || 27==$pid || 4==$pid){

	}else{
		exit('未开放');
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

	$action = trim($_POST['action']);
	if('getcode' == $action){

		$count = intval(trim($_POST['count']));
		if('5'==$count){
  			$yk_activity_id = 1;
  			$yk_adminid = $uid;
  			$remarks = '春节活动';
		}elseif('1'==$count){
			$yk_activity_id = 2;
			$yk_adminid = 0;
			$remarks = '春节活动奖励';
		}else{
			$json['msg'] = '参数错误';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}

		/*
		//查询用户是否使用了激活码
		$query = 'select yk_usecode from yk_user where yk_uid='.$uid.' order by yk_uid desc limit 1';
		$res = mysql_query($query, $link);
		if(!$res){
			$json['msg'] = '查询错误';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}


		$user = mysql_fetch_array($res, MYSQL_ASSOC);
		if('1'!=$user['yk_usecode']){
			$json['msg'] = '必须使用1年激活码的用户才能参与活动，详细请阅读活动规则';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}
		*/

		//查询是否已经领取过了.---临时简单的判断
		$query = 'select 1 from yk_activity_receive where yk_activity_id='.$yk_activity_id.' and yk_uid='.$uid.' order by yk_atwid desc limit 1';
		$res = mysql_query($query, $link);
		if(!$res){
			$json['msg'] = '查询错误';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}
		if(0<mysql_num_rows($res)){
			$json['msg'] = '您已经领取过了，如果遗失平台不予找回';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}

		if('2' == $yk_activity_id){
			//查询是否有资格领取另外一个奖励激活码
			$query = 'select yk_activation_count from yk_activity_receive where yk_activity_id=1 and yk_uid='.$uid.' order by yk_atwid desc limit 1';
			$res = mysql_query($query, $link);
			if(!$res){
				$json['msg'] = '查询错误';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
			}
			if(0<mysql_num_rows($res)){
				$yk_activity_receive = mysql_fetch_array($res, MYSQL_ASSOC);
				if(5!=$yk_activity_receive['yk_activation_count']){
					$count_tmp = 5 - $yk_activity_receive['yk_activation_count'];
					$json['msg'] = '您分享给好友的激活码还有'.$count_tmp.'个未使用';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
				}
			}else{
				$json['msg'] = '请先分享你领取的5个激活码并且激活使用';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
			}
		}
		

		//生成激活码
		$yk_expirationtime = strtotime('+1 year');

		mysql_query("BEGIN");
		$i = 0;
		$time = time();
		$leng = 12;//激活码长度
		$code_str = '';
		$ctid = 8;//1年的激活码
		while($i<$count){
			$yk_code = createRandomStr($leng, 1);
			$query = 'select 1 from yk_cardlist where yk_code="'.$yk_code.'" limit 1';
			$res = mysql_query($query, $link);
			if(!$res){
				mysql_query("FALLBACK");
				$json['msg'] = '查询失败';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
			}
			if(mysql_num_rows($res)){
				continue;
			}
			$query = 'INSERT INTO yk_cardlist (yk_ctid, yk_uid, yk_addtime, yk_gettime, yk_expirationtime, yk_code, yk_title, yk_remarks, yk_adminid) VALUES('.$ctid.', 0, '.$time.', '.$time.', '.$yk_expirationtime.', "'.$yk_code.'", "激活码", "'.$remarks.'", '.$yk_adminid.')';
			$res = mysql_query($query, $link);
			if(!$res){
				mysql_query("FALLBACK");
				$json['msg'] = '查询失败';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
			}
			$code_str .= $yk_code."</br>";
			$i++;
		}
		mysql_query("COMMIT");

		//添加领取记录
		$query = 'INSERT INTO yk_activity_receive(yk_activity_id, yk_uid, yk_addtime, yk_remarks) VALUES('.$yk_activity_id.', '.$uid.', '.$time.', "'.$remarks.'")';
		$res = mysql_query($query, $link);
		if(!$res){
			mysql_query("FALLBACK");
			$json['msg'] = '查询失败';
			echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
		}


		$json['msg'] = '200';
		$json['codelist'] = '激活码请自行复制保存：</br>'.$code_str;
		echo json_encode($json, JSON_UNESCAPED_UNICODE);exit(0);
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
    <title id="pagetitle">春节活动</title>
    <link rel="stylesheet" type="text/css" href="../../css/style@fe108c767e.css"/>
    <link rel="stylesheet" type="text/css" href="../../css/dialog@ee6fc1d32d.css"/>
    <script type="text/javascript" src="../../js/jquery-2.1.4.min@f9c7afd057.js">
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

.by-law ul li {
	color:#fff;
}

.anniu{ 
	padding: 10px 40px;
    background: #fff;
    color: #e61c1c;
    text-decoration: none;
    border-radius:20px;
}

</style> 
</head>
<body style="padding-bottom: 0px !important;background-color:#e61c1c;">
	<br/>
	<section class="pre-wrap" style="min-height: 10rem !important;">   
    	<img src="https://ws3.sinaimg.cn/large/005BYqpgly1fzt6aemk1zj30ku082jsl.jpg" alt="" width="100%">
    </section>
    <br/>
    <br/>
    <section class="pre-wrap" style="min-height: 7rem !important;">
    <div class="container" style="text-align: center;">
        <a onclick="getcode(5);" class="anniu">领取5个1年激活码</a><br/><br/><br/>
        <a onclick="getcode(1);" class="anniu">领取1个1年激活码</a><br/><br/>

        <span style="color:#fff;" id="code">
        	
        </span>
    </div>
    </section>
    <br/>
    <br/>
    <br/>
    <br/>
    <section class="by-law">
    	<h3 style="border-top: 0.5px solid #fff;"><b style="border-radius:12px;color:#e61c1c;">活动规则</b></h3>
    	<ul>
            <li>免费赠送价值1980元的5个1年激活码</li>
            <li>5个激活码只能赠送给5个不同的好友，不能自己使用，不能同一个好友使用</li>
            <li>5个激活码赠送给好友并使用后，您将可以领取1年的激活码，可以自己使用</li>
            <li>激活码生成后请自行保存，遗失后果自负</li>
    	</ul>
        <br/><br/>
    </section>
    <script>
    	function getcode(count){
    		var code = document.getElementById("code");
    		//code.innerHTML = '';
	    	$.ajax({
				type: 'post', 
				url:"",
				dataType: 'json',
				data:'action=getcode&count='+count,
				cache : false,
				async : false,
				success: function(data){
					if(data.msg=="200"){
						code.innerHTML = data.codelist;
						alert("请务必复制保存激活码，遗失不再找回");
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
    <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? "https://" : "http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1276108146'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s96.cnzz.com/z_stat.php%3Fid%3D1276108146' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>