<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:48
 */

namespace app\src\rfpay\po;

/**
 * 商户余额查询请求体
 * Class RfNoCardBalanceReq
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\po
 */
class RfNoCardBalanceReq extends RfBaseReq
{
    /**
     * @return mixed
     */
    function getData()
    {
        return [
            'linkId'=>$this->getLinkId()
        ];
    }

    /**
     * @return mixed
     */
    function getAction()
    {
        return "SdkNocardSettleBalance";
    }

    private $linkId;

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



}