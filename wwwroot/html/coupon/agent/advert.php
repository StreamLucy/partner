<?php
//用户分享领取页面
header("Content-Type: text/html; charset=UTF-8");
include("../../../inc/config.php");
include("../../../inc/function.php");
include("./config.php");

session_start();

$time = time();

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

	//查询当前用户代理的轮播图
	$query = 'select * from yk_carousel_ad where yk_uid='.$uid.'';
	$res = mysql_query($query, $link);
	if(!$res){
		echo '查询失败';exit(0);
	}
    $ad_num = mysql_num_rows($res);
    //查询全部的轮播图信息
    if(0 < $ad_num){
        $yk_carousel = mysql_fetch_all($res, MYSQL_ASSOC);
    }
/*	if(0<$ad_num){
		$yk_carousel = mysql_fetch_array($res, MYSQL_ASSOC);
	 }*/


	if ($_POST['submit']){
        $ad_id = trim($_REQUEST['ad_id']);//接收要修改的yk_carousel_ad_id
		$click_url = mysql_escape_string(trim($_POST['url']));
		if(''==$click_url){
			echo "<script>alert('广告点击URL不能为空');javascript:history.go(-1);location.reload()</script>";exit();
		}
		if('http://'!=substr($click_url, 0, 7) && 'https://'!=substr($click_url, 0, 8)){
			echo "<script>alert('请输入合法的http开头是链接');javascript:history.go(-1);location.reload()</script>";exit();
		}
		$type = $_FILES['file']['type'];
		$type = explode('/',$type);
		$filetype = array('jpg','jpeg','png');   /*  ['jpg', 'jpeg', 'png'];   5.4*/
		if (!in_array($type[1], $filetype)) {
			echo "<script>alert('上传图片格式有误，支持jpg，jpeg，png');javascript:history.go(-1);location.reload()</script>";exit();
		}else{
	  		if ($_FILES["file"]["error"] > 0)
	    	{
	    		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";//获取文件返回错误
	    	}
	  		else
	    	{
				//打印文件信息
	    		// echo "Upload: " . $_FILES["file"]["name"] . "<br />";//获取文件名
	    		// echo "Type: " . $_FILES["file"]["type"] . "<br />";//获取文件类型
	    		// echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";//获取文件大小
	    		// echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";//获取文件临时地址
				
				//自定义文件名称
				$array=$_FILES["file"]["type"];
				$array=explode("/",$array);
				$newfilename=$time;//自定义文件名（测试的时候中文名会操作失败）
				$file_name = date('Ymd').'_'.$uid.".".$array[1];
				$_FILES["file"]["name"]=$file_name;
				
				if (!is_dir("./upload"))//当路径不存在
				{
					mkdir("./upload");//创建路径
				}
				$url="./upload/";//记录路径
	    		if (file_exists($url.$_FILES["file"]["name"]))//当文件存在
	      		{
	      			//echo $_FILES["file"]["name"] . " already exists. ";
	      		}
	    		else//当文件不存在
	      		{
					$url=$url.$_FILES["file"]["name"];
	      			move_uploaded_file($_FILES["file"]["tmp_name"],$url);
	      			//echo "Stored in: " . $url;
	      		}//var_dump($yk_carousel);die;

	      		//插入数据库
	      		$yk_img_url = 'http://'.$_SERVER['HTTP_HOST'].'/html/coupon/agent/upload/'.$file_name;
	      		if(isset($yk_carousel) && 0 <= $ad_num && $ad_num < 3 ){//轮播图可以最多上传3个
	      		    $query = 'INSERT INTO yk_carousel_ad (yk_title, yk_img_url, yk_url, yk_sort, yk_addtime, yk_begintime, yk_endtime, yk_device, yk_uid, yk_status) VALUES("合伙人UID：'.$uid.'", "'.$yk_img_url.'", "'.$click_url.'", 0, "'.$time.'", "'.$time.'", "'.$time.'", "0", '.$uid.', "2")';
	      		}elseif($ad_num == 3){
                    $query = 'update yk_carousel_ad set yk_img_url="'.$yk_img_url.'", yk_url="'.$click_url.'", yk_status="2" where yk_carousel_ad_id='.$ad_id;
	      		}else{
                    $query = 'INSERT INTO yk_carousel_ad (yk_title, yk_img_url, yk_url, yk_sort, yk_addtime, yk_begintime, yk_endtime, yk_device, yk_uid, yk_status) VALUES("合伙人UID：'.$uid.'", "'.$yk_img_url.'", "'.$click_url.'", 0, "'.$time.'", "'.$time.'", "'.$time.'", "0", '.$uid.', "2")';
                }
				$res = mysql_query($query, $link);
                //echo "<pre>";print_r(mysql_affected_rows());echo "<pre/>";die;
				if(!$res){
					throw new AppException('添加轮播图片失败1', $query, true);
				}
				if(!mysql_affected_rows() && mysql_affected_rows() != 0){
					throw new AppException('添加轮播图片失败2', $query, true);
				}
/*                if(1 <= $ad_num && $ad_num <= 3 && mysql_affected_rows() == 0){//print_r(123);die;
                    echo "<script>alert('请不要更新相同图片和地址');javascript:history.go(-1);location.reload()</script>";exit();
                }elseif(mysql_affected_rows() == 1){
                    echo "<script>alert('更新图片成功，请等待后台审核，加速审核请加微信：".$SHENHE_WEIXIN."';javascript:history.go(-1);location.reload())</script>";exit();
                }elseif(!mysql_affected_rows()){
                    throw new AppException('添加轮播图片失败2', $query, true);
                }*/
	    	}
	 	}
	 	echo "<script>alert('上传图片成功，请等待后台审核，加速审核请加微信：".$SHENHE_WEIXIN."')</script>";
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
    <title id="pagetitle">广告投放</title>
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
    <style>
		input{
			display: inline-block;
		    width: 100%;
		    height: 3rem;
		    line-height: 3rem;
		    border: none;
		    text-align: center;
		    border-radius: .7rem;
		    font-size: 1.2rem;
		}
		#preview{
			width: auto;  
		    height: auto;  
		    max-width: 100%;  
		    max-height: 100%; 
		}
	</style>
