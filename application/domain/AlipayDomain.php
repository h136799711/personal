<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 10:59
 */

namespace app\domain;


use app\src\alipay\action\AlipayNotifyAction;
use app\src\alipay\po\AlipayNotfiyPo;

class AlipayDomain extends BaseDomain
{
    public function simulator(){

        addLog("Alipay_notify",$_GET,$_POST,"支付宝异步模拟通知");

        $alipayNotfiyPo = new AlipayNotfiyPo();

        $total_amount = $this->_post("total_amount","0.01");
        $out_trade_no = $this->_post("out_trade_no","11111"); 

        $alipayNotfiyPo->setTotalAmount($total_amount);
        $alipayNotfiyPo->setOutTradeNo($out_trade_no);

        $alipayNotfiyPo->setTradeStatus('TRADE_SUCCESS');
        $alipayNotfiyPo->setBuyerId('test');
        $alipayNotfiyPo->setBody('test');
        $alipayNotfiyPo->setTradeNo('TEST');
        $alipayNotfiyPo->setAuthAppId('');
        $alipayNotfiyPo->setNotifyTime(time());
        $alipayNotfiyPo->setSubject('');
        $alipayNotfiyPo->setSignType('rsa');
        $alipayNotfiyPo->setBuyerLogonId('');
        $alipayNotfiyPo->setCharset('utf-8');
        $alipayNotfiyPo->setNotifyType('trade_status_sync');
        $alipayNotfiyPo->setInvoiceAmount('0.01');
        $alipayNotfiyPo->setGmtPayment('');
        $alipayNotfiyPo->setVersion('1.0');
        $alipayNotfiyPo->setPointAmount('');
        $alipayNotfiyPo->setSign('');
        $alipayNotfiyPo->setGmtCreate('');
        $alipayNotfiyPo->setBuyerPayAmount('');
        $alipayNotfiyPo->setReceiptAmount('');
        $alipayNotfiyPo->setFundBillList('');
        $alipayNotfiyPo->setAppId('');
        $alipayNotfiyPo->setSellerId('');
        $alipayNotfiyPo->setNotifyId('');
        $alipayNotfiyPo->setSellerEmail('');

        $debug  = true;
        $action = new AlipayNotifyAction($alipayNotfiyPo); 
        $result = $action->notify($debug);

        $this->exitWhenError($result,true);
    }
}