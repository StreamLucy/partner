<?php 
/**
 * 支付成功异步回调接口
 *
 * 当用户支付成功后，支付平台会把订单支付信息异步请求到本接口(最多5次)
 * 
 * @date 2017年3月13日
 * @copyright 重庆迅虎网络有限公司
 */
require_once 'api.php';


$x = '';
foreach ($_POST as $var => $val) {
    $x .= $var.'='.$val.'&';
}
file_put_contents('./t.txt', $x.PHP_EOL, FILE_APPEND);


/**
 * 回调数据
 * @var array(
 *       'trade_order_id'，商户网站订单ID
         'total_fee',订单支付金额
         'transaction_id',//支付平台订单ID
         'order_date',//支付时间
         'plugins',//自定义插件ID,与支付请求时一致
         'status'=>'OD'//订单状态，OD已支付，WP未支付
 *   )
 */

$appid              = '20146125906';//测试账户，仅支持一元内支付
$appsecret          = '098CBD716BDAEBE185693DB6FF1B6B20';//测试账户，仅支持一元内支付
$my_plugin_id       = 'my-plugins-id';

$data = $_POST;
foreach ($data as $k=>$v){
    $data[$k] = stripslashes($v);
}

if(!isset($data['hash'])||!isset($data['trade_order_id'])){
   echo 'failed';exit;
}

//自定义插件ID,请与支付请求时一致
if(isset($data['plugins'])&&$data['plugins']!=$my_plugin_id){
    echo 'failed';exit;
}

//APP SECRET
$appkey =$appsecret;
$hash =XH_Payment_Api::generate_xh_hash($data,$appkey);
if($data['hash']!=$hash){
    //签名验证失败
    echo 'failed';exit;
}

//商户订单ID
$trade_order_id =$data['trade_order_id'];

if($data['status']=='OD'){
    /************商户业务处理******************/
    //TODO:此处处理订单业务逻辑,支付平台会多次调用本接口(防止网络异常导致回调失败等情况)
    //     请避免订单被二次更新而导致业务异常！！！
    //     if(订单未处理){
    //         处理订单....
    //      }

    //处理开始
    include("../../../../inc/config.php");
    include("../../../../inc/function.php");
    include("../config.php");

    $time = time();

    $trade_order_id = mysql_escape_string(trim($_POST['trade_order_id']));
    $trade_order_id_tmp = explode('_', $trade_order_id);
    $pid            = intval($trade_order_id_tmp[0]);
    $auth_uid       = intval($trade_order_id_tmp[1]);
    $yk_recharge_id = intval($trade_order_id_tmp[2]);
    $transaction_id = mysql_escape_string(trim($_POST['transaction_id']));

    if(0==$pid || 0==$auth_uid || 0==$yk_recharge_id){
        exit('error-1');
    }

    $link = conn_pid($pid);
    if(!$link){
        exit('error-2');
    }
    
    //查询支付订单信息
    $query = 'select yk_uid, yk_orderid from yk_recharge_log where yk_recharge_id='.$yk_recharge_id.' order by yk_recharge_id desc limit 1';
    $res = mysql_query($query, $link);
    if(!$res){
         exit('error-3');
    }
    if(1>mysql_num_rows($res)){
        exit('error-4');
    }
    $yk_recharge_log = mysql_fetch_array($res, MYSQL_ASSOC);
    if($yk_recharge_log['yk_orderid']!=$trade_order_id){
        exit('error-5');
    }

    mysql_query("BEGIN");
    //授权用户合伙人身份
    $query = 'INSERT INTO yk_agent(yk_pid, yk_user_id, yk_addtime) VALUES('.$yk_recharge_log['yk_uid'].', '.$auth_uid.', '.$time.')';
    $res = mysql_query($query, $link);
    if(!$res){
        mysql_query("FALLBACK");
        exit('error-6');
    }

    //修改订单状态
    $query = 'update yk_recharge_log set yk_transtime='.$time.', yk_result="1", yk_transid="'.$transaction_id.'" where yk_recharge_id='.$yk_recharge_id.' order by yk_recharge_id desc limit 1';
    $res = mysql_query($query, $link);
    if(!$res){
        mysql_query("FALLBACK");
        exit('error-7');
    }

    //给父级合伙人添加记录
    $query = 'update yk_agent set yk_agent_num=yk_agent_num+1, yk_all_money=yk_all_money+'.$AGENT_PROFIT.' where yk_user_id='.$yk_recharge_log['yk_uid'].' limit 1';
    $res = mysql_query($query, $link);
    if(!$res){
        mysql_query("FALLBACK");
        exit('error-8');
    }

    mysql_query("COMMIT");
    
    //....
    //...
    /*************商户业务处理 END*****************/
}else{
    //处理未支付的情况    
}

//以下是处理成功后输出，当支付平台接收到此消息后，将不再重复回调当前接口
echo 'success';
exit;
?>