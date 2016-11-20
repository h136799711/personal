<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 15:49
 */

namespace app\src\message\sms;


use app\src\message\interfaces\IMessage;

class QCloudSms implements IMessage
{
    var $url;
    var $sdk_app_id;
    var $app_key;

//    var $nationCode;
//$phoneNumber, $content
    // sdkappid 使用整数即可
    function __construct($sdk_app_id, $app_key)
    {
        // url 需要根据我们说明文档上适时调整
        $this->url = "https://yun.tim.qq.com/v3/tlssmssvr/sendsms";
        $this->sdk_app_id = $sdk_app_id;
        $this->app_key = $app_key;
    }

    public function init($nationCode, $phoneNumber, $content)
    {

    }

    /**
         * @return mixed */
    function create(){
    }

    /**
     * @return mixed
     */
    function send()
    {
            $randNum = rand(100000, 999999);
            $wholeUrl = $this->url . "?sdkappid=" . $this->sdk_app_id . "&random=" . $randNum;
            echo $wholeUrl;
            $tel = new \stdClass();
            $tel->nationcode = $nationCode;
            $tel->phone = $phoneNumber;
            $jsonData = new \stdClass();
            $jsonData->tel = $tel;
            $jsonData->type = "0";
            $jsonData->msg = $content;
            $jsonData->sig = md5($this->app_key . $phoneNumber);
            $jsonData->extend = "";     // 根据需要添加，一般保持默认
            $jsonData->ext = "";        // 根据需要添加，一般保持默认
            $curlPost = json_encode($jsonData);
            echo $curlPost;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $wholeUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $ret = curl_exec($ch);
            $error = "";
            if ($ret === false) {

                $error = (curl_error($ch));

            }
            else {

                $json = json_decode($ret, JSON_OBJECT_AS_ARRAY);

                if ($json == false) {
                    $error = $ret;
                } else {

                    return $json;
                }
            }

            curl_close($ch);

            return $error;
        }


}