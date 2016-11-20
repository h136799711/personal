<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:58
 */

namespace app\src\rfpay\po;


use app\src\rfpay\utils\AesUtils;
use app\src\rfpay\utils\RsaUtils;

abstract  class RfBaseReq
{
    /**
     * 获取数据
     * @return mixed
     */
    abstract function getData();

    /**
     * 设置请求服务
     * @return mixed
     */
    abstract function getAction();
    
    public function getApiType(){
        return "/nocard/action";
    }



//    private $orgNo;
//    private $merNo;
    private $action;
    private $payConfig;
    private $orginKey;
    private $encryptKey;//加密后的key
    private $dataStr;



    public function __construct(RfPayConfig $config=null)
    {
        $this->payConfig = $config;
        if($this->payConfig == null){
            $this->payConfig = new RfPayConfig(null);
        }
    }

    /**
     * @return RfPayConfig
     */
    public function getPayConfig()
    {
        return $this->payConfig;
    }

    

    /**
     * 转化成请求数组
     * @return array
     */
    public function toRequestParams(){

        $data = $this->getData();

        $this->orginKey  = md5(strtolower(time()));
        $this->orginKey  = substr($this->orginKey,0,16);

        $this->encryptKey = RsaUtils::encrypt($this->orginKey,$this->payConfig->getPemContent());

        //AES加密的字符串
        $this->dataStr = AesUtils::encrypt(json_encode($data),$this->orginKey);

        return [
            'orgNo'=>$this->getOrgNo(),
            'merNo'=>$this->getMerNo(),
            'action'=>$this->getAction(),
            'data'=>$this->getDataStr(),
            'encryptkey'=>$this->getEncryptKey(),
            'sign'=>$this->getSign()
        ];
    }

    /**
     * 获取订单创建后回调通知
     * @return mixed
     */
    public function getNoCardOrderBackUrl(){
        return $this->payConfig->getNoCardOrderBackUrl();
    }

    /**
     * @return mixed
     */
    public function getOrgNo()
    {
        return $this->payConfig->getOrgNo();
    }

    /**
     * @return mixed
     */
    public function getMerNo()
    {
        return $this->payConfig->getMerNo();
    }

    /**
     * @return mixed
     */
//    public function getAction()
//    {
//        return $this->action;
//    }

    /**
     * @param mixed $action
     */
//    public function setAction($action)
//    {
//        $this->action = $action;
//    }




    /**
     * 获取加密key
     * @return mixed
     */
    public function getEncryptKey()
    {
        return $this->encryptKey;
    }

    /**
     * 数据签名,校验数据安全性，加密方式参考如下
     * Md5(orgNo+merNo+action+data+分配商户授权密钥)
     * @return mixed
     */
    public function getSign()
    {
        $sign = md5($this->getOrgNo().$this->getMerNo().$this->getAction().$this->getDataStr().$this->payConfig->getKey());
        return $sign;
    }

    /**
     *
     */
    public function getDataStr(){
        return $this->dataStr;
    }

    /**
     * 获取一个随机的流水号
     * @author hebidu <email:346551990@qq.com>
     */
    public function getRandomLinkId(){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8)  . '';
        $uuid .= substr($str,8,4)  . '';
//        $uuid .= substr($str,12,4) . '';
        $uuid .= substr($str,16,6) . '';
//        $uuid .= substr($str,20,12);

        return 'L'.substr(date("YmdHis",time()),1).$uuid;
   }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->payConfig->getApiUrl();
    }


}