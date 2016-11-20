<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-27
 * Time: 10:14
 */

namespace app\src\rfpay\po;

/**
 * 无卡支付传参数
 * Class RfNoCardOrderModel
 * @package app\src\rfpay\po
 */
class RfNoCardOrderCreateReq extends RfBaseReq
{
    /**
     *
     * @return mixed
     */
    function getAction()
    {
        return "SdkNocardOrderConsume";
    }


    public function setTestData(){
        $this->setLinkId("1477555031231");
        $this->setAmount("1");
        $this->setBankAccount("陈斌委");
        $this->setBankCert("331023198512285222");
        $this->setBankCvv("123");
        $this->setBankNo("6225123456787413");
        $this->setBankPhone("18757166868");
        $this->setBankYxq("1234");
        $this->setType("T0");
        $this->setGoodsId("1000");
    }

    /**
     * 记录日志
     * @return array
     */
    public function getData()
    {
        return  [
            "linkId"=>$this->getLinkId(),
            "type"=>$this->getType(),
            "amount"=>$this->getAmount(),
            "goodsId"=>$this->getGoodsId(),
            "backUrl"=>$this->getNoCardOrderBackUrl(),
            "bankNo"=>$this->getBankNo(),
            "bankAccount"=>$this->getBankAccount(),
            "bankPhone"=>$this->getBankPhone(),
            "bankCert"=>$this->getBankCert(),
            "bankCvv"=>$this->getBankCvv(),
            "bankYxq"=>$this->getBankYxq()
        ];
    }



    private $amount;//支付金额：单位：分
    private $bankAccount;//银行账户
    private $bankCert;//绑定身份证号
    private $bankCvv;//信用卡后三位
    private $bankNo;//银行卡号
    private $bankPhone;//绑定手机号码
    private $bankYxq;//信用卡有效期 MMYY 格式
    private $goodsId;//商品ID 1000:消费充值
    private $linkId;//请求流水号
    private $type;



    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param mixed $bankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return mixed
     */
    public function getBankCert()
    {
        return $this->bankCert;
    }

    /**
     * @param mixed $bankCert
     */
    public function setBankCert($bankCert)
    {
        $this->bankCert = $bankCert;
    }

    /**
     * @return mixed
     */
    public function getBankCvv()
    {
        return $this->bankCvv;
    }

    /**
     * @param mixed $bankCvv
     */
    public function setBankCvv($bankCvv)
    {
        $this->bankCvv = $bankCvv;
    }

    /**
     * @return mixed
     */
    public function getBankNo()
    {
        return $this->bankNo;
    }

    /**
     * @param mixed $bankNo
     */
    public function setBankNo($bankNo)
    {
        $this->bankNo = $bankNo;
    }

    /**
     * @return mixed
     */
    public function getBankPhone()
    {
        return $this->bankPhone;
    }

    /**
     * @param mixed $bankPhone
     */
    public function setBankPhone($bankPhone)
    {
        $this->bankPhone = $bankPhone;
    }

    /**
     * @return mixed
     */
    public function getBankYxq()
    {
        return $this->bankYxq;
    }

    /**
     * @param mixed $bankYxq
     */
    public function setBankYxq($bankYxq)
    {
        $this->bankYxq = $bankYxq;
    }

    /**
     * @return mixed
     */
    public function getGoodsId()
    {
        return $this->goodsId;
    }

    /**
     * @param mixed $goodsId
     */
    public function setGoodsId($goodsId)
    {
        $this->goodsId = $goodsId;
    }

    /**
     * @return mixed
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @param mixed $linkId
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}