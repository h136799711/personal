<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-19
 * Time: 14:46
 */

namespace app\src\order\action;


use app\src\base\action\BaseAction;
use app\src\order\logic\OrdersLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersExpress;

/**
 * Class OrderShippedAction
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\order\action
 */
class OrderShippedAction extends BaseAction
{
    /*
     *
     * @author hebidu <email:346551990@qq.com>
     */
    public function shipped(Orders $orders,OrdersExpress $ordersExpress){
        $result = (new OrdersLogic())->shipped($orders,$ordersExpress);
        return $this->result($result);
    }
}