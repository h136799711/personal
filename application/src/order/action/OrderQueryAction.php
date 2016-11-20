<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 9:16
 */

namespace app\src\order\action;


use app\src\base\action\BaseAction;
use app\src\base\helper\PageHelper;
use app\src\base\helper\ValidateHelper;
use app\src\order\logic\OrdersItemLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersItem;
use think\Db;

/**
 * Class OrderQueryAction
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\order\action
 */
class OrderQueryAction extends BaseAction
{
    /**
     * 订单查询
     * @author hebidu <email:346551990@qq.com>
     * @param $keyword
     * @param Orders $orders
     * @param PageHelper $pageHelper
     * @return array
     */
    public function query($keyword,Orders $orders,PageHelper $pageHelper){
        $search_keyword = empty($keyword) ? false : true;

        $uid = $orders->getUid();
        $orderStatus = $orders->getOrderStatus();
        $payStatus = $orders->getPayStatus();

        $query = Db::table("itboye_orders")->alias("orders")
            ->field("orders.*,m.nickname as publisher_name")
            ->join("itboye_store as store",'store.id = orders.storeid')
            ->join(["member m","common_"],"m.uid = store.uid")
            ->where('orders.uid',$uid);

        if($search_keyword){
            $query->distinct(true)->join("itboye_orders_item as item","item.order_code = orders.order_code","left")
                ->where(['item.name'=>['like','%'.$keyword.'%']]);
        }

        if(strlen($orderStatus) > 0) {
            if(strpos($orderStatus,",") > -1){
                $query->where("orders.order_status", 'in',$orderStatus);
            }else{
                $query->where("orders.order_status", $orderStatus);
            }
        }

        if(strlen($payStatus) > 0){
            $query->where("orders.pay_status",$payStatus);
        }

        $result = $query->limit($pageHelper->getOffset(),$pageHelper->getPageSize())->select();

        $ordersCodes = [];
        if(is_array($result)){
            foreach ($result as $item){
                array_push($ordersCodes,$item['order_code']);
            }

            $map = [
                'order_code'=>['in', implode(",",$ordersCodes)]
            ];

            $orderItemsResult = (new OrdersItemLogic())->queryNoPaging($map);

            if(ValidateHelper::legalArrayResult($orderItemsResult)){
                $result = $this->addOrderItem($result,$orderItemsResult['info']);
            }
        }

        $query = Db::table("itboye_orders")->field("orders.order_code")->alias("orders")
            ->where('orders.uid',$uid);

        if($search_keyword){
            $query->join("itboye_orders_item as item","item.order_code = orders.order_code","left")
                ->where(['item.name'=>['like','%'.$keyword.'%']]);
        }

        if(strlen($orderStatus) > 0) {
            //多个状态的时候
            if(strpos($orderStatus,",") > -1){
                $query->where("orders.order_status", 'in',$orderStatus);
            }else{
                $query->where("orders.order_status", $orderStatus);
            }
        }

        if(strlen($orderStatus) > 0) {
            $query->where("orders.pay_status",$payStatus);
        }

        $count = $query->count("distinct(orders.order_code)");

        return $this->success( ['count'=>$count,'list'=>$result]);
    }

    /**
     * 向订单数据 添加订单项
     * @param $order
     * @param $orderItems
     */
    private function addOrderItem($order,$orderItems){

        $tmp = [];
        foreach ($orderItems as $vo){
            if(!isset($tmp[$vo['order_code']])){
                $tmp[$vo['order_code']] = [];
            }
            array_push($tmp[$vo['order_code']],$vo);
        }

        foreach ($order as &$item){
            $item['items'] = $tmp[$item['order_code']];
        }

        return $order;
    }

}