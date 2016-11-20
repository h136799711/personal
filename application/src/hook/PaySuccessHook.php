<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 22:17
 */

namespace app\src\hook;

use app\src\base\helper\ValidateHelper;
use app\src\message\enum\MessageType;
use app\src\message\facade\MessageFacade;
use app\src\order\logic\OrdersLogic;
use app\src\order\logic\OrdersPaycodeLogic;
use app\src\order\model\OrdersPaycode;
use think\Db;

/**
 * 支付成功钩子
 * Class PaySuccessHook
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\afterpay\logic
 */
class PaySuccessHook
{

    /**
     * 支付完成回调
     * @param $seller_id
     * @param $total_fee
     * @param $out_trade_no
     */
    public function finished($seller_id,$total_fee,$out_trade_no){

    }

    /**
     * 支付成功回调
     * @param $seller_id
     * @param $total_fee
     * @param $out_trade_no
     * @param $currency string 货币单位
     * @return \app\src\base\logic\status|array|bool|void
     * @internal param $seller_id
     */
    public function success($seller_id,$total_fee,$out_trade_no,$currency){

        $payCodeLogic = new OrdersPaycodeLogic();

        $result = $payCodeLogic->getInfo(['pay_code'=>$out_trade_no]);
        if(ValidateHelper::legalArrayResult($result)){

            $payInfo = $result['info'];

            addLog("alipay/notify",$payInfo['pay_money'],$total_fee,"支付金额对比");

            if($payInfo['pay_money'] != $total_fee){
                //TODO： 2. 订单进入风控环节
//                echo "订单风控";
            }

            if($payInfo['pay_status'] != OrdersPaycode::PAY_STATUS_PAYED){

                Db::startTrans();
                $result = $payCodeLogic->paySuccess($out_trade_no,OrdersPaycode::PAY_TYPE_ALIPAY);

                //更新成功
                if($result['status']){
                    //1. 更新paycode表
                    //2. 更新order表 订单编号 逗号隔开的
                    $orderCodeArr = explode(",",$payInfo['order_content']);
                    $pay_info = [
                        'pay_type'=>OrdersPaycode::PAY_TYPE_ALIPAY,
                        'pay_balance'=>0,
                        'pay_code'=>$out_trade_no
                    ];

                    $ordersLogic = new OrdersLogic();
                    foreach ($orderCodeArr as $order_code){
                        if(empty($order_code)) continue;
                        
                        $result  = $ordersLogic->getInfo(['order_code'=>$order_code]);
                        if(!$result['status'] || empty($result['info'])){
                            Db::rollback();
                            return $result;
                        }

                        $result = $ordersLogic->paySuccess($result['info'],$pay_info);

                        if(!$result['status']){

                            Db::rollback();
                            return $result;
                        }
                    }

                    $this->sendNotification($payInfo['uid'],$payInfo['order_content']);

                    Db::commit();
                    return ['status'=>true,'info'=>lang('success')];

                }else{
                    Db::rollback();
                    return $result;
                }
            }
            else{
                return ['status'=>false,'info'=>lang('err_hook_pay_payed')];
            }
        }else{
            return ['status'=>false,'info'=>lang('err_hook_pay_no_trade_info')];
        }

    }

    
    
    public function fail(){
        
    }


    /**
     * 发送通知
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $orderContent
     */
    private function sendNotification($uid,$orderContent){
        //记入消息表
        $facade = new MessageFacade();
        $entity = [
            'uid'=> 0,
            'to_uid'=>$uid,
            'content'=> lang('tip_hook_pay_success_content',['content'=>$orderContent]),
            'title'=> lang('tip_hook_pay_success'),
            'summary'=> lang('tip_hook_pay_success_summary',['content'=>$orderContent]),
            'extra'=> '',
            'msg_type'=> MessageType::ORDER
        ];

        $facade->addMsg($entity);
        //TODO: 发短信
    }
}