</head>
<body style="padding-bottom: 0px !important;">
<div id="fade" class="black_overlay"> 
</div> 
<article>
    <section class="pre-wrap">
    	<?php
    	if(isset($yk_carousel)){
    	    if(isset($yk_carousel[0])){
    	        ?>
                &nbsp;&nbsp;&nbsp;&nbsp;广告状态：<?='1'==$yk_carousel[0]['yk_status']?'正常投放':('2'==$yk_carousel[0]['yk_status']?'审核中':'审核未通过，询问原因请加微信：'.$SHENHE_WEIXIN)?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;广告点击URL：<?=$yk_carousel[0]['yk_url']?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;轮播广告图片：<br/>
                <img src="<?=$yk_carousel[0]['yk_img_url']?>">
                <form action="" method="post" enctype="multipart/form-data" style="padding-top:10%">
                    <input type="hidden" id="ad_id" name="ad_id" value="<?php echo $yk_carousel[0]['yk_carousel_ad_id'];?>">
                    <input name="url" type="text" class="verify_c" placeholder="请输入广告点击URL" style="border:1px solid #828282" />
                    <label for="file" style="padding-top:10%">请选择您要上传广告的图片（尺寸：750*290）：</label>
                    <input type="file" name="file" id="file" onchange="javascript:setImagePreview();"  style="margin-top:5%" >
                    <div id="localImag"><img id="preview" width=-1 height=-1 style="display:none" /></div>
                    <br>
                    <input type="submit" name="submit" value="上传" style="color:#dff0d8;background-color:red;"/><br/><br/>
                    <input type="button" name="" onclick="window.location.href='./index.php?uid=<?= $uid ?>&token=<?= $token ?>&pid=<?= $pid ?>'" value="首页" style="color:#dff0d8;background-color:red;"/>
                </form>
                <?php
            }
            if(isset($yk_carousel[1])){
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;广告状态：<?='1' == $yk_carousel[1]['yk_status'] ? '正常投放' : ('2' == $yk_carousel[1]['yk_status'] ? '审核中' : '审核未通过，询问原因请加微信：' . $SHENHE_WEIXIN)?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;广告点击URL：<?=$yk_carousel[1]['yk_url']?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;轮播广告图片：<br/>
                <img src="<?=$yk_carousel[1]['yk_img_url']?>">
                <form action="" method="post" enctype="multipart/form-data" style="padding-top:10%">
                    <input type="hidden" id="ad_id" name="ad_id" value="<?php echo $yk_carousel[1]['yk_carousel_ad_id'];?>">
                    <input name="url" type="text" class="verify_c" placeholder="请输入广告点击URL" style="border:1px solid #828282"/>
                    <label for="file" style="padding-top:10%">请选择您要上传广告的图片（尺寸：750*290）：</label>
                    <input type="file" name="file" id="file" onchange="javascript:setImagePreview();" style="margin-top:5%">
                    <div id="localImag"><img id="preview" width=-1 height=-1 style="display:none"/></div>
                    <br>
                    <input type="submit" name="submit" value="上传" style="color:#dff0d8;background-color:red;"/><br/><br/>
                    <input type="button" name="" onclick="window.location.href='./index.php?uid=<?= $uid ?>&token=<?= $token ?>&pid=<?= $pid ?>'" value="首页" style="color:#dff0d8;background-color:red;"/>
                </form>
                <?php
            }
            if(isset($yk_carousel[2])){
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;广告状态：<?='1' == $yk_carousel[2]['yk_status'] ? '正常投放' : ('2' == $yk_carousel[2]['yk_status'] ? '审核中' : '审核未通过，询问原因请加微信：' . $SHENHE_WEIXIN)?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;广告点击URL：<?=$yk_carousel[2]['yk_url']?><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;轮播广告图片：<br/>
                <img src="<?=$yk_carousel[2]['yk_img_url']?>">
                <form action="" method="post" enctype="multipart/form-data" style="padding-top:10%">
                    <input type="hidden" id="ad_id" name="ad_id" value="<?php echo $yk_carousel[2]['yk_carousel_ad_id'];?>">
                    <input name="url" type="text" class="verify_c" placeholder="请输入广告点击URL" style="border:1px solid #828282"/>
                    <label for="file" style="padding-top:10%">请选择您要上传广告的图片（尺寸：750*290）：</label>
                    <input type="file" name="file" id="file" onchange="javascript:setImagePreview();" style="margin-top:5%">
                    <div id="localImag"><img id="preview" width=-1 height=-1 style="display:none"/></div>
                    <br>
                    <input type="submit" name="submit" value="上传" style="color:#dff0d8;background-color:red;"/><br/><br/>
                    <input type="button" name="" onclick="window.location.href='./index.php?uid=<?= $uid ?>&token=<?= $token ?>&pid=<?= $pid ?>'" value="首页" style="color:#dff0d8;background-color:red;"/>
                </form>
                <?php
            }
        }
        if($yk_carousel == null){
            ?>
            <form action="" method="post" enctype="multipart/form-data" style="padding-top:10%">
                <input name="url" type="text" class="verify_c" placeholder="请输入广告点击URL" style="border:1px solid #828282" />
                <label for="file" style="padding-top:10%">请选择您要上传广告的图片（尺寸：750*290）：</label>
                <input type="file" name="file" id="file" onchange="javascript:setImagePreview();"  style="margin-top:5%" >
                <div id="localImag"><img id="preview" width=-1 height=-1 style="display:none" /></div>
                <br>
                <input type="submit" name="submit" value="上传" style="color:#dff0d8;background-color:red;"/><br/><br/>
                <input type="button" name="" onclick="window.location.href='./index.php?uid=<?=$uid?>&token=<?=$token?>&pid=<?=$pid?>'" value="首页" style="color:#dff0d8;background-color:red;"/>
            </form>
            <?php
        }
        ?>
    </section>
    <section class="by-law">
        <h3><b>广告投放说明</b></h3>
        <ul>
            <li>合伙人都有权限修改首页轮播图片以及链接的权限；</li>
            <li>上传的图片禁止黄、赌、毒，一经发现直接取消合伙人权限（非常重要）；</li>
            <li>使用你激活码的用户都能看到你更换的图片以及链接；</li>
            <li>重新上传将会覆盖原来的图片以及链接；</li>
        </ul>
        <br/><br/>
    </section>
