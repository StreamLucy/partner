<?php
//合伙人相关的配置文件
$PAY_PRICE 		= '99';//授权价格，支付价格
$SHOW_PRICE 	= '399';//显示的合伙人价格
$AGENT_PROFIT 	= '300';//合伙人授权一个收益金额，一般是=show_price-pay_price
$SHENHE_WEIXIN	= 'Wx_ChuXin';//广告加速审核微信

$pid = intval($_GET['pid']);
if(11!=$pid && 16!=$pid && 24!=$pid){
	if(strpos($_SERVER['PHP_SELF'], 'notify')==false){
		echo '未开放';exit(0);
	}
}
?>