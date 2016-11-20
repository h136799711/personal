<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:26
 */

namespace app\src\rfpay\service;

use app\src\rfpay\po\RfBaseReq;
use app\src\rfpay\po\RfBaseResp;
use app\src\rfpay\po\RfPayConfig;

class RfBaseService
{

    private $payConfig;

    public function __construct(RfPayConfig $payConfig=null)
    {
        $this->payConfig = $payConfig;
        if($this->payConfig == null){
            $this->payConfig = new RfPayConfig(null);
        }
    }

    /**
     * 进行请求
     * @param $action
     * @param RfBaseReq|RfBaseResp $resp
     * @return RfResponseBody
     * @internal param RfRequestBody $requestBody
     */
    public function doRequest(RfBaseReq $resp){
        return $this->curlPost($resp);
    }

    /**
     *
     * @param $action
     * @param RfBaseReq $req
     * @return array
     * @internal param $api_url
     * @internal param array $data
     * @internal param $url
     */
    protected function curlPost(RfBaseReq $req) {
        $url = rtrim($req->getApiUrl().$req->getApiType(),"/");
        $data = $req->toRequestParams();
//        var_dump($resp->getApiUrl());
//        var_dump("===<br/>====");
//        var_dump($data);
        $ch     = curl_init();
        $header = ['Accept-Charset'=>"utf-8"];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.64 Safari/537.36'); //chrome46/mac
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $error = curl_errno($ch);

//        var_dump($tmpInfo);
//        var_dump($error);
//        exit;
        if($error){
            return ['status' => false, 'info' => $error];
        }else{

            $js = json_decode($tmpInfo,true);
            if(is_null($js)){
                $js = "$tmpInfo";
            }
            return ['status' => true, 'info' => $js];
        }
    }
}