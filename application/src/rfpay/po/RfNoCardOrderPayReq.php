<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:20
 */

namespace app\src\rfpay\po;


class RfNoCardOrderPayReq extends RfBaseReq
{
    /**
     * @return mixed
     */
    function getAction()
    {
        return "SdkNocardOrderPay";
    }


    /**
     *
     * @return mixed
     */
    function getData()
    {
        return [
            'orderNo'=>$this->getOrderNo(),
            'code'=>$this->getCode()
        ];
    }



    //订单流水号
    private $orderNo;

    //短信验证码
    private $code;

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

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

}