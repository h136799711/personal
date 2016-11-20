<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/12/3
 * Time: 11:17
 */

namespace app\src\base\utils;

use  app\src\base\exception\ApiException;

class CryptUtils {
    
    /**
     * 签证签名
     * @param $sign
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function verify_sign($sign,$data){
        $tmp_sign = CryptUtils::sign($data);
        if($sign == $tmp_sign){
            return true;
        }

        return false;
    }

    /**
     * 对数据进行解密,base64_decode 2次而已
     * @param $encrypt_data
     * @return string
     * @internal param $decrypt_data
     * @internal param $data
     */
    public static function decrypt($encrypt_data){
        return json_decode(base64_decode(base64_decode($encrypt_data)),JSON_OBJECT_AS_ARRAY);
    }

    /**
     * 对数据进行加密,base64_decode2次而已
     * @param $data
     * @return string
     */
    public static function encrypt($data){
        $str = json_encode($data,0,512);
        return base64_encode(base64_encode($str));
    }

    /**
     * 签名
     * @param $param
     * @return string
     * @throws \Exception
     */
    public static function sign($param){
        if(!isset($param['client_secret']) && empty($param['client_secret'])){
            throw new ApiException("client_secret参数非法!");
        }
        if(empty($time) && empty($param['time'])){
            throw new ApiException("time参数非法!");
        }
        if(empty($type) && empty($param['type'])){
            throw new ApiException("type参数非法!");
        }

        if(empty($notify_id) && empty($param['notify_id'])){
            throw new ApiException("notify_id参数非法!");
        }

        $time = $param['time'];
        $type = $param['type'];
        $data = $param['data'];
        $client_secret = $param['client_secret'];
        $notify_id = $param['notify_id'];

        $text = $time.$type.$data.$client_secret.$notify_id;

        return md5($text);
    }


}