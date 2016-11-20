<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:26
 */

namespace app\src\rfpay\po;

/**
 * 订单结果查询请求体
 * Class RfNoCardOrderQueryReq
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardOrderQueryReq extends RfBaseReq
{
    /**
     * @return mixed
     */
    function getData()
    {
        $data = [];

        if(!empty($this->getLinkId())){
            $data['linkId'] = $this->getLinkId();
        }

        if(!empty($this->getOrderNo())){
            $data['orderNo'] = $this->getOrderNo();
        }

        return $data;
    }

    /**
     * @return mixed
     */
    function getAction()
    {
        return "SdkNocardOrderQuery";
    }
    
    private $linkId;

    private $orderNo;

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



}