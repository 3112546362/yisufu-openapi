<?php

/**
 *  微信小程序内支付（使用自有小程序）
 */
 
include_once('src/request.php');
include_once('config.php');
use Langhanyun\YisufuOpenapi\request;

# 初始化网络请求
$gateways_init = new Request;

# 配置请求的类型
$gateways_init->service = 'gateway.unified.pay';

# 本次请求需求的报文内容
$gateways_init->channel_type = 'WECHAT_H5';
$gateways_init->total_fee = '0.01';
$gateways_init->pay_name = '测试订单';
$gateways_init->pay_body = '订单交易测试';
$gateways_init->notify_url = 'https://api.yisufu.cn/yisufu-openapi/notify.php';
$gateways_init->out_trade_no = date("YmdHis").rand(1,99).rand(1,99).rand(1,99).rand(1,99).rand(1,99);
$gateways_init->user_ip = $_SERVER['REMOTE_ADDR']; // $_SERVER['REMOTE_ADDR']
$gateways_init->server_url = $_SERVER['HTTP_HOST'];
$gateways_init->sub_mch_id = 'xxx';
$gateways_init->appid = 'xxx'; // 微信指定的小程序APPID
$gateways_init->openid = 'xxx'; // 获取的用户OPENID
$request = $gateways_init->init($config);

# 网络接口请求结束
if($gateways_init->isError == true){
    echo '接口请求错误：'.$gateways_init->ErrorMessage;
}else{
    echo '接口请求成功: ';
    echo json_encode($gateways_init->request_array);
    echo "<br><br>接口请求报文：{$gateways_init->request_body}";
    echo "<hr/>";
    echo "微信小程序付款参数：".json_encode($gateways_init->request_array['wechat_pay_info']);
    echo "<br>小程序APPID：{$gateways_init->request_array['wechat_appid']}";
    echo "<br>易速付系统流水号：{$gateways_init->request_array['system_order_id']}";
}