<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:50
 */

namespace app\src\rfpay\utils;


use app\src\rfpay\po\RfPayConfig;

class RsaUtils
{
    public static function test(){
        //以下是二进制pem转换过来的 BEGIN 与 END 部分是手动添加
        $config = new RfPayConfig(null);
        $pemKey = $config->getPemContent();

        $data = "!1231231sdaf3ra中文gt23*(&(*^";

        echo var_dump($pemKey);
        $encrypt = RsaUtils::encrypt($data,$pemKey);
        echo $encrypt;
        echo "<br/>===========================================<br/>";
        $encrypt = "KwirD5bYWk264T+DaPqKRp9UttPxJpdUrVr6NMBc/s368opIz/ZhkopG1s5A xDIL3lT/mJwhjLHbZC+nBzfVPUD45XTevWXFiKWTEipuxcqnHj0BEBBLHSrs jr+MzILLTUqyh8rO2+JAn+MH21kyVMAvXTDPDQWCK07AWfuowog=";
        echo RsaUtils::decrypt($encrypt,$pemKey);

        exit;
    }

    /**
     * 私钥加密
     * @param $data
     * @param $private_key
     * @return string  返回的是base64加密过的
     * @throws CryptException
     */
    public static function encrypt($data,$private_key){
        $pi_key = openssl_pkey_get_private($private_key);
        if($pi_key === false){
            throw  new CryptException("私钥获取失败");
        }
        
        $encrypt = "";
        $ret = openssl_private_encrypt($data,$encrypt,$private_key);


        if($ret == false){
            throw  new CryptException("加密失败");
        }

        return base64_encode($encrypt);
    }

    /**
     * 私钥解密
     * @author hebidu <email:346551990@qq.com>
     * @param $data
     * @param $private_key
     * @return string
     * @throws CryptException
     */
    public static function decrypt($data,$private_key){
        $pi_key = openssl_pkey_get_private($private_key);
        if($pi_key === false){
            throw  new CryptException("私钥获取失败");
        }

        $decrypt = "";
        $ret = openssl_private_decrypt(base64_decode($data),$decrypt,$private_key);

        if($ret == false){
            throw  new CryptException("解密失败");
        }

        return $decrypt;
    }
}