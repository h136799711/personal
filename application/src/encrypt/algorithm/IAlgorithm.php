<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:42
 */

namespace app\src\encrypt\algorithm;

/**
 * Class IAlgorithm
 * 数据传输算法
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\encrypt\algorithm
 */
abstract  class IAlgorithm
{

    /**
     * 获取用于传输、流通的数据
     * @param $param
     * @param $desKey
     * @return mixed
     */
    abstract function getTransmissionData($param,$desKey);

    /**
     * 签名校验
     * @param $data
     * @param $sign
     * @return mixed
     */
    abstract function verify_sign($data,$sign);

    /**
     * 签名
     * @param $param
     * @return mixed
     */
    abstract function sign($param);

    /**
     * 解密数据
     * @param $encryptData
     * @return mixed
     */
    abstract function  decryptData($encryptData);

    /**
     * 加密数据
     * @param $data
     * @return mixed
     */
    abstract function  encryptData($data);
}