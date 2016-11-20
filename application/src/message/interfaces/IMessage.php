<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 14:07
 */

namespace app\src\message\interfaces;

/**
 * 消息接口
 * Interface IMessage
 * @package app\src\message\interfaces
 */
interface IMessage
{
    /**
     * 创建消息对象
     * @return mixed
     */
    function create();

    /**
     * 消息发送
     * @return mixed
     */
    function send();
}