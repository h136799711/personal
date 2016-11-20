<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 11:57
 */

namespace app\src\order\logic;


use app\src\base\logic\BaseLogic;
use app\src\order\model\OrdersItem;

class OrdersItemLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new OrdersItem());
    }
}