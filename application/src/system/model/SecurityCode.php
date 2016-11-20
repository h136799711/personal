<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 11:17
 */

namespace app\src\system\model;


use think\Model;

class SecurityCode extends Model
{

    /**
     * 注册
     */
    const TYPE_FOR_REGISTER = 1;

    /**
     * 更新密码
     */
    const TYPE_FOR_UPDATE_PSW = 2;

    /**
     * 绑定手机号,之前未绑定过
     */
    const TYPE_FOR_NEW_BIND_PHONE = 3;

    /**
     * 更换手机号,
     */
    const TYPE_FOR_CHANGE_NEW_PHONE = 4;

    /**
     * 用于登录
     */
    const TYPE_FOR_LOGIN = 5;


    public static function getTypeDesc($type)
    {
        switch ($type) {
            case SecurityCode::TYPE_FOR_CHANGE_NEW_PHONE:
                return "更换手机";
            case SecurityCode::TYPE_FOR_NEW_BIND_PHONE:
                return "绑定新手机";
            case SecurityCode::TYPE_FOR_REGISTER:
                return "注册";
            case SecurityCode::TYPE_FOR_UPDATE_PSW:
                return "更新密码";
            case SecurityCode::TYPE_FOR_LOGIN:
                return "登录";
            default:
                return "未知";
        }
    }

}