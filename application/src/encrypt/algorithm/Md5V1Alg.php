<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:45
 */

namespace app\src\encrypt\algorithm;


use app\src\encrypt\des\Des;
use app\src\encrypt\exception\CryptException;

/**
 * Class Md5V1Alg
 * 目前使用于 java
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\encrypt\algorithm
 */
class Md5V1Alg extends IAlgorithm
{

    function getTransmissionData($param,$desKey)
    {
        $encrypt_data = Des::encryptDesEcbPKCS5(json_encode($param) , $desKey);
        
        return ($encrypt_data);
    }

    function verify_sign($data,$sign)
    {
        $tmp_sign = Md5V1Alg::sign($data);
        if($sign == $tmp_sign){
            return true;
        }

        return false;
    }

    function sign($param)
    {

        if(!isset($param['client_secret']) && empty($param['client_secret']) && !isset($param['client_id']) && empty($param['client_id'])){
            return "";
        }

        if(empty($time) && empty($param['time'])){
            throw new CryptException("time参数非法!");
        }

        if(empty($type) && empty($param['type'])){
            throw new CryptException("type参数非法!");
        }

        if(empty($notify_id) && empty($param['notify_id'])){
            throw new CryptException("notify_id参数非法!");
        }

        $time = $param['time'];
        $type = $param['type'];
        $data = $param['data'];

        $notify_id = $param['notify_id'];
        $text = "";
        if(isset($param['client_secret'])){
            $client_secret = $param['client_secret'];
            $text = $time.$type.$data.$client_secret.$notify_id;
        }elseif (isset($param['client_id'])){
            $client_id = $param['client_id'];
            $text = $time.$type.$data.$client_id.$notify_id;
        }

        return md5($text);
    }

    function decryptData($encryptData)
    {
        return json_decode(base64_decode(base64_decode($encryptData)),JSON_OBJECT_AS_ARRAY);
    }

    function encryptData($data)
    {
        $str = json_encode($data,0, 512);
        return base64_encode(base64_encode($str));
    }

}