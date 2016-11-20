<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 11:37
 */

namespace app\src\order\helper;


use app\src\base\exception\BusinessException;
use app\src\order\enum\BusinessStatus;
use app\src\order\model\Orders;

class OrdersBusinessStatusHelper
{
    static public function analysisQueryStatus($query_status){

        //1=>待付款
        if($query_status == 1){
            return ['order_status'=>Orders::ORDER_TOBE_CONFIRMED,'pay_status'=>Orders::ORDER_TOBE_PAID];
        }

        //2=>待发货
        if($query_status == 2){
            return ['order_status'=>Orders::ORDER_TOBE_SHIPPED.','.Orders::ORDER_TOBE_CONFIRMED,'pay_status'=>Orders::ORDER_PAID];
        }

        //3=>待收货
        if($query_status == 3){
            return ['order_status'=>Orders::ORDER_SHIPPED,'pay_status'=>Orders::ORDER_PAID];
        }

        //4=>已收货
        if($query_status == 4){
            return ['order_status'=>Orders::ORDER_RECEIPT_OF_GOODS,'pay_status'=>Orders::ORDER_PAID];
        }

        //5=>退款/售后
//        if($query_status == 5){
//            return ['order_status'=>Orders::ORDER_TOBE_CONFIRMED,'pay_status'=>Orders::ORDER_TOBE_PAID];
//        }
//        //6=>待评价
//        if($query_status == 6){
//            return ['order_status'=>Orders::ORDER_TOBE_CONFIRMED,'pay_status'=>Orders::ORDER_TOBE_PAID];
//        }

        //7=>已完成
        if($query_status == 7){
            return ['order_status'=>Orders::ORDER_COMPLETED,'pay_status'=>Orders::ORDER_PAID];
        }

        return ['order_status'=>'','pay_status'=>''];
    }
    
    static public function convertQueryStatus($item){
        $order_status = $item['order_status'];
        $pay_status   = $item['pay_status'];

        if($order_status == Orders::ORDER_TOBE_CONFIRMED && $pay_status ==  Orders::ORDER_TOBE_PAID){
            return BusinessStatus::WAIT_PAYING;
        }

        //2=>待发货
        if(($order_status == Orders::ORDER_TOBE_CONFIRMED || $order_status == Orders::ORDER_TOBE_SHIPPED ) && $pay_status ==  Orders::ORDER_PAID){
            return BusinessStatus::WAIT_SHIPPING;
        }

        //3=>待收货
        if($order_status == Orders::ORDER_SHIPPED && $pay_status ==  Orders::ORDER_PAID){
            return BusinessStatus::WAIT_RECEIVED;
        }

        //4=>已收货
        if($order_status == Orders::ORDER_RECEIPT_OF_GOODS && $pay_status ==  Orders::ORDER_PAID){
            return BusinessStatus::RECEIPT_OF_GOODS;
        }


        //7=>已完成
        if($order_status == Orders::ORDER_COMPLETED && $pay_status ==  Orders::ORDER_PAID){
            return BusinessStatus::COMPLETED;
        }
        
        //8. 已关闭
        if($order_status == Orders::ORDER_CANCEL){
            return BusinessStatus::CLOSED;
        }
    
        return BusinessStatus::UNKNOWN;
    }

}