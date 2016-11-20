<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 21:26
 */

namespace app\src\order\logic;


use app\src\base\logic\BaseLogic;
use app\src\base\utils\CodeGenerateUtils;
use app\src\order\model\OrdersPaycode;

class OrdersPaycodeLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new OrdersPaycode());
    }

    public function paySuccess($out_trade_no,$pay_type){

        $updateEntity = [
            'pay_type'=>$pay_type,
            'pay_status'=>OrdersPaycode::PAY_STATUS_PAYED
        ];

        $result = $this->save(['pay_code'=>$out_trade_no],$updateEntity);

        return $result;
    }

    /**
     * 获取支付信息
     * @param $uid integer 用户id
     * @param $orderCodes string 订单编号 逗号隔开
     * @param $payMoney  integer 支付金额(单位: 分)
     * @param $currency
     * @return array
     */
    public function getPayInfo($uid,$orderCodes,$payMoney,$currency){
        $order_content = implode(",",$orderCodes);
        $payCode = (new CodeGenerateUtils())->getAppPayCode($uid);
        $entity = [
            'order_content'=>$order_content,
            'createtime'=>time(),
            'uid'=>$uid,
            'pay_type'=>0,
            'pay_money'=>$payMoney,
            'pay_code'=>$payCode,
            'pay_balance'=>0,
            'pay_status'=>0,
            'pay_currency'=>$currency
        ];
        
        $result = $this->add($entity);
        if($result['status'] && $result['info'] > 0){
            $payInfo = ['pay_money'=>$payMoney ,'pay_code'=> $payCode];
            $sign = $this->sign($entity);
            $payInfo['sign'] = $sign;

            return $this->apiReturnSuc($payInfo);

        }

        return $this->apiReturnErr(lang('err_get_pay_info'));
    }

    private function sign($entity){
        $uid = $entity['uid'];
        $payMoney = $entity['pay_money'];
        $payCode = $entity['pay_code'];

        return md5(strval($uid).strval($payMoney).$payCode);
    }

}