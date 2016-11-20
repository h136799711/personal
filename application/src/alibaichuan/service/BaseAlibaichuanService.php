<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-09-14
 * Time: 16:14
 */

namespace app\src\alibaichuan\service;

abstract class BaseAlibaichuanService
{
    protected $config;

    /**
     * 解析返回结果
     * @param $resp 淘宝调用返回json数组信息
     * @return ['code'=>"0:成功 其它不成功",'info'=>'调用返回信息','extra'=>'返回的原始信息数组'];
     */
    public function parseResp($resp){
        $parse_code = "0";
        $parse_msg  = "请求成功";
        $extra = $resp;
        if(is_array($resp) && isset($resp['error_response'])){
            //
            $parse_code = "-1";
            $error_response = $resp['error_response'];
            $code = $error_response['code'];
            $msg  = $error_response['msg'];
            $sub_code = $error_response['sub_code'];
            $sub_msg  = $error_response['sub_msg'];
            if($sub_code == 'isv.param-error'){
                $parse_msg  = "(接口请求参数错误)";
            }elseif($sub_code == "isp.service-error"){
                $parse_msg  = "(服务内部错误,请稍后重试.)";
            }elseif($sub_code == "isv.data-duplicate-error"){
                $parse_msg  = "(重复添加)";
            }elseif($sub_code == "isp.http-read-timeout"){
                $parse_msg  = "(连接超时)";
            }elseif($sub_code == "isp.http-connection-refuse"){
                $parse_msg  = "(连接失败)";
            }elseif($sub_code == "isp.http-connection-timeout"){
                $parse_msg  = "(连接超时)";
            }else{
                $parse_msg = "";
            }
            $parse_msg = $msg.$parse_msg;

        }
        return ['code'=>$parse_code,'info'=>$parse_msg,'extra'=>$extra];
    }

    public function getTopClient(){
        $client = new \TopClient($this->getAppKey(),$this->getAppSecret());
        return $client;
    }

    public function getAppKey(){
        $appkey = $this->config['app_key'];
        if(empty($appkey)){
            throw new \Exception("缺少app_key信息！~");
        }
        return $appkey;
    }

    public function getAppSecret(){
        $app_secret = $this->config['app_secret'];
        if(empty($app_secret)){
            throw new \Exception("缺少app_secret信息！~");
        }

        return $app_secret;
    }

    function __construct()
    {
        $this->config = config('alibaichuan_cfg');
        define('IS_BAICHUAN_DEBUG',false);

        //引入2016年09月14日最新代码
        vendor("Alibaichuan20160914.TopSdk");
        if(!is_array($this->config)){
            throw new \Exception("请先配置百川的配置信息！~");
        }
    }



}