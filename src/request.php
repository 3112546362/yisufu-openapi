<?php

namespace Langhanyun\YisufuOpenapi;


class request{
    
    public $gateway_url = 'https://openapi.yisufu.cn';
    
    # 订单交易部分报文参数
    public $pay_name = ''; // 订单标题
    public $pay_body = ''; // 订单详细描述
    public $channel_type = ''; 
    public $total_fee = '';
    public $out_trade_no = '';
    public $notify_url = '';
    public $user_ip = '';
    public $server_url = '';
    public $sub_mch_id = '';
    public $appid = '';
    public $openid = '';
    public $sub_type = '';
    public $service = '';
    
    # 订单查询及退款部分参数
    public $system_order_id = '';
    public $bank_order = '';
    
    public $isError = false;
    public $ErrorMessage = '';

    public  $request_body = [];
    /**
     * @var mixed
     */
    public $request_array;


    public function init($config){
        $param = [
        	'open_userid' => $config['open_userid'],
        	'res_body' => [
        	    'channel_type' => $this->channel_type,
        	    'total_fee' => $this->total_fee,
        	    'pay_name' => $this->pay_name, // test_debug
        	    'pay_body' => $this->pay_body,
        	    'notify_url' => $this->notify_url,
        	    'out_trade_no' => $this->out_trade_no,
        	    'user_ip' => $this->user_ip,
        	    'server_url' => $this->server_url,
        	    'sub_mch_id' => $this->sub_mch_id,
        	    'appid' => $this->appid,
        	    'openid' => $this->openid,
        	    'sub_type' => $this->sub_type,
        	    'system_order_id' => $this->system_order_id,
        	    'bank_order' => $this->bank_order,
        	],
        	'service' => $this->service,
        	'sign_type' => $config['sign_type'],
            'version' => '2.0',
        ];
        
        
        foreach ($param['res_body'] as $k => $v){
            if($v == '') unset($param['res_body'][$k]);
        }
        
        // res_body 格式化json
        ksort($param['res_body']);
        
        $param['res_body'] = json_encode($param['res_body'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        
        $sign = new sign;
        $param['sign'] = $sign->sign_value($param,$config);
        
        $this->request_body = json_encode($param);
        
        $body = $this->curl($param);
        
        $re_data = @json_decode($body,true);
        if(!$re_data){
            $this->isError = true;
            $this->ErrorMessage = 'JSON解析失败';
            return ;
        }
        if($re_data['rsp_code'] == '0000'){
            $this->isError = false;
            $this->request_array = $re_data['request_array'];
        }else{
            $this->isError = true;
            $this->ErrorMessage = $re_data['rsp_msg'];
        }
        
    }
    
    public function curl($data){
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->gateway_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);//单位 秒，也可以使用
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    public function order_status($status): string
    {
        return $status == '1' ? '支付成功' : '等待付款';
    }
    
}