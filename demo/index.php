<?php

/**
 *  统一扫码支付
 */

use Langhanyun\YisufuOpenapi\request;

# 配置对接参数
$config = [
    'open_userid' => '',
    'open_key' => '',
    'rsa_rkey' => '', // 支付宝工具生成的RSA私钥
    'rsa_public_key' => '', // 平台RSA2公钥
    'sign_type' => 'RSA2', // 请按照文档填写合适的参数
];

# 初始化网络请求
$gateways_init = new Request;

# 配置请求的类型
$gateways_init->service = 'gateway.unified.pay';

# 本次请求需求的报文内容
$gateways_init->channel_type = 'ALIPAY'; // 扫码可选 ALIPAY WECHAT_MP UNIONPAY_QR
$gateways_init->total_fee    = '0.01';
$gateways_init->pay_name     = '测试订单';
$gateways_init->pay_body     = '订单交易测试';
$gateways_init->notify_url   = 'https://api.langhanyun.com/demo/notify.php';
$gateways_init->out_trade_no = date("YmdHis").rand(1,99).rand(1,99).rand(1,99).rand(1,99).rand(1,99);
$gateways_init->user_ip      = $_SERVER['REMOTE_ADDR'];
$gateways_init->server_url   = $_SERVER['HTTP_HOST'];
$gateways_init->sub_mch_id   = '6015667110974';
# 发送请求
$request = $gateways_init->init($config);

# 网络接口请求结束
if($gateways_init->isError == true){
    echo '接口请求错误：'.$gateways_init->ErrorMessage;
}else{
    echo '接口请求成功: ';
    echo json_encode($gateways_init->request_array);
    echo "<br><br>接口请求报文：{$gateways_init->request_body}";
    echo "<hr/>";
    echo "二维码支付地址：{$gateways_init->request_array['pay_url']}";
    echo "<br>易速付系统流水号：{$gateways_init->request_array['system_order_id']}";
}



