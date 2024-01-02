<?php

namespace Langhanyun\YisufuOpenapi;
include_once('config.php');
include_once('src/sign.php');


$param = [
	'open_userid' => $config['open_userid'],
    'out_trade_no' => $_POST['out_trade_no'],
    'system_order_id' => $_POST['system_order_id'],
    'pay_external_id' => $_POST['pay_external_id'],
    'total_fee' => $_POST['total_fee']
];

ksort($param);

$sign = new Sign;

$sign = $sign->sign_value($param,$config,$_POST['sign']);

if($sign && $_POST['sign'] !=''){ // 建议此处还是判断下sign是否为空值
    # SUCCESS 校验成功了,再此处实现业务逻辑
    $out_trade_no = $param['out_trade_no']; // 商户订单号
    echo 'SUCCESS'; // 输出SUCCESS则判断为通知成功
}else{
    echo '签名校验失败';
}