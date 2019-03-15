<?php
//用户分享领取页面
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");

session_start();

$card_num = 1000;//一次性最大生成数量

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


	if (isset($_POST['num'])) {
		$count = intval(trim($_POST['num']));
		if ($card_num<$count || $count < 1) {
			$data['msg'] = '-1';
			echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
		}

		//小青果只能提1000个码
		if(16==$pid || 24==$pid){
			if($yk_agent['yk_card_num']==1000){
				$data['msg'] = '你已经提取了1000个激活码了，不能再提取了';
				echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
			}
			$code_sum = $yk_agent['yk_card_num'] + $count;
			if($code_sum>1000){
				$tmp_code = 1000 - $yk_agent['yk_card_num'];
				$data['msg'] = '你本次最多能提取'.$tmp_code.'个激活码';
				echo json_encode($data, JSON_UNESCAPED_UNICODE);exit(0);
			}
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
		echo json_encode($data);exit(0);
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
    <title id="pagetitle">生成激活码</title>
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
                <li><input id="count" name="count" type="text" class="verify_c" placeholder="输入生成数量(最大<?=$card_num?>)" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/></li>
                <li><a onclick="addcard()" href="JavaScript:;"  class="sub" style="color:#dff0d8;background-color:red;">生成激活码</a></li>
                <li><a onclick="copycard()" href="JavaScript:;" class="sub" style="color:#dff0d8;background-color:red;">复制激活码</a></li>
                <li><a onclick="window.location.href='./cardlist.php?uid=<?= $uid ?>&token=<?= $token ?>&pid=<?= $pid ?>'"  class="sub" style="color:#dff0d8;background-color:red;">查看激活码记录</a></li>
            </ul>
        </div>
    </section>
    <section class="by-law">    
		<div class="add_card">
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
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script type="text/javascript">
	function addcard() {
		var num=$("#count").val();
		var card_num = <?=$card_num?>;
		if (num > card_num) {
			alert("一次最多可以生成"+card_num+"个激活码");return false;
		}
		if (num < 1) {
			alert("最少要生成1个激活码");return false;
		}
	    $.ajax({
			type: 'post', 
			url:"",
			dataType: 'json',
			data:'num='+num,
			cache : false,
			async : false,
			success: function(data){
				if(data.msg=="-1"){
					alert('激活码生成数量不正确');
				}else if(data.msg=="200"){
					alert('激活码生成成功');
					var html='激活码列表（请复制到别处保存）：';
					var codelist = '';
					$.each(data.card,function (key,val){
						codelist+=val+"\r\n";
						//html+=html='<li style="margin-left:10%">'+val+'</li>';
					})
					html+=html='<textarea id="card"rows="10" cols="40">'+codelist+'</textarea>';
					
					$('.add_card').html(html);
				}else{
					alert(data.msg);
				}
			},
			error:function(data){
				alert('错误');
			}
		});
	}
    function copycard() {
        var obj = document.getElementById("card");
        obj.select();console.log(obj);//选择对象
        document.execCommand("Copy");// 执行浏览器复制命令
        alert("已成功地将内容复制到粘贴板!");
    }

</script>
</body>
</html>