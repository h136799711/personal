<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 11:00
 */

namespace app\src\alipay\action;


use app\src\alipay\po\AlipayNotfiyPo;
use app\src\base\action\BaseAction;
use app\src\hook\PaySuccessHook;
use app\src\order\enum\PayCurrency;
use app\src\alipay\logic\AlipayNotifyLogic;

class AlipayNotifyAction extends BaseAction
{
    private $alipay_config;
    private $alipayNotfiyPo;

    public function __construct(AlipayNotfiyPo $alipayNotfiyPo)
    {
        //引入支付官方sdk
        vendor("AlipayApp.lib.alipay_notify");
        $this->alipayNotfiyPo = $alipayNotfiyPo;

        $this->alipay_config = $this->getAlipayConfig();
    }

    public function notify($is_debug=false){
        $alipayNotify = new \AlipayNotify($this->alipay_config);
        if($is_debug){ //测试环境不验证
            $verify_result = true;
        }else{
            $verify_result = $alipayNotify->verifyNotify();
        }

        if($verify_result) {
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //商户订单号
            $out_trade_no =  $this->alipayNotfiyPo->getOutTradeNo();

            //支付宝交易号
            $trade_no =  $this->alipayNotfiyPo->getTradeNo();

            //交易状态
            $trade_status =  $this->alipayNotfiyPo->getTradeStatus();

            //支付金额
            $total_fee =  $this->alipayNotfiyPo->getTotalAmount();

            //seller_id
            $seller_id =  $this->alipayNotfiyPo->getSellerId();


            if($trade_status == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

                $this->log();
                $this->payFinished($seller_id,$total_fee,$out_trade_no);
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                $this->log();
                $result = $this->paySuccess($seller_id,$total_fee,$out_trade_no);

                if($result['status']){
                    return $this->success('success'); //请不要修改或删除
                } else{

                    if($is_debug){
                        $str = "信息:".$result['info'].',交易编号:'.$this->alipayNotfiyPo->getOutTradeNo();
                        return $this->error($str);
                    }

                    addLog("ALIPAY_NOTIFY_ACTION","错误信息:".$result['info'],'系统交易编号:'.$this->alipayNotfiyPo->getOutTradeNo(),"支付宝异步通知处理异常");
                }
            }


        }

        return $this->error('fail');
    }



    /**
     * 支付完成
     * @param $seller_id
     * @param $total_fee
     * @param $out_trade_no
     */
    private function payFinished($seller_id,$total_fee,$out_trade_no){
        (new PaySuccessHook())->finished($seller_id,$total_fee,$out_trade_no);
    }

    /**
     * 支付成功
     * @param $seller_id
     * @param $total_fee
     * @param $out_trade_no
     * @return \app\src\base\logic\status|array|bool|void
     */
    private function paySuccess($seller_id,$total_fee,$out_trade_no){

        //1. 对支付宝默认其货币为: 人民币
        $result =  (new PaySuccessHook())->success($seller_id,$total_fee,$out_trade_no,PayCurrency::RMB);

        return $result;
    }

    /**
     * 记录日志
     * @author hebidu <email:346551990@qq.com>
     */
    private function log(){
        $entity = array(
            'payment_type' => "",
            'subject' => $this->alipayNotfiyPo->getSubject(),
            'trade_no' => $this->alipayNotfiyPo->getTradeNo(),
            'buyer_email' => "",
            'gmt_create' => $this->alipayNotfiyPo->getSellerEmail(),
            'notify_type' => $this->alipayNotfiyPo->getNotifyType(),
            'quantity' => 0,
            'out_trade_no' => $this->alipayNotfiyPo->getOutTradeNo(),
            'seller_id' => $this->alipayNotfiyPo->getSellerId(),
            'notify_time' => $this->alipayNotfiyPo->getNotifyTime(),
            'body' => $this->alipayNotfiyPo->getBody(),
            'trade_status' => $this->alipayNotfiyPo->getTradeStatus(),
            'is_total_fee_adjust' => "",
            'total_fee' => $this->alipayNotfiyPo->getTotalAmount(),
            'seller_email' => $this->alipayNotfiyPo->getSellerEmail(),
            'use_coupon' => "",
            'buyer_id' => $this->alipayNotfiyPo->getBuyerId(),
            'notify_id' => $this->alipayNotfiyPo->getNotifyId(),
            'price' => $this->alipayNotfiyPo->getTotalAmount(),
            'sign_type' => $this->alipayNotfiyPo->getSignType(),
            'sign' => $this->alipayNotfiyPo->getSign(),
            'gmt_payment' => $this->alipayNotfiyPo->getGmtPayment()
        );

        $result = (new AlipayNotifyLogic())->add($entity);

        if(!$result['status']){
            LogRecord($result['info'],__FILE__.__LINE__);
        }

    }


    private function getAlipayConfig(){

        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者id，以2088开头的16位纯数字
//        $alipay_config['partner']		= '2088211347135986';
        $alipay_config['partner']		= '2088102178836020';

        //商户的私钥（后缀是.pen）文件相对路径
        $alipay_config['private_key_path']	= APP_PATH.'src/alipay/key/test/rsa_private_key.pem';

        //支付宝公钥（后缀是.pen）文件相对路径
        $alipay_config['ali_public_key_path']= APP_PATH.'src/alipay/key/test/alipay_public_key.pem';

        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

        //签名方式 不需修改
        $alipay_config['sign_type']    = strtoupper('RSA');

        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset']= strtolower('utf-8');

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = getcwd().'\\cacert.pem';

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport']    = 'http';

        return  $alipay_config;
    }
}