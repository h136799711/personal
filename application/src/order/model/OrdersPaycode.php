<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 21:25
 */

namespace app\src\order\model;

use think\Model;

class OrdersPaycode extends Model
{
    /**
     * 支付宝支付方式
     * 1
     */
    const PAY_TYPE_ALIPAY = "1";
    /**
     * palpay 支付方式
     * 1
     */
    const PAY_TYPE_PALPAY = "2";


    /**
     * 未支付
     */
    const PAY_STATUS_NOT_PAYED = 0;

    /**
     * 已支付
     */
    const PAY_STATUS_PAYED = 1;

    /**
     * 正在支付
     */
    const PAY_STATUS_PAYING = 2;

}