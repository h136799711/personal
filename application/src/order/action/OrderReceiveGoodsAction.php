<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 10:31
 */

namespace app\src\order\action;


use app\src\base\action\BaseAction;
use app\src\order\logic\OrdersLogic;
use app\src\order\model\Orders;

/**
 * Class OrderReceiveGoodsAction
 * @package app\src\order\action
 */
class OrderReceiveGoodsAction extends BaseAction
{
    /**
     * 确认收货
     * @param Orders $orders
     * @return array
     */
    public function receiveGoods(Orders $orders){
        $result = (new OrdersLogic())->receiveGoods($orders);
        return $this->result($result);
    }
}