</article>
<!-- <script type="text/javascript" src="../js/mobile_validate@8a40fc9165.js"></script> -->
<script type="text/javascript" src="../js/get@b6bc631aad.js"></script>
<script type="text/javascript" src="../js/origin@82944a8e91.js"></script>
<script>
function setImagePreview() 
{
        var docObj=document.getElementById("file");
        var str = docObj.files[0].type;
        var reg = RegExp(/image/);
        if(!str.match(reg)){
		   alert('上传图片格式有误');return false;
		}
        var imgObjPreview=document.getElementById("preview");
        if(docObj.files &&    docObj.files[0])
		{
         	//火狐下，直接设img属性
            imgObjPreview.style.display = 'block';
            // imgObjPreview.style.width = '200px';
            // imgObjPreview.style.height = '200px';                    
           //imgObjPreview.src = docObj.files[0].getAsDataURL();
      	   //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
      		imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
        }
		else
		{
			//IE下，使用滤镜
			docObj.select();
			var imgSrc = document.selection.createRange().text;
			var localImagId = document.getElementById("localImag");
			//必须设置初始大小
			localImagId.style.width = "300px";
			localImagId.style.height = "120px";
			//图片异常的捕捉，防止用户修改后缀来伪造图片
			try
			{
				localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
				localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
            }
			catch(e)
			{
				alert("您上传的图片格式不正确，请重新选择!");
				return false;
            }
				imgObjPreview.style.display = 'none';
				document.selection.empty();
         }
         return true;
 }
 </script>
</body>
</html>