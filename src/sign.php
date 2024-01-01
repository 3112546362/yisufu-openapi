<?php

namespace Langhanyun\YisufuOpenapi;

class sign {
    
    # 获得签名信息内容
    public function sign_value($param,$config,$sign = '')
    {
        
        // param 排序
        ksort($param);
        
        if($config['sign_type'] == 'MD5' || $config['sign_type'] == 'SHA1'){
            $sign_true = $this->md5sign($param,$config);
        }elseif($config['sign_type'] == 'RSA2' && $sign == ''){
            $sign_true = $this->rsa($param,$config);
        }elseif($config['sign_type'] == 'RSA2' && $sign != ''){
            $sign_true = $this->verify($param,$config,$sign);
            return $sign_true ? true : false;
        }
        
        if($sign == ''){
            return $sign_true;
        }else{
            if($sign != $sign_true) return false;
            return true;
        }
        
    }
    
    # md5 sha1 sign
    public function md5sign($param,$config): string
    {
        $data = '';
        foreach($param as $k => $v){
            $data .= "&{$k}={$v}";
        }
        
        $data = substr($data,1);
        if($config['sign_type'] == 'MD5'){
            return md5($data.$config['open_key']);
        }else{
            return sha1($data.$config['open_key']);
        }
    }
    
    # SHA256
    public function rsa($param,$config){
        $json = json_encode($param,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        // echo "待签名数据：{$json}";
        $priKey = $config['rsa_rkey'];
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($json, $sign, $res, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }
    
    # 验证签名是否正确
    public function verify($param,$config,$sign){
        
        ksort($param);
        $public = $config['rsa_public_key'];
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($public, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('您使用的公钥格式错误，请检查RSA公钥配置');

        //调用openssl内置方法验签，返回bool值
        return (@openssl_verify(json_encode($param), base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1);
    }

	
}
