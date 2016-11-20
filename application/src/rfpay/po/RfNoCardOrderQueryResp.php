<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:37
 */

namespace app\src\rfpay\po;

/**
 * 无卡支付订单查询返回结果
 * Class RfNoCardOrderQueryResp
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardOrderQueryResp extends RfBaseResp
{
    public function __construct($result, RfPayConfig $payConfig)
    {
        parent::__construct($result, $payConfig);
        $this->setOrderNo("");
        $this->setLinkId("");
        $this->setOrderStatus("");
        $this->setOrderTime("");
        $this->setOrderMemo("");

        if(is_array($this->decodeData)){

            if(isset($this->decodeData['orderNo'])){
                $this->setOrderNo($this->decodeData['orderNo']);
            }

            if(isset($this->decodeData['linkId'])){
                $this->setLinkId($this->decodeData['linkId']);
            }

            if(isset($this->decodeData['orderStatus'])){
                $this->setOrderStatus($this->decodeData['orderStatus']);
            }

            if(isset($this->decodeData['orderTime'])){
                $this->setOrderTime($this->decodeData['orderTime']);
            }

            if(isset($this->decodeData['orderMemo'])){
                $this->setOrderMemo($this->decodeData['orderMemo']);
            }

        }

    }

    private $linkId;
    private $orderNo;
    private $orderStatus;//订单状态，参考 RfOrderStatus
    private $orderTime;//订单时间
    private $orderMemo;//订单备注

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
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param mixed $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * @return mixed
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param mixed $orderTime
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
    }

    /**
     * @return mixed
     */
    public function getOrderMemo()
    {
        return $this->orderMemo;
    }

    /**
     * @param mixed $orderMemo
     */
    public function setOrderMemo($orderMemo)
    {
        $this->orderMemo = $orderMemo;
    }



}