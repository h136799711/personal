<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 14:24
 */

namespace app\src\order\helper;


use app\src\order\config\OrdersConfig;
use app\src\order\enum\BusinessStatus;

class OrdersTimeHelper
{

    /**
     * 获取该订单下一次自动操作的时间
     * @param $order_info
     * @return int
     */
    public static function nextTime($order_info){
        
        $status = OrdersBusinessStatusHelper::convertQueryStatus($order_info);
        $now = time();
        $updateTime = $order_info['updatetime'];
        
        if($status == BusinessStatus::WAIT_PAYING){
            $timeInterval = OrdersConfig::getAutoCloseTimeInterval();

            return $updateTime + $timeInterval > $now ? 0: $updateTime + $timeInterval;
        }

        
        return 0;
    }

}