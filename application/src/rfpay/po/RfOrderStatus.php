<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-28
 * Time: 9:37
 */

namespace app\src\rfpay\po;


class RfOrderStatus
{

    /**
     *  初始化
     */
    const INIT = "10";

    /**
     *  等待支付
     */
    const WAIT = "11";

    /**
     *  订单确认
     */
    const CONF = "12";

    /**
     *  支付成功
     */
    const SUCCESS = "20";


    /**
     *  支付失败
     */
    const FAIL = "21";

    /**
     *  订单风控
     */
    const FREEZE = "30";

    /**
     *  订单保留
     */
    const HOLD = "99";
    
}