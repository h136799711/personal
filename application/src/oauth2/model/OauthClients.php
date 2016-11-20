<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-12
 * Time: 11:25
 */

namespace app\src\oauth2\model;

use think\Model;

class OauthClients extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oauth_clients';
    
}