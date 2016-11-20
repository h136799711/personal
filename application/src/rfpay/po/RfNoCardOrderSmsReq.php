<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:02
 */

namespace app\src\rfpay\po;

/**
 * Class RfOrderSmsReq
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardOrderSmsReq extends RfBaseReq
{
    public function __construct(RfPayConfig $config=null)
    {
        parent::__construct($config);
    }

    /**
     * @return mixed
     */
    function getAction()
    {
        return "SdkNocardOrderSms";
    }


    /**
     * @return mixed
     */
    function getData()
    {
        return [
            'orderNo'=>$this->getOrderNo()
        ];
    }

    private $orderNo;

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }



}