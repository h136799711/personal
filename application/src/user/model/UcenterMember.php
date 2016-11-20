<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-15
 * Time: 17:10
 */

namespace app\src\user\model;


use app\src\base\helper\ConfigHelper;
use think\Model;

class UcenterMember extends Model
{
    /**
     * 手机注册的
     * 3
     */
    const ACCOUNT_TYPE_MOBILE  = "3";
    /**
     * 用户名注册
     * 1
     */
    const ACCOUNT_TYPE_USERNAME  = "1";

    protected $auto   = ['last_login_time','last_login_ip'];
    protected $insert = ['status' => 1 ,'reg_time'=>0, 'reg_ip'=>0 ];
    protected $update = ['update_time' ];
    
    protected function setUpdateTimeAttr()
    {
        return time();
    }

    protected function setLastLoginIpAttr()
    {
        return ip2long(request()->ip());
    }

    protected function setLastLoginTimeAttr()
    {
        return time();
    }

    protected function setRegIpAttr()
    {
        return ip2long(request()->ip());
    }


    protected function setRegTimeAttr()
    {
        return time();
    }

}