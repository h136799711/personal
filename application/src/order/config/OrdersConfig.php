<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 14:33
 */
namespace app\src\order\config;

class OrdersConfig{

    /**
     * 获取未支付订单关闭的时间
     * 1 天
     * @author hebidu <email:346551990@qq.com>
     */
    public static function getAutoCloseTimeInterval(){
        return 24*3600;
    }

    /**
     * 获取自动收货时间
     * 30 天
     * @author hebidu <email:346551990@qq.com>
     */
//    public static function getAutoReceiveTimeInterval(){
//        return 30*24*3600;
//    }



}