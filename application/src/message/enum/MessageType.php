<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 15:11
 */

namespace app\src\message\enum;


class MessageType
{
    /**
     * 推送消息
     */
    const PUSH = "6010";

    /**
     * 系统消息
     */
    const SYSTEM = "6010";

    /**
     * 订单消息
     */
    const ORDER = "6043";

    /**
     * 物流消息
     */
    const LOGISTICS = "6048";

    /**
     * 其它消息
     */
    const OTHER = "6074";

    /**
     * 私信
     */
    const PERSONAL_LETTER = "6078";

}