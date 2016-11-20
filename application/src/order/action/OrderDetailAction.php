<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 13:46
 */

namespace app\src\order\action;


use app\src\base\action\BaseAction;
use app\src\base\helper\ValidateHelper;
use app\src\order\helper\OrdersBusinessStatusHelper;
use app\src\order\helper\OrdersTimeHelper;
use app\src\order\logic\OrdersContactinfoLogic;
use app\src\order\logic\OrdersExpressLogic;
use app\src\order\logic\OrdersItemLogic;
use app\src\order\logic\OrdersLogic;
use app\src\order\logic\OrdersPaycodeLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersContactinfo;
use app\src\order\model\OrdersExpress;
use app\src\order\model\OrdersPaycode;
use think\Db;

/**
 * Class OrderDetailAction
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\order\action
 */
class OrderDetailAction extends BaseAction
{
    public function detail($uid,$order_code){
        
        $result = (new OrdersLogic())->getInfoWithPublisherName(['orders.uid'=>$uid,'orders.order_code'=>$order_code]);

        if(!ValidateHelper::legalArrayResult($result)){
           return $this->error(lang('err_data_query'));
        }

        $order_info = $result['info'];
        $pay_code = $order_info['pay_code'];

        $result = (new OrdersItemLogic())->queryNoPaging(['order_code'=>$order_code]);

        if(!ValidateHelper::legalArrayResult($result)){
            return $this->error(lang('err_data_query'));
        }

        if(is_array($order_info)){
            $order_info['query_status'] = OrdersBusinessStatusHelper::convertQueryStatus($order_info);
        }

        if(is_array($order_info)){
            $order_info['_auto_op_time'] = OrdersTimeHelper::nextTime($order_info);
        }

        $order_info['items'] = [];
        foreach ($result['info'] as $item){
            array_push($order_info['items'],$item);
        }

        $map = ['order_code'=>$order_code];
        $result = (new OrdersContactinfoLogic())->getInfo($map);
//        $order_info['_address'] = [];

        if(!empty($result['info']) && is_array($result['info'])){
            $order_info['_address'] = $result['info'];
        }

//        $order_info['_express'] =  ["_id"=>"0"];
//        $order_info['_payinfo'] =  ["_id"=>"0"];

        //1. 获取物流信息
        $result = (new OrdersExpressLogic())->getInfo($map,false,"updatetime,expressno,expresscode,expressname");

        if(!empty($result['info']) && is_array($result['info'])){
            $order_info['_express'] = $result['info'];
        }

        //2. 获取支付信息
        $result = (new OrdersPaycodeLogic())->getInfo(['pay_code'=>$pay_code]);

        if(!empty($result['info']) && is_array($result['info'])){
            $order_info['_payinfo'] = $result['info'];
        }

        return $this->success($order_info);
    }
}