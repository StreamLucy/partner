<?php
//公共函数
date_default_timezone_set('PRC');
//数据库连接
function conn(){
	global $DBHOST, $DBUSER, $DBPWD, $DBNAME;
	
	$link = mysql_connect($DBHOST,$DBUSER,$DBPWD) or die("could not connect:".mysql_error());
	mysql_select_db($DBNAME) or die("could not select database");
	mysql_query("SET NAMES 'utf8'",$link);
	return $link;
}

//优看侠-母数据库连接
function conn_ykxia(){
	$DBHOST_ykxia = 'localhost';
	$DBUSER_ykxia = 'ykxia';
	$DBPWD_ykxia = 'ykxia123';
	$DBNAME_ykxia = 'ykxia';
	
	$link_ykxia = mysql_connect($DBHOST_ykxia,$DBUSER_ykxia,$DBPWD_ykxia) or die("could not connect:".mysql_error());
	mysql_select_db($DBNAME_ykxia) or die("could not select database");
	mysql_query("SET NAMES 'utf8'",$link_ykxia);
	return $link_ykxia;
}


function conn_pid($pid){
	$DBHOST = 'localhost';
	$DBUSER = 'ykxia';
	$DBPWD	= 'ykxia123';
	switch ($pid) {
		case '1':
			$DBNAME = 'ykxia';
			break;
		case '2':
			$DBNAME = 'uubanlv';
			break;
		case '3':
			$DBNAME = 'kanyikan';
			break;
		case '4':
			$DBNAME = 'aishitv';
			break;
		case '5':
			$DBNAME = 'baiyikan';
			break;
		case '6':
			$DBNAME = 'vvshijie';
			break;
		case '8':
			$DBNAME = 'suibiankan';
			break;
		case '9':
			$DBNAME = 'wukongjingling';
			break;
		case '10':
			$DBNAME = 'donggandidai';
			break;
		case '11':
			$DBNAME = 'fujiabaishi';
			break;
		case '15':
			$DBNAME = 'xinshijie';
			break;
		case '16':
			$DBNAME = 'xiaoqingguo';
			break;
		case '17':
			$DBNAME = 'weiying';
			break;
		case '18':
			$DBNAME = 'qukan';
			break;
		case '19':
			$DBNAME = 'kanjian';
			break;
		case '20':
			$DBNAME = 'heika';
			break;
		case '21':
			$DBNAME = 'dashijie';
			break;
		case '22':
			$DBNAME = 'renshiyou';
			break;
		case '23':
			$DBNAME = 'gshijie';
			break;
		case '24':
			$DBNAME = 'vipyingshi';
			break;
		case '25':
			$DBNAME = 'yingshijie';
			break;
		case '26':
			$DBNAME = 'gongzhu';
			break;
		case '27':
			$DBNAME = 'xinshijue';
			break;
		case '28':
			$DBNAME = 'quanminyingshi';
			break;
		default:
			return false;
			break;
	}
	
	$link = mysql_connect($DBHOST,$DBUSER,$DBPWD) or die("could not connect:".mysql_error());
	mysql_select_db($DBNAME) or die("could not select database");
	mysql_query("SET NAMES 'utf8'",$link);
	return $link;
}

//异常处理函数
class AppException extends Exception{
	private $uid;
	private $sysmsg;
	private $writelog;
	
	function __construct($errmsg, $additionmsg, $writelog=false){
		parent::__construct($errmsg);
		
		$this->uid=$uid;
		$this->sysmsg=$_SERVER['REMOTE_ADDR'].': Error on '.$this->getFile().' line:'.$this->getLine().'|['.$errmsg.']['.$additionmsg.']';
		$this->writelog=$writelog;
	}
	

	public function log(){
		if($this->writelog){
			$loglink=conn();
			$query="insert into yk_errormsg (yk_uid, yk_addtime,yk_sysmsg) values ('".$this->uid."','".date("Y-m-d H:i:s")."','".mysql_escape_string($this->sysmsg)."')";
			$res=mysql_query($query,$loglink);
			if(!$res){
				echo 'add log error';
			}
			mysql_close($loglink);
		}
	}

	public function getlogmsg(){
		return $this->sysmsg;
	}
}

//mode 0:数字和字母 1:数字 2:字母
function createRandomStr($strLen, $mode){
	if(empty($mode)) $mode = 0;
	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));

	$number = '';
	$number_len = $strLen;
	if($mode==1){//数字
		$stuff = '1234567890';
	}elseif($mode==2){//字母
		$stuff = 'abcdefghijklmnopqrstuvwxyz';
	}else{
		$stuff = '1234567890abcdefghijklmnopqrstuvwxyz';//附加码显示范围ABCDEFGHIJKLMNOPQRSTUVWXYZ
	}
	$stuff_len = strlen($stuff) - 1;
	for ($i = 0; $i < $number_len; $i++) {
		$number .= substr($stuff, mt_rand(0, $stuff_len), 1);
	}
	return $number;
}

//注册用户
function adduser($mobile, $pid=0, $link){
	$query = 'INSERT INTO yk_user(yk_pid, yk_mobile, yk_regtime) VALUES('.$pid.', "'.$mobile.'", '.time().')';
	$res = mysql_query($query, $link);
	if(!$res){
		return false;
	}
	$uid = mysql_insert_id();
	return $uid;
}

