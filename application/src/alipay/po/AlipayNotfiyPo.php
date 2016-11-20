<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 11:04
 */

namespace app\src\alipay\po;


class AlipayNotfiyPo
{
 
    

    public function init($arr){
        $this->setTotalAmount($arr['total_amount']);
        $this->setBuyerId($arr['buyer_id']);
        $this->setTradeNo($arr['trade_no']);
        $this->setBody($arr['body']);
        $this->setNotifyTime($arr['notify_time']);
        $this->setSubject($arr['subject']);
        $this->setSignType($arr['sign_type']);
        $this->setBuyerLogonId($arr['buyer_logon_id']);
        $this->setAuthAppId($arr['auth_app_id']);
        $this->setCharset($arr['charset']);
        $this->setNotifyType($arr['notify_type']);
        $this->setInvoiceAmount($arr['invoice_amount']);
        $this->setOutTradeNo($arr['out_trade_no']);
        $this->setTradeStatus($arr['trade_status']);
        $this->setGmtPayment($arr['gmt_payment']);
        $this->setVersion($arr['version']);
        $this->setPointAmount($arr['point_amount']);
        $this->setSign($arr['sign']);
        $this->setGmtCreate($arr['gmt_create']);
        $this->setBuyerPayAmount($arr['buyer_pay_amount']);
        $this->setReceiptAmount($arr['receipt_amount']);
        $this->setFundBillList($arr['fund_bill_list']);
        $this->setAppId($arr['app_id']);
        $this->setSellerId($arr['seller_id']);
        $this->setNotifyId($arr['notify_id']);
        $this->setSellerEmail($arr['seller_email']);
    }

    private $total_amount;//支付金额(单位: 元)
    private $buyer_id;//2088702242653434
    private $trade_no;//2016111721001004430204578896
    private $body;//2016111721001004430204578896
    private $notify_time;//2016-11-17 11:09:13
    private $subject;//冠南测试
    private $sign_type;//RSA
    private $buyer_logon_id;//158****5030
    private $auth_app_id;//2016111102714102
    private $charset;//utf-8
    private $notify_type;//trade_status_sync
    private $invoice_amount;//0.01
    private $out_trade_no;//PA16321104829701380920
    private $trade_status;//TRADE_SUCCESS
    private $gmt_payment;//2016-11-17 10:49:21
    private $version;//1.0
    private $point_amount;//0.00
    private $sign;//goonjRh2q0YNFy4cJzy1bRGnn06j1L1tYFyo2MYv8yKLwzRKra+oL8J2VUY320XqpJx78yrP+y8mVuHqjq3YW03qxyzXaIRgj6sCiB09Ak8Qy3JGONehrOzrid5W0mQzngBVoezPjeYUX6AuqNmBg2pCEQTTNoVxk8hHbrmB1gE=
    private $gmt_create;//2016-11-17 10:49:19
    private $buyer_pay_amount;//0.01 用户支付金额
    private $receipt_amount;//0.01
    private $fund_bill_list;//[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]
    private $app_id;//2016111102714102
    private $seller_id;//2088211347135986
    private $notify_id;//095018f143c42540caed82682ed9552jbi
    private $seller_email;//353539333@qq.com

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

    /**
     * @return mixed
     */
    public function getBuyerId()
    {
        return $this->buyer_id;
    }

    /**
     * @param mixed $buyer_id
     */
    public function setBuyerId($buyer_id)
    {
        $this->buyer_id = $buyer_id;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->trade_no;
    }

