<?php
/**
 * 调用支付
 *
 * 实现微信、支付宝支付的接口
 * @date 2017年3月13日
 * @copyright 重庆迅虎网络有限公司
 */
require_once 'api.php';
//$trade_order_id = time();//商户网站内部ID，此处time()是演示数据
$appid              = '20146125906';//测试账户，
$appsecret          = '098CBD716BDAEBE185693DB6FF1B6B20';//测试账户，
$my_plugin_id       ='my-plugins-id';


//网站处理逻辑
include("../../../../inc/config.php");
include("../../../../inc/function.php");
include("../config.php");
$time = time();
$uid        = intval(trim($_GET['uid']));//用户ID
$pid        = intval(trim($_GET['pid']));//平台ID
$token      = mysql_escape_string(trim($_GET['token']));
$auth_uid   = mysql_escape_string(trim($_GET['auth_uid']));//需要授权的用户ID
if(0==$uid || 0==$pid || ''==$token){
    echo '参数错误-1';exit(0);
}

$link = conn_pid($pid);
if(!$link){
    echo '参数错误-pid';exit(0);
}

//验证token
$query = 'select 1 from yk_userlog where yk_uid='.$uid.' and yk_token="'.$token.'" order by yk_ulid desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
     echo '查询错误-2';exit(0);
}
if(1>mysql_num_rows($res)){
     echo '请重新登录APP再打开';exit(0);
}

//检查需要授权的用户ID的真实性
$auth_uid = intval(substr($auth_uid, 5));
$query = 'select 1 from yk_user where yk_uid='.$auth_uid.' order by yk_uid desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
     echo '查询错误-3';exit(0);
}
if(1>mysql_num_rows($res)){
     echo '参数错误-4';exit(0);
}

//查看一下是否已经是合伙人了..
$query = 'select 1 from yk_agent where yk_user_id='.$auth_uid.' order by yk_agent_id desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
     echo '查询错误-5';exit(0);
}
if(0<mysql_num_rows($res)){
     echo '用户已经是合伙人了，无需再支付';exit(0);
}

//生成支付订单
$price          = $PAY_PRICE;//支付价格
$trade_order_id = '';//网站订单ID，前面两位是平台ID，后面的是真实的订单ID
if(1===strlen($pid)){
    $trade_order_id .= '0'.$pid;
}else{
    $trade_order_id .= $pid;
}
$trade_order_id .= '_'.$auth_uid;

$query = 'INSERT INTO yk_recharge_log(yk_uid, yk_price_id, yk_addtime, yk_type, yk_money, yk_orderid, yk_play_type) VALUES('.$uid.', 0, '.$time.', "2", "'.$price.'", "'.$trade_order_id.'", "2")';
$res = mysql_query($query, $link);
if(!$res){
     echo '查询错误';exit(0);
}
$yk_recharge_id = mysql_insert_id();
$trade_order_id .= '_'.$yk_recharge_id;

$query ='update yk_recharge_log set yk_orderid="'.$trade_order_id.'" where yk_recharge_id='.$yk_recharge_id.' order by yk_recharge_id desc limit 1';
$res = mysql_query($query, $link);
if(!$res){
     echo '查询错误';exit(0);
}

$return_url = 'http://agent.ykvip.net/html/coupon/agent/show.php?uid='.$uid.'&token='.$token.'&pid='.$pid.'&res=success';
$callback_url = 'http://agent.ykvip.net/html/coupon/agent/show.php?uid='.$uid.'&token='.$token.'&pid='.$pid.'&res=error';

$data=array(
    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
    'plugins'   => $my_plugin_id,//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
    'appid'     => $appid, //必须的，APPID
    'trade_order_id'=> $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
    'payment'   => 'alipay',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
    'total_fee' => $price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
    'title'     => '合伙人授权', //必须的，订单标题，长度32或以内
    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
    'notify_url'=>  'http://agent.ykvip.net/html/coupon/agent/pay/notify.php', //必须的，支付成功异步回调接口
    'return_url'=> $return_url,//必须的，支付成功后的跳转地址
    'callback_url'=>$callback_url,//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
    'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);


$hashkey =$appsecret;
$data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
/**
 * 个人支付宝/微信即时到账，支付网关：https://pay2.xunhupay.com/v2
 * 微信支付宝代收款，需提现，支付网关：https://pay.wordpressopen.com
 */
$url              = 'https://pay2.xunhupay.com/v2/payment/do.html';

try {
    $response     = XH_Payment_Api::http_post($url, json_encode($data));
    /**
     * 支付回调数据
     * @var array(
     *      order_id,//支付系统订单ID
     *      url//支付跳转地址
     *  )
     */
    $result       = $response?json_decode($response,true):null;
    if(!$result){
        throw new Exception('Internal server error',500);
    }

    $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
    if(!isset( $result['hash'])|| $hash!=$result['hash']){
        throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
    }

    if($result['errcode']!=0){
        throw new Exception($result['errmsg'],$result['errcode']);
    }


    $pay_url =$result['url'];
    header("Location: $pay_url");
    exit;
} catch (Exception $e) {
    echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
    //TODO:处理支付调用异常的情况
}
?>
