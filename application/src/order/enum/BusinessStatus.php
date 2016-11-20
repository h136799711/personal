<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 14:25
 */

namespace app\src\order\enum;

/**
 * 订单业务状态
 * Class BusinessStatus
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\order\enum
 */
class BusinessStatus
{
    /**
     * 未知状态
     */
    const UNKNOWN = 0;

    /**
     * 待付款
     */
    const WAIT_PAYING = 1;

    /**
     * 待发货
     * WAIT_SHIPPING
     *
     */
    const WAIT_SHIPPING = 2;

    /**
     * 待收货
     * WAIT_RECEIVED
     *
     */
    const WAIT_RECEIVED = 3;


    /**
     * 已收货
     * RECEIPT_OF_GOODS
     *
     */
    const RECEIPT_OF_GOODS = 4;

    /**
     * 退款/售后
     * AFTER_SALES
     *
     */
    const AFTER_SALES = 5;

    /**
     *
     * 已完成
     *
     */
    const COMPLETED = 7;

    /**
     * 关闭
     */
    const CLOSED = 8;

}