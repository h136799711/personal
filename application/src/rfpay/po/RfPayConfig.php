<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:09
 */

namespace app\src\rfpay\po;


use app\src\rfpay\utils\CryptException;

class RfPayConfig
{

    private $api_url;//接口地址
    private $noCardOrderBackUrl;//订单创建回调地址
    private $orgNo;//为接入机构分配机构编号(orgNo)
    private $merNo;//商户编号(merNo)
    private $key;//接口授权密钥
    private $pemPath = "";

    public function __construct($config)
    {
        RfPayConfig::init($config);
    }
        
    public function init($config){
        if(empty($config) || !is_array($config)) $config = config("rf_pay_config");
        
        if(isset($config['api_url'])){
            $this->setApiUrl($config['api_url']);
        }

        if(isset($config['org_no'])){
            $this->setOrgNo($config['org_no']);
        }

        if(isset($config['mer_no'])){
            $this->setMerNo($config['mer_no']);
        }

        if(isset($config['key'])){
            $this->setKey($config['key']);
        }

        if(isset($config['pem_path'])){
            $this->setPemPath($config['pem_path']);
        }

        if(isset($config['no_card_order_backUrl'])){
            $this->setNoCardOrderBackUrl($config['no_card_order_backUrl']);
        }

    }

    /**
     * 获取订单创建成功后的回调地址
     * @return mixed
     */
    public function getNoCardOrderBackUrl()
    {
        return $this->noCardOrderBackUrl;
    }

    /**
     * @param mixed $noCardOrderBackUrl
     */
    public function setNoCardOrderBackUrl($noCardOrderBackUrl)
    {
        $this->noCardOrderBackUrl = $noCardOrderBackUrl;
    }



    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }

    /**
     * @param mixed $api_url
     */
    public function setApiUrl($api_url)
    {
        $this->api_url = $api_url;
    }

    /**
     * @return mixed
     */
    public function getOrgNo()
    {
        return $this->orgNo;
    }

    /**
     * @param mixed $orgNo
     */
    public function setOrgNo($orgNo)
    {
        $this->orgNo = $orgNo;
    }

    /**
     * @return mixed
     */
    public function getMerNo()
    {
        return $this->merNo;
    }

    /**
     * @param mixed $merNo
     */
    public function setMerNo($merNo)
    {
        $this->merNo = $merNo;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getPemPath()
    {
        return $this->pemPath;
    }

    /**
     * @param string $pemPath
     */
    public function setPemPath($pemPath)
    {
        $this->pemPath = $pemPath;
    }

    /**
     * 获取rsa 私钥  内容
     * @author hebidu <email:346551990@qq.com>
     */
    public function getPemContent(){

        $path = $this->getPemPath();

        $result = file_get_contents($path);

        if($result === false){
            throw new \Exception("读取私钥内容失败");
        }

        return $result;
    }



}