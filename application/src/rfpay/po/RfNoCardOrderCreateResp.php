<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-27
 * Time: 10:40
 */

namespace app\src\rfpay\po;

/**
 * Class RfNoCardOrderResp
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardOrderCreateResp extends RfBaseResp
{
    public function __construct($result,RfPayConfig $payConfig)
    {
        parent::__construct($result,$payConfig);

        if(is_array($this->decodeData) && isset($this->decodeData['orderNo'])){
            $this->setOrderNo($this->decodeData['orderNo']);
        }else{
            $this->setOrderNo("");
        }

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