    /**
     * @param mixed $trade_no
     */
    public function setTradeNo($trade_no)
    {
        $this->trade_no = $trade_no;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getNotifyTime()
    {
        return $this->notify_time;
    }

    /**
     * @param mixed $notify_time
     */
    public function setNotifyTime($notify_time)
    {
        $this->notify_time = $notify_time;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->sign_type;
    }

    /**
     * @param mixed $sign_type
     */
    public function setSignType($sign_type)
    {
        $this->sign_type = $sign_type;
    }

    /**
     * @return mixed
     */
    public function getBuyerLogonId()
    {
        return $this->buyer_logon_id;
    }

    /**
     * @param mixed $buyer_logon_id
     */
    public function setBuyerLogonId($buyer_logon_id)
    {
        $this->buyer_logon_id = $buyer_logon_id;
    }

    /**
     * @return mixed
     */
    public function getAuthAppId()
    {
        return $this->auth_app_id;
    }

    /**
     * @param mixed $auth_app_id
     */
    public function setAuthAppId($auth_app_id)
    {
        $this->auth_app_id = $auth_app_id;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getNotifyType()
    {
        return $this->notify_type;
    }

    /**
     * @param mixed $notify_type
     */
    public function setNotifyType($notify_type)
    {
        $this->notify_type = $notify_type;
    }

    /**
     * @return mixed
     */
    public function getInvoiceAmount()
    {
        return $this->invoice_amount;
    }

    /**
     * @param mixed $invoice_amount
     */
    public function setInvoiceAmount($invoice_amount)
    {
        $this->invoice_amount = $invoice_amount;
    }

    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->out_trade_no;
    }

    /**
     * @param mixed $out_trade_no
     */
    public function setOutTradeNo($out_trade_no)
    {
        $this->out_trade_no = $out_trade_no;
    }

    /**
     * @return mixed
     */
    public function getTradeStatus()
    {
        return $this->trade_status;
    }

    /**
     * @param mixed $trade_status
     */
    public function setTradeStatus($trade_status)
    {
        $this->trade_status = $trade_status;
    }

    /**
     * @return mixed
     */
    public function getGmtPayment()
    {
        return $this->gmt_payment;
    }

    /**
     * @param mixed $gmt_payment
     */
    public function setGmtPayment($gmt_payment)
    {
        $this->gmt_payment = $gmt_payment;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getPointAmount()
    {
        return $this->point_amount;
    }

    /**
     * @param mixed $point_amount
     */
    public function setPointAmount($point_amount)
    {
        $this->point_amount = $point_amount;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getGmtCreate()
    {
        return $this->gmt_create;
    }

    /**
     * @param mixed $gmt_create
     */
    public function setGmtCreate($gmt_create)
    {
        $this->gmt_create = $gmt_create;
    }

    /**
     * @return mixed
     */
    public function getBuyerPayAmount()
    {
        return $this->buyer_pay_amount;
    }

    /**
     * @param mixed $buyer_pay_amount
     */
    public function setBuyerPayAmount($buyer_pay_amount)
    {
        $this->buyer_pay_amount = $buyer_pay_amount;
    }

    /**
     * @return mixed
     */
    public function getReceiptAmount()
    {
        return $this->receipt_amount;
    }

    /**
     * @param mixed $receipt_amount
     */
    public function setReceiptAmount($receipt_amount)
    {
        $this->receipt_amount = $receipt_amount;
    }

    /**
     * @return mixed
     */
    public function getFundBillList()
    {
        return $this->fund_bill_list;
    }

    /**
     * @param mixed $fund_bill_list
     */
    public function setFundBillList($fund_bill_list)
    {
        $this->fund_bill_list = $fund_bill_list;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param mixed $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     * @param mixed $seller_id
     */
    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
    }

    /**
     * @return mixed
     */
    public function getNotifyId()
    {
        return $this->notify_id;
    }

    /**
     * @param mixed $notify_id
     */
    public function setNotifyId($notify_id)
    {
        $this->notify_id = $notify_id;
    }

    /**
     * @return mixed
     */
    public function getSellerEmail()
    {
        return $this->seller_email;
    }

    /**
     * @param mixed $seller_email
     */
    public function setSellerEmail($seller_email)
    {
        $this->seller_email = $seller_email;
    }


}