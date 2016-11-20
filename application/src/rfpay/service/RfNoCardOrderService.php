<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-27
 * Time: 10:07
 */

namespace app\src\rfpay\service;

use app\src\rfpay\po\RfNoCardBalanceReq;
use app\src\rfpay\po\RfNoCardBalanceResp;
use app\src\rfpay\po\RfNoCardOrderCreateReq;
use app\src\rfpay\po\RfNoCardOrderCreateResp;
use app\src\rfpay\po\RfNoCardOrderPayReq;
use app\src\rfpay\po\RfNoCardOrderPayResp;
use app\src\rfpay\po\RfNoCardOrderSmsReq;
use app\src\rfpay\po\RfNoCardOrderSmsResp;


/**
 * 1. 创建订单
 * Class RfNoCardOrder
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\rfpay\service
 */
class RfNoCardOrderService extends RfBaseService
{

    /**
     * 创建订单
     * @author hebidu <email:346551990@qq.com>
     * @param RfNoCardOrderCreateReq $req
     * @return \app\src\rfpay\po\RfNoCardOrderCreateResp
     */
    public function create(RfNoCardOrderCreateReq $req){
        return  new RfNoCardOrderCreateResp($this->doRequest($req),$req->getPayConfig());
    }

    /**
     * 2. 发送短信验证码
     * @param RfNoCardOrderSmsReq $req
     * @return RfNoCardOrderSmsResp
     */
    public function sendSms(RfNoCardOrderSmsReq $req){
        return new RfNoCardOrderSmsResp($this->doRequest($req),$req->getPayConfig());
    }

    /**
     * 3. 订单支付
     * @param RfNoCardOrderPayReq $req
     * @return RfNoCardOrderPayResp
     */
    public function pay(RfNoCardOrderPayReq $req){
        return new RfNoCardOrderPayResp($this->doRequest($req),$req->getPayConfig());
    }

    /**
     * 查询账户余额
     * @param RfNoCardBalanceReq $req
     * @return RfNoCardBalanceReq
     */
    public function balance(RfNoCardBalanceReq $req){
        return new RfNoCardBalanceResp($this->doRequest($req),$req->getPayConfig());
    }
}