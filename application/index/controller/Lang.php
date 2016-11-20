<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-31
 * Time: 13:53
 */

namespace app\index\controller;


use app\src\base\helper\ConfigHelper;
use think\controller\Rest;

class Lang extends Rest
{
    public function support(){
        return ( ConfigHelper::getLangSupport());
    }
}