//获取微信公众号参数-access_token
function get_wx_access_token($link){
	
	global $get_access_token_url;
	$time = time();
	
	$query = 'select yk_id, yk_access_token, yk_token_expiretime from yk_wx_config limit 1';
	$res = mysql_query($query, $link);
	$yk_wx_config = mysql_fetch_array($res, MYSQL_ASSOC);
	
	$access_token = $yk_wx_config['yk_access_token'];
	
	
	if($yk_wx_config['yk_token_expiretime']<$time){
		$json = @file_get_contents($get_access_token_url);
		$json = json_decode($json);
		$access_token = $json->access_token;
		$expires_in = $json->expires_in;

		if(strlen($access_token)>0){
			$yk_token_expiretime = $time + $expires_in - 60 ;
			$query = 'update yk_wx_config set yk_access_token="'.$access_token.'", yk_token_expiretime='.$yk_token_expiretime.' where yk_id='.$yk_wx_config['yk_id'];
			$res = mysql_query($query, $link);
		}
	}
	return $access_token;
}

//获取微信公众号参数-access_token
function get_wx_jsapi_ticket($access_token, $link){
	$get_jsapi_ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
	$time = time();
	
	$query = 'select yk_id, yk_jsapi_ticket, yk_ticket_expiretime from yk_wx_config limit 1';
	$res = mysql_query($query, $link);
	$yk_wx_config = mysql_fetch_array($res, MYSQL_ASSOC);
	
	$ticket = $yk_wx_config['yk_jsapi_ticket'];
	
	if($yk_wx_config['yk_ticket_expiretime']<$time){
		$json = @file_get_contents($get_jsapi_ticket_url);
		$json = json_decode($json);
		$ticket = $json->ticket;
		$expires_in = $json->expires_in;
		
		if(strlen($ticket)>0){
			$yk_ticket_expiretime = $time + $expires_in - 60 ;
			$query = 'update yk_wx_config set yk_jsapi_ticket="'.$ticket.'", yk_ticket_expiretime='.$yk_ticket_expiretime.' where yk_id='.$yk_wx_config['yk_id'];
			$res = mysql_query($query, $link);
		}
	}
	return $ticket;
}

//获取客户端IP
function get_center_ip(){
	if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
		$ip = getenv("HTTP_CLIENT_IP");
	}elseif(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}elseif(getenv("REMOTE_ADDR") &&strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
		$ip = getenv("REMOTE_ADDR");
	}elseif(isset($_SERVER['REMOTE_ADDR'])&& $_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],"unknown")){
		$ip = $_SERVER['REMOTE_ADDR'];
	}else{
		$ip = "unknown";
	}
	return $ip;
}

//注册时检测IP
function reg_check_ip($ip, $link){
	if(''==$ip || 'unknown'==$ip){
		return '200';
	}
	$tmptime = time() - 3600;
	$query = 'select yk_ip from yk_regcheckip where yk_ip="'.$ip.'" and yk_addtime>'.$tmptime;
	$res = mysql_query($query, $link);
	if(!$res || mysql_num_rows($res)>30){//1小时内注册超过20个不能注册了
		//记录非法注册IP
		$query = 'INSERT INTO yk_regcheckip (yk_ip, yk_addtime, yk_type) VALUES("'.$ip.'", '.$time.', "0")';
		$res = mysql_query($query, $link);
		if(!$res){
			throw new AppException('40000', $query, true);
		}
		return '40000';
	}
	return '200';
}

//记录统计官网referer
function referer_record($referer, $PLATFORM_ID, $PRODUCT_NAME){

	$link = conn_ykxia();
	//判断是否为空
	if(''==$referer){
		$yk_domian = '无';
	}elseif(strpos($referer, 'html/coupon/share')!==false){
		$yk_domian = '分享';
	}else{
		$url = parse_url($referer);
		$yk_domian = $url['scheme'].'://'.$url['host'];

		//对搜索引擎来的跳转全部转到404公益
		if(strpos($yk_domian, 'baidu.com')!==false || strpos($yk_domian, 'sm.cn')!==false || strpos($yk_domian, 'sogou.com')!==false || strpos($yk_domian, 'so.com')!==false || strpos($yk_domian, 'lanrenqi.com')!==false || strpos($yk_domian, 'xp510.com')!==false ||strpos($yk_domian, 'sina.cn')!==false ||strpos($yk_domian, 'pc6.com')!==false){
			header("Location: https://qzone.qq.com/404/");
			exit(0);
		}
	}

	$query = 'select yk_id from yk_referer_list where yk_domian="'.$yk_domian.'" and yk_platform_id='.$PLATFORM_ID.' limit 1';
	$res = mysql_query($query, $link);
	if(1>mysql_num_rows($res)){
		//添加一条
		$query = 'INSERT INTO yk_referer_list(yk_platform_id, yk_domian, yk_count, yk_name) VALUES('.$PLATFORM_ID.', "'.$yk_domian.'", 1, "'.$PRODUCT_NAME.'")';
		$res = mysql_query($query, $link);
		if(!$res){
			return '404';
		}
	}else{
		$yk_referer_list = mysql_fetch_array($res, MYSQL_ASSOC);
		$query = 'update yk_referer_list set yk_count=yk_count+1 where yk_id='.$yk_referer_list['yk_id'].' limit 1';
		$res = mysql_query($query, $link);
		if(!$res){
			return '404';
		}
	}

	return '200';
}

//简单判断手机号码真实性
function is_mobile($phone){
    $res = preg_match("/^1[345789]{1}\d{9}$/",$phone);
    if($res){
        return true;
    }else{
        return false;
    }
}

//mysql获取多行数据到数组
function mysql_fetch_all($result){
    $rows = array();
    while($row = mysql_fetch_array($result))
        $rows[] = $row;
    return $rows;
}
?>