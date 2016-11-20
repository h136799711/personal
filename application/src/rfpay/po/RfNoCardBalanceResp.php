<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:50
 */

namespace app\src\rfpay\po;

/**
 * 商户查询返回结果
 * Class RfNoCardBalanceResp
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardBalanceResp extends RfBaseResp
{
    public function __construct($result, RfPayConfig $payConfig)
    {
        parent::__construct($result, $payConfig);

        $this->initParams();
    }


    private function initParams(){

        $this->setAllBalanceT0("");
        $this->setEnableBalanceT0("");
        $this->setUnableBalanceT0("");
        $this->setAllBalanceT1("");
        $this->setEnableBalanceT1("");
        $this->setUnableBalanceT1("");
        if(is_array($this->decodeData)){
            $keys = ["allBalanceT0","unableBalanceT0","enableBalanceT0","allBalanceT1","unableBalanceT1","enableBalanceT1"];
            
            foreach ($keys as $key){
                if(isset($this->decodeData[$key])){
                    $this->$key = $this->decodeData[$key];
                }
            }
        }
    }

    private $allBalanceT0;//T0总金额
    private $unableBalanceT0;//T0不可用总金额
    private $enableBalanceT0;//T0可用总金额
    private $allBalanceT1;//T1总金额
    private $unableBalanceT1;//T1不可用总金额
    private $enableBalanceT1;//T1可用总金额

    /**
     * @return mixed
     */
    public function getAllBalanceT0()
    {
        return $this->allBalanceT0;
    }

    /**
     * @param mixed $allBalanceT0
     */
    public function setAllBalanceT0($allBalanceT0)
    {
        $this->allBalanceT0 = $allBalanceT0;
    }

    /**
     * @return mixed
     */
    public function getUnableBalanceT0()
    {
        return $this->unableBalanceT0;
    }

    /**
     * @param mixed $unableBalanceT0
     */
    public function setUnableBalanceT0($unableBalanceT0)
    {
        $this->unableBalanceT0 = $unableBalanceT0;
    }

    /**
     * @return mixed
     */
    public function getEnableBalanceT0()
    {
        return $this->enableBalanceT0;
    }

    /**
     * @param mixed $enableBalanceT0
     */
    public function setEnableBalanceT0($enableBalanceT0)
    {
        $this->enableBalanceT0 = $enableBalanceT0;
    }

    /**
     * @return mixed
     */
    public function getAllBalanceT1()
    {
        return $this->allBalanceT1;
    }

    /**
     * @param mixed $allBalanceT1
     */
    public function setAllBalanceT1($allBalanceT1)
    {
        $this->allBalanceT1 = $allBalanceT1;
    }

    /**
     * @return mixed
     */
    public function getUnableBalanceT1()
    {
        return $this->unableBalanceT1;
    }

    /**
     * @param mixed $unableBalanceT1
     */
    public function setUnableBalanceT1($unableBalanceT1)
    {
        $this->unableBalanceT1 = $unableBalanceT1;
    }

    /**
     * @return mixed
     */
    public function getEnableBalanceT1()
    {
        return $this->enableBalanceT1;
    }

    /**
     * @param mixed $enableBalanceT1
     */
    public function setEnableBalanceT1($enableBalanceT1)
    {
        $this->enableBalanceT1 = $enableBalanceT1;
    }


}