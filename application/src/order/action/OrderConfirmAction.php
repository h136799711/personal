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
class OrderConfirmAction extends BaseAction
{
    /**
     * 确认收货
     * @param Orders $orders
     * @return array
     */
    public function confirm(Orders $orders){
        $result = (new OrdersLogic())->confirm($orders);
        return $this->result($result);
    }
}