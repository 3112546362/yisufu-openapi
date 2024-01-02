<?php

/**
 *  订单付款状态查询
 */
 
include_once('src/request.php');
include_once('config.php');
use Langhanyun\YisufuOpenapi\request;

# 初始化网络请求
$gateways_init = new Request;

# 配置请求的类型
$gateways_init->service = 'order.query.status';

# 本次请求需求的报文内容
$gateways_init->system_order_id = 'xxx'; // 易速付订单流水号

$request = $gateways_init->init($config);

# 网络接口请求结束
if($gateways_init->isError == true){
    echo '接口请求错误：'.$gateways_init->ErrorMessage;
}else{
    echo '接口请求成功: ';
    echo json_encode($gateways_init->request_array);
    echo "<br><br>接口请求报文：{$gateways_init->request_body}";
    echo "<hr/>";
    echo "订单支付状态：{$gateways_init->order_status($gateways_init->request_array['status'])}";
}